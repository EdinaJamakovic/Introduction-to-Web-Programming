<?php
require_once "BaseService.php";
require_once "./rest/dao/ReviewDao.php";

class ReviewService extends BaseService {
    public function __construct() {
        $dao = new ReviewDao();
        parent::__construct($dao);
    }

    public function getByDoctorId($id) {
        if (!is_numeric($id)) {
            throw new Exception("Doctor ID must be numeric", 400);
        }
        return $this->dao->getByDoctorId($id);
    }

    public function create($data) {
        $requiredFields = ['doctor_id', 'patient_id', 'rating', 'comment'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                throw new Exception("Missing required field: $field", 400);
            }
        }

        if (!is_numeric($data['doctor_id']) || !is_numeric($data['patient_id'])) {
            throw new Exception("Doctor and Patient IDs must be numeric", 400);
        }

        if (!is_numeric($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5) {
            throw new Exception("Rating must be between 1 and 5", 400);
        }

        if (strlen($data['comment']) > 500) {
            throw new Exception("Comment must be less than 500 characters", 400);
        }

        return parent::create($data);
    }

    public function update($id, $data) {
        if (!is_numeric($id)) {
            throw new Exception("Review ID must be numeric", 400);
        }

        if (isset($data['rating']) && (!is_numeric($data['rating']) || $data['rating'] < 1 || $data['rating'] > 5)) {
            throw new Exception("Rating must be between 1 and 5", 400);
        }

        if (isset($data['comment']) && strlen($data['comment']) > 500) {
            throw new Exception("Comment must be less than 500 characters", 400);
        }

        return parent::update($id, $data);
    }
}
?>