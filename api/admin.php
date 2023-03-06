<?php 
    //include "headers.php";
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");
    
    class User{
        function login($json){
            include "connection.php";
            // {"adminId":"kobi", "password":"kobi123"}
            $json = json_decode($json, true);

            $adminId = $json["adminId"];
            $password = $json["password"];

            $sql = "SELECT * FROM tbladmin WHERE adm_adminId=:adminId AND adm_password=:password";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":adminId", $adminId);
            $stmt->bindParam(":password", $password);

            $returnValue = 0;

            if($stmt->execute()){
                if($stmt->rowCount() > 0){
                    $rs = $stmt->fetch(PDO::FETCH_ASSOC);
                    $returnValue = json_encode($rs);
                }
            }

            return $returnValue;
        }
        function activate($json){
            include "connection.php";
            //{"studId":1}
            $json = json_decode($json, true);
            
            $sql = "UPDATE tblstudents SET stud_active = 1 ";
            $sql .= "WHERE stud_id = :studId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":studId", $json['studId']);
            $stmt->execute();
            $returnValue = $stmt->rowCount() > 0 ? 1 : 0;
            $stmt = null; $conn = null;
            return $returnValue;
        }
        function getInactiveStudents(){
            include "connection.php";
           
            $sql = "SELECT * FROM tblstudents WHERE stud_active = 0 ORDER BY stud_name";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $returnValue=$stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_ASSOC) : 0;
            $stmt = null;
            $conn = null;
            return json_encode($returnValue);
        }
    }

    $json = isset($_POST["json"]) ? $_POST["json"] : "0";
    $operation = isset($_POST["operation"]) ? $_POST["operation"] : "0";

    $user = new User();

    switch($operation){
        case "login":
            echo $user->login($json);
            break;
        case "activate":
            echo $user->activate($json);
            break;
        case "getInactiveStudents":
            echo $user->getInactiveStudents();
            break;
    }
?>