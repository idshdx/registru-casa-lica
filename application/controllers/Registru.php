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
         return ['Cheltuieli'=>$this->calcul_model->ammount_by_day('SumeCheltuieli', $idzi), 
                    'MarfaTVA9'=> $this->calcul_model->ammount_by_day('SumeMarfaTVA9', $idzi),
                    'MarfaTVA24'=> $this->calcul_model->ammount_by_day('SumeMarfaTVA24', $idzi), 
                    'Aport'=> $this->calcul_model->ammount_by_day('SumeAport', $idzi)];
     }

     public function get_records_json($data) {
          $idzi = $this->date_model->id_by_date($data);
          echo json_encode($this->get_records($idzi));
     }

     private function get_records($idzi) {
          $currentid = $idzi+1;
          $chelt = $this->main_model->get_records('SumeCheltuieli', $idzi);
          $marfa9 = $this->main_model->get_records('SumeMarfaTVA9', $idzi);
          $marfa24 = $this->main_model->get_records('SumeMarfaTVA24', $idzi);
          $aport = $this->main_model->get_records('SumeAport', $idzi);

          $soldinitial_luna = (float)$this->soldinitial_model->get_sold_initial((int)$this->date_model->first_day_of_month($idzi));
          $sold_aport = floatval($this->calcul_model->cumul('SumeAport', $idzi));
          $sold_chelt = floatval($this->calcul_model->cumul('SumeCheltuieli', $idzi));
          $sold_marfa9 = floatval($this->calcul_model->cumul('SumeMarfaTVA9', $idzi));
          $sold_marfa24 = floatval($this->calcul_model->cumul('SumeMarfaTVA24', $idzi));
 

          $soldinitial_zi = $soldinitial_luna + $sold_aport - $sold_chelt - $sold_marfa9 - $sold_marfa24;



          $zi = $this->date_model->id_and_date($idzi);
          $first_date = $this->parsed_date_to_string($this->date_model->first_day_db_entry());     
          $furnizori = $this->get_furnizori();

          $calcule = ['Cheltuieli' => floatval($this->calcul_model->cumul('SumeCheltuieli', $idzi)),
                      'MarfaTVA9' => floatval($this->calcul_model->cumul('SumeMarfaTVA9', $idzi)), 
                      'MarfaTVA24' => floatval($this->calcul_model->cumul('SumeMarfaTVA24', $idzi)), 
                      'Aport' => floatval($this->calcul_model->cumul('SumeAport', $idzi)),
                      'soldinitial' => $soldinitial_zi ];
          
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
          $date = $this->date_model->date_by_id($idzi);

           //Compute the initial sold
          $soldchelt = (float)$this->calcul_model->cumul('SumeCheltuieli', (string)$idzi);
          $soldmarfa9 = (float)$this->calcul_model->cumul('SumeMarfaTVA9', (string)$idzi);
          $soldmarfa24 = (float)$this->calcul_model->cumul('SumeMarfaTVA24', (string)$idzi);
          $soldaport = (float)$this->calcul_model->cumul('SumeAport', (string)$idzi);


          $firstid = (int)$this->date_model->first_day_of_month($idzi -1);

          $soldinitial_la_inceput =  (float)$this->soldinitial_model->get_sold_initial( $firstid );

          /*$finalday = $this->get_total($idzi)['Aport'] - $this->get_total($idzi)['Cheltuieli'] - $this->get_total($idzi)['MarfaTVA9'] - $this->get_total($idzi)['MarfaTVA24'];*/

          $soldfinal_zi = $soldinitial_la_inceput + $soldaport - $soldchelt - $soldmarfa9 - $soldmarfa24;

          /*var_dump($soldfinal_zi);*/
           //check to see if the date inserted is the first day of any month(if there is a match, insert sold initial)
          if($date['day'] == 1) {

              //Insert sold initial into the db
              $toInsert = ['IDZi'=> $idzi, 'SoldInitial' => $soldfinal_zi];
              $this->soldinitial_model->new_sold_initial($toInsert);
          }
          // echo the new date inserted
          echo json_encode(['new_last_day' => 
            $this->parsed_date_to_string( $this->date_model->date_by_id(  $this->date_model->last_day_id() ) ),
                            'sold' => $soldfinal_zi ] );      
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