<?php //Sold initial model class

Class soldinitial_model extends CI_Model {

	function __construct() {

		parent:: __construct();

		$this->load->database();
		$this->load->model('date_model');
		$this->load->library('session');
	}

	//Adauga soldul initial in tabel Luni.Returns true or false.
	function new_sold_initial($sum) {
    	$this->db->insert('Luni', $sum);
	}

	//editeaza soldul initial in tabel Luni.Returns true or false.
	function edit_sold_initial($idzi, $sold_initial) {
    	$this->db->where('IDZi', $idzi);
    	$this->db->set('SoldInitial', $sold_initial);
    	$this->db->update('Luni');
	}

	//Get sold initital by ID.Returns an array
	function get_sold_initial($idzi) {

		$this->db->select('SoldInitial');
		$this->db->where('IDZi', $idzi);

		return floatval($this->db->get('Luni')->result_array()[0]['SoldInitial']);

		
	}

	function __destruct() {

        $this->db->close();
    }

}