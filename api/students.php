<?php 
    // include "headers.php";
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");

    class User{
        function login($json){
            include "connection.php";
            // {"schoolId":"123"}
            $json = json_decode($json, true);
            $schoolId = $json["schoolId"];
            $sql = "SELECT * FROM tblstudents WHERE stud_schoolId=:schoolId ";// AND stud_active = 1";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":schoolId", $schoolId);

            $returnValue = 0;

            if($stmt->execute()){
                if($stmt->rowCount() > 0){
                    $rs = $stmt->fetch(PDO::FETCH_ASSOC);
                    $returnValue = json_encode($rs);
                }
            }
            return $returnValue;
        }
      
        function register($json){
            include "connection.php";
            //{"nickName":"Pitok","schoolId":"02-1617-05810","name":"Pitok Batolata", "course":"BSIT"}
            $json = json_decode($json, true);
      
            if($this->schoolIdExist($json["schoolId"], $conn)){
              return -1;
            }
      
            $sql = "INSERT INTO tblstudents(stud_schoolId, stud_name, stud_nickName, stud_course) ";
            $sql .= "VALUES(:schoolId, :name, :nickName, :course)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":nickName", $json["nickName"]);
            $stmt->bindParam(":name", $json["name"]);
            $stmt->bindParam(":schoolId", $json["schoolId"]);
            $stmt->bindParam(":course", $json["course"]);
            $stmt->execute();
            $returnValue = $stmt->rowCount() > 0 ? 1 : 0;
            $stmt = null;
            $conn = null;
            return $returnValue;      
        }
      
        function schoolIdExist($schoolId, $myConn){
            $sql = "SELECT stud_id FROM tblstudents WHERE stud_schoolId = :schoolId";
            $stmt = $myConn->prepare($sql);
            $stmt->bindParam(":schoolId", $schoolId);
            $stmt->execute();
            $returnValue = $stmt->rowCount() > 0 ? true : false;
            return $returnValue;
        }
  
        function addNickName($json){
            // {"schoolId":"02-1617-05810", "nickName":"Joe"}
            include "connection.php";

            $json = json_decode($json, true);
            $nickName = $json["nickName"];
            $schoolId = $json["schoolId"];
            $sql = "UPDATE tblstudents ";
            $sql .= "SET stud_nickName = :nickName ";
            $sql .= "WHERE stud_schoolId = :schoolId";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":nickName", $nickName);
            $stmt->bindParam(":schoolId", $schoolId);
            $returnValue = 0;

            if($stmt->execute()){
                if($stmt->rowCount() > 0){
                    $returnValue = 1;
                }
            }
            return $returnValue;
        }
    }

    $json = isset($_POST["json"]) ? $_POST["json"] : "0";
    $operation = isset($_POST["operation"]) ? $_POST["operation"] : "0";

    $user = new User();

    switch($operation){
        case "login":
            echo $user->login($json);
            break;
        case "addNickName":
            echo $user->addNickName($json);
            break;
        case "register":
            echo $user->register($json);
            break;
    }
?>