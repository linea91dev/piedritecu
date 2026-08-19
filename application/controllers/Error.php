<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Error extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
    }
   
    public function index()
    { 
    	
    }

    public function error()
    {
		$data['page_name']		=	'error';
		$data['page_title']		=	"No encontramos la URL que estas buscando";
		$this->load->view('frontend/index' , $data);
    }
}