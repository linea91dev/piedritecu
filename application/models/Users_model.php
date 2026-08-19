<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users_model extends CI_Model {
	
	function __construct()
    {
        parent::__construct();
    }
    
    public function crear_empleado()
    {
        $insert['nombre']    = $this->input->post('nombre');
        $insert['apellido']  = $this->input->post('apellido');
        $insert['celular']   = $this->input->post('celular');
        $insert['usuario']   = $this->input->post('usuario');
        $insert['password']  = sha1($this->input->post('password'));
        $insert['direccion'] = $this->input->post('direccion');
        $insert['salario']   = $this->input->post('salario');
        $this->db->insert('empleado', $insert);
    }
	

}