<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
 
class Auth extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
       
        $this->load->model('crud_model');
        $this->load->database();
        $this->load->library('session');
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
    }

    public function index()
    {
        if ($this->session->userdata('admin_login') == 1)
        {
            redirect(base_url() . 'admin/tablero/', 'refresh');
        }
    }


    function getBrowser() 
    {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $browser        = "Unknown Browser";
        $browser_array = array(
            '/msie/i'      => 'Internet Explorer',
            '/firefox/i'   => 'Firefox',
            '/safari/i'    => 'Safari',
            '/chrome/i'    => 'Chrome',
            '/edge/i'      => 'Edge',
            '/opera/i'     => 'Opera',
            '/netscape/i'  => 'Netscape',
            '/maxthon/i'   => 'Maxthon',
            '/konqueror/i' => 'Konqueror',
            '/mobile/i'    => 'Handheld Browser'
        );
        foreach ($browser_array as $regex => $value)
            if (preg_match($regex, $user_agent))
                $browser = $value;
                return $browser;
    }

    function formatDate2()
    {
        $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
        $meses = array("Ene","Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic");
        return date('d')." de ".$meses[date('n')-1].". a las ".date('H:i A');
    }

    
    function secure($param1	= '' , $param2 =  '')
    {
		if($param1 == 'login')
		{
            if($this->input->post('gm_id') == "")
            {
                $credential =   array('username' => $this->input->post('username') , 'password' => sha1($this->input->post('password')),'status'=>1);

                $query = $this->db->get_where('admin' , $credential);
                if ($query->num_rows() > 0) 
                {
                    $row = $query->row();
                    $horario = $this->db->get_where('settings', array('type'=>'horario_limite'))->row()->description;
                    $horario_inicio = $this->db->get_where('settings', array('type'=>'horario_inicio'))->row()->description;
                    
                    if( strtotime($horario) >= strtotime(date('H:i')) && strtotime($horario_inicio) < strtotime(date('H:i')) && $row->type == 2 )
                    {

                        $this->session->set_userdata('admin_login', '1');
                        $this->session->set_userdata('login_user_id', $row->admin_id);
                        $this->session->set_userdata('name', $row->name);
                        $this->session->set_userdata('login_type', 'admin');

                        $this->session->set_userdata('usuarios', $row->usuarios);
                        $this->session->set_userdata('productos',$row->productos);
                        $this->session->set_userdata('reportes', $row->reportes);
                        $this->session->set_userdata('herramientas', $row->herramientas);
                        $this->session->set_userdata('contabilidad', $row->contabilidad);
                        $this->session->set_userdata('sucursales', $row->sucursales);
                        $this->session->set_userdata('ajustes', $row->ajustes);

                        $this->session->set_userdata('login_user_type', $row->type);

                        $permisos = $this->db->get_where('job', array('job_id' => $row->job, 'status' => 1))->row()->permissions;
                        $this->session->set_userdata('permissions', $permisos);
                        $this->session->set_userdata('job_id', $row->job);

                        $branchs = unserialize($row->sucursal);
    
                        log_message('error', $branchs[0]);
                        $this->session->set_userdata('branch_id', $branchs[0]);
    
                        $user_browser   = $this->getBrowser();
                        $insert_info = $this->formatDate2()." utilizando ".$user_browser;
                        if($row->last_info == ""){
                            $datas['last_info'] = $insert_info;
                        }
                        else{
                            $datas['last_info'] = $row->current_info;
                        }
                        $datas['current_info'] = $insert_info;
                        $this->db->where('admin_id', $row->admin_id);
                        $this->db->update('admin', $datas);
    
    
                        redirect(base_url() . 'admin/inventario_marca/', 'refresh');
                        
                    }else if( $row->type == 1 ){
                        
                        $this->session->set_userdata('admin_login', '1');
                        $this->session->set_userdata('login_user_id', $row->admin_id);
                        $this->session->set_userdata('name', $row->name);
                        $this->session->set_userdata('login_type', 'admin');
                        $branchs = unserialize($row->sucursal);
    

                        $this->session->set_userdata('usuarios', $row->usuarios);
                        $this->session->set_userdata('productos',$row->productos);
                        $this->session->set_userdata('reportes', $row->reportes);
                        $this->session->set_userdata('herramientas', $row->herramientas);
                        $this->session->set_userdata('contabilidad', $row->contabilidad);
                        $this->session->set_userdata('sucursales', $row->sucursales);
                        $this->session->set_userdata('ajustes', $row->ajustes);

                        $this->session->set_userdata('login_user_type', $row->type);

                        $permisos = $this->db->get_where('job', array('job_id' => $row->job, 'status' => 1))->row()->permissions;
                        $this->session->set_userdata('permissions', $permisos);
                        $this->session->set_userdata('job_id', $row->job);
                        
                        log_message('error', $branchs[0]);
                        $this->session->set_userdata('branch_id', $branchs[0]);
    
                        $user_browser   = $this->getBrowser();
                        $insert_info = $this->formatDate2()." utilizando ".$user_browser;
                        if($row->last_info == ""){
                            $datas['last_info'] = $insert_info;
                        }
                        else{
                            $datas['last_info'] = $row->current_info;
                        }
                        $datas['current_info'] = $insert_info;
                        $this->db->where('admin_id', $row->admin_id);
                        $this->db->update('admin', $datas);
    
    
                        redirect(base_url() . 'admin/tablero/', 'refresh');
                        
                    
                    }else {
                        $this->session->set_flashdata('error' , "La hora limite de acceso a pasado!");
                        redirect(base_url().'panel', 'refresh');
                    }
                   
                    
                }else{
                    $this->session->set_flashdata('error' , "Error en el usuario o contraseña!");
                    redirect(base_url().'panel', 'refresh');
                }


            }else
            {
                $credential =   array('gm_id' => $this->input->post('gm_id'));
                $query = $this->db->get_where('admin' , $credential);
                if ($query->num_rows() > 0) 
                {
                    $row = $query->row();
                    $horario = $this->db->get_where('settings', array('type'=>'horario_limite'))->row()->description;
                    
                    if( strtotime($horario) >= strtotime(date('H:i')) && $row->type == 2 )
                    {

                        $this->session->set_userdata('admin_login', '1');
                        $this->session->set_userdata('login_user_id', $row->admin_id);
                        $this->session->set_userdata('name', $row->name);
                        $this->session->set_userdata('login_type', 'admin');
                        $branchs = unserialize($row->sucursal);
    

                        $this->session->set_userdata('usuarios', $row->usuarios);
                        $this->session->set_userdata('productos',$row->productos);
                        $this->session->set_userdata('reportes', $row->reportes);
                        $this->session->set_userdata('herramientas', $row->herramientas);
                        $this->session->set_userdata('contabilidad', $row->contabilidad);
                        $this->session->set_userdata('sucursales', $row->sucursales);
                        $this->session->set_userdata('ajustes', $row->ajustes);

                        $this->session->set_userdata('login_user_type', $row->type);

                        $permisos = $this->db->get_where('job', array('job_id' => $row->job, 'status' => 1))->row()->permissions;
                        $this->session->set_userdata('permissions', $permisos);

                        
                        log_message('error', $branchs[0]);
                        $this->session->set_userdata('branch_id', $branchs[0]);
    
                        $user_browser   = $this->getBrowser();
                        $insert_info = $this->formatDate2()." utilizando ".$user_browser;
                        if($row->last_info == ""){
                            $datas['last_info'] = $insert_info;
                        }
                        else{
                            $datas['last_info'] = $row->current_info;
                        }
                        $datas['current_info'] = $insert_info;
                        $this->db->where('admin_id', $row->admin_id);
                        $this->db->update('admin', $datas);
    
    
                        redirect(base_url() . 'admin/tablero/', 'refresh');
                        
                    }else if( $row->type == 1 ){
                        
                        $this->session->set_userdata('admin_login', '1');
                        $this->session->set_userdata('login_user_id', $row->admin_id);
                        $this->session->set_userdata('name', $row->name);
                        $this->session->set_userdata('login_type', 'admin');
                        $branchs = unserialize($row->sucursal);
    

                        $this->session->set_userdata('usuarios', $row->usuarios);
                        $this->session->set_userdata('productos',$row->productos);
                        $this->session->set_userdata('reportes', $row->reportes);
                        $this->session->set_userdata('herramientas', $row->herramientas);
                        $this->session->set_userdata('contabilidad', $row->contabilidad);
                        $this->session->set_userdata('sucursales', $row->sucursales);
                        $this->session->set_userdata('ajustes', $row->ajustes);

                        $this->session->set_userdata('login_user_type', $row->type);

                        $permisos = $this->db->get_where('job', array('job_id' => $row->job, 'status' => 1))->row()->permissions;
                        $this->session->set_userdata('permissions', $permisos);
                        
                        log_message('error', $branchs[0]);
                        $this->session->set_userdata('branch_id', $branchs[0]);
    
                        $user_browser   = $this->getBrowser();
                        $insert_info = $this->formatDate2()." utilizando ".$user_browser;
                        if($row->last_info == ""){
                            $datas['last_info'] = $insert_info;
                        }
                        else{
                            $datas['last_info'] = $row->current_info;
                        }
                        $datas['current_info'] = $insert_info;
                        $this->db->where('admin_id', $row->admin_id);
                        $this->db->update('admin', $datas);
    
    
                        redirect(base_url() . 'admin/tablero/', 'refresh');
                        
                    
                    }else {
                        $this->session->set_flashdata('error' , "La hora limite de acceso a pasado!");
                        redirect(base_url().'panel', 'refresh');
                    }
                    
                }else{
                    $this->session->set_flashdata('error' ,"No hay ninguna cuenta vinculado a este correo!");
                    redirect(base_url().'panel', 'refresh');
                }
            }
            
		   
		}
        
    }

    
    function check_m()
    {
        
        $credential =   array('email' => $this->input->post('mail'));
        $query1 = $this->db->get_where('admin' , $credential)->num_rows();

         header('Content-type: application/json; charset=utf-8');
         echo json_encode($query1);
         exit();
    }


    function request()
    {
        if($this->input->post('email') != "")
        {

            $credential =   array('email' => $this->input->post('email'));

            $query1 = $this->db->get_where('admin' , $credential);
            if ($query1->num_rows() > 0) 
            {
                $row = $query1->row();
                $current_date = date('Y-m-d H:i:s'); 
                $new_date = strtotime ( '+30 minute' , strtotime ($current_date)) ; 
                $new_date = date ( 'Y-m-d H:i:s' , $new_date); 

                $info['expire'] = $new_date;
                $info['auth_key'] = base64_encode($query1->row()->admin_id.'*'.sha1(date('d-m-y H:i:s')));
                $this->db->where('admin_id',$query1->row()->admin_id);
                $this->db->update('admin',$info);
            
                $email  = $this->db->get_where('settings', array('type' => 'email'))->row()->description;
                $name   = $this->db->get_where('settings', array('type' => 'name'))->row()->description;
             
                if($this->input->post('email') != "")
                {
                    log_message('error',$this->input->post('email'));
                    
                    require("class.phpmailer.php");
                    
                    $mail = new PHPMailer();
                    $mail->IsHTML(true);
                    $mail->IsMail();
                    $mail->CharSet = 'UTF-8';
                    $mail->SetFrom('no-reply@mayansource.dev', 'Recuperar credenciales de acceso');
                    $mail->Subject = 'Recuperar credenciales de acceso';
                    $mail->Body = '¡Hola! Hemos recibido una solicitud para cambiar tu contraseña el <b>'.date('d').', de '.date('M').' a las '.date('H:i A').'</b>, para continuar y cambiar tu contraseña haz click en el botón de abajo, el enlace expira en 30 minutos.<br>
                                    <p>Si no has sido tú, ignora este mensaje, mantendremos segura tu cuenta.</p><hr>
                                    <a href="'.base_url().'auth/new_request?auth='.$info['auth_key'].'" style="padding: 8px 20px; background-color: #0044e9; border-radius: 5px; color: #fff; font-weight: bolder; font-size: 16px; display: inline-block; margin: 20px 0px; margin-right: 20px; text-decoration: none; -webkit-box-shadow: 0px 2px 14px rgba(0, 68, 233, 0.40);
                                            box-shadow: 0px 2px 14px rgba(0, 68, 233, 0.40);">Recuperar contraseña</a>
                                        <h4 style="margin-bottom: 10px;">¿Necesitas ayuda?</h4>
                                        <div style="color: #A5A5A5; font-size: 12px;">
                                    <p>Si tienes alguna pregunta o duda con respecto a este correo electrónico. Por favor escríbenos a <a href="mailto:'.$name.'" style="text-decoration: underline; color: #4B72FA;">'.$name.'</a>';
                    $mail->AddAddress($this->input->post('email'));
                    if(!$mail->Send()) {
                        echo "Mailer Error: " . $mail->ErrorInfo;
                    }
                    $mail->ClearAllRecipients();
                }

            }

            redirect(base_url(), 'refresh');
        }

        //redirect(base_url(), 'refresh');
        
    }

    public function new_request() 
    {
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?"), 1), $_GET); 
        $page_data['page_name']   = 'new_password';
        $page_data['token']  = $_GET['auth'];
        $this->load->view('backend/auth/new_password');
    }


    public function new_password() 
    {
        $data['password'] = sha1($this->input->post('password'));

        $this->db->where('auth_key', $this->input->post('auth_key'));
        $this->db->update('admin',$data);

        $this->session->set_flashdata('success' ,"Tu contraseña a sido actualizada.");
        redirect(base_url(), 'refresh');
    }


}
