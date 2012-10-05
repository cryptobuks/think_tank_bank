<?php
 
class DOMDocumentExt extends DOMDocument
{
 
    /**
     * Load content of the current document from an array
     *
     * @param array $array Array of key=>value pairs to load the contents from
     * @return boolean
     */
    public function loadArray(array $array)
    {
        // clean the current document
        parent::__construct($this->version, $this->encoding);
 
        // append the supplied array to the current class
        $this->_loadArray($array);
       
        return true;
    }
   
   
    /**
     * Append contents of a multidimensional array to the current document
     *
     * @param array $array     Multi-dimensional array containing the data
     * @param DOMElement $node Parent node to attach children to (only for recursion)
     * @return DOMElement
     */
    private function _loadArray(array $array, $node = null)
    {
        // loop through each entry of the supplied array
        foreach ($array as $key => $value) {
            // if the entry contains other entries
            if (is_array($value)) {
                // create and append empty document node
                $new_node = $this->createElement($key);
                // append contents of the entry to the newly created node
                $child = $this->_loadArray($value, $new_node);
           
            // entry is the deepest level in the branch
            } else {   
                // append text node
                $child = $this->createElement($key, $value);
            }
           
            // if parent node is not specified
            if ($node === null) {
                // append the newly created node to the document root
                $this->appendChild($child);
            } else {
                // otherwise append the new node to the specified parent node
                $node->appendChild($child);
            }
        }
       
        // return the new node. Only for recursion
        return $node;
    }
   
   
    /**
     * Save current document tree as an array
     *
     * @return array
     */
    public function saveArray()
    {
        if ($this->documentElement !== null) {
            return $this->_saveArray($this);
        } else {
            return null;
        }
    }
   
   
    /**
     * Convert current document to an array
     *
     * @param DOMElement $node XML document's node to convert
     * @return array
     */
    private function _saveArray($node)
    {
        $result = null;
       
        // if the node is a text node, simply return its value
        if ($node->nodeType == XML_TEXT_NODE) {
            $result = $node->nodeValue;
       
        // otherwise there are other entries inside the current one
        } else {
            // start with a blank array
            $result = array();
           
            if ($node->hasChildNodes()) {
                $children = $node->childNodes;
               
                // loop through all childnodes of the node
                for ($i = 0; $i < $children->length; $i++) {
                    $child = $children->item($i);
                   
                    // if this is not a text node
                    if (strtolower($child->nodeName) != '#text') {
                        // if we haven't encountered another node with the same name before
                        if (!isset($result[$child->nodeName])) {
                            $result[$child->nodeName] = $this->_saveArray($child);
   
                        } else {    // we have another node with the same name
                            // save the existing node in a temporary variable
                            $temp = $result[$child->nodeName];
                           
                            // reset the current value
                            $result[$child->nodeName] = array();
                           
                            // append the previous value
                            $result[$child->nodeName][] = $temp;
                            // add the
                            $result[$child->nodeName][] = $this->_saveArray($child);
                        }
                    } else {
                        $result = $node->nodeValue;
                    }
                }
            } else {
                $result = "";
            }
        }
       
        return $result;
    }
   
   
    /**
     * Get value of a node in the current document
     *
     * @param string $path Path to the node in root/child/../target_node format
     * @return mixed
     */
    public function getPathValue($path)
    {
        $array = $this->saveArray();
       
        $pathlist = explode('/', $path);
       
        foreach ($pathlist as $key) {
            if (is_array($array) && isset($array[$key])) {
                $array = $array[$key];
            } else {
                return null;
            }
        }
       
        return $array;
    }
 
}
 
?>
