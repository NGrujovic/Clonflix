<?php 
require_once("includes/header.php");



$preview = new PreviewProvider($con, $userLoggedIn);
echo $preview-> createTvShowPreviewVideo(null);

$categoryContainer = new CategoryContainers($con, $userLoggedIn);
echo $categoryContainer->showTvShowCategories();
?>