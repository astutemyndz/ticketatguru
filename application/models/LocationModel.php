<?php
/**
 * Name:    User Model
 * Author:  Rakesh Maity
 *           rakesh@astutemyndz.com
 * Requirements: PHP5 or above
 *
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class LocationModel extends App_Model {
    private $pjCountryModel;
    private $pjCityModel;
    private $pjStateModel;
    public function __construct()
    {
        parent::__construct();
       
        $this->pjCountryModel = pjCountryModel::factory();
        $this->pjStateModel = pjStateModel::factory();
        $this->pjCityModel = pjCityModel::factory();
        
    }
    public function countries() {
        return $this->pjCountryModel
                    ->orderBy('t1.name ASC')
                    ->findAll()
                    ->getData();
    }
    public function cities($data) {
        return $this->pjCityModel
                    ->where('state_id', $data['state_id'])
                    ->where('country_id', $data['country_id'])
                    ->orderBy('t1.name ASC')
                    ->findAll()
                    ->getData();
    }
    public function states($data) {
        return $this->pjStateModel
                    ->where('country_id', $data['country_id'])
                    ->orderBy('t1.name ASC')
                    ->findAll()
                    ->getData();
    }
    
}
