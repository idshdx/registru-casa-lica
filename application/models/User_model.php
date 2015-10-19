<?php //user model class

Class User_model extends CI_Model {

	function __construct() {

		parent:: __construct();

		$this->load->database();
		$this->load->model('date_model');
	}

	public function login_check($username, $password) { //returns true of false

        $this->db->where('Nume', $username);
        $this->db->where('Parola', $password);
        $result = $this->db->get('Utilizatori');

        return $result->num_rows() == 1;
    }

	function __destruct() {

        $this->db->close();
    }



}