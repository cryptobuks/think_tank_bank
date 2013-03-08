<?php

/*
 * entity_extraction
 *
 * Extracts entities from a body of text
 *
 * @content (text) Some text to extract entities from 
 * @return (array) Entities extracted 
 */

function entity_extraction($content)
{
    // Your license key (obatined from api.opencalais.com)
    
    
    $contentType  = "text/txt"; // simple text - try also text/html
    $outputFormat = "Application/JSON"; // simple output format - try also xml/rdf and text/microformats
    
    $restURL   = "http://api.opencalais.com/enlighten/rest/";
    $paramsXML = "<c:params xmlns:c=\"http://s.opencalais.com/1/pred/\" " . "xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"> " . "<c:processingDirectives c:contentType=\"" . $contentType . "\" " . "c:outputFormat=\"" . $outputFormat . "\"" . "></c:processingDirectives> " . "<c:userDirectives c:allowDistribution=\"false\" " . "c:allowSearch=\"false\" c:externalID=\" \" " . "c:submitter=\"Calais REST Sample\"></c:userDirectives> " . "<c:externalMetadata><c:Caller>Calais REST Sample</c:Caller>" . "</c:externalMetadata></c:params>";
    
    // Construct the POST data string
    $data = "licenseID=" . urlencode(CALAIS_API_KEY);
    $data .= "&paramsXML=" . urlencode($paramsXML);
    $data .= "&content=" . urlencode($content);
   
    // Invoke the Web service via HTTP POST
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $restURL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $response = curl_exec($ch);

    
    curl_close($ch);
    
    $json = json_decode($response, true);
    print_R($json);
    
    if (!isset($json)) {
        echo "The Open Calais API is down ";
        return array();
    } else {
        $return_value = array();
        
        $banned_array = array(
            'gbp',
            'eu',
            'rt',
            'http'
        );
        
        foreach ($json as $entity) {
            if (isset($entity['name'])) {
                $extracted['term']      = $entity['name'];
                $extracted['type']      = $entity['_type'];
                $extracted['frequency'] = count($entity['instances']);
                $extracted['source']    = "entity";
                if ($entity['_type'] == 'Organization' || $entity['_type'] == 'Person') {
                    $extracted['frequency'] = $extracted['frequency'] * 1.5;
                    echo "<p>Person or Org Found!</p>";
                }
                 
                if ($entity['_type'] != 'URL' && $extracted['frequency'] > 2 && !array_search(strtolower($extracted['term']), $banned_array)) {
                    echo "----";
                   
                    $return_value[] = $extracted;
                }
            }
        }
        
        print_r($return_value);
        
        return $return_value;
    }
}

?>