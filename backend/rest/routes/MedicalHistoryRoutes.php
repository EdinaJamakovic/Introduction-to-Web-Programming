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
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
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
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
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
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::PATIENT]);
    $currentUser = Flight::get('user');
    
    if ($currentUser['role'] === Roles::PATIENT && $currentUser['id'] != $id) {
        Flight::halt(403, "Can only view your own records");
    }
    
    $service = new MedicalHistoryService();
    Flight::json($service->getForPatient($id));
});

/**
 * @OA\Post(
 *     path="/medical-history",
 *     summary="Create a new medical record",
 *     tags={"Medical History"},
 *     security={{"ApiKey": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"appointment_id", "diagnosis", "treatment"},
 *             @OA\Property(property="appointment_id", type="integer", description="ID of the completed appointment"),
 *             @OA\Property(property="diagnosis", type="string"),
 *             @OA\Property(property="treatment", type="string"),
 *             @OA\Property(property="notes", type="string"),
 *             @OA\Property(property="prescriptions", type="array", @OA\Items(type="string"))
 *         )
 *     ),
 *     @OA\Response(response="201", description="Medical record created and appointment marked as completed"),
 *     @OA\Response(response="400", description="Invalid input or appointment not found"),
 *     @OA\Response(response="403", description="Unauthorized - doctor doesn't have this appointment"),
 *     @OA\Response(response="500", description="Server error")
 * )
 */
Flight::route('POST /medical-history', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN, Roles::DOCTOR);
    $currentUser = Flight::get('user');
    $data = Flight::request()->data->getData();

    if (!isset($data['appointment_id'])) {
        Flight::halt(400, "Appointment ID is required");
    }

    $appointmentService = new AppointmentService();
    $appointment = $appointmentService->getById($data['appointment_id']);

    if (!$appointment) {
        Flight::halt(400, "Appointment not found");
    }

    if ($currentUser['role'] === Roles::DOCTOR && $currentUser['id'] != $appointment['doctor_id']) {
        Flight::halt(403, "You are not assigned to this appointment");
    }

    $medicalHistoryService = new MedicalHistoryService();
    
    try {
        $record = $medicalHistoryService->create($data);
        
        $appointmentService->update($data['appointment_id'], [
            'status' => 'completed'
        ]);
        
        Flight::json([
            'medical_record' => $record,
            'appointment' => $appointmentService->getById($data['appointment_id'])
        ], 201);
        
    } catch (Exception $e) {
        Flight::halt(500, "Failed to create medical record: " . $e->getMessage());
    }
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
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
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
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $service = new MedicalHistoryService();
    Flight::json($service->delete($id));
});
?>