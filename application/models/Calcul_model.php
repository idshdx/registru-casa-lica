<?php //Class responsible for computing

class calcul_model extends CI_Model {

	function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // Returns the sum(total of sums) on a particular day
    public function ammount_by_day($table, $idzi) {
        $query = "SELECT SUM(Suma) as Suma From $table WHERE Idzi = $idzi";

        return (float)$this->db->query($query)->result_array()[0]['Suma'];
    }

    //Computes all the sums from a table, starting on 1st of every month untill specified($idzi)
    public function cumul($tabel, $idzi) {
        $previdzi = $idzi-1;
        $this->load->model('date_model');
        $firstid = $this->date_model->first_day_of_month($idzi);

        $query = "SELECT Sum(Suma) as Suma FROM $tabel WHERE IDZi BETWEEN $firstid AND $previdzi";
        
        return $this->db->query($query)->result_array()[0]['Suma'];
    }

    public function __destruct() {
        $this->db->close();
    }

}