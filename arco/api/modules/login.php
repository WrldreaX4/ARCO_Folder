<?php 

  class login extends Post {
    
      private $pdo;
  
      public function __construct(\PDO $pdo){
          $this->pdo = $pdo;
      }

      public function login($data) {
        
        try {
            if (empty($data->email) || empty($data->password)) {
                throw new Exception("All input fields are required!");
            }
            
            // Prepare and execute the query to fetch user information based on email
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(':email', $data->email);
            $stmt->execute();
            
            // Fetch the result
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($user) {
                
                if (password_verify($data->password, $user['password'])) {
                    
                    //password_hash
                    $password_hash = password_hash($data->password, PASSWORD_DEFAULT);
                    $stmt = $this->pdo->prepare("INSERT INTO users (email, password) 
                               VALUES (:email, :password_hash)");

                    // Passwords match, login successful
                    session_start();
                    $_SESSION['user_id'] = $user['user_id'];

                    return $this->sendResponse("Login successful", null, 200);
                } else {
                    // Passwords do not match
                    return $this->sendResponse("Email or password is incorrect", null, 401);
                }
            } else {
                // User not found
                return $this->sendResponse("User not found", null, 404);
            }
        } catch (\Exception $e) {
            return $this->sendResponse("Failed to login", $e->getMessage(), 400);
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