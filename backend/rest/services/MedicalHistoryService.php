<?php
require_once "BaseService.php";
require_once "./rest/dao/MedicalHistoryDao.php";

class MedicalHistoryService extends BaseService {
    protected $dao;

    public function __construct() {
        $dao = new MedicalHistoryDao();
        parent::__construct($dao);
    }

    public function getForPatient($id) {
        if (!is_numeric($id)) {
            throw new Exception("Patient ID must be numeric", 400);
        }
        return $this->dao->getByPatientId($id);
    }

    public function create($data) {
        $requiredFields = ['appointment_id', 'diagnosis', 'treatment'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Missing required field: $field", 400);
            }
        }

        if (!is_numeric($data['appointment_id'])) {
            throw new Exception("Appointment ID must be numeric", 400);
        }

        if (isset($data['prescriptions']) && !is_array($data['prescriptions'])) {
            throw new Exception("Prescriptions must be an array", 400);
        }

        return parent::create($data);
    }

    public function update($id, $data) {
        if (!is_numeric($id)) {
            throw new Exception("Record ID must be numeric", 400);
        }

        if (empty($data['diagnosis']) && empty($data['treatment'])) {
            throw new Exception("At least diagnosis or treatment must be provided", 400);
        }

        return parent::update($id, $data);
    }
}
?>