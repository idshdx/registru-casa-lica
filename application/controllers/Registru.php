<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registru extends CI_Controller {

	public function __construct() {
          parent::__construct();
          $this->load->model(['user_model', 'main_model', 'soldinitial_model', 'furnizori_model', 'date_model', 'calcul_model']);
          $this->load->helper('registru_helper');
          $this->load->helper('url'); 
 
     }

     public function index() {
          $idzi = $this->date_model->last_day_id() ;        
          $records = $this->get_records($idzi);
          $this->load->view('index', ['server_data' => json_encode($records) ]);
     }

     public function get_total_json($idzi) {
          echo json_encode($this->get_total($idzi));
     }
     private function get_total($idzi) {
         return ['Cheltuieli'=>$this->calcul_model->get_amount_by_day('SumeCheltuieli', $idzi), 
                    'MarfaTVA9'=> $this->calcul_model->get_amount_by_day('SumeMarfaTVA9', $idzi),
                    'MarfaTVA24'=> $this->calcul_model->get_amount_by_day('SumeMarfaTVA24', $idzi), 
                    'Aport'=> $this->calcul_model->get_amount_by_day('SumeAport', $idzi)];
     }

     public function get_records_json($data) {
          $idzi = $this->date_model->id_by_date($data);
          echo json_encode($this->get_records($idzi));
     }

     private function get_records($idzi) {

          $chelt = $this->main_model->get_records('SumeCheltuieli', $idzi);
          $marfa9 = $this->main_model->get_records('SumeMarfaTVA9', $idzi);
          $marfa24 = $this->main_model->get_records('SumeMarfaTVA24', $idzi);
          $aport = $this->main_model->get_records('SumeAport', $idzi);

          $soldinitial = $this->soldinitial_model->get_sold_initial($this->date_model->id_first_day_by_id($idzi));
          $zi = $this->date_model->get_id_and_date($idzi);
          $first_date = $this->parsed_date_to_string($this->date_model->first_day_ever());     
          $furnizori = $this->get_furnizori();

          $calcule = ['Cheltuieli' => floatval($this->calcul_model->cumul('SumeCheltuieli', $idzi)),
                      'MarfaTVA9' => floatval($this->calcul_model->cumul('SumeMarfaTVA9', $idzi)), 
                      'MarfaTVA24' => floatval($this->calcul_model->cumul('SumeMarfaTVA24', $idzi)), 
                      'Aport' => floatval($this->calcul_model->cumul('SumeAport', $idzi)), 
                      'soldinitial' => floatval($soldinitial) - floatval($this->calcul_model->cumul('SumeCheltuieli', $idzi))
                      - floatval($this->calcul_model->cumul('SumeMarfaTVA24', $idzi)) + floatval($this->calcul_model->cumul('SumeAport', $idzi))];
          
          return ['Cheltuieli' => $chelt, 
                    'MarfaTVA9' => $marfa9, 
                    'MarfaTVA24' => $marfa24,
                    'Aport' => $aport, 
                    'zi' => $zi, 
                    'first_date' => $first_date,
                    'furnizori' => $furnizori, 
                    'cumuli' => $calcule,
                    'totals' => $this->get_total($idzi),
                    'loggedin' => loggedin() ] ;
     }

     public function get_furnizori_json() {
          echo  json_encode($this->get_furnizori());
     }

     private function get_furnizori() {
          $furnizori_chelt = $this->furnizori_model->get_furnizori('Cheltuieli');
          $furnizori_marfa9 = $this->furnizori_model->get_furnizori('MarfaTVA9');
          $furnizori_marfa24 = $this->furnizori_model->get_furnizori('MarfaTVA24');

          return ['Cheltuieli' => $furnizori_chelt, 'MarfaTVA9' => $furnizori_marfa9, 'MarfaTVA24' => $furnizori_marfa24];
     }


     public function new_day(){

          //insert the next date(to use in last_day_id())
          $this->date_model->new_day(); 

          //get the date of the last id(the inserted date)
          $idzi = $this->date_model->last_day_id();
          $date = $this->date_model->get_date_by_id($idzi);

          //check to see if the date inserted is the first date of any month(if there is a match, insert sold initial)
          if($date == date_first_day( $this->date_model->last_day_id() ) ) {
            //Compute the initial sold
              $soldchelt = $this->calcul_model->cumul('SumeCheltuieli', $idzi);
              $soldmarfa9 = $this->calcul_model->cumul('SumeMarfaTVA9', $idzi);
              $soldmarfa24 = $this->calcul_model->cumul('SumeMarfaTVA9', $idzi);
              $soldaport = $this->calcul_model->cumul('SumeMarfaTVA9', $idzi);
              $soldinitial =  $this->soldinitial_model->get_sold_initial($idzi);
              $soldfinal = $soldinitial + $soldaport - $soldchelt - $soldmarfa9 - $soldmarfa24;
              //Insert sold initial into the db
              $this->soldinitial_model->new_sold_initial($soldfinal);
          }
          // echo the new date inserted
          echo json_encode(['new_last_day' => 
            $this->parsed_date_to_string( $this->date_model->get_date_by_id(  $this->date_model->last_day_id() ) ) ] );      
     }

     //$date(date array) = result of call to date_parse
     private function parsed_date_to_string($date) {
          return  join('-', [$date['year'], $date['month'], $date['day'] ] );
     }

     private function session_check($idzi) {
          return isset($_SESSION['userdata']) || $this->date_model->last_day_id() == $idzi;
    }

    public function session_check_echo($idzi) {
          echo $this->session_check($idzi);
    }
}