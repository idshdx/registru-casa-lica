<?php //Sold initial model class

Class soldinitial_model extends CI_Model {

	public function __construct() {
		parent:: __construct();
		$this->load->database();
		$this->load->model('date_model');
		$this->load->library('session');
	}

	
	public function new_sold_initial($data) {
    	$this->db->insert('Luni', $data);
	}


	public function edit_sold_initial($idzi, $sold_initial) {
    	$this->db->where('IDZi', $idzi);
    	$this->db->set('SoldInitial', $sold_initial);
    	$this->db->update('Luni');
	}

	public function get_sold_initial($idzi) {
		$this->db->select('SoldInitial');
		$this->db->where('IDZi', $idzi);

		return $this->db->get('Luni')->result_array()[0]['SoldInitial'];	
		
	}

	public function __destruct() {
        $this->db->close();
    }

}