<?php
class FormSanitizer{



    
    public static function sanitazeFormStrings($inputText){
        $inputText = strip_tags($inputText);
        //$inputText = str_replace(" ","",$inputText);
        $inputText = trim($inputText);
        $inputText = strtolower($inputText);
        $inputText = ucfirst($inputText);
        return $inputText;
    }

    public static function sanitazeFormUsername($inputText){
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ","",$inputText);
        
        
        return $inputText;
    }
    public static function sanitazeFormPasswords($inputText){
        $inputText = strip_tags($inputText);
        
        return $inputText;
    }
    public static function sanitazeFormEmail($inputText){
        $inputText = strip_tags($inputText);
        $inputText = str_replace(" ","",$inputText);
        
        return $inputText;
    }
}

?>