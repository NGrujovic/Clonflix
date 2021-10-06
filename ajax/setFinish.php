<?php 
 include_once("../includes/connfig.php");

 if(isset($_POST["videoId"]) && isset($_POST["username"])){
     $query = $con->prepare("UPDATE videoProgress SET finished = 1 ,progress = 0
      WHERE username = :username AND videoId = :videoId");
     $query->bindValue(":videoId",$_POST["videoId"]);
     $query->bindValue(":username",$_POST["username"]);
     

     $query->execute();
     
 }
 else{
     echo "No videoId or username passed into file";
 }



?>