<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Provider extends CI_Controller
{    
	function __construct()
	{
		parent::__construct();
		$this->load->library('user_agent');
		$this->load->database();
        $this->load->library('session');
        $this->load->model('crud_model');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
    }
 
 
    public function detalles_compra($param1)
    {
        if ($param1 == 'change_provider') {
            if ($this->session->userdata('admin_login') != 1) {
                redirect(base_url() . 'login', 'refresh');
                return;
            }
            $code = $this->input->post('code');
            $ok = $this->crud_model->change_shopping_provider($code);
            if ($ok) {
                $this->session->set_flashdata('flash_message', 'Proveedor actualizado. Puedes reenviar la solicitud al nuevo proveedor.');
            } else {
                $this->session->set_flashdata('error_message', 'No se pudo cambiar el proveedor.');
            }
            redirect(base_url() . 'provider/detalles_compra/'.$code, 'refresh');
            return;
        }

        if ($param1 == 'split_confirm') {
            if ($this->session->userdata('admin_login') != 1) {
                redirect(base_url() . 'login', 'refresh');
                return;
            }
            $code = $this->input->post('code');
            $result = $this->crud_model->split_shopping_by_providers($code);
            if ($result === false || (is_array($result) && !empty($result['error']))) {
                $msg = is_array($result) && !empty($result['error'])
                    ? $result['error']
                    : 'No se pudo generar las subórdenes.';
                $this->session->set_flashdata('error_message', $msg);
                redirect(base_url() . 'provider/detalles_compra/'.$code, 'refresh');
                return;
            }
            $this->session->set_flashdata('flash_message', $result['message']);
            redirect(base_url() . 'admin/detalles_compra/'.$code, 'refresh');
            return;
        }

        $this->crud_model->ensure_shopping_parent_code();
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
