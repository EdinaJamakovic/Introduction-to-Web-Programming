<?php
require_once "BaseService.php";
require_once "./rest/dao/UserDao.php";

class UserService extends BaseService {

    public function __construct(){
        $dao = new UserDao();
        parent::__construct($dao);
    }

    public function create($data){
        $errors = $this->validateInput($data, true);

        $data['password_hash'] = password_hash($data['password_hash'], PASSWORD_DEFAULT);

    if (!empty($errors)) {
        throw new Exception("Validation failed: " . implode(", ", $errors));
    }

    try {
        return $this->dao->insert($data);
    } catch (Exception $e) {
        throw new Exception("Insert failed: " . $e->getMessage());
    }

    return true;

    }

    public function update($id, $data){
        $errors = $this->validateInput($data, false);

        if (!empty($errors)) {
            throw new Exception("Validation failed: " . implode(", ", $errors));
        }
    
        try {
            if(isset($data['password_hash'])){
                $data['password_hash'] = password_hash($data['password_hash'], PASSWORD_DEFAULT);
            }
            return $this->dao->update($id, $data);
        } catch (Exception $e) {
            throw new Exception("Update failed: " . $e->getMessage());
        }
    }

    public function login($email, $password) {
        $emailPattern = "/^[\w\.\-]+@[\w\-]+\.[a-z]{2,6}$/i";

        if (empty($email) || empty($password)) {
            throw new Exception("Email and password are required.");
        }
    
        if (!preg_match($emailPattern, $email)) {
            throw new Exception("Invalid email format.");
        }
    
        try {
            $user = $this->dao->getByEmail($email);
    
            if (!$user || !password_verify($password, $user['password_hash'])) {
                throw new Exception("Invalid email or password.");
            }
         
            return $user;
    
        } catch (Exception $e) {
            throw new Exception("Login failed: " . $e->getMessage());
        }
    }
    

    public function validateInput($data, $isCreate = true) {
        $errors = [];
    
        $emailPattern = "/^[\w\.\-]+@[\w\-]+\.[a-z]{2,6}$/i";
        $passwordPattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/";
        $phonePattern = "/^\+?\d{7,15}$/";
        $namePattern = "/^[a-zA-Z'-]+$/";
    
        if ($isCreate && !isset($data['email'])) {
            $errors[] = "Email is required.";
        } elseif (isset($data['email']) && !preg_match($emailPattern, $data['email'])) {
            $errors[] = "Invalid email address.";
        }
    
        if ($isCreate && !isset($data['password_hash'])) {
            $errors[] = "Password is required.";
        } elseif (isset($data['password_hash']) && !preg_match($passwordPattern, $data['password_hash'])) {
            $errors[] = "Password must be at least 8 characters long, contain at least one uppercase letter, one lowercase letter, and one number.";
        }
    
        if (isset($data['phone']) && !preg_match($phonePattern, $data['phone'])) {
            $errors[] = "Invalid phone number.";
        }
    
        if ($isCreate && !isset($data['first_name'])){
            $errors[] = "First name is required";
        } elseif(isset($data['first_name']) && !preg_match($namePattern, $data['first_name'])) {
            $errors[] = "First name contains illegal characters or is empty.";
        }

        if($isCreate && !isset($data['last_name'])){
            $errors[] = "Last name is required.";
        } elseif(isset($data['last_name']) && !preg_match($namePattern, $data['last_name'])) {
            $errors[] = "Last name contains illegal characters or is empty.";
        }
    
        return $errors;
    }
    
    
}

    


?>