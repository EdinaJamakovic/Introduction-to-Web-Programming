<?php
/**
 * @OA\Get(
 *     path="/reviews",
 *     summary="Get all reviews",
 *     tags={"Reviews"},
 *     @OA\Response(response="200", description="List of all reviews"),
 *     @OA\Response(response="500", description="Server error")
 * )
 */
Flight::route('GET /reviews', function() {
    $service = new ReviewService();
    Flight::json($service->getAll());
});

/**
 * @OA\Get(
 *     path="/reviews/{id}",
 *     summary="Get a review by ID",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Review details"),
 *     @OA\Response(response="404", description="Review not found")
 * )
 */
Flight::route('GET /reviews/@id', function($id) {
    $service = new ReviewService();
    Flight::json($service->getById($id));
});

/**
 * @OA\Get(
 *     path="/reviews/doctor/{id}",
 *     summary="Get reviews by doctor ID",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="List of doctor's reviews"),
 *     @OA\Response(response="404", description="Doctor not found")
 * )
 */
Flight::route('GET /reviews/doctor/@id', function($id) {
    $service = new ReviewService();
    Flight::json($service->getByDoctorId($id));
});

/**
 * @OA\Post(
 *     path="/reviews",
 *     summary="Create a new review",
 *     tags={"Reviews"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="doctor_id", type="integer"),
 *             @OA\Property(property="patient_id", type="integer"),
 *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5),
 *             @OA\Property(property="comment", type="string")
 *         )
 *     ),
 *     @OA\Response(response="201", description="Review created"),
 *     @OA\Response(response="400", description="Invalid input")
 * )
 */
Flight::route('POST /reviews', function() {
    $service = new ReviewService();
    $data = Flight::request()->data->getData();
    Flight::json($service->create($data));
});

/**
 * @OA\Put(
 *     path="/reviews/{id}",
 *     summary="Update a review",
 *     tags={"Reviews"},
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
 *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5),
 *             @OA\Property(property="comment", type="string")
 *         )
 *     ),
 *     @OA\Response(response="200", description="Review updated"),
 *     @OA\Response(response="404", description="Review not found")
 * )
 */
Flight::route('PUT /reviews/@id', function($id) {
    $service = new ReviewService();
    $data = Flight::request()->data->getData();
    Flight::json($service->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/reviews/{id}",
 *     summary="Delete a review",
 *     tags={"Reviews"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Review deleted"),
 *     @OA\Response(response="404", description="Review not found")
 * )
 */
Flight::route('DELETE /reviews/@id', function($id) {
    $service = new ReviewService();
    Flight::json($service->delete($id));
});
?>