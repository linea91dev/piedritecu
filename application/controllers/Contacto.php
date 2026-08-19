<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Contacto extends CI_Controller
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
		$data['page_name']		=	'contacto';
		$data['page_title']		=	"Contacto";
		$this->load->view('frontend/index' , $data);
    }
    
    function send()
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
        
        if($this->input->post('name') != '' && $captcha_success->success)
        {
            $msg = 'Nuevo mensaje recibido a tráves del sitio web, los datos son los siguientes: <br><br>';
            $msg .= '<b>Nombre:</b> '.$this->input->post('name')."<br>";
            $msg .= '<b>Correo:</b> '.$this->input->post('email')."<br>";
            $msg .= '<b>Celular:</b> '.$this->input->post('phone')."<br>";
            $msg .= '<b>Mensaje:</b> <br>'.$this->input->post('message');
            require("class.phpmailer.php");
            $mail = new PHPMailer(); 
            $mail->CharSet = 'UTF-8';
            $mail->IsHTML(true);
            $mail->IsMail();
            $mail->addReplyTo($this->input->post('email'),$this->input->post('name'));
            $mail->Subject = 'Nuevo mensaje recibido';
            $mail->SetFrom('notificacionesmiaula@gmail.com', 'Nuevo correo recibido');
            $mail->AddAddress('gwgtacounts@gmail.com');
            $data = array(
                'email_msg' => $msg,
                'asuntico' => 'Nuevo mensaje recibido'
            );
            $mail->Body = $this->load->view('backend/mails/notify.php',$data,TRUE);
            if(!$mail->Send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }
            $this->session->set_flashdata('success_msg' , '1');
            redirect(base_url() . 'contacto/', 'refresh');
        }else{
            $this->session->set_flashdata('error_msg' , '1');
            redirect(base_url() . 'contacto/', 'refresh');
        }    
    }
}