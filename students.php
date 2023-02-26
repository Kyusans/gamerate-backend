<?php 
    include "headers.php";

    class User{
        function login($json){
            include "connection.php";

            $json = json_decode($json, true);

            $schoolId = $json["schoolId"];
            $password = $json["password"];

            $sql = "SELECT * FROM tblstudents WHERE stud_schoolId=:schoolId AND stud_password=:password";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":schoolId", $schoolId);
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

        function signup($json){

            // {"schoolId":"123","password":"kobi123"}

            include "connection.php";

            $json = json_decode($json, true);

            $schoolId = $json["schoolId"];
            $password = $json["password"];

            $sql = "INSERT INTO tblstudents(stud_schoolId, stud_password) ";
            $sql .= "VALUES(:schoolId, :password)";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam(":schoolId", $schoolId);
            $stmt->bindParam(":password", $password);

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
        case "signup":
            echo $user->signup($json);
            break;
    }
?>