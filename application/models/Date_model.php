<?php //Class responsible for date-related queries

class date_model extends CI_Model {

	function __construct() {
        parent::__construct();
        
        $this->load->database();
    }

    //Returns date as an array by IDZi
    public function get_date_by_id($idzi) {
    	$query = "SELECT Data FROM Zile WHERE ID = $idzi ;";

    	return date_parse($this->db->query($query)->result_array()[0]['Data']);
    }

    //Returns date+ID as an array by IDZi
    public function get_id_date_by_id($idzi) {
        $query = "SELECT * FROM Zile WHERE ID = $idzi ;";

        $result = $this->db->query($query)->result_array();
        $final[] = ['ID' => intval($result[0]['ID']), 'Data' => $result[0]['Data']];

        return $final[0];
    }
    
    //Returns the ID of the first day of the month by IDZi
    public function id_first_day_by_id($idzi) {
    	$data = $this->get_date_by_id($idzi);
    	$year = $data['year'];
    	$month = $data['month'];
    	
    	$query = "SELECT ID FROM Zile WHERE Data = STR_TO_DATE('$year, $month,1','%Y,%m,%d');";

    	return $this->db->query($query)->result_array()[0]['ID'];
    }

    //Returns the Date of the first day of the month by IDzi.Depends on get_date_by_id()
    public function date_first_day($idzi) {
    	$year = $this->get_date_by_id($idzi)['year'];
        $month = $this->get_date_by_id($idzi)['month'];
    	
    	$query = "SELECT Data FROM Zile WHERE Data = STR_TO_DATE('$year, $month,1' ,'%Y-%m-%d')";

    	return date_parse($this->db->query($query)->result_array()[0]['Data']);
    }

    //Returns the ID of the last entry in the table
    public function last_day_id() {
    	$query = "SELECT * FROM Zile order by ID DESC LIMIT 1 ;";
    	$result = $this->db->query($query)->result_array();
    	
		
		return intval($result[0]['ID']);	
    }	

    public function __destruct() {
        $this->db->close();
    }

}