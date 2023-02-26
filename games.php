<?php 
    include "headers.php";

    class User{
        function getGames(){
            include "connection.php";

            $sql = "SELECT * FROM tblgames ORDER BY game_id";

            $stmt = $conn->prepare($sql);
            $returnValue = 0;

            if($stmt->execute()){
                if($stmt->rowCount() > 0){
                    $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $returnValue = json_encode($rs);
                }
            }
            return $returnValue;
        }

        function selectGame($json){
            include "connection.php";

            $json = json_decode($json, true);
            $gameId = $json["gameId"];

            $sql = "SELECT * FROM tblgames WHERE game_id=:gameId";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":gameId", $gameId);

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
        case "getGames":
            echo $user->getGames();
            break;
        case "selectGame":
            echo $user->selectGame($json);
            break;
    }
?>