<?php 
require_once("includes/header.php");

if(!isset($_GET["id"])){
    ErrorMessage::show("No id passed to page");
}

$preview = new PreviewProvider($con, $userLoggedIn);
echo $preview-> createCategoryPreviewVideo($_GET["id"]);

$categoryContainer = new CategoryContainers($con, $userLoggedIn);
echo $categoryContainer->showCategory($_GET["id"]);
?>