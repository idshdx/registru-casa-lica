<?php

class main_model extends CI_Model {

	public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    //Method to fetch recods
    public function get_records($table, $idzi) {
    	$this->db->where('IDzi', $idzi);
		$this->db->select('*');
		$result = $this->db->get($table)->result_array();
		$count = count($result);
		
		for($i = 0; $i < $count; $i++) {
			$result[$i]['ID'] = intval($result[$i]['ID']);
			$result[$i]['IDZi'] = intval($result[$i]['IDZi']);
			$result[$i]['Suma'] = floatval($result[$i]['Suma']);
			if($table != 'SumeAport') $result[$i]['IDFurnizor'] = intval($result[$i]['IDFurnizor']);
		}
		
		return $result;	
    }

    //Fetch the last record(array)
    public function get_last_record($table){
    	$query = "SELECT * from $table ORDER BY ID DESC LIMIT 1;";

    	$result = $this->db->query($query)->result_array()[0];
    	$final = ['ID' => intval($result['ID']), 'IDZi'=> intval($result['IDZi']), 'Suma' => floatval($result['Suma']), 
    			'IDFurnizor' => intval($result['IDFurnizor']), 'Factura' => $result['Factura'], 'Chitanta' => $result['Chitanta'] ];

    	return $final;
    }

    public function get_last_aport($idzi) {
        $query = "SELECT * FROM SumeAport WHERE IDZi= $idzi ORDER BY ID DESC LIMIT 1";
        $result = $this->db->query($query)->result_array()[0];
        $final = ['ID' => (int)$result['ID'],'IDZi' => (int)$result['IDZi'], 'Suma' => (float)$result['Suma'] ];

        return $final;
    }

    //Fetch the ID of the last record
    public function get_last_record_id($table) {
    	$query = "SELECT ID from $table ORDER BY ID DESC LIMIT 1;";

    	$result = $this->db->query($query)->result_array();

    	return intval($result[0]);
    }

    //Fetch the ID of the specified record
    public function get_record_by_id($table, $id) {
        $query = "SELECT * from $table WHERE ID = $id ;";

        $result = $this->db->query($query)->result_array()[0];
        $final = ['ID'=> (int)$result['ID'], 'IDZi'=> (int)$result['IDZi'], 'Suma'=> (float)$result['Suma'],
                 'IDFurnizor'=> (int)$result['IDFurnizor'], 'Factura'=> $result['Factura'], 'Chitanta'=> $result['Chitanta']];
        return $final;
    }

    public function new_record($table, $data) {
         $this->db->insert($table, $data);
	}


	public function edit_record($table, $id, $data) {			
		$this->db->where('ID', $id);
        $this->db->set($data);
		$this->db->update($table);	
	}

	public function delete_record($table, $id, $idzi) {
        $this->db->where('id', $id);
		$this->db->where('idzi', $idzi);
		$this->db->delete($table);
	}

    public function __destruct() {
        $this->db->close();
    }
}
