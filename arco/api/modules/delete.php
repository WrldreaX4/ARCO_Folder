<?php
class Delete extends GlobalMethods{
    private $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function delete_reports($id){
        $query = "DELETE FROM reports WHERE report_id = ?";
        try{
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([
                $id
            ]);
            return $this->sendResponse("Success", null, 200);
        } catch(PDOException $e){
            return $this->sendResponse("Failed", $e->getMessage(), 500); 

        }
        }
}
?>