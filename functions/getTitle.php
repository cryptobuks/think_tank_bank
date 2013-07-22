<?
function getUrlTitle($Url){
    
    $str = file_get_contents($Url);
    
    if(strlen($str)>0){
        preg_match("/\<title\>(.*)\<\/title\>/",$str,$title);
        return $title[1];
    }
}

?>