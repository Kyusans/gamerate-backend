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

        function getGameResult(){
            include "connection.php";

            $sql = "SELECT * FROM tblgames ORDER BY game_stars DESC";

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

        function addStar($json){
            include "connection.php";
            // {"schoolId":"123", "gameId":"1", "stars":"5"}

            $json = json_decode($json, true);

            $gameId = $json["gameId"];
            $schoolId = $json["schoolId"];
            $stars = $json["stars"];

            $sql = "INSERT INTO tblrating(rate_gameId, rate_schoolId, rate_rating) ";
            $sql .= "VALUES(:gameId, :schoolId, :stars)";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam("gameId", $gameId);
            $stmt->bindParam("schoolId", $schoolId);
            $stmt->bindParam("stars", $stars);

            $returnValue = 0;

            if($stmt->execute()){
                if($stmt->rowCount() > 0){
                    $sql2 = "UPDATE tblgames SET game_stars = game_stars + :stars WHERE game_id = :gameId";
                    $stmt2 = $conn->prepare($sql2);

                    $stmt2->bindParam(":stars", $stars);
                    $stmt2->bindParam(":gameId", $gameId);

                    $stmt2->execute();
                    $returnValue = 1;
                }
            }

            return $returnValue;
        }

        function getStudentRate($json){
            include "connection.php";
            // {"schoolId":"123", "gameId":"1"}

            $json = json_decode($json, true);
            
            $schoolId = $json["schoolId"];
            $gameId = $json["gameId"];

            $sql = "SELECT rate_rating FROM tblrating WHERE rate_schoolId = :schoolId AND rate_gameId = :gameId";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":schoolId", $schoolId);
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
        case "getGameResult":
            echo $user->getGameResult();
            break;
        case "addStar":
            echo $user->addStar($json);
            break;
        case "getStudentRate":
            echo $user->getStudentRate($json);
            break;
    }
?>