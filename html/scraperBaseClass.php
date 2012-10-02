<?
class scraperBaseClass {
    
    var $base_url;
    
    function get_page_array() {
        echo 'getting a list of pages'; 
    }
    
    function get_page_html($url) {            
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Think Tank Bank");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        $result = curl_exec ($ch);    
        curl_close($ch)
        return($result);
    } 

    
}




?>