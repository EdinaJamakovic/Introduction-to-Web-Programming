<?php
/**
 * @OA\Get(
 *     path="/medical-history",
 *     summary="Get all medical history records",
 *     tags={"Medical History"},
 *     @OA\Response(response="200", description="List of medical records"),
 *     @OA\Response(response="500", description="Server error")
 * )
 */
Flight::route('GET /medical-history', function() {
    $service = new MedicalHistoryService();
    Flight::json($service->getAll());
});

/**
 * @OA\Get(
 *     path="/medical-history/{id}",
 *     summary="Get a medical record by ID",
 *     tags={"Medical History"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Medical record details"),
 *     @OA\Response(response="404", description="Record not found")
 * )
 */
Flight::route('GET /medical-history/@id', function($id) {
    $service = new MedicalHistoryService();
    Flight::json($service->getById($id));
});

/**
 * @OA\Get(
 *     path="/medical-history/patient/{id}",
 *     summary="Get medical history for a patient",
 *     tags={"Medical History"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="List of patient's medical records"),
 *     @OA\Response(response="404", description="Patient not found")
 * )
 */
Flight::route('GET /medical-history/patient/@id', function($id) {
    $service = new MedicalHistoryService();
    Flight::json($service->getForPatient($id));
});

/**
 * @OA\Post(
 *     path="/medical-history",
 *     summary="Create a new medical record",
 *     tags={"Medical History"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="patient_id", type="integer"),
 *             @OA\Property(property="diagnosis", type="string"),
 *             @OA\Property(property="treatment", type="string")
 *         )
 *     ),
 *     @OA\Response(response="201", description="Medical record created"),
 *     @OA\Response(response="400", description="Invalid input")
 * )
 */
Flight::route('POST /medical-history', function() {
    $service = new MedicalHistoryService();
    $data = Flight::request()->data->getData();
    Flight::json($service->create($data));
});

/**
 * @OA\Put(
 *     path="/medical-history/{id}",
 *     summary="Update a medical record",
 *     tags={"Medical History"},
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
 *             @OA\Property(property="diagnosis", type="string"),
 *             @OA\Property(property="treatment", type="string")
 *         )
 *     ),
 *     @OA\Response(response="200", description="Medical record updated"),
 *     @OA\Response(response="404", description="Record not found")
 * )
 */
Flight::route('PUT /medical-history/@id', function($id) {
    $service = new MedicalHistoryService();
    $data = Flight::request()->data->getData();
    Flight::json($service->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/medical-history/{id}",
 *     summary="Delete a medical record",
 *     tags={"Medical History"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Medical record deleted"),
 *     @OA\Response(response="404", description="Record not found")
 * )
 */
Flight::route('DELETE /medical-history/@id', function($id) {
    $service = new MedicalHistoryService();
    Flight::json($service->delete($id));
});
?>