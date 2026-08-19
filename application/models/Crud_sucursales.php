<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Crud_sucursales extends CI_Model 
{
    function __construct() 
    {
        parent::__construct();
    }

    function clear_cache() 
    {
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    //Funciones de sucursales
    function create_sucursal(){
        $data['name']    = $this->input->post('name');
        $data['manager'] = $this->input->post('manager');    
        $data['phone']   = $this->input->post('phone');    
        $data['tel']     = $this->input->post('tel');    
        $data['address'] = trim($this->input->post('address'));    
        $data['email']   = $this->input->post('email');    
        $this->db->insert('branch', $data);
        $id = $this->db->insert_id();
        
        $data2['sucursal'] = serialize(array($id)); 
        $this->db->where('admin_id',$data['manager']);
        $this->db->update('admin',$data2);
        
        $data_sucursal = $this->db->get_where('admin', array('admin_id'=>1))->row()->sucursal;
        $sucursales = unserialize($data_sucursal);
            array_push($sucursales, $id);
        $data3['sucursal'] = serialize($sucursales); 
        $this->db->where('admin_id','1');
        $this->db->update('admin',$data3);
        
        $message = 'Ha ingresado una nueva sucursal denominada '.$this->input->post('name');
        $this->crud_model->insert_binnacle($message);
        
        $dataB['branch_id']       = $id;
        $dataB['name_account']    = 'Caja Chica';
        $dataB['no_account']      = '00000000';
        $dataB['currency']        = '(Q)';
        $dataB['current_balance'] = '0';
        $this->db->insert('account_bank', $dataB);
        $bank_id = $this->db->insert_id();
        
        $message    = 'Ha ingresado una nueva Cuenta ID: '.$bank_id;
        $this->crud_model->insert_binnacle($message);

        $this->crud_model->insert_notification($message, base64_encode('admin/cuentas/bancarias/'), 'cuentas_bancarias', 'Cuenta_bancaria');
    }

    function update_sucursal($ID){
        $data['name']    = $this->input->post('name');
        $data['manager'] = $this->input->post('manager');    
        $data['phone']   = $this->input->post('phone');    
        $data['tel']     = $this->input->post('tel');    
        $data['address'] = trim($this->input->post('address'));    
        $data['email']   = $this->input->post('email');    
        $this->db->where('branch_id', $ID);
        $this->db->update('branch', $data);

        $message = 'Ha actualizado la sucursal denominada '.$this->input->post('name');
        $this->crud_model->insert_binnacle($message);
        
        $data_sucursal = $this->db->get_where('admin', array('admin_id'=>$data['manager']))->row()->sucursal;
        $sucursales = unserialize($data_sucursal);
        array_push($sucursales, $ID);
        
        $data2['sucursal'] = serialize($ID); 
        $this->db->where('admin_id',$data['manager']);
        $this->db->update('admin',$data2);

    }

    function delete_sucursal($ID){
        $data['status']    = 0;
        $this->db->where('branch_id', $ID);
        $this->db->update('branch', $data);
        
        $manager = $this->db->get_where('branch', array('branch_id'=>$ID))->row()->manager;
        $data_sucursal = $this->db->get_where('admin', array('admin_id'=>$manager))->row()->sucursal;
        $sucursales = unserialize($data_sucursal);
            if (($branch = array_search($ID, $sucursales)) !== false) {
                    unset($sucursales[$branch]);
            }
        $data2['sucursal'] = serialize($sucursales); 
        $this->db->where('admin_id',$manager);
        $this->db->update('admin',$data2);
        
        $data_sucursal = $this->db->get_where('admin', array('admin_id'=>1))->row()->sucursal;
        $sucursales = unserialize($data_sucursal);
            if (($branch = array_search($ID, $sucursales)) !== false) {
                    unset($sucursales[$branch]);
            }
        $data3['sucursal'] = serialize($sucursales); 
        $this->db->where('admin_id','1');
        $this->db->update('admin',$data3);
        
        $dataB['status'] = 0;
        $this->db->where('branch_id',$ID);
        $this->db->update('account_bank',$dataB);
        

        $message = 'Ha eliminado la sucursal denominada '.$this->db->get_where('branch', array('branch_id'=>$ID))->row()->name;
        $this->crud_model->insert_binnacle($message);
    }

   


/*Finaliza el Crud_sucursales.php*/
}
