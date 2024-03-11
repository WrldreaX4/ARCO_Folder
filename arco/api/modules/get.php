<?php

require_once "global.php";


class Get extends GlobalMethods{

    private $pdo;

    public function __construct(\PDO $pdo){
        $this->pdo = $pdo;
    }

    public function get_records($table, $condition=null){
        $sqlString ="SELECT * FROM $table";
        if($condition != null){
            $sqlString = "WHERE" .$condition;
        }
        $result = $this->executeQuery($sqlString);

        if($result['code']==200){
            return $this->sendPayload($result['data'], 'success', 'Sucessfully retrieved records', $result['code']);
        }
            return $this->sendPayload(null, 'failed', 'Failed retrieved records', $result['code']);

    }

    public function executeQuery($sql){
        $data = array();
        $errmsg = "";
        $code = 0;

         try{
            if($result = $this->pdo->query($sql)->fetchAll()){
                foreach($result as $record){
                    array_push($data, $record);
                }
                $code = 200;
                $result = null;
                return array("code"=>$code, "data"=>$data);
            }
            else{
                $errmsg = "No Data Found";
                $code = 404;
            }
         }
         catch(\PDOException $e){
            $errmsg = $e->getMessage();
            $code = 403;
         }

         return array("code"=>$code, "errmsg"=>$errmsg);
    }
    
    public function get_signup($id=null){
        $conditionString = null;
        if($id != null){
            $conditionString = "user_id=$id";
        }
        return $this->get_records("users", $conditionString);
    }

    //data to be changed to user_id, will do once testing phase is over
    public function get_flipbook($data) {
        $sqlString = "SELECT u.username, r.title, r.description, r.date_created, c.collage_desc, f.* 
        FROM users u
        JOIN reports r ON u.user_id = r.user_id 
        JOIN flipbook f ON r.report_id = f.report_id 
        JOIN collage c ON f.collage_id = c.collage_id 
        WHERE f.flipbook_id = :flipbook_id";
        
    
        $stmt = $this->pdo->prepare($sqlString);
        $stmt->bindParam(':flipbook_id', $data);
        $stmt->execute();
    
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        if ($result) {
            return $this->getResponse($result, "success", null, 200);
        } else {
            return $this->getResponse(null, "failed", "Failed retrieved records", 404);
        }
    }

        //data to be changed to user_id, will do once testing phase is over
        public function get_flipbookall() {
            $sqlString = "SELECT u.username, r.title, r.description, c.collage_desc, f.* 
            FROM users u
            JOIN reports r ON u.user_id = r.user_id 
            JOIN flipbook f ON r.report_id = f.report_id 
            JOIN collage c ON f.collage_id = c.collage_id";
            
        
            $stmt = $this->pdo->prepare($sqlString);
            $stmt->execute();
        
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
            if ($result) {
                return $this->getResponse($result, "success", null, 200);
            } else {
                return $this->getResponse(null, "failed", "Failed retrieved records", 404);
            }
        }

    //data to be changed to user_id, will do once testing phase is over
    public function get_collage($data){
        $sqlString = "SELECT * FROM collage WHERE collage_id = ?";
        $stmt = $this->pdo->prepare($sqlString);
        $stmt->bindParam(1, $data);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if($result > 0){
            return $this->getResponse($result, "Success", null, 200);
        }else{
            return $this->getResponse(null, "Failed", "Failed to retrieve the table", 404);
        }
          
    }

    //data to be changed to user_id, will do once testing phase is over
    public function get_reports($data){
        $sqlString = "SELECT * FROM reports WHERE report_id = ?";
        $stmt = $this->pdo->prepare($sqlString);
        $stmt->bindParam(1, $data);
        $stmt->execute();

        $result = $stmt-> fetchAll(PDO:: FETCH_ASSOC);

        if(!empty($result)){
            return $this->getResponse($result, "Success", null, 200);
        } else{
            return $this->getResponse(null, "Failed", "Failed to retrieve", 404);
        }
    }

    public function get_reportsall(){
        $sqlString = "SELECT * FROM reports";
        $stmt = $this->pdo->prepare($sqlString);
        $stmt->execute();

        $result = $stmt-> fetchAll(PDO:: FETCH_ASSOC);

        if(!empty($result)){
            return $this->getResponse($result, "Success", null, 200);
        } else{
            return $this->getResponse(null, "Failed", "Failed to retrieve", 404);
        }
    }

}


?>