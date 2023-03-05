<?php 
    include "headers.php";

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
        
    }

    $json = isset($_POST["json"]) ? $_POST["json"] : "0";
    $operation = isset($_POST["operation"]) ? $_POST["operation"] : "0";

    $user = new User();

    switch($operation){
        case "login":
            echo $user->login($json);
            break;
    }
?>