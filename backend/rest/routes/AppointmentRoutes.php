<?php
require_once __DIR__ . '/../../data/roles.php';
/**
 * @OA\Get(
 *     path="/appointments",
 *     summary="Get all appointments",
 *     tags={"Appointments"},
 *     @OA\Response(response="200", description="List of appointments"),
 *     @OA\Response(response="500", description="Server error")
 * )
 */
Flight::route('GET /appointments', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $service = new AppointmentService();
    Flight::json($service->getAll());
});

/**
 * @OA\Get(
 *     path="/appointments/free",
 *     summary="Get all available (free) appointments",
 *     tags={"Appointments"},
 *     @OA\Response(response="200", description="List of free appointments"),
 *     @OA\Response(response="500", description="Server error")
 * )
 */
Flight::route('GET /appointments/free', function() {
   $auth = new AuthMiddleware();
 
    // Then check for allowed roles
        Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::PATIENT, Roles::DOCTOR]);

    // Proceed with the request
    $service = new AppointmentService();
    Flight::json([
        'success' => true,
        'data' => $service->getFreeAppointments()
    ]);
});

/**
 * @OA\Get(
 *     path="/appointments/{id}",
 *     summary="Get an appointment by ID",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Appointment details"),
 *     @OA\Response(response="404", description="Appointment not found")
 * )
 */
Flight::route('GET /appointments/@id', function($id) { //POZIVA OVU RUTU
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $service = new AppointmentService();
    Flight::json($service->getById($id));
});

/**
 * @OA\Get(
 *     path="/appointments/patient/{id}",
 *     summary="Get appointments for a patient",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="List of patient's appointments"),
 *     @OA\Response(response="404", description="Patient not found")
 * )
 */
Flight::route('GET /appointments/patient/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::PATIENT, Roles::ADMIN]);
    $service = new AppointmentService();
    Flight::json($service->getByPatientId($id));
});

/**
 * @OA\Get(
 *     path="/appointments/doctor/{id}/{status}",
 *     summary="Get appointments for a doctor by status",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="status",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string", enum={"confirmed", "pending", "cancelled"})
 *     ),
 *     @OA\Response(response="200", description="List of doctor's appointments"),
 *     @OA\Response(response="404", description="Doctor not found")
 * )
 */
Flight::route('GET /appointments/doctor/@id/@status', function($id, $status) {
    Flight::auth_middleware()->authorizeRole(Roles::DOCTOR, Roles::ADMIN);
    $service = new AppointmentService();
    Flight::json($service->getByDoctorId($id, $status));
});

/**
 * @OA\Get(
 *     path="/appointments/doctor/{id}/free",
 *     summary="Get free appointments for a specific doctor",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response="200",
 *         description="List of available (free) appointments for the doctor"
 *     ),
 *     @OA\Response(
 *         response="404",
 *         description="Doctor not found"
 *     ),
 *     @OA\Response(
 *         response="403",
 *         description="Unauthorized access"
 *     )
 * )
 */
Flight::route('GET /appointments/doctor/@id/free', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::PATIENT, Roles::ADMIN, Roles::DOCTOR);
    $service = new AppointmentService();
    Flight::json($service->getByDoctorId($id, 'free'));
});

/**
 * @OA\Post(
 *     path="/appointments",
 *     summary="Create a new appointment",
 *     tags={"Appointments"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="patient_id", type="integer"),
 *             @OA\Property(property="doctor_id", type="integer"),
 *             @OA\Property(property="date", type="string", format="date"),
 *             @OA\Property(property="time", type="string", format="time"),
 *             @OA\Property(property="service", type="string")
 *         )
 *     ),
 *     @OA\Response(response="201", description="Appointment created"),
 *     @OA\Response(response="400", description="Invalid input")
 * )
 */
Flight::route('POST /appointments', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $service = new AppointmentService();
    $data = Flight::request()->data->getData();
    Flight::json($service->create($data));
});

/**
 * @OA\Put(
 *     path="/appointments/{id}",
 *     summary="Update an appointment",
 *     tags={"Appointments"},
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
 *             @OA\Property(property="status", type="string", enum={"confirmed", "cancelled"})
 *         )
 *     ),
 *     @OA\Response(response="200", description="Appointment updated"),
 *     @OA\Response(response="404", description="Appointment not found")
 * )
 */
Flight::route('PUT /appointments/@id', function($id) {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::DOCTOR, Roles::PATIENT]);
    $service = new AppointmentService();
    $data = Flight::request()->data->getData();
    Flight::json($service->update($id, $data));
});

/**
 * @OA\Delete(
 *     path="/appointments/{id}",
 *     summary="Delete an appointment",
 *     tags={"Appointments"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="200", description="Appointment deleted"),
 *     @OA\Response(response="404", description="Appointment not found")
 * )
 */
Flight::route('DELETE /appointments/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $service = new AppointmentService();
    Flight::json($service->delete($id));
});
?>