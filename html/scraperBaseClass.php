<?
class scraperBaseClass {
    
    var $base_url;
    
    function get_page_array() {
        echo 'getting a list of pages'; 
    }
    
    function get_page_html($url) {            
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt ($ch, CURLOPT_HEADER, 0);
        $html = curl_exec ($ch);    
        curl_close($ch);
        return $html;
    } 
    

    function zend_query($target, $selector) { 
    
        //This function will process either Dom Nodes or URLs        
        if(@get_class($target)== "DOMElement") { 
            $node_doc = new DOMDocument();
            $cloned = $target->cloneNode(TRUE);
            $node_doc->appendChild($node_doc->importNode($cloned,TRUE));
            $string = $node_doc->saveHTML();

        } 
        
        else if(substr($target,0,7) == "http://") { 
            $string = $this->get_page_html($target); 
        }        
            
        $dom = new Zend_Dom_Query($string);
        $results = $dom->query($selector);
        $number_of_nodes = count($results);
       
       
        if ($number_of_nodes > 1) { //if we have many results convert from the Zend_Dom_Query_Result class to Dom Nodes
             $output_array = array();
            
            foreach ($results as $result) { 
                $output_array[] = $result;  
            }
            
            $output = $output_array; 
        }
        
        else if ($number_of_nodes == 1){ // else, get the text out of this node
            foreach ($results as $result) { 
                $output = $result->textContent;  
            }
        }
        
        else {
            $output= 'no results';
        }
        
        return $output;
    }
    

}




?>