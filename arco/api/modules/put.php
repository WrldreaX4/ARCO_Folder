<?php
class Put extends GlobalMethods{
    private $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function edit_reports($data, $id){
        try{
            $query = "UPDATE reports SET title=?, description=? WHERE report_id=?";
            //will add userid for authorization in the future
            $stmt = $this->pdo->prepare($query);
            $result = $stmt->execute([
               'title' => $data->title,
                'description' => $data->description,
                'report_id' => $id
            ]);
            return $this->sendResponse("Success", null, 200);

        } catch(PDOException $e) {
            return $this->sendResponse("Failed", $e->getMessage(), 500); 
        }
    }



}

?>