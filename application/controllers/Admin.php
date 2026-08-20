<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{   
    private $moneda = '';

	function __construct()
	{
		parent::__construct();
		$this->load->library('user_agent');
		$this->load->database();
        $this->load->library('session');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
        $moneda = $this->crud_model->get_info("moneda");
    }
    
    public function hora(){
        echo date('h:i:s');
    }
    
    public function pass($param1){
        echo sha1($param1);
    }
    
    function prbs()
    {
        $data = $this->db->query("SELECT * FROM `lotes` WHERE `code` LIKE '%CMPR%' AND chk_factura = 1")->result_array();    
        foreach($data as $rw)
        {
            $query = $this->db->query("SELECT * FROM `product_details` WHERE `code` LIKE '".$rw['code']."' AND products_id = '".$rw['id_producto']."' AND id_traslado > 0;")->result_array();
            foreach($query as $rww)
            {
                $dbu['chk_factura'] = 1;
                $this->db->where('code', $rww['activity_ref']);
                $this->db->where('id_producto', $rww['products_id']);
                $this->db->update('lotes', $dbu);
            }
        }
        
    }
    function inversion_costo()
    {
        /*$total = 0;
        $data = $this->db->query("SELECT * FROM `lotes` WHERE chk_factura = 1 and fecha <= '2024-05-15' AND existencia <> 0; ")->result_array();    
        foreach($data as $rw)
        {
            $total = $total + ($rw['precio']*$rw['existencia']);
            echo '<br>';
            echo $total;
        }*/
        
        $this->crud_model->downloadExcelProductFactura();
        echo 'Result';
    }
    
    function lotes(){
        $productos = $this->db->query("SELECT * FROM `products`")->result_array();
        //echo 'productos = '. json_encode($productos);
        foreach($productos as $row){
            $data = $this->db->query("SELECT * FROM `lotes` WHERE id_producto = '".$row['products_id']."' and branch_id = 0")->result_array();        
            $tot = 0 ;
            foreach($data as $row2){
                $tot = $tot + $row2['existencia'];
            }
            $stock = $this->crud_model->get_stock_inventario($row['products_id'],0);
            if($tot == $stock){
                echo 'Id producto'.$row['products_id'];
                echo 'Stock '.$tot.' es igual '.$stock;
                echo '<br>';
            }else{
                echo 'Id producto'.$row['products_id'];
                echo 'Stock '.$tot.' es igual '.$stock;
                echo 'Stock no igual-------';
                echo '<br>';
            }
        }
    }

    function control(){
        $datos = $this->db->get('sales')->result_array();
        $i = 1;
        foreach($datos as $row){
            if($row['products'] != "['']" && $row['products'] != "[]"){
                $dat = json_decode($row['products'], true);  
                foreach($dat as $rw){
                    $tipo = 2;
                    if($row['client_id'] > 0){
                        $tipo = $this->db->get_where('client', array('client_id' => $row['client_id']))->row()->type;   
                    }else{
                        $tipo = 2;
                    }
                    $total = $rw['amount']*$rw['price'];
                    $total2 = $rw['amount']*$rw['price_my'];
                    if($tipo == 2){
                        if($rw['sub'] != $total && $rw['discount'] == 0){
                            echo '<b>Código:</b> '.$row['code'].'<br>';
                            echo '<b>CP:</b> '.$this->db->get_where('products', array('products_id' => $rw['product']))->row()->code.'<br>';
                            echo '<b>Cantidad:</b> '.$rw['amount'].'<br>';
                            echo '<b>Precio:</b> '.$rw['price'].'<br>';
                            echo '<b>Precio Mayorista:</b> '.$rw['price_my'].'<br>';   
                            echo '<b>Descuento:</b> '.$rw['discount'].'<br>';   
                            echo '<div style="background:red;color:black">';
                                echo '<b style="color:purple">Normal:</b> Q'.$total.' | '.'<b style="color:orange">Normal original:</b> Q'.$rw['sub'].'<br><br><br><br>'; 
                            echo '</div>';
                            echo '<b>DIFERENCIA:</b> '.$total-$rw['sub'].'<br>';   
                        }
                    }else{
                        if($rw['sub_my'] != $total2 && $rw['discount'] == 0){
                            echo '<b>Código:</b> '.$row['code'].'<br>';
                            echo '<b>CP:</b> '.$this->db->get_where('products', array('products_id' => $rw['product']))->row()->code.'<br>';
                            echo '<b>Cantidad:</b> '.$rw['amount'].'<br>';
                            echo '<b>Precio:</b> '.$rw['price'].'<br>';
                            echo '<b>Precio Mayorista:</b> '.$rw['price_my'].'<br>';   
                            echo '<b>Descuento:</b> '.$rw['discount'].'<br>';   
                            echo '<div style="background:red;color:black">';
                                echo '<b style="color:blue">Mayorista:</b> Q'.$total2.' | '.'<b style="color:green">Mayorista original:</b> Q'.$rw['sub_my'].'<br><br><br><br>';   
                            echo '</div>';
                            echo '<b>DIFERENCIA:</b> '.$total2-$rw['sub_my'].'<br>';   
                        }
                    }
                }
            }
        }
    }
    
    public function reportes_leslie(){
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        
         /*$page_data['ID']         = $user_id;
        $page_data['data']       = $data;
        $page_data['date']       = $date;*/
        $page_data['page_name']  = 'reportes_leslie';
        $page_data['page_title'] = "Leslie";
        $this->load->view('backend/index', $page_data);
    }
    function lesli(){
        $mes = $this->input->post('mes'); 
        $dia = $this->input->post('dia');
        $branch_id = $this->input->post('branch_id');
        echo'mes: '.$mes.' <br>';
        echo'dia: '.$dia.'<br>';
        echo'sucursal: '.$branch_id.'<br>';
        echo '     --------- <br> ';
       echo 'Ganancia por mes:<br> '. $this->crud_model->total_vendido_por_mes2($mes,$branch_id); //Ganancia Mes 
       
       echo '<br>Total ventas del mes <br>'. $this->crud_model->total_vendido_mes2($mes,$branch_id);   //Total vendido mes
       
       echo '<br>Ganancia del dia <br>'. $this->crud_model->total_vendido_por_dia2($dia,$branch_id); //Total ganancia por dia 
       
       echo '<br>capital del dia <br>'. $this->crud_model->capital_vendido_hoy2($dia,$branch_id); //Capital del dia 
       
       echo '<br>Total venta por dia <br>'. $this->crud_model->total_vendido2($dia,$branch_id); //Total vendido por dia 
    } 
    
    public function index()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($this->session->userdata('admin_login') == 1 && $this->session->userdata('login_user_type') == '1')
        {
            redirect(base_url() . 'admin/tablero/', 'refresh');
        }
        if ($this->session->userdata('admin_login') == 1 && $this->session->userdata('login_user_type') == '2')
        {
            redirect(base_url() . 'admin/inventario_marca/', 'refresh');
        }
    }

    public function ver_permisos($permiso){
        $login_user_type = $this->session->userdata('login_user_type');
        $permisos = unserialize($this->session->userdata('permissions'));
        if ($login_user_type == 2 && $permisos[$permiso] != 1) {
            redirect(base_url(), 'refresh');
        }
    }

    public function admin_login()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
    }

    public function deleteAll_nots()
    {
        $data = $this->db->query("SELECT * FROM notificacion WHERE DATEDIFF(NOW(), date) > 30")->result_array();
        foreach($data as $row){
            $this->db->where('notificacion_id', $row['notificacion_id']);
            $this->db->delete('notificacion');
        }
        //$this->db->truncate('notificacion');
    }

    function search_email()
    {
        $res = 1;
        $credential = array('email'=>$this->input->post('email'));

        $email_admin = $this->db->get_where('admin', $credential);
        if($email_admin->num_rows() > 0 ){
            $res = '0';
        }

        $email_client = $this->db->get_where('client', $credential);
        if($email_client->num_rows() > 0 ){
            $res = '0';
        }

        echo $res;
    }

    function read_not($url='', $not_id='')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $this->crud_model->read_notificacion($not_id);
        redirect(base_url() . base64_decode($url), 'refresh');
    }
    
    function actividad($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        
        $user_id = $param1;
        $date = $this->input->post('date');

        if ($user_id == '') {
            $user_id = $this->session->userdata('login_user_id');
        }

        if ($date != '') {
            $date = date('Y-m-d', strtotime($date));
        }
        else {
            $date = date('Y-m-d');
        }
        
        $data = $this->db->query("SELECT * FROM `binnacle` WHERE date LIKE '%$date%' AND user_id = '$user_id' ORDER BY date DESC");
        
        $page_data['ID']         = $user_id;
        $page_data['data']       = $data;
        $page_data['date']       = $date;
        $page_data['page_name']  = 'actividad';
        $page_data['page_title'] = "Actividad de usuario";
        $this->load->view('backend/index', $page_data);
    }
    
    function anulacion($param1 = '')
    {
        $this->admin_login();
        $this->ver_permisos('crear_anulaciones');

        if ($param1 == "confirm")
        {
            $this->crud_model->sale_anulation();
            redirect(base_url().'admin/ventas', 'refresh');
        }

        if($this->input->post('code') == "")
            $page_data['code'] = $param1;
        else
            $page_data['code'] = $this->input->post('code');


        $page_data['page_name']  = 'anulacion';
        $page_data['page_title'] = "Nueva anulación";
        $this->load->view('backend/index', $page_data);
    }
    
    
    function codigos($param1 = '')
    {
        $this->admin_login();
        $this->ver_permisos('codigos');

        if ($param1 == "update")
        {
            $this->ver_permisos('guardar_codigos');
            $this->crud_model->codigos_update();
            redirect(base_url().'admin/codigos', 'refresh');
        }

        $page_data['page_name']  = 'codigos';
        $page_data['page_title'] = "Códigos de autorización";
        $this->load->view('backend/index', $page_data);
    }

    function detalles_venta($param1 = '')
    {
        $this->admin_login();

        if($this->input->post('code') == "")
            $page_data['code'] = $param1;
        else
            $page_data['code'] = $this->input->post('code');


        $page_data['page_name']  = 'detalles_venta';
        $page_data['page_title'] = "Detalles de la venta";

        $login_user_type = $this->session->userdata('login_user_type');
        $permisos = unserialize($this->session->userdata('permissions'));
        if ($login_user_type == 1 || $permisos['ver_ventas'] == 1 || $permisos['creditos'] == 1 || $permisos['ver_anulaciones'] == 1 || $permisos['ver_cambios'] == 1 || $permisos['ver_envios'] == 1 || $permisos['historial_fel'] == 1) {
            $this->load->view('backend/index', $page_data);
        }
        else{
            redirect(base_url(), 'refresh');
        }
    }
    
    function venta_editar($param1 = '', $param2 = '', $param3 = '')
    {
        $this->admin_login();

        if($this->input->post('code') == "")
            $page_data['code'] = $param1;
        else
            $page_data['code'] = $this->input->post('code');
            
        if ($param1 == 'generar') {
                    $sales = $this->db->get_where('sales', array('code'=>$code));
        if($sales->num_rows() <= 0){
            $this->crud_model->apply_to_sale($param2);
            $this->session->set_flashdata('flash_message' , "Venta generada correctamente.");
        }else{
            $this->session->set_flashdata('flash_message' , "Error.");
        }
            redirect(base_url() . 'admin/ventas/', 'refresh');
        }  
        if ($param1 == 'eliminar_producto') {
            $this->crud_model->eliminarPAplicado($param2, $param3);
            $this->session->set_flashdata('flash_message' , "Producto eliminado correctamente.");
            redirect(base_url() . 'admin/venta_editar/'.$param3, 'refresh');
        }
        if ($param1 == 'editar') {
            $this->crud_model->edit_sale($param2);
            $this->session->set_flashdata('flash_message' , "Venta editada correctamente.");
            redirect(base_url() . 'admin/ventas/', 'refresh');
        }
        if ($param1 == 'generar_venta') {
            $return = $this->crud_model->apply_to_sale($param2);
            $this->session->set_flashdata('flash_message' , "Venta generada correctamente.");
            echo $return;
            return $return;
        }
        if ($param1 == 'facturar_venta') {
            $return = $this->crud_model->apply_to_sale($param2);
            $venta = $this->db->get_where('sales',array('code'=>$param2))->row();
            log_message('error',$this->session->userdata('login_user_id').'aplico:'.$param2);
            if($venta->credito == 1){
                //$this->validarXMLCambiaria($param2);
            }
            else{
               // $this->validarXML($param2);   
            }
            $venta2 = $this->db->get_where('sales',array('code'=>$param2))->row();
            if($venta2->FEL== 1 ){
                echo 0;;
            }else{
             echo $return;    
            }
            $this->session->set_flashdata('flash_message' , "Venta generada correctamente.");
            return $return;
        }
        if ($param1 == 'editar_venta') {
            log_message('error',$this->session->userdata('login_user_id').'edito:'.$param2);
            $return = $this->crud_model->edit_sale($param2);
            $this->session->set_flashdata('flash_message' , "Venta editada correctamente.");
            echo $return;
            return $return;
        }


        $page_data['page_name']  = 'venta_editar';
        $page_data['page_title'] = "Editar venta";

        $login_user_type = $this->session->userdata('login_user_type');
        $permisos = unserialize($this->session->userdata('permissions'));
        if ($login_user_type == 1 || $permisos['ver_ventas'] == 1 || $permisos['creditos'] == 1 || $permisos['ver_anulaciones'] == 1 || $permisos['ver_cambios'] == 1 || $permisos['ver_envios'] == 1 || $permisos['historial_fel'] == 1) {
            $this->load->view('backend/index', $page_data);
        } else {
            redirect(base_url(), 'refresh');
        }
    }
    
    function nueva_cotizacion($param1 = '')
    {
        $this->admin_login();
        $this->ver_permisos('crear_cotizaciones');

        $page_data['code']    = $this->crud_model->getCodeCotizacion();
        $page_data['page_name']  = 'nueva_cotizacion';
        $page_data['page_title'] = "Nueva cotización";
        $this->load->view('backend/index', $page_data);
    }

    function historial_fel($param1 = '')
    {
        $this->admin_login();
        $this->ver_permisos('historial_fel');

        $page_data['page_name']  = 'historial_fel';
        $page_data['page_title'] = "Historial de FEL";
        $this->load->view('backend/index', $page_data);
    }

    function get_price(){
        $product_id = $this->input->post('product_id');
        $price = $this->db->get_where('products', array('products_id'=>$product_id))->row()->price;
        echo $price;
    }

    function detalles_cotizacion($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['ID']         = $param1;
        $page_data['page_name']  = 'detalles_cotizacion';
        $page_data['page_title'] = "Detalles";
        $this->load->view('backend/index', $page_data);
    }

    function editar_cotizacion($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['ID']         = $param1;
        $page_data['page_name']  = 'editar_cotizacion';
        $page_data['page_title'] = "Editar cotización";
        $this->load->view('backend/index', $page_data);
    }
    
    function nuevo_cambio($param1 = '')
    {
        $login_user_type = $this->session->userdata('login_user_type');
        $permisos = unserialize($this->session->userdata('permissions'));
        if ($this->session->userdata('admin_login') != 1 && ($login_user_type != 1 || $permisos['crear_cambios'] != 1))
        {
            redirect(base_url(), 'refresh');
        }

        
        if ($param1 == "confirm")
        {
            $this->crud_model->sale_anulation();
            redirect(base_url().'admin/ventas', 'refresh');
        }

        if($this->input->post('code') == "")
            $page_data['code'] = $param1;
        else
            $page_data['code'] = $this->input->post('code');

            
        $page_data['page_name']  = 'nuevo_cambio';
        $page_data['page_title'] = "Nuevo cambio";
        $this->load->view('backend/index', $page_data);
    }
    
    function nueva_solicitud($param1 = '')
    {
        $this->admin_login();
        $this->ver_permisos('crear_solicitud');

        $page_data['code']       = $this->crud_model->getCodeSolicitud();
        $page_data['page_name']  = 'nueva_solicitud';
        $page_data['page_title'] = "Realizar nueva solicitud de compra";
        $this->load->view('backend/index', $page_data);
    }
    
    function codigo_producto()
    {
        $this->admin_login();

        $code = $this->input->post('code');
        $data = $this->db->get_where('products', array('code' => $code, 'status'=> 1))->num_rows();
        if ($data <= 0) {
            echo "0";
        }
        else{
            echo "1";
        }
    }
    
    function nuevo_producto($param1 = '' ,$param2 = '' ,$param3 ='')
    {
        $this->admin_login();
        //$this->ver_permisos('crear_productos');
        if($param1 == 'create')
        {
            $this->ver_permisos('crear_productos');
            $code = $this->crud_model->get_products($this->session->userdata('branch_id'))->row()->code;
            if($this->input->post('code') == $code ){
                $this->session->set_flashdata('flash_message' , "El producto ya esta registrado con codigo: ". $this->input->post('code'));
                redirect(base_url() . 'admin/inventario/', 'refresh');
            }else{
                $this->crud_model->create_product();
                $this->session->set_flashdata('flash_message' , "Producto agregado correctamente.");
                redirect(base_url() . 'admin/inventario/', 'refresh');
            }
            
        }
        
        if($param1 == 'update')
        {
            //$this->ver_permisos('editar_productos');
            log_message("error",'Editar');
            $this->crud_model->update_product($param2);
            $this->session->set_flashdata('flash_message' , "Producto actualizado correctamente.");
            redirect(base_url() . 'admin/editar_producto/'.$param2, 'refresh');
        }
        
        if($param1 == 'delete')
        {   
            $this->ver_permisos('estado_productos');

            $this->crud_model->delete_product($param2);
            $this->session->set_flashdata('flash_message' , "Producto eliminado correctamente.");
            
            redirect(base_url() . 'admin/inventario/', 'refresh');
        }

        $page_data['destino']    = $param1;
        $page_data['page_name']  = 'nuevo_producto';
        $page_data['page_title'] = "Nuevo producto";
        $this->load->view('backend/index', $page_data);
    }
   

    function producto_detalle($param1 = '' ,$param2='' ,$param3 ='')
    {
        $this->admin_login();
        $this->ver_permisos('inventario');

        if ($param1 == 'devolucion') {
            $this->ver_permisos('editar_productos');

            $this->crud_model->devolucion_producto($param2);
            $this->session->set_flashdata('flash_message' , "Producto devuelto registrado.");
            redirect(base_url() . 'admin/producto_detalle/'.$param2, 'refresh');
        }
        if ($param1 == 'perdida') {
            $this->ver_permisos('editar_productos');

            $this->crud_model->perdida_producto($param2);
            $this->session->set_flashdata('flash_message' , "Producto pérdido registrado.");
            redirect(base_url() . 'admin/producto_detalle/'.$param2, 'refresh');
        }
        
        $page_data['ID']         = $param1;
        $page_data['page_name']  = 'producto_detalle';
        $page_data['page_title'] = "Movimientos";
        $this->load->view('backend/index', $page_data);
    }


    function producto_lote($param1 = '' ,$param2='' ,$param3 ='')
    {
        $this->admin_login();
        $this->ver_permisos('crear_productos');

        if($param1  == 'create'){
            $this->ver_permisos('crear_productos');

            $this->crud_model->create_lote();
            $this->session->set_flashdata('flash_message' , "Lote agreagdo correctamente.");
            redirect(base_url() . 'admin/producto_lote/'.$this->input->post('products_id'), 'refresh');

        }

        if($param1  == 'edit'){
            $this->ver_permisos('editar_productos');

            $this->crud_model->edit_lote();
            $this->session->set_flashdata('flash_message' , "Lote actualizado correctamente.");
            redirect(base_url() . 'admin/producto_detalle/'.$this->input->post('products_id'), 'refresh');

        }

        if($param1  == 'delete'){
            $this->ver_permisos('editar_productos');

            $this->crud_model->delete_lote($param2);
            $this->session->set_flashdata('flash_message' , "Lote eliminado correctamente.");
            redirect(base_url() . 'admin/producto_detalle/'.$param3, 'refresh');

        }


        $page_data['ID']         = $param1;
        $page_data['page_name']  = 'producto_lote';
        $page_data['page_title'] = "Nuevo Lote";
        $this->load->view('backend/index', $page_data);
    }

    function producto_traslado($param1 = '' ,$param2='' ,$param3 ='')
    {
        $this->admin_login();
        $this->ver_permisos('crear_traslados');

        $page_data['ID']         = $param1;
        $page_data['page_name']  = 'producto_traslado';
        $page_data['page_title'] = "Traslados";
        $this->load->view('backend/index', $page_data);
    }

    function traslados($param1 = '' ,$param2='' ,$param3 ='')
    {
        $this->admin_login();
        $this->ver_permisos('traslados');
   
        if($param1  == 'create'){
          //  $this->ver_permisos('crear_traslados');

            $this->crud_model->create_traslado();
            $this->session->set_flashdata('flash_message' , "Traslado afectuado correctamente.");
            redirect(base_url() . 'admin/traslados/encabezado', 'refresh');

        }

        if($param1  == 'edit'){
            $this->ver_permisos('editar_traslados');

            $this->crud_model->edit_traslado();
            $this->session->set_flashdata('flash_message' , "Traslado actualizado correctamente.");
            redirect(base_url() . 'admin/traslados/encabezado', 'refresh');

        }

        if($param1  == 'delete'){
            $this->ver_permisos('eliminar_translados');

            $this->crud_model->delete_traslado($param2);
            $this->session->set_flashdata('flash_message' , "Traslado eliminado correctamente.");
            redirect(base_url() . 'admin/traslados/encabezado', 'refresh');

        }

        if($param1  == 'encabezado'){
            $initial = $this->input->post('initial');
            $final   = $this->input->post('final');
            log_message("error", "Initial: $initial, Final: $final");
            if ($initial == '') $initial = date("Y-m-01");
            if ($final == '') $final = date("Y-m-t");
            log_message("error", "Initial: $initial, Final: $final");
            $page_data['ID']         = $param1;
            $page_data['page_name']  = 'traslados2';
            $page_data['page_title'] = "Traslados";
            $page_data['data']       = $this->crud_model->get_transfer2();
            $page_data['id_branch']  = $this->input->post('id_branch');
            $page_data['initial']    = $initial;
            $page_data['final']      = $final;
            $this->load->view('backend/index', $page_data);
        } else {
            $page_data['ID']         = $param1;
            $page_data['page_name']  = 'traslados';
            $page_data['page_title'] = "Traslados";
            $this->load->view('backend/index', $page_data);
        }
        
    }
    function traslados_detalle($param1 = '' ,$param2='' ,$param3 =''){
        $page_data['ID']         = $param1;
        $page_data['page_name']  = 'traslados_detalle';
        $page_data['page_title'] = "Traslados detalle";
        $this->load->view('backend/index', $page_data);
    }

    function cambios($param1 = '' ,$param2='' ,$param3 ='')
    {
        $this->admin_login();
        $this->ver_permisos('cambios');
   
        if($param1  == 'create'){
            $this->ver_permisos('crear_cambios');

            $this->crud_model->new_change();
            $this->session->set_flashdata('flash_message' , "Traslado afectuado correctamente.");
            redirect(base_url() . 'admin/cambios/', 'refresh');

        }
        if($param1  == 'delete'){
            $this->ver_permisos('eliminar_cambios');

            $this->crud_model->delete_change($param2);
            $this->session->set_flashdata('flash_message' , "Traslado eliminado correctamente.");
            redirect(base_url() . 'admin/cambios/', 'refresh');
        }

        $this->ver_permisos('ver_cambios');

        $page_data['page_name']  = 'cambios';
        $page_data['page_title'] = "Cambios";
        $this->load->view('backend/index', $page_data);
    }
    
    function envios($param1 = '', $param2='', $param3 ='')
    {
        $this->admin_login();
        $this->ver_permisos('envios');
   
        if($param1 == 'asignar'){

            $this->crud_model->asignar_fecha($param2);
            $this->session->set_flashdata('flash_message' , "Traslado afectuado correctamente.");
            redirect(base_url() . 'admin/envios/', 'refresh');

        }
        if($param1 == 'cancelar'){
            $this->ver_permisos('cancelar_envios');

            $this->crud_model->cancel_shipping($param2);
            $this->session->set_flashdata('flash_message' , "Traslado eliminado correctamente.");
            redirect(base_url() . 'admin/envios/', 'refresh');
        }

        $this->ver_permisos('ver_envios');

        $page_data['page_name']  = 'envios';
        $page_data['page_title'] = "Envíos pendientes";
        $this->load->view('backend/index', $page_data);
    }

    function entregas($param1 = '', $param2='', $param3 ='')
    {
        $this->admin_login();
        $this->ver_permisos('envios');
   
        if($param1  == 'create'){
            $this->ver_permisos('registrar_entregas');

            $this->crud_model->create_delivery();
            $this->session->set_flashdata('flash_message' , "Entrega realizada correctamente.");
            redirect(base_url() . 'admin/entregas/', 'refresh');

        }
        if($param1  == 'update'){
            $this->ver_permisos('editar_entregas');

            $this->crud_model->update_delivery($param2);
            $this->session->set_flashdata('flash_message' , "Entrega actualizada correctamente.");
            redirect(base_url() . 'admin/entregas/', 'refresh');

        }
        if($param1  == 'delete'){
            $this->ver_permisos('eliminar_entregas');

            $this->crud_model->delete_delivery($param2);
            $this->session->set_flashdata('flash_message' , "Entrega eliminada correctamente.");
            redirect(base_url() . 'admin/entregas/', 'refresh');
        }
        if($param1  == 'completar'){
            $this->ver_permisos('editar_entregas');

            $this->crud_model->completar_entrega($param2);
            $this->session->set_flashdata('flash_message' , "Entrega completada correctamente.");
            redirect(base_url() . 'admin/entregas/', 'refresh');
        }

        $this->ver_permisos('ver_envios');

        $page_data['page_name']  = 'entregas';
        $page_data['page_title'] = "Entregas realizadas";
        $this->load->view('backend/index', $page_data);
    }

    function detalles_entrega($code = '')
    {
        $page_data['code']       = $code;
        $page_data['page_name']  = 'detalles_entrega';
        $page_data['page_title'] = "Detalle de la entrega";
        $this->load->view('backend/index', $page_data);
    }

    function producto_lote_edit($param1 = '' ,$param2='' ,$param3 ='')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['ID']         = $param1;
        $page_data['page_name']  = 'producto_lote_edit';
        $page_data['page_title'] = "Edit Lote";
        $this->load->view('backend/index', $page_data);
    }

    function editar_producto($param1 = '' ,$param2='' ,$param3 ='')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        //$this->ver_permisos('editar_productos');
        
        $page_data['ID']         = $param1;
        $page_data['page_name']  = 'producto_edit';
        $page_data['page_title'] = "Editar producto";
        $this->load->view('backend/index', $page_data);
    }

    
    
    function change_session($id_sucursal = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $data['id_sucursal'] = $id_sucursal;
        $this->db->where('admin_id', $this->session->userdata('login_user_id'));
        $this->db->update('admin', $data);
        $refer =  $this->agent->referrer();
        $this->session->set_userdata('id_sucursal', $id_sucursal);
        redirect($refer, 'refresh');
    }
    
    function sucursales($param1 = '', $param2 = '')
    {
        $this->admin_login();
        $this->ver_permisos('sucursales');

        if($param1 == 'create')
        {
            $this->ver_permisos('crear_sucursales');
            
            $this->crud_sucursales->create_sucursal(); 
            $this->session->set_flashdata('flash_message' , "Sucursal agregada correctamente.");
            redirect(base_url() . 'admin/sucursales/', 'refresh');
        }
        if($param1 == 'update')
        {
            $this->ver_permisos('editar_sucursales');

            $this->crud_sucursales->update_sucursal($param2);
            $this->session->set_flashdata('flash_message' , "Sucursal actualizada correctamente.");
            redirect(base_url() . 'admin/sucursales/', 'refresh');
        }
        if($param1 == 'delete')
        {   
            $this->ver_permisos('eliminar_sucursales');

            $this->crud_sucursales->delete_sucursal($param2);
            $this->session->set_flashdata('flash_message' , "Sucursal eliminada correctamente.");
            redirect(base_url() . 'admin/sucursales/', 'refresh');
        }
        $page_data['page_name']  = 'sucursales';
        $page_data['page_title'] = "Administrar sucursales";
        $this->load->view('backend/index', $page_data);
    }
    
    function make_cierre()
    {
        $datos['status'] = 0;
        $this->db->update('ganancias', $datos);   
        $this->db->update('gastos', $datos);   
    }
    
    function make_datatables()
	{  
        $this->make_query();  
        if($_POST["length"] != -1)  
        {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result();  
    } 
    
    function make_datatables_creditos()
	{  
        $this->make_query_creditos();  
        if($_POST["length"] != -1)  
        {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result();  
    } 
      
    function get_all_data()  
    {  
        $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
        $this->db->select("*");  
        $this->db->from("producto");  
        return $this->db->count_all_results();  
    }  
    
    function get_all_creditos()  
    {  
        $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
        $this->db->select("*");  
        $this->db->from("creditos");  
        return $this->db->count_all_results();  
    } 
    
    function get_all_datas()  
    {  
        $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
        $this->db->select("*");  
        $this->db->from("ventas");  
        return $this->db->count_all_results();  
    }  

/*     function make_query()  
    {  
        $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
        $this->db->select("*");  
        $this->db->from("producto");  
        if(isset($_POST["search"]["value"]))  
        {  
            $this->db->like(" nombre", $_POST["search"]["value"]);  
        }  
        if(isset($_POST["order"]))  
        {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        }  
        else  
        {  
            $this->db->order_by('nombre', 'ASC');  
        }  
    } */
    
    function make_query_creditos()  
    {  
        $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
        $this->db->select("*");  
        $this->db->from("creditos");  
        if(isset($_POST["search"]["value"]))  
        {  
            $this->db->like("cliente", $_POST["search"]["value"]);  
        }  
        if(isset($_POST["order"]))  
        {  
            $index = $_REQUEST["order"][0]['column'];
            $this->db->order_by($_REQUEST["columns"][ $index ]["name"]);  
        }  
        else  
        {  
            $this->db->order_by('id', 'DESC');  
        }  
    }
    
    function make_querys()  
    {  
        $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
        $this->db->select("*");  
        $this->db->from("producto");  
        if(isset($_POST["search"]["value"]))  
        {  
            $this->db->like(" nombre", $_POST["search"]["value"]);  
        }  
        if(isset($_POST["order"]))  
        {  
            $this->db->order_by($this->order_column[$_POST['order']['1']['column']], $_POST['order']['1']['dir']);  
        }  
        else  
        {  
            $this->db->order_by('nombre', 'DESC');  
        }  
    }
      
    function get_filtered_data()
    {  
        $this->make_query();  
        $query = $this->db->get();  
        return $query->num_rows();  
    } 
    
    function get_filtered_data_creditos()
    {  
        $this->make_query_creditos();  
        $query = $this->db->get();  
        return $query->num_rows();  
    } 
    
    function get_orders()
    {
        $fetch_data = $this->make_datatables();  
           $data = array();  
           foreach($fetch_data as $row)  
           {  
                $sub_array = array();  
                $sub_array[] = $row->nombre;  
                $sub_array[] = $row->marca;  
                $sub_array[] = '<a class="btn btn-sm btn-primary" style="color: #fff;"><small>'.$this->db->get_where('categoria', array('codigo' => $row->codigo_categoria))->row()->nombre.'</small></a>';  
                $sub_array[] = '<span style="font-weight:bold;font-size:16px">'.$row->stock.'</span>';  
                $sub_array[] = $row->fecha;  
                $costo = "";
                if($this->session->userdata('login_user_id') == 16){
                    $costo = $moneda.$row->costo;
                }else{
                    $costo = 'N/A';
                }
                $sub_array[] = $costo;  
                $sub_array[] = $moneda.$row->precio;  
                if($this->session->userdata('login_user_id') == 16){
                    $sub_array[] = '<a class="btn btn-info btn-sm" href="#" onclick="showAjaxModal('."'".base_url().'modal/popup/editar_producto/'.$row->id."'".');"><i class="os-icon os-icon-ui-49"></i></a>
                    <a onClick="return confirm('."'¿Está seguro que desea eliminar el producto?'".');" href="'.base_url().'admin/productos/delete/'.$row->id.'"><i class="os-icon os-icon-ui-15"></i></a>';
                }else{
                    $sub_array[] = 'Sin acciones';
                }
                $data[] = $sub_array;  
           }  
           $output = array(  
                "draw"                    =>     intval($_POST["draw"]),  
                "recordsTotal"          =>      $this->get_all_data(),  
                "recordsFiltered"     =>     $this->get_filtered_data(),  
                "data"                  =>     $data  
           );  
           echo json_encode($output); 
    }
    
    function get_creditos()
    {
        $fetch_data = $this->make_datatables_creditos();  
        $data = array();  
        foreach($fetch_data as $row)  
        {  
            $sub_array = array();  
            $sub_array[] = $row->fecha;  
            $sub_array[] = $this->db->get_where('clientes', array('id' => $row->client_id))->row()->nombre;  
            $sub_array[] = '<span style="font-weight:bold;font-size:18px">'.$moneda. number_format($row->total).'</span>';  
            $sub_array[] = '<span style="font-weight:bold;font-size:18px">'.$moneda.number_format($row->restante).'</span>';  
            $status = "";
            if($row->status == 0){
                $status = '<span class="badge badge-danger">En curso</span>';
            }else{
                $status = '<span class="badge badge-success">Cancelado</span>';
            }
            $sub_array[] = $status;  
            $client_id = $this->db->get_where('ventas', array('id' => $row->id_venta))->row()->client_id;
            $sub_array[] = '<a class="badge badge-primary" href="'.base_url().'admin/proyeccion/'.$row->id.'"><i class="picons-thin-icon-thin-0070_paper_role"></i></a>
            <a class="badge badge-success"  target="_blank" href="http://'.$this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->dirip.'/update/login/impresion/?atendio='.$this->db->get_where('admin', array('admin_id' => $row->admin_id))->row()->name.'&productos='.base64_encode($this->db->get_where('ventas', array('id' => $row->id_venta))->row()->productos).'&total='.number_format($this->db->get_where('ventas', array('id' => $row->id_venta))->row()->total).'&fecha='.$this->db->get_where('ventas', array('id' => $row->id_venta))->row()->fecha.'&codigo='.$this->db->get_where('ventas', array('id' => $row->id_venta))->row()->codigo.'&cliente='.$this->db->get_where('clientes', array('id' => $client_id))->row()->nombre.'&direccion='.$this->db->get_where('clientes', array('id' => $client_id))->row()->direccion.'&tel='.$this->db->get_where('clientes', array('id' => $client_id))->row()->telefono.'">Imprimir</a>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"            => intval($_POST["draw"]),  
            "recordsTotal"    => $this->get_all_creditos(),  
            "recordsFiltered" => $this->get_filtered_data_creditos(),  
            "data"            => $data  
        );  
        echo json_encode($output); 
    }

    function nueva_venta($param1='', $param2='')
    {
        $this->admin_login();
        $this->ver_permisos('crear_ventas');
        
        if($param1 !=''){        
            $page_data['pro'] = $param1;
        }
        $page_data['code'] =  $this->crud_model->getCodeVenta();
        $page_data['page_name']  = 'nueva_venta';
        $page_data['page_title'] = "Nueva venta";
        $this->load->view('backend/index', $page_data);
    }

    function nueva_venta_c($param1='', $param2='')
    {
        $this->admin_login();
        $this->ver_permisos('crear_ventas');

        if($param1 !=''){        
            $page_data['code'] = $param1;  
        }
        
        $page_data['page_name']  = 'nueva_venta_c';
        $page_data['page_title'] = "Nueva venta";
        $this->load->view('backend/index', $page_data);
    }

    function editar_venta($param1='')
    {
        $this->admin_login();
        $this->ver_permisos('editar_ventas');

        if($param1 !=''){
            $page_data['code'] = $param1;
            $code = $param1;
        }else{
            $code = $this->crud_model->getCodeVenta();
            $page_data['code'] = $code; 
        }
        
        $sales = $this->db->get_where('sales', array('code'=>$code));
        if($sales->num_rows() <= 0){
            $this->crud_model->create_sale($code);
            $this->session->set_flashdata('flash_message' , "Venta se agregó correctamente");
        }

        $page_data['page_name']  = 'nueva_venta';
        $page_data['page_title'] = "Nueva venta";
        $this->load->view('backend/index', $page_data);
    }
    
    function nuevo_ingreso()
    {
        $this->admin_login();
        $this->ver_permisos('crear_productos');

        $page_data['code']       = $this->crud_model->getCodeIngreso();
        $page_data['page_name']  = 'nuevo_ingreso';
        $page_data['page_title'] = "Registro de ingreso";
        $this->load->view('backend/index', $page_data);
    }
    
    function add_stock() {
        $this->admin_login();
        $this->ver_permisos('crear_productos');
        $this->crud_model->add_stock();
        redirect(base_url().'admin/inventario/', 'refresh');
    }

    function registrar_perdida()
    {
        $this->admin_login();
        $this->ver_permisos('crear_productos');

        $page_data['code']       = $this->crud_model->getCodePerdida();
        $page_data['page_name']  = 'registrar_perdida';
        $page_data['page_title'] = "Registro de ingreso";
        $this->load->view('backend/index', $page_data);
    }
    
    function record_loss(){
        $this->admin_login();
        $this->ver_permisos('crear_productos');
        $this->crud_model->record_loss();
        redirect(base_url().'admin/perdidas/', 'refresh');
    }
    
    function nueva_compra()
    {
        $this->admin_login();
        $this->ver_permisos('crear_compras');

        $page_data['code']       = $this->crud_model->getCodeCompra();
        $page_data['page_name']  = 'nueva_compra';
        $page_data['page_title'] = "Nueva compra";
        $this->load->view('backend/index', $page_data);
    }

    function nueva_compra_s($param1 ='')
    {
        $this->admin_login();
        $this->ver_permisos('completar_solicitud');

        $page_data['code']       = $param1;
        $page_data['page_name']  = 'nueva_compra_s';
        $page_data['page_title'] = "Nueva compra a proveedor";
        $this->load->view('backend/index', $page_data);
    }

    function tablero($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if ($this->session->userdata('login_user_type') == '2') {
            redirect(base_url().'admin/inventario_marca', 'refresh');
        }
        if($param1 == 'cliente')
        {
            $data['id_sucursal'] = $this->session->userdata('id_sucursal');
            $data['nombre'] = $this->input->post('nombre');
            $data['correo'] = $this->input->post('correo');
            $data['telefono'] = $this->input->post('telefono');
            $data['nit'] = $this->input->post('nit');
            $data['direccion'] = $this->input->post('direccion');
            $this->db->insert('clientes', $data);
            $this->session->set_flashdata('flash_message' , "El cliente se agregó correctamente");
            redirect(base_url() . 'admin/tablero/', 'refresh');
        }
        $initial = $this->input->post('initial');
        $final = $this->input->post('final');
        if($initial == '') $initial = date("Y-m-d");
        if($final == '') $final = date("Y-m-d");
        $page_data['page_name']  = 'tablero';
        $page_data['page_title'] = "Tablero de administración";
        $page_data['initial']    = $initial;
        $page_data['final']      = $final;
        $this->load->view('backend/index', $page_data);
    }

    function cuenta($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'editar')
        {
            $data['name'] = $this->input->post('name');
            $data['email'] = $this->input->post('email');
            $data['username'] = $this->input->post('username');
            if($this->input->post('password') != "")
            {
                $data['password'] = sha1($this->input->post('password'));
            }
            $this->db->where('admin_id', $this->session->userdata('login_user_id'));
            $this->db->update('admin', $data);
            $this->session->set_flashdata('flash_message' , "La cuenta se actualizó correctamente");
            redirect(base_url() . 'admin/cuenta/', 'refresh');
       }
        if($param1 == 'cliente')
        {
            $data['nombre'] = $this->input->post('nombre');
            $data['correo'] = $this->input->post('correo');
            $data['telefono'] = $this->input->post('telefono');
            $data['nit'] = $this->input->post('nit');
            $data['direccion'] = $this->input->post('direccion');
            $this->db->insert('clientes', $data);
            $this->session->set_flashdata('flash_message' , "El cliente se agregó correctamente");
            redirect(base_url() . 'admin/tablero/', 'refresh');
        }
        $page_data['page_name']  = 'cuenta';
        $page_data['page_title'] = "Configuración de la cuenta";
        $this->load->view('backend/index', $page_data);
    }

    function registros($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'cliente')
        {
            $data['nombre'] = $this->input->post('nombre');
            $data['id_sucursal'] = $this->session->userdata('id_sucursal');
            $data['correo'] = $this->input->post('correo');
            $data['telefono'] = $this->input->post('telefono');
            $data['nit'] = $this->input->post('nit');
            $data['direccion'] = $this->input->post('direccion');
            $this->db->insert('clientes', $data);
            $this->session->set_flashdata('flash_message' , "El cliente se agregó correctamente");
            redirect(base_url() . 'admin/tablero/', 'refresh');
        }
        $page_data['page_name']  = 'registro';
        $page_data['page_title'] = "Registros diarios";
        $this->load->view('backend/index', $page_data);
    }

    function listado($param1 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'listado';
        $page_data['page_title'] = "Registros diarios";
        $this->load->view('backend/index', $page_data);
    }

    function sales_order_append_entry_response($count , $selected_variants)
    {
        $page_data['count']                 =   $count;
        $page_data['selected_variants']     =   $selected_variants;
        $this->load->view('backend/admin/sales_order_append_entry' , $page_data);
    }

    function sales_order_entry_response($variant_id , $count=1)
    {
        $page_data['variant_id']    =   $variant_id;
        $page_data['count']         =   $count;
        $this->load->view('backend/admin/sales_order_entry' , $page_data);
    }

    function sales_order_add()
    {
        $data['order_code']          =   $this->input->post('order_code');
        $data['seller_user']          =   $this->input->post('seller_user');
        $data['shipping_address']    =   $this->input->post('direccion');
        $data['descripcion']    =   $this->input->post('descripcion');
        $data['gas']    =   $this->input->post('gas');
        $data['date_added']          =   strtotime($this->input->post('fecha'));
        $data['order_status']        =   0;
        $data['payment_status']      =   0;
        if($this->input->post('type') == 1){
            $data['customer_user_id']    =   $this->input->post('customer_user_id');
        }
        else if($this->input->post('type') == 2){
            $data2['nombre'] = $this->input->post('nombre');
            $data2['correo'] = $this->input->post('correo');
            $data2['telefono'] = $this->input->post('telefono');
            $data2['direccion'] = $this->input->post('direccion');
            $data2['id_sucursal'] = $this->session->userdata('id_sucursal');
            $this->db->insert('clientes' , $data2);
            $data['customer_user_id'] = $this->db->insert_id();
        }       
        $data['mes'] = date('M');
        $data['dia'] = date('d');
        $data['anio'] = date('Y');
        $data['total_amount']   =   round($total_amount , 2);
        $data['order_entries']  =   json_encode($sales_order_entries);
        $data['id_sucursal'] = $this->session->userdata('id_sucursal');
        $this->db->insert('sales_order' , $data);
        $this->session->set_flashdata('flash_message' , "La venta se agregó correctamente");
        redirect(base_url() . 'admin/listado/', 'refresh');
    }

    function report_sales_order_view()
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $timestamp_range = $this->input->post('timestamp_range');
        $timestamp_start = strtotime($timestamp_range[0]);
        $timestamp_end   = strtotime($timestamp_range[1]);
        $admin_id = $this->input->post('empleado');
        $page_data['timestamp_start'] = $timestamp_start;
        $page_data['timestamp_end']   = $timestamp_end;
        $page_data['admin_id']   = $admin_id;
        $page_data['page_name']       = 'report_sales_order';
        $page_data['page_title']      = "Reporte de ventas";
        $this->load->view('backend/index', $page_data);
    }

    function sales_order_invoice_print_view($invoice_id)
    {
        if ($this->session->userdata('admin_login') != 1) {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']   = 'sales_order_invoice_print_view';
        $page_data['invoice_id']  = $invoice_id;
        $page_data['page_title']  = "Imprimir factura";
        $this->load->view('backend/admin/sales_order_invoice_print_view', $page_data);
    }

    function viajes($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'cliente')
        {
            $data['nombre'] = $this->input->post('nombre');
            $data['correo'] = $this->input->post('correo');
            $data['telefono'] = $this->input->post('telefono');
            $data['nit'] = $this->input->post('nit');
            $data['direccion'] = $this->input->post('direccion');
            $data['id_sucursal'] = $this->session->userdata('id_sucursal');
            $this->db->insert('clientes', $data);
            $this->session->set_flashdata('flash_message' , "El cliente se agregó correctamente");
            redirect(base_url() . 'admin/tablero/', 'refresh');
        }
        if($param1 == 'delete')
        {
            $this->db->where('order_code', $param2);
            $this->db->delete('sales_order');
            $this->session->set_flashdata('flash_message' , "El viaje se eliminó correctamente");
            redirect(base_url() . 'admin/listado/', 'refresh');
        }
        $page_data['page_name']  = 'viajes';
        $page_data['page_title'] = "Registros diarios";
        $this->load->view('backend/index', $page_data);
    }

    function detalles_viaje($code)
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['code']  = $code;
        $page_data['page_name']  = 'detalles_viaje';
        $page_data['page_title'] = "Detalles del viaje";
        $this->load->view('backend/index', $page_data);
    }

    function cierre()
    {
      require_once 'smsGateway.php';
      $email = "carlos21_94@hotmail.com";
      $pass   = "guatemala21";    
      $device   = "82360";    
      $object = new SmsGateway($email, $pass);
      $data['estado'] = 1;
      $this->db->update('ventas', $data);
      $result = $object->sendMessageToNumber("31993894", "El cierre de ventas diaria se realizó correctamente a las 18:00 Horas.", "82360");
    }

    function cierreviajes()
    {
        require_once 'smsGateway.php';
        $email = "carlos21_94@hotmail.com";
        $pass   = "guatemala21";    
        $device   = "82360";    
        $object = new SmsGateway($email, $pass);
        $data['order_status'] = 1;
        $this->db->update('sales_order', $data);
        $result = $object->sendMessageToNumber("31993894", "El cierre de viajes diarios se realizó correctamente a las 18:00 Horas.", "82360");
    }

    function cierreviajes2()
    {
        require_once 'smsGateway.php';
        $email = "carlos21_94@hotmail.com";
        $pass   = "guatemala21";    
        $device   = "82360";    
        $object = new SmsGateway($email, $pass);
        $data['order_status'] = 1;
        $this->db->update('sales_order', $data);
        $result = $object->sendMessageToNumber("31993894", "El cierre de viajes diarios se realizó correctamente a las 18:00 Horas.", "82360");
        $this->session->set_flashdata('flash_message' , "Los viajes se cerraron correctamente");
        redirect(base_url() . 'admin/listado/', 'refresh');
    }

    function get_nit($id)
    {
        $nit = $this->db->get_where('clientes' , array('id' => $id))->result_array();
        foreach ($nit as $row) 
        {
            echo $row['nit'];
        }
    }

    function regis($param1 = '', $param2 ='')
    {
        $nuevo = "";
        $nuevos = "";
        $mes = date('M');
        $mess = date('M');
        $mesval = "";
        if($mes == "Jan"){$nuevo = "Enero";}
        if($mes == "Feb"){$nuevo = "Febrero";}
        if($mes == "Mar"){$nuevo = "Marzo";}
        if($mes == "Apr"){$nuevo = "Abril";}
        if($mes == "May"){$nuevo = "Mayo";}
        if($mes == "Jun"){$nuevo = "Junio";}
        if($mes == "Jul"){$nuevo = "Julio";}
        if($mes == "Aug"){$nuevo = "Agosto";}
        if($mes == "Sept"){$nuevo = "Septiembre";}
        if($mes == "Oct"){$nuevo = "Octubre";}
        if($mes == "Nov"){$nuevo = "Noviembre";}
        if($mes == "Dec"){$nuevo = "Diciembre";}

        if($mess == "Jan"){$nuevos = 1;}
        if($mess == "Feb"){$nuevos = 2;}
        if($mess == "Mar"){$nuevos = 3;}
        if($mess == "Apr"){$nuevos = 4;}
        if($mess == "May"){$nuevos = 5;}
        if($mess == "Jun"){$nuevos = 6;}
        if($mess == "Jul"){$nuevos = 7;}
        if($mess == "Aug"){$nuevos = 8;}
        if($mess == "Sept"){$nuevos = 9;}
        if($mess == "Oct"){$nuevos = 10;}
        if($mess == "Nov"){$nuevos = 11;}
        if($mess == "Dec"){$nuevos = 12;}
        $data['mes'] = date('d') ." de ". $nuevo ." del ".date('Y');
        $data['cuota'] = $this->input->post('monto');
        $data['id_credito'] = $param1;
        $this->db->insert('cuotas', $data);
        
        $restante = $this->db->get_where('creditos', array('id' => $param1))->row()->restante;
        $cuota = $this->input->post('monto');

        $data2['restante'] = $restante-$cuota;
        if($restante-$cuota == 0){
            $data2['status'] =1;
        }
        $this->db->where('id', $param1);
        $this->db->update('creditos', $data2);
        $this->session->set_flashdata('flash_message' , "La cuota se ingresó correctamente");
        redirect(base_url() . 'admin/proyeccion/'.$param1.'/', 'refresh');
   }
   
   function test()
   {
       if(date('D')!='Mon'){    
                    $staticstart = date('Y-m-d',strtotime('last Monday'));    
                }else{
                    $staticstart = date('Y-m-d');   
                }
                if(date('D') != 'Sun') {
                    $staticfinish = date('Y-m-d',strtotime('next Saturday'));
                }else{
                    $staticfinish = date('Y-m-d');
                }
       echo $this->crud_model->obtener_ganancia($staticstart,$staticfinish);
   }

    function confirmar()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        } 
       $nuevo = "";
        $nuevos = "";
        $mes = date('M');
        $mess = date('M');
        $mesval = "";
        if($mes == "Jan"){$nuevo = "Enero";}
        if($mes == "Feb"){$nuevo = "Febrero";}
        if($mes == "Mar"){$nuevo = "Marzo";}
        if($mes == "Apr"){$nuevo = "Abril";}
        if($mes == "May"){$nuevo = "Mayo";}
        if($mes == "Jun"){$nuevo = "Junio";}
        if($mes == "Jul"){$nuevo = "Julio";}
        if($mes == "Aug"){$nuevo = "Agosto";}
        if($mes == "Sep"){$nuevo = "Septiembre";}
        if($mes == "Oct"){$nuevo = "Octubre";}
        if($mes == "Nov"){$nuevo = "Noviembre";}
        if($mes == "Dec"){$nuevo = "Diciembre";}

        if($mess == "Jan"){$nuevos = 1;}
        if($mess == "Feb"){$nuevos = 2;}
        if($mess == "Mar"){$nuevos = 3;}
        if($mess == "Apr"){$nuevos = 4;}
        if($mess == "May"){$nuevos = 5;}
        if($mess == "Jun"){$nuevos = 6;}
        if($mess == "Jul"){$nuevos = 7;}
        if($mess == "Aug"){$nuevos = 8;}
        if($mess == "Sep"){$nuevos = 9;}
        if($mess == "Oct"){$nuevos = 10;}
        if($mess == "Nov"){$nuevos = 11;}
        if($mess == "Dec"){$nuevos = 12;}
        
        $data['fecha'] = date('d') ." de ". $nuevo ." del ".date('Y');
        $data['client_id'] = $this->input->post('cliente');
        $data['productos'] = serialize($this->cart->contents());
        if($this->input->post('credito') == ""){
         $data['credito'] = "0";   
        }else {
         $data['credito'] = $this->input->post('credito');   
        }
        $interes = $this->cart->total()*$this->input->post('interes')/100;
        $data['total'] = $this->cart->total()+$interes;
        $data['nit'] = $this->input->post('nit');
        $data['direccion'] = $this->input->post('address');
        $data['admin_id'] = $this->session->userdata('login_user_id');
        $data['codigo'] = substr(md5(rand(0, 1000000)), 0, 7);
        $data['cantidades'] = 0;
        $data['variable'] = 1;
        if($this->input->post('credito') == "1")
        {
            $data['restante'] = $this->cart->total()+$interes;
        }else{
            $data['restante'] = "N/A";
        }
        $data['estado'] = 0;
        $data['anio'] = date('Y');
        $data['dia'] = date('d');
        $data['mes'] = date('M');
        $data['entrega'] = "N/A";
        $data['date_added'] = date('d M, Y');
        $data['cliente'] = $this->db->get_where('clientes', array('id' => $this->input->post('cliente')))->row()->nombre;
        $data['id_sucursal'] = $this->session->userdata('id_sucursal');
        
        foreach ($this->cart->contents() as $key) 
        {
            $stock = $this->db->get_where('producto', array('id' => $key['id']))->row()->stock;
            if($key['qty'] <= $stock)
            {            
                $new_stock = $stock-$key['qty'];
                $db['stock'] = $new_stock;
                $this->db->where('id', $key['id']);
                $this->db->update('producto', $db);
                
                $precio = $this->db->get_where('producto', array('id' => $key['id']))->row()->precio-$key['discount'];
                $costo = $this->db->get_where('producto', array('id' => $key['id']))->row()->costo;
                $gan = $precio-$costo;
                $datos['id_producto'] = $key['id'];
                $datos['monto'] = $precio;
                $datos['mes'] = date('m');
                $datos['dia'] = date('d');
                $datos['anio'] = date('Y');
                $datos['status'] = 1;
                $datos['fecha'] = date('Y-m-d');
                $datos['fecha_'] = date('d/m/Y H:i A');
                $datos['cantidad'] = $key['qty'];
                $datos['descuento'] = $key['discount'];
                $datos['ganancia'] = $gan*$key['qty'];
                $datos['id_sucursal'] = $this->session->userdata('id_sucursal');
                $this->db->insert('ganancias', $datos);
            }
        }
        $this->db->insert('ventas', $data);
        $id_venta = $this->db->insert_id();
        if($this->input->post('credito') == "1")
        {
            $data2['client_id'] = $this->input->post('cliente');
            $data2['cliente'] = $this->db->get_where('clientes', array('id' => $this->input->post('cliente')))->row()->nombre;
            $data2['fecha'] = strftime('%d') ." de ". strftime('%B') ." del ".strftime('%Y');
            $data2['admin_id'] = $this->session->userdata('login_user_id');
            $data2['cuotas'] = $this->input->post('cuotas');
            $data2['interes'] = $this->input->post('interes');
            $data2['id_venta'] = $id_venta;
            $data2['status'] = 0;
            $data2['total'] = $this->cart->total();
            $data2['restante'] = $this->cart->total();
            $data2['id_sucursal'] = $this->session->userdata('id_sucursal');
            $this->db->insert('creditos', $data2);
            $id_credito = $this->db->insert_id();
            //Insert de cuota.
            //$data3['id_credito'] = $id_credito;
            //$data3['cuota'] = $this->cart->total();
            //$data3['status'] = 0;
            //$this->db->insert('cuotas', $data3);
        }
        $this->cart->destroy();
        $this->session->set_flashdata('flash_message' , "Carrito actualizado correctamente");
        redirect(base_url() . 'admin/recibo/'. $id_venta .'/', 'refresh');
    }
    
    function updateall()
    {
        $this->db->limit(3000);
        $this->db->where('cliente',NULL);
        $clientes = $this->db->get('ventas')->result_array();
        foreach($clientes as $row){
            $nom = $this->db->get_where('clientes', array('id' => $row['client_id']))->row()->nombre;
            $datas['cliente'] = $nom;
            $this->db->where('id', $row['id']);
            $this->db->update('ventas', $datas);
        }
    }

    function get_address($id)
    {
        $nit = $this->db->get_where('clientes' , array('id' => $id))->result_array();
        foreach ($nit as $row) 
        {
            echo $row['direccion'];
        }
    }

    function marcar($id,$id2)
    {
        $data['status'] = 1;
        $this->db->where('id', $id);
        $this->db->update('cuotas', $data);
        
        $restante = $this->db->get_where('creditos', array('id' => $id2))->row()->restante;
        $cuota = $this->db->get_where('cuotas', array('id' => $id))->row()->cuota;

        $data2['restante'] = $restante-$cuota; 
        $this->db->where('id', $id2);
        $this->db->update('creditos', $data2);
        $this->session->set_flashdata('flash_message' , "La cuota se canceló correctamente");
        redirect(base_url() . 'admin/proyeccion/'.$id2.'/', 'refresh');
    }

    function cancelar($id)
    {
        $data['status'] = 1;
        $this->db->where('id_credito', $id);
        $this->db->update('cuotas', $data);

        $data2['status'] = 1;
        $data2['restante'] = 0;
        $this->db->where('id', $id);
        $this->db->update('creditos', $data2);
        $this->session->set_flashdata('flash_message' , "El crédito se canceló correctamente");
        redirect(base_url() . 'admin/proyeccion/'.$id, 'refresh');
    }

    function update()
    {   
        require_once 'smsGateway.php';
        $email = "carlos21_94@hotmail.com";
        $pass   = "guatemala21";    
        $device   = "82360";    
        $object = new SmsGateway($email, $pass);

        $id = $this->input->post('id');
        $ids = $this->input->post('ids');
        $qty = $this->input->post('cantidad');
        $descuento = $this->input->post('descuento');

        $entries = sizeof($id);
        for($i = 0; $i < $entries; $i++) 
        {
            $stock = $this->db->get_where('producto', array('id' => $ids[$i]))->row()->stock;
            $precio = $this->db->get_where('producto', array('id' => $ids[$i]))->row()->precio;
            $fin = $precio-$descuento[$i];
            $nombre = $this->db->get_where('producto', array('id' => $ids[$i]))->row()->nombre;
            if($qty[$i] <= $stock)
            {
                $data = array(
                    'rowid' => $id[$i],
                    'qty'   => $qty[$i],
                    'price' => $this->db->get_where('producto', array('id' => $ids[$i]))->row()->precio-$descuento[$i],
                    'discount' => $descuento[$i]
                );
                $this->cart->update($data);
            }else{
                //$result = $object->sendMessageToNumber("31993894", "El producto ".$nombre." esta fuera de Stock.", "82360");
                $this->session->set_flashdata('error_message' , "El producto esta fuera de Stock.");
                redirect(base_url() . 'admin/tablero/', 'refresh');
            }
        }            
        $this->session->set_flashdata('flash_message' , "Carrito actualizado correctamente");
        redirect(base_url() . 'admin/tablero/', 'refresh');
    }

    function delete_item($rowid) 
    {   
        $data = array(
            'rowid'   => $rowid,
            'qty'     => 0
        );
        $this->cart->update($data);
        $this->session->set_flashdata('flash_message' , "Carrito actualizado correctamente");
        redirect(base_url() . 'admin/tablero/', 'refresh');
    }

    function sms() 
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://mayansource.com/sms/api/send/sms',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => array('phone' => '+50235125115','message' => 'Hola Mundo'),
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1cmkiOiJodHRwczpcL1wvbXNhbGVydHMuY29tXC8iLCJ1aWQiOiI4MjI5OTg3OSIsInllYXIiOiIyMDIxIiwic2VjcmV0IjoiMW1qaHNvZ3pwbXN5NHFnbXJvIn0.S_P8x7mUlFtEhVilEQpIsRTccndXdN0JqVeI0GhRz5k',
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;



    }

    function cart($param)
    {
        $stock = $this->db->get_where('producto', array('id' => $param))->row()->stock;
        if(1 <= $stock)
        {  
            $data = array(
                'id' => $param,
                'qty' => 1,
                'price' => $this->db->get_where('producto', array('id' => $param))->row()->precio,
                'name' => $this->db->get_where('producto', array('id' => $param))->row()->nombre,
                'discount' => 0
            );
            $this->cart->insert($data);
            $this->session->set_flashdata('flash_message' , "El producto se agregó correctamente");
            redirect(base_url() . 'admin/tablero/', 'refresh');
        }
        elseif(1 > $stock)
        {
            $this->session->set_flashdata('error_message' , "El producto esta fuera de Stock.");
            redirect(base_url() . 'admin/tablero/', 'refresh');
        }
    }

    function cart_view($param1 = '', $param2 = '')
    {
        $data['page_name']      =   'carrito';
        $data['page_title']     =   "Carrito de compras";
        $data['codigo']     =   $param1;
        $this->load->view('frontend/index' , $data);
    }

    function vaciar()
    {
        $this->cart->destroy();
        redirect(base_url(), 'refresh');
    }

    function reportes_generales()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_generales');
        
        $date_initial = $this->input->post('date_initial');
        $date_final = $this->input->post('date_final');
        $branch_id = $this->session->userdata('branch_id');

        if ($date_initial != '' && $date_final != '') {
            $date_initial = date('Y-m-d', strtotime($date_initial));
            $date_final = date('Y-m-d', strtotime($date_final));
        }
        else{
            $date_initial = date('Y-m-d');
            $date_final = date('Y-m-d');
        }

        $page_data['date_initial'] = $date_initial;
        $page_data['date_final']   = $date_final;
        $page_data['page_name']  = 'reportes_generales';
        $page_data['page_title'] = "Reportes generales";
        $this->load->view('backend/index', $page_data);
    }

    function reportes_financieros()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_financiero');

        $page_data['page_name']  = 'reportes_financiero';
        $page_data['page_title'] = "Reportes financiero";
        $this->load->view('backend/index', $page_data);
    }
    
    function ventas_traslados()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_ventas');
        
        $initial = $this->input->post('initial');
        $final = $this->input->post('final');
        $type = $this->input->post('type');
        if ($initial == '') $initial = date("Y-m-01");
        if ($final == '') $final = date("Y-m-t");
        
        $page_data['initial']    = $initial;
        $page_data['final']      = $final;
        $page_data['type']       = $type;
        $page_data['page_name']  = 'ventas_traslados';
        $page_data['page_title'] = "Ventas por cliente";
        $this->load->view('backend/index', $page_data);
    }
    
    function ventas_producto()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_ventas');
        
        $initial = $this->input->post('initial');
        $final = $this->input->post('final');
        $products_id = $this->input->post('products_id');
        if ($initial == '') $initial = date("Y-m-01");
        if ($final == '') $final = date("Y-m-t");
        if ($products_id == 'T') $products_id = '';
        
        $page_data['initial']     = $initial;
        $page_data['final']       = $final;
        $page_data['products_id'] = $products_id;
        $page_data['page_name']   = 'ventas_producto';
        $page_data['page_title']  = "Ventas por producto";
        $this->load->view('backend/index', $page_data);
    }
    
    function ventas_marca()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_ventas');
        
        $initial = $this->input->post('initial');
        $final = $this->input->post('final');
        $mark_id = $this->input->post('mark_id');
        // log_message("error", "Mark ID: $mark_id");
        if ($initial == '') $initial = date("Y-m-01");
        if ($final == '') $final = date("Y-m-t");
        if ($mark_id == 'T') $mark_id = '';
        
        $page_data['initial']    = $initial;
        $page_data['final']      = $final;
        $page_data['mark_id']    = $mark_id;
        $page_data['page_name']  = 'ventas_marca';
        $page_data['page_title'] = "Ventas por marca";
        $this->load->view('backend/index', $page_data);
    }
    
    function reportes_mensual(){
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        
         /*$page_data['ID']         = $user_id;
        $page_data['data']       = $data;
        $page_data['date']       = $date;*/
        $page_data['page_name']  = 'reportes_mensual';
        $page_data['page_title'] = "Reporte Mensual";
        $this->load->view('backend/index', $page_data);
    }
    
    function ventas_mensuales(){
        $result = $this->input->post('mes');
        $mes2 = explode("-",$result);
        $year = $mes2[0];
        $mes = $mes2[1];
        $dia = $this->input->post('dia');
        if ($dia == '') $dia = date("Y-m-d");
        $branch_id = $this->input->post('branch_id');
        $initial = $this->input->post('initial');
        $final = $this->input->post('final');
        log_message("error", "Mes-Año: $result, Mes: $mes, Año: $year, Dia: $dia, Branch ID: $branch_id");
        /*echo'mes: '.$mes.' <br>';
        echo'dia: '.$dia.'<br>';
        echo'sucursal: '.$branch_id.'<br>';
        echo '     --------- <br> ';
        echo 'Ganancia por mes:<br> '. $this->crud_model->total_vendido_por_mes2($mes,$branch_id); //Ganancia Mes 
       
        echo '<br>Total ventas del mes <br>'. $this->crud_model->total_vendido_mes2($mes,$branch_id);   //Total vendido mes
       
        echo '<br>Ganancia del dia <br>'. $this->crud_model->total_vendido_por_dia2($dia,$branch_id); //Total ganancia por dia 
       
        echo '<br>capital del dia <br>'. $this->crud_model->capital_vendido_hoy2($dia,$branch_id); //Capital del dia 
       
        echo '<br>Total venta por dia <br>'. $this->crud_model->total_vendido2($dia,$branch_id); //Total vendido por dia */
       
        $ganancia_mes = $this->crud_model->total_vendido_por_mes2($mes, $year, $branch_id); //Ganancia Mes 
        $Total_vendido_mes = $this->crud_model->total_vendido_mes2($mes, $year, $branch_id);   //Total vendido mes
        
        $ganancia_fechas = $this->crud_model->total_vendido_por_fechas($initial, $final, $branch_id); //Ganancia Fechas
        $Total_vendido_fechas = $this->crud_model->total_vendido_fechas($initial, $final,  $branch_id);   //Total vendido Fechas
        /*
        $Total_ganancia_por_dia = $this->crud_model->total_vendido_por_dia2($dia,$branch_id); //Total ganancia por dia 
        $Capital_del_dia = $this->crud_model->capital_vendido_hoy2($dia,$branch_id); //Capital del dia 
        $Total_vendido_por_dia = $this->crud_model->total_vendido2($dia,$branch_id); //Total vendido por dia
        */
        $page_data['ganancia_mes']              = $ganancia_mes;
        $page_data['Total_vendido_mes']         = $Total_vendido_mes;
        $page_data['ganancia_fechas']           = $ganancia_fechas;
        $page_data['Total_vendido_fechas']      = $Total_vendido_fechas;
        /* $page_data['Total_ganancia_por_dia'] = $Total_ganancia_por_dia;
        $page_data['Capital_del_dia']           = $Capital_del_dia;
        $page_data['Total_vendido_por_dia']     = $Total_vendido_por_dia;
        $page_data['date']                      = $dia; */
        $page_data['year_month']                = $result;
        $page_data['month']                     = $mes;
        $page_data['year']                      = $year;
        $page_data['initial']                   = $initial;
        $page_data['final']                     = $final;
        $page_data['page_name']  = 'ventas_mensual';
        $page_data['page_title'] = "Reporte Mensual";
        $this->load->view('backend/index', $page_data);
    } 
    
    function ventas_afectas()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_ventas');
        
        $initial = $this->input->post('initial');
        $final = $this->input->post('final');
        if ($initial == '') $initial = date("Y-m-01");
        if ($final == '') $final = date("Y-m-t");
        
        $page_data['initial']    = $initial;
        $page_data['final']      = $final;
        $page_data['page_name']  = 'ventas_afectas';
        $page_data['page_title'] = "Ventas afectas";
        $this->load->view('backend/index', $page_data);
    }
    
    function ventas_exentas()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_ventas');
        
        $initial = $this->input->post('initial');
        $final = $this->input->post('final');
        if ($initial == '') $initial = date("Y-m-01");
        if ($final == '') $final = date("Y-m-t");
        
        $page_data['initial']    = $initial;
        $page_data['final']      = $final;
        $page_data['page_name']  = 'ventas_exentas';
        $page_data['page_title'] = "Ventas exentas";
        $this->load->view('backend/index', $page_data);
    }
    
    function cuentas_por_cobrar()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_ventas');
        
        $initial = $this->input->post('initial');
        $final = $this->input->post('final');
        $client_id = $this->input->post('client_id');
        if ($initial == '') $initial = date("Y-m-01");
        if ($final == '') $final = date("Y-m-t");
        if ($client_id == 'T') $client_id = '';
        
        $page_data['initial']    = $initial;
        $page_data['final']      = $final;
        $page_data['client_id'] = $client_id;
        $page_data['page_name']  = 'cuentas_por_cobrar';
        $page_data['page_title'] = "Cuentas por cobrar";
        $this->load->view('backend/index', $page_data);
    }
    
    function cuentas_por_pagar()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_ventas');
        
        $initial = $this->input->post('initial');
        $final = $this->input->post('final');
        $provider_id = $this->input->post('provider_id');
        if ($initial == '') $initial = date("Y-m-01");
        if ($final == '') $final = date("Y-m-t");
        if ($provider_id == 'T') $provider_id = '';
        
        $page_data['initial']     = $initial;
        $page_data['final']       = $final;
        $page_data['provider_id'] = $provider_id;
        $page_data['page_name']   = 'cuentas_por_pagar';
        $page_data['page_title']  = "Cuentas por pagar";
        $this->load->view('backend/index', $page_data);
    }
    
    function ventas_vendedor()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_ventas');
        
        $initial = $this->input->post('initial');
        $final = $this->input->post('final');
        $vendedor_id = $this->input->post('vendedor_id');
        if ($initial == '') $initial = date("Y-m-01");
        if ($final == '') $final = date("Y-m-t");
        if ($vendedor_id == 'T') $vendedor_id = '';
        
        $page_data['initial']     = $initial;
        $page_data['final']       = $final;
        $page_data['vendedor_id'] = $vendedor_id;
        $page_data['page_name']   = 'ventas_vendedor';
        $page_data['page_title']  = "Ventas por vendedor";
        $this->load->view('backend/index', $page_data);
    }
    
    function clientes_por_usuario()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_ventas'); // O cambiar a un permiso apropiado
        
        $initial = $this->input->post('initial');
        $final = $this->input->post('final');
        $usuario_id = $this->input->post('usuario_id');
        if ($initial == '') $initial = date("Y-m-01");
        if ($final == '') $final = date("Y-m-t");
        if ($usuario_id == 'T') $usuario_id = '';
        
        $page_data['initial']     = $initial;
        $page_data['final']       = $final;
        $page_data['usuario_id'] = $usuario_id;
        $page_data['page_name']   = 'clientes_por_usuario';
        $page_data['page_title']  = "Clientes creados por usuario";
        $this->load->view('backend/index', $page_data);
    }
    
    function reportes_ventas()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_ventas');

        $page_data['page_name']  = 'reportes_ventas';
        $page_data['page_title'] = "Reportes de ventas";
        $this->load->view('backend/index', $page_data);
    }
    
    function reportes_compras()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_compras');
        $initial = $this->input->post('initial');
        $final   = $this->input->post('final');
        log_message("error", "Initial: $initial, Final: $final");
        if ($initial == '') $initial = date("Y-m-d");
        if ($final == '') $final = date("Y-m-d");

        $page_data['initial']     = $initial;
        $page_data['final']       = $final;
        $page_data['provider_id'] = $this->input->post('provider_id');
        $page_data['page_name']   = 'reportes_compras';
        $page_data['page_title']  = "Reportes de compras";
        $this->load->view('backend/index', $page_data);
    }
    
    function reportes_anulaciones()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_anulaciones');

        $page_data['page_name']  = 'reportes_anulaciones';
        $page_data['page_title'] = "Reportes de anulaciones";
        $this->load->view('backend/index', $page_data);
    }
    
    function reportes_cambios()
    {
        $this->admin_login();
        $this->ver_permisos('graficas_cambios');

        $page_data['page_name']  = 'reportes_cambios';
        $page_data['page_title'] = "Reportes de cambios";
        $this->load->view('backend/index', $page_data);
    }
    
    function reportes_actividad()
    {
        $this->admin_login();

        $user_type = $this->db->get_where('admin', array('admin_id' => $this->session->userdata('login_user_id')))->row()->type;

        if ($user_type == 1) {
            $user_id = $this->input->post('user_id');
            $date = $this->input->post('date');

            if ($user_id != '' && $date != '') {
                $date = date('Y-m-d', strtotime($date));
                $data = $this->db->query("SELECT * FROM `binnacle` WHERE date LIKE '%$date%' AND user_id = '$user_id' ORDER BY date DESC");
            }
            elseif ($user_id == '' && $date != '') {
                $date = date('Y-m-d', strtotime($date));
                $data = $this->db->query("SELECT * FROM `binnacle` WHERE date LIKE '%$date%' ORDER BY date DESC");
            }
            elseif ($user_id != '' && $date == '') {
                $data = $this->db->query("SELECT * FROM `binnacle` WHERE AND user_id = '$user_id' ORDER BY date DESC");
            }
            elseif($user_id == '' && $date == '') {
                $date = date('Y-m-d');
                $data = $this->db->query("SELECT * FROM `binnacle` WHERE date LIKE '%$date%' ORDER BY date DESC");
            }
        }
        else{
            $user_id = $this->session->userdata('login_user_id');
            $date = $this->input->post('date');
            if ($date != '') {
                $date = date('Y-m-d', strtotime($date));
                $data = $this->db->query("SELECT * FROM `binnacle` WHERE date LIKE '%$date%' AND user_id = '$user_id' ORDER BY date DESC");
            }
            else{
                $date = date('Y-m-d');
                $data = $this->db->query("SELECT * FROM `binnacle` WHERE date LIKE '%$date%' AND user_id = '$user_id' ORDER BY date DESC");
            }
        }

        $page_data['date']       = $date;
        $page_data['data']       = $data;
        $page_data['user_id']    = $user_id;
        $page_data['user_type']  = $user_type;
        $page_data['page_name']  = 'reportes_actividad';
        $page_data['page_title'] = "Reportes de actividad de usuarios";
        $this->load->view('backend/index', $page_data);
    }

    function notificaciones($param1='', $param2='', $param3='')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        if ($param1 == 'delete_all') {
            $this->db->where('user_id', $this->session->userdata('login_user_id'));
            $this->db->delete('notificacion');

            $this->session->set_flashdata('flash_message' , "Se borraron todas las notificaciones");
            redirect(base_url() . 'admin/notificaciones/', 'refresh');
        }
        if ($param1 == 'read_all') {
            $data['readed'] = 1;
            $this->db->where('user_id', $this->session->userdata('login_user_id'));
            $this->db->update('notificacion', $data);

            $this->session->set_flashdata('flash_message' , "Se han marcado todas las notificaciones como vistas");
            redirect(base_url() . 'admin/notificaciones/', 'refresh');
        }
        
        $user_id = $this->session->userdata('login_user_id');
        $branch_id = $this->session->userdata('branch_id');
        $date = $this->input->post('date');

        if ($date != '') {
            $date = date('Y-m-d', strtotime($date));
        }
        else{
            $date = date('Y-m-d');
        }

        $data = $this->db->query("SELECT * FROM `notificacion` WHERE date LIKE '%$date%' AND user_id = '$user_id' ORDER BY date DESC");

        $page_data['date']       = $date;
        $page_data['data']       = $data;
        $page_data['page_name']  = 'notificaciones';
        $page_data['page_title'] = "Notificaciones";
        $this->load->view('backend/index', $page_data);
    }

    function historial_cortes($param1='', $param2='', $param3='')
    {
        $this->admin_login();
        $this->ver_permisos('ver_cortes');

        $date_initial = $this->input->post('date_initial');
        $date_final = $this->input->post('date_final');
        $branch_id = $this->session->userdata('branch_id');

        if ($date_initial != '' && $date_final != '') {
            $date_initial = date('Y-m-d', strtotime($date_initial));
            $date_final = date('Y-m-d', strtotime($date_final));
        }
        else{
            $date_initial = date('Y-m-d');
            $date_final = date('Y-m-d');
        }

        $data = $this->db->query("SELECT * FROM `cash_history` WHERE branch_id = $branch_id AND (date_close BETWEEN '$date_initial' AND '$date_final') ORDER BY date_close DESC");

        $page_data['date_initial'] = $date_initial;
        $page_data['date_final']   = $date_final;
        $page_data['data']         = $data;
        $page_data['page_name']    = 'historial_cortes';
        $page_data['page_title']   = "Cortes de caja";
        $this->load->view('backend/index', $page_data);
    }
    
    function ingresos()
    {
        $this->admin_login();
        $this->ver_permisos('ingresos');

        $page_data['page_name']  = 'ingresos';
        $page_data['page_title'] = "Ingresos";
        $this->load->view('backend/index', $page_data);
    }

    function detalles_ingreso($param1='')
    {
        $this->admin_login();
        $this->ver_permisos('ingresos');

        $page_data['ID']        = $param1;
        $page_data['page_name']  = 'detalles_ingreso';
        $page_data['page_title'] = "Detalle de ingresos";
        $this->load->view('backend/index', $page_data);
    }

    function detalles_egreso($param1='')
    {
        $this->admin_login();
        $this->ver_permisos('ver_egresos');

        $page_data['ID']        = $param1;
        $page_data['page_name']  = 'detalles_egreso';
        $page_data['page_title'] = "Detalle de egresos";
        $this->load->view('backend/index', $page_data);
    }
    
    function egresos($param1 = '', $param2 = '', $param3 = '')
    {
        $this->admin_login();
        $this->ver_permisos('egresos');

        if ($param1 == "create")
        {
            $this->ver_permisos('crear_egresos');

            $this->crud_model->create_expense();
            $this->session->set_flashdata('flash_message' , "Se agrego el gasto correctamente");
            redirect(base_url() . 'admin/egresos/', 'refresh');
        }
        if ($param1 == 'update') {
            $this->ver_permisos('editar_egresos');

            $this->crud_model->update_expense($param2);
            $this->session->set_flashdata('flash_message' , "Se actualizo el gasto correctamente");
            redirect(base_url() . 'admin/egresos/', 'refresh');
        }
        if ($param1 == 'delete') {
            $this->ver_permisos('editar_egresos');
            $this->crud_model->delete_expense($param2);
            $this->session->set_flashdata('flash_message' , "Se ha eliminado el gasto correctamente");
            redirect(base_url() . 'admin/egresos/', 'refresh');
        }

        $this->ver_permisos('ver_egresos');

        if ($param1 == '') {
            $page_data['expenses'] = $this->crud_model->get_expenses();
            $page_data['status']   = 'activos';
        }
        if ($param1 == 'inactivos'){
            $page_data['expenses'] = $this->crud_model->expenses_canceled();
            $page_data['status']   = 'inactivos';
        }
        $page_data['page_name']  = 'egresos';
        $page_data['page_title'] = "Egresos";
        $this->load->view('backend/index', $page_data);
    }

    function expense_tab(){
        $this->admin_login();
        $this->ver_permisos('crear_egresos');

        $this->crud_model->create_expense();
        $this->session->set_flashdata('flash_message' , "Se agrego el gasto correctamente");
        redirect(base_url() . 'admin/egresos/', 'refresh');
    }
    
    function planillas($param1 = '', $param2 = '', $param3 ='', $param4 ='')
    {
        $this->admin_login();
        $this->ver_permisos('planillas');

        if($param1 == 'create')
        {   
            $this->ver_permisos('pagar_planillas');

            $date_start = $this->input->post('date_start');
            $date_end   = $this->input->post('date_end');
            $start      = DateTime::createFromFormat('Y-m-d', $date_start);
            $end        = DateTime::createFromFormat('Y-m-d', $date_end);

            if (!$start || !$end || $start->format('Y-m-d') !== $date_start || $end->format('Y-m-d') !== $date_end || $end < $start) {
                $this->session->set_flashdata('flash_error', "El rango de fechas de la planilla no es válido.");
                redirect(base_url() . 'admin/pagar_planillas/', 'refresh');
                return;
            }

            $payroll_name = $this->input->post('payroll_name');
            if (!in_array($payroll_name, array('Oficial', 'Interna'), true)) {
                $this->session->set_flashdata('flash_error', "Solo se permiten planillas oficial o interna en este módulo.");
                redirect(base_url() . 'admin/pagar_planillas/', 'refresh');
                return;
            }

            $total = $this->crud_model->calculate_payroll_total(
                $this->input->post('employee'),
                $this->input->post('discount'),
                $this->input->post('advance'),
                $this->input->post('remuneration'),
                $date_start,
                $date_end,
                $this->input->post('other_discount'),
                $payroll_name
            );

            if ($total <= 0) {
                $this->session->set_flashdata('flash_error', "El total de la planilla debe ser mayor que cero.");
                redirect(base_url() . 'admin/pagar_planillas/', 'refresh');
                return;
            }

            $this->crud_model->create_payroll(); 
            $this->session->set_flashdata('flash_message' , "Planilla pagada correctamente.");
            redirect(base_url() . 'admin/planillas/', 'refresh');
        }
        if($param1 == 'update')
        {
            $this->ver_permisos('editar_planillas');

            $this->crud_model->update_payroll($param2, $param3);
            $this->session->set_flashdata('flash_message' , "Planilla actualizado correctamente.");
            redirect(base_url() . 'admin/planillas/', 'refresh');
        }
        if($param1 == 'delete')
        {   
            $this->ver_permisos('estado_planillas');
            
            $this->crud_model->delete_payroll($param2);
            $this->session->set_flashdata('flash_message' , "Planilla desactivada correctamente.");
            redirect(base_url() . 'admin/planillas/', 'refresh');
        }
        if($param1 == 'active'){
            $this->ver_permisos('estado_planillas');

            $this->crud_model->active_payroll($param2);
            $this->session->set_flashdata('flash_message' , "Planilla activado correctamente.");
            redirect(base_url() . 'admin/planillas/', 'refresh');
        }
        if($param1 == 'imprimir'){
            $this->ver_permisos('reportes_planillas');

            $this->crud_model->imprimir_payroll($param2,$param3);
            return;
        }
        if($param1 == 'detalle'){
            $this->admin_login();
            $this->ver_permisos('planillas');

            $page_data['page_name']  = 'detalles_planilla';
            $page_data['page_title'] = "Detalle de Planilla";
            $page_data['payroll_id'] = $param2;
            $this->load->view('backend/index', $page_data);
            return;
        }
        $page_data['page_name']  = 'planillas';
        $page_data['page_title'] = "Planillas";
        $this->load->view('backend/index', $page_data);
    }

    function saldo_cuenta()
    {
        $this->admin_login();

        $branch_id = $this->session->userdata('branch_id');
        $bank_id = $this->input->post('bank_id');
        $total = $this->input->post('total');
        if ($bank_id == 0) {
            $saldo = $this->db->get_where('account_bank', array('bank_id' => $bank_id, 'branch_id'=>$branch_id, 'status'=> 1))->row()->current_balance;
        }
        else {
            $saldo = $this->db->get_where('account_bank', array('account_bank_id' => $bank_id))->row()->current_balance;
        }
        $resultado = $saldo - $total;
        if ($resultado > 0) {
            echo "1";
        }
        elseif ($resultado == 0) {
            echo "2";
        }
        elseif ($resultado < 0) {
            echo "3";
        }
    }

    function pagar_planillas()
    {
        $this->admin_login();
        $this->ver_permisos('pagar_planillas');
        
        $page_data['page_name']  = 'pagar_planillas';
        $page_data['page_title'] = "Pago de Planillas";
        $this->load->view('backend/index', $page_data);
    }

    function bonos($param1 = '', $param2 = '', $param3 = '')
    {
        $this->admin_login();
        $this->ver_permisos('planillas');

        if ($param1 == 'create') {
            $this->ver_permisos('pagar_planillas');

            $payroll_name = $this->input->post('payroll_name');
            if (!in_array($payroll_name, array('Bono 14', 'Aguinaldo'), true)) {
                $this->session->set_flashdata('flash_error', "Selecciona Bono 14 o Aguinaldo.");
                redirect(base_url() . 'admin/pagar_bonos/', 'refresh');
                return;
            }

            $date_starts = $this->input->post('emp_date_start');
            $date_ends   = $this->input->post('emp_date_end');
            if (!is_array($date_starts) || !is_array($date_ends)) {
                $this->session->set_flashdata('flash_error', "Debes indicar el período de cada empleado.");
                redirect(base_url() . 'admin/pagar_bonos/', 'refresh');
                return;
            }

            foreach ($date_starts as $i => $row_start) {
                $row_end = isset($date_ends[$i]) ? $date_ends[$i] : '';
                $start = DateTime::createFromFormat('Y-m-d', $row_start);
                $end   = DateTime::createFromFormat('Y-m-d', $row_end);
                if (!$start || !$end || $start->format('Y-m-d') !== $row_start || $end->format('Y-m-d') !== $row_end || $end < $start) {
                    $this->session->set_flashdata('flash_error', "Hay períodos de empleado inválidos.");
                    redirect(base_url() . 'admin/pagar_bonos/', 'refresh');
                    return;
                }
            }

            // Cabecera: usa el primer período como referencia (luego create_payroll ajusta min/max).
            $_POST['date_start'] = $date_starts[0];
            $_POST['date_end']   = $date_ends[0];

            $total = $this->crud_model->calculate_bonus_payroll_total(
                $this->input->post('employee'),
                $this->input->post('salary'),
                $date_starts,
                $date_ends,
                $this->input->post('discount'),
                $this->input->post('advance'),
                $this->input->post('remuneration')
            );

            if ($total <= 0) {
                $this->session->set_flashdata('flash_error', "El total del bono debe ser mayor que cero.");
                redirect(base_url() . 'admin/pagar_bonos/', 'refresh');
                return;
            }

            $payroll_id = $this->crud_model->create_payroll();
            $this->session->set_flashdata('flash_message', "Pago de ".$payroll_name." registrado correctamente.");
            redirect(base_url() . 'admin/bonos/', 'refresh');
        }

        if ($param1 == 'update') {
            $this->ver_permisos('editar_planillas');
            $this->crud_model->update_payroll($param2, $param3);
            $this->session->set_flashdata('flash_message', "Pago actualizado correctamente.");
            redirect(base_url() . 'admin/bonos/', 'refresh');
        }

        if ($param1 == 'delete') {
            $this->ver_permisos('estado_planillas');
            $this->crud_model->delete_payroll($param2);
            $this->session->set_flashdata('flash_message', "Pago desactivado correctamente.");
            redirect(base_url() . 'admin/bonos/', 'refresh');
        }

        if ($param1 == 'active') {
            $this->ver_permisos('estado_planillas');
            $this->crud_model->active_payroll($param2);
            $this->session->set_flashdata('flash_message', "Pago reactivado correctamente.");
            redirect(base_url() . 'admin/bonos/', 'refresh');
        }

        if ($param1 == 'imprimir') {
            $this->ver_permisos('reportes_planillas');
            $this->crud_model->imprimir_payroll($param2, $param3);
            $this->session->set_flashdata('flash_message', "Boleta generada correctamente.");
            return;
        }

        if ($param1 == 'detalle') {
            $page_data['page_name']  = 'detalles_planilla';
            $page_data['page_title'] = "Detalle de Bono";
            $page_data['payroll_id'] = $param2;
            $this->load->view('backend/index', $page_data);
            return;
        }

        $page_data['page_name']  = 'bonos';
        $page_data['page_title'] = "Bono 14 y Aguinaldo";
        $this->load->view('backend/index', $page_data);
    }

    function pagar_bonos()
    {
        $this->admin_login();
        $this->ver_permisos('pagar_planillas');

        $page_data['page_name']  = 'pagar_bonos';
        $page_data['page_title'] = "Pagar Bono 14 / Aguinaldo";
        $this->load->view('backend/index', $page_data);
    }

    function vacaciones($param1 = '', $param2 = '')
    {
        $this->admin_login();
        $this->ver_permisos('vacaciones');

        if ($param1 == 'create') {
            $this->ver_permisos('crear_vacaciones');
            $this->crud_model->create_vacation();
            $this->session->set_flashdata('flash_message', "Vacación registrada correctamente.");
            redirect(base_url() . 'admin/vacaciones/', 'refresh');
        }

        if ($param1 == 'update') {
            $this->ver_permisos('editar_vacaciones');
            $this->crud_model->update_vacation($param2);
            $this->session->set_flashdata('flash_message', "Vacación actualizada correctamente.");
            redirect(base_url() . 'admin/vacaciones/', 'refresh');
        }

        if ($param1 == 'delete') {
            $this->ver_permisos('estado_vacaciones');
            $this->crud_model->delete_vacation($param2);
            $this->session->set_flashdata('flash_message', "Vacación anulada correctamente.");
            redirect(base_url() . 'admin/vacaciones/', 'refresh');
        }

        if ($param1 == 'active') {
            $this->ver_permisos('estado_vacaciones');
            $this->crud_model->active_vacation($param2);
            $this->session->set_flashdata('flash_message', "Vacación reactivada correctamente.");
            redirect(base_url() . 'admin/vacaciones/', 'refresh');
        }

        if ($param1 == 'detalle') {
            $this->ver_permisos('vacaciones');
            $page_data['page_name']   = 'detalles_vacacion';
            $page_data['page_title']  = "Detalle de Vacación";
            $page_data['vacation_id'] = $param2;
            $this->load->view('backend/index', $page_data);
            return;
        }

        $page_data['page_name']  = 'vacaciones';
        $page_data['page_title'] = "Control de Vacaciones";
        $this->load->view('backend/index', $page_data);
    }

    function registrar_vacacion()
    {
        $this->admin_login();
        $this->ver_permisos('crear_vacaciones');

        $page_data['page_name']  = 'registrar_vacacion';
        $page_data['page_title'] = "Registrar Vacación";
        $this->load->view('backend/index', $page_data);
    }
    
    function cuentas_bancarias($param1 = '', $param2 = '')
    {
        $this->admin_login();
        $this->ver_permisos('cuentas_bancarias');
        
        if($param1 == 'create')
        {
            $this->ver_permisos('crear_cuentas');

            $this->crud_model->create_account_bank();
            $this->session->set_flashdata('flash_message' , "Datos agregados correctamente.");
            redirect(base_url() . 'admin/cuentas_bancarias/', 'refresh');
        }
        
        if($param1 == 'update')
        {
            $this->ver_permisos('editar_cuentas');

            $this->crud_model->update_account_bank($param2);
            $this->session->set_flashdata('flash_message' , "Datos actualizados correctamente.");
            redirect(base_url() . 'admin/cuentas_bancarias/', 'refresh');
        }
        
        if($param1 == 'delete')
        {
            $this->ver_permisos('eliminar_cuentas');

            $this->crud_model->delete_account_bank($param2);
            $this->session->set_flashdata('flash_message' , "Datos actualizados correctamente.");
            redirect(base_url() . 'admin/cuentas_bancarias/', 'refresh');
        }
        
        if($param1 == 'transfer_account')
        {
            $this->ver_permisos('transferir_cuentas');

            $this->crud_model->transfer_transaction_bank($param2);
            $this->session->set_flashdata('flash_message' , "Transacción realizada correctamente.");
            redirect(base_url() . 'admin/cuentas_bancarias/', 'refresh');
        }
        
        $page_data['page_name']  = 'cuentas_bancarias';
        $page_data['page_title'] = "Cuentas bancarias";
        $this->load->view('backend/index', $page_data);
    }
    
    function perfil()
    {
        $this->admin_login();

        $page_data['page_name']  = 'perfil';
        $page_data['page_title'] = "Perfil";
        $this->load->view('backend/index', $page_data);
    }
    
    function perfil_admin($admin_id)
    {
        $this->admin_login();
        $this->ver_permisos('admins');

        $page_data['page_name']  = 'perfil_admin';
        $page_data['page_title'] = "Perfil";
        $page_data['admin_id']   = $admin_id;
        $this->load->view('backend/index', $page_data);
    }
    
    function calendario($param1 ='', $param2='')
    {
        $this->admin_login();
        $this->ver_permisos('calendario');

        if($param1 == 'create')
        {
            $this->ver_permisos('crear_eventos');

            $this->crud_model->create_event();
            $this->session->set_flashdata('flash_message' , "Evento agregado correctamente.");
            redirect(base_url() . 'admin/calendario/', 'refresh');
        }
        
        if($param1 == 'update')
        {   
            $this->ver_permisos('editar_eventos');

            $accion = $this->crud_model->update_event($param2);
            if ($accion == 'update') {
                $this->session->set_flashdata('flash_message' , "Evento actualizado correctamente.");
            }
            elseif ($accion == 'delete') {
                $this->session->set_flashdata('flash_message' , "Evento eliminado correctamente.");
            }
            redirect(base_url() . 'admin/calendario/', 'refresh');
        }

        if($param1 == 'update2')
        {
            $this->ver_permisos('editar_eventos');

            $this->crud_model->update_event2($param2);
        }
        
        if($param1 == 'delete')
        {
            $this->ver_permisos('editar_eventos');

            $this->crud_model->delete_event($param2);
            $this->session->set_flashdata('flash_message' , "Evento eliminado correctamente.");
            redirect(base_url() . 'admin/calendario/', 'refresh');
        }
        $branch_id = $this->session->userdata('branch_id');
        $data = $this->db->query("SELECT * FROM events WHERE status = 1 AND (branch_id = $branch_id OR view_all = 1)");
        $page_data['data']       = $data;
        $page_data['page_name']  = 'calendario';
        $page_data['page_title'] = "Calendario";
        $this->load->view('backend/index', $page_data);
    }

    function marcas($param1 ='', $param2='')
    {
        $this->admin_login();
        $this->ver_permisos('marcas');

        if($param1 == 'create')
        {
            $this->ver_permisos('crear_marcas');

            $this->crud_model->create_mark();
            $this->session->set_flashdata('flash_message' , "Marca agregada correctamente.");
            redirect(base_url() . 'admin/marcas/', 'refresh');
        }
        
        if($param1 == 'update')
        {
            $this->ver_permisos('editar_marcas');

            $this->crud_model->update_mark($param2);
            $this->session->set_flashdata('flash_message' , "Marca actualizada correctamente.");
            redirect(base_url() . 'admin/marcas/', 'refresh');
        }
        
        if($param1 == 'delete')
        {
            $this->ver_permisos('eliminar_marcas');

            $this->crud_model->delete_mark($param2);
            $this->session->set_flashdata('flash_message' , "Marca eliminada correctamente.");
            redirect(base_url() . 'admin/marcas/', 'refresh');
        }
        
        if($param1 == 'active')
        {
            $this->ver_permisos('eliminar_marcas');

            $this->crud_model->activar_mark($param2);
            $this->session->set_flashdata('flash_message' , "Marca re-activada correctamente.");
            redirect(base_url() . 'admin/marcas/', 'refresh');
        }


        $page_data['page_name']  = 'marcas';
        $page_data['page_title'] = "Marcas";
        $this->load->view('backend/index', $page_data);
    }
    
    function alertas()
    {
        $this->admin_login();
        $this->ver_permisos('alertas_productos');

        $page_data['page_name']  = 'alertas';
        $page_data['page_title'] = "Productos en alerta";
        $this->load->view('backend/index', $page_data);
    }
    function por_vencer()
    {
        $this->admin_login();
        $this->ver_permisos('alertas_productos');

        $page_data['page_name']  = 'xvencer';
        $page_data['page_title'] = "Productos por Vencer";
        $this->load->view('backend/index', $page_data);
    }
    
    function configuracion($param1='')
    {
        $this->admin_login();
        $this->ver_permisos('configuracion');

        if($param1 == 'sessions')
        {
            
            $this->db->truncate('ci_sessions');    
            $this->session->set_flashdata('flash_message' , "Cambios aplicados correctamente.");
            redirect(base_url(), 'refresh');
        }
        
        if($param1 == 'update'){
            $this->ver_permisos('editar_configuraciones');

            $this->crud_model->update_settings();
            $this->session->set_flashdata('flash_message' , "Sistema actualizado correctamente.");
            redirect(base_url() . 'admin/configuracion/', 'refresh');
        }

        $page_data['page_name']  = 'configuracion';
        $page_data['page_title'] = "Configuración";
        $this->load->view('backend/index', $page_data);
    }

     function impresion($id) 
     {
        $this->admin_login();

        $page_data['id']    =   $id;
        $this->load->view('backend/admin/impresion', $page_data);
    }

    function recibo($id)
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['id']  = $id;
        $page_data['page_name']  = 'recibo';
        $page_data['page_title'] = "Recibo de pago";
        $this->load->view('backend/index', $page_data);
    }

     function proyeccion($id)
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['id']  = $id;
        $page_data['page_name']  = 'proyeccion';
        $page_data['page_title'] = "Proyección de pagos";
        $this->load->view('backend/index', $page_data);
    }

     function proveedores($param1 ='', $param2='', $param3='')
    {
        if ($this->session->userdata('admin_login') != 1 )
        {
            redirect(base_url(), 'refresh');
        }
        
        $this->ver_permisos('proveedores');

        if($param1 == 'create')
        {   
            $this->ver_permisos('crear_proveedores');

           // $data = $this->db->get_where('provider', array('email'=>$this->input->post('email')))->num_rows();
           $data = 0;
            
            if( $data > 0 ){
                $this->session->set_flashdata('flash_error' , "Ya esta ingresado este correo: ".$this->input->post('email'));

            }else{
                $this->crud_model->create_provider(); 
                $this->session->set_flashdata('flash_message' , "Proveedor agregado correctamente.");
            }

            redirect(base_url() . 'admin/proveedores/', 'refresh');
        }
        if($param1 == 'update')
        {
            $this->ver_permisos('editar_proveedores');

            $this->crud_model->update_provider($param2);
            $this->session->set_flashdata('flash_message' , "Proveedor actualizado correctamente.");
            if ($param3 == 1) {
                redirect(base_url() . 'admin/proveedores/', 'refresh');
            }
            elseif ($param3 == 2) {
                redirect(base_url() . 'admin/perfil_proveedor/'.$param2, 'refresh');
            }
        }
        if($param1 == 'delete')
        {   
            $this->ver_permisos('estado_proveedores');

            $this->crud_model->delete_provider($param2);
            $this->session->set_flashdata('flash_message' , "Proveedor desactivado correctamente.");
            redirect(base_url() . 'admin/proveedores/', 'refresh');
        }
        if($param1 == 'active'){
            
            $this->ver_permisos('estado_proveedores');

            $this->crud_model->active_provider($param2);
            $this->session->set_flashdata('flash_message' , "Proveedor activado correctamente.");
            redirect(base_url() . 'admin/proveedores/', 'refresh');
        }

        $page_data['page_name']  = 'proveedores';
        $page_data['page_title'] = "Manejar proveedores";
        $this->load->view('backend/index', $page_data);
    }

    function roles($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $this->ver_permisos('roles');

        if ($param1 == 'create') {
            $this->ver_permisos('crear_roles');

            $this->crud_model->create_rol();
            $this->session->set_flashdata('flash_message', "Rol agregado correctamente");
            redirect(base_url() . 'admin/roles/', 'refresh');
        }
        if ($param1 == 'update') {
            $this->ver_permisos('editar_roles');

            $this->crud_model->update_rol($param2);
            $this->session->set_flashdata('flash_message', "Rol actualizado correctamente");
            redirect(base_url() . 'admin/roles/', 'refresh');
        }
        if ($param1 == 'delete') {
            $this->ver_permisos('estado_roles');

            $this->crud_model->delete_rol($param2);
            $this->session->set_flashdata('flash_message' , "Rol eliminado correctamente");
            redirect(base_url() . 'admin/roles/', 'refresh');
        }
        if ($param1 == 'active') {
            $this->ver_permisos('estado_roles');

            $this->crud_model->active_rol($param2);
            $this->session->set_flashdata('flash_message' , "Rol activado correctamente");
            redirect(base_url() . 'admin/roles/', 'refresh');
        }
        $page_data['page_name'] = 'roles';
        $page_data['page_title'] = "Manejar puestos";
        $this->load->view('backend/index', $page_data);
    }

    function agregar_rol()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }

        $this->ver_permisos('crear_roles');

        $page_data['page_name']  = 'agregar_rol';
        $page_data['page_title'] = "Agregar un nuevo puesto";
        $this->load->view('backend/index', $page_data);
    }

    function editar_rol($ID)
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        
        $this->ver_permisos('editar_roles');

        $page_data['rol_id']     = $ID;
        $page_data['page_name']  = 'editar_rol';
        $page_data['page_title'] = "Editar un puesto";
        $this->load->view('backend/index', $page_data);
    }

    function empleados($param1 ='', $param2='')
    {
        $this->admin_login();
        $this->ver_permisos('empleados');

        if($param1 == 'create')
        {   
            $this->ver_permisos('crear_empleados');           
            $this->crud_model->create_employee(); 
            $this->session->set_flashdata('flash_message' , "Empleado agregado correctamente.");
            redirect(base_url() . 'admin/empleados/', 'refresh');
        }
        if($param1 == 'update')
        {
            $this->ver_permisos('editar_empleados');

            $this->crud_model->update_employee($param2);
            $this->session->set_flashdata('flash_message' , "Empleado actualizado correctamente.");
            redirect(base_url() . 'admin/empleados/', 'refresh');
        }
        if($param1 == 'delete')
        {   
            $this->ver_permisos('estado_empleados');

            $this->crud_model->delete_employee($param2);
            $this->session->set_flashdata('flash_message' , "Empleado desactivado correctamente.");
            redirect(base_url() . 'admin/empleados/', 'refresh');
        }
        if($param1 == 'active'){
            $this->ver_permisos('estado_empleados');

            $this->crud_model->active_employee($param2);
            $this->session->set_flashdata('flash_message' , "Empleado activado correctamente.");
            redirect(base_url() . 'admin/empleados/', 'refresh');
        }
        $page_data['page_name']  = 'empleados';
        $page_data['page_title'] = "Manejar empleados";
        $this->load->view('backend/index', $page_data);
    }

    function agregar_empleado(){
        $this->admin_login();
        $this->ver_permisos('crear_empleados');

        $page_data['page_name']  = 'agregar_empleado';
        $page_data['page_title'] = "Manejar empleados";
        $this->load->view('backend/index', $page_data);
    }

    function empleados_edit($param1){
        $this->admin_login();
        $this->ver_permisos('editar_empleados');

        $page_data['ID']         = $param1;
        $page_data['page_name']  = 'empleados_edit';
        $page_data['page_title'] = "Manejar empleados";
        $this->load->view('backend/index', $page_data);
    }

    function perfil_empleado($param1){
        $this->admin_login();
        $this->ver_permisos('empleados');

        $page_data['ID']         = $param1;
        $page_data['page_name']  = 'perfil_empleado';
        $page_data['page_title'] = "Manejar empleados";
        $this->load->view('backend/index', $page_data);
    }
    
    function perfil_proveedor($param1){
        $this->admin_login();
        $this->ver_permisos('proveedores');

        $historial = $this->db->query("SELECT s.shopping_id, s.type, s.code, s.total, s.date, s.responsable FROM shopping AS s INNER JOIN product_details AS p ON s.code = p.activity_ref WHERE p.provider = '$ID' GROUP BY p.activity_ref");
        $page_data['ID']         = $param1;
        $page_data['historial']  = $historial;
        $page_data['page_name']  = 'perfil_proveedor';
        $page_data['page_title'] = "Perfil de proveedor";
        $this->load->view('backend/index', $page_data);
    }
    
    function inventario()
    {
        $this->admin_login();
        
        if ($this->session->userdata('login_user_type') == 2 && $this->session->userdata('login_user_id') != '45') {
            redirect(base_url(), 'refresh');
        }

        $page_data['page_name']  = 'inventario';
        $page_data['page_title'] = "Inventario";
        $this->load->view('backend/index', $page_data);
    }
    
    function inventario_marca()
    {
        $this->admin_login();
        $this->ver_permisos('inventario');
        $mark_id = $this->input->post('mark_id');
        if ($mark_id == 'T') $mark_id = '';

        $page_data['mark_id']    = $mark_id;
        $page_data['page_name']  = 'inventario_marca';
        $page_data['page_title'] = "Inventario";
        $this->load->view('backend/index', $page_data);
    }
    
    function increases()
    {
        $this->admin_login();
        if ($this->session->userdata('login_user_type') == 2) {
            redirect(base_url(), 'refresh');
        }

        $initial = $this->input->post('initial');
        $final   = $this->input->post('final');
        // log_message("error", "Initial: $initial, Final: $final");
        if ($initial == '') $initial = date("Y-m-01");
        if ($final == '') $final = date("Y-m-t");
        // log_message("error", "Initial: $initial, Final: $final");
        
        $page_data['page_name']  = 'increases';
        $page_data['page_title'] = "Entradas";
        $page_data['initial']    = $initial;
        $page_data['final']      = $final;
        $this->load->view('backend/index', $page_data);
    }
    
    function ingreso_detalles($param1 = '')
    {
        $this->admin_login();
        if ($this->session->userdata('login_user_type') == 2) {
            redirect(base_url(), 'refresh');
        }

        if($this->input->post('code') == "")
            $page_data['code'] = $param1;
        else
            $page_data['code'] = $this->input->post('code');

        $page_data['page_name']  = 'ingreso_detalles';
        $page_data['page_title'] = "Detalles de ingreso";
        $this->load->view('backend/index', $page_data);
    }

    function perdidas()
    {
        $this->admin_login();
        if ($this->session->userdata('login_user_type') == 2) {
            redirect(base_url(), 'refresh');
        }

        $initial = $this->input->post('initial');
        $final   = $this->input->post('final');
        // log_message("error", "Initial: $initial, Final: $final");
        if ($initial == '') $initial = date("Y-m-01");
        if ($final == '') $final = date("Y-m-t");
        // log_message("error", "Initial: $initial, Final: $final");
        
        $page_data['page_name']  = 'perdidas';
        $page_data['page_title'] = "Pérdidas";
        $page_data['initial']    = $initial;
        $page_data['final']      = $final;
        $this->load->view('backend/index', $page_data);
    }
    
    function perdida_detalles($param1 = '')
    {
        $this->admin_login();
        if ($this->session->userdata('login_user_type') == 2) {
            redirect(base_url(), 'refresh');
        }

        if($this->input->post('code') == "")
            $page_data['code'] = $param1;
        else
            $page_data['code'] = $this->input->post('code');

        $page_data['page_name']  = 'perdida_detalles';
        $page_data['page_title'] = "Detalles de pérdida";
        $this->load->view('backend/index', $page_data);
    }

    function productos_vencidos()
    {
        $this->admin_login();
        if ($this->session->userdata('login_user_type') == 2) {
            redirect(base_url(), 'refresh');
        }

        $initial = $this->input->post('initial');
        $final   = $this->input->post('final');
        // log_message("error", "Initial: $initial, Final: $final");
        if ($initial == '') $initial = date("Y-m-01");
        if ($final == '') $final = date("Y-m-t");
        // log_message("error", "Initial: $initial, Final: $final");
        
        $page_data['page_name']  = 'productos_vencidos';
        $page_data['page_title'] = "Productos vencidos";
        $page_data['initial']    = $initial;
        $page_data['final']      = $final;
        $this->load->view('backend/index', $page_data);
    }
    
    function anulaciones()
    {
        $this->admin_login();
        $this->ver_permisos('ver_anulaciones');

        $page_data['page_name']  = 'anulaciones';
        $page_data['page_title'] = "Anulaciones";
        $this->load->view('backend/index', $page_data);
    }
    
    function compras($param1='', $param2='')
    {
        $this->admin_login();
        $this->ver_permisos('compras');

        if($param1 == 'create')
        {
            $this->ver_permisos('crear_compras');

            $this->crud_model->create_shopping(); 
            $this->session->set_flashdata('flash_message' , "Nueva compra agregada correctamente.");
            redirect(base_url() . 'admin/compras/', 'refresh');
        }
        if($param1 == 'update')
        {
            $this->ver_permisos('editar_compras');

            $this->crud_model->update_shopping($param2);
            $this->session->set_flashdata('flash_message' , "Compra actualizada correctamente.");
            redirect(base_url() . 'admin/compras/', 'refresh');
        }
        if($param1 == 'delete')
        {   
            $this->ver_permisos('eliminar_compras');

            $this->crud_model->delete_shopping($param2);
            $this->session->set_flashdata('flash_message' , "Compra eliminada correctamente.");
            redirect(base_url() . 'admin/compras/', 'refresh');
        }
        if($param1 == 'complete')
        {   
            $this->ver_permisos('completar_solicitud');

            $this->crud_model->complete_shopping($param2);
            $this->session->set_flashdata('flash_message' , "Compra registrada correctamente.");
            redirect(base_url() . 'admin/compras/', 'refresh');
        }

        $this->ver_permisos('ver_compras');

        $initial = $this->input->post('initial');
        $final   = $this->input->post('final');
        log_message("error", "Initial: $initial, Final: $final");
        if ($initial == '') $initial = date("Y-m-d");
        if ($final == '') $final = date("Y-m-d");
        log_message("error", "Initial: $initial, Final: $final");
        
        $page_data['page_name']  = 'compras';
        $page_data['page_title'] = "Compras";
        $page_data['initial']    = $initial;
        $page_data['final']      = $final;
        $page_data['code']       = trim($this->input->post('code'));
        $page_data['type']       = $this->input->post('type');
        $page_data['admin_id']   = $this->input->post('admin_id');
        $page_data['data']       = $this->crud_model->get_shopping_table();
        $this->load->view('backend/index', $page_data);
    }
    
    function cotizaciones($param1 ='', $param2='')
    {
        $this->admin_login();
        $this->ver_permisos('cotizaciones');

        if($param1 == 'create')
        {   
            $this->ver_permisos('crear_cotizaciones');

            $this->crud_model->create_quote(); 
            $this->session->set_flashdata('flash_message' , "Preventa creada correctamente.");
            redirect(base_url() . 'admin/cotizaciones/', 'refresh');
        }
        if($param1 == 'update')
        {
            $this->ver_permisos('editar_cotizaciones');

            $this->crud_model->update_quote($param2);
            $this->session->set_flashdata('flash_message' , "Preventa actualizada correctamente.");
            redirect(base_url() . 'admin/cotizaciones/', 'refresh');
        }
        if($param1 == 'delete')
        {   
            $this->ver_permisos('eliminar_cotizaciones');

            $this->crud_model->delete_quote($param2);
            $this->session->set_flashdata('flash_message' , "Preventa eliminada correctamente.");
            redirect(base_url() . 'admin/cotizaciones/', 'refresh');
        }
        if($param1 == 'venta')
        {   
            $this->ver_permisos('asignar_cotizaciones');

            $this->crud_model->delete_quote($param2);
            $this->session->set_flashdata('flash_message' , "Preventa eliminada correctamente.");
            redirect(base_url() . 'admin/cotizaciones/', 'refresh');
        }

        $this->ver_permisos('ver_cotizaciones');

        $page_data['page_name']  = 'cotizaciones';
        $page_data['page_title'] = "Cotizaciones";
        $this->load->view('backend/index', $page_data);
    }
    
    function ventas($param1='', $param2='',  $param3='')
    {
        $this->admin_login();
        $this->ver_permisos('ventas');

        if($param1 == 'pay')
        {
            $return = $this->crud_model->add_pay($param2);
            $this->session->set_flashdata('flash_message' , "Pago agregado correctamente.");
            redirect(base_url() . 'admin/detalles_venta/'.$param2, 'refresh');
        }

        if($param1 == 'create')
        {
            $this->ver_permisos('crear_ventas');
            
            $sales = $this->db->get_where('sales', array('code'=>$param2));
        if($sales->num_rows() <= 0){
            $return = $this->crud_model->create_sale($param2, $param3);
            $this->session->set_flashdata('flash_message' , "Venta realizada correctamente.");
            //aqui_ventas
            $venta = $this->db->get_where('sales',array('code'=>$param2))->row();
            if($venta->FEL!=1){
                if($venta->credito == 1 ){
                 //   $this->validarXMLCambiaria($param2);
                }
                else{
                   // $this->validarXML($param2);   
                } 
            }
            if($return == 1){
                echo 2 ;
                redirect(base_url() . 'admin/creditos/', 'refresh');
                exit();
            }else{
                echo 1;
                redirect(base_url() . 'admin/ventas/', 'refresh');
                exit();
            }
        }
        $this->session->set_flashdata('flash_message' , "Venta no completada.");
        echo 1;
        redirect(base_url() . 'admin/ventas/', 'refresh');
        exit();
        }

        if($param1 == 'apply')
        {
            $this->ver_permisos('crear_ventas');
                    $sales = $this->db->get_where('sales', array('code'=>$param2));
        if($sales->num_rows() <= 0){
            $return = $this->crud_model->apply_sale($param2);
            $this->session->set_flashdata('flash_message' , "Venta realizada correctamente.");
            
            if($return == 1){
                echo 2 ;
                redirect(base_url() . 'admin/creditos/', 'refresh');
                exit();
            }else{
                echo 1;
                redirect(base_url() . 'admin/ventas/', 'refresh');
                exit();
            }
        }
        $this->session->set_flashdata('flash_message' , "Venta no completada.");
        echo 1;
        redirect(base_url() . 'admin/ventas/', 'refresh');
        exit();
        }

        if($param1 == 'generar111')
        {
            $this->ver_permisos('crear_ventas');

            $return = $this->crud_model->generar_venta($param2);
            $this->session->set_flashdata('flash_message' , "Venta realizada correctamente.");
            
            if($return == 1){
                redirect(base_url() . 'admin/creditos/', 'refresh');
            } else {
                redirect(base_url() . 'admin/ventas/', 'refresh');
            }
        }

        if($param1 == 'delete')
        {   
            $login_user_type    = $this->session->userdata('login_user_type');
            $permisos     = unserialize($this->session->userdata('permissions'));
            if($login_user_type == 1 || $permisos['eliminar_ventas'] == 1 || $permisos['eliminar_creditos'] == 1 || $permisos['eliminar_anulaciones'] == 1 || $permisos['eliminar_cambios'] == 1){
                $this->crud_model->delete_sale($param2);
                $this->session->set_flashdata('flash_message' , "Venta eliminada correctamente.");
                if($param3 == 1){
                    redirect(base_url() . 'admin/ventas/', 'refresh');
                }else{
                    redirect(base_url() . 'admin/creditos/', 'refresh');
                }
            }
            else{
                redirect(base_url(), 'refresh');
            }
        }

        $this->ver_permisos('ver_ventas');

        $initial = $this->input->post('initial');
        $final   = $this->input->post('final');
        // log_message("error", "Initial: $initial, Final: $final");
        if ($initial == '') $initial = date("Y-m-d");
        if ($final == '') $final = date("Y-m-d");
        // log_message("error", "Initial: $initial, Final: $final");
        
        $page_data['page_name']  = 'ventas';
        $page_data['page_title'] = "Ventas";
        $page_data['initial']    = $initial;
        $page_data['final']      = $final;
        $this->load->view('backend/index', $page_data);
    }

    function detalles_creditos($param1='', $param2='',  $param3='')
    {
        $this->admin_login();
        $this->ver_permisos('creditos');

        $page_data['client_id']  = $param1;
        $page_data['page_name']  = 'detalles_creditos';
        $page_data['page_title'] = "Créditos";
        $this->load->view('backend/index', $page_data);
    }
    
    function admins($param1 = '', $param2 ='', $param3 = '')
    {
        $this->admin_login();
        $this->ver_permisos('admins');

        if($param1 == 'create')
        {   
            $this->ver_permisos('crear_admins');

            $data = $this->db->get_where('admin', array('email'=>$this->input->post('email')))->num_rows();
            
            if( $data > 0 ){
                $this->session->set_flashdata('flash_error' , "Ya esta ingresado este correo: ".$this->input->post('email'));

            }else{
                $this->crud_model->create_admins(); 
                $this->session->set_flashdata('flash_message' , "Administrador agregado correctamente.");
            }

            redirect(base_url() . 'admin/admins/', 'refresh');
        }
        if($param1 == 'update')
        {
            $this->ver_permisos('editar_admins');

            if($param3 ==1){
                $this->crud_model->update_admins($param2);
                $this->session->set_flashdata('flash_message' , "Administrador actualizado correctamente.");
                redirect(base_url() . 'admin/perfil/', 'refresh');
            }elseif($param3 ==2){
                $my_email = $this->db->get_where('admin', array('admin_id'=>$param2));  
                $email = $this->db->get_where('admin', array('email'=>$this->input->post('email')));  

                if($my_email->row()->email == $this->input->post('email')){
                    $this->crud_model->update_empleado_profile($param2);
                    $this->session->set_flashdata('flash_message' , "Empleado actualizado correctamente.");
                    redirect(base_url() . 'admin/perfil_empleado/'.$param2, 'refresh');
                }else{
                    if($email->num_rows() > 0 ){
                        $this->session->set_flashdata('flash_error' , "El perfil no se puede actualizar.");
                        redirect(base_url() . 'admin/perfil_empleado/'.$param2, 'refresh');
                    }else{
                        $this->crud_model->update_empleado_profile($param2);
                        $this->session->set_flashdata('flash_message' , "Empleado actualizado correctamente.");
                        redirect(base_url() . 'admin/perfil_empleado/'.$param2, 'refresh');
                    }
                }

            }else{
                $this->crud_model->update_admin_modal($param2);
                $this->session->set_flashdata('flash_message' , "Administrador actualizado correctamente.");
                redirect(base_url() . 'admin/admins/', 'refresh');
            }
        }
        if($param1 == 'delete')
        {   
            $this->ver_permisos('estado_admins');

            $this->crud_model->delete_admins($param2);
            $this->session->set_flashdata('flash_message' , "Administrador desactivado correctamente.");
            redirect(base_url() . 'admin/admins/', 'refresh');
        }
        if($param1 == 'active'){
            $this->ver_permisos('estado_admins');

            $this->crud_model->active_admins($param2);
            $this->session->set_flashdata('flash_message' , "Administrador activado correctamente.");
            redirect(base_url() . 'admin/admins/', 'refresh');
        }
        if($param1 == 'credenciales'){
            //$this->ver_permisos('estado_admins');

            $this->crud_model->send_credentials($param2);
            $this->session->set_flashdata('flash_message' , "Credenciales enviadas correctamente.");
            if($param3 == 1 ){
                redirect(base_url() . 'admin/perfil_admin/'.$param2, 'refresh');
            }else{
                redirect(base_url() . 'admin/perfil_empleado/'.$param2, 'refresh');
            }
        }

        $page_data['page_name']  = 'admins';
        $page_data['page_title'] = "Administradores";
        $this->load->view('backend/index', $page_data);
    }
    
    function clientes($param1='', $param2='')
    {
        $this->admin_login();
        $this->ver_permisos('clientes');

        if($param1 == 'create')
        {   
            $this->ver_permisos('crear_clientes');

                $this->crud_model->create_client(); 
                $this->session->set_flashdata('flash_message' , "Cliente agregado correctamente.");
            
            redirect(base_url() . 'admin/clientes/', 'refresh');
        }
        if($param1 == 'update')
        {
            $this->ver_permisos('editar_clientes');

            $this->crud_model->update_client($param2);
            $this->session->set_flashdata('flash_message' , "Cliente actualizado correctamente.");
            redirect(base_url() . 'admin/clientes/', 'refresh');
        }
        if($param1 == 'delete')
        {   
            $this->ver_permisos('estado_clientes');

            $this->crud_model->delete_client($param2);
            $this->session->set_flashdata('flash_message' , "Cliente desactivado correctamente.");
            redirect(base_url() . 'admin/clientes/', 'refresh');
        }
        if($param1 == 'active'){
            $this->ver_permisos('estado_clientes');

            $this->crud_model->active_client($param2);
            $this->session->set_flashdata('flash_message' , "Cliente activado correctamente.");
            redirect(base_url() . 'admin/clientes/', 'refresh');
        }
        
        $page_data['page_name']  = 'clientes';
        $page_data['page_title'] = "Clientes";
        $this->load->view('backend/index', $page_data);
    }

    function usuarios($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'cliente')
        {
            $data['nombre'] = $this->input->post('nombre');
            $data['correo'] = $this->input->post('correo');
            $data['telefono'] = $this->input->post('telefono');
            $data['id_sucursal'] = $this->session->userdata('id_sucursal');
            $this->db->insert('clientes', $data);
            $this->session->set_flashdata('flash_message' , "El cliente se agregó correctamente");
            redirect(base_url() . 'admin/usuarios/', 'refresh');    
        }
        if($param1 == 'update_client')
        {
            $data['nombre'] = $this->input->post('nombre');
            $data['correo'] = $this->input->post('correo');
            $data['telefono'] = $this->input->post('telefono');
            $this->db->where('id', $param2);
            $this->db->update('clientes', $data);
            $this->session->set_flashdata('flash_message' , "El cliente se actualizó correctamente");
            redirect(base_url() . 'admin/usuarios/', 'refresh');    
        }
        if($param1 == 'delete_client')
        {
            $this->db->where('id', $param2);
            $this->db->delete('clientes');
            $this->session->set_flashdata('flash_message' , "El cliente se eliminó correctamente");
            redirect(base_url() . 'admin/usuarios/', 'refresh');    
        }
        if($param1 == 'empleado')
        {
            $checked_jornada = $this->input->post('sucursal');
            $total_checked_values = count($checked_jornada);
            $jornada = '';
            for ($i = 0; $i < $total_checked_values; $i++) {
                $jornada .= $checked_jornada[$i] . ",";
            }
            $data['sucursales'] = $jornada;
            $data['name']       = $this->input->post('nombre');
            $data['permiso']    = $this->input->post('permiso');
            $data['email']      = $this->input->post('correo');
            $data['password']   = sha1($this->input->post('contrasena'));
            $data['username']   = $this->input->post('usuario');
            $data['phone']      = $this->input->post('telefono');

            $data['productos']     = $this->input->post('productos');
            $data['cat_productos'] = $this->input->post('cat_productos');
            $data['reportes']      = $this->input->post('reportes');
            $data['usuarios']      = $this->input->post('usuarios');
            $data['asistencia']    = $this->input->post('asistencia');
            $data['transporte']    = $this->input->post('transporte');
            $data['creditos']      = $this->input->post('creditos');
            $data['ajustes']       = $this->input->post('ajustes');
            $data['id_sucursal']   = $this->session->userdata('id_sucursal');
            
            $this->db->insert('admin', $data);
            $this->session->set_flashdata('flash_message' , "El empleado se agregó correctamente");
            redirect(base_url() . 'admin/empleados/', 'refresh');    
        }
        if($param1 == 'update_emp')
        {
            $checked_jornada = $this->input->post('sucursal');
            $total_checked_values = count($checked_jornada);
            $jornada = '';
            for ($i = 0; $i < $total_checked_values; $i++) {
                $jornada .= $checked_jornada[$i] . ",";
            }
            $data['sucursales']     = $jornada;
            $data['name'] = $this->input->post('nombre');
            $data['permiso'] = $this->input->post('permiso');
            $data['email'] = $this->input->post('correo');
            $data['productos'] = $this->input->post('productos');
            $data['cat_productos'] = $this->input->post('cat_productos');
            $data['reportes'] = $this->input->post('reportes');
            $data['usuarios'] = $this->input->post('usuarios');
            $data['asistencia'] = $this->input->post('asistencia');
            $data['transporte'] = $this->input->post('transporte');
            $data['creditos'] = $this->input->post('creditos');
            $data['ajustes'] = $this->input->post('ajustes');
            if($this->input->post('contrasena') != "")
            {
                $data['password'] = sha1($this->input->post('contrasena'));
            }
            $data['username'] = $this->input->post('usuario');
            $data['phone'] = $this->input->post('telefono');
            $this->db->where('admin_id', $param2);
            $this->db->update('admin', $data);
            $this->session->set_flashdata('flash_message' , "El empleado se actualizó correctamente");
            redirect(base_url() . 'admin/empleados/', 'refresh');    
        }
        if($param1 == 'delete_emp')
        {
            $this->db->where('admin_id', $param2);
            $this->db->delete('admin');
            $this->session->set_flashdata('flash_message' , "El empleado se eliminó correctamente");
            redirect(base_url() . 'admin/empleados/', 'refresh');    
        }
        if($param1 == 'proveedor')
        {
            $data['nombre'] = $this->input->post('nombre');
            $data['correo'] = $this->input->post('correo');
            $data['telefono'] = $this->input->post('telefono');
            $data['direccion'] = $this->input->post('direccion');
            $data['id_sucursal'] = $this->session->userdata('id_sucursal');
            $this->db->insert('proveedores', $data);
            $this->session->set_flashdata('flash_message' , "El proveedor se agregó correctamente");
            redirect(base_url() . 'admin/proveedores/', 'refresh');    
        }
        if($param1 == 'update_prov')
        {
            $data['nombre'] = $this->input->post('nombre');
            $data['correo'] = $this->input->post('correo');
            $data['telefono'] = $this->input->post('telefono');
            $data['direccion'] = $this->input->post('direccion');
            $this->db->where('id', $param2);
            $this->db->update('proveedores', $data);
            $this->session->set_flashdata('flash_message' , "El proveedor se actualizó correctamente");
            redirect(base_url() . 'admin/proveedores/', 'refresh');    
        }
        if($param1 == 'delete_prov')
        {
            $this->db->where('id', $param2);
            $this->db->delete('proveedores');
            $this->session->set_flashdata('flash_message' , "El proveedor se eliminó correctamente");
            redirect(base_url() . 'admin/proveedores/', 'refresh');    
        }
        $page_data['page_name']  = 'usuarios';
        $page_data['page_title'] = "Usuarios";
        $this->load->view('backend/index', $page_data);
    }

    function asistencia()
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        $page_data['page_name']  = 'asistencia';
        $page_data['page_title'] = "Manejar asistencia";
        $this->load->view('backend/index', $page_data);
    }

    function categorias($param1 = '', $param2 = '')
    {
        $this->admin_login();
        $this->ver_permisos('categorias');

        if($param1 == 'update')
        {
            $this->ver_permisos('editar_categorias');

            $this->crud_model->update_category($param2);
            $this->session->set_flashdata('flash_message' , "La categoría se actualizó correctamente");
            redirect(base_url() . 'admin/categorias/', 'refresh');
        }
        if($param1 == 'delete')
        {
            $this->ver_permisos('eliminar_categorias');

            $this->crud_model->delete_category($param2);
            $this->session->set_flashdata('flash_message' , "La categoría se eliminó correctamente");
            redirect(base_url() . 'admin/categorias/', 'refresh');
        }
        if($param1 == 'create')
        {
            $this->ver_permisos('crear_categorias');

            $this->crud_model->create_category();
            $this->session->set_flashdata('flash_message' , "La categoría se creó correctamente");
            redirect(base_url() . 'admin/categorias/', 'refresh');
        }
        $page_data['page_name']  = 'categorias';
        $page_data['page_title'] = "Manejar categorías de productos";
        $this->load->view('backend/index', $page_data);
    }

    function tipos_p($param1 = '', $param2 = '')
    {
        $this->admin_login();
        $this->ver_permisos('categorias');

        if($param1 == 'update')
        {
            $this->ver_permisos('editar_categorias');

            $this->crud_model->update_type_product($param2);
            $this->session->set_flashdata('flash_message' , "El tipo de producto se actualizó correctamente");
            redirect(base_url() . 'admin/tipos_p/', 'refresh');
        }
        if($param1 == 'delete')
        {
            $this->ver_permisos('eliminar_categorias');

            $this->crud_model->delete_type_product($param2);
            $this->session->set_flashdata('flash_message' , "El tipo de producto se eliminó correctamente");
            redirect(base_url() . 'admin/tipos_p/', 'refresh');
        }
        if($param1 == 'create')
        {
            $this->ver_permisos('crear_categorias');

            $this->crud_model->create_type_product();
            $this->session->set_flashdata('flash_message' , "El tipo de producto se creó correctamente");
            redirect(base_url() . 'admin/tipos_p/', 'refresh');
        }
        $page_data['page_name']  = 'tipos_p';
        $page_data['page_title'] = "Manejar tipos de productos";
        $this->load->view('backend/index', $page_data);
    }
    function class_p($param1 = '', $param2 = '')
    {
        $this->admin_login();
        $this->ver_permisos('categorias');

        if($param1 == 'update')
        {
            $this->ver_permisos('editar_categorias');

            $this->crud_model->update_class_product($param2);
            $this->session->set_flashdata('flash_message' , "El tipo de producto se actualizó correctamente");
            redirect(base_url() . 'admin/class_p/', 'refresh');
        }
        if($param1 == 'delete')
        {
            $this->ver_permisos('eliminar_categorias');

            $this->crud_model->delete_class_product($param2);
            $this->session->set_flashdata('flash_message' , "El tipo de producto se eliminó correctamente");
            redirect(base_url() . 'admin/class_p/', 'refresh');
        }
        if($param1 == 'create')
        {
            $this->ver_permisos('crear_categorias');

            $this->crud_model->create_class_product();
            $this->session->set_flashdata('flash_message' , "El tipo de producto se creó correctamente");
            redirect(base_url() . 'admin/class_p/', 'refresh');
        }
        $page_data['page_name']  = 'clase_p';
        $page_data['page_title'] = "Manejar clase de productos";
        $this->load->view('backend/index', $page_data);
    }

    function attendance_selector()
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        
        $str = $this->input->post('timestamp');
        $originalDate =$this->input->post('timestamp');
        $newDate = date("d-m-Y", strtotime($originalDate));
        $data['timestamp']  = strtotime($newDate);
        $query = $this->db->get_where('asistencia' ,array('year'=>date('Y'),'timestamp'=>$data['timestamp'], 'id_sucursal' => $this->session->userdata('id_sucursal')));
        if($query->num_rows() < 1) 
        {
            $admins = $this->db->get_where('admin')->result_array();
            foreach($admins as $row) 
            {
                $attn_data['admin_id']   = $row['admin_id'];
                $attn_data['year'] = date('Y');
                $attn_data['timestamp']  = $data['timestamp'];
                $attn_data['id_sucursal'] = $this->session->userdata('id_sucursal');
                $this->db->insert('asistencia' , $attn_data);  
            }
        }
        redirect(base_url().'admin/visor_asistencia/'. $data['timestamp'],'refresh');
    }

    function reporte_selector()
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
        $data['year']       = date('Y');
        $data['month']      = $this->input->post('month');
        $this->session->set_flashdata('flash_message' , "Información generada con éxito");
        redirect(base_url().'admin/visor_reporte/'.$data['month'],'refresh');
    }

     function visor_reporte($month = '') 
    {
        if($this->session->userdata('admin_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        $page_data['month']    = $month;
        $page_data['page_name'] = 'visor_reporte';
        $page_data['page_title'] = "Reporte de asistencia";
        $this->load->view('backend/index', $page_data);
     }

    function reporte_asistencia() 
    {
        if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }
         $page_data['month']        =  date('m');
         $page_data['page_name']    = 'reporte';
         $page_data['page_title']   = "Reporte de asistencia";
         $this->load->view('backend/index',$page_data);
     }

     function attendance_update2($timestamp = '')
    {
         if ($this->session->userdata('admin_login') != 1) 
        {
            $this->session->set_userdata('last_page', current_url());
            redirect(base_url(), 'refresh');
        }

        $attendance_of = $this->db->get_where('asistencia' , array('year'=> date('Y'),'timestamp'=>$timestamp))->result_array();
        foreach($attendance_of as $row) 
        {
            $attendance_status = $this->input->post('entrada_'.$row['attendance_id']);
            $attendance_status2 = $this->input->post('salida_'.$row['attendance_id']);
            $this->db->where('attendance_id' , $row['attendance_id']);
            $this->db->update('asistencia' , array('entrada' => $attendance_status, 'salida' => $attendance_status2));
        }
            $this->session->set_flashdata('flash_message' , "La asistencia se actualizó correctamente");
        redirect(base_url().'admin/visor_asistencia/'.$timestamp , 'refresh');
    }


     function visor_asistencia($timestamp = '')
    {
        if($this->session->userdata('admin_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        $page_data['timestamp'] = $timestamp;
        $page_data['page_name'] = 'visor';
        $page_data['page_title'] = "Control de asistencia diaria";
        $this->load->view('backend/index', $page_data);
    }

    function help($timestamp = '')
    {
        if($this->session->userdata('admin_login')!=1)
        {
            redirect(base_url() , 'refresh');
        }
        $page_data['page_name'] = 'help';
        $page_data['page_title'] = "Asistencia técnica";
        $this->load->view('backend/index', $page_data);
    }

    function transporte($param1 = '', $param2 = '')
    {
        $this->admin_login();
        $this->ver_permisos('transportes');

        if($param1 == 'create')
        {   
            $this->ver_permisos('crear_transportes');

            $this->crud_model->create_transport();
            $this->session->set_flashdata('flash_message' , "Se registró correctamente");
            redirect(base_url().'admin/transporte/', 'refresh');
        }
        if($param1 == 'update')
        {   
            $this->ver_permisos('editar_transportes');

            $this->crud_model->update_transport($param2);
            $this->session->set_flashdata('flash_message' , "Se actualizó correctamente");
            redirect(base_url().'admin/transporte/', 'refresh');
        }
        if($param1 == 'delete')
        {
            $this->ver_permisos('eliminar_transportes');

            $this->crud_model->delete_transport($param2);
            $this->session->set_flashdata('flash_message' , "Se eliminó correctamente");
            redirect(base_url().'admin/transporte/', 'refresh');   
        }
        $page_data['page_name']  = 'transporte';
        $page_data['page_title'] = "Administrar transporte";
        $this->load->view('backend/index', $page_data);
    }
    
    function transporte_servicios($param1 = '', $param2 = '')
    {
        $this->admin_login();
        $this->ver_permisos('transportes');

        if($param1 == 'create_service')
        {   
            $this->ver_permisos('registrar_servicios');

            $this->crud_model->create_service($param2);
            $this->session->set_flashdata('flash_message' , "Se registró correctamente");
            redirect(base_url().'admin/transporte_servicios/'.base64_encode($param2), 'refresh');
        }
        
        $page_data['id_transporte'] = base64_decode($param1);
        $page_data['page_name']     = 'transporte_servicios';
        $page_data['page_title']    = "Administrar servicio transporte";
        $this->load->view('backend/index', $page_data);
    }

    function creditos()
    {
        $this->admin_login();
        $this->ver_permisos('creditos');
        
        $page_data['page_name']  = 'creditos';
        $page_data['page_title'] = "Administrar Créditos";
        $this->load->view('backend/index', $page_data);
    }
    
    function creditos_compras()
    {
        $this->admin_login();
        $this->ver_permisos('creditos');
        
        $page_data['page_name']  = 'creditos_compras';
        $page_data['page_title'] = "Administrar Compras Créditos";
        $this->load->view('backend/index', $page_data);
    }

    function detalles_credito($code = '', $param1 = '', $param2 = '')
    {
        $this->admin_login();
        $this->ver_permisos('creditos');

        if ($param1 == 'add_pay') {
            $this->crud_model->add_pay($code);
            $this->session->set_flashdata('flash_message' , "El pago se registró correctamete");
            redirect(base_url().'admin/detalles_credito/'.$code, 'refresh');
        }
        if ($param1 == 'delete_pay') {
            $this->crud_model->cancelar_pago($param2, $code);
            $this->session->set_flashdata('flash_message' , "El pago se anuló correctamete");
            redirect(base_url().'admin/detalles_credito/'.$code, 'refresh');
        }

        if ($code == '') {
            $code = $this->input->post('code');
        }

        $page_data['code']       = $code;
        $page_data['page_name']  = 'detalles_credito';
        $page_data['page_title'] = "Detalles del crédito";
        $this->load->view('backend/index', $page_data);
    }
    
    function detalles_creditos_prov($code = '', $param1 = '', $param2 = '')
    {
        $this->admin_login();
        $this->ver_permisos('creditos');
        log_message('error',$code);

        if ($param1 == 'add_pay') {
            
            $this->crud_model->add_pay_prov($code);
            $this->session->set_flashdata('flash_message' , "El pago se registró correctamete");
            redirect(base_url().'admin/detalles_creditos_prov/'.$code, 'refresh');
        }
        if ($param1 == 'delete_pay') {
            $this->crud_model->cancelar_pago_prov($param2, $code);
            $this->session->set_flashdata('flash_message' , "El pago se anuló correctamete");
            redirect(base_url().'admin/detalles_creditos_prov/'.$code, 'refresh');
        }

        if ($code == '') {
            $code = $this->input->post('code');
        }

        $page_data['code']       = $code;
        $page_data['page_name']  = 'detalles_creditos_prov';
        $page_data['page_title'] = "Detalles del crédito";
        $this->load->view('backend/index', $page_data);
    }

    function search_pay()
    {
        $moneda = $this->crud_model->get_info("moneda");
        $id_pago = $this->input->post('id_pago');
        $pago = $this->db->get_where('credit_details', array('credit_details_id' => $id_pago))->row_array();
        echo date('Y-m-d', strtotime($pago['date'])).' - '.$moneda.number_format($pago['amount'],2,'.',',');
    }

    function pagos($param1 = '', $param2 = '')
    {
        $this->admin_login();

        if($param1 == 'nuevo')
        {
            $data['fecha'] = $this->input->post('fecha');
            $data['metodo'] = $this->input->post('metodo');
            $data['monto'] = $this->input->post('monto');
            $data['descripcion'] = $this->input->post('descripcion');
            $data['admin_id'] = $this->session->userdata('login_user_id');
            $data['id_sucursal'] = $this->session->userdata('id_sucursal');
            $this->db->insert('pagos',$data);
            $id = $this->db->insert_id();
            move_uploaded_file($_FILES["comprobante"]["tmp_name"], "uploads/pagos/" . $id .".jpg");
            $this->session->set_flashdata('flash_message' , "El pago se registró correctamete");
            redirect(base_url().'admin/pagos/', 'refresh');
        }
        if($param1 == 'editar')
        {
            $data['metodo'] = $this->input->post('metodo');
            $data['monto'] = $this->input->post('monto');
            $data['descripcion'] = $this->input->post('descripcion');
            $this->db->where('id', $param2);
            $this->db->update('pagos',$data);
            move_uploaded_file($_FILES["comprobante"]["tmp_name"], "uploads/pagos/" . $param2 .".jpg");
            $this->session->set_flashdata('flash_message' , "El pago se actualizó correctamete");
            redirect(base_url().'admin/pagos/', 'refresh');  
        }
        if($param1 == 'eliminar')
        {
            unlink("uploads/pagos/" . $param2.".jpg");
            $this->db->where('id', $param2);
            $this->db->delete('pagos');
            $this->session->set_flashdata('flash_message' , "El pago se eliminó correctamete");
            redirect(base_url().'admin/pagos/', 'refresh');
        }
        $page_data['page_name']  = 'pagos';
        $page_data['page_title'] = "Administrar pagos";
        $this->load->view('backend/index', $page_data);
    }

    function create_box_cut()
    {
        $this->admin_login();
        $this->ver_permisos('registrar_cortes');

        $this->crud_model->create_box_cut();
        $this->session->set_flashdata('flash_message' , "Se creo el corte de caja exitosamente");
        $refer =  $this->agent->referrer();
        redirect($refer, 'refresh');
    }

    function productos($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'nuevo')
        {
            
            $this->ver_permisos('crear_productos');

            $nuevo = "";
            $mes = date('M');
            if($mes == "Jan"){$nuevo = "Enero";}
            if($mes == "Feb"){$nuevo = "Febrero";}
            if($mes == "Mar"){$nuevo = "Marzo";}
            if($mes == "Apr"){$nuevo = "Abril";}
            if($mes == "May"){$nuevo = "Mayo";}
            if($mes == "Jun"){$nuevo = "Junio";}
            if($mes == "Jul"){$nuevo = "Julio";}
            if($mes == "Aug"){$nuevo = "Agosto";}
            if($mes == "Sept"){$nuevo = "Septiembre";}
            if($mes == "Oct"){$nuevo = "Octubre";}
            if($mes == "Nov"){$nuevo = "Noviembre";}
            if($mes == "Dec"){$nuevo = "Diciembre";}
            $data['nombre'] = $this->input->post('nombre');
            $data['marca'] = $this->input->post('marca');
            $data['codigo_categoria'] = $this->input->post('codigo_categoria');
            $data['precio'] = $this->input->post('precio');
            $data['costo'] = $this->input->post('costo');
            $data['stock'] = $this->input->post('stock');
            $data['descripcion'] = $this->input->post('descripcion');
            $data['alerta'] = $this->input->post('alerta');
            $data['estado'] = 1;
            $data['fecha'] = date('d')." de ". $nuevo." del ".date('Y');
            $data['admin_id'] = $this->session->userdata('login_user_id');
            $data['hora'] = date('H:i:s');
            $data['mes'] = $nuevo;
            $data['id_sucursal'] = $this->session->userdata('id_sucursal');
            $data['codigo'] = $this->input->post('codigo');
            $this->db->insert('producto', $data);
            $id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/productos/' . $id . '.jpg');
            $this->session->set_flashdata('flash_message' , "El producto se agregó correctamente");
            redirect(base_url() . 'admin/productos/', 'refresh');    
        }   
        if($param1 == 'bodega')
        {
            $this->ver_permisos('crear_productos');

            $nuevo = "";
            $mes = date('M');
            if($mes == "Jan"){$nuevo = "Enero";}
            if($mes == "Feb"){$nuevo = "Febrero";}
            if($mes == "Mar"){$nuevo = "Marzo";}
            if($mes == "Apr"){$nuevo = "Abril";}
            if($mes == "May"){$nuevo = "Mayo";}
            if($mes == "Jun"){$nuevo = "Junio";}
            if($mes == "Jul"){$nuevo = "Julio";}
            if($mes == "Aug"){$nuevo = "Agosto";}
            if($mes == "Sept"){$nuevo = "Septiembre";}
            if($mes == "Oct"){$nuevo = "Octubre";}
            if($mes == "Nov"){$nuevo = "Noviembre";}
            if($mes == "Dec"){$nuevo = "Diciembre";}
            $data['nombre'] = $this->input->post('nombre');
            $data['marca'] = $this->input->post('marca');
            $data['codigo_categoria'] = $this->input->post('codigo_categoria');
            $data['precio'] = $this->input->post('precio');
            $data['costo'] = $this->input->post('costo');
            $data['stock'] = $this->input->post('stock');
            $data['descripcion'] = $this->input->post('descripcion');
            $data['alerta'] = $this->input->post('alerta');
            $data['estado'] = 1;
            $data['fecha'] = date('d')." de ". $nuevo." del ".date('Y');
            $data['admin_id'] = $this->session->userdata('login_user_id');
            $data['hora'] = date('H:i:s');
            $data['mes'] = $nuevo;
            $data['codigo'] = $this->input->post('codigo');
            $data['id_sucursal'] = $this->session->userdata('id_sucursal');
            $this->db->insert('bodega', $data);
            $id = $this->db->insert_id();
            $this->session->set_flashdata('flash_message' , "El producto se agregó correctamente a bodega");
            redirect(base_url() . 'admin/bodega/', 'refresh');    
        }   
        if($param1 == 'actualizar')
        {
            $this->ver_permisos('editar_productos');

            $data['nombre'] = $this->input->post('nombre');
            $data['marca'] = $this->input->post('marca');
            $data['codigo_categoria'] = $this->input->post('codigo_categoria');
            $data['precio'] = $this->input->post('precio');
            $data['costo'] = $this->input->post('costo');
            $data['stock'] = $this->input->post('stock');
            $data['descripcion'] = $this->input->post('descripcion');
            $data['alerta'] = $this->input->post('alerta');
            $data['admin_id'] = $this->session->userdata('login_user_id');
            $data['codigo'] = $this->input->post('codigo');
            $this->db->where('id', $param2);
            $this->db->update('producto', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/productos/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , "El producto se actualizó correctamente");
            redirect(base_url() . 'admin/productos/', 'refresh');    
        }   
        if($param1 == 'update')
        {
            $this->ver_permisos('editar_productos');

            $data['nombre'] = $this->input->post('nombre');
            $data['marca'] = $this->input->post('marca');
            $data['codigo_categoria'] = $this->input->post('codigo_categoria');
            $data['precio'] = $this->input->post('precio');
            $data['costo'] = $this->input->post('costo');
            $data['stock'] = $this->input->post('stock');
            $data['descripcion'] = $this->input->post('descripcion');
            $data['alerta'] = $this->input->post('alerta');
            $data['admin_id'] = $this->session->userdata('login_user_id');
            $data['codigo'] = $this->input->post('codigo');
            $this->db->where('id', $param2);
            $this->db->update('bodega', $data);
            $this->session->set_flashdata('flash_message' , "El producto se actualizó correctamente");
            redirect(base_url() . 'admin/bodega/', 'refresh');    
        }   
        if($param1 == 'categoria')
        {
            $this->ver_permisos('eliminar_productos');

            $data['nombre'] = $this->input->post('nombre');
            $data['descripcion'] = $this->input->post('descripcion');
            $data['codigo'] = substr(md5(rand(0, 1000000)), 0, 7);
            $data['id_sucursal'] = $this->session->userdata('id_sucursal');
            $this->db->insert('categoria', $data);
            $this->session->set_flashdata('flash_message' , "La categoría se agregó correctamente");
            redirect(base_url() . 'admin/categorias/', 'refresh');
        }
        if($param1 == "delete")
        {
            $this->ver_permisos('estado_productos');

            unlink("uploads/productos/" . $param2.".jpg");
            $this->db->where('id', $param2);
            $this->db->delete('producto');
            $this->session->set_flashdata('flash_message' , "El producto se eliminó correctamente");
            redirect(base_url() . 'admin/productos/', 'refresh');   
        }

        if($param1 == "deleteb")
        {
            $this->ver_permisos('estado_productos');

            $this->db->where('id', $param2);
            $this->db->delete('bodega');
            $this->session->set_flashdata('flash_message' , "El producto se eliminó correctamente de bodega");
            redirect(base_url() . 'admin/bodega/', 'refresh');   
        }
        $page_data['page_name']  = 'productos';
        $page_data['page_title'] = "Administrar productos en Stock";
        $this->load->view('backend/index', $page_data);
    }
    
    function bodega($param1 = '', $param2 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == 'nuevo')
        {
            $nuevo = "";
            $mes = date('M');
            if($mes == "Jan"){$nuevo = "Enero";}
            if($mes == "Feb"){$nuevo = "Febrero";}
            if($mes == "Mar"){$nuevo = "Marzo";}
            if($mes == "Apr"){$nuevo = "Abril";}
            if($mes == "May"){$nuevo = "Mayo";}
            if($mes == "Jun"){$nuevo = "Junio";}
            if($mes == "Jul"){$nuevo = "Julio";}
            if($mes == "Aug"){$nuevo = "Agosto";}
            if($mes == "Sept"){$nuevo = "Septiembre";}
            if($mes == "Oct"){$nuevo = "Octubre";}
            if($mes == "Nov"){$nuevo = "Noviembre";}
            if($mes == "Dec"){$nuevo = "Diciembre";}
            $data['nombre'] = $this->input->post('nombre');
            $data['marca'] = $this->input->post('marca');
            $data['codigo_categoria'] = $this->input->post('codigo_categoria');
            $data['precio'] = $this->input->post('precio');
            $data['costo'] = $this->input->post('costo');
            $data['stock'] = $this->input->post('stock');
            $data['descripcion'] = $this->input->post('descripcion');
            $data['alerta'] = $this->input->post('alerta');
            $data['estado'] = 1;
            $data['fecha'] = date('d')." de ". $nuevo." del ".date('Y');
            $data['admin_id'] = $this->session->userdata('login_user_id');
            $data['hora'] = date('H:i:s');
            $data['mes'] = $nuevo;
            $data['codigo'] = $this->input->post('codigo');
            $data['id_sucursal'] = $this->session->userdata('id_sucursal');
            $this->db->insert('producto', $data);
            $id = $this->db->insert_id();
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/productos/' . $id . '.jpg');
            $this->session->set_flashdata('flash_message' , "El producto se agregó correctamente");
            redirect(base_url() . 'admin/productos/', 'refresh');    
        }   
        if($param1 == 'actualizar')
        {
            $data['nombre'] = $this->input->post('nombre');
            $data['marca'] = $this->input->post('marca');
            $data['codigo_categoria'] = $this->input->post('codigo_categoria');
            $data['precio'] = $this->input->post('precio');
            $data['costo'] = $this->input->post('costo');
            $data['stock'] = $this->input->post('stock');
            $data['descripcion'] = $this->input->post('descripcion');
            $data['alerta'] = $this->input->post('alerta');
            $data['admin_id'] = $this->session->userdata('login_user_id');
            $data['codigo'] = $this->input->post('codigo');
            $this->db->where('id', $param2);
            $this->db->update('producto', $data);
            move_uploaded_file($_FILES['userfile']['tmp_name'], 'uploads/productos/' . $param2 . '.jpg');
            $this->session->set_flashdata('flash_message' , "El producto se actualizó correctamente");
            redirect(base_url() . 'admin/productos/', 'refresh');    
        }   
        if($param1 == 'categoria')
        {
            $data['nombre'] = $this->input->post('nombre');
            $data['descripcion'] = $this->input->post('descripcion');
            $data['codigo'] = substr(md5(rand(0, 1000000)), 0, 7);
            $data['id_sucursal'] = $this->session->userdata('id_sucursal');
            $this->db->insert('categoria', $data);
            $this->session->set_flashdata('flash_message' , "La categoría se agregó correctamente");
            redirect(base_url() . 'admin/categorias/', 'refresh');
        }
        if($param1 == "delete")
        {
            unlink("uploads/productos/" . $param2.".jpg");
            $this->db->where('id', $param2);
            $this->db->delete('producto');
            $this->session->set_flashdata('flash_message' , "El producto se eliminó correctamente");
            redirect(base_url() . 'admin/productos/', 'refresh');   
        }
        $page_data['page_name']  = 'bodega';
        $page_data['page_title'] = "Administrar productos en Bodega";
        $this->load->view('backend/index', $page_data);
    }

    function ajustes($param1 = '', $param2 = '', $param3 = '')
    {
        if ($this->session->userdata('admin_login') != 1)
        {
            redirect(base_url(), 'refresh');
        }
        if($param1 == "session")
        { 
            $this->db->truncate('ci_sessions');
            redirect(base_url(), 'refresh');
        }
        if ($param1 == 'do_update') 
        {
            $data['nombre']    = $this->input->post('nombre');
            $data['dirip']     = $this->input->post('dirip');
            $data['titulo']    = $this->input->post('titulo');    
            $data['telefono']  = $this->input->post('telefono');    
            $data['direccion'] = $this->input->post('direccion');    
            $data['correo']    = $this->input->post('correo');    
            $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
            $this->db->update('sucursales', $data);
            $this->session->set_flashdata('flash_message' , "Los ajustes se actualizaron correctamente");
            redirect(base_url() . 'admin/ajustes/', 'refresh');
        }
        $page_data['page_name']  = 'ajustes';
        $page_data['page_title'] = "Ajustes del sistema";
        $this->load->view('backend/index', $page_data);
    }
    
    function get_ventas()
    {
        $fetch_data = $this->make_datatables_ventas();  
        $data = array();  
        foreach($fetch_data as $row)  
        {  
            $admin =$this->db->get_where('admin', array('admin_id' => $row->admin_id))->row()->name;
            $cliente = $this->db->get_where('clientes', array('id' => $row->client_id))->row()->nombre;
            $direccion = $this->db->get_where('clientes', array('id' => $row->client_id))->row()->direccion;
            
            $sub_array = array();  
            $sub_array[] = '<center>'.$row->fecha.'</center>';  
            $sub_array[] = '<center><b>'.$this->db->get_where('clientes', array('id' => $row->client_id))->row()->nombre.'</b></center>';  
            $sub_array[] = '<center><b>'.$admin.'</b></center>';  
            $sub_array[] = '<center><span class="text-success" style="font-weight:bold;font-size:18px;text-align:center;">'.$moneda.number_format($row->total).'</span></center>';  
            $client_id = $this->db->get_where('ventas', array('id' => $row->id_venta))->row()->client_id;
            $sub_array[] = '<a class="badge badge-warning" href="'.base_url().'admin/recibo/'.$row->id.'">Detalles</a>
            <a class="badge badge-primary" target="_blank" href="http://'.$this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->dirip.'/update/login/impresion/?atendio='.$admin.'&productos='.base64_encode($row->productos).'&total='.number_format($roma['total']).'&fecha='.$row->fecha.'&codigo='.$row->codigo.'&cliente='.$cliente.'&direccion='. $direccion.'&tel='.$this->db->get_where('clientes', array('id' => $row->client_id))->row()->telefono.'">Imprimir</a>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>      $this->get_all_ventas(),  
            "recordsFiltered"       =>     $this->get_filtered_data_ventas(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output); 
    }
    
    function make_datatables_ventas()
	{  
        $this->make_query_ventas();  
        if($_POST["length"] != -1)  
        {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result();  
    } 
    
    function get_all_ventas()  
    {  
        $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
        $this->db->select("*");  
        $this->db->from("ventas");  
        return $this->db->count_all_results();  
    }
    
    function get_filtered_data_ventas()
    {  
        $this->make_query_ventas();  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    function make_query_ventas()  
    {  
        $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
        $this->db->select("*");  
        $this->db->from("ventas");  
        if(isset($_POST["search"]["value"]))  
        {  
            $this->db->like("cliente", $_POST["search"]["value"]);  
        }  
        if(isset($_POST["order"]))  
        {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        }  
        else  
        {  
            $this->db->order_by('id', 'DESC');  
        }  
    }
    
    function get_clientes()
    {
        $fetch_data = $this->make_datatables_clientes();  
        $data = array();  
        $n = 1;
        foreach($fetch_data as $row)  
        {  
            $sub_array = array();

            $sub_array[] = $n++;  
            $sub_array[] = $row->nombre;  
            if($row->correo == ''){
                $sub_array[] = 'No aplica';    
            }else{
                $sub_array[] = $row->correo; 
            }
            if($row->telefono == ''){
                $sub_array[] = '--';    
            }else{
                $sub_array[] = $row->telefono; 
            }
            $sub_array[] = '<a onclick="showAjaxModal('."'".base_url().'modal/popup/editar_cliente/'.$row->id."'".');" href="javascript:void(0);"><i class="os-icon os-icon-ui-49"></i></a>
            <a class="danger" onClick="return confirm('."'¿Seguro desea eliminar al cliente? Esta acción no se puede deshacer.'".');" href="'.base_url().'admin/usuarios/delete_client/'.$row->id.'"><i class="os-icon os-icon-ui-15"></i></a>';
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"                  =>     intval($_POST["draw"]),  
            "recordsTotal"          =>     $this->get_all_clientes(),  
            "recordsFiltered"       =>     $this->get_filtered_data_clientes(),  
            "data"                  =>     $data  
        );  
        echo json_encode($output); 
    }
    
    function make_datatables_clientes()
	{  
        $this->make_query_clientes();  
        if($_POST["length"] != -1)  
        {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result();  
    } 
    
    function get_all_clientes()  
    {  
        $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
        $this->db->select("*");  
        $this->db->from("clientes");  
        return $this->db->count_all_results();  
    }
    
    function get_filtered_data_clientes()
    {  
        $this->make_query_clientes();  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    function make_query_clientes()  
    {  
        $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
        $this->db->select("*");  
        $this->db->from("clientes");  
        if(isset($_POST["search"]["value"]))  
        {  
            $this->db->like("nombre", $_POST["search"]["value"]);  
        }  
        if(isset($_POST["order"]))  
        {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        }  
        else  
        {  
            $this->db->order_by('id', 'DESC');  
        }  
    }

    function get_productosP($pro, $id)  
    {  
        $user_type    = $this->session->userdata('login_user_type');
        $moneda = $this->db->get_where('settings', array('type'=>'moneda'))->row()->description;
        $data = $this->db->get_where('products', array('products_id'=>$pro));
        if($data->row()->presentation == 'Caja'){
            $total = $this->crud_model->get_stock($data->row()->id_prod_matriz, $this->session->userdata('branch_id'));
            $stock_inventory = ($total/$data->row()->cnt_prod_matriz); 
        }else{
            $stock_inventory  = $this->crud_model->get_stock($pro, $this->session->userdata('branch_id'));
        }
        $max_ = $stock_inventory;
        
        
        foreach($data->result_array() as $row){
            $precio = $this->crud_model->last_price($pro, $this->session->userdata('branch_id'));
            $cost = $this->crud_model->last_cost($pro, $this->session->userdata('branch_id'));
            
            $precio_farma = $this->db->get_Where('products', array('products_id'=>$pro))->row()->farma;
            if ($user_type != 1){ $min_cost_farma=$precio_farma; }else{$min_cost_farma=$cost;}
            
            $precio_my = $this->db->get_Where('products', array('products_id'=>$pro))->row()->precio_mayorista; 
            if ($user_type != 1){ $min_cost_my=$precio_my; }else{$min_cost_my=$cost;}
            
            $table = '<tr id="producto-'.$pro.'">
            <td><span class="product_name">'.$row['name'].' (';
            if (!$row['iva']) $table .= 'Exento';
            else $table .= 'Afecto';
            $table .= ')</span> 
                <input type="hidden"  id ="productoss-'.$pro.'" name="product[]" value="'.$row['products_id'].'">  
                <input type="hidden"  id ="cost-'.$pro.'" name="cost[]" value="'.$cost.'"> 
            </td>
            <td>
                <input  class="form-control aumentar-'.$pro.' amount" type="number" min="1" max="'.$max_.'" style="width:70px" step="any" id="amount-'.$pro.'" name="amount[]" value="1" onchange="sum('.$pro.','.$pro.')"> 
                <input type="hidden" id="max_vendidos-'.$pro.'" value="'.$max_.'"> </td>
            <td class="client-mn">
                <input min="'.$precio.'" class="form-control" type="number" style="width:110px" step="any" id="price-'.$pro.'" name="price[]" value="'.$precio.'" onchange="sum('.$pro.','.$pro.')" >
                <input type="hidden" id="precioProducto-'.$pro.'" value="'.$cost.'">
                <input type="hidden" id="prPrice-'.$pro.'" value="'.$precio.'">
            </td>
            <td class="client-farma">
                <input min="'.$min_cost_farma.'" class="form-control" type="number" style="width:110px" step="any" id="price_farma-'.$pro.'" name="price_farma[]" value="'.$precio_farma.'" onchange="sum('.$pro.','.$pro.',4)" autofocus>
                <input type="hidden" id="prPrice_farma-'.$pro.'" value="'.$precio_farma.'">
            </td>
            <td class="client-my">
                <input min="'.$min_cost_my.'" class="form-control" type="number" style="width:110px" step="any" id="price_my-'.$pro.'" name="price_my[]" value="'.$precio_my.'" onchange="sum('.$pro.','.$pro.',2)" autofocus>
                <input type="hidden" id="prPrice_my-'.$pro.'" value="'.$precio_my.'">
            </td>
            <td>
                <input min="0" max="100" class="form-control discount" type="number" style="width:70px" step="any" id="discount-'.$pro.'" name="discount[]" value="0"  onChange="sum('.$pro.','.$pro.',3)" onInput="sum('.$pro.','.$pro.',3)">
            </td>
            <td class="client-mn">
                <span class="text-success" id="sub-'.$pro.'">'.$moneda.'1.00</span> 
                <input type="hidden" class="total" name="sub[]" id="subt-'.$pro.'"  step="any">
                <span class="totalx" id="txtx-'.$pro.'" style="display:none;">'.$pro.'</span>
            </td>
            <td class="client-farma">
                <span class="text-success" id="sub_farma-'.$pro.'">'.$moneda.'1.00</span> 
                <input type="hidden" class="total_farma" name="sub_farma[]" id="subt_farma-'.$pro.'"  step="any">
            </td>
            <td class="client-my">
                <span class="text-success" id="sub_my-'.$pro.'">'.$moneda.'1.00</span> 
                <input type="hidden" class="total_my" name="sub_my[]" id="subt_my-'.$pro.'"  step="any">
            </td>
            <td>
                <a class="badge badge-danger" style="padding:3px;" onclick="removeOption(\''.$pro.'\')"
                    href="javascript:;">
                    <span class="svg-icon svg-icon-white svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <path
                                    d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                                    fill="#000000" fill-rule="nonzero"></path>
                                <path
                                    d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                    fill="#000000" opacity="0.3"></path>
                            </g>
                        </svg>
                    </span>
                </a>
            </td>
        </tr>
        <tr id="mensaje-'.$pro.'"></tr>';
        echo $table;
        }
    }

    function get_productos($pro, $id)  
    {  
        $readonly = '';
        $user_type    = $this->session->userdata('login_user_type');
        if ($user_type != 1) $readonly = "readonly";
        $moneda = $this->db->get_where('settings', array('type'=>'moneda'))->row()->description;
        $data = $this->db->get_where('products', array('products_id'=>$pro));
        $producto = $this->db->get_where('products',array('products_id'=>$pro))->row();
            if($producto->presentation == 'Caja'){
                $total = $this->crud_model->get_stock($producto->id_prod_matriz, $this->session->userdata('branch_id'));
                $stock_inventory = ($total/$producto->cnt_prod_matriz); 
                $tot_bodega= $this->crud_model->get_stock($producto->id_prod_matriz, 0);
                $stock_bodega = ($tot_bodega/$producto->cnt_prod_matriz);
            }else{
                $stock_inventory  = $this->crud_model->get_stock($pro, $this->session->userdata('branch_id'));
                $stock_bodega = $this->crud_model->get_stock($pro, 0);
            }
        //$max_ = $this->crud_model->get_stock($pro,$this->session->userdata('branch_id'));
        $max_ = ($stock_inventory+$stock_bodega);
        foreach($data->result_array() as $row){
            $precio = $this->crud_model->last_price($pro, $this->session->userdata('branch_id'));
            $cost = $this->crud_model->last_cost($pro, $this->session->userdata('branch_id'));
            $precio_farma = $this->db->get_Where('products', array('products_id'=>$pro))->row()->farma;
           
            if ($user_type != 1){ $min_cost_farma=$precio_farma; }else{$min_cost_farma=$cost;}
            $precio_my = $this->db->get_Where('products', array('products_id'=>$pro))->row()->precio_mayorista; 
            if ($user_type != 1){ $min_cost_my=$precio_my; }else{$min_cost_my=$cost;}
            
            $table = '<tr id="producto-'.$pro.'">
            <td><span class="product_name">'.$row['name'].' (';
            if (!$row['iva']) $table .= 'Exento';
            else $table .= 'Afecto';
            $table .= ')</span> 
                <input type="hidden"  id ="productoss-'.$pro.'" name="product[]" value="'.$row['products_id'].'">  
                <input type="hidden"  id ="cost-'.$pro.'" name="cost[]" value="'.$cost.'"> 
            </td>
            <td>
                <input  class="form-control aumentar-'.$pro.'" type="number" min="1" max="'.$max_.'" style="width:70px" step="any" id="amount-'.$pro.'" name="amount[]" value="1" onchange="sum('.$pro.','.$pro.')" onInput="sum('.$pro.','.$pro.')"> 
                <input type="hidden" id="max_vendidos-'.$pro.'" value="'.$max_.'"> </td>
            <td class="client-mn">
                <input min="'.$min_cost_my.'" class="form-control" type="number" style="width:110px" step="any" id="price-'.$pro.'" name="price[]" value="'.$precio.'" onChange="sum('.$pro.','.$pro.',2)" onInput="sum('.$pro.','.$pro.',2)" autofocus>
                <input type="hidden" id="precioProducto-'.$pro.'" value="'.$cost.'">
                <input type="hidden" id="prPrice-'.$pro.'" value="'.$precio.'">
            </td>
            <td class="client-farma">
                <input min="'.$min_cost_farma.'" class="form-control" type="number" style="width:110px" step="any" id="price_farma-'.$pro.'" name="price_farma[]" value="'.$precio_farma.'"  onChange="sum('.$pro.','.$pro.',4)" onInput="sum('.$pro.','.$pro.',2)" autofocus>
                <input type="hidden" id="prPrice_farma-'.$pro.'" value="'.$precio_farma.'">
            </td>
            <td class="client-my">
                <input min="'.$min_cost_my.'" class="form-control" type="number" style="width:110px" step="any" id="price_my-'.$pro.'" name="price_my[]" value="'.$precio_my.'"  onChange="sum('.$pro.','.$pro.',2)" onInput="sum('.$pro.','.$pro.',2)"  autofocus>
                <input type="hidden" id="prPrice_my-'.$pro.'" value="'.$precio_my.'">
            </td>
            <td>
                <input min="0" max="100" class="form-control discount" type="number" style="width:70px" step="any" id="discount-'.$pro.'" name="discount[]" value="0"  onChange="sum('.$pro.','.$pro.',3)" onInput="sum('.$pro.','.$pro.',3)">
            </td>
            <td class="client-mn">
                <span class="text-success" id="sub-'.$pro.'">'.$moneda.'1.00</span> 
                <input type="hidden" class="total" name="sub[]" id="subt-'.$pro.'"  step="any">
            </td>
            <td class="client-farma">
                <span class="text-success" id="sub_farma-'.$pro.'">'.$moneda.'1.00</span> 
                <input type="hidden" class="total_farma" name="sub_farma[]" id="subt_farma-'.$pro.'"  step="any">
            </td>
            <td class="client-my">
                <span class="text-success" id="sub_my-'.$pro.'">'.$moneda.'1.00</span> 
                <input type="hidden" class="total_my" name="sub_my[]" id="subt_my-'.$pro.'"  step="any">
            </td>
            
            <td>
                <a class="badge badge-danger" style="padding:3px;" onclick="removeOption(\''.$pro.'\')"
                    href="javascript:;">
                    <span class="svg-icon svg-icon-white svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <path
                                    d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                                    fill="#000000" fill-rule="nonzero"></path>
                                <path
                                    d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                    fill="#000000" opacity="0.3"></path>
                            </g>
                        </svg>
                    </span>
                </a>
            </td>
        </tr>
        <tr id="mensaje-'.$pro.'"></tr>';
        echo $table;
        }
    }

    function get_productos_perdida($pro, $id)  
    {  
        $readonly = '';
        $user_type    = $this->session->userdata('login_user_type');
        if ($user_type != 1) $readonly = "readonly";
        $moneda = $this->db->get_where('settings', array('type'=>'moneda'))->row()->description;
        $data = $this->db->get_where('products', array('products_id'=>$pro));
        $producto = $this->db->get_where('products',array('products_id'=>$pro))->row();
            if($producto->presentation == 'Caja'){
                $total = $this->crud_model->get_stock($producto->id_prod_matriz, $this->session->userdata('branch_id'));
                $stock_inventory = ($total/$producto->cnt_prod_matriz); 
                //$tot_bodega= $this->crud_model->get_stock($producto->id_prod_matriz, 0);
                //$stock_bodega = ($tot_bodega/$producto->cnt_prod_matriz);
            }else{
                $stock_inventory  = $this->crud_model->get_stock($pro, $this->session->userdata('branch_id'));
                //$stock_bodega = $this->crud_model->get_stock($row->products_id, 0);
            }
        //$max_ = $this->crud_model->get_stock($pro,$this->session->userdata('branch_id'));
        $max_ = $stock_inventory;
        foreach($data->result_array() as $row){
            $cost = $this->crud_model->last_cost($pro, $this->session->userdata('branch_id'));
            log_message("error", "Costo: $cost");
            $table = '<tr id="producto-'.$pro.'">
            <td><span class="product_name">'.$row['name'].' (';
            if (!$row['iva']) $table .= 'Exento';
            else $table .= 'Afecto';
            $table .= ')</span> 
                <input type="hidden"  id ="productoss-'.$pro.'" name="product[]" value="'.$row['products_id'].'"> 
            </td>
            <td class="text-right">
                <span>'.$max_.'</span> 
                <input type="hidden" id="max_vendidos-'.$pro.'" value="'.$max_.'">
            </td>
            <td class="text-right"> 
                <span>'.$moneda.number_format($cost,2,'.',',').'</span>
                <input type="hidden"  id ="cost-'.$pro.'" name="cost[]" value="'.$cost.'"> 
            </td>
            <td class="text-right">
                <div class="form-group">
                    <input class="form-control aumentar-'.$pro.'" required type="number" min="1" max="'.$max_.'" style="width:70px; float: right;" step="any" id="amount-'.$pro.'" name="amount[]" value="1" onchange="sum('.$pro.','.$pro.')" onInput="sum('.$pro.','.$pro.')">
                </div>
            </td>
            <td class="text-right">
                <span class="text-success" id="sub-'.$pro.'">'.$moneda.'1.00</span> 
                <input type="hidden" class="total" name="sub[]" id="subt-'.$pro.'" step="any">
            </td>
            
            <td class="text-right">
                <a class="badge badge-danger" style="padding:3px;" onclick="removeOption(\''.$pro.'\')"
                    href="javascript:;">
                    <span class="svg-icon svg-icon-white svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <path
                                    d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                                    fill="#000000" fill-rule="nonzero"></path>
                                <path
                                    d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                    fill="#000000" opacity="0.3"></path>
                            </g>
                        </svg>
                    </span>
                </a>
            </td>
        </tr>
        <tr id="mensaje-'.$pro.'"></tr>';
        echo $table;
        }
    }

    function get_client_sale($client_id)  
    {  
        
            $data = $this->db->get_where('client', array('client_id'=>$client_id));
            foreach($data->result_array() as $row){
                    echo json_encode($row);
                }
    }

function get_productos_change($pro, $id)
{
$moneda = $this->crud_model->get_info("moneda");
$data = $this->db->get_where('products', array('products_id'=>$pro));
foreach($data->result_array() as $row){
$table = '
<td>
    <span>'.$row['name'].'</span>
    <input type="hidden" name="product[]" value="'.$row['products_id'].'">
    <input type="hidden" name="change[]" value="1" id="changeProducto-'.$pro.'">
</td>
<td><input min="1" max="'.$this->crud_model->get_stock($pro,$this->session->userdata('branch_id')).'" class="form-control" type="number" style="width:70px" step="any" id="amount-'.$id.'" name="amount[]" value="1" onchange="subtotal('.$id.')"></td>
<td>'.$moneda.number_format($row['price'],2,'.',',').' <input min="1" class="form-control" type="hidden" style="width:110px" step="any" id="price-'.$id.'" name="price[]" value=" '.$row['price'].' ">
</td>
<td>
    <input min="0" class="form-control" type="number" style="width:70px" step="any" id="discount-'.$id.'" name="discount[]" value="" onchange="subtotal('.$id.')">
</td>
<td><span class="text-success" id="sub-'.$id.'">'.$moneda.$row['price'].'</span>
    <input type="hidden" class="total" name="sub[]" id="subt-'.$id.'" value="'.$row['price'].'">
</td>
<td>
    <a class="badge badge-danger" style="padding:3px;" onclick="removeOption(\''.$id.'\')" href="javascript:;" >
        <span class="svg-icon svg-icon-white svg-icon-2x">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <rect x="0" y="0" width="24" height="24"></rect>
                    <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"></path>
                    <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3">
                    </path>
                </g>
            </svg>
        </span>
    </a>
</td>';
echo $table;
}
}

    function get_productos_stock($pro, $branch)
    {
        echo $this->crud_model->get_stock($pro, $branch);
    }

    function get_productos_move($pro, $id)
    {
        
        $branch = $this->session->userdata('branch_id');
        $data = $this->db->get_where('products', array('products_id'=>$pro));
        $stock_branch =$this->crud_model->get_stock($pro,$this->session->userdata('branch_id'));
        $stock_bodega =$this->crud_model->get_stock($pro,0);
        $max_ =$stock_branch+$stock_bodega;
        foreach($data->result_array() as $row){
            $table = '<tr id="producto-'.$pro.'">
                <td>'.$row['name'].' <input type="hidden" class="producto" id ="productoss-'.$pro.'" name="products[]" value="'.$row['products_id'].'"></td>
                <td>
                    <select class="form-control" name="branch_envia[]" required id="from_'.$pro.'" onchange="ver_sucursales(this.value, '.$stock_branch.', '.$stock_bodega.', '.$pro.' )">
                        <option value="">Seleccionar</option>';
                        if ($stock_bodega > 0):
                        $table .= '<option value="0">Bodega</option>';
                        endif;
                        if ($stock_branch > 0):
                        $table .= '<option value="'.$this->session->userdata('branch_id').'">'.$this->crud_model->getBranch($this->session->userdata('branch_id')).'</option>';
                        endif;
                        $products = $this->db->get_where('branch', array('status'=>1,'branch_id !='=>$this->session->userdata('branch_id')));
                        foreach ($products->result_array() as $product):
                        $table .= '<option value="'.$product['branch_id'].'" >'.$product['name'].'</option>';
                        endforeach;
                        $table .= '
                    </select>
                </td>
                <td>
                    <select class="form-control" name="branch_reciber[]" required id="to_'.$pro.'" onchange="ver_sucursales()">
                        <option value="">Seleccionar</option>
                        <option value="0">Bodega</option>';
                        $products = $this->db->get_where('branch', array('status'=>1));
                        foreach ( $products->result_array() as $product):
                        $table .= '<option value='.$product['branch_id'].'>'.$product['name'].'</option>';
                        endforeach;
                        $table .= '
                    </select>
                </td>
                <td>
                    <input required min="1" max="'.$max_.'" class="form-control aumentar-'.$pro.'" type="number" style="max-width: fit-content;" step="any" id="send_'.$pro.'" name="amount2[]" value="1">
                    <input type="hidden" id="max_vendidos-'.$pro.'" value="'.$max_.'"> 
                </td>
                <td>
                    <a class="badge badge-danger" style="padding:3px;" onclick="removeOption(\''.$pro.'\')" href="javascript:;">
                        <span class="svg-icon svg-icon-white svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"></path>
                                    <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3">
                                    </path>
                                </g>
                            </svg>
                        </span>
                    </a>
                </td>
            </tr>';
        }
        echo $table;
    }
    
    function get_productos_move2($pro, $id, $desde)
    {
        
        $branch = $desde;
        $data = $this->db->get_where('products', array('products_id'=>$pro));
        $stock_branch = 0;
        if($data->row()->presentation == 'Caja'){
            $total = $this->crud_model->get_stock($data->row()->id_prod_matriz, $desde);
            $stock_branch = ($total/$data->row()->cnt_prod_matriz); 
        } else {
            $stock_branch  = $this->crud_model->get_stock($pro, $desde);
        }
        $max_ = $stock_branch;
        foreach($data->result_array() as $row){
            $table = '<tr id="producto-'.$pro.'">
                <td>'.$row['name'].' <input type="hidden" class="producto" id ="productoss-'.$pro.'" name="products[]" value="'.$row['products_id'].'"></td>
                <td>
                    <input required min="1" max="'.$max_.'" class="form-control aumentar-'.$pro.'" type="number" style="max-width: fit-content;" step="any" id="send_'.$pro.'" name="amount2[]" value="1">
                    <input type="hidden" id="max_vendidos-'.$pro.'" value="'.$max_.'"> 
                </td>
                <td>
                    <a class="badge badge-danger" style="padding:3px;" onclick="removeOption(\''.$pro.'\')" href="javascript:;">
                        <span class="svg-icon svg-icon-white svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"></path>
                                    <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3">
                                    </path>
                                </g>
                            </svg>
                        </span>
                    </a>
                </td>
            </tr>';
        }
        echo $table;
    }

    function get_stock_lote($lote_id) {
        return $this->crud_model->echo_stock_lote($lote_id);
    }

    function get_productos_compras_s($pro, $id)
    {
    $data = $this->db->get_where('products', array('products_id'=>$pro));
    foreach($data->result_array() as $row){
    $table = '<tr id="producto-'.$id.'">
        <td>
            '.$row['name'].'
            <input type="hidden" id="productoss-'.$pro.'" name="product[]" value=" '.$row['products_id'].'">
        </td>
        <td>
            <input min="1" class="form-control aumentar-'.$pro.'" type="number" style="width:100px" id="amount-'.$id.'" name="amount[]" value="1" onchange="alert('.$id.')"  onkeyup=".$id." onblur="sum('.$id.')">
        </td>
        <td class="text-right">
            <a class="badge badge-danger" style="padding:3px;" onclick="removeOption(\''.$id.'\')" href="javascript:;">
                <span class="svg-icon svg-icon-white svg-icon-2x">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"></rect>
                            <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"></path>
                            <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3">
                            </path>
                        </g>
                    </svg>
                </span>
            </a>
        </td>
    </tr>';
    echo $table;
    }
    }

    function get_productos_compras($pro, $id, $promo = 0)
    {
        $data = $this->db->get_where('products', array('products_id'=>$pro));
        foreach($data->result_array() as $row){
            $price_buy = 0; $last_cost = 0;
            $last_cost = $this->crud_model->last_cost($row['products_id'],  $this->session->userdata('branch_id'));
            if ($promo == 1) $price_buy = $last_cost;
            $table = '<tr id="producto-'.$pro.'">
                <td>
                    '.$row['code'].'
                    <input type="hidden" name="cont[]" id="cont-'.$pro.'" value="'.$pro.'"/>
                </td>
                <td>
                    '.$row['name'].'
                    <input type="hidden" id="productoss-'.$pro.'" name="product[]" value=" '.$row['products_id'].'">
                </td>
                <td class="text-center">
                    <input class="form-control" type="date" id="expiration-'.$pro.'" name="expiration[]" value="'.date('Y-m-d').'" onblur="sum('.$pro.')" >
                </td>
                <td class="text-center">
                    <input  style="max-width: fit-content;" class=" text-center form-control aumentar-'.$pro.'" type="number" id="amount-'.$pro.'" name="amount[]" value="1" step="0.01" onblur="sum('.$pro.')" required>
                </td>
                <td >
                    <input  class="form-control" type="number" step="0.01" style="max-width: fit-content; text-align: center;" id="price_buy-'.$pro.'" name="price_buy[]" value="'.$price_buy.'" step="0.01" onblur="sum('.$pro.')" required>
                    <input type="hidden" name="hidden_cost[]" id="hidden_cost-'.$pro.'" value="'.number_format($last_cost,2,'.','').'" />
                </td>
                <td class="text-center">
                    <span class="label label-lg font-weight-bold label-light-success label-inline" id="sub-'.$pro.'">'.$moneda.'0.00</span>
                    <input  class="total" type="hidden" id="subt-'.$pro.'" name="sub[]" value="0" onchange="sum('.$pro.')"  onkeyup="sum('.$pro.')" onblur="sum('.$pro.')">
                </td>
                <td class="text-right">
                    <a class="badge badge-danger" style="padding:3px;" onclick="removeOption(\''.$pro.'\')" href="javascript:;">
                        <span class="svg-icon svg-icon-white svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"></path>
                                    <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3">
                                    </path>
                                </g>
                            </svg>
                        </span>
                    </a>
                </td>
            </tr>';
        }
        echo $table;
    }


    function get_variantes($pro, $id, $i)
    {
    $data = $this->db->get_where('products', array('products_id'=>$pro));
    foreach($data->result_array() as $row){
    if ($row['variants'] != "" || $row['variants'] != null) {
    $var = json_decode($row['variants'],true);
    } else {
    $var = array();
    }
    $table = '<tr id="producto-'.$id.'">
        <td>
            '.$var[$i]['type'].'
            <input type="hidden" name="product[]" value=" '.$var[$i]['type'].'">
        </td>
        <td><input min="1" class="form-control" type="number" style="width:70px" step="any" id="amount-'.$id.'" name="amount[]" value="1" onblur="sum('.$id.')">
        </td>
        <td>
            '.$moneda.number_format($var[$i]['price_sale'],2,'.',',').'
            <input min="1" class="form-control" type="hidden" style="width:110px" step="any" id="price-'.$id.'" name="price[]" value=" '.$var[$i]['price_sale'].' ">
        </td>
        <td><input min="0" max="100" class="form-control" type="number" style="width:70px" step="any" id="discount-'.$id.'" name="discount[]" value="" onblur="sum('.$id.')">
        </td>
        <td><span class="text-success" id="sub-'.$id.'">'.$moneda.'.1</span> <input type="hidden" class="total" name="sub[]" id="subt-'.$id.'"></td>
        <td>
            <a class="badge badge-danger" style="padding:3px;" onclick="removeOption(\''.$id.'\')" href="javascript:;">
                <span class="svg-icon svg-icon-white svg-icon-2x">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"></rect>
                            <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"></path>
                            <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3">
                            </path>
                        </g>
                    </svg>
                </span>
            </a>
        </td>
    </tr>';
    echo $table;
    }
    }


    function export_excel($param1 = '', $param2='', $param3 ='', $param4 = '')
    {
        $this->admin_login();

        if ($param1 == 'categories')
        {
            $this->ver_permisos('reportes_categorias');
            $this->crud_model->downloadExcelCategories();
        }
        if ($param1 == 'types_product')
        {
            $this->ver_permisos('reportes_categorias');
            $this->crud_model->downloadExcelTypesProduct();
        }
        if ($param1 == 'marcas')
        {
            $this->ver_permisos('reportes_marcas');
            $this->crud_model->downloadExcelMarks();
        }
        if ($param1 == 'proveedores')
        {
            $this->ver_permisos('reportes_proveedores');
            $this->crud_model->downloadExcelProveedores();
        }
        if ($param1 == 'clientes')
        {
            $this->ver_permisos('reportes_clientes');
            $this->crud_model->downloadExcelClientes();
        }
        if ($param1 == 'actividades')
        {
            $this->ver_permisos('actividad_usuarios');
            $this->crud_model->downloadExcelActividades($param2, $param3);
        }
        if ($param1 == 'empleados')
        {
            $this->ver_permisos('reportes_empleados');
            $this->crud_model->downloadExcelEmpleados();
        }
        if ($param1 == 'roles')
        {
            $this->ver_permisos('reportes_roles');
            $this->crud_model->downloadExcelRoles();
        }
        if ($param1 == 'planillas')
        {
            $this->ver_permisos('reportes_planillas');
            $this->crud_model->downloadExcelPlanillas();
        }
        if ($param1 == 'transports')
        {
            $this->ver_permisos('reportes_transportes');
            $this->crud_model->downloadExcelTransports();
        }
        if ($param1 == 'transportservices')
        {
            $this->ver_permisos('reportes_servicios');
            $this->crud_model->downloadExcelTransportServices();
        }
        if ($param1 == 'transfers')
        {
            $this->ver_permisos('reportes_traslados');
            $this->crud_model->downloadExcelTransfers($param2,$param3);
        }
        if ($param1 == 'account_banks')
        {
            $this->ver_permisos('reportes_cuentas');
            $this->crud_model->downloadExcelAccountBanks();
        }
        if ($param1 == 'bodega')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadExcelBodega();
        }
        if ($param1 == 'inventario')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadExcelInventario();
        }
        if ($param1 == 'inventario_mark')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadExcelInventario_Mark($param2);
        }
        if ($param1 == 'productos')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadExcelProductos();
        }
        if ($param1 == 'inventario_mark')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadExcelInventarioMark();
        }
        if ($param1 == 'cotizaciones')
        {
            $this->ver_permisos('reportes_cotizaciones');
            $this->crud_model->downloadExcelCotizaciones();
        }
        if ($param1 == 'ventas')
        {
            $this->ver_permisos('reportes_ventas');
            $this->crud_model->downloadExcelVentas($param2, $param3);
        }
        if ($param1 == 'anulaciones')
        {
            $this->ver_permisos('reportes_anulaciones');
            $this->crud_model->downloadExcelVentasAnuladas();
        }
        if ($param1 == 'cambios')
        {
            $this->ver_permisos('reportes_cambios');
            $this->crud_model->downloadExcelVentasCambiadas();
        }
        if ($param1 == 'compras')
        {
            $this->ver_permisos('reportes_compras');
            $this->crud_model->downloadExcelCompras($param2, $param3);
        }
        if ($param1 == 'envios')
        {
            $this->ver_permisos('envios');
            $this->crud_model->downloadExcelEnvios();
        }
        if ($param1 == 'entregas')
        {
            $this->ver_permisos('envios');
            $this->crud_model->downloadExcelEntregas();
        }
        if ($param1 == 'creditos')
        {
            $this->ver_permisos('creditos');
            $this->crud_model->downloadExcelCreditos();
        }
        if ($param1 == 'alertas')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadExcelAlertas();
        }
        if ($param1 == 'ingresos')
        {
            $this->ver_permisos('ingresos');
            $this->crud_model->downloadExcelIngresos();
        }
        if ($param1 == 'egreso')
        {
            $this->ver_permisos('reportes_egresos');
            $this->crud_model->downloadExcelEgresos($param2);
        }
        if ($param1 == 'cash_history')
        {
            $this->ver_permisos('historial_caja');
            $this->crud_model->downloadExcelCashHistory();
        }
        if ($param1 == 'ventas_traslado')
        {
            $this->ver_permisos('graficas_ventas');
            $this->crud_model->downloadExcelVentaTraslado($param2, $param3, $param4);
        }
        if ($param1 == 'ventas_producto')
        {
            $this->ver_permisos('graficas_ventas');
            $this->crud_model->downloadExcelVentasProducto($param2, $param3, $param4);
        }
        if ($param1 == 'ventas_marca')
        {
            $this->ver_permisos('graficas_ventas');
            $this->crud_model->downloadExcelVentasMarca($param2, $param3, $param4);
        }
        if ($param1 == 'ventas_afectas')
        {
            $this->ver_permisos('graficas_ventas');
            $this->crud_model->downloadExcelVentasAfectas($param2, $param3);
        }
        if ($param1 == 'ventas_exentas')
        {
            $this->ver_permisos('graficas_ventas');
            $this->crud_model->downloadExcelVentasExentas($param2, $param3);
        }
        if ($param1 == 'cuentas_cobrar')
        {
            $this->ver_permisos('graficas_ventas');
            $this->crud_model->downloadExcelCuentasCobrar($param2, $param3, $param4);
        }
        if ($param1 == 'cuentas_pagar')
        {
            $this->ver_permisos('graficas_ventas');
            $this->crud_model->downloadExcelCuentasPagar($param2, $param3, $param4);
        }
        if ($param1 == 'ventas_vendedor')
        {
            $this->ver_permisos('graficas_ventas');
            $this->crud_model->downloadExcelVentasVendedor($param2, $param3, $param4);
        }
        if ($param1 == 'perdidas_detalles_fechas')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadExcelPerdidaDetallesFechas($param2, $param3);
        }
        if ($param1 == 'productos_vencidos')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadExcelProductosVencidos($param2, $param3);
        }
        if ($param1 == 'increase_details')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadExcelincreases_details($param2, $param3);
        }

        redirect(base_url(), 'refresh');

    }


    function export_pdf($param1 = '',$param2='', $param3 ='')
    {
        $this->admin_login();
        if ($param1 == 'categories')
        {
            $this->ver_permisos('reportes_categorias');
            $this->crud_model->downloadPDFCategories();
        }
        if ($param1 == 'types_product')
        {
            $this->ver_permisos('reportes_categorias');
            $this->crud_model->downloadPDFTypesProduct();
        }
        if ($param1 == 'marcas')
        {
            $this->ver_permisos('reportes_marcas');
            $this->crud_model->downloadPDFMarks();
        }
        if ($param1 == 'proveedores')
        {
            $this->ver_permisos('reportes_proveedores');
            $this->crud_model->downloadPDFProveedores();
        }
        if ($param1 == 'clientes')
        {
            $this->ver_permisos('reportes_clientes');
            $this->crud_model->downloadPDFClientes();
        }
        if ($param1 == 'actividades')
        {
            $this->ver_permisos('actividad_usuarios');
            $this->crud_model->downloadPDFActividades($param2, $param3);
        }
        if ($param1 == 'empleados')
        {
            $this->ver_permisos('reportes_empleados');
            $this->crud_model->downloadPDFEmpleados();
        }
        if ($param1 == 'empleado')
        {
            $this->ver_permisos('reportes_empleados');
            $this->crud_model->downloadPDFEmpleado($param2);
        }
        if ($param1 == 'roles')
        {
            $this->ver_permisos('reportes_roles');
            $this->crud_model->downloadPDFRoles();
        }
        if ($param1 == 'rol')
        {
            $this->ver_permisos('reportes_roles');
            $this->crud_model->downloadPDFRol($param2);
        }
        if ($param1 == 'planillas')
        {
            $this->ver_permisos('reportes_planillas');
            $this->crud_model->downloadPDFplanilla();
        }
        if ($param1 == 'transports')
        {
            $this->ver_permisos('reportes_transportes');
            $this->crud_model->downloadPDFTransports();
        }
        if ($param1 == 'transportservices')
        {
            $this->ver_permisos('reportes_servicios');
            $this->crud_model->downloadPDFTransportServices();
        }
        if ($param1 == 'transfers')
        {
            $this->ver_permisos('reportes_traslados');
            $this->crud_model->downloadPDFTransfers();
        }
        if ($param1 == 'account_banks')
        {
            $this->ver_permisos('reportes_cuentas');
            $this->crud_model->downloadPDFAccountBanks();
        }
        if ($param1 == 'bodega')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadPDFBodega();
        }
        if ($param1 == 'inventario')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadPDFInventario();
        }
        if ($param1 == 'cotizaciones')
        {
            $this->ver_permisos('reportes_cotizaciones');
            $this->crud_model->downloadPDFCotizaciones();
        }
        if ($param1 == 'quotes')
        {
            $this->ver_permisos('reportes_cotizaciones');
            $this->crud_model->downloadPDFQuotes($param2);
        }
        if ($param1 == 'ventas')
        {
            $this->ver_permisos('reportes_ventas');
            $this->crud_model->downloadPDFVentas($param2, $param3);
        }
        if ($param1 == 'anulaciones')
        {
            $this->ver_permisos('reportes_anulaciones');
            $this->crud_model->downloadPDFVentasAnuladas();
        }
        if ($param1 == 'cambios')
        {
            $this->ver_permisos('reportes_cambios');
            $this->crud_model->downloadExcelVentasCambiadas();
        }
        if ($param1 == 'venta')
        {
            $login_user_type = $this->session->userdata('login_user_type');
            $permisos = unserialize($this->session->userdata('permissions'));
            if ($login_user_type == 1 || $permisos['reportes_ventas'] == 1 || $permisos['reportes_creditos'] == 1 || $permisos['reportes_anulaciones'] == 1 || $permisos['reportes_cambios'] == 1 || $permisos['reportes_envios'] == 1 || $login_user_type == 4) {
                $this->crud_model->downloadPDFVenta($param2);
            }
            else{
                redirect(base_url(), 'refresh');
            }
        }
        if ($param1 == 'compras')
        {
            $this->ver_permisos('reportes_compras');
            $this->crud_model->downloadPDFCompras($param2, $param3);
        }
        if ($param1 == 'compra')
        {
            $this->ver_permisos('reportes_compras');
            $this->crud_model->downloadPDFCompra($param2);
        }
        if ($param1 == 'envios')
        {
            $this->ver_permisos('reportes_envios');
            $this->crud_model->downloadPDFEnvios();
        }
        if ($param1 == 'entregas')
        {
            $this->ver_permisos('reportes_envios');
            $this->crud_model->downloadPDFEntregas();
        }
        if ($param1 == 'entrega')
        {
            $this->ver_permisos('reportes_envios');
            $this->crud_model->downloadPDFEntrega($param2);
        }
        if ($param1 == 'creditos')
        {
            $this->ver_permisos('reportes_creditos');
            $this->crud_model->downloadPDFCreditos();
        }
        if ($param1 == 'credito')
        {
            
            $this->ver_permisos('reportes_creditos');
            $this->crud_model->downloadPDFCredito($param2);
        }
        if ($param1 == 'alertas')
        {
            $this->ver_permisos('reportes_alertas');
            $this->crud_model->downloadPDFAlertas();
        }
        if ($param1 == 'ingresos')
        {
            $this->ver_permisos('ingresos');
            $this->crud_model->downloadPDFIngresos();
        }
        if ($param1 == 'ingreso')
        {
            $this->ver_permisos('ingresos');
            $this->crud_model->downloadPDFIngreso($param2);
        }
        if ($param1 == 'egresos')
        {
            $this->ver_permisos('reportes_egresos');
            $this->crud_model->downloadPDFEgresos($param2);
        }
        if ($param1 == 'cash_history')
        {
            $this->ver_permisos('historial_caja');
            $this->crud_model->downloadPDFCashHistory();
        }
        if ($param1 == 'ventaFEL')
        {
            //$this->ver_permisos('reportes_fel');
            $this->crud_model->downloadPDFVentaFel($param2); 
        }
        if ($param1 == 'increase')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadPDFIncrease($param2, $param3);
        }
        if ($param1 == 'increase_details')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadPDFIncreaseDetails($param2, $param3);
        }
        if ($param1 == 'ingreso_detalles')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadPDFIngresoDetalles($param2);
        }
        if ($param1 == 'perdidas_detalles')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadPDFLosseDetails($param2);
        }
        if ($param1 == 'perdidas_detalles_fechas')
        {
            $this->ver_permisos('reportes_productos');
            $this->crud_model->downloadPDFLosseDateDetails($param2, $param3);
        }
        if ($param1 == 'recibo_venta')
        {
            $this->crud_model->downloadPDFReciboVenta($param2);
        }
        
        if ($param1 == 'recibo_venta1')
        {
            $this->crud_model->downloadPDFReciboVenta1($param2);
        }
        
        if ($param1 == 'traslado')
        {
            $this->crud_model->downloadPDFTraslado($param2);
        }

        if ($param1 == 'voucher_sale')
        {
            $this->crud_model->printVoucherSale($param2);
        }

        if ($param1 == 'voucher_shop')
        {
            $this->crud_model->printVoucherShop($param2);
        }
        if ($param1 == 'pago_credito')
        {
            $this->crud_model->printRecibopago($param2);
        }

        redirect(base_url(), 'refresh');
    }
    
    function reciboDirecto($param2){
        $this->crud_model->downloadPDFReciboVenta($param2);
    }

    function change_branch($param1 ='')
    {
        $this->admin_login();
        
        $refer =  $this->agent->referrer();
        $this->session->set_userdata('branch_id', $param1);
        redirect($refer, 'refresh');
    }

    function detalles_compra($param1 = '')
    {
        $this->admin_login();
        $this->ver_permisos('ver_compras');

        if($this->input->post('code') == "")
            $page_data['code'] = $param1;
        else
            $page_data['code'] = $this->input->post('code');


        $page_data['page_name']  = 'detalles_compra';
        $page_data['page_title'] = "Detalles de compra";
        $this->load->view('backend/index', $page_data);
    }

    function search($param1 ='', $param2='')
    {
    if ($this->session->userdata('admin_login') != 1)
    {
        redirect(base_url(), 'refresh');
    }
    
    $moneda = $this->crud_model->get_info("moneda");
    
    if ($param1 == 'admin'){
    $name = $this->input->post('name');
    $status = $this->input->post('status');
    $login_user_id = $this->session->userdata('login_user_id');

    if($name != '' && $status >= 0)
    $data = $this->db->query("SELECT * FROM `admin` WHERE admin_id != $login_user_id AND status = '$status' AND type = '1' AND name like '%$name%' OR last_name LIKE '%$name%'
    OR email LIKE '$name' OR username LIKE '$name' OR phone LIKE '$name' OR cui like '$name' ");

    if($name !='' && $status =='')
    $data = $this->db->query("SELECT * FROM `admin` WHERE admin_id != $login_user_id AND type = '1' AND name like '%$name%' OR last_name LIKE '%$name%'
    OR email LIKE '$name' OR username LIKE '$name' OR phone LIKE '$name' OR cui like '$name' ");

    if($name =='' && $status >=0)
    $data = $this->db->query("SELECT * FROM `admin` WHERE status = '$status' AND type = '1' AND admin_id != $login_user_id ");

    if($name == '' && $status == '')
    $data = $this->db->get_where('admin', array('admin_id !='=>$login_user_id , 'type'=>1));


    $n = 1;
    foreach($data->result_array() as $row){
    $table = ' <tr>
        <td>'. $n++.'</td>
        <td> '.$row["name"].' '.$row["last_name"].'</td>
        <td><span class="text-warning font-weight-bolder"><a style="color: #FFA800 !important;" href="tel:+502'.$row[" phone"].'">';
                    if($row["phone"]==""){$table.='Sin datos';}else{ $table.='(+502)'.$row["phone"];} $table.='</a>
            </span></td>
        <td>';if($row["cui"]==""){$table.='Sin datos';} else{ $table.=$row['cui'] ;} $table.='</td>
        <td><span class="font-weight-bold">'.$row["username"].'</span></td>
        <td><a style="color:#3F4254;" href="mailto:'.$row[" email"].'">
                '.$row["email"].'</a></td>
        <td>
            <span class="label label-lg font-weight-bold label-light-';if($row[" status"]==1) {$table.='success ' ;}else{$table.='danger ' ; } $table.='label-inline">' ; if($row["status"]==1){$table .='Activo' ;} else{$table.='Inactivo' ; } $table.='</span>
                    </td>
                    <td>
                        <div class="d-flex flex-shrink-0">
                            <a href="base_url()admin/actividad_empleado/" data-toggle="tooltip"  data-original-title="Actividad"
                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none"
                                            fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <circle fill="#000000" opacity="0.3" cx="12" cy="12"
                                                r="10" />
                                            <rect fill="#000000" x="11" y="10" width="2" height="7"
                                                rx="1" />
                                            <rect fill="#000000" x="11" y="7" width="2" height="2"
                                                rx="1" />
                                        </g>
                                    </svg>
                                </span>
                            </a>
                            &nbsp;
                            <a href="javascript:;" data-toggle="tooltip"  data-original-title="Editar Administrador"
                                onclick="showAjaxModal(\''.base_url().' modal/popup/admin_edit/'.$row['admin_id'].'\');" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                <span class="svg-icon svg-icon-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path opacity="0.3"
                            d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                            fill="#8950FC" />
                        <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                            fill="#8950FC" />
                    </svg>
                </span>
                </a>
                &nbsp;';
                if($row['status'] == 1){
                $table.= '<a href="javascript:;" data-toggle="tooltip" data-original-title="Eliminar Administrador" onclick="executeExample('.$row['admin_id'].')" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero" />
                                <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000"
                                    opacity="0.3" />
                            </g>
                        </svg>
                    </span>
                </a>';
                }else{
                $table.=' <a href="'.base_url().'admin/admins/active/'.$row['admin_id'].'" title="" data-toggle="tooltip" data-original-title="Re-activar Administrador" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M6.82866499,18.2771971 L13.5693679,12.3976203 C13.7774696,12.2161036 13.7990211,11.9002555 13.6175044,11.6921539 C13.6029128,11.6754252 13.5872233,11.6596867 13.5705402,11.6450431 L6.82983723,5.72838979 C6.62230202,5.54622572 6.30638833,5.56679309 6.12422426,5.7743283 C6.04415337,5.86555116 6,5.98278612 6,6.10416552 L6,17.9003957 C6,18.1765381 6.22385763,18.4003957 6.5,18.4003957 C6.62084305,18.4003957 6.73759731,18.3566309 6.82866499,18.2771971 Z"
                                    fill="#000000" opacity="0.3" />
                                <path d="M12.828665,18.2771971 L19.5693679,12.3976203 C19.7774696,12.2161036 19.7990211,11.9002555 19.6175044,11.6921539 C19.6029128,11.6754252 19.5872233,11.6596867 19.5705402,11.6450431 L12.8298372,5.72838979 C12.622302,5.54622572 12.3063883,5.56679309 12.1242243,5.7743283 C12.0441534,5.86555116 12,5.98278612 12,6.10416552 L12,17.9003957 C12,18.1765381 12.2238576,18.4003957 12.5,18.4003957 C12.6208431,18.4003957 12.7375973,18.3566309 12.828665,18.2771971 Z"
                                    fill="#000000" />
                            </g>
                        </svg>
                    </span>
                </a>';
                }

                $table .='
                </div>
        </td>
    </tr>';
    echo $table;
    }
    }
    if($param1 == 'employee'){
    $name = $this->input->post('name');
    $status = $this->input->post('status');
    $job = $this->input->post('job');
    $branch_id = $this->session->userdata('branch_id');

    $this->db->where('branch_id',$branch_id);

    if($name != '' && $status != '' && $job !='')
    $data = $this->db->query("SELECT * FROM `admin` WHERE type = '2' AND status = '$status' AND job = '$job' AND name like '%$name%' OR last_name LIKE '%$name%' OR email LIKE '$name' OR username LIKE '$name' OR phone LIKE '$name' OR cui like '$name' ");

    if($name != '' && $status == '' && $job =='')
    $data = $this->db->query("SELECT * FROM `admin` WHERE type = '2' AND name like '%$name%' OR last_name LIKE '%$name%'
    OR email LIKE '$name' OR username LIKE '$name' OR phone LIKE '$name' OR cui like '$name' ");

    if($name != '' && $status != '' && $job =='')
    $data = $this->db->query("SELECT * FROM `admin` WHERE type = '2' AND status = '$status' AND name like '%$name%' OR last_name LIKE '%$name%'
    OR email LIKE '$name' OR username LIKE '$name' OR phone LIKE '$name' OR cui like '$name' ");

    if($name != '' && $status == '' && $job !='')
    $data = $this->db->query("SELECT * FROM `admin` WHERE type = '2' AND job = '$job' AND name like '%$name%' OR last_name LIKE '%$name%'
    OR email LIKE '$name' OR username LIKE '$name' OR phone LIKE '$name' OR cui like '$name' ");

    if($name == '' && $status != '' && $job !='')
    $data = $this->db->query("SELECT * FROM `admin` WHERE type = '2' AND status = '$status' AND job = '$job'");

    if($name == '' && $status != '' && $job =='')
    $data = $this->db->query("SELECT * FROM `admin` WHERE type = '2' AND status = '$status'");

    if($name == '' && $status == '' && $job !='')
    $data = $this->db->query("SELECT * FROM `admin` WHERE type = '2' AND job = '$job'");

    if($name == '' && $status == '' && $job == '')
    $data = $this->db->get_where('admin', array('type'=>2));


    $n = 1;
    foreach($data->result_array() as $row){
    $table = '<tr>
        <td>'. $n++ .'</td>
        <td>'. $row["name"].' '.$row['last_name'].'</td>
        <td class="text-center"><span class="label label-lg font-weight-bold label-light-primary label-inline">'. $row['job'].'</span>
        </td>
        <td><span class="text-warning font-weight-bolder"><a style="color: #FFA800 !important;" href="tel:+502'. $row[" phone"].'">';
                    if($row["phone"]=='') { $table.='Sin datos';}else{$table.='(+502)'.$row["phone"];}
                    $table.='</a>
            </span></td>
        <td>';
            if($row["cui"]=='') {$table.='Sin datos';}else{$table.=$row["cui"];}
            $table.='</td>
        <td><span class="font-weight-bold">'.$row["username"].'</span></td>
        <td><a style="color:#3F4254;" href="mailto:'.$row[" email"].'">'.$row["email"].'</a></td>
        <td class="text-center">
            <span class="label label-lg font-weight-bold label-light-';
                            if($row['status']==1){$table.='success ';}else{$table.='danger ';}
                            $table.='label-inline">';
                if($row['status']==1) { $table.='Activo ';} else{$table.='Inactivo';}
                $table.='</span>
        </td>
        <td>
            <div class="d-flex flex-shrink-0">
                <a href=" base_url()admin/actividad_empleado/" data-toggle="tooltip" data-original-title="Actividad del empleado" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10" />
                                <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1" />
                                <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1" />
                            </g>
                        </svg>
                    </span>
                </a>
                &nbsp;
                <a href="'.base_url().'admin/empleados_edit/'.$row['admin_id'].'" data-toggle="tooltip" data-original-title="Editar empleado" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                fill="#8950FC" />
                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                fill="#8950FC" />
                        </svg>
                    </span>
                </a>
                &nbsp;';
                if($row['status']==1){
                $table.='<a href="javascript:;" onclick="executeExample('.$row[" admin_id"].')" data-toggle="tooltip" data-original-title="Eliminar empleado" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero" />
                                <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000"
                                    opacity="0.3" />
                            </g>
                        </svg>
                    </span>
                </a>';
                }else{
                $table.='<a href=" '.base_url().'admin/admins/active/'.$row['admin_id'].'" title="" data-toggle="tooltip" data-original-title="Re-activar empleado" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M6.82866499,18.2771971 L13.5693679,12.3976203 C13.7774696,12.2161036 13.7990211,11.9002555 13.6175044,11.6921539 C13.6029128,11.6754252 13.5872233,11.6596867 13.5705402,11.6450431 L6.82983723,5.72838979 C6.62230202,5.54622572 6.30638833,5.56679309 6.12422426,5.7743283 C6.04415337,5.86555116 6,5.98278612 6,6.10416552 L6,17.9003957 C6,18.1765381 6.22385763,18.4003957 6.5,18.4003957 C6.62084305,18.4003957 6.73759731,18.3566309 6.82866499,18.2771971 Z"
                                    fill="#000000" opacity="0.3" />
                                <path d="M12.828665,18.2771971 L19.5693679,12.3976203 C19.7774696,12.2161036 19.7990211,11.9002555 19.6175044,11.6921539 C19.6029128,11.6754252 19.5872233,11.6596867 19.5705402,11.6450431 L12.8298372,5.72838979 C12.622302,5.54622572 12.3063883,5.56679309 12.1242243,5.7743283 C12.0441534,5.86555116 12,5.98278612 12,6.10416552 L12,17.9003957 C12,18.1765381 12.2238576,18.4003957 12.5,18.4003957 C12.6208431,18.4003957 12.7375973,18.3566309 12.828665,18.2771971 Z"
                                    fill="#000000" />
                            </g>
                        </svg>
                    </span>
                </a>';
                }
                $table.='
            </div>
        </td>
    </tr> ';
    echo $table;
    }
    }
    if ($param1 == 'provider'){
    $name = $this->input->post('name');
    $status = $this->input->post('status');
    $branch_id = $this->session->userdata('branch_id');

    $this->db->where('branch_id',$branch_id);

    if($name != '' && $status >= 0)
    $data = $this->db->query("SELECT * FROM `provider` WHERE status = '$status' AND name like '%$name%' OR manager LIKE '%$name%'
    OR email LIKE '$name' OR phone LIKE '$name'");

    if($name !='' && $status =='')
    $data = $this->db->query("SELECT * FROM `provider` WHERE name like '%$name%' OR manager LIKE '%$name%'
    OR email LIKE '$name' OR phone LIKE '$name'");

    if($name =='' && $status >=0)
    $data = $this->db->query("SELECT * FROM `provider` WHERE status = '$status'");

    if($name == '' && $status == '')
    $data = $this->db->get('provider');


    $n = 1;
    foreach($data->result_array() as $row){
    $table = '<tr>
        <td>'. $n++ .' </td>
        <td><b class="text-info"> '.$row["name"].'</b></td>
        <td> '.$row["manager"].'</td>
        <td><span class="text-warning font-weight-bolder"><a style="color: #FFA800 !important;" href="tel:+502'. $row[" phone"].'">';
                    if($row["phone"]=='') {$table.='Sin datos';}else{$table.='(+502)'.$row["phone"];}
                    $table.='</a>
            </span></td>
        <td><span class="text-warning font-weight-bolder"><a target="_blank" style="color: #99bf2d !important;" href="https://api.whatsapp.com/send?phone=+502'.$row[" phone"].'">';
                    if($row["phone"]=='') { $table.='Sin datos';}else{$table.='(+502)'.$row['phone'];}
                    $table.='</a>
            </span></td>
        <td><a style="color:#3F4254;" href="mailto:'. $row[" email"].'">
                '.$row['email'].'</a></td>
        <td> '.$row['address'].'</td>
        <td class="text-center">
            <span class="label label-lg font-weight-bold label-light-'; 
                            if($row['status']==1) {$table.='success ';}else{$table.='danger ';}  
                            $table.='label-inline">';
                if($row['status']==1){$table.='Activo';}else{$table.='Inactivo';}
                $table.='</span>
        </td>
        <td>
            <div class="d-flex flex-shrink-0">
                <a href=" base_url()admin/perfil_proveedor/" data-toggle="tooltip" data-original-title="Actividad del proveedor" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10" />
                                <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1" />
                                <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1" />
                            </g>
                        </svg>
                    </span>
                </a>
                &nbsp;';

                if($row['status']==1){
                $table.='<a href="javascript:;" title="" data-toggle="tooltip" data-original-title="Editar proveedor" onclick="showAjaxModal(\''.base_url().'modal/popup/editar_proveedor/'.$row['provider_id'].'\');" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                fill="#8950FC" />
                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                fill="#8950FC" />
                        </svg>
                    </span>
                </a>
                &nbsp;
                <a href="javascript:;" title="Eliminar" onclick="executeExample('.$row['provider_id'].')" data-toggle="tooltip" data-original-title="Eliminar proveedor" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero" />
                                <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000"
                                    opacity="0.3" />
                            </g>
                        </svg>
                    </span>
                </a>';
                }else{
                $table.='<a href="'.base_url().'admin/proveedores/active/'.$row['provider_id'].'" data-toggle="tooltip" data-original-title="Re-activar proveedor" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M6.82866499,18.2771971 L13.5693679,12.3976203 C13.7774696,12.2161036 13.7990211,11.9002555 13.6175044,11.6921539 C13.6029128,11.6754252 13.5872233,11.6596867 13.5705402,11.6450431 L6.82983723,5.72838979 C6.62230202,5.54622572 6.30638833,5.56679309 6.12422426,5.7743283 C6.04415337,5.86555116 6,5.98278612 6,6.10416552 L6,17.9003957 C6,18.1765381 6.22385763,18.4003957 6.5,18.4003957 C6.62084305,18.4003957 6.73759731,18.3566309 6.82866499,18.2771971 Z"
                                    fill="#000000" opacity="0.3" />
                                <path d="M12.828665,18.2771971 L19.5693679,12.3976203 C19.7774696,12.2161036 19.7990211,11.9002555 19.6175044,11.6921539 C19.6029128,11.6754252 19.5872233,11.6596867 19.5705402,11.6450431 L12.8298372,5.72838979 C12.622302,5.54622572 12.3063883,5.56679309 12.1242243,5.7743283 C12.0441534,5.86555116 12,5.98278612 12,6.10416552 L12,17.9003957 C12,18.1765381 12.2238576,18.4003957 12.5,18.4003957 C12.6208431,18.4003957 12.7375973,18.3566309 12.828665,18.2771971 Z"
                                    fill="#000000" />
                            </g>
                        </svg>
                    </span>
                </a>';
                }
                $table.='
            </div>
        </td>
    </tr>';
    echo $table;
    }
    }
    if($param1 == 'client'){
    $name = $this->input->post('name');
    $status = $this->input->post('status');
    $type = $this->input->post('type');
    $branch_id = $this->session->userdata('branch_id');

    $this->db->where('branch_id',$branch_id);

    if($name != '' && $status != '' && $type !='')
    $data = $this->db->query("SELECT * FROM `client` WHERE type = '$type' AND status = '$status' AND branch_id = '$branch_id' AND name like '%$name%' OR last_name LIKE '%$name%' OR email LIKE '$name' OR phone LIKE '$name' OR nit like '$name' ");

    if($name != '' && $status == '' && $type =='')
    $data = $this->db->query("SELECT * FROM `client` WHERE branch_id = '$branch_id' AND name like '%$name%' OR last_name LIKE '%$name%' OR email LIKE '$name' OR phone LIKE '$name' OR nit like '$name' ");

    if($name != '' && $status != '' && $type =='')
    $data = $this->db->query("SELECT * FROM `client` WHERE type = '$type' AND status = '$status' AND name like '%$name%' OR last_name LIKE '%$name%' OR email LIKE '$name' OR phone LIKE '$name' OR nit like '$name' ");

    if($name != '' && $status == '' && $type !='')
    $data = $this->db->query("SELECT * FROM `client` WHERE type = '$type' AND status = '$status' AND name like '%$name%' OR last_name LIKE '%$name%' OR email LIKE '$name' OR phone LIKE '$name' OR nit like '$name' ");

    if($name == '' && $status != '' && $type !='')
    $data = $this->db->query("SELECT * FROM `client` WHERE type = '$type' AND status = '$status' AND branch_id = '$branch_id'");

    if($name == '' && $status != '' && $type =='')
    $data = $this->db->query("SELECT * FROM `client` WHERE status = '$status' AND branch_id = '$branch_id'");

    if($name == '' && $status == '' && $type !='')
    $data = $this->db->query("SELECT * FROM `client` WHERE type = '$type' AND branch_id = '$branch_id'");

    if($name == '' && $status == '' && $type == '')
    $data = $this->db->get_where('client', array('branch_id' => $branch_id));


    $n = 1;
    foreach($data->result_array() as $row){
    $table = '<tr>
        <td>'. $n++ .'</td>
        <td><span class="text-secondary"><b>'. $row["name"].' '.$row['last_name'].'</b></span></td>
        <td>';
            if($row['nit'] == ''){$table.='Sin datos';}else{$table.=$row['nit'];}
            $table.='</td>
        <td>';

            if($row['phone'] == ''){$table.='Sin datos';}else{$table.=$row['phone'];}
            $table.='</td>

        <td>
            <span class="label label-lg font-weight-bold label-light-';
                    if($row['type'] == 1){$table.='info ';}else{$table.='warning ';} 
                    $table.='label-inline">';
                if($row['type'] == 1) {$table.='Mayorista';}else{$table.='Minorista';}
                $table.='</span>
        </td>
        <td><a style="color:#3F4254;" href="mailto:'.$row[" email"].'">'.$row["email"].'</a></td>
        <td><span class="text-danger"><b>';
                    if($row['limite'] == 0) {$table.='Sin Limite';} else {$table.=$moneda.$row['limite'];}
                    $table.='</b></span></td>
        <td class="text-center">
            <span class="label label-lg font-weight-bold label-light-';
                            if($row['status']==1){$table.='success ';}else{$table.='danger ';}
                            $table.='label-inline">';
                if($row['status']==1) { $table.='Activo ';} else{$table.='Inactivo';}
                $table.='</span>
        </td>
        <td>
            <div class="d-flex flex-shrink-0">
                <a href=" base_url()admin/actividad_empleado/" data-toggle="tooltip" data-original-title="Actividad del cliente" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10" />
                                <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1" />
                                <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1" />
                            </g>
                        </svg>
                    </span>
                </a>
                &nbsp;
                <a href="javascript:;" onclick="showAjaxModal(\''.base_url().'modal/popup/editar_cliente/'.$row['client_id'].'\');" data-toggle="tooltip" data-original-title="Editar cliente" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                fill="#8950FC" />
                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                fill="#8950FC" />
                        </svg>
                    </span>
                </a>
                &nbsp;';
                if($row['status']==1){
                $table.='<a href="javascript:;" onclick="executeExample('.$row[" client_id"].')" data-toggle="tooltip" data-original-title="Eliminar cliente" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero" />
                                <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000"
                                    opacity="0.3" />
                            </g>
                        </svg>
                    </span>
                </a>';
                }else{
                $table.='<a href=" '.base_url().'admin/clientes/active/'.$row['cliet_id'].'" data-toggle="tooltip" data-original-title="Re-activar cliente" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M6.82866499,18.2771971 L13.5693679,12.3976203 C13.7774696,12.2161036 13.7990211,11.9002555 13.6175044,11.6921539 C13.6029128,11.6754252 13.5872233,11.6596867 13.5705402,11.6450431 L6.82983723,5.72838979 C6.62230202,5.54622572 6.30638833,5.56679309 6.12422426,5.7743283 C6.04415337,5.86555116 6,5.98278612 6,6.10416552 L6,17.9003957 C6,18.1765381 6.22385763,18.4003957 6.5,18.4003957 C6.62084305,18.4003957 6.73759731,18.3566309 6.82866499,18.2771971 Z"
                                    fill="#000000" opacity="0.3" />
                                <path d="M12.828665,18.2771971 L19.5693679,12.3976203 C19.7774696,12.2161036 19.7990211,11.9002555 19.6175044,11.6921539 C19.6029128,11.6754252 19.5872233,11.6596867 19.5705402,11.6450431 L12.8298372,5.72838979 C12.622302,5.54622572 12.3063883,5.56679309 12.1242243,5.7743283 C12.0441534,5.86555116 12,5.98278612 12,6.10416552 L12,17.9003957 C12,18.1765381 12.2238576,18.4003957 12.5,18.4003957 C12.6208431,18.4003957 12.7375973,18.3566309 12.828665,18.2771971 Z"
                                    fill="#000000" />
                            </g>
                        </svg>
                    </span>
                </a>';
                }
                $table.='
            </div>
        </td>
    </tr> ';
    echo $table;
    }
    }
    if($param1 == 'actividad'){
    $user_id = $this->input->post('user');
    $branch_id = $this->session->userdata('branch_id');

    if($user_id !='')
    $data = $this->db->order_by('binnacle_id','DESC')->get_where('binnacle', array('branch_id' => $branch_id , 'user_id'=>$user_id));

    if($user_id =='')
    $data = $this->db->order_by('binnacle_id','DESC')->get_where('binnacle', array('branch_id' => $branch_id));

    $n = 1;
    if($data->num_rows()>0){
    $boton = '<div class="dropdown dropdown-inline mr-2 mb-5">
        <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="svg-icon svg-icon-md">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24" />
                        <path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                            fill="#000000" opacity="0.3" />
                        <path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                            fill="#000000" />
                    </g>
                </svg>
            </span>Exportar
        </button>
        <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
            <ul class="navi flex-column navi-hover py-2">
                <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                    Exportar como:</li>
                <li class="navi-item">
                    <a href="'.base_url().'admin/export_excel/actividades/'.$user_id.'" class="navi-link">
                        <span class="navi-icon">
                            <i class="la la-file-excel-o"></i>
                        </span>
                        <span class="navi-text">Excel</span>
                    </a>
                </li>
                <li class="navi-item">
                    <a href="'.base_url().'admin/export_pdf/actividades/'.$user_id.'" class="navi-link">
                        <span class="navi-icon">
                            <i class="la la-file-pdf-o"></i>
                        </span>
                        <span class="navi-text">PDF</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>';
    echo $boton;
    foreach($data->result_array() as $row){
    $actividad.= '<div class="row">
        <div class="col-sm-2">
            <p class="timeline-label text-dark-75 font-size-lg">
                <b>';
                    setlocale(LC_TIME, "spanish"); $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'])); $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));
                    $actividad.=$Mes_Anyo.'</b>
            </p>
        </div>
        <div class="timeline-badge">
            <i class="fa fa-genderless text-success icon-xl"></i>
        </div>
        <div class="col-sm-9">
            <p class="font-weight-mormal font-size-lg timeline-content text-muted pl-3">
                '.$row['message'].'
            </p>
        </div>
    </div>';
    echo $actividad;
    }
    }else{
    $actividad =' <center>
        <h3>Sin datos</h3><br>
        <img src="'.base_url().'uploads/empty.jpg" style="max-width:35%">
    </center>';echo $actividad;
    }
    }
    if($param1 == 'bodega'){
    $name = $this->input->post('name');
    $provider = $this->input->post('status');
    $branch_id = $this->session->userdata('branch_id');

    if($name != '' && $provider >= 0)
    $data = $this->db->query("SELECT * FROM `products` WHERE destiny = '2' AND status ='1' AND branch_id = '$branch_id' AND provider = '$provider' AND (name like '%$name%' OR code like '%$name%') ");

    if($name !='' && $provider =='')
    $data = $this->db->query("SELECT * FROM `products` WHERE destiny = '2' AND status ='1' AND branch_id = '$branch_id' AND (name like '%$name%' OR code like '%$name%') ");

    if($name =='' && $provider >=0)
    $data = $this->db->query("SELECT * FROM `products` WHERE destiny = '2' AND status ='1' AND branch_id = '$branch_id' AND provider = '$provider'");

    if($name == '' && $provider == '')
    $data = $this->db->query("SELECT * FROM `products` WHERE destiny = '2' AND status ='1' AND branch_id = '$branch_id' ");

    $n = 1;
    foreach($data->result_array() as $row){
    $table.= '<tr>
        <td>'. $n++ .'</td>
        <td> '.$row['name'].'</td>
        <td><span class="label label-lg font-weight-bold label-light-info label-inline">'.
                $this->db->get_where('categories', array('category_id'=>$row['category']))->row()->name.'
            </span>
        </td>
        <td><span class="label label-lg font-weight-bold label-light-success label-inline">'.
                $this->db->get_where('provider', array('provider_id'=>$row['provider']))->row()->name.'
            </span>
        </td>
        <td><span class="text-success"><b>'. $row['stock'].'</b></span></td>
        <td>';
            if($row['variantes']==1) {$table.='Si';} else {$table.='No';}
            $table.='</td>
        <td> '.$moneda.$row['price_buy'].' </td>
        <td> '.$moneda.$row['price_sale'].' </td>
        <td>
            <div class="d-flex flex-shrink-0">
                &nbsp;
                <a href="'.base_url().'admin/editar_producto/'.$row['products_id'].'" data-toggle="tooltip" data-original-title="Editar bodega" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                fill="#8950FC" />
                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                fill="#8950FC" />
                        </svg>
                    </span>
                </a>
                &nbsp;
                <a href="javascript:;" data-toggle="tooltip" data-original-title="Eliminar bodega" onclick="executeExample('.$row['products_id'].')" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero" />
                                <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000"
                                    opacity="0.3" />
                            </g>
                        </svg>
                    </span>
                </a>
            </div>
        </td>
    </tr>';

    }
    echo $table;

    }
    if($param1 == 'inventario'){
    $name = $this->input->post('name');
    $provider = $this->input->post('status');
    $branch_id = $this->session->userdata('branch_id');

    if($name != '' && $provider >= 0)
    $data = $this->db->query("SELECT * FROM `products` WHERE status ='1' AND branch_id = '$branch_id' AND provider = '$provider' AND (name like '%$name%' OR code like '%$name%') ");

    if($name !='' && $provider =='')
    $data = $this->db->query("SELECT * FROM `products` WHERE status ='1' AND branch_id = '$branch_id' AND (name like '%$name%' OR code like '%$name%') ");

    if($name =='' && $provider >=0)
    $data = $this->db->query("SELECT * FROM `products` WHERE status ='1' AND branch_id = '$branch_id' AND provider = '$provider'");

    if($name == '' && $provider == '')
    $data = $this->db->query("SELECT * FROM `products` WHERE status ='1' AND branch_id = '$branch_id' ");

    $n = 1;
    foreach($data->result_array() as $row){

    $stock_inventory = $this->crud_model->get_stock($row['products_id'], $this->session->userdata('branch_id'));
    $stock_bodega = $this->crud_model->get_stock($row['products_id'], 0);
    $table.= '<tr>
        <td>'. $n++ .'</td>
        <td> '.$row['name'].'</td>
        <td><span class="label label-lg font-weight-bold label-light-info label-inline">'.
                $this->db->get_where('categories', array('category_id'=>$row['category']))->row()->name.'
            </span>
        </td>
        <td><span class="label label-lg font-weight-bold label-light-success label-inline">'.
                $this->db->get_where('provider', array('provider_id'=>$row['provider']))->row()->name.'
            </span>
        </td>
        <td><span class="text-success"><b>'.$stock_inventory.'</b></span></td>
        <td><span class="text-warning"><b>'.$stock_bodega.'</b></span></td>
        <td> '.$moneda.$row['price_sale'].' </td>
        <td>';
            if(($stock_inventory + $stock_bodega) >$row['price_sale']):
            $table.='<span class="label label-lg font-weight-bold label-light-success label-inline">
                Disponible
            </span>';
            elseif(($stock_inventory + $stock_bodega) <= $row['alert'] && ($stock_inventory + $stock_bodega)> 0):

                $table.='<span class="label label-lg font-weight-bold label-light-warning label-inline">
                    Alerta
                </span>';

                elseif(($stock_inventory + $stock_bodega) == 0):
                $table.='<span class="label label-lg font-weight-bold label-light-danger label-inline">
                    Sin disponiblidad
                </span>';
                endif;
                $table.='</td>
        <td>
            <div class="d-flex flex-shrink-0">
                &nbsp;
                <a href="'.base_url().'admin/producto_detalle/'.$row['products_id'].'" data-toggle="tooltip" data-original-title="Moviento del producto" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3">
                                </path>
                                <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"></path>
                                <rect fill="#000000" opacity="0.3" x="10" y="9" width="7" height="2" rx="1"></rect>
                                <rect fill="#000000" opacity="0.3" x="7" y="9" width="2" height="2" rx="1"></rect>
                                <rect fill="#000000" opacity="0.3" x="7" y="13" width="2" height="2" rx="1"></rect>
                                <rect fill="#000000" opacity="0.3" x="10" y="13" width="7" height="2" rx="1"></rect>
                                <rect fill="#000000" opacity="0.3" x="7" y="17" width="2" height="2" rx="1"></rect>
                                <rect fill="#000000" opacity="0.3" x="10" y="17" width="7" height="2" rx="1"></rect>
                            </g>
                        </svg>
                    </span>
                </a>
                &nbsp;
                <a href="'.base_url().'admin/editar_producto/'.$row['products_id'].'" data-toggle="tooltip" data-original-title="Editar bodega" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                    <span class="svg-icon svg-icon-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.3"
                                d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                fill="#8950FC" />
                            <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                fill="#8950FC" />
                        </svg>
                    </span>
                </a>
                &nbsp;
                <a href="javascript:;" data-toggle="tooltip" data-original-title="Eliminar bodega" onclick="executeExample('.$row['products_id'].')" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero" />
                                <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000"
                                    opacity="0.3" />
                            </g>
                        </svg>
                    </span>
                </a>
            </div>
        </td>
    </tr>';

    }
    echo $table;

    }
    if($param1 == 'cotizaciones'){
    $name = $this->input->post('name');
    $branch_id = $this->session->userdata('branch_id');

    if($name != '')
    $data = $this->db->query("SELECT q.quotes_id, q.code, q.client_id, q.num_products, q.products, q.date_start, q.date_end, q.total, q.responsable, q.branch_id, q.status
    FROM `quotes` AS q INNER JOIN `client` AS c ON q.client_id = c.client_id INNER JOIN `admin` AS a ON q.responsable = a.admin_id
    WHERE q.status ='1' AND q.branch_id = '$branch_id' AND q.code like '%$name%' OR q.date_end LIKE '%$name%' OR q.date_start LIKE '%$name%' OR c.name LIKE '%$name%' OR
    c.last_name LIKE '%$name%' OR a.name LIKE '%$name%' OR a.last_name LIKE '%$name%'");

    if($name == '')
    $data = $this->db->query("SELECT * FROM `quotes` WHERE status ='1' AND branch_id = '$branch_id'");

    $n = 1;
    foreach($data->result_array() as $row){
    $table.= '<tr>
        <td>'. $n++ . ' </td>
        <td>'. $this->crud_model->getName('client', $row['client_id']).'</td>
        <td><a href="tel:+502 '.$this->db->get_where('client', array('client_id'=>$row['client_id']))->row()->phone.'">
                '.$this->db->get_where('client', array('client_id'=>$row['client_id']))->row()->phone.'</a>
        </td>
        <td><span class="text-info"><b>';
                    setlocale(LC_TIME, "spanish");
                    $Nueva_Fecha = date("d-m-Y", strtotime( $row['date_end'] ));
                    $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));
                    $table.=$Mes_Anyo;
                    $table.='</b></span></td>
        <td><span class="label label-lg font-weight-bolder label-light-success label-inline"> '.$moneda.number_format($row['total'],2,'.',',').' </span>
        </td>
        <td>';
            $hoy = date('Y-m-d') ;
            $vencimiento = $row['date_end'];
            $date1 = new DateTime($hoy);
            $date2 = new DateTime($vencimiento);
            $diff = $date1->diff($date2);

            if($date1 < $date2){ $table.='<span class="badge badge-success">Válida
                                                </span>' ; } elseif( $diff->days == 2 || $diff->days == 1 || $date1 == $date2 ){
                $table.='<span class="badge badge-warning">Por vencer
                </span>';

                }elseif($date1 > $date2){
                $table.='<span class="badge badge-danger">Vencida
                </span>';
                }
                $table.='</td>
        <td>'. $this->crud_model->getName('admin',$row['responsable']).' </td>
        <td>
            <div class="d-flex flex-shrink-0">';
                if($date1 < $date2 || $diff->days == 2 || $diff->days == 1 || $date1 == $date2 ){
                    $table.=' <a href="'.base_url().'admin/cotizaciones/venta/'.$row['code'].'" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-toggle="tooltip" title="" data-original-title="Convertir en venta">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path d="M18.1446364,11.84388 L17.4471627,16.0287218 C17.4463569,16.0335568 17.4455155,16.0383857 17.4446387,16.0432083 C17.345843,16.5865846 16.8252597,16.9469884 16.2818833,16.8481927 L4.91303792,14.7811299 C4.53842737,14.7130189 4.23500006,14.4380834 4.13039941,14.0719812 L2.30560137,7.68518803 C2.28007524,7.59584656 2.26712532,7.50338343 2.26712532,7.4104669 C2.26712532,6.85818215 2.71484057,6.4104669 3.26712532,6.4104669 L16.9929851,6.4104669 L17.606173,3.78251876 C17.7307772,3.24850086 18.2068633,2.87071314 18.7552257,2.87071314 L20.8200821,2.87071314 C21.4717328,2.87071314 22,3.39898039 22,4.05063106 C22,4.70228173 21.4717328,5.23054898 20.8200821,5.23054898 L19.6915238,5.23054898 L18.1446364,11.84388 Z"
                                        fill="#000000" opacity="0.3" />
                                    <path d="M6.5,21 C5.67157288,21 5,20.3284271 5,19.5 C5,18.6715729 5.67157288,18 6.5,18 C7.32842712,18 8,18.6715729 8,19.5 C8,20.3284271 7.32842712,21 6.5,21 Z M15.5,21 C14.6715729,21 14,20.3284271 14,19.5 C14,18.6715729 14.6715729,18 15.5,18 C16.3284271,18 17,18.6715729 17,19.5 C17,20.3284271 16.3284271,21 15.5,21 Z"
                                        fill="#000000" />
                                </g>
                            </svg>
                        </span>
                    </a>';
                    }
                    $table.='&nbsp;
                    <a href="'.base_url().'admin/detalles_cotizacion/'.$row['quotes_id'].'" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1" data-toggle="tooltip" title="" data-original-title="Detalles">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10" />
                                    <rect fill="#000000" x="11" y="10" width="2" height="7" rx="1" />
                                    <rect fill="#000000" x="11" y="7" width="2" height="2" rx="1" />
                                </g>
                            </svg>
                        </span>
                    </a>
                    &nbsp;
                    <a href="'.base_url().'admin/export_pdf/quotes/'.$row['quotes_id'].'" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm" data-toggle="tooltip" title="" data-original-title="Imprimir">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <path d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z"
                                        fill="#000000"></path>
                                    <rect fill="#000000" opacity="0.3" x="8" y="2" width="8" height="2" rx="1"></rect>
                                </g>
                            </svg>
                        </span>
                    </a>
                    &nbsp;
                    <a href="'.base_url().'admin/editar_cotizacion/'.$row['quotes_id'].'" data-toggle="tooltip" data-original-title="Editar cotización" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3"
                                    d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                    fill="#8950FC" />
                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                    fill="#8950FC" />
                            </svg>
                        </span>
                    </a>
                    &nbsp;
                    <a href="javascript:;" data-toggle="tooltip" data-original-title="Eliminar cotizacón" onclick="executeExample('.$row['quotes_id'].')" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero" />
                                    <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000"
                                        opacity="0.3" />
                                </g>
                            </svg>
                        </span>
                    </a>
            </div>
        </td>
    </tr>';

    }
    echo $table;
    }
    if($param1 == 'productTras'){
        $name = $this->input->post('name');
        $desde = $this->input->post('desde');
        $branch_name = $this->db->get_where('branch', array('branch_id'=>$desde))->row()->name;
        $branch_id = $this->session->userdata('branch_id');
    
        if($name != ''){
        $data = $this->db->query("SELECT * FROM `products` WHERE status ='1' AND (name like '%$name%' OR code like '%$name%') ");
    
        if($data->num_rows()>0){
            $stock = $this->crud_model->get_stock($data->row()->products_id, $branch_id)+$this->crud_model->get_stock($data->row()->products_id, 0);
            
                if($name === $data->row()->code){
                    $scan.= $data->row()->products_id;
                }else{
                    $scan.= '0';
                }
            foreach($data->result_array() as $row){
                $stock_inventory = 0; $stock_bodega = 0;
                if($row['presentation'] == 'Caja'){
                    $total = $this->crud_model->get_stock($row['id_prod_matriz'], $desde);
                    $stock_inventory = ($total/$row['cnt_prod_matriz']); 
                    $tot_bodega= $this->crud_model->get_stock($row['id_prod_matriz'], 0);
                    $stock_bodega = ($tot_bodega/$row['cnt_prod_matriz']);
                }else{
                    $stock_inventory  = $this->crud_model->get_stock($row['products_id'], $desde);
                    $stock_bodega = $this->crud_model->get_stock($row['products_id'], 0);
                }
                $table.='
                <tr>
                    <td>
                        <a href="javascript:;"';
                        if ($stock_inventory > 0) {
                            $table.='onclick="addOption('.$row['products_id'].')" id="click"';
                        }
                        $table.='>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50 symbol-sm flex-shrink-0">
                                    <img class="h-75 align-self-end" src="';
                                                        
                                    if($row['img']!=''){ $table.=base_url().'uploads/productos/'.$row['img'];} else { $table.=base_url().'uploads/productos/default_product.png'; } 
                                    $table.='" alt="photo">
                                </div>
                                <div class="ml-4">
                                    <div class="text-dark-75 font-weight-normal font-size-lg mb-0">
                                        '.$row['name'].' - '.$row['code'].'- <b class="text-danger">['.$this->db->get_where('mark', array('mark_id'=>$row['mark']))->row()->name.']</b>
                                    </div>';
                        if ($desde > 0) {
                            $table .= '<a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">'.$branch_name.':
                                        <span class="label label-lg font-weight-bold  label-light-info label-inline">'.$stock_inventory.'</span></a>';
                        }
                        $table .= '<a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">Bodega:
                                        <span class="label label-lg font-weight-bold  label-light-info label-inline">'.$stock_bodega.'</span></a> </div>
                                    </div>
                        </a>
                    </td>
                </tr>';            
            }
        
            
    
        }else{
            $table.= '<div class="card-body" style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
            <center>
                <h3>Sin datos</h3><br>
                <img src="'.base_url().'uploads/empty.jpg" style="max-width:25%">
            </center>
        </div>';
        }
    
        }
        $resul = array('scan'=>$scan, 'table'=>$table);
        echo json_encode($resul) ;
    }
    if($param1 == 'productSaleP'){
        $name = $this->input->post('name');
        $branch_id = $this->session->userdata('branch_id');
    
        if($name != '') {
            $data = $this->db->query("SELECT * FROM `products` WHERE status ='1' AND  ((name like '%$name%' OR code like '%$name%')) ORDER BY name ASC");
    
            if($data->num_rows()>0) {
                if($name === $data->row()->code) {
                    $scan.= $data->row()->products_id;
                } else {
                    $scan.= '0';
                }
                $stock = '';
                foreach($data->result_array() as $row) {
                    $stock = $this->crud_model->get_stock($row['products_id'], $branch_id);
                    $stock_inventory = 0; $stock_bodega = 0;
                    $producto = $this->db->get_where('products',array('products_id'=>$row['products_id']))->row();
                    // log_message("error", "Producto: ".json_encode($producto));
                    if($producto->presentation == 'Caja') {
                        $total = $this->crud_model->get_stock($producto->id_prod_matriz, $this->session->userdata('branch_id'));
                        if($producto->cnt_prod_matriz > 0) $stock_inventory = ($total/$producto->cnt_prod_matriz);
                        $stock = $stock_inventory;
                        $tot_bodega= $this->crud_model->get_stock($producto->id_prod_matriz, 0);
                        if($producto->cnt_prod_matriz > 0) $stock_bodega = ($tot_bodega/$producto->cnt_prod_matriz);
                    } else {
                        $stock_inventory  = $this->crud_model->get_stock($row['products_id'], $this->session->userdata('branch_id'));
                        $stock_bodega = $this->crud_model->get_stock($row['products_id'], 0);
                    }
                    //$stock = '1';
                    $table.='
                    <tr>
                        <td>
                            <a href="javascript:;"';
                            if($stock >= 1){
                                $table.='onclick="addOption('.$row['products_id'].')" id="click"';
                            }
                            
                            $table.='>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50 symbol-sm flex-shrink-0">
                                        <img class="h-75 align-self-end" src="';
                                                            
                                        if($row['img']!=''){ $table.=base_url().'uploads/productos/'.$row['img'];} else { $table.=base_url().'uploads/productos/default_product.png'; } 
                                        $table.='" alt="photo">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-dark-75 font-weight-normal font-size-lg mb-0">
                                            '.$row['name'].' - '.$row['code'].' (';
                        if (!$row['iva']) $table .= 'Exento';
                        else $table .= 'Afecto';
                        $table .= ') - <b class="text-danger">Q.'.$this->crud_model->last_price_buy($row['products_id'], 0).' - <b class="text-primary">Q.'.$this->crud_model->last_price_buy_farma($row['products_id']).' - <b class="text-success">Q.'.$this->crud_model->last_price_buy_my($row['products_id']).'</b>
                                        </div>
                                        <a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">Tienda:
                                            <span class="label label-lg font-weight-bold  label-light-info label-inline">'.$stock_inventory.'</span></a>
                                            
                                        <a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">Bodega:
                                            <span class="label label-lg font-weight-bold  label-light-info label-inline">'.$stock_bodega.'</span></a>
                                        
                                    </div>';
                                if($stock == 0){ $table .= '<div class=" ml-3 alert alert-danger" role="alert">Producto sin Stock ir a<a href="'.base_url().'admin/traslados"  target="_blank"> traslado </a>  </div>';}
                            $table.='</div>
                            </a>
                        </td>
                    </tr>';
                }
            } else{
                $table.= '<div class="card-body" style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
                    <center>
                        <h3>Sin datos</h3><br>
                        <img src="'.base_url().'uploads/empty.jpg" style="max-width:25%">
                    </center>
                </div>';
            }
        
        }
        $resul = array('scan'=>$scan, 'table'=>$table);
        echo json_encode($resul) ;
    }
    if($param1 == 'productSale'){
        $name = $this->input->post('name');
        $branch_id = $this->session->userdata('branch_id');
    
        if($name != ''){
        $data = $this->db->query("SELECT * FROM `products` WHERE status ='1' AND ((name like '%$name%' OR code like '%$name%')) ORDER BY name ASC");
    
        if($data->num_rows()>0){
            if($name === $data->row()->code){
                $scan.= $data->row()->products_id;
            }else{
                $scan.= '0';
            }
            $stock = '';
            foreach($data->result_array() as $row){
                $stock = $this->crud_model->get_stock($row['products_id'], $branch_id);
                //$stock = '1'; $stock_inventory = 0; $stock_bodega = 0;
                $producto = $this->db->get_where('products',array('products_id'=>$row['products_id']))->row();
                if($producto->presentation == 'Caja'){
                    $total = $this->crud_model->get_stock($producto->id_prod_matriz, $this->session->userdata('branch_id'));
                    $stock_inventory = ($total/$producto->cnt_prod_matriz);
                    $stock = $stock_inventory;
                    $tot_bodega= $this->crud_model->get_stock($producto->id_prod_matriz, 0);
                    $stock_bodega = ($tot_bodega/$producto->cnt_prod_matriz);
                }else{
                    $stock_inventory  = $this->crud_model->get_stock($row['products_id'], $this->session->userdata('branch_id'));
                    $stock_bodega = $this->crud_model->get_stock($row['products_id'], 0);
                }
                $table.='
                <tr>
                    <td>
                        <a href="javascript:;"';
                        if($stock >= 1){
                            $table.='onclick="addOption('.$row['products_id'].')" id="click"';
                        }
                        
                        $table.='>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50 symbol-sm flex-shrink-0">
                                    <img class="h-75 align-self-end" src="';
                                                        
                                    if($row['img']!=''){ $table.=base_url().'uploads/productos/'.$row['img'];} else { $table.=base_url().'uploads/productos/default_product.png'; } 
                                    $table.='" alt="photo">
                                </div>
                                <div class="ml-4">
                                    <div class="text-dark-75 font-weight-normal font-size-lg mb-0">
                                        '.$row['name'].' - '.$row['code'].' (';
                        if (!$row['iva']) $table .= 'Exento';
                        else $table .= 'Afecto';
                        $table .= ') - <b class="text-danger">Q.'.$this->crud_model->last_price_buy($row['products_id'], 0).' - <b class="text-primary">Q.'.$this->crud_model->last_price_buy_farma($row['products_id']).' - <b class="text-success">Q.'.$this->crud_model->last_price_buy_my($row['products_id']).'</b>
                                    </div>
                                   
                                    <a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">Tienda:
                                        <span class="label label-lg font-weight-bold  label-light-info label-inline">'.$stock_inventory.'</span></a>
                                        
                                    <a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">Bodega:
                                        <span class="label label-lg font-weight-bold  label-light-info label-inline">'.$stock_bodega.'</span></a>
                                    <a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">Total:
                                        <span class="label label-lg font-weight-bold  label-light-info label-inline">'.($stock_inventory+$stock_bodega).'</span></a>
                                    
                                </div>';
                            if($stock == 0){ $table .= '<div class=" ml-3 alert alert-danger" role="alert">Producto sin Stock ir a<a href="'.base_url().'admin/traslados"  target="_blank"> traslado </a>  </div>';}
                        $table.='</div>
                        </a>
                    </td>
                </tr>';            
            }
        }else{
            $table.= '<div class="card-body" style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
            <center>
                <h3>Sin datos</h3><br>
                <img src="'.base_url().'uploads/empty.jpg" style="max-width:25%">
            </center>
        </div>';
        }
    
        }
        $resul = array('scan'=>$scan, 'table'=>$table);
        echo json_encode($resul) ;
        }
    if($param1 == 'productSaleCot'){
        $name = $this->input->post('name');
        $branch_id = $this->session->userdata('branch_id');
    
        if($name != ''){
        $data = $this->db->query("SELECT * FROM `products` WHERE status ='1' AND (name like '%$name%' OR code like '%$name%') ORDER BY name ASC");
    
        if($data->num_rows()>0){
            if($name === $data->row()->code){
                $scan.= $data->row()->products_id;
            }else{
                $scan.= '0';
            }
            $stock = '';
            foreach($data->result_array() as $row){
                $stock = 0; $stock_inventory = 0; $stock_bodega = 0;
                $producto = $this->db->get_where('products',array('products_id'=>$row['products_id']))->row();
                if($producto->presentation == 'Caja'){
                    $total = $this->crud_model->get_stock($producto->id_prod_matriz, $this->session->userdata('branch_id'));
                    $stock_inventory = ($total/$producto->cnt_prod_matriz);
                    $stock = $stock_inventory;
                    $tot_bodega= $this->crud_model->get_stock($producto->id_prod_matriz, 0);
                    $stock_bodega = ($tot_bodega/$producto->cnt_prod_matriz);
                }else{
                    $stock_inventory  = $this->crud_model->get_stock($row['products_id'], $this->session->userdata('branch_id'));
                    $stock_bodega = $this->crud_model->get_stock($row['products_id'], 0);
                }
                // $stock = '1';
                $table.='
                <tr>
                    <td>
                        <a href="javascript:;"';
                        if($stock_inventory >= 1){
                            $table.='onclick="addOption('.$row['products_id'].')" id="click"';
                        }
                        
                        $table.='>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50 symbol-sm flex-shrink-0">
                                    <img class="h-75 align-self-end" src="';
                                                        
                                    if($row['img']!=''){ $table.=base_url().'uploads/productos/'.$row['img'];} else { $table.=base_url().'uploads/productos/default_product.png'; } 
                                    $table.='" alt="photo">
                                </div>
                                <div class="ml-4">
                                    <div class="text-dark-75 font-weight-normal font-size-lg mb-0">
                                        '.$row['name'].' - '.$row['code'].' (';
                                    if (!$row['iva']) $table .= 'Exento';
                                    else $table .= 'Afecto';
                                    $table .= ') - <b class="text-danger">Q.'.$this->crud_model->last_price_buy($row['products_id'], 0).' - <b class="text-primary">Q.'.$this->crud_model->last_price_buy_farma($row['products_id']).' - <b class="text-success">Q.'.$this->crud_model->last_price_buy_my($row['products_id']).'</b>
                                    </div>
                                    <a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">Inventario:
                                        <span class="label label-lg font-weight-bold  label-light-info label-inline">'.$stock_inventory.'</span></a>
                                    <a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">Bodega:
                                        <span class="label label-lg font-weight-bold  label-light-info label-inline">'.$stock_bodega.'</span></a> 
                                </div>';
                            if($stock_inventory == 0){ $table .= '<div class=" ml-3 alert alert-danger" role="alert">Producto sin Stock ir a<a href="'.base_url().'admin/traslados"  target="_blank"> traslado </a>  </div>';}
                        $table.='</div>
                        </a>
                    </td>
                </tr>';            
            }
        }else{
            $table.= '<div class="card-body" style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
            <center>
                <h3>Sin datos</h3><br>
                <img src="'.base_url().'uploads/empty.jpg" style="max-width:25%">
            </center>
        </div>';
        }
    
        }
        $resul = array('scan'=>$scan, 'table'=>$table);
        echo json_encode($resul) ;
        }
    if($param1 == 'productPurchase'){
        $name = $this->input->post('name');
        $branch_id = $this->session->userdata('branch_id');

        if($name != ''){
            $data = $this->db->query("SELECT * FROM `products` WHERE status ='1' AND (name like '%$name%' OR code like '%$name%') ORDER BY name ASC");

            if($data->num_rows()>0){
                if($name === $data->row()->code){
                    $scan.= $data->row()->products_id;
                }else{
                    $scan.= '0';
                }
                foreach($data->result_array() as $row){
                    $id_product = $row['products_id'];
                    if ($row['presentation'] == 'Caja') {
                        $id_product = $row['id_prod_matriz'];
                        $stock_inventario = 0; $stock_bodega = 0;
                        if ($row['cnt_prod_matriz'] > 0) {
                            $stock_inventario = $this->crud_model->get_stock($id_product, $branch_id) / $row['cnt_prod_matriz'];
                            $stock_bodega = $this->crud_model->get_stock($id_product, 0) / $row['cnt_prod_matriz'];
                        }
                    } else {
                        $stock_inventario = $this->crud_model->get_stock($row['products_id'], $branch_id);
                        $stock_bodega = $this->crud_model->get_stock($row['products_id'], 0);
                    }
                    $table.= '<tr>
                        <td>
                            <a href="javascript:;" onclick="addOption('.$row['products_id'].')" id="click" >
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50 symbol-sm flex-shrink-0">
                                        <img class="h-75 align-self-end" src="';
                                                            
                                                                if($row['img']!=''){ $table.=base_url().'uploads/productos/'.$row['img'];} else { $table.=base_url().'uploads/productos/default_product.png'; } 
                                                                $table.='" alt="photo">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-dark-75 font-weight-normal font-size-lg mb-0">
                                            '.$row['name'].'- <b class="text-danger">['.$this->db->get_where('mark', array('mark_id'=>$row['mark']))->row()->name.']</b></div>
                                        <a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">Inventario:
                                            <span class="label label-lg font-weight-bold  label-light-info label-inline">'.$stock_inventario.'</span></a>
                                        <a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">Bodega:
                                            <span class="label label-lg font-weight-bold  label-light-info label-inline">'.$stock_bodega.'</span></a>
                                    </div>
                                </div>
                            </a>
                        </td>
                    </tr>';
                }

            }else{
                $table.= '<div class="card-body" style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
                <center>
                    <h3>Sin datos</h3><br>
                    <img src="'.base_url().'uploads/empty.jpg" style="max-width:25%">
                </center>
            </div>';

            }

        }
        $resul = array('scan'=>$scan, 'table'=>$table);
        echo json_encode($resul) ;
    }
    if($param1 == 'clients'){
        $nit = $this->input->post('nit');
        $branch_id = $this->session->userdata('branch_id');
        if($nit != 'c/f' && $nit != 'C/F' && $nit != ''){
        $data = $this->db->query("SELECT * FROM `client` WHERE status ='1' AND nit like '$nit' AND branch_id = '$branch_id'");
        if ($data->num_rows() <= 0) {
            $data = $this->db->query("SELECT * FROM `client` WHERE status ='1' AND nit like '$nit'");
        }

        if($data->num_rows() == 1){
            $dt =  json_encode($data->result_array());
            
            echo $dt;
        }else{
            echo '0';
        }
    }
}
    if($param1 == 'search_product'){
        $name = $this->input->post('name');
        $branch_id = $this->session->userdata('branch_id');
    
        if($name != ''){

        $categorias = $this->db->like('name',$name)->get_where('categories', array('status' => 1))->row()->category_id;

        if($categorias > 0){
            $data = $this->db->query("SELECT * FROM `products` WHERE status ='1' AND category like '%$categorias%' ");
            
        }else{
            $data = $this->db->query("SELECT * FROM `products` WHERE status ='1' AND (name like '%$name%' OR code like '%$name%')");
        }
        

        if($data->num_rows()>0){

            foreach($data->result_array() as $row){
    
                $stock = $this->crud_model->get_stock($row['products_id'], $branch_id);
                
                $table.= '<tr>
                        <td>
                            <a href="'; if($stock > 0){ $table.= base_url().'admin/nueva_venta/'.$this->crud_model->getCodeVenta().'/'.$row['products_id'].'"'; }else{ $table.='javascrip:;" onclick="showModalProductos(\' '.base_url().'modal/popup/productos_0/'.$row['products_id'].' \');"'; }  $table.='>
                                <div class="d-flex align-items-center">
                                    <div class="symbol symbol-50 symbol-sm flex-shrink-0">
                                        <img class="h-75 align-self-end" src="';
                                        if($row['img']!=''){ $table.=base_url().'uploads/productos/'.$row['img'];} else { $table.=base_url().'uploads/productos/default_product.png'; } 
                                        $table.='" alt="photo">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-dark-75 font-weight-normal font-size-lg mb-0">
                                            '.$row['name'].'- <b class="text-danger">['.$this->db->get_where('mark', array('mark_id'=>$row['mark']))->row()->name.']</b></div>
                                        <a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">Inventario:
                                            <span class="label label-lg font-weight-bold  label-light-info label-inline">'.$this->crud_model->get_stock($row['products_id'], $branch_id).'</span></a>
                                        <a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">Bodega:
                                            <span class="label label-lg font-weight-bold  label-light-info label-inline">'.$this->crud_model->get_stock($row['products_id'], 0).'</span></a></div>
                                </div>
                            </a>
                        </td>
                    </tr>';
                }
                    if($name == 'c'|| $name == 'co'|| $name == 'con'){
                        $table.='<tr>
                        <td>
                        <div class="d-flex align-items-center bg-light-info rounded p-5 mb-5">
                        <span class="svg-icon svg-icon-info mr-5">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect opacity="0.200000003" x="0" y="0" width="24" height="24" />
                                <path
                                    d="M4.5,7 L9.5,7 C10.3284271,7 11,7.67157288 11,8.5 C11,9.32842712 10.3284271,10 9.5,10 L4.5,10 C3.67157288,10 3,9.32842712 3,8.5 C3,7.67157288 3.67157288,7 4.5,7 Z M13.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L13.5,18 C12.6715729,18 12,17.3284271 12,16.5 C12,15.6715729 12.6715729,15 13.5,15 Z"
                                    fill="#000000" opacity="0.3" />
                                <path
                                    d="M17,11 C15.3431458,11 14,9.65685425 14,8 C14,6.34314575 15.3431458,5 17,5 C18.6568542,5 20,6.34314575 20,8 C20,9.65685425 18.6568542,11 17,11 Z M6,19 C4.34314575,19 3,17.6568542 3,16 C3,14.3431458 4.34314575,13 6,13 C7.65685425,13 9,14.3431458 9,16 C9,17.6568542 7.65685425,19 6,19 Z"
                                    fill="#000000" />
                            </g>
                        </svg>
                            </span>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <a href="'.base_url().'admin/configuracion/" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Configuración</a>
                            </div>
                        </div>
                        </td>
                    </tr> ';
                        }
                    if($name == 's'|| $name == 'su' || $name == 'suc'){
                            $table.='<tr>
                            <td>
                            <div class="d-flex align-items-center bg-light-info rounded p-5 mb-5">
                            <span class="svg-icon svg-icon-info mr-5">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <path d="M3.5,3 L5,3 L5,19.5 C5,20.3284271 4.32842712,21 3.5,21 L3.5,21 C2.67157288,21 2,20.3284271 2,19.5 L2,4.5 C2,3.67157288 2.67157288,3 3.5,3 Z" fill="#000000"></path>
                                <path d="M6.99987583,2.99995344 L19.754647,2.99999303 C20.3069317,2.99999474 20.7546456,3.44771138 20.7546439,3.99999613 C20.7546431,4.24703684 20.6631995,4.48533385 20.497938,4.66895776 L17.5,8 L20.4979317,11.3310353 C20.8673908,11.7415453 20.8341123,12.3738351 20.4236023,12.7432941 C20.2399776,12.9085564 20.0016794,13 19.7546376,13 L6.99987583,13 L6.99987583,2.99995344 Z" fill="#000000" opacity="0.3"></path>
                            </g>
                        </svg>
                    </span>
                                <div class="d-flex flex-column flex-grow-1 mr-2">
                                    <a href="'.base_url().'admin/sucursales/" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Sucursales</a>
                                </div>
                            </div>
                            </td>
                        </tr> ';
                            }
                    if($name == 'c'|| $name == 'ca' || $name == 'cal'){
                                $table.='<tr>
                                <td>
                                <div class="d-flex align-items-center bg-light-info rounded p-5 mb-5">
                                <span class="svg-icon svg-icon-info mr-5">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <rect fill="#000000" x="2" y="5" width="19" height="4" rx="1"></rect>
                                <rect fill="#000000" opacity="0.3" x="2" y="11" width="19" height="10" rx="1"></rect>
                            </g>
                        </svg>
                    </span>
                                        </span>
                                    </span>
                                    <div class="d-flex flex-column flex-grow-1 mr-2">
                                        <a href="'.base_url().'admin/calendario/" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Calendario</a>
                                    </div>
                                </div>
                                </td>
                            </tr> ';
                                }
                    if($name == 'n'|| $name == 'no' || $name == 'not'){
                                    $table.='<tr>
                                    <td>
                                    <div class="d-flex align-items-center bg-light-info rounded p-5 mb-5">
                                    <span class="svg-icon svg-icon-success mr-5">
                                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                            <span class="svg-icon svg-icon-info svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Design/Component.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M12.7442084,3.27882877 L19.2473374,6.9949025 C19.7146999,7.26196679 20.003129,7.75898194 20.003129,8.29726722 L20.003129,15.7027328 C20.003129,16.2410181 19.7146999,16.7380332 19.2473374,17.0050975 L12.7442084,20.7211712 C12.2830594,20.9846849 11.7169406,20.9846849 11.2557916,20.7211712 L4.75266256,17.0050975 C4.28530007,16.7380332 3.99687097,16.2410181 3.99687097,15.7027328 L3.99687097,8.29726722 C3.99687097,7.75898194 4.28530007,7.26196679 4.75266256,6.9949025 L11.2557916,3.27882877 C11.7169406,3.01531506 12.2830594,3.01531506 12.7442084,3.27882877 Z M12,14.5 C13.3807119,14.5 14.5,13.3807119 14.5,12 C14.5,10.6192881 13.3807119,9.5 12,9.5 C10.6192881,9.5 9.5,10.6192881 9.5,12 C9.5,13.3807119 10.6192881,14.5 12,14.5 Z" fill="#000000"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                            </span>
                                        </span>
                                        <div class="d-flex flex-column flex-grow-1 mr-2">
                                            <a href="'.base_url().'admin/notificaciones/" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Notificaciones</a>
                                        </div>
                                    </div>
                                    </td>
                                </tr> ';
                                }
                    if($name == 'e' || $name == 'em' || $name == 'emp' ){
                        $table.='<tr>
                                    <td>
                                    <a href="'.base_url().'admin/empleados/">
                                        <div class="d-flex align-items-center mb-10">
                                    <div class="symbol symbol-40 symbol-light-primary mr-5">
                                        <span class="symbol-label">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Design/Layers.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"/>
                                            <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"/>
                                            <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"/>
                                        </g>
                                    </svg><!--end::Svg Icon--></span>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column font-weight-bold">
                                        <a  href="'.base_url().'admin/empleados/" class="text-dark text-hover-primary mb-1 font-size-lg">Usuarios</a>
                                        <span class="text-muted">Empleados</span>
                                    </div>
                                </div>
                                    </a>
                                    </td>
                                </tr> ';
                            }

                    if($name == 'a' || $name == 'ad' || $name == 'adm' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/admins/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Design/Layers.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/admins/" class="text-dark text-hover-primary mb-1 font-size-lg">Usuarios</a>
                                    <span class="text-muted">Administradores</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'p' || $name == 'pr' || $name == 'pro' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/proveedores/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Design/Layers.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/proveedores/" class="text-dark text-hover-primary mb-1 font-size-lg">Usuarios</a>
                                    <span class="text-muted">Proveedores</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'c' || $name == 'cl' || $name == 'cli' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/clientes/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Design/Layers.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24"/>
                                        <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/clientes/" class="text-dark text-hover-primary mb-1 font-size-lg">Usuarios</a>
                                    <span class="text-muted">Clientes</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }
                    if($name == 'i' || $name == 'in' || $name == 'inv' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/inventario/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Commode2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M5.5,2 L18.5,2 C19.3284271,2 20,2.67157288 20,3.5 L20,6.5 C20,7.32842712 19.3284271,8 18.5,8 L5.5,8 C4.67157288,8 4,7.32842712 4,6.5 L4,3.5 C4,2.67157288 4.67157288,2 5.5,2 Z M11,4 C10.4477153,4 10,4.44771525 10,5 C10,5.55228475 10.4477153,6 11,6 L13,6 C13.5522847,6 14,5.55228475 14,5 C14,4.44771525 13.5522847,4 13,4 L11,4 Z" fill="#000000" opacity="0.3"/>
                                            <path d="M5.5,9 L18.5,9 C19.3284271,9 20,9.67157288 20,10.5 L20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 L4,10.5 C4,9.67157288 4.67157288,9 5.5,9 Z M11,11 C10.4477153,11 10,11.4477153 10,12 C10,12.5522847 10.4477153,13 11,13 L13,13 C13.5522847,13 14,12.5522847 14,12 C14,11.4477153 13.5522847,11 13,11 L11,11 Z M5.5,16 L18.5,16 C19.3284271,16 20,16.6715729 20,17.5 L20,20.5 C20,21.3284271 19.3284271,22 18.5,22 L5.5,22 C4.67157288,22 4,21.3284271 4,20.5 L4,17.5 C4,16.6715729 4.67157288,16 5.5,16 Z M11,18 C10.4477153,18 10,18.4477153 10,19 C10,19.5522847 10.4477153,20 11,20 L13,20 C13.5522847,20 14,19.5522847 14,19 C14,18.4477153 13.5522847,18 13,18 L11,18 Z" fill="#000000"/>
                                        </g>
                                        </svg></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/invnetario/" class="text-dark text-hover-primary mb-1 font-size-lg">Productos</a>
                                    <span class="text-muted">Inventario</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }
                    if($name == 't' || $name == 'tr' || $name == 'tra' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/traslados/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Commode2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M5.5,2 L18.5,2 C19.3284271,2 20,2.67157288 20,3.5 L20,6.5 C20,7.32842712 19.3284271,8 18.5,8 L5.5,8 C4.67157288,8 4,7.32842712 4,6.5 L4,3.5 C4,2.67157288 4.67157288,2 5.5,2 Z M11,4 C10.4477153,4 10,4.44771525 10,5 C10,5.55228475 10.4477153,6 11,6 L13,6 C13.5522847,6 14,5.55228475 14,5 C14,4.44771525 13.5522847,4 13,4 L11,4 Z" fill="#000000" opacity="0.3"/>
                                            <path d="M5.5,9 L18.5,9 C19.3284271,9 20,9.67157288 20,10.5 L20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 L4,10.5 C4,9.67157288 4.67157288,9 5.5,9 Z M11,11 C10.4477153,11 10,11.4477153 10,12 C10,12.5522847 10.4477153,13 11,13 L13,13 C13.5522847,13 14,12.5522847 14,12 C14,11.4477153 13.5522847,11 13,11 L11,11 Z M5.5,16 L18.5,16 C19.3284271,16 20,16.6715729 20,17.5 L20,20.5 C20,21.3284271 19.3284271,22 18.5,22 L5.5,22 C4.67157288,22 4,21.3284271 4,20.5 L4,17.5 C4,16.6715729 4.67157288,16 5.5,16 Z M11,18 C10.4477153,18 10,18.4477153 10,19 C10,19.5522847 10.4477153,20 11,20 L13,20 C13.5522847,20 14,19.5522847 14,19 C14,18.4477153 13.5522847,18 13,18 L11,18 Z" fill="#000000"/>
                                        </g>
                                        </svg></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/traslados/" class="text-dark text-hover-primary mb-1 font-size-lg">Productos</a>
                                    <span class="text-muted">Traslados</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }
                    if($name == 'c' || $name == 'ca' || $name == 'cat' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/categorias/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Commode2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M5.5,2 L18.5,2 C19.3284271,2 20,2.67157288 20,3.5 L20,6.5 C20,7.32842712 19.3284271,8 18.5,8 L5.5,8 C4.67157288,8 4,7.32842712 4,6.5 L4,3.5 C4,2.67157288 4.67157288,2 5.5,2 Z M11,4 C10.4477153,4 10,4.44771525 10,5 C10,5.55228475 10.4477153,6 11,6 L13,6 C13.5522847,6 14,5.55228475 14,5 C14,4.44771525 13.5522847,4 13,4 L11,4 Z" fill="#000000" opacity="0.3"/>
                                            <path d="M5.5,9 L18.5,9 C19.3284271,9 20,9.67157288 20,10.5 L20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 L4,10.5 C4,9.67157288 4.67157288,9 5.5,9 Z M11,11 C10.4477153,11 10,11.4477153 10,12 C10,12.5522847 10.4477153,13 11,13 L13,13 C13.5522847,13 14,12.5522847 14,12 C14,11.4477153 13.5522847,11 13,11 L11,11 Z M5.5,16 L18.5,16 C19.3284271,16 20,16.6715729 20,17.5 L20,20.5 C20,21.3284271 19.3284271,22 18.5,22 L5.5,22 C4.67157288,22 4,21.3284271 4,20.5 L4,17.5 C4,16.6715729 4.67157288,16 5.5,16 Z M11,18 C10.4477153,18 10,18.4477153 10,19 C10,19.5522847 10.4477153,20 11,20 L13,20 C13.5522847,20 14,19.5522847 14,19 C14,18.4477153 13.5522847,18 13,18 L11,18 Z" fill="#000000"/>
                                        </g>
                                        </svg></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/categorias/" class="text-dark text-hover-primary mb-1 font-size-lg">Productos</a>
                                    <span class="text-muted">Categorias</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'v' || $name == 've' || $name == 'ven' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/ventas/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                        <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/ventas/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                                    <span class="text-muted">Ventas</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'c' || $name == 'co' || $name == 'com' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/compras/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                        <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/compras/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                                    <span class="text-muted">Compras</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'c' || $name == 'co' || $name == 'cot' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/cotizaciones/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                        <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/cotizaciones/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                                    <span class="text-muted">Cotizaciones</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'c' || $name == 'cr' || $name == 'cre' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/creditos/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                        <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/creditos/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                                    <span class="text-muted">Creditos</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'a' || $name == 'an' || $name == 'anu' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/anulaciones/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                        <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/anulaciones/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                                    <span class="text-muted">Anulaciones</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'c' || $name == 'ca' || $name == 'cam' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/cambios/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                        <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/cambios/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                                    <span class="text-muted">Cambios</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'e' || $name == 'en' || $name == 'env' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/envios/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                        <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/envios/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                                    <span class="text-muted">Envios</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 't' || $name == 'tr' || $name == 'tra' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/transporte/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                        <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/transporte/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                                    <span class="text-muted">Transporte</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'g' || $name == 'ge' || $name == 'gen' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/reportes/generales/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                                <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/reportes/generales/" class="text-dark text-hover-primary mb-1 font-size-lg">Reportes</a>
                                    <span class="text-muted">Generales</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'v' || $name == 've' || $name == 'ven' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/reportes/ventas/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                                <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/reportes/ventas/" class="text-dark text-hover-primary mb-1 font-size-lg">Reportes</a>
                                    <span class="text-muted">Ventas</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'c' || $name == 'co' || $name == 'com' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/reportes/compras/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                                <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/reportes/compras/" class="text-dark text-hover-primary mb-1 font-size-lg">Reportes</a>
                                    <span class="text-muted">Compras</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'c' || $name == 'ca' || $name == 'cam' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/reportes/cambios/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                                <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/reportes/cambios/" class="text-dark text-hover-primary mb-1 font-size-lg">Reportes</a>
                                    <span class="text-muted">Cambios</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'a' || $name == 'an' || $name == 'anu' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/reportes/anulaciones/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                                <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/reportes/anulaciones/" class="text-dark text-hover-primary mb-1 font-size-lg">Reportes</a>
                                    <span class="text-muted">Anulaciones</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }
                        

                    if($name == 'f' || $name == 'fi' || $name == 'fin' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/reportes_financieros/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                                <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/reportes_financieros/" class="text-dark text-hover-primary mb-1 font-size-lg">Reportes</a>
                                    <span class="text-muted">Fianciero</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'a' || $name == 'ac' || $name == 'act' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/reportes/actividad/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                                <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                            </g>
                                        </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/reportes/actividad/" class="text-dark text-hover-primary mb-1 font-size-lg">Reportes</a>
                                    <span class="text-muted">Actividad</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'i' || $name == 'in' || $name == 'ing' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/ingresos/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
                                        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
                                        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/ingresos/" class="text-dark text-hover-primary mb-1 font-size-lg">Contabilidad</a>
                                    <span class="text-muted">Ingresos</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'e' || $name == 'eg' || $name == 'egr' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/egresos/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
                                        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
                                        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/egressos/" class="text-dark text-hover-primary mb-1 font-size-lg">Contabilidad</a>
                                    <span class="text-muted">Egresos</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'p' || $name == 'pl' || $name == 'pla' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/planillas/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
                                        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
                                        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/planillas/" class="text-dark text-hover-primary mb-1 font-size-lg">Contabilidad</a>
                                    <span class="text-muted">Planillas</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'c' || $name == 'cu' || $name == 'cue' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/cuentas/bancarias/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
                                        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
                                        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/cuentas_bancarias/" class="text-dark text-hover-primary mb-1 font-size-lg">Contabilidad</a>
                                    <span class="text-muted">Cuentas Bancarias</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'c' || $name == 'ca' || $name == 'caj' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/historial_cortes/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
                                        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
                                        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/historial_cortes/" class="text-dark text-hover-primary mb-1 font-size-lg">Contabilidad</a>
                                    <span class="text-muted">Historial de cortes de caja</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                        }

                    if($name == 'f' || $name == 'fe' || $name == 'fel' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/historial_fel/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
                                        <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
                                        <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/historial_fel/" class="text-dark text-hover-primary mb-1 font-size-lg">Contabilidad</a>
                                    <span class="text-muted">Historial FEL/span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                    }
                    if($name == 'r' || $name == 'ro' || $name == 'rol' || $name == 'p' || $name == 'pu' || $name == 'pue' ){
                            $table.='<tr>
                                <td>
                                <a href="'.base_url().'admin/roles/">
                                    <div class="d-flex align-items-center mb-10">
                                <div class="symbol symbol-40 symbol-light-primary mr-5">
                                    <span class="symbol-label">
                                        <i class="flaticon-user-settings"></i>
                                    </span>
                                </div>
                                <div class="d-flex flex-column font-weight-bold">
                                    <a  href="'.base_url().'admin/roles/" class="text-dark text-hover-primary mb-1 font-size-lg">Usuarios</a>
                                    <span class="text-muted">Roles de usuarios</span>
                                </div>
                            </div>
                                </a>
                                </td>
                            </tr> ';
                    }
                    if($name == 'c' || $name == 'co' || $name == 'cod' ){
                        $table.='<tr>
                            <td>
                            <a href="'.base_url().'admin/codigos/">
                                <div class="d-flex align-items-center mb-10">
                            <div class="symbol symbol-40 symbol-light-primary mr-5">
                                <span class="symbol-label">
                                    <i class="fab fa-expeditedssl"></i>
                                </span>
                            </div>
                            <div class="d-flex flex-column font-weight-bold">
                                <a  href="'.base_url().'admin/codigos/" class="text-dark text-hover-primary mb-1 font-size-lg">Usuarios</a>
                                <span class="text-muted">Códigos de autorización</span>
                            </div>
                        </div>
                            </a>
                            </td>
                        </tr> ';
                    }
                    
        }else{
            $table.= '<div class="card-body" style="/* padding: 2rem 2.25rem; */padding-top: 20px;padding-bottom: 20px;">
                <center>
                    <h3>Ningún producto</h3><br>
                    <img src="'.base_url().'uploads/empty.jpg" style="max-width:25%">
                </center>
            </div>';
        }
        
        if($name == 'con' || $name == 'conf' || $name == 'confi' || $name == 'config'|| $name == 'configu' || $name == 'configur' || $name == 'configura'|| $name == 'configurac' || $name == 'configuraci' || $name == 'configuracio' || $name == 'configuracion' ){
            $table.='<tr>
                        <td>
                        <div class="d-flex align-items-center bg-light-info rounded p-5 mb-5">
                        <span class="svg-icon svg-icon-info mr-5">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect opacity="0.200000003" x="0" y="0" width="24" height="24" />
                                <path
                                    d="M4.5,7 L9.5,7 C10.3284271,7 11,7.67157288 11,8.5 C11,9.32842712 10.3284271,10 9.5,10 L4.5,10 C3.67157288,10 3,9.32842712 3,8.5 C3,7.67157288 3.67157288,7 4.5,7 Z M13.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L13.5,18 C12.6715729,18 12,17.3284271 12,16.5 C12,15.6715729 12.6715729,15 13.5,15 Z"
                                    fill="#000000" opacity="0.3" />
                                <path
                                    d="M17,11 C15.3431458,11 14,9.65685425 14,8 C14,6.34314575 15.3431458,5 17,5 C18.6568542,5 20,6.34314575 20,8 C20,9.65685425 18.6568542,11 17,11 Z M6,19 C4.34314575,19 3,17.6568542 3,16 C3,14.3431458 4.34314575,13 6,13 C7.65685425,13 9,14.3431458 9,16 C9,17.6568542 7.65685425,19 6,19 Z"
                                    fill="#000000" />
                            </g>
                        </svg>
                            </span>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <a href="'.base_url().'admin/configuracion/" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Configuración</a>
                            </div>
                        </div>
                        </td>
                    </tr> ';
        }
        if($name == 'suc' || $name == 'sucu' || $name == 'sucur' || $name == 'sucurs' || $name == 'sucursa' || $name == 'sucursal'|| $name == 'sucursale' || $name == 'sucursales' ){
            $table.='<tr>
                        <td>
                        <div class="d-flex align-items-center bg-light-info rounded p-5 mb-5">
                        <span class="svg-icon svg-icon-info mr-5">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <path d="M3.5,3 L5,3 L5,19.5 C5,20.3284271 4.32842712,21 3.5,21 L3.5,21 C2.67157288,21 2,20.3284271 2,19.5 L2,4.5 C2,3.67157288 2.67157288,3 3.5,3 Z" fill="#000000"></path>
                                <path d="M6.99987583,2.99995344 L19.754647,2.99999303 C20.3069317,2.99999474 20.7546456,3.44771138 20.7546439,3.99999613 C20.7546431,4.24703684 20.6631995,4.48533385 20.497938,4.66895776 L17.5,8 L20.4979317,11.3310353 C20.8673908,11.7415453 20.8341123,12.3738351 20.4236023,12.7432941 C20.2399776,12.9085564 20.0016794,13 19.7546376,13 L6.99987583,13 L6.99987583,2.99995344 Z" fill="#000000" opacity="0.3"></path>
                            </g>
                        </svg>
                    </span>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <a href="'.base_url().'admin/sucursales/" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Sucursales</a>
                            </div>
                        </div>
                        </td>
                    </tr> ';
        }
        if($name == 'cal' ||  $name == 'cale' ||  $name == 'calen' ||  $name == 'calend' ||  $name == 'calenda' ||  $name == 'calendar' ||  $name == 'calendari' ||  $name == 'calendario' ){
            $table.='<tr>
                        <td>
                        <div class="d-flex align-items-center bg-light-info rounded p-5 mb-5">
                        <span class="svg-icon svg-icon-info mr-5">
                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"></rect>
                                <rect fill="#000000" x="2" y="5" width="19" height="4" rx="1"></rect>
                                <rect fill="#000000" opacity="0.3" x="2" y="11" width="19" height="10" rx="1"></rect>
                            </g>
                        </svg>
                    </span>
                            </span>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <a href="'.base_url().'admin/calendario/" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Calendario</a>
                            </div>
                        </div>
                        </td>
                    </tr> ';
        }
        if($name == 'not' || $name == 'noti' || $name == 'notif' || $name == 'notifi' || $name == 'notific'|| $name == 'notifica' || $name == 'notificac' || $name == 'notificaci' || $name == 'notificacio' || $name == 'notificacion' || $name == 'notificacione' || $name == 'notificacione'){
            $table.='<tr>
                        <td>
                        <div class="d-flex align-items-center bg-light-info rounded p-5 mb-5">
                        <span class="svg-icon svg-icon-success mr-5">
                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                <span class="svg-icon svg-icon-info svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Design/Component.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M12.7442084,3.27882877 L19.2473374,6.9949025 C19.7146999,7.26196679 20.003129,7.75898194 20.003129,8.29726722 L20.003129,15.7027328 C20.003129,16.2410181 19.7146999,16.7380332 19.2473374,17.0050975 L12.7442084,20.7211712 C12.2830594,20.9846849 11.7169406,20.9846849 11.2557916,20.7211712 L4.75266256,17.0050975 C4.28530007,16.7380332 3.99687097,16.2410181 3.99687097,15.7027328 L3.99687097,8.29726722 C3.99687097,7.75898194 4.28530007,7.26196679 4.75266256,6.9949025 L11.2557916,3.27882877 C11.7169406,3.01531506 12.2830594,3.01531506 12.7442084,3.27882877 Z M12,14.5 C13.3807119,14.5 14.5,13.3807119 14.5,12 C14.5,10.6192881 13.3807119,9.5 12,9.5 C10.6192881,9.5 9.5,10.6192881 9.5,12 C9.5,13.3807119 10.6192881,14.5 12,14.5 Z" fill="#000000"/>
                                </g>
                            </svg><!--end::Svg Icon--></span>
                                </span>
                            </span>
                            <div class="d-flex flex-column flex-grow-1 mr-2">
                                <a href="'.base_url().'admin/notificaciones/" class="font-weight-normal text-dark-75 text-hover-primary font-size-lg mb-1">Notificaciones</a>
                            </div>
                        </div>
                        </td>
                    </tr> ';
        }
        $cantidad = strlen($name);
        if($cantidad > 0){
            if($cantidad >= 3 && $cantidad <=12){
            $client = $this->db->query("SELECT * FROM `client` WHERE status ='1' AND nit like '$name'");
            if($client->num_rows() > 0){
                foreach($client->result_array() as $row){
                    if($row['type']==1){$type = 'Mayorista';}else{$type='Minorista';}
                $table.= '
                <tr>
                <td>
                <a href="javascript:;" onclick="selectClient('.$row['client_id'].')" >
                    <div class="col-sm-12" >
                        <div class="form-group">
                            <label><b>Cliente:</b></label>
                            <div class="d-flex align-items-center">
                            <div class="ml-4">
                                <div class="text-dark-75 font-weight-normal font-size-lg mb-0">'.$this->crud_model->getName('client', $row['client_id']).'-<b class="text-info">'.$type.'</b></div>
                                NIT:<span class="label label-lg font-weight-bold  label-light-info label-inline">'.$row['nit'].'</span>
                            </div>
                        </div>
                        </div>
                    </div>
                </a>
                </td>
                </tr>';
                }
            }
        }
        if($name == 'emp' || $name == 'empl' || $name == 'emple' || $name == 'emplea' || $name == 'emplead' || $name == 'empleado' || $name == 'empleados'){
            $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/empleados/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Design/Layers.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"/>
                                <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"/>
                                <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/empleados/" class="text-dark text-hover-primary mb-1 font-size-lg">Usuarios</a>
                            <span class="text-muted">Empleados</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
        }
        if($name == 'adm' || $name == 'admi'|| $name == 'admis' || $name == 'admist' || $name == 'admistr'|| $name == 'admistra'|| $name == 'admistrad'|| $name == 'admistrado' || $name == 'admistrador'|| $name == 'admistradore'|| $name == 'admistradores' ){
            $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/admins/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Design/Layers.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"/>
                                <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"/>
                                <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/admins/" class="text-dark text-hover-primary mb-1 font-size-lg">Usuarios</a>
                            <span class="text-muted">Administradores</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }

        if($name == 'pro' || $name == 'prov' || $name == 'prove' || $name == 'provee' || $name == 'proveed' || $name == 'proveedo' || $name == 'proveedor' || $name == 'proveedore' || $name == 'proveedores' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/proveedores/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Design/Layers.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"/>
                                <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"/>
                                <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/proveedores/" class="text-dark text-hover-primary mb-1 font-size-lg">Usuarios</a>
                            <span class="text-muted">Proveedores</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'cli' || $name =='clie' || $name =='clien' || $name =='client' || $name =='cliente' || $name =='clientes' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/clientes/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Design/Layers.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24"/>
                                <path d="M12.9336061,16.072447 L19.36,10.9564761 L19.5181585,10.8312381 C20.1676248,10.3169571 20.2772143,9.3735535 19.7629333,8.72408713 C19.6917232,8.63415859 19.6104327,8.55269514 19.5206557,8.48129411 L12.9336854,3.24257445 C12.3871201,2.80788259 11.6128799,2.80788259 11.0663146,3.24257445 L4.47482784,8.48488609 C3.82645598,9.00054628 3.71887192,9.94418071 4.23453211,10.5925526 C4.30500305,10.6811601 4.38527899,10.7615046 4.47382636,10.8320511 L4.63,10.9564761 L11.0659024,16.0730648 C11.6126744,16.5077525 12.3871218,16.5074963 12.9336061,16.072447 Z" fill="#000000" fill-rule="nonzero"/>
                                <path d="M11.0563554,18.6706981 L5.33593024,14.122919 C4.94553994,13.8125559 4.37746707,13.8774308 4.06710397,14.2678211 C4.06471678,14.2708238 4.06234874,14.2738418 4.06,14.2768747 L4.06,14.2768747 C3.75257288,14.6738539 3.82516916,15.244888 4.22214834,15.5523151 C4.22358765,15.5534297 4.2250303,15.55454 4.22647627,15.555646 L11.0872776,20.8031356 C11.6250734,21.2144692 12.371757,21.2145375 12.909628,20.8033023 L19.7677785,15.559828 C20.1693192,15.2528257 20.2459576,14.6784381 19.9389553,14.2768974 C19.9376429,14.2751809 19.9363245,14.2734691 19.935,14.2717619 L19.935,14.2717619 C19.6266937,13.8743807 19.0546209,13.8021712 18.6572397,14.1104775 C18.654352,14.112718 18.6514778,14.1149757 18.6486172,14.1172508 L12.9235044,18.6705218 C12.377022,19.1051477 11.6029199,19.1052208 11.0563554,18.6706981 Z" fill="#000000" opacity="0.3"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/clientes/" class="text-dark text-hover-primary mb-1 font-size-lg">Usuarios</a>
                            <span class="text-muted">Clientes</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }

        if($name == 'inv' || $name == 'ine' || $name == 'inen' || $name == 'inent' || $name == 'inenta' || $name == 'inentar' || $name == 'inentari' || $name == 'inentario' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/inventario/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Commode2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M5.5,2 L18.5,2 C19.3284271,2 20,2.67157288 20,3.5 L20,6.5 C20,7.32842712 19.3284271,8 18.5,8 L5.5,8 C4.67157288,8 4,7.32842712 4,6.5 L4,3.5 C4,2.67157288 4.67157288,2 5.5,2 Z M11,4 C10.4477153,4 10,4.44771525 10,5 C10,5.55228475 10.4477153,6 11,6 L13,6 C13.5522847,6 14,5.55228475 14,5 C14,4.44771525 13.5522847,4 13,4 L11,4 Z" fill="#000000" opacity="0.3"/>
                                    <path d="M5.5,9 L18.5,9 C19.3284271,9 20,9.67157288 20,10.5 L20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 L4,10.5 C4,9.67157288 4.67157288,9 5.5,9 Z M11,11 C10.4477153,11 10,11.4477153 10,12 C10,12.5522847 10.4477153,13 11,13 L13,13 C13.5522847,13 14,12.5522847 14,12 C14,11.4477153 13.5522847,11 13,11 L11,11 Z M5.5,16 L18.5,16 C19.3284271,16 20,16.6715729 20,17.5 L20,20.5 C20,21.3284271 19.3284271,22 18.5,22 L5.5,22 C4.67157288,22 4,21.3284271 4,20.5 L4,17.5 C4,16.6715729 4.67157288,16 5.5,16 Z M11,18 C10.4477153,18 10,18.4477153 10,19 C10,19.5522847 10.4477153,20 11,20 L13,20 C13.5522847,20 14,19.5522847 14,19 C14,18.4477153 13.5522847,18 13,18 L11,18 Z" fill="#000000"/>
                                </g>
                                </svg></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/inventario/" class="text-dark text-hover-primary mb-1 font-size-lg">Productos</a>
                            <span class="text-muted">Inventario</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'tra' || $name == 'tras' || $name == 'trasl' || $name == 'trasla' || $name == 'traslad' || $name == 'traslado' || $name == 'traslados'  ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/traslados/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Commode2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M5.5,2 L18.5,2 C19.3284271,2 20,2.67157288 20,3.5 L20,6.5 C20,7.32842712 19.3284271,8 18.5,8 L5.5,8 C4.67157288,8 4,7.32842712 4,6.5 L4,3.5 C4,2.67157288 4.67157288,2 5.5,2 Z M11,4 C10.4477153,4 10,4.44771525 10,5 C10,5.55228475 10.4477153,6 11,6 L13,6 C13.5522847,6 14,5.55228475 14,5 C14,4.44771525 13.5522847,4 13,4 L11,4 Z" fill="#000000" opacity="0.3"/>
                                    <path d="M5.5,9 L18.5,9 C19.3284271,9 20,9.67157288 20,10.5 L20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 L4,10.5 C4,9.67157288 4.67157288,9 5.5,9 Z M11,11 C10.4477153,11 10,11.4477153 10,12 C10,12.5522847 10.4477153,13 11,13 L13,13 C13.5522847,13 14,12.5522847 14,12 C14,11.4477153 13.5522847,11 13,11 L11,11 Z M5.5,16 L18.5,16 C19.3284271,16 20,16.6715729 20,17.5 L20,20.5 C20,21.3284271 19.3284271,22 18.5,22 L5.5,22 C4.67157288,22 4,21.3284271 4,20.5 L4,17.5 C4,16.6715729 4.67157288,16 5.5,16 Z M11,18 C10.4477153,18 10,18.4477153 10,19 C10,19.5522847 10.4477153,20 11,20 L13,20 C13.5522847,20 14,19.5522847 14,19 C14,18.4477153 13.5522847,18 13,18 L11,18 Z" fill="#000000"/>
                                </g>
                                </svg></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/traslados/" class="text-dark text-hover-primary mb-1 font-size-lg">Productos</a>
                            <span class="text-muted">Traslados</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }

        if($name == 'cat' || $name == 'cate' || $name == 'categ' || $name == 'catego' || $name == 'categor' || $name == 'categori' || $name == 'categoria' || $name == 'categorias' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/categorias/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Commode2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M5.5,2 L18.5,2 C19.3284271,2 20,2.67157288 20,3.5 L20,6.5 C20,7.32842712 19.3284271,8 18.5,8 L5.5,8 C4.67157288,8 4,7.32842712 4,6.5 L4,3.5 C4,2.67157288 4.67157288,2 5.5,2 Z M11,4 C10.4477153,4 10,4.44771525 10,5 C10,5.55228475 10.4477153,6 11,6 L13,6 C13.5522847,6 14,5.55228475 14,5 C14,4.44771525 13.5522847,4 13,4 L11,4 Z" fill="#000000" opacity="0.3"/>
                                    <path d="M5.5,9 L18.5,9 C19.3284271,9 20,9.67157288 20,10.5 L20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 L4,10.5 C4,9.67157288 4.67157288,9 5.5,9 Z M11,11 C10.4477153,11 10,11.4477153 10,12 C10,12.5522847 10.4477153,13 11,13 L13,13 C13.5522847,13 14,12.5522847 14,12 C14,11.4477153 13.5522847,11 13,11 L11,11 Z M5.5,16 L18.5,16 C19.3284271,16 20,16.6715729 20,17.5 L20,20.5 C20,21.3284271 19.3284271,22 18.5,22 L5.5,22 C4.67157288,22 4,21.3284271 4,20.5 L4,17.5 C4,16.6715729 4.67157288,16 5.5,16 Z M11,18 C10.4477153,18 10,18.4477153 10,19 C10,19.5522847 10.4477153,20 11,20 L13,20 C13.5522847,20 14,19.5522847 14,19 C14,18.4477153 13.5522847,18 13,18 L11,18 Z" fill="#000000"/>
                                </g>
                                </svg></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/categorias/" class="text-dark text-hover-primary mb-1 font-size-lg">Productos</a>
                            <span class="text-muted">Categorias</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'mar' || $name == 'marc' || $name == 'marca' || $name == 'marcas' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/marcas/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                                <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Commode2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M5.5,2 L18.5,2 C19.3284271,2 20,2.67157288 20,3.5 L20,6.5 C20,7.32842712 19.3284271,8 18.5,8 L5.5,8 C4.67157288,8 4,7.32842712 4,6.5 L4,3.5 C4,2.67157288 4.67157288,2 5.5,2 Z M11,4 C10.4477153,4 10,4.44771525 10,5 C10,5.55228475 10.4477153,6 11,6 L13,6 C13.5522847,6 14,5.55228475 14,5 C14,4.44771525 13.5522847,4 13,4 L11,4 Z" fill="#000000" opacity="0.3"/>
                                    <path d="M5.5,9 L18.5,9 C19.3284271,9 20,9.67157288 20,10.5 L20,13.5 C20,14.3284271 19.3284271,15 18.5,15 L5.5,15 C4.67157288,15 4,14.3284271 4,13.5 L4,10.5 C4,9.67157288 4.67157288,9 5.5,9 Z M11,11 C10.4477153,11 10,11.4477153 10,12 C10,12.5522847 10.4477153,13 11,13 L13,13 C13.5522847,13 14,12.5522847 14,12 C14,11.4477153 13.5522847,11 13,11 L11,11 Z M5.5,16 L18.5,16 C19.3284271,16 20,16.6715729 20,17.5 L20,20.5 C20,21.3284271 19.3284271,22 18.5,22 L5.5,22 C4.67157288,22 4,21.3284271 4,20.5 L4,17.5 C4,16.6715729 4.67157288,16 5.5,16 Z M11,18 C10.4477153,18 10,18.4477153 10,19 C10,19.5522847 10.4477153,20 11,20 L13,20 C13.5522847,20 14,19.5522847 14,19 C14,18.4477153 13.5522847,18 13,18 L11,18 Z" fill="#000000"/>
                                </g>
                                </svg></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/marcas/" class="text-dark text-hover-primary mb-1 font-size-lg">Productos</a>
                            <span class="text-muted">Marcas</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'ven' || $name == 'vent' || $name == 'venta' || $name == 'ventas' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/ventas/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/ventas/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                            <span class="text-muted">Ventas</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'com' || $name == 'comp' || $name == 'compr' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/compras/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/compras/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                            <span class="text-muted">Compras</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }

        if($name == 'cot' || $name == 'coti' || $name == 'cotiz' ||  $name == 'cotiza' ||  $name == 'cotizac' ||  $name == 'cotizaci' ||  $name == 'cotizacio' ||  $name == 'cotizacion' ||  $name == 'cotizacione' ||  $name == 'cotizaciones' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/cotizaciones/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/cotizaciones/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                            <span class="text-muted">Cotizaciones</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'cre' || $name == 'cred' || $name == 'credi' ||  $name == 'credit' ||  $name == 'credito' ||  $name == 'creditos' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/creditos/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/creditos/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                            <span class="text-muted">Creditos</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'anu' || $name == 'anul'|| $name == 'anul'|| $name == 'anula' || $name == 'anulac' || $name == 'anulaci' || $name == 'anulacio' || $name == 'anulacion' || $name == 'anulacione' || $name == 'anulaciones' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/anulaciones/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/anulaciones/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                            <span class="text-muted">Anulaciones</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'cam' || $name == 'camb' || $name == 'cambi' || $name == 'cambio' || $name == 'cambios' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/cambios/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/cambios/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                            <span class="text-muted">Cambios</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'env' || $name == 'envi' || $name == 'envio' || $name == 'envios' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/envios/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/envios/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                            <span class="text-muted">Envios</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'tra' || $name == 'tras' || $name == 'trasp' || $name == 'traspo' || $name == 'traspor' || $name == 'trasport' || $name == 'trasporte' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/transporte/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Cooking/Cutting board.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <path d="M8.37867966,15.1213203 C9.35499039,16.0976311 9.35499039,17.6805435 8.37867966,18.6568542 L6.25735931,20.7781746 C5.28104858,21.7544853 3.69813614,21.7544853 2.72182541,20.7781746 C1.74551468,19.8018639 1.74551468,18.2189514 2.72182541,17.2426407 L4.84314575,15.1213203 C5.81945648,14.1450096 7.40236893,14.1450096 8.37867966,15.1213203 Z M3.81784105,19.7528699 C4.30599642,20.2410253 5.09745264,20.2410253 5.58560801,19.7528699 C6.07376337,19.2647145 6.07376337,18.4732583 5.58560801,17.9851029 C5.09745264,17.4969476 4.30599642,17.4969476 3.81784105,17.9851029 C3.32968569,18.4732583 3.32968569,19.2647145 3.81784105,19.7528699 Z" fill="#000000" opacity="0.3"/>
                                <path d="M14.3890873,1.33273811 L22.1672619,9.1109127 C22.9483105,9.89196129 22.9483105,11.1582912 22.1672619,11.9393398 L12.9748737,21.131728 C12.1938252,21.9127766 10.9274952,21.9127766 10.1464466,21.131728 L2.36827202,13.3535534 C1.58722343,12.5725048 1.58722343,11.3061748 2.36827202,10.5251263 L11.5606602,1.33273811 C12.3417088,0.551689527 13.6080387,0.551689527 14.3890873,1.33273811 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/transporte/" class="text-dark text-hover-primary mb-1 font-size-lg">Herramientas</a>
                            <span class="text-muted">Transporte</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'gen' || $name == 'gene' || $name == 'gener' || $name == 'genera' || $name == 'general' || $name == 'generale' || $name == 'generales' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/reportes/generales/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/reportes/generales/" class="text-dark text-hover-primary mb-1 font-size-lg">Reportes</a>
                            <span class="text-muted">Generales</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'ven' || $name == 'ven' || $name == 'venta' || $name == 'ventas' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/reportes/ventas/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/reportes/ventas/" class="text-dark text-hover-primary mb-1 font-size-lg">Reportes</a>
                            <span class="text-muted">Ventas</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'com' || $name == 'comp' || $name == 'compr' || $name == 'compra' || $name == 'compras'  ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/reportes/compras/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/reportes/compras/" class="text-dark text-hover-primary mb-1 font-size-lg">Reportes</a>
                            <span class="text-muted">Compras</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'cam' || $name == 'camb' || $name == 'cambi' || $name == 'cambio' || $name == 'cambios'){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/reportes/cambios/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/reportes/cambios/" class="text-dark text-hover-primary mb-1 font-size-lg">Reportes</a>
                            <span class="text-muted">Cambios</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
                if( $name == 'anu' || $name == 'anul' || $name == 'anula' || $name == 'anulac' || $name == 'anulaci' || $name == 'anulacio' || $name == 'anulacion' || $name == 'anulacione' || $name == 'anulaciones' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/reportes/anulaciones/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/reportes/anulaciones/" class="text-dark text-hover-primary mb-1 font-size-lg">Reportes</a>
                            <span class="text-muted">Anulaciones</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'fin' || $name == 'fina' || $name == 'finan' || $name == 'financ' || $name == 'financi' || $name == 'financie' || $name == 'financier' || $name == 'financiero'  ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/reportes_financieros/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/reportes_financieros/" class="text-dark text-hover-primary mb-1 font-size-lg">Reportes</a>
                            <span class="text-muted">Fianciero</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'act' || $name == 'acti' || $name == 'activ'|| $name == 'activi' || $name == 'activid'|| $name == 'activida' || $name == 'actividad' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/reportes/actividad/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Chart-line1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M8.7295372,14.6839411 C8.35180695,15.0868534 7.71897114,15.1072675 7.31605887,14.7295372 C6.9131466,14.3518069 6.89273254,13.7189711 7.2704628,13.3160589 L11.0204628,9.31605887 C11.3857725,8.92639521 11.9928179,8.89260288 12.3991193,9.23931335 L15.358855,11.7649545 L19.2151172,6.88035571 C19.5573373,6.44687693 20.1861655,6.37289714 20.6196443,6.71511723 C21.0531231,7.05733733 21.1271029,7.68616551 20.7848828,8.11964429 L16.2848828,13.8196443 C15.9333973,14.2648593 15.2823707,14.3288915 14.8508807,13.9606866 L11.8268294,11.3801628 L8.7295372,14.6839411 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/reportes/actividad/" class="text-dark text-hover-primary mb-1 font-size-lg">Reportes</a>
                            <span class="text-muted">Actividad</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'ing' || $name == 'ingr' || $name == 'incgre' || $name == 'incgres'|| $name == 'incgreso' || $name == 'incgresos' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/ingresos/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
                                <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/ingresos/" class="text-dark text-hover-primary mb-1 font-size-lg">Contabilidad</a>
                            <span class="text-muted">Ingresos</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'egre' || $name == 'egres' || $name == 'egreso' || $name == 'egresos'  ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/egresos/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
                                <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/egressos/" class="text-dark text-hover-primary mb-1 font-size-lg">Contabilidad</a>
                            <span class="text-muted">Egresos</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'pla' || $name == 'plan' || $name == 'plani' || $name == 'planil' || $name == 'planill' || $name == 'planilla' || $name == 'planillas' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/planillas/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
                                <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/planillas/" class="text-dark text-hover-primary mb-1 font-size-lg">Contabilidad</a>
                            <span class="text-muted">Planillas</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'cue' || $name == 'cuen' || $name == 'cuent' || $name == 'cuenta' || $name == 'cuentas'  ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/cuentas/bancarias/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
                                <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/cuentas_bancarias/" class="text-dark text-hover-primary mb-1 font-size-lg">Contabilidad</a>
                            <span class="text-muted">Cuentas Bancarias</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }

        if($name == 'c' || $name == 'ca' || $name == 'caj'|| $name == 'caja'  ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/historial_cortes/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
                                <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/historial_cortes/" class="text-dark text-hover-primary mb-1 font-size-lg">Contabilidad</a>
                            <span class="text-muted">Historial de cortes de caja</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
                }
        if($name == 'f' || $name == 'fe' || $name == 'fel' ){
                    $table.='<tr>
                        <td>
                        <a href="'.base_url().'admin/historial_fel/">
                            <div class="d-flex align-items-center mb-10">
                        <div class="symbol symbol-40 symbol-light-primary mr-5">
                            <span class="symbol-label">
                            <span class="svg-icon svg-icon-primary svg-icon-2x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Shopping/Wallet.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24"/>
                                <circle fill="#000000" opacity="0.3" cx="20.5" cy="12.5" r="1.5"/>
                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 6.500000) rotate(-15.000000) translate(-12.000000, -6.500000) " x="3" y="3" width="18" height="7" rx="1"/>
                                <path d="M22,9.33681558 C21.5453723,9.12084552 21.0367986,9 20.5,9 C18.5670034,9 17,10.5670034 17,12.5 C17,14.4329966 18.5670034,16 20.5,16 C21.0367986,16 21.5453723,15.8791545 22,15.6631844 L22,18 C22,19.1045695 21.1045695,20 20,20 L4,20 C2.8954305,20 2,19.1045695 2,18 L2,6 C2,4.8954305 2.8954305,4 4,4 L20,4 C21.1045695,4 22,4.8954305 22,6 L22,9.33681558 Z" fill="#000000"/>
                            </g>
                        </svg><!--end::Svg Icon--></span>
                            </span>
                        </div>
                        <div class="d-flex flex-column font-weight-bold">
                            <a  href="'.base_url().'admin/historial_fel/" class="text-dark text-hover-primary mb-1 font-size-lg">Contabilidad</a>
                            <span class="text-muted">Historial FEL</span>
                        </div>
                    </div>
                        </a>
                        </td>
                    </tr> ';
        }
        if($name == 'rol' || $name == 'role' || $name == 'roles' || $name == 'pue' || $name == 'pues' || $name == 'puest' || $name == 'puesto' || $name == 'puestos' ){
            $table.='<tr>
                <td>
                <a href="'.base_url().'admin/roles/">
                    <div class="d-flex align-items-center mb-10">
                <div class="symbol symbol-40 symbol-light-primary mr-5">
                    <span class="symbol-label">
                        <i class="flaticon-user-settings"></i>
                    </span>
                </div>
                <div class="d-flex flex-column font-weight-bold">
                    <a  href="'.base_url().'admin/roles/" class="text-dark text-hover-primary mb-1 font-size-lg">Usuarios</a>
                    <span class="text-muted">Roles de usuarios</span>
                </div>
            </div>
                </a>
                </td>
            </tr> ';
        }
        if($name == 'cod' || $name == 'codi'|| $name == 'codig'|| $name == 'codigo' || $name == 'codigos' ){
            $table.='<tr>
                <td>
                <a href="'.base_url().'admin/codigos/">
                    <div class="d-flex align-items-center mb-10">
                <div class="symbol symbol-40 symbol-light-primary mr-5">
                    <span class="symbol-label">
                        <i class="fab fa-expeditedssl"></i>
                    </span>
                </div>
                <div class="d-flex flex-column font-weight-bold">
                    <a  href="'.base_url().'admin/codigos/" class="text-dark text-hover-primary mb-1 font-size-lg">Usuarios</a>
                    <span class="text-muted">Códigos de autorización</span>
                </div>
            </div>
                </a>
                </td>
            </tr> ';
        }

    }
        else{
            $table.= '<div class="card-body" style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
                <center>
                    <h3>Sin datos</h3><br>
                    <img src="'.base_url().'uploads/empty.jpg" style="max-width:25%">
                </center>
            </div>';
        }
    }
    echo $table;
    }

    if($param1 == 'clients_co'){
        $nit = $this->input->post('nit');
        if($nit != 'c/f' && $nit != 'C/F' && $nit != ''){
        $data = $this->db->query("SELECT * FROM `client` WHERE status ='1' AND nit like '$nit'");
        if($data->num_rows() > 0){
            foreach($data->result_array() as $row){
                if($row['type']==1){$type = 'Mayorista';}else{$type='Minorista';}
            $table.= '
            <a href="javascript:;" onclick="selectClient('.$row['client_id'].')" >
                <div class="col-sm-12" >
                    <div class="form-group">
                        <label><b>Cliente:</b></label>
                        <div class="d-flex align-items-center">
                        <div class="ml-4">
                            <div class="text-dark-75 font-weight-normal font-size-lg mb-0">'.$this->crud_model->getName('client', $row['client_id']).'-<b class="text-info">'.$type.'</b></div>
                            NIT:<span class="label label-lg font-weight-bold  label-light-info label-inline">'.$row['nit'].'</span>
                        </div>
                    </div>
                    </div>
                </div>
            </a>
                <input type="hidden" name="client_nit" id="client_nit_'.$row['client_id'].'" value="'.$row['nit'].'" >
                <input type="hidden" name="client" value="'.$row['client_id'].'">';
            }
            echo $table;
        }else{
            echo $table='';
        }
    }
}

if($param1 == 'search_productos'){
    $provider = $this->input->post('provider');
    $branch_id = $this->session->userdata('branch_id');
    $data = $this->db->order_by('name', 'ASC')->get_where('products', array('provider'=>$provider, 'status'=>1));
    $select = ' <option value="">Seleccionar</option> ';
    foreach($data->result_array() as $row){
    $select.= ' <option value="'.$row['name'].'">'.$row['name'].'</option> ';
}
echo $select; 
}
if($param1 == 'product_solicitud'){
    $name = $this->input->post('name');
    $branch_id = $this->session->userdata('branch_id');

    if($name != ''){
    $data = $this->db->query("SELECT * FROM `products` WHERE status ='1' AND (name LIKE '%$name%' OR code LIKE '%$name%') ORDER BY name");

    if($data->num_rows()>0){

        foreach($data->result_array() as $row){
            $id_product = $row['products_id'];
            if ($row['presentation'] == 'Caja') {
                $id_product = $row['id_prod_matriz'];
                $stock_inventario = 0; $stock_bodega = 0;
                if ($row['cnt_prod_matriz'] > 0) {
                    $stock_inventario = $this->crud_model->get_stock($id_product, $branch_id) / $row['cnt_prod_matriz'];
                    $stock_bodega = $this->crud_model->get_stock($id_product, 0) / $row['cnt_prod_matriz'];
                }
            } else {
                $stock_inventario = $this->crud_model->get_stock($row['products_id'], $branch_id);
                $stock_bodega = $this->crud_model->get_stock($row['products_id'], 0);
            }
            $table.= '<tr>
                <td>
                    <a href="javascript:;" onclick="addOption('.$row['products_id'].')">
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-50 symbol-sm flex-shrink-0">
                                <img class="h-75 align-self-end" src="';
                                                    
                                                        if($row['img']!=''){ $table.=base_url().'uploads/productos/'.$row['img'];} else { $table.=base_url().'uploads/productos/default_product.png'; } 
                                                        $table.='" alt="photo">
                            </div>
                            <div class="ml-4">
                                <div class="text-dark-75 font-weight-normal font-size-lg mb-0">
                                    '.$row['name'].'- <b class="text-danger">['.$this->db->get_where('mark', array('mark_id'=>$row['mark']))->row()->name.']</b></div>
                                <a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">Inventario:
                                    <span class="label label-lg font-weight-bold  label-light-info label-inline">'.$stock_inventario.'</span></a>
                                <a href="javascript:void(0);" class="text-muted font-weight-bold text-hover-primary">Bodega:
                                    <span class="label label-lg font-weight-bold  label-light-info label-inline">'.$stock_bodega.'</span></a>
                                
                            </div>
                        </div>
                    </a>
                </td>
            </tr>';
            
        }

    }else{
        $table.= '<div class="card-body" style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
                <center>
                    <h3>Sin datos</h3><br>
                    <img src="'.base_url().'uploads/empty.jpg" style="max-width:25%">
                </center>
            </div>';
    }

    }
    echo $table;
    }
    if($param1 == 'search_email'){
        $ID = $this->input->post('ID');
        $table_id = $param2.'_id';
        $email = $this->input->post('email');
        $email_consul = $this->db->get_where($param2, array($table_id=>$ID));
        $email_admin = $this->db->get_where('admin', array('email'=>$this->input->post('email'), 'admin_id !='=>$ID ));
        $email_client = $this->db->get_where('client', array('email'=>$this->input->post('email'), 'client_id !='=>$ID ));
        $email_prov = $this->db->get_where('provider', array('email'=>$this->input->post('email'), 'provider_id !='=>$ID ));
        
        if($email_consul->row()->email == $email){
            $res = '1';
        }else{
            if($email_admin->num_rows() > 0 || $email_client->num_rows() > 0 || $email_prov->num_rows() > 0){
                $res = '0';
            }else{
                $res ='2';
            }
        }
        echo $res; 
    }

}

function getTable($table = '' ,$param1 = '' ,$param2 = '' ,$param3 = '')
{
    return $this->tables_model->getTables($table,$param1,$param2,$param3);   
}

function getCui($cui){
    $token = $this->db->get_where('settings',array('type'=>'token_cui'))->row()->description;
    $token = 'Authorization: Bearer '.$token;
    log_message('error','token: '.$token);
    $curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://certificador.feel.com.gt/api/v2/servicios/externos/cui',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => array('cui' => $cui),
  CURLOPT_HTTPHEADER => array(
    $token
  ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;
log_message('error','datos: '.json_encode($response));
 $respuesta = json_decode($response);
    if($respuesta->descripcion == 'Token no valido o expirado, por favor solicite un nuevo token' ){
        //log_message('error','entro a nuevo token');
        $this->getToken($cui);
    }else{
        if($respuesta->resultado==true){
        $str = explode(',,',$respuesta->cui->nombre);
            $cn = count($str);
            if($cn == 2){
                $str = explode(',,',$respuesta->cui->nombre);
            }elseif($cn == 1){
                $str = explode(',',$respuesta->cui->nombre);
            }else{
                $str = $respuesta->cui->nombre;
            }
            echo json_encode($str) ;
    }else{
        $response = 'NIT o CUI no válido';
        echo json_encode($response);
    }
    }
    
}

function getToken($cui){
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://certificador.feel.com.gt/api/v2/servicios/externos/login',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('prefijo' => 'FERRECENTRO','llave' => 'C03FF92A1ECC8DB63226EB41334970BB'),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    log_message('error','datos Nuevo token: '.json_encode($response));
    $respuesta = json_decode($response);
    if($respuesta->resultado==true){
        $token_nuevo = $respuesta->token;
        $data_tkn['description'] =$token_nuevo;
        $this->db->where('type','token_cui');
        $this->db->update('settings',$data_tkn);
        $this->getCui($cui);
    }
}

function getNit(){
    $nit = $this->input->post('nit');
    
    if(strlen($nit)>9){
        $this->getCui($nit);
    }else{
        $emisor = $this->db->get_where('settings',array('type'=>'usuario_api_prod'))->row()->description;
        $emisor_clave = $this->db->get_where('settings',array('type'=>'llave_api_prod'))->row()->description;
        $curl = curl_init();
        
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://consultareceptores.feel.com.gt/rest/action',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>'{
            "emisor_codigo": "FERRECENTRO",
            "emisor_clave" : "C03FF92A1ECC8DB63226EB41334970BB",
            "nit_consulta": "'.$nit.'"
        }',
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        $respuesta = json_decode($response);
        if($respuesta->mensaje!='NIT no válido'){
            $str = explode(',,',$respuesta->nombre);
                $cn = count($str);
                if($cn == 2){
                    $str = explode(',,',$respuesta->nombre);
                }elseif($cn == 1){
                    $str = explode(',',$respuesta->nombre);
                }else{
                    $str = $respuesta->nombre;
                }
                echo json_encode($str) ;
        }else{
            $response = 'NIT o CUI no válido';
        echo json_encode($response);
        }
    }
   
}

/*function validarXML(){
    $name = $this->input->post('name');
    $nit = $this->input->post('nit');
    $address = $this->input->post('address');
    $productos = $this->input->post('productos');
    $total = $this->input->post('total');
    $num = $this->input->post('num');
    
    $code = $this->input->post('code');
    $jwt = $this->db->get_where('settings', array('type'=>'jwt'))->row()->description;

    $nombreComercial = $this->db->get_where('settings', array('type'=>'nombreComercial'))->row()->description;
    $emisorDireccion = $this->db->get_where('settings', array('type'=>'direccionemisor'))->row()->description;
    $emisorCodigoPostal = $this->db->get_where('settings', array('type'=>'codigoPostal'))->row()->description;
    $emisorMunicipio = $this->db->get_where('settings', array('type'=>'municipio'))->row()->description;
    $emisorDepartamento = $this->db->get_where('settings', array('type'=>'departamento'))->row()->description;

    $rg=$this->db->get_where('settings',array('type'=>'regimen'))->row()->description;
    if($rg == 12){
        $afiliacion = 'GEN';
    }else{
        $afiliacion = 'PEC';
    }
    
    $xml= '<?xml version="1.0" encoding="utf-8"?>
<dte:GTDocumento xmlns:cex="http://www.sat.gob.gt/face2/ComplementoExportaciones/0.1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:cfe="http://www.sat.gob.gt/face2/ComplementoFacturaEspecial/0.1.0" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:cfc="http://www.sat.gob.gt/dte/fel/CompCambiaria/0.1.0" xmlns:cno="http://www.sat.gob.gt/face2/ComplementoReferenciaNota/0.1.0" Version="0.1" xmlns:dte="http://www.sat.gob.gt/dte/fel/0.2.0">
    <dte:SAT ClaseDocumento="dte">
        <dte:DTE ID="DatosCertificados">
            <dte:DatosEmision ID="DatosEmision">
                <dte:DatosGenerales Tipo="FACT" FechaHoraEmision="'.date('Y').'-'.date('m').'-'.date('d').'T'.date('H').':'.date('i').':'.date('s').'" CodigoMoneda="GTQ" />
                <dte:Emisor NITEmisor="'.$this->db->get_where('settings', array('type'=>'nit'))->row()->description.'" NombreEmisor="'.$this->db->get_where('settings', array('type'=>'name_fel'))->row()->description.'" CodigoEstablecimiento="4" NombreComercial="'.$nombreComercial.'" AfiliacionIVA="'.$afiliacion.'">
                    <dte:DireccionEmisor>
                        <dte:Direccion>'.$emisorDireccion.'</dte:Direccion>
                        <dte:CodigoPostal>'.$emisorCodigoPostal.'</dte:CodigoPostal>
                        <dte:Municipio>'.$emisorMunicipio.'</dte:Municipio>
                        <dte:Departamento>'.$emisorDepartamento.'</dte:Departamento>
                        <dte:Pais>GT</dte:Pais>
                    </dte:DireccionEmisor>
                </dte:Emisor>
                <dte:Receptor IDReceptor="'.$nit.'" NombreReceptor="'.$name.'">
                    <dte:DireccionReceptor>
                        <dte:Direccion>'.$address.'</dte:Direccion>
                        <dte:CodigoPostal>01000</dte:CodigoPostal>
                        <dte:Municipio>Guatemala</dte:Municipio>
                        <dte:Departamento>Guatemala</dte:Departamento>
                        <dte:Pais>GT</dte:Pais>
                    </dte:DireccionReceptor>
                </dte:Receptor>
                <dte:Frases>
                    <dte:Frase TipoFrase="1" CodigoEscenario="1" />
                </dte:Frases>
                <dte:Items>';
                    $totalImpuesto =0;
                    $GranTotal =0;
                    $n=1; for ($i=0; $i < $num ; $i++) { $regimen=$this->db->get_where('settings',array('type'=>'regimen'))->row()->description / 100;
                        $totalp = $productos[$i]["price"]*$productos[$i]["amount"];
                        $montoGravable = number_format($totalp/($regimen + 1),6,".","");
                        $montoImpuesto = number_format($montoGravable*$regimen,6,".","");
                        $totalImpuesto += $montoImpuesto;
                        $GranTotal += $montoGravable + $montoImpuesto;
                        $xml.='
                        <dte:Item NumeroLinea="'.$n++.'" BienOServicio="B">
                            <dte:Cantidad>'.$productos[$i]["amount"].'</dte:Cantidad>
                            <dte:UnidadMedida>UNI</dte:UnidadMedida>
                            <dte:Descripcion>'.$productos[$i]["producto"].'</dte:Descripcion>
                            <dte:PrecioUnitario>'.number_format($productos[$i]["price"],6,".","").'</dte:PrecioUnitario>
                            <dte:Precio>'.number_format($totalp,6,".","").'</dte:Precio>
                            <dte:Descuento>0</dte:Descuento>
                            <dte:Impuestos>
                                <dte:Impuesto>
                                    <dte:NombreCorto>IVA</dte:NombreCorto>
                                    <dte:CodigoUnidadGravable>1</dte:CodigoUnidadGravable>
                                    <dte:MontoGravable>'.$montoGravable.'</dte:MontoGravable>
                                    <dte:MontoImpuesto>'.$montoImpuesto.'</dte:MontoImpuesto>
                                </dte:Impuesto>
                            </dte:Impuestos>
                            <dte:Total>'.number_format($productos[$i]["sbtl"],6,".","").'</dte:Total>
                        </dte:Item>';
                        }
                        $xml.='
                </dte:Items>
                <dte:Totales>
                    <dte:TotalImpuestos>
                        <dte:TotalImpuesto NombreCorto="IVA" TotalMontoImpuesto="'.number_format($totalImpuesto,6,".","").'" />
                    </dte:TotalImpuestos>
                    <dte:GranTotal>'.number_format($GranTotal,6,".","").'</dte:GranTotal>
                </dte:Totales>
            </dte:DatosEmision>
        </dte:DTE>
    </dte:SAT>
</dte:GTDocumento>';

log_message('error',$xml);
log_message('error',urlencode($xml));

$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => 'https://mayansource.com/dte/firmarDTE.php',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_POSTFIELDS => 'document='.urlencode($xml).'&product=msbox',
CURLOPT_HTTPHEADER => array(
'Authorization: Bearer'.' '.$jwt,
'Content-Type: application/x-www-form-urlencoded',
),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err)
{
$data['estado'] = 5;
$data['FEL'] = 2;
$this->db->where('code', $code);
$this->db->update('sales', $data);
echo 'error-fel';
}
else
{
$array = json_decode($response,true);
if($array['response'] == 200){
$str = explode(',',$response);
$res = json_encode($str);
echo $res;
}elseif($array['response'] == 404){
echo 'error-fel';
$data['estado'] = 5;
$data['FEL'] = 2;
$this->db->where('code', $code);
$this->db->update('sales', $data);
}


}


}*/

function validarXML($code){
    $sale_data = $this->db->get_where('sales',array('code'=>$code))->row();
    $name   = $sale_data->name;
    $cui    = $sale_data->cui;
    
    $nit = $sale_data->nit;
    $nit = str_replace(' ', '', $nit);
    $name = str_replace('"', '', $name);
    if($cui!=1){
     //$nit2 = $this->getNit2($nit);    
       if( $nit == 'Cf' || $nit == 'cf'|| $nit == 'cF' || $nit == 'FARMACIA' || $nit == 'farmacia' || $nit == 'MAYORISTA' || strlen($nit)<6){
           $nit = 'CF';
          }
    }
    $cliente = $this->db->get_where('client', array('client_id'=>$sale_data->client_id))->row();
    //echo $nit2;
    
    $address = $sale_data->address;
    if($address == ''||$address == null){
        $address = 'Ciudad';
    }
    $productos = json_decode($sale_data->products,true);
    $total = $sale_data->total;
    $num = $sale_data->num_products;
    
    //$code = $this->input->post('code');
    $jwt = $this->db->get_where('settings', array('type'=>'jwt'))->row()->description;

    $nombreComercial = $this->db->get_where('settings', array('type'=>'nombreComercial'))->row()->description;
    $emisorDireccion = str_replace('<br>','',$this->db->get_where('settings', array('type'=>'direccionemisor'))->row()->description);
    $emisorCodigoPostal = $this->db->get_where('settings', array('type'=>'codigoPostal'))->row()->description;
    $emisorMunicipio = $this->db->get_where('settings', array('type'=>'municipio'))->row()->description;
    $emisorDepartamento = $this->db->get_where('settings', array('type'=>'departamento'))->row()->description;
    $establecimiento = 1;
    if($this->session->userdata('branch_id')==23){
    $establecimiento = 2;   
    $emisorDireccion = str_replace('<br>','',$this->db->get_where('settings', array('type'=>'direccionemisor2'))->row()->description);
    $emisorMunicipio = $this->db->get_where('settings', array('type'=>'municipio2'))->row()->description;
    }
    $rg=$this->db->get_where('settings',array('type'=>'regimen'))->row()->description;
    if($rg == 12){
        $afiliacion = 'GEN';
    }else{
        $afiliacion = 'PEC';
    }
    
     if($cliente->type == 1){
            $type_price = 'price_my';
            $type_sub = 'sub_my';
        }elseif($cliente->type == 2){
            $type_price = 'price';
            $type_sub = 'sub';
        }elseif($cliente->type == 3 ){
            $type_price = 'price_farma';
            $type_sub = 'sub_farma';
        }
        else{
            $type_price = 'price';
            $type_sub = 'sub';
        }
    
$fecha = $sale_data->date; // '2025-08-18'

// separar la fecha y combinar con hora actual
$FechaHoraEmision = date('Y-m-d\TH:i:s', strtotime($fecha . ' ' . date('H:i:s')));

log_message('error','fecha DB:'. $FechaHoraEmision);

    
    $xml= '<?xml version="1.0" encoding="utf-8"?>
<dte:GTDocumento xmlns:cex="http://www.sat.gob.gt/face2/ComplementoExportaciones/0.1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:cfe="http://www.sat.gob.gt/face2/ComplementoFacturaEspecial/0.1.0" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:cfc="http://www.sat.gob.gt/dte/fel/CompCambiaria/0.1.0" xmlns:cno="http://www.sat.gob.gt/face2/ComplementoReferenciaNota/0.1.0" Version="0.1" xmlns:dte="http://www.sat.gob.gt/dte/fel/0.2.0">
    <dte:SAT ClaseDocumento="dte">
        <dte:DTE ID="DatosCertificados">
            <dte:DatosEmision ID="DatosEmision">
                <dte:DatosGenerales Tipo="FACT" FechaHoraEmision="'.$FechaHoraEmision.'" CodigoMoneda="GTQ" />
                <dte:Emisor NITEmisor="'.$this->db->get_where('settings', array('type'=>'nit'))->row()->description.'" NombreEmisor="'.$this->db->get_where('settings', array('type'=>'name_fel'))->row()->description.'" CodigoEstablecimiento="'.$establecimiento.'" NombreComercial="'.$nombreComercial.'" AfiliacionIVA="'.$afiliacion.'">
                    <dte:DireccionEmisor>
                        <dte:Direccion>'.$emisorDireccion.'</dte:Direccion>
                        <dte:CodigoPostal>'.$emisorCodigoPostal.'</dte:CodigoPostal>
                        <dte:Municipio>'.$emisorMunicipio.'</dte:Municipio>
                        <dte:Departamento>'.$emisorDepartamento.'</dte:Departamento>
                        <dte:Pais>GT</dte:Pais>
                    </dte:DireccionEmisor>
                </dte:Emisor>
                 <dte:Receptor IDReceptor="'.$nit.'"';
                if ($cui == 1) $xml .= ' TipoEspecial="CUI" ';
                $xml .= ' NombreReceptor="'.$name.'">
                    <dte:DireccionReceptor>
                        <dte:Direccion>'.$address.'</dte:Direccion>
                        <dte:CodigoPostal>'.$emisorCodigoPostal.'</dte:CodigoPostal>
                        <dte:Municipio>'.$emisorMunicipio.'</dte:Municipio>
                        <dte:Departamento>'.$emisorDepartamento.'</dte:Departamento>
                        <dte:Pais>GT</dte:Pais>
                    </dte:DireccionReceptor>
                </dte:Receptor>
                <dte:Frases>
                    <dte:Frase TipoFrase="1" CodigoEscenario="1" />
                    <dte:Frase CodigoEscenario="1" TipoFrase="2"/>
                    <dte:Frase TipoFrase="4" CodigoEscenario="9" />
                </dte:Frases>
                <dte:Items>';
                    $totalImpuesto =0;
                    $GranTotal =0;
                    $n=1; for ($i=0; $i < $num ; $i++) { 
                        
                        
                        $regimen=$this->db->get_where('settings',array('type'=>'regimen'))->row()->description / 100;
                    $description=$this->db->get_where('products',array('products_id'=>$productos[$i]["product"]))->row();
                        $totalp = $productos[$i][$type_price]*$productos[$i]["amount"];
                        
                        if($description->iva == 1){
                        $montoGravable = number_format($totalp/($regimen + 1),6,".","");
                        $montoImpuesto = number_format($montoGravable*$regimen,6,".","");    
                        $CodigoUnidadGravable= 1;
                        }else{
                            $montoGravable = number_format($totalp,6,".","");
                            $montoImpuesto = 0.00;    
                            $CodigoUnidadGravable= 2;
                        }
                       
                        $totalImpuesto += $montoImpuesto;
                        $GranTotal += $montoGravable + $montoImpuesto;
                        $xml.='
                        <dte:Item NumeroLinea="'.$n++.'" BienOServicio="'.$description->type.'">
                            <dte:Cantidad>'.$productos[$i]["amount"].'</dte:Cantidad>
                            <dte:UnidadMedida>UNI</dte:UnidadMedida>
                            <dte:Descripcion>'.$description->name.'</dte:Descripcion>
                            <dte:PrecioUnitario>'.number_format($productos[$i][$type_price],6,".","").'</dte:PrecioUnitario>
                            <dte:Precio>'.number_format($totalp,6,".","").'</dte:Precio>
                            <dte:Descuento>0</dte:Descuento>
                            <dte:Impuestos>
                                <dte:Impuesto>
                                    <dte:NombreCorto>IVA</dte:NombreCorto>
                                    <dte:CodigoUnidadGravable>'.$CodigoUnidadGravable.'</dte:CodigoUnidadGravable>
                                    <dte:MontoGravable>'.$montoGravable.'</dte:MontoGravable>
                                    <dte:MontoImpuesto>'.$montoImpuesto.'</dte:MontoImpuesto>
                                </dte:Impuesto>
                            </dte:Impuestos>
                            <dte:Total>'.number_format($productos[$i][$type_sub],6,".","").'</dte:Total>
                        </dte:Item>';
                        }
                        $xml.='
                </dte:Items>
                <dte:Totales>
                    <dte:TotalImpuestos>
                        <dte:TotalImpuesto NombreCorto="IVA" TotalMontoImpuesto="'.number_format($totalImpuesto,6,".","").'" />
                    </dte:TotalImpuestos>
                    <dte:GranTotal>'.number_format($GranTotal,6,".","").'</dte:GranTotal>
                </dte:Totales>
            </dte:DatosEmision>
        </dte:DTE>
    </dte:SAT>
</dte:GTDocumento>';

$fel = $this->crud_model->getInfo("FEL");
//log_message('error','fell: '.$fel);
        if ($fel) {
            $url           = $this->crud_model->getInfo("url_fel_prod");
            $usuario_api   = $this->crud_model->getInfo("usuario_api_prod"); 
            $llave_api     = $this->crud_model->getInfo("llave_api_prod");
            $usuario_firma = $this->crud_model->getInfo("usuario_firma_prod"); 
            $correlativo   = $this->crud_model->getInfo("identificador_demo");
            $llave_firma   = $this->crud_model->getInfo("llave_firma_prod");
            $identificador = "FEL-".sprintf('%04d', $correlativo);

            /*$new_correl = $correlativo++;
            $this->db->where('type', 'identificador_demo');
            $this->db->update('settings', array('description'=>$new_correl));*/
        } else {
            $url           = $this->crud_model->getInfo("url_fel_demo");
            $usuario_api   = $this->crud_model->getInfo("usuario_api_demo"); 
            $llave_api     = $this->crud_model->getInfo("llave_api_demo");
            $usuario_firma = $this->crud_model->getInfo("usuario_firma_demo"); 
            $correlativo   = $this->crud_model->getInfo("identificador_demo");
            $llave_firma   = $this->crud_model->getInfo("llave_firma_demo");
            $identificador = "PRUEBA-".sprintf('%04d', $correlativo);

            /*$new_correl = $correlativo++;
            $this->db->where('type', 'identificador_demo');
            $this->db->update('settings', array('description'=>$new_correl));*/
        }

        $data = array(
            'URL: '.$url,
            'UsuarioAPI: '.$usuario_api,
            'LlaveAPI: '.$llave_api,
            'UsuarioFirma: '.$usuario_firma,
            'Identificador: '.$identificador,
            'LlaveFirma: '.$llave_firma
        );
        log_message("error", "Data: ".json_encode($data));

log_message('error',$xml);
log_message('error',urlencode($xml));
$curl = curl_init();
try {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $xml,
                CURLOPT_HTTPHEADER => array(
                    'UsuarioAPI: '.$usuario_api,
                    'LlaveAPI: '.$llave_api,
                    'UsuarioFirma: '.$usuario_firma,
                    'Identificador: '.$identificador,
                    'LlaveFirma: '.$llave_firma,
                    'Content-Type: text/plain'
                ),
            ));
    
            $response = curl_exec($curl);
    log_message('error',$response);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err)
    {
        $data_fac['estado'] = 6;
        $data_fac['FEL'] = 2;
        $this->db->where('code', $code);
        $this->db->update('sales', $data_fac);
        echo 'error-fel';   
    }
    else
    {
        $respuesta = json_decode($response);
        //log_message('error','array'.print_r($respuesta));
        if($respuesta->resultado)
        {
            log_message('error','fecha-emision-dte'.$respuesta->fecha-emision-dte);
            log_message('error','fecha-emision'.$respuesta->fecha-emision);
            $str = explode(',',$response);
            $res = json_encode($str);
            $data_fac['xml_fel']    = $respuesta->xml_certificado;
            $data_fac['facturado']  = 1;
            $data_fac['FEL']  = 1;
            $data_fac['estado'] = 2;
            $data_fac['code_fel']   = $respuesta->uuid;
            $data_fac['date_fel']   = $respuesta->fecha;
            $data_fac['serie_fel']  = $respuesta->serie;
            $data_fac['numero_fel'] = $respuesta->numero;
            $data_fac['identificador'] = $identificador;
            $this->db->where('code', $code);
            $this->db->update('sales', $data_fac);
            
            $num = $this->db->get_where('settings',array('type'=>'identificador_demo'))->row()->description;
            $data_identi['description'] = $num + 1;
            $this->db->where('type','identificador_demo');
            $this->db->update('settings', $data_identi);
            //echo $res;
        }
        else
        {
            echo 'error-fel';
            $data_fac['estado'] = 6;
            $data_fac['FEL'] = 2;
            $this->db->where('code', $code);
            $this->db->update('sales', $data_fac);
        }
    }

        } catch (Exception $e) {
            log_message("error", "Error exception tc: $e");
            $response = false;
        }


    
}
function validarXMLCambiaria($code){
    $sale_data = $this->db->get_where('sales',array('code'=>$code))->row();
    $name = $sale_data->name;
    $cui    = $sale_data->cui;
    
    $nit = $sale_data->nit;
    $nit = str_replace(' ', '', $nit);
    $name = str_replace('"', '', $name);
    if($cui!=1){
     //$nit2 = $this->getNit2($nit);    
       if( $nit == 'Cf' || $nit == 'cf'|| $nit == 'cF' || $nit == 'FARMACIA' || $nit == 'farmacia' || $nit == 'MAYORISTA' || strlen($nit)<6){
           $nit = 'CF';
          }
    }
    $address = $sale_data->address;
    if($address == ''||$address == null){
        $address = 'Ciudad';
    }
    $cliente = $this->db->get_where('client', array('client_id'=>$sale_data->client_id))->row();
     if($cliente->type == 1){
            $type_price = 'price_my';
            $type_sub = 'sub_my';
        }elseif($cliente->type == 2){
            $type_price = 'price';
            $type_sub = 'sub';
        }if($cliente->type == 3 ){
            $type_price = 'price_farma';
            $type_sub = 'sub_farma';
        }
    $productos = json_decode($sale_data->products,true);
    $total = $sale_data->total;
    $num = $sale_data->num_products;
    
    //$code = $this->input->post('code');
    $jwt = $this->db->get_where('settings', array('type'=>'jwt'))->row()->description;

    $nombreComercial = $this->db->get_where('settings', array('type'=>'nombreComercial'))->row()->description;
    $emisorDireccion = str_replace('<br>','',$this->db->get_where('settings', array('type'=>'direccionemisor'))->row()->description);
    $emisorCodigoPostal = $this->db->get_where('settings', array('type'=>'codigoPostal'))->row()->description;
    $emisorMunicipio = $this->db->get_where('settings', array('type'=>'municipio'))->row()->description;
    $emisorDepartamento = $this->db->get_where('settings', array('type'=>'departamento'))->row()->description;
    
    $establecimiento = 1;
    if($this->session->userdata('branch_id')==23){
    $establecimiento = 2;   
    $emisorDireccion = str_replace('<br>','',$this->db->get_where('settings', array('type'=>'direccionemisor2'))->row()->description);
    $emisorMunicipio = $this->db->get_where('settings', array('type'=>'municipio2'))->row()->description;
    }

    $rg=$this->db->get_where('settings',array('type'=>'regimen'))->row()->description;
    if($rg == 12){
        $afiliacion = 'GEN';
    }else{
        $afiliacion = 'PEC';
    }
    
    $fecha = $sale_data->date; // '2025-08-18'

// separar la fecha y combinar con hora actual
$FechaHoraEmision = date('Y-m-d\TH:i:s', strtotime($fecha . ' ' . date('H:i:s')));

    
    $xml= '<?xml version="1.0" encoding="utf-8"?>
<dte:GTDocumento xmlns:cex="http://www.sat.gob.gt/face2/ComplementoExportaciones/0.1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:cfe="http://www.sat.gob.gt/face2/ComplementoFacturaEspecial/0.1.0" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:cfc="http://www.sat.gob.gt/dte/fel/CompCambiaria/0.1.0" xmlns:cno="http://www.sat.gob.gt/face2/ComplementoReferenciaNota/0.1.0" Version="0.1" xmlns:dte="http://www.sat.gob.gt/dte/fel/0.2.0">
    <dte:SAT ClaseDocumento="dte">
        <dte:DTE ID="DatosCertificados">
            <dte:DatosEmision ID="DatosEmision">
                <dte:DatosGenerales Tipo="FCAM" FechaHoraEmision="'.$FechaHoraEmision.'" CodigoMoneda="GTQ" />
                <dte:Emisor NITEmisor="'.$this->db->get_where('settings', array('type'=>'nit'))->row()->description.'" NombreEmisor="'.$this->db->get_where('settings', array('type'=>'name_fel'))->row()->description.'" CodigoEstablecimiento="1" NombreComercial="'.$nombreComercial.'" AfiliacionIVA="'.$afiliacion.'">
                    <dte:DireccionEmisor>
                        <dte:Direccion>'.$emisorDireccion.'</dte:Direccion>
                        <dte:CodigoPostal>'.$emisorCodigoPostal.'</dte:CodigoPostal>
                        <dte:Municipio>'.$emisorMunicipio.'</dte:Municipio>
                        <dte:Departamento>'.$emisorDepartamento.'</dte:Departamento>
                        <dte:Pais>GT</dte:Pais>
                    </dte:DireccionEmisor>
                </dte:Emisor>
                <dte:Receptor IDReceptor="'.$nit.'"';
                if ($cui == 1) $xml .= ' TipoEspecial="CUI" ';
                $xml .= ' NombreReceptor="'.$name.'">
                    <dte:DireccionReceptor>
                        <dte:Direccion>'.$address.'</dte:Direccion>
                        <dte:CodigoPostal>07001</dte:CodigoPostal>
                        <dte:Municipio>Momostenango</dte:Municipio>
                        <dte:Departamento>Totonicapan</dte:Departamento>
                        <dte:Pais>GT</dte:Pais>
                    </dte:DireccionReceptor>
                </dte:Receptor>
                <dte:Frases>
                    <dte:Frase TipoFrase="1" CodigoEscenario="1" />
                    <dte:Frase CodigoEscenario="1" TipoFrase="2"/>
                    <dte:Frase TipoFrase="4" CodigoEscenario="9" />
                </dte:Frases>
                <dte:Items>';
                    $totalImpuesto =0;
                    $GranTotal =0;
                    $n=1; for ($i=0; $i < $num ; $i++) { $regimen=$this->db->get_where('settings',array('type'=>'regimen'))->row()->description / 100;
                    $description=$this->db->get_where('products',array('products_id'=>$productos[$i]["product"]))->row();
                        $totalp = $productos[$i][$type_price]*$productos[$i]["amount"];
                        $montoGravable = number_format($totalp/($regimen + 1),6,".","");
                        if($description->iva == 1){
                        $montoGravable = number_format($totalp/($regimen + 1),6,".","");
                        $montoImpuesto = number_format($montoGravable*$regimen,6,".","");    
                        $CodigoUnidadGravable= 1;
                        }else{
                            $montoGravable = number_format($totalp,6,".","");
                            $montoImpuesto = 0.00;    
                            $CodigoUnidadGravable= 2;
                        }
                        $totalImpuesto += $montoImpuesto;
                        $GranTotal += $montoGravable + $montoImpuesto;
                        $xml.='
                        <dte:Item NumeroLinea="'.$n++.'" BienOServicio="B">
                            <dte:Cantidad>'.$productos[$i]["amount"].'</dte:Cantidad>
                            <dte:UnidadMedida>UNI</dte:UnidadMedida>
                            <dte:Descripcion>'.$description->name.'</dte:Descripcion>
                            <dte:PrecioUnitario>'.number_format($productos[$i][$type_price],6,".","").'</dte:PrecioUnitario>
                            <dte:Precio>'.number_format($totalp,6,".","").'</dte:Precio>
                            <dte:Descuento>0</dte:Descuento>
                            <dte:Impuestos>
                                <dte:Impuesto>
                                    <dte:NombreCorto>IVA</dte:NombreCorto>
                                    <dte:CodigoUnidadGravable>'.$CodigoUnidadGravable.'</dte:CodigoUnidadGravable>
                                    <dte:MontoGravable>'.$montoGravable.'</dte:MontoGravable>
                                    <dte:MontoImpuesto>'.$montoImpuesto.'</dte:MontoImpuesto>
                                </dte:Impuesto>
                            </dte:Impuestos>
                            <dte:Total>'.number_format($productos[$i][$type_sub],6,".","").'</dte:Total>
                        </dte:Item>';
                        }
                        $xml.='
                </dte:Items>
                <dte:Totales>
                    <dte:TotalImpuestos>
                        <dte:TotalImpuesto NombreCorto="IVA" TotalMontoImpuesto="'.number_format($totalImpuesto,6,".","").'" />
                    </dte:TotalImpuestos>
                    <dte:GranTotal>'.number_format($GranTotal,6,".","").'</dte:GranTotal>
                </dte:Totales>
                <dte:Complementos>
                    <dte:Complemento IDComplemento="TEXT" NombreComplemento="TEXT" URIComplemento="TEXT">
                        <cfc:AbonosFacturaCambiaria xmlns:cfc="http://www.sat.gob.gt/dte/fel/CompCambiaria/0.1.0" Version="1" xsi:schemaLocation="http://www.sat.gob.gt/dte/fel/CompCambiaria/0.1.0 C:\Users\Desktop\SAT_FEL_FINAL_V1\Esquemas\GT_Complemento_Cambiaria-0.1.0.xsd">
                            <cfc:Abono>
                                <cfc:NumeroAbono>1</cfc:NumeroAbono>
                                <cfc:FechaVencimiento>'.date("Y-m-d", strtotime($today."+ 30 days")).'</cfc:FechaVencimiento>
                                <cfc:MontoAbono>'.rtrim(number_format($GranTotal,6,'.','')).'</cfc:MontoAbono>
                            </cfc:Abono>
                        </cfc:AbonosFacturaCambiaria>
                    </dte:Complemento>
                </dte:Complementos>
            </dte:DatosEmision>
        </dte:DTE>
    </dte:SAT>
</dte:GTDocumento>';

$fel = $this->crud_model->getInfo("FEL");
log_message('error','fell: '.$fel);
        if ($fel) {
            $url           = $this->crud_model->getInfo("url_fel_prod");
            $usuario_api   = $this->crud_model->getInfo("usuario_api_prod"); 
            $llave_api     = $this->crud_model->getInfo("llave_api_prod");
            $usuario_firma = $this->crud_model->getInfo("usuario_firma_prod"); 
            $correlativo   = $this->crud_model->getInfo("identificador_demo");
            $llave_firma   = $this->crud_model->getInfo("llave_firma_prod");
            $identificador = "FEL-".sprintf('%04d', $correlativo);

            /*$new_correl = $correlativo++;
            $this->db->where('type', 'identificador_demo');
            $this->db->update('settings', array('description'=>$new_correl));*/
        } else {
            $url           = $this->crud_model->getInfo("url_fel_demo");
            $usuario_api   = $this->crud_model->getInfo("usuario_api_demo"); 
            $llave_api     = $this->crud_model->getInfo("llave_api_demo");
            $usuario_firma = $this->crud_model->getInfo("usuario_firma_demo"); 
            $correlativo   = $this->crud_model->getInfo("identificador_prod");
            $llave_firma   = $this->crud_model->getInfo("llave_firma_demo");
            $identificador = "PRUEBA-".sprintf('%04d', $correlativo);

            /*$new_correl = $correlativo++;
            $this->db->where('type', 'identificador_demo');
            $this->db->update('settings', array('description'=>$new_correl));*/
        }

        $data = array(
            'URL: '.$url,
            'UsuarioAPI: '.$usuario_api,
            'LlaveAPI: '.$llave_api,
            'UsuarioFirma: '.$usuario_firma,
            'Identificador: '.$identificador,
            'LlaveFirma: '.$llave_firma
        );
        log_message("error", "Data: ".json_encode($data));

log_message('error',$xml);
log_message('error',urlencode($xml));
$curl = curl_init();
try {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $xml,
                CURLOPT_HTTPHEADER => array(
                    'UsuarioAPI: '.$usuario_api,
                    'LlaveAPI: '.$llave_api,
                    'UsuarioFirma: '.$usuario_firma,
                    'Identificador: '.$identificador,
                    'LlaveFirma: '.$llave_firma,
                    'Content-Type: text/plain'
                ),
            ));
    
            $response = curl_exec($curl);
    log_message('error',$response);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err)
    {
        $data_fac['estado'] = 6;
        $data_fac['FEL'] = 2;
        $this->db->where('code', $code);
        $this->db->update('sales', $data_fac);
        echo 'error-fel';   
    }
    else
    {
        $respuesta = json_decode($response);
        //log_message('error','array'.print_r($respuesta));
        if($respuesta->resultado)
        {
            $str = explode(',',$response);
            $res = json_encode($str);
            $data_fac['xml_fel']    = $respuesta->xml_certificado;
            $data_fac['facturado']  = 1;
            $data_fac['FEL']  = 1;
            //$data_fac['estado'] = 2;
            $data_fac['code_fel']   = $respuesta->uuid;
            $data_fac['date_fel']   = $respuesta->fecha;
            $data_fac['serie_fel']  = $respuesta->serie;
            $data_fac['numero_fel'] = $respuesta->numero;
            $data_fac['identificador'] = $identificador;
            $this->db->where('code', $code);
            $this->db->update('sales', $data_fac);
            
            $num = $this->db->get_where('settings',array('type'=>'identificador_demo'))->row()->description;
            $data_identi['description'] = $num + 1;
            $this->db->where('type','identificador_demo');
            $this->db->update('settings', $data_identi);
            //echo $res;
        }
        else
        {
            echo 'error-fel';
            $data_fac['estado'] = 6;
            $data_fac['FEL'] = 2;
            $this->db->where('code', $code);
            $this->db->update('sales', $data_fac);
        }
    }

        } catch (Exception $e) {
            log_message("error", "Error exception tc: $e");
            $response = false;
        }
}

/*function anulacionXML($code){

$nit = $this->input->post('nit');
$code_xml = $this->input->post('code_fel');
$date_xml = $this->input->post('date_fel');
$motivo = $this->input->post('motivo');
$nit_emisor = $this->db->get_where('settings', array('type'=>'nit'))->row()->description;
$jwt = $this->db->get_where('settings', array('type'=>'jwt'))->row()->description;
$fecha = date("Y-m-d\TH:i:s.u");


$xml= '
<?xml version="1.0" encoding="UTF-8"?>
<dte:GTAnulacionDocumento xmlns:dte="http://www.sat.gob.gt/dte/fel/0.1.0" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" Version="0.1" xsi:schemaLocation="http://www.sat.gob.gt/dte/fel/0.1.0 GT_AnulacionDocumento-0.1.0.xsd">
    <dte:SAT>
        <dte:AnulacionDTE ID="DatosCertificados">
            <dte:DatosGenerales FechaEmisionDocumentoAnular="'.$date_xml.'" FechaHoraAnulacion="'.$fecha.'-06:00'.'" ID="DatosAnulacion" IDReceptor="'.$nit.'" MotivoAnulacion="'.$motivo.'" NITEmisor="'.$nit_emisor.'" NumeroDocumentoAAnular="'.$code_xml.'" />
        </dte:AnulacionDTE>
    </dte:SAT>
</dte:GTAnulacionDocumento>';

$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => 'https://mayansource.com/dte/anularDTE.php',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_POSTFIELDS => 'document='.urlencode($xml).'&product=msbox',
CURLOPT_HTTPHEADER => array(
'Authorization: Bearer'.' '.$jwt,
'Content-Type: application/x-www-form-urlencoded'
),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err)
{

}else{


if($response != 'null'){
$data['estado'] = 3;
$data['motivo'] = trim($motivo);
$this->db->where('code', $code);
$this->db->update('sales', $data);
//redirect(base_url() . 'admin/historial_fel/', 'refresh');
$this->ventas('delete',$code,1);
}
}
}*/

function anulacionXML($code){
    
    $nit = $this->input->post('nit');
    if(strlen($nit) <= 5){
        $nit = 'CF';
    }
    $code_xml = $this->input->post('code_fel');
    $date_xml = $this->input->post('date_fel');
    $motivo = $this->input->post('motivo');
    $sale_data = $this->db->get_where('sales',array('code'=>$code))->row();
    $nit_emisor = $this->db->get_where('settings', array('type'=>'nit'))->row()->description;
    $jwt = $this->db->get_where('settings', array('type'=>'jwt'))->row()->description;
    $fecha = date("Y-m-d\TH:i:s");

    $xml = '<?xml version="1.0" encoding="UTF-8"?>
    <dte:GTAnulacionDocumento xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:dte="http://www.sat.gob.gt/dte/fel/0.1.0" xmlns:n1="http://www.altova.com/samplexml/other-namespace" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" Version="0.1" xsi:schemaLocation="http://www.sat.gob.gt/dte/fel/0.1.0 C:\Users\User\Desktop\FEL\Esquemas\GT_AnulacionDocumento-0.1.0.xsd">
            <dte:SAT>
                <dte:AnulacionDTE ID="DatosCertificados">
                <dte:DatosGenerales FechaEmisionDocumentoAnular="'.$date_xml.'" FechaHoraAnulacion="'.$fecha.'-06:00" ID="DatosAnulacion" IDReceptor="'.$nit.'" MotivoAnulacion="'.$motivo.'" NITEmisor="'.$nit_emisor.'" NumeroDocumentoAAnular="'.$code_xml.'"/>
                </dte:AnulacionDTE>
            </dte:SAT>
        </dte:GTAnulacionDocumento>';
 
    $fel = $this->crud_model->getInfo("FEL");
    log_message('error','fell: '.$fel);
        if ($fel) {
            $url           = $this->crud_model->getInfo("url_fel_prod");
            $usuario_api   = $this->crud_model->getInfo("usuario_api_prod"); 
            $llave_api     = $this->crud_model->getInfo("llave_api_prod");
            $usuario_firma = $this->crud_model->getInfo("usuario_firma_prod"); 
            $correlativo   = $this->crud_model->getInfo("identificador_demo");
            $llave_firma   = $this->crud_model->getInfo("llave_firma_prod");
            $identificador = $sale_data->identificador;//"FEL-".sprintf('%04d', $correlativo);

            /*$new_correl = $correlativo++;
            $this->db->where('type', 'identificador_demo');
            $this->db->update('settings', array('description'=>$new_correl));*/
        } else {
            $url           = $this->crud_model->getInfo("url_fel_demo");
            $usuario_api   = $this->crud_model->getInfo("usuario_api_demo"); 
            $llave_api     = $this->crud_model->getInfo("llave_api_demo");
            $usuario_firma = $this->crud_model->getInfo("usuario_firma_demo"); 
            $correlativo   = $this->crud_model->getInfo("identificador_prod");
            $llave_firma   = $this->crud_model->getInfo("llave_firma_demo");
            $identificador = "PRUEBA-".sprintf('%04d', $correlativo);

            /*$new_correl = $correlativo++;
            $this->db->where('type', 'identificador_demo');
            $this->db->update('settings', array('description'=>$new_correl));*/
        }

        $data = array(
            'URL: '.$url,
            'UsuarioAPI: '.$usuario_api,
            'LlaveAPI: '.$llave_api,
            'UsuarioFirma: '.$usuario_firma,
            'Identificador: '.$identificador,
            'LlaveFirma: '.$llave_firma
        );
        log_message("error", "Data: ".json_encode($data));

        log_message('error',$xml);
        log_message('error',urlencode($xml));
        $curl = curl_init();
        try {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $xml,
                CURLOPT_HTTPHEADER => array(
                    'UsuarioAPI: '.$usuario_api,
                    'LlaveAPI: '.$llave_api,
                    'UsuarioFirma: '.$usuario_firma,
                    'Identificador: '.$identificador,
                    'LlaveFirma: '.$llave_firma,
                    'Content-Type: text/plain'
                ),
            ));
    
            $response = curl_exec($curl);
            log_message('error',$response);
            $err = curl_error($curl);
            curl_close($curl);
        if ($err)
        {
        
        }else{
             $respuesta = json_decode($response);
            //log_message('error','array'.print_r($respuesta));
            if($respuesta->resultado)
            {
                $datasale['estado'] = 3;
                $datasale['motivo'] = trim($motivo);
                $this->db->where('code', $code);
                $this->db->update('sales', $datasale);
                $this->ventas('delete',$code,1);                //echo $res;
            }
        }
    } catch (Exception $e) {
        log_message("error", "Error exception tc: $e");
        $response = false;
    }
}


public function checktoken(){
$jwt = $this->db->get_where('settings', array('type'=>'jwt'))->row()->description;
$tokenParts = explode('.', $jwt);
$header = base64_decode($tokenParts[0]);
$payload = base64_decode($tokenParts[1]);

if($currentTime <= json_decode($payload)->exp){
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://mayansource.com/dte/sat/refreshToken',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'jwt='.$jwt,
    CURLOPT_HTTPHEADER => array(
    'Content-Type: application/x-www-form-urlencoded',
    ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err)
    {
    echo 'error-actualizar-token';
    }
    else
    {
    $str = json_decode($response, true);

    if($str['jwt'] != ''){
    $data['description'] = $str['jwt'];
    $this->db->where('type', 'jwt' );
    $this->db->update('settings', $data);
    }


    }
    }
    }

    public function checkProducts(){
    $dias = $this->db->get_where('settings', array('type'=>'vencimiento'))->row()->description;

    $data = $this->crud_model->get_products($this->session->userdata('branch_id'));

    foreach ($data->result_array() as $row){

    $fecha_vencimiento = $this->crud_model->get_vencimiento($row['products_id'], $this->session->userdata('branch_id'));

    $hoy = date('Y-m-d');
    $date1 = new DateTime($hoy);
    $date2 = new DateTime($fecha_vencimiento);
    $diff = $date1->diff($date2);

    if($fecha_vencimiento > '0000-00-00' && $diff->days < $dias){ log_message('producto por vencer :'.$row['name'].'.'); $message='Producto a punto de vencer ' .$row['name']; $this->crud_model->insert_notification($message,
        base64_encode('admin/producto_detalle/'.$row['products_id']), 'inventario', 'Inventario');
        }
        }


        }

        function validarXMLtarde(){

        $jwt = $this->db->get_where('settings', array('type'=>'jwt'))->row()->description;
        $nit_emisor = $this->db->get_where('settings', array('type'=>'nit'))->row()->description;




        $data = $this->db->get_where('sales', array('FEL'=>2));

        foreach($data->result_array() as $row){
        for ($i=0; $i < $row['num_products'] ; $i++){ if ($row['products'] !="" || $row['products'] !=null) { $pro=json_decode($row['products'],true); }else{ $pro=array(); } $total=$row['total']; $num=$row['num_products']; $code=$row['code']; $typeClient=$this->db->get_Where('client',
            array('client_id'=> $row['client_id'] ))->row()->type;
            if($row['client']== 0){
            $name = 'Consumidor Final';
            $nit = 'CF';
            $address = 'Ciudad';
            $price = $pro[$i]['price'];
            }else{
            $name = $row['name'];
            $nit = $row['nit'];
            $address = $row['address'];
            if($typeClient == 1){
            $price = $pro[$i]['price_my'];
            }else{
            $price = $pro[$i]['price'];
            }
            }

            $productName = $this->db->get_where('products', array('products_id'=>$pro[$i]['product']))->row()->name;

            $xml= '
            <?xml version="1.0" encoding="utf-8"?>
            <dte:GTDocumento xmlns:cex="http://www.sat.gob.gt/face2/ComplementoExportaciones/0.1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:cfe="http://www.sat.gob.gt/face2/ComplementoFacturaEspecial/0.1.0" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:cfc="http://www.sat.gob.gt/dte/fel/CompCambiaria/0.1.0" xmlns:cno="http://www.sat.gob.gt/face2/ComplementoReferenciaNota/0.1.0" Version="0.1" xmlns:dte="http://www.sat.gob.gt/dte/fel/0.2.0">
                <dte:SAT ClaseDocumento="dte">
                    <dte:DTE ID="DatosCertificados">
                        <dte:DatosEmision ID="DatosEmision">
                            <dte:DatosGenerales Tipo="FACT" FechaHoraEmision="'.date('Y').'-'.date('m').'-'.date('d').'T'.date('H').':'.date('i').':'.date('s').'" CodigoMoneda="GTQ" />
                            <dte:Emisor NITEmisor="'.$this->db->get_where('settings', array('type'=>'nit'))->row()->description.'" NombreEmisor="'.$this->db->get_where('settings', array('type'=>'name_fel'))->row()->description.'" CodigoEstablecimiento="4" NombreComercial="MAYANSOURCE" AfiliacionIVA="GEN">
                                <dte:DireccionEmisor>
                                    <dte:Direccion>2 CALLE 7-57 Zona 4</dte:Direccion>
                                    <dte:CodigoPostal>01000</dte:CodigoPostal>
                                    <dte:Municipio>Coban</dte:Municipio>
                                    <dte:Departamento>ALTA VERAPAZ</dte:Departamento>
                                    <dte:Pais>GT</dte:Pais>
                                </dte:DireccionEmisor>
                            </dte:Emisor>
                            <dte:Receptor IDReceptor="'.$nit.'" NombreReceptor="'.$name.'">
                                <dte:DireccionReceptor>
                                    <dte:Direccion>'.$address.'</dte:Direccion>
                                    <dte:CodigoPostal>01000</dte:CodigoPostal>
                                    <dte:Municipio>Guatemala</dte:Municipio>
                                    <dte:Departamento>Guatemala</dte:Departamento>
                                    <dte:Pais>GT</dte:Pais>
                                </dte:DireccionReceptor>
                            </dte:Receptor>
                            <dte:Frases>
                                <dte:Frase TipoFrase="1" CodigoEscenario="1" />
                            </dte:Frases>
                            <dte:Items>';
                                $totalImpuesto =0;
                                $GranTotal =0;
                                $n=1; for ($i=0; $i < $num ; $i++) { $regimen=$this->
                                    db->get_where('settings',array('type'=>'regimen'))->row()->description / 100;
                                    $montoGravable = number_format($price/($regimen + 1),6,".","");
                                    $montoImpuesto = number_format($montoGravable*$regimen,6,".","");
                                    $totalImpuesto += $montoImpuesto;
                                    $GranTotal += $montoGravable + $montoImpuesto;
                                    $xml.='
                                    <dte:Item NumeroLinea="'.$n++.'" BienOServicio="B">
                                        <dte:Cantidad>'.$pro[$i]['amount'].'</dte:Cantidad>
                                        <dte:UnidadMedida>UNI</dte:UnidadMedida>
                                        <dte:Descripcion>'.$productName.'</dte:Descripcion>
                                        <dte:PrecioUnitario>'.number_format($price,2,".","").'</dte:PrecioUnitario>
                                        <dte:Precio>'.number_format($price,6,".","").'</dte:Precio>
                                        <dte:Descuento>'.$pro[$i]['discount'].'</dte:Descuento>
                                        <dte:Impuestos>
                                            <dte:Impuesto>
                                                <dte:NombreCorto>IVA</dte:NombreCorto>
                                                <dte:CodigoUnidadGravable>1</dte:CodigoUnidadGravable>
                                                <dte:MontoGravable>'.$montoGravable.'</dte:MontoGravable>
                                                <dte:MontoImpuesto>'.$montoImpuesto.'</dte:MontoImpuesto>
                                            </dte:Impuesto>
                                        </dte:Impuestos>
                                        <dte:Total>'.number_format($price,6,".","").'</dte:Total>
                                    </dte:Item>';
                                    }
                                    $xml.='
                            </dte:Items>
                            <dte:Totales>
                                <dte:TotalImpuestos>
                                    <dte:TotalImpuesto NombreCorto="IVA" TotalMontoImpuesto="'.number_format($totalImpuesto,6," .","").'" />
                                </dte:TotalImpuestos>
                                <dte:GranTotal>'.number_format($GranTotal,6,".","").'</dte:GranTotal>
                            </dte:Totales>
                        </dte:DatosEmision>
                    </dte:DTE>
                </dte:SAT>
            </dte:GTDocumento>';

            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://mayansource.com/dte/firmarDTE.php',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'document='.$xml.'&product=msbox',
            CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer'.' '.$jwt,
            'Content-Type: application/x-www-form-urlencoded',
            ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
            if ($err)
            {
            $data_error['estado'] = '5';
            $data_error['FEL'] = '2';
            $this->db->where('code', $code);
            $this->db->update('sales', $data_error);
            echo 'error-fel';
            }
            else
            {
            $str = json_decode($response, true);


            $dat1['estado'] = '2';
            $dat1['FEL'] = '1';
            $dat1['xml_fel'] = $str['1'];
            $dat1['code_fel'] = $str['guid'];
            $dat1['date_fel'] = $str['date'];
            $this->db->where('code', $code );
            $this->db->update('sales', $dat1);
            }



            }
            }
            }


            function checkCodigos(){

            $codigo = $this->input->post('code');
            $valor = $this->input->post('valor');
            log_message('error',$valor);
            $codigos = $this->db->get_where('admin', array('code_authorization'=>$codigo , $valor => 1))->row()->code_authorization;

            if($codigo == $codigos){
            echo '1';
            }else{
            echo '0';
            }



            }

            function carga($param1='')
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    redirect(base_url(), 'refresh');
                }
                if($param1=='excel')
                {
                $this->crud_model->bulkProducts();
                    
                }
                $page_data['code']  = $code;
                $page_data['page_name']  = 'carga';
                $page_data['page_title'] = "Detalles del viaje";
                $this->load->view('backend/index', $page_data);
                
            }
            
            
            
            
            
            function get_sum_stock($product_id,$branch_id)
            {   
                $this->db->select_sum('amount', 'amount');
                $this->db->where('products_id',$product_id);
                $this->db->where('branch_id',$branch_id);
                $this->db->group_start();
                $this->db->where('type',1);
                $this->db->or_where('type',2);
                $this->db->group_end();
                $this->db->where('status',1);
                $stock = $this->db->get('product_details')->row()->amount;
                
                $this->db->select_sum('amount', 'amount');
                $this->db->where('products_id',$product_id);
                $this->db->where('branch_id',$branch_id);
                $this->db->group_start();
                $this->db->where('type',0);
                $this->db->or_where('type',3);
                $this->db->or_where('type',4);
                $this->db->group_end();
                $this->db->where('status',1);
                $discount = $this->db->get('product_details')->row()->amount;
        
                $total = $stock - $discount;
                if($total > 0)
                    echo $stock.' - '.$discount;
                else
                    echo $stock.' - '.$discount;
            }
            
            
            function get_inventario()
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_inventario();    
                }
            }
            
            function get_increase($param1, $param2)
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_increase($param1, $param2);    
                }
            }
            
            function get_perdidas($param1, $param2)
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_losses($param1, $param2);    
                }
            }
            
            function get_vencidos($param1, $param2)
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_vencidos($param1, $param2);    
                }
            }
            
            function get_ventas_traslados($param1, $param2, $param3 = '')
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_ventas_traslados($param1, $param2, $param3);    
                }
            }
            
            function get_ventas_producto($param1, $param2, $param3 = '')
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_ventas_producto($param1, $param2, $param3);    
                }
            }
            
            function get_ventas_marca($param1, $param2, $param3 = '')
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_ventas_marca($param1, $param2, $param3);    
                }
            }
            
            function get_ventas_afectas($param1, $param2)
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_ventas_afectas($param1, $param2);    
                }
            }
            
            function get_ventas_exentas($param1, $param2)
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_ventas_exentas($param1, $param2);    
                }
            }
            
            function get_cuentas_por_cobrar($param1, $param2, $param3 = '')
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_cuentas_por_cobrar($param1, $param2, $param3);    
                }
            }
            
            function get_cuentas_por_pagar($param1, $param2, $param3 = '')
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_cuentas_por_pagar($param1, $param2, $param3);    
                }
            }
            
            function get_ventas_vendedor($param1, $param2, $param3 = '')
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_ventas_vendedor($param1, $param2, $param3);    
                }
            }
            
            function get_clientes_por_usuario($param1, $param2, $param3 = '')
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_clientes_por_usuario($param1, $param2, $param3);    
                }
            }
            
            
            function get_inventario_by_mark($mark_id = '')
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_inventario_by_mark($mark_id);  
                }
            }
            
            function get_inventario_by_category($category_id)
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_inventario_by_category($category_id);    
                }
            }
            
            function get_inventario_by_class($category_id)
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_inventario_by_class($category_id);    
                }
            }
            
            function get_inventario_by_type($category_id)
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_inventario_by_type($category_id);    
                }
            }
            
            function get_inventario_by_marca($category_id)
            {
                if ($this->session->userdata('admin_login') != 1)
                {
                    return array('message' => 'error');    
                }
                else
                {
                    return $this->tables_model->get_inventario_by_marca($category_id);    
                }
            }
            
            function compare_stock()
            {
                if($_POST['c'] != "")
                {
                    $cantidad = $_POST['c'];
                    $result = $this->crud_model->get_stock_compare($cantidad);
                    if($result == 'success') 
                    {
                        echo 'success';
                    }
                    
                    else
                    {
                        echo $result; 
                    }
                }
            }
            
            function fixedProductLotes() {
                // $this->crud_model->fixedProductLotes();
                // redirect(base_url().'admin/inventario/', 'refresh');
            }
            
            function corte_caja_filtro($param1='',$param2='',$param3=''){
                $initial = $this->input->post('initial');
                
                // log_message("error", "Initial: $initial, Final: $final");
                if ($initial == '') $initial = date('Y-m-d');
                
                $page_data['initial']  = $initial;
                $page_data['page_name']  = 'corte_caja_filtro';
                $page_data['page_title'] = "Corte de caja";
                $this->load->view('backend/index', $page_data);
            }
            
            function FEL($param1 = '',$param2 = '',$param3 = ''){
        
                if($param1== "reemitir"){
                    
                    $FEL = $this->db->get_where('sales',array('code'=>$param2))->row()->FEL;
                    if($FEL==1){
                        $this->session->set_flashdata('flash_message' , "Venta ya certificada.");
                        redirect(base_url() . 'admin/detalles_venta/'.$param2, 'refresh');    
                    }
                    else
                    {
                       $venta = $this->db->get_where('sales',array('code'=>$param2))->row();
                        if($venta->credito == 1){
                            //$this->validarXMLCambiaria($param2);
                        }
                        else{
                           // $this->validarXML($param2);   
                        } 
                        $this->session->set_flashdata('flash_message' , "Venta reemitida correctamente.");
                        redirect(base_url() . 'admin/detalles_venta/'.$param2, 'refresh');   
                    }
                }
            }
            
            function import_products_old()
            {
                $this->load->library('PHPExcel'); 
                $path = 'multiahorro.xlsx';
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet)
                {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    $cont = 1;
                    for($row=2; $row <= $highestRow; $row++)
                    {
                        $name           = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $amount         = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $precio         = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $mayorista      = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $socio          = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $costo          = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $marca          = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $iva            = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        
                        if($name != '')
                        {
                            //echo $cont++.' Nuevo Producto '.$name.'--'.$amount.'--'.$precio.'--'.$mayorista.'--'.$socio.'--'.$costo.'--'.$marca.'--'.$iva.'<br>';
                          $exist_marca = $this->db->where('name',$marca)->get('mark');
                          if($exist_marca->num_rows() == 0)
                            {
                                $data_marca['name']    = $marca;
                                $data_marca['branch_id']    = 1;
                                $this->db->insert('mark', $data_marca); 
                                $id_mark = $this->db->insert_id();
                            }else{
                                $id_mark = $exist_marca->row()->mark_id;
                            }
                            if($iva == 'SI'){
                                $iva2 = 1;
                            }else{
                                $iva2 = 0;
                            }
                                if($name != '')
                                {
                                    //echo $cont++.' Nuevo Producto '.$name.'--'.$amount.'<br>';
                                    //echo $cont++.' Nuevo Producto '.$exist->row()->products_id.'//'.$exist->row()->name.'--'.$exist->row()->price.'--'.$exist->row()->cost.'<br>';
                                    echo $cont++.' Nuevo Producto1111 '.$name.'--'.$amount.'--'.$precio.'--'.$mayorista.'--'.$socio.'--'.$costo.'--'.$marca.'--'.$iva.'<br>';
                                    //$code = $this->crud_model->getCodeIngreso();
                                    
                                    $data['code']    =  $cont;
                                    $data['name']     =  $name;
                                    $data['category']         =  1;
                                    $data['provider']               =  1;
                                    $data['mark']               =  $id_mark;
                                    $data['stock_bodega']       =  $amount;
                                    $data['cost']               =  $costo;
                                    $data['precio_mayorista']   =  $mayorista;
                                    $data['farma']   =  $socio;
                                    $data['price']              =  $precio;
                                    $data['status']             = 1;
                                    $this->db->insert('products',$data);
                                    $products_id = $this->db->insert_id();
                                    
                                        $data2['products_id']   =   $products_id;
                                        $data2['user_id']    =   1;
                                        $data2['type']      =   1;
                                        $data2['branch_id']      =   0;
                                        $data2['amount']      =  $amount;
                                        $data2['provider']      =  $data['provider'];
                                        $data2['price']      =  $data['price'];
                                        $data2['cost']      =  $data['cost'];
                                        $data2['status']      =  1;
                                        $data2['estado']      =  1;
                                        $data2['date']      =  date('Y/m/d');
                    
                                        $this->db->insert('product_details' , $data2);
                                    
                                }
                            
                        }
                    }
                }
            }
            function import_products2()
            {
                $this->load->library('PHPExcel'); 
                $path = 'Tienda.xls';
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet)
                {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    $cont = 1;
                    for($row=2; $row <= $highestRow; $row++)
                    {
                        $name           = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $amount         = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $precio         = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $mayorista      = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $socio          = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $costo          = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $marca          = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $iva            = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        
                        if($name != '')
                        {
                            //echo $cont++.' Nuevo Producto '.$name.'--'.$amount.'--'.$precio.'--'.$mayorista.'--'.$socio.'--'.$costo.'--'.$marca.'--'.$iva.'<br>';
                            $cont++;
                          $exist_products = $this->db->where('name',$name)->get('products');
                          
                                if($name != '' && $exist_products->num_rows() > 0)
                                {
                                    //echo $cont++.' Nuevo Producto '.$name.'--'.$amount.'<br>';
                                    //echo $cont++.' Nuevo Producto '.$exist->row()->products_id.'//'.$exist->row()->name.'--'.$exist->row()->price.'--'.$exist->row()->cost.'<br>';
                                    //echo $cont++.' Nuevo Producto '.$name.'--'.$amount.'--'.$precio.'--'.$mayorista.'--'.$socio.'--'.$costo.'--'.$marca.'--'.$iva.'<br>';
                                    //$code = $this->crud_model->getCodeIngreso();
                                    
                                        $products_id = $exist_products->row()->products_id;
                                        $data2['products_id']   =   $products_id;
                                        $data2['user_id']    =   1;
                                        $data2['type']      =   1;
                                        $data2['branch_id']      =   1;
                                        $data2['amount']      =  $amount;
                                        $data2['provider']      =  $exist_products->row()->provider;
                                        $data2['price']      =  $exist_products->row()->price;
                                        $data2['cost']      =  $exist_products->row()->cost;
                                        $data2['status']      =  1;
                                        $data2['estado']      =  1;
                                        $data2['date']      =  date('Y/m/d');
                                        echo print_r($data2).'<br>';
                                       // $this->db->insert('product_details' , $data2);
                                    
                                }else{
                                    echo $name.' no encontrado <br>';
                                    $data['code']    =  $cont;
                                    $data['name']     =  $name;
                                    $data['category']         =  1;
                                    $data['provider']               =  1;
                                    $data['mark']               =  1;
                                    $data['stock_bodega']       =  $amount;
                                    $data['cost']               =  0;
                                    $data['precio_mayorista']   =  0;
                                    $data['farma']   =  0;
                                    $data['price']              =  0;
                                    $data['status']             = 1;
                                    //$this->db->insert('products',$data);
                                    //$products_id = $this->db->insert_id();
                                    echo print_r($data).'<br>';
                                }
                            
                        }
                    }
                }
            }
            
            function import_provider_old()
            {
                $this->load->library('PHPExcel'); 
                $path = 'Proveedores.xls';
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet)
                {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    $cont = 1;
                    for($row=2; $row <= $highestRow; $row++)
                    {
                        $nit        = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $nombre     = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $telefono   = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $direccion  = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $correo     = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        
                        if($nombre != '')
                        {
                            //echo $cont++.' Nuevo Producto '.$name.'--'.$amount.'--'.$precio.'--'.$mayorista.'--'.$socio.'--'.$costo.'--'.$marca.'--'.$iva.'<br>';
                          $exist_marca = $this->db->where('nit',$nit)->get('provider');
                          if($exist_marca->num_rows() == 0)
                            {
                                $data_marca['nit']    = $nit;
                                $data_marca['name']    = $nombre;
                                $data_marca['phone']    = $telefono;
                                $data_marca['address']    = $direccion;
                                $data_marca['email']    = $correo;
                                $data_marca['branch_id']    = 1;
                                $this->db->insert('provider', $data_marca); 
                                echo 'encontrado'.print_r($data_marca);
                            }else{
                                $id_mark = $exist_marca->row()->mark_id;
                                echo 'encontrado';
                            }
                        }
                    }
                }
            }
            
            function correccion_p_old()
            {
                $this->load->library('PHPExcel'); 
                $path = 'multiahorro.xlsx';
                $object = PHPExcel_IOFactory::load($path);
                foreach($object->getWorksheetIterator() as $worksheet)
                {
                    $highestRow = $worksheet->getHighestRow();
                    $highestColumn = $worksheet->getHighestColumn();
                    $cont = 1;
                    for($row=2; $row <= $highestRow; $row++)
                    {
                        $name           = $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                        $amount         = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                        $precio         = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                        $mayorista      = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                        $socio          = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                        $costo          = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                        $marca          = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                        $iva            = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                        
                        if($name != '')
                        {
                            //echo $cont++.' Nuevo Producto '.$name.'--'.$amount.'--'.$precio.'--'.$mayorista.'--'.$socio.'--'.$costo.'--'.$marca.'--'.$iva.'<br>';
                            $cont++;
                          $exist_products = $this->db->where('name',$name)->get('products');
                                if($iva == 'SI'){
                                    $iva2 = 1;
                                }else{
                                    $iva2 = 0;
                                }
                                if($name != '' && $exist_products->num_rows() > 0)
                                {
                                        
                                        $products_id = $exist_products->row()->products_id;
                                        //$data2['products_id']   =   $products_id;
                                        $data2['iva']   =   $iva2;
                                        echo print_r($data2).'<br>';
                                        $this->db->where('products_id',$products_id);
                                        $this->db->update('products' , $data2);
                                    
                                }else{
                                    echo $name.' no encontrado <br>';
                                    $data['name']     =  $name;
                                    $data['iva']   =   $iva2;
                                    //$this->db->insert('products',$data);
                                    //$products_id = $this->db->insert_id();
                                    echo print_r($data).'<br>';
                                }
                            
                        }
                    }
                }
            }
            
    public function search_products() {
      
        $q = trim($this->input->post('q'));
       
        $this->db->select('products_id as id, CONCAT(name, " (", code, ")") as text');
        $this->db->from('products');
        $this->db->where('status', 1);
        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('name', $q);
            $this->db->or_like('code', $q);
            $this->db->group_end();
        } else {
            // Si no hay búsqueda, no devolver nada para evitar mostrar todos
           
            echo json_encode([]);
            return;
        }
        $query = $this->db->get();
        $results = $query->result_array();
       
        if (!empty($results)) {
            log_message('error', 'First result: ' . json_encode($results[0]));
        }
        echo json_encode($results);
    }

            /* End admin */
}
