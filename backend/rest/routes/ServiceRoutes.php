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
    $service = new ServicesService();
    Flight::json($service->getAll());
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
    $service = new ServicesService();
    Flight::json($service->getById($id));
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
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="description", type="string")
 *         )
 *     ),
 *     @OA\Response(response="201", description="Service created"),
 *     @OA\Response(response="400", description="Invalid input")
 * )
 */
Flight::route('POST /services', function() {
    $service = new ServicesService();
    $data = Flight::request()->data->getData();
    Flight::json($service->create($data));
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
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="description", type="string")
 *         )
 *     ),
 *     @OA\Response(response="200", description="Service updated"),
 *     @OA\Response(response="404", description="Service not found")
 * )
 */
Flight::route('PUT /services/@id', function($id) {
    $service = new ServicesService();
    $data = Flight::request()->data->getData();
    Flight::json($service->update($id, $data));
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
 *     @OA\Response(response="404", description="Service not found")
 * )
 */
Flight::route('DELETE /services/@id', function($id) {
    $service = new ServicesService();
    Flight::json($service->delete($id));
});
?>