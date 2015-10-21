<?php //Furnizor model class

Class Furnizori_model extends CI_Model {

	public function __construct() {
		parent:: __construct();
		$this->load->database();
	}

	//Method to get providers by type(IDFurnizor(1, 2, 3))
    public function get_furnizori($tip) {  
  		$query = "SELECT Furnizori.ID, Furnizori.Nume FROM Furnizori 
  				LEFT JOIN TipFurnizor ON Furnizori.Tip = TipFurnizor.ID WHERE TipFurnizor.Tip = '$tip' ;";
  		$result = $this->db->query($query)->result_array();
  		$array = [];

  		foreach($result as $item) {
  			$intid = intval($item['ID']);
  			$array[$intid] = $item['Nume'];
  		}

  		return $array;	
    }

	public function new_furnizor($tip, $name) {
		$data = array(
			'Tip' => $this->furnizor_tip_id($tip),
			'Nume' => $name
			);
		$this->db->where('Tip', $tip);
        $this->db->where('Nume', $name);
        $this->db->get('Furnizori');
        $result = $this->db->insert('Furnizori', $data);

        return $this->last_furnizor();    
	}

	//Returns the ID from the last record
	public function last_furnizor() {
		$query = "SELECT ID from Furnizori ORDER BY ID DESC LIMIT 1;";
		$result = $this->db->query($query)->result_array();

    	return intval($result[0]['ID']);
	}


	private function furnizor_tip_id($tip) {
		$query = "SELECT ID FROM TipFurnizor WHERE Tip = '$tip';";

		return intval($this->db->query($query)->result_array()[0]['ID']);
	}
	//Fetch the Furnizor ID by name and type. 
	public function furnizor_id($nume, $tip) {
		$tip = $this->furnizor_tip_id($tip);
		$query = "SELECT ID From Furnizori WHERE Nume = '$nume' AND Tip = $tip;";
		$result = $this->db->query($query)->result_array();

		//Returns the ID if record exist, 0 if there is no Furnizor with the provided name and type.
		return count($result) == 1 ? intval($result[0]['ID']) : 0;
		
	}

	public function __destruct() {
        $this->db->close();
    }

}