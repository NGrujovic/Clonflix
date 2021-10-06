

<?php 
    
    require_once("includes/classes/FormSanitizer.php");
    require_once("includes/connfig.php");
    require_once("includes/classes/Account.php");
    require_once("includes/classes/Constants.php");
    $account = new Account($con);
    if(isset($_POST["submitButton"])){
        
        $userName = FormSanitizer::sanitazeFormUsername($_POST["userName"]);
        $password = FormSanitizer::sanitazeFormPasswords($_POST["password"]);

        $success = $account->login($userName,$password);

        if($success){
            $_SESSION["userLoggedIn"] = $userName;
            header("Location: index.php");
        }

    }

    function getInputValue($name){
        if(isset($_POST[$name])){
            echo $_POST[$name];
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
                    <h3>Sign In</h3>
                    <span>to continue to ClonfliX</span>
                   
                </div>

                <form method="POST" action="">
                   
                    <input type="text" name="userName" placeholder="Username" value="<?php getInputValue("userName");?>"required>

                    
                    <input type="password" name="password" placeholder="Password" required>
                    <?php 
                        echo $account->getError(Constants::$loginFailed);
                    ?>
                   
                    <input type="submit" name="submitButton" value="SUBMIT">

                </form>

                <a href="register.php" class="signInMessage">Already have an account? Sign in here.</a>
            </div>
        </div>
    </body>
</html>