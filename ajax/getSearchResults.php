<?php 
 include_once("../includes/connfig.php");
 include_once("../includes/classes/SearchResultsProvider.php");
 include_once("../includes/classes/EntityProvider.php");
 include_once("../includes/classes/Entity.php");
 include_once("../includes/classes/PreviewProvider.php");

 if(isset($_POST["term"]) && isset($_POST["username"])){
    $srp = new SearchResultsProvider($con, $_POST["username"]);
    echo $srp->getResults($_POST["term"]);
 }
 else{
     echo "No term or username passed into file";
 }



?>