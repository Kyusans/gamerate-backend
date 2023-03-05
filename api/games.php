<?php 
    include "headers.php";

    class User{
        function getGames(){
            include "connection.php";

            $sql = "SELECT * FROM tblgames ORDER BY RAND()";

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

            // $sql = "SELECT * FROM tblgames ORDER BY game_stars DESC";

            $sql = "SELECT a.game_id, a.game_name, a.game_letter, sum(b.rate_rating) as totalStars ";
            $sql .= "FROM tblgames a LEFT JOIN tblrating b ";
            $sql .= "ON a.game_id = b.rate_gameId ";
            $sql .= "GROUP BY a.game_name, a.game_letter, a.game_id ";
            $sql .= "ORDER BY totalStars DESC";

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

            if ($this->hasRated($schoolId, $gameId, $conn)){
                return 3;
            }

            $sql = "INSERT INTO tblrating(rate_gameId, rate_schoolId, rate_rating) ";
            $sql .= "VALUES(:gameId, :schoolId, :stars)";

            $stmt = $conn->prepare($sql);

            $stmt->bindParam("gameId", $gameId);
            $stmt->bindParam("schoolId", $schoolId);
            $stmt->bindParam("stars", $stars);

            $stmt->execute();

            $returnValue = $stmt->rowCount() > 0 ? 1 : 0;

            // if($stmt->execute()){
            //     if($stmt->rowCount() > 0){
            //         $sql2 = "UPDATE tblgames SET game_stars = game_stars + :stars WHERE game_id = :gameId";
            //         $stmt2 = $conn->prepare($sql2);

            //         $stmt2->bindParam(":stars", $stars);
            //         $stmt2->bindParam(":gameId", $gameId);

            //         $stmt2->execute();
            //         $returnValue = 1;
            //     }
            // }

            return $returnValue;
        }

        function hasRated($schoolId, $gameId, $myConn){
            $sql = "SELECT rate_rating FROM tblrating WHERE rate_schoolId = :schoolId AND rate_gameId = :gameId";

            $stmt = $myConn->prepare($sql);
            $stmt->bindParam(":schoolId", $schoolId);
            $stmt->bindParam(":gameId", $gameId);
            $stmt->execute();

            $returnValue = $stmt->rowCount() > 0 ? true : false;

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

        function getDevs($json){
            include "connection.php";

            $json = json_decode($json, true);
            $gameId = $json["gameId"];

            $sql = "SELECT tbldevs.dev_name ";
            $sql .= "FROM tbldevs INNER JOIN tblgames ";
            $sql .= "ON tbldevs.dev_gameId = tblgames.game_id ";
            $sql .= "WHERE tblgames.game_Id = :gameId";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":gameId", $gameId);
            $stmt->execute();
            $returnValue = 0;
            if($stmt->rowCount() > 0){
                $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $returnValue = json_encode($rs);
            }
            
            return $returnValue;
        }

        function getImage($json){
            include "connection.php";

            $json = json_decode($json, true);
            $gameId = $json["gameId"];

            $sql = "SELECT tblimage.img_image ";
            $sql .= "FROM tblimage INNER JOIN tblgames ";
            $sql .= "ON tblimage.img_gameId = tblgames.game_id ";
            $sql .= "WHERE tblgames.game_Id = :gameId";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":gameId", $gameId);
            $stmt->execute();
            $returnValue = 0;
            if($stmt->rowCount() > 0){
                $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $returnValue = json_encode($rs);
            }
            
            return $returnValue;
        }

        function getSettings(){
            include "connection.php";

            $sql = "SELECT * FROM tblsettings";

            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $returnValue = 0;
            if($stmt->rowCount() > 0){
                $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $returnValue = json_encode($rs);
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
        case "getDevs":
            echo $user->getDevs($json);
            break;
        case "getImage":
            echo $user->getImage($json);
            break;
        case "getSettings":
            echo $user->getSettings();
            break;
    }
?>