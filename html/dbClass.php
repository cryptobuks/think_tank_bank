<?
class dbClass {
    
    var $db;
    
    function __construct($db_location, $db_user_name, $db_password, $db_name) { 
        $this->db = MYSQL_CONNECT ($db_location, $db_user_name,  $db_password) or DIE ("Unable to connect to Database Server");
        MYSQL_SELECT_DB ($db_name, $this->db) or DIE ("Could not select database");
    }
       

    private function query($sql) {
        $result = MYSQL_QUERY ($sql, $this->db) or DIE ("Invalid query: " . MYSQL_ERROR());
        return $result;
    }

    private function fetch($sql) {
        $data = array();
        $result = $this->query($sql);

        WHILE($row = MYSQL_FETCH_ASSOC($result)) {
           $data[] = $row;
        }
        
        return $data;
     }

    public function get_thinktanks() { 
        $query = "SELECT * FROM thinktanks"; 
        $result = $this->fetch($query); 
        
        return $result; 
    } 
}

?>