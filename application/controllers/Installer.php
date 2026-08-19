<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Installer extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
    }
   
    public function index()
    {
        
            $data['page_name']		=	'inicio';
            $data['page_title']		=	"Página principal";
            $this->load->view('installer/index.php' , $data);
       
    }
    
    function set_location($param1 = '', $param2 = '')
    {
        if($param1 == 'change')
        {
            $id_cambio = base64_decode($param2);
            $this->session->set_userdata('current_location', $id_cambio);   
            $refer =  $this->agent->referrer();
            redirect($refer, 'refresh');
        }
    }

    function KeyGen()
    {
        $key = md5(microtime());
        $new_key = '';
        for($i=1; $i <= 25; $i ++ ){
                  $new_key .= $key[$i];
                  if ( $i%5==0 && $i != 25) $new_key.='-';
        }
        
        echo strtoupper($new_key);
    }


    function continue()
    {
        $purchase_verify    = $this->verify_purchase(str_replace(' ', '', $this->input->post('key')));
        if($purchase_verify != "non validate")
        {

            if($purchase_verify == 0)
            {
                $data['page_name']		=	'continue';
                $data['page_title']		=	"Continuar instalaciones";
                $data['type_install']   =   "0";
                $data['key']   =   $this->input->post('key') ;
              

            }
            else {
                $data['page_name']		=	'continue';
                $data['page_title']		=	"Continuar instalaciones";
                $data['type_install']   =   "1";
                $data['key']   =  $this->input->post('key');

            }

            $this->load->view('installer/install.php' , $data);

        }else
        {
            $_SESSION['error'] = "El código de instalación no es válido.";
            redirect(base_url().'installer','refresh');
        }

    }

    function complete_install()
    {
        $purchase_verify    = $this->verify_purchase(str_replace(' ', '', $this->input->post('key')));
        if($purchase_verify != "non validate")
        {
            $data['page_name']		=	'continue';
            $data['page_title']		=	"Continuar instalaciones";
            $data['key']   =   $this->input->post('key');

            $this->load->view('installer/complete_install.php' , $data);
        }
      

    }

    function install()
    {
        if($this->input->post('ioncube') != 0 && $this->input->post('curl') != 0)
        {
            
            $hostname = str_replace(' ', '', html_escape($this->input->post('hostname')));
            $username = str_replace(' ', '', html_escape($this->input->post('dbusername')));
            $password = $this->input->post('dbpassword');
            $dbname   = str_replace(' ', '', html_escape($this->input->post('database')));
            $db_connection      = $this->database_connection($hostname, $username, $password, $dbname);


            if($db_connection == 'success'  )
            {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://customer.mayansource.com/api/new_installation',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => '5L',
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => 'new_install='.$this->input->post('type_install').'&type='.$this->input->post('instalation_tp').'&url='.base64_encode(base_url()).'&product=1'.'&ip='.base64_encode($this->input->post('ip')).'&key='.base64_encode($this->input->post('key')),
                    CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                ),
                ));

                $success = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
            
                if($success)
                {

                    $data = read_file('./application/config/database.php');
                    $data = str_replace('dbname',    $dbname,    $data);
                    $data = str_replace('dbusername',   $username,   $data);
                    $data = str_replace('dbpassword',  html_escape($this->input->post('dbpassword')),  $data);           
                    $data = str_replace('dbhostname',   $hostname,   $data);
                    write_file('./application/config/database.php', $data);
                    $data2 = read_file('./application/config/routes.php');
                    $data2 = str_replace('installer','panel',$data2);
                    write_file('./application/config/routes.php', $data2);
                    $this->load->database();
                    $templine = '';
                    $lines = file('./uploads/msbox_sql.sql');
                    foreach ($lines as $line) 
                    {
                        if (substr($line, 0, 2) == '--' || $line == '')
                            continue;
                            $templine .= $line;
                        if (substr(trim($line), -1, 1) == ';') 
                        {
                            $this->db->query($templine);
                            $templine = '';
                        }
                    }
                   
                    $sucursal['name']    = $this->input->post('name_cm');
                    $sucursal['manager'] = '1';    
                    $sucursal['phone']     = $this->input->post('phone_cm');    
                    $sucursal['tel']     = $this->input->post('phone_cm');    
                    $sucursal['address'] = trim($this->input->post('address_cm'));    
                    $sucursal['email']   = $this->input->post('email_cm'); 
                    $sucursal['status']   = 1;    
                    $this->db->insert('branch', $sucursal);
                    
                    $id = $this->db->insert_id();
                    log_message('error',$sucursal['name']);
                    
                    $username = $this->getPin();
                    $password = $this->getPassword();

                    $admin['name']          = $this->input->post('name_ad');
                    $admin['last_name']     = $this->input->post('last_name_ad');
                    $admin['email']         = $this->input->post('email_ad');    
                    $admin['password']      = sha1( $password); 
                    $admin['username']      = $username;
                    $admin['type']          = 1;
                    $admin['phone']         = $this->input->post('phone_ad'); 
                    $admin['productos']     = 1;
                    $admin['reportes']      = 1;
                    $admin['usuarios']      = 1;
                    $admin['ajustes']       = 1;
                    $admin['herramientas']  = 1;
                    $admin['contabilidad']  = 1;
                    $admin['sucursales']    = 1; 
                    $admin['sucursal']      = serialize(array($id));
                    
                    $this->db->insert('admin', $admin);

                    $destino = $this->input->post('email_ad');

                    require("class.phpmailer.php");
                    $emails = $destino;
                    $mail = new PHPMailer();
                    $mail->IsHTML(true);
                    $mail->IsMail();
                    $mail->CharSet = 'UTF-8';
                    $mail->SetFrom('no-reply@msbox.gt', 'Datos de Ingreso');
                    $mail->Subject = 'Datos de Ingreso';
                    $mail->Body = '<b>Nombre:</b> '.$this->input->post('name').' '.$this->input->post('last_name').' <br> <b>Correo Electronico:</b> '. $this->input->post('email').'<br> <b>Usuario:</b>'. $username.'<br> <b>Contraseña:</b>'.$password ;
                    $mail->AddAddress($emails);
                    $mail->Send();
                    $mail->ClearAllRecipients();

                    if($_FILES['logo']['tmp_name'] != '')
                    {
                
                        $logo = $this->db->get_where('settings',array('type'=>'logo'))->row()->description;
                        $datalg['description'] = $md5.str_replace(' ', '', $_FILES['logo']['name']);
            
                        move_uploaded_file($_FILES["logo"]["tmp_name"], "uploads/img/". $md5.str_replace(' ', '', $_FILES['logo']['name']));
                        
                        $this->db->where('type', 'logo' );
                        $this->db->update('settings', $datalg);
            
                    }


                    $datast['description'] = 1;
                    $this->db->where('type','install');
                    $this->db->update('settings',$datast);

                    unlink(APPPATH.'temp_install.txt');

                    redirect(base_url(),'refresh');

                }else {
                    $_SESSION['error'] = "Hubo un problema durante la instalación, verifica tu conexión a internet o contacta a tu proveedor para mas información.";
                    redirect(base_url().'installer','refresh');
                }

            }else {
               
                if( $db_connection != 'success' )
                {

                    $_SESSION['error'] = "No se apodido establecer conexión a la base de datos verifica que el usuario y contraseña es la correcta.";
                    redirect(base_url().'installer','refresh');
                }

            }
        }else{
                $_SESSION['error'] = "Para continuar con la instalación debe tener habilitado el cUrl y IonCube en su servidor.";
                redirect(base_url().'installer','refresh');
        }

    }


    function verify_purchase($purchase_code = "")
    {
        $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://customer.mayansource.com/api/validate_licens',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => '5L',
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'key='.$purchase_code,
                CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                ),
                ));
            
            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            return $response;
    }

    function getPassword() 
    {
        return strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8)); 
    }

    function getPin() 
    {
        return strtoupper(substr(str_shuffle("0123456789"), 0, 6)); 
    }

    //Check if conn is success.
    function database_connection($hostname, $username, $password, $dbname) 
    {
        $link = mysqli_connect($hostname, $username, $password, $dbname);
        if (!$link) 
        {
            mysqli_close($link);
            return 'failed';
        }
        $db_selected = mysqli_select_db($link, $dbname);
        if (!$db_selected) {
        mysqli_close($link);
            return "db_not_exist";
        }
        mysqli_close($link);
        return 'success';
    }
    
    function deleteDir($path  = '') {
        $this->load->helper("file"); 
        delete_files(APPPATH.'views/install', true);
    }

}
 
