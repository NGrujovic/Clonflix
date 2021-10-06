<?php 

require_once("PayPal-PHP-SDK/autoload.php");

$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'AePq2m1flULwVyh58IJh8BXcE_5BYlm69bxoEJYMTjk81ZhI3TCdTW8BOHLKXGXcQzrLb2YpUY6K30kR',     // ClientID
        'EDFbm04JYQjsHxkWdO-UXKAsISodI8k1InLP9VG5rtB4AC65iGXzAHxCzMMyYRaT7SoST0zTKyXqXGTV'      // ClientSecret
    )
);

?>