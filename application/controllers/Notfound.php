<?php defined('BASEPATH') OR exit('No direct script access allowed');

require (APPPATH.'/libraries/REST_Controller.php');

Class Notfound extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
    }
    //show list of posts inside category
    public function index(){
        $this->load->view(VIEW_FOLDER.'/notfound', $this->data);
    }

}

