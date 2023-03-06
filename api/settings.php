<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

class Settings {

  function getRatingStatus(){
    include "connection.php";

    $sql = "SELECT * FROM tblsettings WHERE set_key = 'status'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $returnValue = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC)['set_value'] : 0;
    $stmt = null; $conn = null;
    return json_encode($returnValue);
  }

  function setRatingStatus($json){
    include "connection.php";
    //{"status":1}
    $json = json_decode($json, true);
  
    $sql = "UPDATE tblsettings SET set_value = :status ";
    $sql .= "WHERE set_key = 'status'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":status", $json['status']);
    $stmt->execute();
    $returnValue = $stmt->rowCount() > 0 ? 1 : 0;
    $stmt = null; $conn = null;
    return $returnValue;
  }

  function setRevealStatus($json){
    include "connection.php";
    //{"status":1}
    $json = json_decode($json, true);
  
    $sql = "UPDATE tblsettings SET set_value = :status ";
    $sql .= "WHERE set_key = 'reveal'";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":status", $json['status']);
    $stmt->execute();
    $returnValue = $stmt->rowCount() > 0 ? 1 : 0;
    $stmt = null; $conn = null;
    return $returnValue;
  }

  function getRevealStatus(){
    include "connection.php";

    $sql = "SELECT * FROM tblsettings WHERE set_key = 'reveal'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $returnValue = $stmt->rowCount() > 0 ? $stmt->fetch(PDO::FETCH_ASSOC)['set_value'] : 0;
    $stmt = null; $conn = null;
    return json_encode($returnValue);
  }

}

$operation = isset($_POST["operation"]) ? $_POST["operation"] : "invalid";
$json  = isset($_POST["json"]) ? $_POST["json"] : "";

$settings = new Settings();
switch($operation){
  case "setRatingStatus":
    echo $settings->setRatingStatus($json);
    break;
  case "getRatingStatus":
    echo $settings->getRatingStatus($json);
    break;
  case "getRevealStatus":
    echo $settings->getRevealStatus($json);
    break;
  case "setRevealStatus":
    echo $settings->setRevealStatus($json);
    break;
  }



?>