<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

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

    public function index() {
      $this->session->unset_userdata('userdata');  
    }

    public function session_check() {
      return isset($_SESSION['userdata']);
    }
}