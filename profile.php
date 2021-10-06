<?php 
require_once("includes/header.php");
require_once("includes/paypalConfig.php");
require_once("includes/classes/Account.php");
require_once("includes/classes/FormSanitizer.php");
require_once("includes/classes/Constants.php");
require_once("includes/classes/BillingDetails.php");


$detailsMessage = "";
$passwordMessage="";
$subscriptionMessage="";
$user = new User($con, $userLoggedIn);



if(isset($_POST["saveDetailsBtn"])){
    $account= new Account($con);

    $firstName = FormSanitizer::sanitazeFormStrings($_POST["firstName"]);
    $lastName = FormSanitizer::sanitazeFormStrings($_POST["lastName"]);
    $email = FormSanitizer::sanitazeFormEmail($_POST["email"]);

    if($account->updateDetails($firstName,$lastName,$email,$userLoggedIn)){
        $detailsMessage= "<div class='alertSuccess'>
            Details updated successfully!
        </div>";
    }
    else{
        $errorMessage = $account->getFirstError();

        $detailsMessage= "<div class='alertError'>
            $errorMessage
        </div>";
    }



}


if(isset($_POST["savePasswordBtn"])){
    $account= new Account($con);

    $passwordOld = FormSanitizer::sanitazeFormPasswords($_POST["oldPassword"]);
    $passwordNew = FormSanitizer::sanitazeFormPasswords($_POST["newPassword"]);
    $passwordConfirm = FormSanitizer::sanitazeFormPasswords($_POST["newPassword2"]);

    if($account->updatePassword($passwordOld,$passwordNew,$passwordConfirm,$userLoggedIn)){
        $passwordMessage= "<div class='alertSuccess'>
            Password updated successfully!
        </div>";
    }
    else{
        $errorMessage = $account->getFirstError();

        $passwordMessage= "<div class='alertError'>
            $errorMessage
        </div>";
    }



}

if (isset($_GET['success']) && $_GET['success'] == 'true') {
    $token = $_GET['token'];
    $agreement = new \PayPal\Api\Agreement();
    $subscriptionMessage= "<div class='alertError'>
    Something went wrong !
     </div>";
    try {
      // Execute agreement
      $agreement->execute($token, $apiContext);

      $result = BillingDetails::insertDetails($con,$agreement, $token, $userLoggedIn);
      $result = $result && $user->setIsSubscribed(1);

      if($result){
        $subscriptionMessage= "<div class='alertSuccess'>
        You're all signed up !
        </div>";
      }
    } catch (PayPal\Exception\PayPalConnectionException $ex) {
      echo $ex->getCode();
      echo $ex->getData();
      die($ex);
    } catch (Exception $ex) {
      die($ex);
    }
  } else if (isset($_GET['success']) && $_GET['success'] == 'false'){
    $subscriptionMessage= "<div class='alertError'>
                        Usser canceled or something went wrong !
                        </div>";
  }
?>

<div class="settingsContainer column">
    <div class="formSection">
        <form method="POST">
            <h2>User details</h2>

            <?php 
            
            $firstName = isset($_POST["fistName"]) ? $_POST["firstName"] : $user->getFirstName();
            $lastName = isset($_POST["lastName"]) ? $_POST["lastName"] : $user->getLastName();
            $email = isset($_POST["email"]) ? $_POST["email"] : $user->getEmail();

            ?>
            <input type="text" name="firstName" placeholder="First Name" value="<?php echo $firstName;?>" >
            <input type="text" name="lastName" placeholder="Last Name" value ="<?php echo $lastName;?>">
            <input type="email" name="email" placeholder="E-Mail" value="<?php echo $email;?>">
            <div class="message">
                <?php echo $detailsMessage; ?>
            </div>
            <input type="submit" name="saveDetailsBtn" value="Save">

        </form>
    </div>

    <div class="formSection">
        <form method="POST">
            <h2>Update Password</h2>
            <input type="password" name="oldPassword" placeholder="Old Password" >
            <input type="password" name="newPassword" placeholder="New Password">
            <input type="password" name="newPassword2" placeholder="Confirm Password">
            <div class="message">
                <?php echo $passwordMessage; ?>
            </div>
            <input type="submit" name="savePasswordBtn" value="Save">

        </form>
    </div>

    <div class="formSection">
        <h2>Subscription</h2>
        <div class="message">
                <?php echo $subscriptionMessage; ?>
            </div>
        <?php 
            if($user->getSubscription()){
                echo "<h3>You are subscribed!Go to PayPal to cancel.</h3>";
            }
            else{
                echo "<a href='billing.php'>Subscribe to ClonfliX</a>";
            }
        ?>
    </div>
</div>