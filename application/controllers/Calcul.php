<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class calcul extends CI_Controller {

	public function __construct() {
    parent::__construct();
    $this->load->helper('form');
    $this->load->library('session');
    $this->load->helper('url');
    $this->load->helper('html');
    $this->load->database();
    $this->load->library('form_validation');
      //load the model classes and helpers
    $this->load->model(['user_model', 'main_model', 'soldinitial_model', 'furnizori_model', 'date_model', 'calcul_model']);
    $this->load->helper('registru_helper'); 

  }

  public function index($idzi) {
    echo json_encode(['Cheltuieli' => $this->total_chelt($idzi),'total_tva9' => $this->total_tva9($idzi), 
                      'total_tva24' => $this->total_tva24($idzi), 'total_aport' => $this->total_aport($idzi)]);
  }

  private function total_chelt($idzi) {
    return $this->calcul_model->cumul('SumeCheltuieli', $idzi);
  }

  private function total_tva9($idzi) {
    return $this->calcul_model->cumul('SumeMarfaTVA9', $idzi);
  }

  private function total_tva24($idzi) {
    return $this->calcul_model->cumul('SumeMarfaTVA24', $idzi);
  }

  private function total_aport($idzi) {
    return $this->calcul_model->cumul('SumeAport', $idzi);
  }
}