<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Solicitud extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->library('user_agent');
        $this->load->library('session');
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 2010 05:00:00 GMT");
    }
   
    public function index()
    {
		$data['page_name']		=	'solicitud';
		$data['page_title']		=	"Solicitud de demostración";
		$this->load->view('frontend/index' , $data);
    }
    
    public function send()
    {
        $recaptcha = $_POST["g-recaptcha-response"];
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => '6Lf-6twUAAAAADZuei2FMjBD4Pyr7g4RY3YsE4zy',
            'response' => $recaptcha
        );
        $options = array(
                'http' => array (
                'method' => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $verify = file_get_contents($url, false, $context);
        $captcha_success = json_decode($verify);
        
        if($captcha_success->success)
        {
            $msg = 'Hola, se ha recibido una nueva solicitud de demostración para <b>Mi Aula</b> a tráves del sitio web. Los datos son los siguientes:<br><br>';
            $msg .= '<b>Representante:</b> '.$this->input->post('name')."<br>";
            $msg .= '<b>Celular:</b> '.$this->input->post('phone')."<br>";
            $msg .= '<b>Correo:</b> '.$this->input->post('email')."<br>";
            $msg .= '<b>Institución:</b> '.$this->input->post('school')."<br>";
            $msg .= '<b>Cantidad de Estudiantes:</b> '.$this->input->post('students')."<br>";
            $msg .= '<b>Departamento:</b> '.$this->db->get_where('geo_departamentos', array('id' => $this->input->post('depto')))->row()->nombre."<br>";
            require("class.phpmailer.php");
            $mail = new PHPMailer(); 
            $mail->IsHTML(true);
            $mail->IsMail();
            $mail->CharSet = 'UTF-8';
            $mail->SetFrom('notificacionesmiaula@gmail.com', 'Notificaciones Mi Aula');
            $mail->Subject = 'Nueva solicitud para demo - Mi Aula';
            $data = array(
                'email_msg' => $msg,
                'asuntico' => 'Nueva solicitud para demo'
            );
            $mail->Body = $this->load->view('backend/mails/notify.php',$data,TRUE);    
            $mail->AddAddress('ventas@miaula.com.gt');   
            if(!$mail->Send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
            $this->session->set_flashdata('success_msg' , '1');
            redirect(base_url() . 'solicitud/', 'refresh');
        }else{
            $this->session->set_flashdata('error_msg' , '1');
            redirect(base_url() . 'solicitud/', 'refresh');
        }
    }

}