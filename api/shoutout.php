<?php
    header('Content-Type: application/json');
    header("Access-Control-Allow-Origin: *");

  class ShoutOut{
    function getShoutOuts($json){
      include "connection.php";
      //{"limit":2} - number of random shoutouts to be returned

      $json = json_decode($json, true);
      $sql = "SELECT * FROM tblshoutout ORDER BY RAND() LIMIT {$json['limit']}";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $returnValue=$stmt->rowCount() > 0 ? $stmt->fetchAll(PDO::FETCH_ASSOC) : 0;
      $stmt = null;
      $conn = null;
      return json_encode($returnValue);
    }

    function saveShoutOut($json){
      include "connection.php";
      //{"nickName":"Pitok","schoolId":"02-1617-05810","shoutOut":"World Hello!"}
      $json = json_decode($json, true);
      $sql = "INSERT INTO tblshoutout(sh_schoolId, sh_nickName, sh_shoutOut) ";
      $sql .= "VALUES(:schoolId, :nickName, :shoutOut)";
      $stmt = $conn->prepare($sql);
      $stmt->bindParam(":schoolId", $json["schoolId"]);
      $stmt->bindParam(":nickName", $json["nickName"]);
      $stmt->bindParam(":shoutOut", $json["shoutOut"]);
      $stmt->execute();
      $returnValue = $stmt->rowCount() > 0 ? 1 : 0;
      $stmt = null;
      $conn = null;
      return $returnValue;      
    }

  }

  $operation = isset($_POST["operation"]) ? $_POST["operation"] : "invalid";
  $json  = isset($_POST["json"]) ? $_POST["json"] : "";

  $shoutOut = new ShoutOut();
  switch ($operation){
    case "getShoutOuts":
      echo $shoutOut->getShoutOuts($json);
      break;
    case "saveShoutOut":
      echo $shoutOut->saveShoutOut($json);
      break;
  }

?>