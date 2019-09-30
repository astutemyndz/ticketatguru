<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Package\Component\Dropdown;
class LocationController extends App_Controller
{
    public $dropdown;
    
    private $country;
    private $state;
    private $city;

    private $countryId;
    private $stateId;
    private $cityId;

    
    private $filterData;    

    private $option = array();
    public function __construct()
    {
        parent::__construct();
        $this->loadModels();
        //$this->dropdown = new Dropdown();
    }

    private function loadModels() {
        $this->load->model('LocationModel');
    }
    public function setCountry($value) {
        $this->country = $value;
        return $this;
    }
    public function setState($value) {
        $this->state = $value;
        return $this;
    }
    public function setCity($value) {
        $this->city = $value;
        return $this;
    }
   public function setCountryId($value) {
       $this->countryId = $value;
       return $this;
   }
   public function setStateId($value) {
       $this->stateId = $value;
       return $this;
   }
   public function setCityId($value) {
       $this->cityId = $value;
       return $this;
   }
   

    // public function countries() {
    //     $this->setCountry($this->LocationModel->countries());
    //     foreach($this->country as $country) {
    //         $this->option[] = "<option value='".$country['sortname']."' data-country_id='".$country['id']."'>".$country['name']."</option>";
    //     }
    //     foreach($this->data as $props) {
    //         $this->option[] = $this->dropdown->option($props);
    //     }
    //     pjAppController::jsonResponse(['data' => $this->option]);
    //     exit;
    // }

    public function session() {
        App::printSession();
    }

    public function states() {
        $this->setAjax(true);
		if ($this->isXHR())
		{
            $this->setCountryId($this->input->post('country_id'));
            $this->filterData = array(
                'country_id' => $this->countryId
            );
            $this->setState($this->LocationModel->states($this->filterData));
            if($this->state) {
                foreach($this->state as $state) {
                    $this->option[] = "<option value='".$state['name']."' data-country_id='".$this->countryId."' data-state_id='".$state['state_unique_id']."'>".$state['name']."</option>";
                }
            } else {
                $this->option[] = "<option value=''>select state</option>";
            }
            
            pjAppController::jsonResponse(['data' => $this->option]);
            exit;
        }
        pjAppController::jsonResponse(['data' => [], 'message' => 'Invalid Request!']);
        exit;
    }
    public function cities() {
        $this->setAjax(true);
		if ($this->isXHR())
		{
            $this->setCountryId($this->input->post('country_id'));
            $this->setStateId($this->input->post('state_id'));
            $this->filterData = array(
                'country_id' => $this->countryId,
                'state_id' => $this->stateId,
            );
           // App::dd($this->filterData);
            $this->setCity($this->LocationModel->cities($this->filterData));
            ///App::dd($this->city);
            if($this->city) {
                foreach($this->city as $city) {
                    $this->option[] = "<option value='".$city['name']."' data-country_id='".$this->countryId."' data-state_id='".$this->stateId."' data-city_id='".$city['id']."' data-timezoneid='".$city['timezoneid']."'>".$city['name']."</option>";
                }
            } else {
                $this->option[] = "<option value=''>select city</option>";
            }
            
            pjAppController::jsonResponse(['data' => $this->option]);
            exit;
        }
        pjAppController::jsonResponse(['data' => [], 'message' => 'Invalid Request!']);
        exit;
    }
    


	
}
