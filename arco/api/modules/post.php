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

        
        public function report($data) {

            // Check if the necessary data is provided
            if (!isset($data->report_id) || !isset($data->title) || !isset($data->description) || !isset($data->time_created) || !isset($data->user_id)) {
                return $this->sendResponse("Missing fields", null, 400);
            }
        
            try {
                // Prepare SQL statement to insert the report into the database
                $stmt = $this->pdo->prepare("INSERT INTO reports (report_id, title, description, time_created, user_id) 
                                            VALUES (:report_id, :title, :description, :time_created, :user_id)");
                $stmt->execute([
                    'report_id' => $data->report_id,
                    'title' => $data->title,
                    'description' => $data->description,
                    'time_created' => $data->time_created,
                    'user_id' => $data->user_id
                ]);
        
                return $this->sendResponse("Report generated successfully", null, 200);
            } catch (\PDOException $e) {
                return $this->sendResponse("Failed to generate report", $e->getMessage(), 500);
            }
        }




















    }


    





    ?>
