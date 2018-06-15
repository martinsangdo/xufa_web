<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//author: Martin SangDo

class Welcome extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
//        $this->load->helper('app');
//        $this->output->enable_profiler(TRUE);
    }

    /**
     */
    public function index(){
        $this->load->view(VIEW_FOLDER.'/home', $this->data);
    }

}