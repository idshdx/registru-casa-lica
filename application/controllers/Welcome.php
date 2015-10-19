<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {

          parent::__construct();
          $this->load->library('session');
          $this->load->helper('form');
          $this->load->helper('url');
          $this->load->helper('html');
          $this->load->database();
          $this->load->library('form_validation');
          //load the login model and table library
          $this->load->model('user');
          
         
          
     }
	
	public function index() {

		$this->load->view('login_view');
	}

	public function login_check()
     {
          //get the posted values
          $username = $this->input->post('input_username');
          $password = $this->input->post('input_password');

          //set validations
          $this->form_validation->set_rules("input_username", "Username", "trim|required|min_length[4]");
          $this->form_validation->set_rules("input_password", "Password", "trim|required|min_length[4]");

          if ($this->form_validation->run() == FALSE)
          {
               //validation fails
               $this->load->view('login_view');
          }
          else
          {
               //validation succeeds
               if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    $user_result = $this->user->login_check($this->input->post('input_username'), $this->input->post('input_password'));
                    if ($user_result > 0) { //active user record is present    
          
                        $admin_result = $this->user->admin_check($username);
                        

                        if($admin_result > 0) { //admin
                          
                          echo 'ADMIN!!!';
                        } else { //not admin
                          
                          echo 'NOT ADMIN';
                            
                        }
                        
                    } else {

                         echo 'Invalid username and password';
                        
                    }
               }    
          }
    }
}
