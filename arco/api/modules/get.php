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
    /**
     * Retrieve a list of employees.
     *
     * @return string
     *   A string representing the list of employees.
     */
    public function get_signup($id=null){
        $conditionString = null;
        if($id != null){
            $conditionString = "user_id=$id";
        }
        return $this->get_records("users", $conditionString);
    }

    /**
     * Retrieve a list of jobs.
     *
     * @return string
     *   A string representing the list of jobs.
     */
}


?>