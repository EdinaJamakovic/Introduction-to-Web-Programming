<?php
/**
 * @OA\Get(
 *     path="/users",
 *     summary="Get all users",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Response(response="200", description="List of users"),
 *     @OA\Response(response="401", description="Unauthorized"),
 *     @OA\Response(response="500", description="Server error")
 * )
 */
Flight::route('GET /users', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $service = new AuthService();
    Flight::json($service->getAll());
});

// Add to UserRoutes.php
/**
 * @OA\Get(
 *     path="/users/doctors",
 *     summary="Get all doctors",
 *     tags={"Users"},
 *     @OA\Response(response="200", description="List of all doctors"),
 *     @OA\Response(response="500", description="Server error")
 * )
 */
Flight::route('GET /users/doctors', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::DOCTOR, Roles::PATIENT]);
    $service = new AuthService();
    Flight::json($service->getAllDoctors());
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     summary="Get user by ID",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="User details"),
 *     @OA\Response(response="401", description="Unauthorized"),
 *     @OA\Response(response="404", description="User not found")
 * )
 */
Flight::route('GET /users/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN, Roles::DOCTOR, Roles::PATIENT);
    $currentUser = Flight::get('user');
    $targetUser = (new AuthService())->getById($id);
    
    if ($currentUser['id'] == $id) {
        return Flight::json($targetUser);
    }
    
    if ($currentUser['role'] === Roles::ADMIN) {
        return Flight::json($targetUser);
    }
        
    Flight::halt(403, "Unauthorized");
});

/**
 * @OA\Post(
 *     path="/users",
 *     summary="Create a new user",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"email", "password", "first_name", "last_name"},
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="password", type="string", format="password", minLength=8),
 *             @OA\Property(property="first_name", type="string"),
 *             @OA\Property(property="last_name", type="string"),
 *             @OA\Property(property="role", type="string", enum={"patient", "doctor", "admin"})
 *         )
 *     ),
 *     @OA\Response(response="201", description="User created"),
 *     @OA\Response(response="400", description="Invalid input")
 * )
 */
Flight::route('POST /users', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $service = new AuthService();
    $data = Flight::request()->data->getData();
    try {
        Flight::json($service->create($data));
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     summary="Update user information",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="first_name", type="string"),
 *             @OA\Property(property="last_name", type="string"),
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="phone", type="string")
 *         )
 *     ),
 *     @OA\Response(response="200", description="User updated"),
 *     @OA\Response(response="400", description="Invalid input"),
 *     @OA\Response(response="401", description="Unauthorized"),
 *     @OA\Response(response="404", description="User not found")
 * )
 */
Flight::route('PUT /users/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN, Roles::PATIENT, Roles::DOCTOR);
    $currentUser = Flight::get('user');
    $data = Flight::request()->data->getData();
    
    if ($currentUser['id'] != $id && $currentUser['role'] !== Roles::ADMIN) {
        Flight::halt(403, "You can only update your own profile");
    }
    
    if (isset($data['role']) && $currentUser['role'] !== Roles::ADMIN) {
        unset($data['role']);
    }
    
    $service = new AuthService();
    try {
        Flight::json($service->update($id, $data));
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     summary="Delete a user",
 *     tags={"Users"},
 *     security={{"ApiKey": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="User deleted"),
 *     @OA\Response(response="401", description="Unauthorized"),
 *     @OA\Response(response="404", description="User not found")
 * )
 */
Flight::route('DELETE /users/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $service = new AuthService();
    Flight::json($service->delete($id));
});

