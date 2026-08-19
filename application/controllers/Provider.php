<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Provider extends CI_Controller
{    
	function __construct()
	{
		parent::__construct();
		$this->load->library('user_agent');
		$this->load->database();
        $this->load->library('session');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
    }
 
 
    public function detalles_compra($param1)
    {
        $page_data['code'] = $param1;
        $this->load->view('provider/details', $page_data);
    }  
    
    function request($param1 ='', $param2=''){
        if($param1 == 'create'){
            $this->crud_model->create_request($param2);
            redirect(base_url() . 'provider/Gracias/', 'refresh');
        }
    }
    
     function Gracias($param1 = '')
    {
        
        $page_data['page_name']  = 'gracias';
        $page_data['page_title'] = "Gracias";
        $this->load->view('provider/gracias', $page_data);
    }
}