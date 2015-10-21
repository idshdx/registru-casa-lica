<?php //user model class

Class User_model extends CI_Model {

	public function __construct() {
		parent:: __construct();
		$this->load->database();
		$this->load->model('date_model');
	}

	public function login_check($username, $password) { 
        $this->db->where('Nume', $username);
        $this->db->where('Parola', $password);
        $result = $this->db->get('Utilizatori');

        return $result->num_rows() == 1;
    }

	public function __destruct() {
        $this->db->close();
    }
}