<?php

class signup extends Post {

   private $pdo;

    public function __construct(\PDO $pdo){
        $this->pdo = $pdo;
    }

    public function signup($data) {

        if (!isset($data->username) || !isset($data->email) || !isset($data->password)) {
            return $this->sendResponse("Missing fields", null, 400);
        }

        $stmt = $this->pdo->prepare("SELECT user_id FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $data->username, 'email' => $data->email]);

        if ($stmt->rowCount() > 0) {
            return $this->sendResponse("Username or email already exists", null, 400);
        }

        else{

        $password_hash = password_hash($data->password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password) 
                               VALUES (:username, :email, :password_hash)");
        }

        try {
            $stmt->execute([
                'username' => $data->username,
                'email' => $data->email,
                'password_hash' => $password_hash
            ]);

            return $this->sendResponse("Signup successful", null, 200);
        } catch (\PDOException $e) {
            return $this->sendResponse("Failed to signup", $e->getMessage(), 500);
        }
    }

    private function sendResponse($message, $error, $statusCode) {
        $response = [
            'message' => $message
        ];
        if ($error !== null) {
            $response['error'] = $error;
        }
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
?>