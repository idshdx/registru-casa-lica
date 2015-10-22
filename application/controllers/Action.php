<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Action extends CI_Controller {

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
     var_dump($this->main_model->get_record_by_id('SumeMarfaTVA9',1));
      
    }

    public function add_record($table) {
          $post = $this->input->post();
          unset($post['Furnizor']);
          $furnizor = $this->input->post('Furnizor');
          $tip_furnizor = substr($table, 4);
          $idfurnizor = $this->furnizori_model->furnizor_id($furnizor, $tip_furnizor);        

          if($idfurnizor == 0) $idfurnizor = $this->furnizori_model->new_furnizor($tip_furnizor, $furnizor);  
          $post['IDFurnizor'] = $idfurnizor;

          $this->main_model->new_record($table, $post);

          echo json_encode($this->main_model->get_last_record($table));
          
     }

     public function edit_record($table, $id, $idzi) {
         /*if( !isset($_SESSION['userdata']) ) {
                  redirect("login");
            } else {*/
              $post = $this->input->post();
              unset($post['Furnizor']);

              $furnizor = $this->input->post('Furnizor');
              $tip_furnizor = substr($table, 4);
              $idfurnizor = $this->furnizori_model->furnizor_id($furnizor, $tip_furnizor);


              if($idfurnizor == 0) $idfurnizor = $this->furnizori_model->new_furnizor($tip_furnizor, $furnizor);  
              $post['IDFurnizor'] = $idfurnizor;

              $this->main_model->edit_record($table, $id, $post);
              echo json_encode($this->main_model->get_record_by_id($table, $id));       
             
     }

     public function delete_record($table, $id, $idzi) {
           /* if( !isset($_SESSION['userdata']) ) {
                  redirect("login");
            } else {*/
                  $this->main_model->delete_record($table, $id, $idzi);
            
            
     }

     public function edit_sold_initial($idzi, $sum) {
            /*if( !isset($_SESSION['userdata']) ) {
                  redirect("login");
            } else {*/
                  $this->soldinitial_model->edit_sold_initial($idzi, $sum);
            
     }
}