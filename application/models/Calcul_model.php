<?php //Class responsible for computing

class calcul_model extends CI_Model {

	function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Returns the sum(total of sums) on a day
    public function get_amount_by_day($table, $idzi) {
        $this->db->where('IDzi', $idzi);
        $this->db->select('Suma');
        $result = $this->db->get($table);
        
        return $result->result_array(); 
    }

    //Computes all the sums from a table depending on the lastday, starting on 1st of every month
    public function cumul($tabel, $idzi) {
        $previdzi = $idzi-1;
        $this->load->model('date_model');
        $firstid = $this->date_model->id_first_day_by_id($idzi);

        $query = "SELECT Sum(Suma) as Suma FROM $tabel WHERE IDZi BETWEEN $firstid AND $previdzi";
        
        return $this->db->query($query)->result_array()[0]['Suma'];
    }

    public function __destruct() {
        $this->db->close();
    }

}