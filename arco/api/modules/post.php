    <?php
    class Post extends GlobalMethods {

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




        function insertReport($data, $user_id) {
            // Check for required fields
            if (!isset($data->title) || !isset($data->description)) {
                return $this->sendResponse("Missing fields", null, 400);
            }
        
            try {
                // Prepare and execute the SQL statement
                $stmt = $this->pdo->prepare("INSERT INTO reports (title, description, date_created, user_id) 
                                            VALUES (:title, :description, NOW(), :user_id)");
                $stmt->execute([
                    'title' => $data->title,
                    'description' => $data->description,
                    'user_id' => $user_id 
                ]);
        
                // Get the ID of the newly inserted report
                $lastInsertId = $this->pdo->lastInsertId(); 
        
                return $this->sendResponse("Report generated successfully", null, 200);
            } catch (\PDOException $e) {

                return $this->sendResponse("Failed to generate report. Please try again later.", null, 500);
            }
        }


        function flipbook($data, $user_id) {
            // Check for required fields
            // if (!isset($data->reportid) || !isset($data->collageid)) {
            //     return $this->sendResponse("Missing fields", null, 400);
            // }
            
        
            try {
                // Prepare and execute the SQL statement
                $stmt = $this->pdo->prepare("INSERT INTO flipbook (user_id, report_id, collage_id) 
                                            VALUES (:user_id, :report_id, :collage_id)");
                $stmt->execute([
                    'user_id' => $user_id,
                    'report_id' => $data->report_id,
                    'collage_id' => $data->collage_id
                    
                ]);
        
                // Get the ID of the newly inserted report
                $lastInsertId = $this->pdo->lastInsertId(); 
        
                return $this->sendResponse("Report generated successfully", null, 200);
            } catch (\PDOException $e) {

                return $this->sendResponse("Failed to generate report. Please try again later.", null, 500);
            }
        }
        
    
    
    
    }

    ?>
