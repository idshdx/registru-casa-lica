<?php //user model class

Class User extends CI_Model {

	function __construct() {

		parent:: __construct();

		$this->load->database();
	}

	function login_check($username, $password) {

        $this->db->where('Nume', $username);
        $this->db->where('Parola', md5($password));
        $result = $this->db->get('Utilizatori');

        return $result->num_rows();
    }


	//Match for a username with the corresponding value for admin(value =1)
	//Returns the nr of rows(If 0 then no match is found for admin)
	
	function admin_check($username) {

		$this->db->where('Nume', $username);
        $this->db->where('Admin', 1); 
        $query = $this->db->get('Utilizatori');
       
       	return $query->num_rows(); 
	}

	function __destruct() {

        $this->db->close();
    }

}