<?php 


    class Account{

        private $con;
        private $errorArray = array();

        public function __construct($con){
            $this->con = $con;
        }

        public function register($fn,$ln,$un,$em,$em2,$pw,$pw2){
            $this->validateFirstName($fn);
            $this->validateLastName($ln);
            $this->validateUsername($un);
            $this->validateEmails($em,$em2);
            $this->validatePassword($pw,$pw2);

            if(empty($this->errorArray)){
                return $this->insertUserDetails($fn,$ln,$un,$em,$pw);
            }

            return false;
        }

        public function login($un,$pw){
            $pw = hash("sha512", $pw);

            $query = $this->con->prepare("SELECT * FROM users WHERE username=:un AND password = :pw ");

            
            $query->bindValue(":un",$un);
            
            $query->bindValue(":pw",$pw);

            $query->execute();

            if($query->rowCount()==1){
                return true;
            }

            array_push($this->errorArray, Constants::$loginFailed);
            return false;
        }
        private function insertUserDetails($fn,$ln,$un,$em,$pw){

            $pw = hash("sha512",$pw);

            $query = $this->con->prepare("INSERT INTO users (firstName,lastName,username,email,password) VALUES(
                :fn,:ln,:un,:em,:pw)");
            $query->bindValue(":fn",$fn);
            $query->bindValue(":ln",$ln);
            $query->bindValue(":un",$un);
            $query->bindValue(":em",$em);
            $query->bindValue(":pw",$pw);

            return $query->execute();
        }
        private function validateFirstName($fn){
            if(strlen($fn) < 2 || strlen($fn) > 25){
                array_push($this->errorArray,Constants::$firstNameCharacters);
            }
        }

        public function getError($error){
            if(in_array($error,$this->errorArray)){
                return "<span class='errorMsg'>$error</span>";
            }
        }

        private function validateLastName($ln){
            if(strlen($ln) < 2 || strlen($ln) > 25){
                array_push($this->errorArray,Constants::$lastNameCharacters);
            }
        }

        private function validateUsername($un){
            if(strlen($un) < 2 || strlen($un) > 25){
                array_push($this->errorArray,Constants::$usernameCharacters);
                return;
            }

            $query = $this->con->prepare("SELECT * FROM users WHERE username =:un");
            $query->bindValue(":un",$un);

            $query->execute();

            if($query->rowCount()!=0){
                array_push($this->errorArray,Constants::$usernameTaken);
            }
        }
        
        private function validateEmails($em,$em2){
            if($em != $em2){
                array_push($this->errorArray,Constants::$emailNotSame);
                return;
            }
            
            if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
                array_push($this->errorArray,Constants::$emailNotValid);
                return;
            }

            $query = $this->con->prepare("SELECT * FROM users WHERE email =:em");
            $query->bindValue(":em",$em);

            $query->execute();

            if($query->rowCount()!=0){
                array_push($this->errorArray,Constants::$emailTaken);
            }
            
        }

        private function validatePassword($pw,$pw2){
            if($pw != $pw2){
                array_push($this->errorArray,Constants::$pwNotSame);
                return;
            }

            if(strlen($pw) < 2 || strlen($pw) > 25){
                array_push($this->errorArray,Constants::$pwCharacters);
            }
        }

        public function updateDetails($fn,$ln,$em,$un){
            $this->validateFirstName($fn);
            $this->validateLastName($ln);
            $this->validateNewEmail($em,$un);

            if(empty($this->errorArray)){
                $query= $this->con->prepare("UPDATE users SET firstName=:fn,
                lastName=:ln, email=:em WHERE username=:un");
                $query->bindValue(":fn", $fn);
                $query->bindValue(":ln", $ln);
                $query->bindValue(":em", $em);
                $query->bindValue(":un", $un);

                return $query->execute();

            }
            return false;


        }

        public function validateNewEmail($em,$un){
           
            
            if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
                array_push($this->errorArray,Constants::$emailNotValid);
                return;
            }

            $query = $this->con->prepare("SELECT * FROM users WHERE email =:em AND username != :un");
            $query->bindValue(":em",$em);
            $query->bindValue(":un",$un);
            $query->execute();

            if($query->rowCount()!=0){
                array_push($this->errorArray,Constants::$emailTaken);
            }
            
        }

        public function getFirstError(){
            if(!empty($this->errorArray)){
                return $this->errorArray[0];
            }
        }

        public function updatePassword($oldpw,$pw,$pw2,$un){
            $this->validateOldPassword($oldpw,$un);
            $this->validatePassword($pw,$pw2);

            if(empty($this->errorArray)){
                $query= $this->con->prepare("UPDATE users SET password =:pw
                 WHERE username=:un");
                 $pw = hash("sha512", $pw);
                $query->bindValue(":pw", $pw);
                
                $query->bindValue(":un", $un);

                return $query->execute();

            }
            return false;
        }

        public function validateOldPassword($oldpw,$un){
            $pw = hash("sha512", $oldpw);

            $query = $this->con->prepare("SELECT * FROM users WHERE username=:un AND password = :pw ");

            
            $query->bindValue(":un",$un);
            
            $query->bindValue(":pw",$pw);

            $query->execute();

            if($query->rowCount()==0){
                array_push($this->errorArray, Constants::$passwordIncorrect);
            }
        }
    }
?>