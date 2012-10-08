<?
class outputClass { 
    
    var $errors = array(); 
    var $success = array();
    
    // Store the single instance of Database
    private static $outputInstance;

    private function __construct() { }

    public static function getInstance() {
        if (!self::$outputInstance){
            self::$outputInstance = new outputClass();
        }

        return self::$outputInstance;
    }    
    
    function write_output_to_log() { 
        
    }
    
    function display_output() { 
        echo "<br/><br/>ERRORS \n<br/> "; 
        print_r($this->errors); 
        echo " <br/><br/><br/>\n";
        
        echo "Success  \n <br/>";
        print_r($this->success); 
    }
    
}





?>