<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Flash_lib

{

    private $title;

    private $icon;

    private $message = '';

    private $description;



    private $CI;

    public function __construct() {

        $this->CI = &get_instance();

    }

    public function success($title, $description = null) {



        $this->title = (isset($title)) ? $title : 'Success';

        $this->description = (isset($description)) ? $description : 'Successfully Saved!';



        $this->message = '<div class="alert alert-success" role="alert"><strong>'.$this->title.'!</strong>'.$this->description.'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>';



        return $this->CI->session->set_flashdata('message', $this->message);

    }

    public function warning($title, $description = null) {



        $this->title = (isset($title)) ? $title : 'Warning! ';

        $this->description = (isset($description)) ? $description : 'Warning Alert!';



        $this->message = '<div class="alert alert-warning" role="alert"><strong>'.$this->title.'!</strong>'.$this->description.'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>';



        return $this->CI->session->set_flashdata('message', $this->message);

    }



    public function error($title, $description = null) {
        $this->title = (isset($title)) ? $title : 'Error';
        $this->description = (isset($description)) ? $description : 'Error Alert!';
        $this->message = '<div class="alert alert-danger" role="alert"><strong>'.$this->title.'!</strong>'.$this->description.'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>';
        return $this->CI->session->set_flashdata('message', $this->message);
    }



    public function info($title, $description = null) {



        $this->title = (isset($title)) ? $title : 'Info';

        $this->description = (isset($description)) ? $description : 'Info Alert!';



        $this->message = '<div class="alert bg-info alert-icon-right alert-dismissible mb-2" role="alert"><strong>'.$this->title.'!</strong>'.$this->description.'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>';



        return $this->CI->session->set_flashdata('message', $this->message);

    }

}