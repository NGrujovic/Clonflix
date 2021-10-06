<?php 
require_once("includes/classes/FormSanitizer.php");
require_once("includes/connfig.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/Constants.php");

    $account = new Account($con);

    if(isset($_POST["submitButton"])){
        $firstName = FormSanitizer::sanitazeFormStrings($_POST["firstName"]);
        $lastName = FormSanitizer::sanitazeFormStrings($_POST["lastName"]);
        $userName = FormSanitizer::sanitazeFormUsername($_POST["userName"]);
        $email = FormSanitizer::sanitazeFormEmail($_POST["email"]);
        $email2 = FormSanitizer::sanitazeFormEmail($_POST["email2"]);
        $password = FormSanitizer::sanitazeFormPasswords($_POST["password"]);
        $password2 = FormSanitizer::sanitazeFormPasswords($_POST["password2"]);

        
    
        $success = $account->register($firstName,$lastName,$userName,$email,$email2,$password,$password2);
        
        if($success) {
            $_SESSION["userLoggedIn"] = $userName;
            header("Location: index.php");
        }
    }

?>
<!DOCTYPE html>
<html>
    <head>
    <title>ClonfliX</title>
    <link rel="stylesheet" type="text/css" href="assets/style/style.css"/>
    </head>
    <body>
        <div class="signInContainer">
            <div class="column">

                <div class="header">
                <img src="assets/images/logo.png" title="Clonflix" alt="Site logo"/>
                    <h3>Sign Up</h3>
                    <span>to continue to ClonfliX</span>
                   
                </div>

                <form method="POST" >
                    <?php 
                        echo $account->getError(Constants::$firstNameCharacters);
                    ?>
                    <input type="text" name="firstName" placeholder="First name" required>
                    <?php 
                        echo $account->getError(Constants::$lastNameCharacters);
                    ?>
                    <input type="text" name="lastName" placeholder="Last name" required>
                    <?php 
                        echo $account->getError(Constants::$usernameCharacters);
                    ?>
                    <?php 
                        echo $account->getError(Constants::$usernameTaken);
                    ?>
                    <input type="text" name="userName" placeholder="Username" required>
                    <?php 
                        echo $account->getError(Constants::$emailNotSame);
                    ?>
                    <?php 
                        echo $account->getError(Constants::$emailNotValid);
                    ?>
                    <?php 
                        echo $account->getError(Constants::$emailTaken);
                    ?>
                    <input type="email" name="email" placeholder="Email" required>

                    <input type="email" name="email2" placeholder="Confirm email" required>
                    <?php 
                        echo $account->getError(Constants::$pwNotSame);
                    ?>
                    <?php 
                        echo $account->getError(Constants::$pwCharacters);
                    ?>
                    <input type="password" name="password" placeholder="Password" required>

                    <input type="password" name="password2" placeholder="Confirm Password" required>

                    <input type="submit" name="submitButton" value="SUBMIT">

                </form>

                <a href="login.php" class="signInMessage">Need an account? Sign up here!</a>
            </div>
        </div>
    </body>
</html>