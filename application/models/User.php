<?php //user model class

Class User extends CI_Model {

	public function __construct() {
		parent:: __construct();
		$this->load->database();
	}

	public function login_check($username, $password) {
        $this->db->where('Nume', $username);
        $this->db->where('Parola', md5($password));
        $result = $this->db->get('Utilizatori');

        return $result->num_rows();
    }
    
	public function __destruct() {
        $this->db->close();
    }

}