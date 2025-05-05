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
    $service = new UserService();
    Flight::json($service->getAll());
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
    $service = new UserService();
    Flight::json($service->getById($id));
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
    $service = new UserService();
    $data = Flight::request()->data->getData();
    try {
        Flight::json($service->create($data));
    } catch (Exception $e) {
        Flight::halt(400, $e->getMessage());
    }
});

/**
 * @OA\Post(
 *     path="/users/login",
 *     summary="User login",
 *     tags={"Users"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string", format="email"),
 *             @OA\Property(property="password", type="string", format="password")
 *         )
 *     ),
 *     @OA\Response(response="200", description="Login successful"),
 *     @OA\Response(response="401", description="Invalid credentials")
 * )
 */
Flight::route('POST /users/login', function() {
    $service = new UserService();
    $data = Flight::request()->data->getData();
    try {
        Flight::json($service->login($data['email'], $data['password']));
    } catch (Exception $e) {
        Flight::halt(401, $e->getMessage());
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
    $service = new UserService();
    $data = Flight::request()->data->getData();
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
    $service = new UserService();
    Flight::json($service->delete($id));
});
?>