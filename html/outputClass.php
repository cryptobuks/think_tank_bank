<?
class outputClass { 
    var $errors = array(); 
    var $success = array();
    
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