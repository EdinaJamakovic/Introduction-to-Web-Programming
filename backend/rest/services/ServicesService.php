<?php
require_once "BaseService.php";
require_once "./rest/dao/ServicesDao.php";

class ServicesService extends BaseService{
    public function __construct(){
        $dao = new ServicesDao();
        parent::__construct($dao);
    }

    public function create($data) {

        if (empty($data['title']) || empty($data['description'])) {
            throw new Exception("Title and description are required", 400);
        }

        $serviceData = [
            'title' => $data['title'],
            'description' => $data['description'],
            'image_url' => $data['image_url'] ?? null
        ];

        try {
            $result = $this->dao->insert($serviceData);
            if ($result) {
                return ["message" => "Service created successfully"];
            } else {
                throw new Exception("Failed to create service", 500);
            }
        } catch (Exception $e) {
            throw new Exception("Error creating service: " . $e->getMessage(), 500);
        }
    }

    public function update($id, $data) {
        $existingService = $this->dao->getById($id);
        if (!$existingService) {
            throw new Exception("Service not found", 404);
        }

        $updateData = [];
        if (!empty($data['title'])) {
            $updateData['title'] = $data['title'];
        }
        if (!empty($data['description'])) {
            $updateData['description'] = $data['description'];
        }
        if (isset($data['image_url'])) {
            $updateData['image_url'] = $data['image_url'];
        }

        if (empty($updateData)) {
            throw new Exception("No valid fields to update", 400);
        }

        try {
            $result = $this->dao->update($id, $updateData);
            if ($result) {
                return ["message" => "Service updated successfully"];
            } else {
                throw new Exception("Failed to update service", 500);
            }
        } catch (Exception $e) {
            throw new Exception("Error updating service: " . $e->getMessage(), 500);
        }
    }

    public function delete($id) {
        $existingService = $this->dao->getById($id);
        if (!$existingService) {
            throw new Exception("Service not found", 404);
        }

        try {
            $result = $this->dao->delete($id);
            if ($result) {
                return ["message" => "Service deleted successfully"];
            } else {
                throw new Exception("Failed to delete service", 500);
            }
        } catch (Exception $e) {
            throw new Exception("Error deleting service: " . $e->getMessage(), 500);
        }
    }

    public function getAll() {
        try {
            return $this->dao->getAll();
        } catch (Exception $e) {
            throw new Exception("Error fetching services: " . $e->getMessage(), 500);
        }
    }

    public function getById($id) {
        try {
            $service = $this->dao->getById($id);
            if (!$service) {
                throw new Exception("Service not found", 404);
            }
            return $service;
        } catch (Exception $e) {
            throw new Exception("Error fetching service: " . $e->getMessage(), 500);
        }
    }
}