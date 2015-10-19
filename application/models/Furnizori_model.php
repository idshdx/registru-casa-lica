<?php //Furnizor model class

Class Furnizori_model extends CI_Model {

	function __construct() {
		parent:: __construct();
		$this->load->database();
	}

	//Method to get providers by type(IDFurnizor(1, 2, 3))
    function get_furnizori($tip) {  
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

	//Add furnizor record
	function new_furnizor($tip, $name) {
		$data = array(
			'Tip' => $this->furnizor_tip_id($tip),
			'Nume' => $name
			);

		$this->db->where('Tip', $tip);
        $this->db->where('Nume', $name);
        $this->db->get('Furnizori');

       /* $query = "INSERT INTO Furnizori(Tip, Nume) VALUES ($tip"*/

        $result = $this->db->insert('Furnizori', $data);

        return $this->last_furnizor();    
	}

	function last_furnizor() {
		$query = "SELECT ID from Furnizori ORDER BY ID DESC LIMIT 1;";

		$result = $this->db->query($query)->result_array();

    	return intval($result[0]['ID']);
	}

	function furnizor_tip_id($tip) {
		$query = "SELECT ID FROM TipFurnizor WHERE Tip = '$tip';";

		return intval($this->db->query($query)->result_array()[0]['ID']);
	}

	function furnizor_id($nume, $tip) {
		$tip = $this->furnizor_tip_id($tip);
		$query = "SELECT ID From Furnizori WHERE Nume = '$nume' AND Tip = $tip;";
		$result = $this->db->query($query)->result_array();

		 return count($result) == 1 ? intval($result[0]['ID']) : 0;
		
	}

	function __destruct() {
        $this->db->close();
    }

}