<?php
/**
 * @OA\Get(
 *     path="/services",
 *     summary="Get all services",
 *     tags={"Services"},
 *     @OA\Response(response="200", description="List of services"),
 *     @OA\Response(response="500", description="Server error")
 * )
 */
Flight::route('GET /services', function() {
    try {
        Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::PATIENT, Roles::DOCTOR]);
        $service = new ServicesService();
        Flight::json($service->getAll());
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});

/**
 * @OA\Get(
 *     path="/services/{id}",
 *     summary="Get a service by ID",
 *     tags={"Services"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Service details"),
 *     @OA\Response(response="404", description="Service not found")
 * )
 */
Flight::route('GET /services/@id', function($id) {
    try {
        Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::PATIENT, Roles::DOCTOR]);
        $service = new ServicesService();
        Flight::json($service->getById($id));
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});

/**
 * @OA\Post(
 *     path="/services",
 *     summary="Create a new service",
 *     tags={"Services"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="image_url", type="string")
 *         )
 *     ),
 *     @OA\Response(response="201", description="Service created"),
 *     @OA\Response(response="400", description="Invalid input"),
 *     @OA\Response(response="403", description="Unauthorized")
 * )
 */
Flight::route('POST /services', function() {
    try {
        Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::DOCTOR]);
        $service = new ServicesService();
        $data = Flight::request()->data->getData();
        $result = $service->create($data);
        Flight::json($result, 201);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});

/**
 * @OA\Put(
 *     path="/services/{id}",
 *     summary="Update a service",
 *     tags={"Services"},
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
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="description", type="string"),
 *             @OA\Property(property="image_url", type="string")
 *         )
 *     ),
 *     @OA\Response(response="200", description="Service updated"),
 *     @OA\Response(response="404", description="Service not found"),
 *     @OA\Response(response="403", description="Unauthorized")
 * )
 */
Flight::route('PUT /services/@id', function($id) {
    try {
        Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::DOCTOR]);
        $service = new ServicesService();
        $data = Flight::request()->data->getData();
        $result = $service->update($id, $data);
        Flight::json($result);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});

/**
 * @OA\Delete(
 *     path="/services/{id}",
 *     summary="Delete a service",
 *     tags={"Services"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Service deleted"),
 *     @OA\Response(response="404", description="Service not found"),
 *     @OA\Response(response="403", description="Unauthorized")
 * )
 */
Flight::route('DELETE /services/@id', function($id) {
    try {
        Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::DOCTOR]);
        $service = new ServicesService();
        $result = $service->delete($id);
        Flight::json($result);
    } catch (Exception $e) {
        Flight::json(['error' => $e->getMessage()], $e->getCode() ?: 500);
    }
});