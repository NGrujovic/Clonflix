<?php 
require_once("includes/header.php");



$preview = new PreviewProvider($con, $userLoggedIn);
echo $preview->createPreviewVideo(null);

$categoryContainer = new CategoryContainers($con, $userLoggedIn);
echo $categoryContainer->showAllCategories();
?>