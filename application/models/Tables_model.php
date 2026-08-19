<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tables_model extends CI_Model 
{
    function __construct() 
    {
      parent::__construct();
    }
    
    
    function getTables($table = '', $param1 = '', $param2 = '', $param3 = '')
    {
        $fetch_data = $this->MakeTable($table, $param1, $param2, $param3);  
        $data = $this->getArrays($table,$fetch_data,$param1, $param2, $param3);    
       
        $output = array(  
            "draw"                      =>      intval($_POST["draw"]),  
            "recordsTotal"              =>      $this->GetAllData($table,$param1, $param2, $param3),  
            "recordsFiltered"           =>      $this->GetFilteredData($table,$param1, $param2, $param3),  
            "data"                      =>      $data  
        );  
        
        echo json_encode($output); 
    }
    
    function MakeTable($table,$param1, $param2, $param3)
	{  
        $this->MakeQuery($table,$param1, $param2, $param3);  
        if($_POST["length"] != -1)  
        {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result_array();  
    }
    
    function MakeQuery($table,$param1, $param2, $param3)  
    {  
        $this->db->select("*");  
        
        if($table == 'sales')
        {
            $this->db->order_by('sales_id', 'desc');
            $this->db->from("sales");
            $this->db->where('status',1);
            
            $this->db->where('branch_id',$this->session->userdata('branch_id'));
            $this->db->where("DATE(date) >= DATE('$param1')", NULL, FALSE);
            $this->db->where("DATE(date) <= DATE('$param2')", NULL, FALSE);
            // log_message("error", "Table: Initial: $param1, Final: $param2");
        }
        
        //*****************
        if($_POST["search"]["value"] !='' )  
        {
            if($table == 'sales')
            {   
                if($_POST["search"]["value"] == 'completado' || $_POST["search"]["value"] == 'Completado' ){
                    $this->db->where("credito",0 );
                }
                elseif($_POST["search"]["value"] == 'credito' || $_POST["search"]["value"] == 'Credito'){
                    $this->db->where("credito",1 );
                }
                elseif($_POST["search"]["value"] >= 01){
                        $oldDate = $_POST["search"]["value"];
                        $arr = explode(' de ', $oldDate);
                        if($arr[1]=='enero'){
                            $newDate = $arr[2].'-'.'01'.'-'.$arr[0];
                        }
                        if($arr[1]=='febrero'){
                            $newDate = $arr[2].'-'.'02'.'-'.$arr[0];
                        }
                        if($arr[1]=='marzo'){
                            $newDate = $arr[2].'-'.'03'.'-'.$arr[0];
                        }
                        if($arr[1]=='abril'){
                            $newDate = $arr[2].'-'.'04'.'-'.$arr[0];
                        }
                        if($arr[1]=='mayo'){
                            $newDate = $arr[2].'-'.'05'.'-'.$arr[0];
                        }
                        if($arr[1]=='junio'){
                            $newDate = $arr[2].'-'.'06'.'-'.$arr[0];
                        }
                        if($arr[1]=='julio'){
                            $newDate = $arr[2].'-'.'07'.'-'.$arr[0];
                        }
                        if($arr[1]=='agosto'){
                            $newDate = $arr[2].'-'.'08'.'-'.$arr[0];
                        }
                        if($arr[1]=='septiembre'){
                            $newDate = $arr[2].'-'.'09'.'-'.$arr[0];
                        }
                        if($arr[1]=='octubre'){
                            $newDate = $arr[2].'-'.'10'.'-'.$arr[0];
                        }
                        if($arr[1]=='noviembre'){
                            $newDate = $arr[2].'-'.'11'.'-'.$arr[0];
                        }
                        if($arr[1]=='diciembre'){
                            $newDate = $arr[2].'-'.'12'.'-'.$arr[0];
                        }
                        $this->db->like("date", $newDate,"");  
                }
                else {
                    $this->db->group_start();
                    $this->db->like("name_res",$_POST["search"]["value"],"");   
                    $this->db->or_like("name",$_POST["search"]["value"],"");   
                    $this->db->or_like("metodo",$_POST["search"]["value"],"");
                    $this->db->or_like("code",$_POST["search"]["value"],"");
                    $this->db->group_end();
                }
            } 
        }  

        if(isset($_POST["order"]))  
        {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        } else  
        {  
            if($table == 'sales')
            {
                $this->db->order_by('sales_id', 'DESC');
            }
        }  
    }
    
    function GetAllData($table,$param1, $param2, $param3)  
    {  
        if($table == 'sales')
        {
            $this->db->order_by('sales_id', 'DESC');
            $this->db->select("*");  
            $this->db->from("sales"); 
            $this->db->where("DATE(date) >= DATE('$param1')", NULL, FALSE);
            $this->db->where("DATE(date) <= DATE('$param2')", NULL, FALSE);
            // log_message("error", "Initial: $param1, Final: $param2");
            $this->db->where('branch_id',$this->session->userdata('branch_id'));
            $this->db->where('status', 1);
            return $this->db->count_all_results(); 
        }
        elseif($table == 'doctor_appointments')
        {
            $this->db->select("*");  
            $this->db->from("appointment"); 
            $this->db->where('clinic_id',$this->session->userdata('current_clinic'));
            $this->db->where('doctor_id',$param1);
            $this->db->where('status !=', 4);
            $this->db->where('status !=', 5);
            return $this->db->count_all_results(); 
        }
        //*******************
    }
    
    function GetFilteredData($table,$param1, $param2, $param3)
    {  
        $this->MakeQuery($table,$param1, $param2, $param3);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    
    function getArrays($table, $fetch_data,$param1, $param2, $param3)
    {
        if($table == 'sales')
        {
           return $this->get_sales($table, $fetch_data,$param1, $param2, $param3);
        }
        
        elseif($table == 'patients' )
        {
            return $this->get_patients($table, $fetch_data,$param1, $param2, $param3);
        }
        //*******************
    }
    
    function get_sales($table, $fetch_data,$param1, $param2, $param3)
    {
        $user_type    = $this->session->userdata('login_user_type');
        $permisos     = unserialize($this->session->userdata('permissions'));
        $data = array();  
        $n = 1;
           foreach($fetch_data as $row)  
           {   
                $sub_array = array();  
                $sub_array[] = $n++;
                $sub_array[] = $row['code'];

                setlocale(LC_TIME, "spanish");
                $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));				
                $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); 
                
                $sub_array[] = $Mes_Anyo;  
                
                if($row['estado'] == 1)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-warning label-inline">Credito</span>';  
                }
                elseif($row['estado'] == 2)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-success label-inline">Completado</span>';  
                }
                elseif($row['estado'] == 3)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-danger label-inline">Anulado</span>';  
                }
                elseif($row['estado'] == 4)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-info label-inline">Cambio</span>';  
                }
                elseif($row['estado'] == 5)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light- label-inline">Aplicado/Guardado</span>';  
                }
                elseif($row['estado'] == 6)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-danger label-inline">Error FEL</span>';  
                }
                
                if($row['nit'] != "")
                {
                    $sub_array[] = '<span class="text-warning"><b>'.$row['nit'].'</b></span>';  
                }
                else
                {
                    $sub_array[] = '<span class="text-warning"><b>Sin datos</b></span>';  
                }

                if($row['name'] != "")
                {
                    $sub_array[] = '<span class="text-info"><b>'.$row['name'].'</b></span>';  
                }
                else
                {
                    $sub_array[] = '<span class="text-info"><b>Sin datos</b></span>';  
                }
                
                if($row['metodo'] != "")
                {
                    $sub_array[] = '<span class="text-dark"><b>'.$row['metodo'].'</b></span>';
                }else {
                    $sub_array[] = '<span class="text-dark"><b>Sin datos</b></span>';
                    
                }
                $sub_array[] =  $this->crud_model->getName('admin', $row['responsable']);

                if($row['shipping']==1)
                {
                    $sub_array[] =  '<i class="fas fa-check text-warning"></i>';
                }elseif($row['shipping']==0)
                {
                    $sub_array[] =  '<i class="fa fa-times text-danger"></i>';
                }elseif($row['shipping']==2)
                {
                    $sub_array[] =  '<i class="fas fa-check text-success"></i>';
                }
                $moneda = $this->crud_model->get_info("moneda");
                $sub_array[] =  $moneda.number_format($row['total'],2,'.',',');
                
                $actions = '<div class="d-flex flex-shrink-0">';

                if($row['credito'] == 0){
                    $actions .= '
                    &nbsp;
                    <a href="'.base_url().'admin/detalles_venta/'.$row['code'].'"
                        id="kt_quick_panel_toggle"
                        class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm mr-1"
                        data-toggle="tooltip" title="Detalles" data-original-title="Detalless">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none"
                                    fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10">
                                    </circle>
                                    <rect fill="#000000" x="11" y="10" width="2" height="7"
                                        rx="1"></rect>
                                    <rect fill="#000000" x="11" y="7" width="2" height="2"
                                        rx="1"></rect>
                                </g>
                            </svg>
                        </span>
                    </a>';
                }else{
                    $actions .= '
                    &nbsp;
                    <a href="'.base_url().'admin/detalles_credito/'.$row['code'].'"
                        id="kt_quick_panel_toggle"
                        class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm mr-1"
                        data-toggle="tooltip" title="Detalles" data-original-title="Detalless">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none"
                                    fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10">
                                    </circle>
                                    <rect fill="#000000" x="11" y="10" width="2" height="7"
                                        rx="1"></rect>
                                    <rect fill="#000000" x="11" y="7" width="2" height="2"
                                        rx="1"></rect>
                                </g>
                            </svg>
                        </span>
                    </a>';
                }
                if($row['estado'] == 5) {
                    
                    $actions .= '
                    &nbsp;
                    <a href="'.base_url().'admin/venta_editar/'.$row['code'].'"
                        id="kt_quick_panel_toggle"
                        class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm mr-1"
                        data-toggle="tooltip" title="Detalles" data-original-title="Editar venta">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="#8950FC"></path>
                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="#8950FC"></path>
                            </svg>
                        </span>
                    </a>';
                }
                
                if($row['estado'] == 2 || ($row['credito'] == 1 && $row['estado'] == 1)){
                    
                    $actions .= '<a href="'.base_url().'admin/export_pdf/ventaFEL/'.$row['code'].'"
                            class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                            data-toggle="tooltip" title="" data-original-title="Imprimir">
                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none"
                                fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path
                                    d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z"
                                    fill="#000000" />
                                <rect fill="#000000" opacity="0.3" x="8" y="2" width="8"
                                    height="2" rx="1" />
                            </g>
                        </svg>
                            </span>
                        </a>
                    ';
                     $actions .= '<a href="'.base_url().'admin/export_pdf/voucher_sale/'.$row['code'].'"
                            class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                            data-toggle="tooltip" title="" data-original-title="Imprimir" target="_blank">
                    <span class="svg-icon svg-icon-primary svg-icon-2hx">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM12.5 18C12.5 17.4 12.6 17.5 12 17.5H8.5C7.9 17.5 8 17.4 8 18C8 18.6 7.9 18.5 8.5 18.5L12 18C12.6 18 12.5 18.6 12.5 18ZM16.5 13C16.5 12.4 16.6 12.5 16 12.5H8.5C7.9 12.5 8 12.4 8 13C8 13.6 7.9 13.5 8.5 13.5H15.5C16.1 13.5 16.5 13.6 16.5 13ZM12.5 8C12.5 7.4 12.6 7.5 12 7.5H8C7.4 7.5 7.5 7.4 7.5 8C7.5 8.6 7.4 8.5 8 8.5H12C12.6 8.5 12.5 8.6 12.5 8Z" fill="#000000"/>
                            <rect x="7" y="17" width="6" height="2" rx="1" fill="#000000"/>
                            <rect x="7" y="12" width="10" height="2" rx="1" fill="#000000"/>
                            <rect x="7" y="7" width="6" height="2" rx="1" fill="#000000"/>
                            <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="#000000"/>
                        </svg>
                    </span>
                </a>';
                }/*else{
                 $actions .= '<a href="'.base_url().'admin/export_pdf/recibo_venta/'.$row['code'].'"
                            class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                            data-toggle="tooltip" title="" data-original-title="Imprimir">
                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none"
                                fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path
                                    d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z"
                                    fill="#000000" />
                                <rect fill="#000000" opacity="0.3" x="8" y="2" width="8"
                                    height="2" rx="1" />
                            </g>
                        </svg>
                            </span>
                        </a>
                    ';
                }*/
               
                if ($user_type == 1 || $permisos['eliminar_ventas'] == 1) {
                    if( $row['FEL'] ==1){
                        $actions .= '
                        &nbsp;
                        <a href="javascript:void(0);" data-toggle="tooltip" title="Eliminar venta"
                            data-original-title="Anular y eliminar venta"
                            onclick="showModalAn(\''.base_url().'modal/popup/anulacionXML/'.$row['code'].'/'. $row["FEL"].'\');"
                            class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none"
                                        fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                                            fill="#000000" fill-rule="nonzero" />
                                        <path
                                            d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                            fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                            </span>
                        </a>';
                    }else{
                        /* $actions .= '
                &nbsp;
                <a href="javascript:void(0);" data-toggle="tooltip" title="Eliminar venta"
                    data-original-title="Eliminar venta"
                    onclick="executeExample(\''.$row['code'].'\')"
                    class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none"
                                fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path
                                    d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                                    fill="#000000" fill-rule="nonzero" />
                                <path
                                    d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                    fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                    </span>
                </a>'; */
                    }
                }
                $actions .= '</div>'; 

            $sub_array[] = $actions;

            $data[] = $sub_array;  
           }
           return $data;
    }
    

    function get_ventas_traslados($param1, $param2, $param3 = '') 
    {
        $moneda = $this->crud_model->get_info("moneda"); 
        $fetch_data = $this->make_venta_traslado_datatables($param1, $param2, $param3); 
        $data = array();  
        $n = 1;
        foreach($fetch_data as $row) {
            
            if($row->products_id_2 > 0) $prod = $this->db->get_where("products", array("products_id"=>$row->products_id_2))->row_array();
            else $prod = $this->db->get_where("products", array("products_id"=>$row->products_id))->row_array();
            $amount = $row->amount; $divisor = $row->amount;
            $divisor = $row->amount + $this->db->query("SELECT SUM(amount) AS total FROM product_details WHERE status = 1 AND products_id = '".$row->products_id."' AND activity_ref = '".$row->activity_ref."' AND product_details_id != '".$row->product_details_id."'")->row()->total;
            if ($row->products_id_2 > 0 && $prod['cnt_prod_matriz'] > 0) {
                $amount = $row->amount / $prod['cnt_prod_matriz'];
                $divisor = $divisor / $prod['cnt_prod_matriz'];
            }
            $chk_factura = $this->db->get_where("lotes", array("code"=>$row->code,"id_producto"=>$row->products_id))->row()->chk_factura;
            $product = $prod['name'];
            if ($row->iva) $product .= " (Afecto)";
            else $product .= " (Exento)";

            $price = $row->price; if ($divisor >= 1 && $row->description == 'Venta') $price = $row->price/$divisor;
            $total = $row->price; if ($row->description != 'Venta') $total = $row->price * $amount;
            if ($row->price == '') {
                $price = $moneda.number_format($row->cost,2,'.',',');
                $total = $amount * $row->cost;
            }
            // log_message("error", "Producto: $product, Cantidades: ".$row->cants.", Total: ".$row->total);
            
            
            if ($chk_factura == 1) {
                $sub_array = array();  
                $sub_array[] = $n++;
            $sub_array[] = $prod['code'];
            $sub_array[] = $product;
            $sub_array[] = date("d/m/Y", strtotime($row->date));
            $sub_array[] = $row->description.' - '.$row->activity_ref;
            $sub_array[] = $amount;
            $sub_array[] = '<div style="width: 100%; text-align: right">'.$moneda.number_format($price,2,'.',',').'</div>';
            $sub_array[] ='<div style="width: 100%; text-align: right">'.$moneda.number_format($total,2,'.',',').'</div>';
            $sub_array[] = $row->code;
            $sub_array[] = '<span class="label label-lg font-weight-bold label-light-success label-inline">SI</span>';
            $data[] = $sub_array;  
            }
            
            
        }  
        $output = array(  
            "draw"                      =>      intval($_POST["draw"]),  
            "recordsTotal"              =>      $this->get_all_venta_traslado_data($param1, $param2, $param3),  
            "recordsFiltered"           =>      $this->get_filtered_venta_traslado_data($param1, $param2, $param3),  
            "data"                      =>      $data  
        );  
        echo json_encode($output); 
    }

    function make_venta_traslado_datatables($param1, $param2, $param3 = '')
	{  
        $this->make_venta_traslado_query($param1, $param2, $param3);  
        if($_POST["length"] != -1) {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_venta_traslado_query($param1, $param2, $param3 = '')  
    {  
        log_message("error", "Like: $param3");
        $branch_id = $this->session->userdata('branch_id');
        log_message('error','branch ---'.$branch_id);
        $this->db->select('d.*, GROUP_CONCAT(DISTINCT(d.amount)) AS cants, SUM(d.amount) AS total'); 
        $this->db->from('product_details AS d');
        $this->db->join('lotes AS l', 'd.code = l.code');
        if ($param3 != '') {
            $this->db->like('d.description', $param3);
        } else {
            $this->db->group_start();
            $this->db->where('d.description', 'Venta');
            $this->db->or_like('d.description', 'Traslado', 'after');
            $this->db->group_end();
        }
        $this->db->where("DATE(d.date) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(d.date) <= DATE('$param2')", NULL, FALSE);
        $this->db->where('d.type', 0);
        $this->db->where('d.status', 1);
        $this->db->where('d.estado', 1);
        $this->db->where('d.branch_id', $branch_id);
        $this->db->where('l.chk_factura', 1);
        
        if($_POST["search"]["value"] != '') {
            $this->db->group_start();
            $this->db->like("d.description", $_POST["search"]["value"]);
            $this->db->or_like("d.activity_ref", $_POST["search"]["value"]);  
            $this->db->or_like("d.code", $_POST["search"]["value"]);  
            $this->db->group_end();
        } 
        $this->db->group_by('d.products_id');
        $this->db->group_by('d.products_id_2');
        $this->db->group_by('d.activity_ref');
        
        if(isset($_POST["order"])) {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        } else {  
            $this->db->order_by('d.date', 'DESC');  
        }  
    }
    
    function get_all_venta_traslado_data($param1, $param2, $param3 = '')  
    {
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('d.*, GROUP_CONCAT(DISTINCT(d.amount)) AS cants, SUM(d.amount) AS total'); 
        $this->db->from('product_details AS d');
        $this->db->join('lotes AS l', 'd.code = l.code');
        if ($param3 != '') {
            $this->db->like('d.description', $param3);
        } else {
            $this->db->group_start();
            $this->db->where('d.description', 'Venta');
            $this->db->or_like('d.description', 'Traslado', 'after');
            $this->db->group_end();
        }
        $this->db->where("DATE(d.date) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(d.date) <= DATE('$param2')", NULL, FALSE);
        $this->db->where('d.type', 0);
        $this->db->where('d.status', 1);
        $this->db->where('d.estado', 1);
        $this->db->where('d.branch_id', $branch_id);
        $this->db->where('l.chk_factura', 1);
        $this->db->group_by('d.products_id');
        $this->db->group_by('d.products_id_2');
        $this->db->group_by('d.activity_ref');
        return $this->db->count_all_results();  
    }
    
    function get_filtered_venta_traslado_data($param1, $param2, $param3 = '')
    {  
        $this->make_venta_traslado_query($param1, $param2, $param3);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    
    function get_ventas_producto($param1, $param2, $param3 = '')
    {
        $moneda = $this->crud_model->get_info("moneda"); 
        $fetch_data = $this->make_ventas_producto_datatables($param1, $param2, $param3); 
        $data = array();  
        $n = 1;
        foreach($fetch_data as $row) {
            $sub_array = array(); $product = ''; $amount = ''; $price = ''; $total = ''; $client = "Consumidor Final"; $vendedor = '';
            log_message("error", "Client ID: ".$row->client_id);
            if($row->client_id) {
                $client = $this->crud_model->getName("client", $row->client_id);
            }
            $vendedor = $this->crud_model->getName("admin", $row->responsable);
            /*if ($row->user_sale != '') {
                $vendedor = $this->crud_model->getName("admin", $row->user_sale);
            } else {
                
            }*/
            if ($param3 != '') {
                if($row->products_id_2 > 0) $prod = $this->db->get_where("products", array("products_id"=>$row->products_id_2))->row_array();
                else $prod = $this->db->get_where("products", array("products_id"=>$row->products_id))->row_array();
                $product = $prod['code'].' '.$prod['name'];
                if ($row->iva) $product .= " (Afecto)";
                else $product .= " (Exento)";
                $amount = $row->amount;
                if ($row->products_id_2 > 0 && $prod['cnt_prod_matriz'] > 0) $amount = $row->amount / $prod['cnt_prod_matriz'];
                $price = $moneda.number_format($row->price,2,'.',',');
            } else {
                // log_message("error", "Product: ".$row->products);
                $prods = json_decode($row->products, true); $i = 1;
                $count = count($prods);
                foreach($prods as $pro){
                    // log_message("error", "Product: ".$pro['product']);
                    $prod = $this->db->get_where("products", array("products_id"=>$pro['product']))->row_array();
                    $amount .= $pro['amount'];
                    
                    $product .= $prod['code'].' '.$prod['name'];
                    if ($pro['iva']) $product .= " (Afecto)";
                    else $product .= " (Exento)";
                    
                    if ($row->my == '1') $price .= $moneda.number_format($pro['price_my'],2,'.',',');
                    elseif ($row->my == '2') $price .= $moneda.number_format($pro['price'],2,'.',',');
                    elseif ($row->my == '3') $price .= $moneda.number_format($pro['price_farma'],2,'.',',');
                    
                    if ($i<$count) {
                        $product .= " <br>";
                        $price .= " <br> ";
                        $amount .= " <br> ";
                    }
                    $i++;
                }
            }
            
            $sub_array[] = $n++;
            $sub_array[] = $product;
            $sub_array[] = $row->code;
            $sub_array[] = $vendedor;
            $sub_array[] = $client;
            $sub_array[] = date("d/m/Y", strtotime($row->date));
            $sub_array[] = $amount;
            $sub_array[] = '<div class="text-right" style="width: 100%;">'.$price.'</div>';
            $sub_array[] = '<div class="text-right" style="width: 100%;">'.$moneda.number_format($row->total,2,'.',',').'</div>';
            
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"            => intval($_POST["draw"]),  
            "recordsTotal"    => $this->get_all_ventas_producto_data($param1, $param2, $param3),  
            "recordsFiltered" => $this->get_filtered_ventas_producto_data($param1, $param2, $param3),  
            "data"            => $data  
        );  
        echo json_encode($output); 
    }

    function make_ventas_producto_datatables($param1, $param2, $param3 = '')
	{  
        $this->make_ventas_producto_query($param1, $param2, $param3);  
        if($_POST["length"] != -1) {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_ventas_producto_query($param1, $param2, $param3 = '')  
    {  
        $branch_id = $this->session->userdata('branch_id');
        if ($param3 != '') {
            $this->db->select('s.date, s.code, s.client_id, s.responsable, s.user_sale, SUM(d.amount) AS amount, SUM(IFNULL((d.price/d.amount),d.cost)) AS price, SUM(d.amount * IFNULL((d.price/d.amount), d.cost)) AS total, s.my, d.products_id, d.products_id_2'); 
            $this->db->from('sales AS s');
            $this->db->join('product_details AS d', 's.code = d.activity_ref');
            $this->db->where('d.products_id', $param3);
            $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
            $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
            $this->db->where('s.branch_id', $branch_id);
            $this->db->where('s.status', 1);
            $this->db->where_in('s.estado', array(1,2));
            $this->db->where('d.status', 1);
        } else {
            $this->db->select('s.date, s.code, s.client_id, s.num_products AS amount, s.total, s.my, s.products, s.responsable, s.user_sale'); 
            $this->db->from('sales AS s');
            $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
            $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
            $this->db->where('s.branch_id', $branch_id);
            $this->db->where('s.status', 1);
            $this->db->where_in('s.estado', array(1,2));
        }
        
        if($_POST["search"]["value"] != '') {
            $this->db->like("s.code", $_POST["search"]["value"]); 
        }
        if ($param3 != '') {
            $this->db->group_by('d.activity_ref');
            $this->db->group_by('d.products_id_2');
        }
        
        $this->db->order_by('s.date', 'DESC');  
        if(isset($_POST["order"])) {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        }
    }
    
    function get_all_ventas_producto_data($param1, $param2, $param3 = '')  
    {
        $branch_id = $this->session->userdata('branch_id');
        if ($param3 != '') {
            $this->db->select('s.date, s.code, s.client_id, s.responsable, s.user_sale, SUM(d.amount) AS amount, SUM(IFNULL(d.price,d.cost)) AS price, SUM(d.amount * IFNULL(d.price, d.cost)) AS total, s.my, d.products_id, d.products_id_2'); 
            $this->db->from('sales AS s');
            $this->db->join('product_details AS d', 's.code = d.activity_ref');
            $this->db->where('d.products_id', $param3);
            $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
            $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
            $this->db->where('s.branch_id', $branch_id);
            $this->db->where('s.status', 1);
            $this->db->where_in('s.estado', array(1,2));
            $this->db->where('d.status', 1);
            $this->db->group_by('d.activity_ref');
            $this->db->group_by('d.products_id_2');
        } else {
            $this->db->select('s.date, s.code, s.client_id, s.num_products AS amount, s.total, s.my, s.products, s.responsable, s.user_sale'); 
            $this->db->from('sales AS s');
            $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
            $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
            $this->db->where('s.branch_id', $branch_id);
            $this->db->where('s.status', 1);
            $this->db->where_in('s.estado', array(1,2));
        }
        return $this->db->count_all_results();  
    }
    
    function get_filtered_ventas_producto_data($param1, $param2, $param3 = '')
    {  
        $this->make_ventas_producto_query($param1, $param2, $param3);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    
    function get_ventas_marca($param1, $param2, $param3 = '')
    {
        $moneda = $this->crud_model->get_info("moneda"); 
        $fetch_data = $this->make_ventas_marca_datatables($param1, $param2, $param3); 
        $data = array();  
        $n = 1;
        foreach($fetch_data as $row) {
            $sub_array = array(); $mark = ''; $price = ''; $product = ''; $amount = ''; $total = ''; $client = "Consumidor Final"; $vendedor = '';
            if($row->client_id) {
                $client = $this->crud_model->getName("client", $row->client_id);
            }
            if ($row->user_sale) {
                $vendedor = $this->crud_model->getName("admin", $row->user_sale);
            } else {
                $vendedor = $this->crud_model->getName("admin", $row->responsable);
            }
            
            if ($param3 != '') {
                if($row->products_id_2 > 0) $prod = $this->db->get_where("products", array("products_id"=>$row->products_id_2))->row_array();
                else $prod = $this->db->get_where("products", array("products_id"=>$row->products_id))->row_array();
                log_message("error", "Marca: ".$row->mark);
                $mark = $this->db->get_where('mark', array('mark_id'=>$row->mark))->row()->name;
                $product = $prod['code'].' '.$prod['name'];
                if ($row->iva) $product .= " (Afecto)";
                else $product .= " (Exento)";
                $amount = $row->amount;
                if ($row->products_id_2 > 0 && $prod['cnt_prod_matriz'] > 0) $amount = $row->amount / $prod['cnt_prod_matriz'];
                $price = $moneda.number_format($row->price,2,'.',',');
            } else {
                // log_message("error", "Product: ".$row->products);
                $prods = json_decode($row->products, true); $i = 1;
                $count = count($prods);
                foreach($prods as $pro){
                    // log_message("error", "Product: ".$pro['product']);
                    $prod = $this->db->get_where("products", array("products_id"=>$pro['product']))->row_array();
                    $ma = $this->db->get_where("mark", array("mark_id"=>$prod['mark']))->row_array();
                    $mark .= $ma['name'].'.';
                    $product .= $prod['code'].' '.$prod['name'];
                    if ($pro['iva']) $product .= " (Afecto)";
                    else $product .= " (Exento)";
                    
                    $amount .= $pro['amount'];
                    if ($row->my == '1') $price .= $moneda.number_format($pro['price_my'],2,'.',',');
                    elseif ($row->my == '2') $price .= $moneda.number_format($pro['price'],2,'.',',');
                    elseif ($row->my == '3') $price .= $moneda.number_format($pro['price_farma'],2,'.',',');
                    
                    if ($i<$count) {
                        $product .= " <br>";
                        $mark .= " <br>";
                        $price .= " <br> ";
                        $amount .= " <br> ";
                    }
                    $i++;
                }
            }
            
            $sub_array[] = $n++;
            $sub_array[] = date("d/m/Y", strtotime($row->date));
            $sub_array[] = $mark;
            $sub_array[] = $product;
            $sub_array[] = $row->code;
            $sub_array[] = $vendedor;
            $sub_array[] = $client;
            $sub_array[] = $amount;
            $sub_array[] = $price;
            $sub_array[] = '<div class="text-right" style="width: 100%;">'.$moneda.number_format($row->total,2,'.',',').'</div>';
            
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"            => intval($_POST["draw"]),  
            "recordsTotal"    => $this->get_all_ventas_marca_data($param1, $param2, $param3),  
            "recordsFiltered" => $this->get_filtered_ventas_marca_data($param1, $param2, $param3),  
            "data"            => $data  
        );  
        echo json_encode($output); 
    }

    function make_ventas_marca_datatables($param1, $param2, $param3 = '')
	{  
        $this->make_ventas_marca_query($param1, $param2, $param3);  
        if($_POST["length"] != -1) {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_ventas_marca_query($param1, $param2, $param3 = '')  
    {  
        $branch_id = $this->session->userdata('branch_id');
        log_message("error", "Mark ID: $param3");
        if ($param3 != '') {
            $this->db->select('s.date, s.code, s.client_id, s.responsable, s.user_sale, SUM(d.amount) AS amount, SUM(IFNULL((d.price/d.amount),d.cost)) AS price, SUM(d.amount * IFNULL((d.price/d.amount), d.cost)) AS total, s.my, p.mark, d.products_id, d.products_id_2'); 
            $this->db->from('sales AS s');
            $this->db->join('product_details AS d', 's.code = d.activity_ref');
            $this->db->join('products AS p', 'd.products_id = p.products_id');
            $this->db->where('p.mark', $param3);
            $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
            $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
            $this->db->where('s.branch_id', $branch_id);
            $this->db->where('s.status', 1);
            $this->db->where_in('s.estado', array(1,2));
            $this->db->where('d.status', 1);
        } else {
            $this->db->select('s.date, s.code, s.client_id, s.num_products AS amount, s.total, s.my, s.products, s.responsable, s.user_sale'); 
            $this->db->from('sales AS s');
            $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
            $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
            $this->db->where('s.branch_id', $branch_id);
            $this->db->where('s.status', 1);
            $this->db->where_in('s.estado', array(1,2));
        }
        
        if($_POST["search"]["value"] != '') {
            $this->db->like("s.code", $_POST["search"]["value"]); 
        }
        if ($param3 != '') {
            $this->db->group_by('d.activity_ref');
            $this->db->group_by('d.products_id_2');
        }
        
        if(isset($_POST["order"])) {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        } else {  
            $this->db->order_by('s.date', 'DESC');  
        }  
    }
    
    function get_all_ventas_marca_data($param1, $param2, $param3 = '')  
    {
        $branch_id = $this->session->userdata('branch_id');
        if ($param3 != '') {
            $this->db->select('s.date, s.code, s.client_id, s.responsable, s.user_sale, SUM(d.amount) AS amount, SUM(IFNULL(d.price,d.cost)) AS price, SUM(d.amount * IFNULL(d.price, d.cost)) AS total, s.my, p.mark, d.products_id, d.products_id_2'); 
            $this->db->from('sales AS s');
            $this->db->join('product_details AS d', 's.code = d.activity_ref');
            $this->db->join('products AS p', 'd.products_id = p.products_id');
            $this->db->where('p.mark', $param3);
            $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
            $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
            $this->db->where('s.branch_id', $branch_id);
            $this->db->where('s.status', 1);
            $this->db->where_in('s.estado', array(1,2));
            $this->db->where('d.status', 1);
            $this->db->group_by('d.activity_ref');
            $this->db->group_by('d.products_id_2');
        } else {
            $this->db->select('s.date, s.code, s.client_id, s.responsable, s.user_sale, s.num_products AS amount, s.total, s.my, s.products'); 
            $this->db->from('sales AS s');
            $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
            $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
            $this->db->where('s.branch_id', $branch_id);
            $this->db->where('s.status', 1);
            $this->db->where_in('s.estado', array(1,2));
        }
        return $this->db->count_all_results();  
    }
    
    function get_filtered_ventas_marca_data($param1, $param2, $param3 = '')
    {  
        $this->make_ventas_marca_query($param1, $param2, $param3);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    
    function get_ventas_afectas($param1, $param2)
    {
        $moneda = $this->crud_model->get_info("moneda"); 
        $fetch_data = $this->make_ventas_afectas_datatables($param1, $param2); 
        $data = array();  
        $n = 1;
        foreach($fetch_data as $row) {
            $sub_array = array(); $product = ''; $amount = ''; $price = 0; $price_text = ''; $total = 0; $client = "Consumidor Final";
            if($row->client_id) {
                $client = $this->crud_model->getName("client", $row->client_id);
            }
            
            // log_message("error", "Product: ".$row->products);
            $prods = json_decode($row->products, true); $i = 1;
            $count = count($prods);
            foreach($prods as $pro){
                // log_message("error", "Product: ".$pro['product']);
                if ($pro['iva']) {
                    $prod = $this->db->get_where("products", array("products_id"=>$pro['product']))->row_array();
                    $product .= $prod['code'].' '.$prod['name']." (Afecto)";
                    if ($row->my == '1') $price = $pro['price_my'];
                    elseif ($row->my == '2') $price = $pro['price'];
                    elseif ($row->my == '3') $price = $pro['price_farma'];
                    $price_text .= $price;
                    $total += ($pro['amount'] * $price);
                    $amount .= $pro['amount'];
                    if ($i<$count) {
                        $product .= " <br>";
                        $price_text .= " <br>";
                        $amount .= " <br>";
                    }
                }
                $i++;
            }
            
            $sub_array[] = $n++;
            $sub_array[] = date("d/m/Y", strtotime($row->date));
            $sub_array[] = $product;
            $sub_array[] = $row->code;
            $sub_array[] = $client;
            $sub_array[] = $amount;
            $sub_array[] = $price_text;
            $sub_array[] = '<div class="text-right" style="width: 100%;">'.$moneda.number_format($total,2,'.',',').'</div>';
            
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"            => intval($_POST["draw"]),  
            "recordsTotal"    => $this->get_all_ventas_afectas_data($param1, $param2),  
            "recordsFiltered" => $this->get_filtered_ventas_afectas_data($param1, $param2),  
            "data"            => $data  
        );  
        echo json_encode($output); 
    }

    function make_ventas_afectas_datatables($param1, $param2)
	{  
        $this->make_ventas_afectas_query($param1, $param2);  
        if($_POST["length"] != -1) {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_ventas_afectas_query($param1, $param2)  
    {  
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('s.date, s.code, s.client_id, SUM(d.amount) AS amount, SUM(IFNULL(d.price,d.cost)) AS price, SUM(d.amount * IFNULL(d.price, d.cost)) AS total, s.my, s.products'); 
        $this->db->from('sales AS s');
        $this->db->join('product_details AS d', 's.code = d.activity_ref');
        $this->db->where('d.iva', 1);
        $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
        $this->db->where('s.branch_id', $branch_id);
        $this->db->where('s.status', 1);
        $this->db->where_in('s.estado', array(1,2));
        $this->db->where('d.status', 1);
        
        if($_POST["search"]["value"] != '') {
            $this->db->like("s.code", $_POST["search"]["value"]); 
        }
        $this->db->group_by('d.activity_ref');
        
        if(isset($_POST["order"])) {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        } else {  
            $this->db->order_by('s.date', 'DESC');  
        }  
    }
    
    function get_all_ventas_afectas_data($param1, $param2)  
    {
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('s.date, s.code, SUM(d.amount) AS amount, SUM(IFNULL(d.price,d.cost)) AS price, SUM(d.amount * IFNULL(d.price, d.cost)) AS total, s.my, s.products'); 
        $this->db->from('sales AS s');
        $this->db->join('product_details AS d', 's.code = d.activity_ref');
        $this->db->where('d.iva', 1);
        $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
        $this->db->where('s.branch_id', $branch_id);
        $this->db->where('s.status', 1);
        $this->db->where_in('s.estado', array(1,2));
        $this->db->where('d.status', 1);
        $this->db->group_by('d.activity_ref');
        return $this->db->count_all_results();  
    }
    
    function get_filtered_ventas_afectas_data($param1, $param2)
    {  
        $this->make_ventas_afectas_query($param1, $param2);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    
    function get_ventas_exentas($param1, $param2)
    {
        $moneda = $this->crud_model->get_info("moneda"); 
        $fetch_data = $this->make_ventas_exentas_datatables($param1, $param2); 
        $data = array();  
        $n = 1;
        foreach($fetch_data as $row) {
            $sub_array = array(); $product = ''; $amount = ''; $price = 0; $price_text = ''; $total = 0; $client = "Consumidor Final";
            if($row->client_id) {
                $client = $this->crud_model->getName("client", $row->client_id);
            }
            
            // log_message("error", "Product: ".$row->products);
            $prods = json_decode($row->products, true); $i = 1;
            $count = count($prods);
            foreach($prods as $pro){
                // log_message("error", "Product: ".$pro['product']);
                if (!$pro['iva']) {
                    $prod = $this->db->get_where("products", array("products_id"=>$pro['product']))->row_array();
                    $product .= $prod['code'].' '.$prod['name']." (Exento)";
                    if ($row->my == '1') $price = $pro['price_my'];
                    elseif ($row->my == '2') $price = $pro['price'];
                    elseif ($row->my == '3') $price = $pro['price_farma'];
                    $price_text .= $price;
                    $total += ($pro['amount'] * $price);
                    log_message("error", "Amount: ".$pro['amount'].", precio: $price");
                    $amount .= $pro['amount'];
                    if ($i<$count) {
                        $product .= " <br>";
                        $price_text .= " <br>";
                        $amount .= " <br>";
                    }
                }
                $i++;
            }
            
            log_message("error", "Total: $total");
            $sub_array[] = $n++;
            $sub_array[] = date("d/m/Y", strtotime($row->date));
            $sub_array[] = $product;
            $sub_array[] = $row->code;
            $sub_array[] = $client;
            $sub_array[] = $amount;
            $sub_array[] = $price_text;
            $sub_array[] = '<div class="text-right" style="width: 100%;">'.$moneda.number_format($total,2,'.',',').'</div>';
            
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"            => intval($_POST["draw"]),  
            "recordsTotal"    => $this->get_all_ventas_exentas_data($param1, $param2),  
            "recordsFiltered" => $this->get_filtered_ventas_exentas_data($param1, $param2),  
            "data"            => $data  
        );  
        echo json_encode($output); 
    }

    function make_ventas_exentas_datatables($param1, $param2)
	{  
        $this->make_ventas_exentas_query($param1, $param2);  
        if($_POST["length"] != -1) {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_ventas_exentas_query($param1, $param2)  
    {  
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('s.date, s.code, s.client_id, SUM(d.amount) AS amount, SUM(IFNULL(d.price,d.cost)) AS price, SUM(d.amount * IFNULL(d.price, d.cost)) AS total, s.my, s.products'); 
        $this->db->from('sales AS s');
        $this->db->join('product_details AS d', 's.code = d.activity_ref');
        $this->db->group_start();
        $this->db->where('d.iva', 0);
        $this->db->or_where('d.iva IS NULL', NULL, FALSE);
        $this->db->or_where('d.iva', '');
        $this->db->group_end();
        $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
        $this->db->where('s.branch_id', $branch_id);
        $this->db->where('s.status', 1);
        $this->db->where_in('s.estado', array(1,2));
        $this->db->where('d.status', 1);
        
        if($_POST["search"]["value"] != '') {
            $this->db->like("s.code", $_POST["search"]["value"]); 
        }
        $this->db->group_by('d.activity_ref');
        
        if(isset($_POST["order"])) {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        } else {  
            $this->db->order_by('s.date', 'DESC');  
        }  
    }
    
    function get_all_ventas_exentas_data($param1, $param2)  
    {
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('s.date, s.code, SUM(d.amount) AS amount, SUM(IFNULL(d.price,d.cost)) AS price, SUM(d.amount * IFNULL(d.price, d.cost)) AS total, s.my, s.products'); 
        $this->db->from('sales AS s');
        $this->db->join('product_details AS d', 's.code = d.activity_ref');
        $this->db->group_start();
        $this->db->where('d.iva', 0);
        $this->db->or_where('d.iva IS NULL', NULL, FALSE);
        $this->db->or_where('d.iva', '');
        $this->db->group_end();
        $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
        $this->db->where('s.branch_id', $branch_id);
        $this->db->where('s.status', 1);
        $this->db->where_in('s.estado', array(1,2));
        $this->db->where('d.status', 1);
        $this->db->group_by('d.activity_ref');
        return $this->db->count_all_results();  
    }
    
    function get_filtered_ventas_exentas_data($param1, $param2)
    {  
        $this->make_ventas_exentas_query($param1, $param2);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    
    function get_cuentas_por_cobrar($param1, $param2, $param3 = '')
    {
        $moneda     = $this->crud_model->get_info("moneda"); 
        $user_type  = $this->session->userdata('login_user_type');
        $permisos   = unserialize($this->session->userdata('permissions'));
        
        $fetch_data = $this->make_cuentas_por_cobrar_datatables($param1, $param2, $param3); 
        $data = array();  
        $n = 1;
        foreach($fetch_data as $row) {
            $sub_array = array(); $product = ''; $amount = ''; $price = ''; $total = ''; $cliente = "Consumidor Final"; $pendiente = $row->total; $actions = '';
            
            if ($row->client_id > 0) {
                $cli = $this->db->get_where('client', array('client_id'=>$row->client_id))->row_array();
                $cliente = trim($cli['nit']).' - '.trim($cli['name']).' '.trim($cli['last_name']);
            } 
            $pagos = $this->db->query("SELECT SUM(IFNULL(amount,0)) AS total FROM credit_details WHERE sales_id = '".$row->sales_id."' AND status = 1")->row()->total;
            if ($pagos > 0) $pendiente -= $pagos;
            
            $sub_array[] = $n++;
            $sub_array[] = $row->code;
            $sub_array[] = $cliente;
            $sub_array[] = date("d/m/Y", strtotime($row->date));
            $sub_array[] = '<div class="text-right" style="width: 100%;">'.$moneda.number_format($pendiente,2,'.',',').'</div>';
            $sub_array[] = '<div class="text-right" style="width: 100%;">'.$moneda.number_format($row->total,2,'.',',').'</div>';
            $actions .= '<div class="d-flex flex-shrink-0">';
            if ($user_type == 1 || $permisos['ver_ventas'] == 1) {
                log_message("error", "Permiso de creditos");
                $actions .= '&nbsp;
                    <a href="'.base_url().'admin/detalles_credito/'.$row->code.'"
                        id="kt_quick_panel_toggle"
                        class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                        data-toggle="tooltip" title="" data-original-title="Detalles">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none"
                                    fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"></rect>
                                    <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10">
                                    </circle>
                                    <rect fill="#000000" x="11" y="10" width="2" height="7"
                                        rx="1"></rect>
                                    <rect fill="#000000" x="11" y="7" width="2" height="2"
                                        rx="1"></rect>
                                </g>
                            </svg>
                        </span>
                    </a>';
            }
            $actions .= '</div>';
            $sub_array[] = $actions;
            
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"            => intval($_POST["draw"]),  
            "recordsTotal"    => $this->get_all_cuentas_por_cobrar_data($param1, $param2, $param3),  
            "recordsFiltered" => $this->get_filtered_cuentas_por_cobrar_data($param1, $param2, $param3),  
            "data"            => $data  
        );  
        echo json_encode($output); 
    }

    function make_cuentas_por_cobrar_datatables($param1, $param2, $param3 = '')
	{  
        $this->make_cuentas_por_cobrar_query($param1, $param2, $param3);  
        if($_POST["length"] != -1) {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_cuentas_por_cobrar_query($param1, $param2, $param3 = '')  
    {  
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('s.*'); 
        $this->db->from('sales AS s');
        $this->db->join('credit_details AS c', 's.sales_id = c.sales_id', 'left');
        if($param3 != '') $this->db->where('s.client_id', $param3);
        $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
        $this->db->where('s.branch_id', $branch_id);
        $this->db->where('s.estado', 1);
        $this->db->group_start();
        $this->db->where('s.status', 1);
        $this->db->or_where('c.status', 1);
        $this->db->group_end();
        
        if($_POST["search"]["value"] != '') {
            $this->db->like("s.code", $_POST["search"]["value"]); 
        }
        $this->db->group_by('s.sales_id');
        
        if(isset($_POST["order"])) {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        } else {  
            $this->db->order_by('s.date', 'DESC');  
        }  
    }
    
    function get_all_cuentas_por_cobrar_data($param1, $param2, $param3 = '')  
    {
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('s.*'); 
        $this->db->from('sales AS s');
        $this->db->join('credit_details AS c', 's.sales_id = c.sales_id', 'left');
        if($param3 != '') $this->db->where('s.client_id', $param3);
        $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
        $this->db->where('s.branch_id', $branch_id);
        $this->db->where('s.estado', 1);
        $this->db->group_start();
        $this->db->where('s.status', 1);
        $this->db->or_where('c.status', 1);
        $this->db->group_end();
        return $this->db->count_all_results();  
    }
    
    function get_filtered_cuentas_por_cobrar_data($param1, $param2, $param3 = '')
    {  
        $this->make_cuentas_por_cobrar_query($param1, $param2, $param3);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    function get_cuentas_por_pagar($param1, $param2, $param3 = '')
    {
        $moneda     = $this->crud_model->get_info("moneda"); 
        $user_type  = $this->session->userdata('login_user_type');
        $permisos   = unserialize($this->session->userdata('permissions'));
        
        $fetch_data = $this->make_cuentas_por_pagar_datatables($param1, $param2, $param3); 
        $data = array();  
        $n = 1;
        foreach($fetch_data as $row) {
            $sub_array = array(); $product = ''; $amount = ''; $price = ''; $total = ''; $provider = ''; $pendiente = $row->total; $actions = '';
            
            $provider = trim($row->provider_name);
            if($row->nit != '') $provider .= ' - '.trim($row->nit);
            // log_message("error", "Product: ".$row->products);
            $prods = json_decode($row->products, true); $i = 1;
            $count = count($prods);
            foreach($prods as $pro){
                // log_message("error", "Product: ".$pro['product']);
                $prod = $this->db->get_where("products", array("products_id"=>$pro['product']))->row_array();
                
                $product .= $prod['code'].' '.$prod['name'];
                if ($pro['iva']) $product .= " (Afecto)";
                else $product .= " (Exento)";
                
                if ($i<$count) $product .= " <br>";
                $i++;
            }
            $pagos = $this->db->query("SELECT SUM(IFNULL(amount,0)) AS total FROM credit_details WHERE sales_id = '".$row->shopping_id."' AND status = 1")->row()->total;
            if ($pagos > 0) $pendiente -= $pagos;
            
            $sub_array[] = $n++;
            $sub_array[] = $row->code;
            $sub_array[] = $provider;
            $sub_array[] = date("d/m/Y", strtotime($row->date));
            //$sub_array[] = $product;
            $sub_array[] = '<div class="text-right" style="width: 100%;">'.$moneda.number_format($pendiente,2,'.',',').'</div>';
            $sub_array[] = '<div class="text-right" style="width: 100%;">'.$moneda.number_format($row->total,2,'.',',').'</div>';
            $actions .= '<div class="d-flex flex-shrink-0">
                    &nbsp;
                    <a href="'.base_url().'admin/detalles_creditos_prov/'.$row->code.'"
                        id="kt_quick_panel_toggle"
                        class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                        data-toggle="tooltip" title="" data-original-title="Ver credito con proveedor">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none"
                                    fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path
                                        d="M10.9,2 C11.4522847,2 11.9,2.44771525 11.9,3 C11.9,3.55228475 11.4522847,4 10.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,16 C20,15.4477153 20.4477153,15 21,15 C21.5522847,15 22,15.4477153 22,16 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L10.9,2 Z"
                                        fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                    <path
                                        d="M24.0690576,13.8973499 C24.0690576,13.1346331 24.2324969,10.1246259 21.8580869,7.73659596 C20.2600137,6.12944276 17.8683518,5.85068794 15.0081639,5.72356847 L15.0081639,1.83791555 C15.0081639,1.42370199 14.6723775,1.08791555 14.2581639,1.08791555 C14.0718537,1.08791555 13.892213,1.15726043 13.7542266,1.28244533 L7.24606818,7.18681951 C6.93929045,7.46513642 6.9162184,7.93944934 7.1945353,8.24622707 C7.20914339,8.26232899 7.22444472,8.27778811 7.24039592,8.29256062 L13.7485543,14.3198102 C14.0524605,14.6012598 14.5269852,14.5830551 14.8084348,14.2791489 C14.9368329,14.140506 15.0081639,13.9585047 15.0081639,13.7695393 L15.0081639,9.90761477 C16.8241562,9.95755456 18.1177196,10.0730665 19.2929978,10.4469645 C20.9778605,10.9829796 22.2816185,12.4994368 23.2042718,14.996336 L23.2043032,14.9963244 C23.313119,15.2908036 23.5938372,15.4863432 23.9077781,15.4863432 L24.0735976,15.4863432 C24.0735976,15.0278051 24.0690576,14.3014082 24.0690576,13.8973499 Z"
                                        fill="#000000" fill-rule="nonzero"
                                        transform="translate(15.536799, 8.287129) scale(-1, 1) translate(-15.536799, -8.287129) " />
                                </g>
                            </svg>
                        </span>
                    </a>
                </div>';
            $sub_array[] = $actions;
            
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"            => intval($_POST["draw"]),  
            "recordsTotal"    => $this->get_all_cuentas_por_pagar_data($param1, $param2, $param3),  
            "recordsFiltered" => $this->get_filtered_cuentas_por_pagar_data($param1, $param2, $param3),  
            "data"            => $data  
        );  
        echo json_encode($output); 
    }

    function make_cuentas_por_pagar_datatables($param1, $param2, $param3 = '')
	{  
        $this->make_cuentas_por_pagar_query($param1, $param2, $param3);  
        if($_POST["length"] != -1) {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_cuentas_por_pagar_query($param1, $param2, $param3 = '')  
    {  
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('s.*, p.nit, p.name AS provider_name'); 
        $this->db->from('shopping AS s');
        $this->db->join('credit_details AS c', 's.shopping_id = c.sales_id', 'left');
        $this->db->join('provider AS p', 'p.provider_id = s.provider', 'left');
        if($param3 != '') $this->db->where('s.provider', $param3);
        $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
        $this->db->where('s.branch_id', $branch_id);
        $this->db->where('s.credito', 1);
        $this->db->group_start();
        $this->db->where('s.status', 1);
        $this->db->or_where('c.status', 1);
        $this->db->group_end();
        
        if($_POST["search"]["value"] != '') {
            $this->db->like("s.code", $_POST["search"]["value"]); 
        }
        $this->db->group_by('s.shopping_id');
        
        if(isset($_POST["order"])) {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        }
        $this->db->order_by('p.name', 'ASC');
        $this->db->order_by('s.date', 'ASC');  
    }
    
    function get_all_cuentas_por_pagar_data($param1, $param2, $param3 = '')  
    {
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('s.*, p.name AS provider_name'); 
        $this->db->from('shopping AS s');
        $this->db->join('credit_details AS c', 's.shopping_id = c.sales_id', 'left');
        $this->db->join('provider AS p', 'p.provider_id = s.provider', 'left');
        if($param3 != '') $this->db->where('s.provider', $param3);
        $this->db->where("DATE(s.date) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(s.date) <= DATE('$param2')", NULL, FALSE);
        $this->db->where('s.branch_id', $branch_id);
        $this->db->where('s.credito', 1);
        $this->db->group_start();
        $this->db->where('s.status', 1);
        $this->db->or_where('c.status', 1);
        $this->db->group_end();
        $this->db->group_by('s.shopping_id');
        return $this->db->count_all_results();  
    }
    
    function get_filtered_cuentas_por_pagar_data($param1, $param2, $param3 = '')
    {  
        $this->make_cuentas_por_pagar_query($param1, $param2, $param3);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    function get_ventas_vendedor($param1, $param2, $param3 = '')
    {
        $moneda = $this->crud_model->get_info("moneda"); 
        $fetch_data = $this->make_ventas_vendedor_datatables($param1, $param2, $param3); 
        $data = array();  
        $n = 1;
        foreach($fetch_data as $row) {
            $sub_array = array(); $client = "Consumidor Final"; $vendedor = '';
            
            if($row->client_id) {
                $client = $this->crud_model->getName("client", $row->client_id);
            }
            $vendedor = $this->crud_model->getName("admin", $row->responsable);
            
            $sub_array[] = $n++;
            $sub_array[] = $vendedor;
            $sub_array[] = $row->code;
            $sub_array[] = $client;
            $sub_array[] = date("d/m/Y", strtotime($row->date));
            $sub_array[] = $row->num_products;
            $sub_array[] = '<div class="text-right" style="width: 100%;">'.$moneda.number_format($row->total,2,'.',',').'</div>';
            
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"            => intval($_POST["draw"]),  
            "recordsTotal"    => $this->get_all_ventas_vendedor_data($param1, $param2, $param3),  
            "recordsFiltered" => $this->get_filtered_ventas_vendedor_data($param1, $param2, $param3),  
            "data"            => $data  
        );  
        echo json_encode($output); 
    }

    function make_ventas_vendedor_datatables($param1, $param2, $param3 = '')
	{  
        $this->make_ventas_vendedor_query($param1, $param2, $param3);  
        if($_POST["length"] != -1) {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_ventas_vendedor_query($param1, $param2, $param3 = '')  
    {  
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('*'); 
        $this->db->from('sales');
        $this->db->where("DATE(date) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(date) <= DATE('$param2')", NULL, FALSE);
        $this->db->where('branch_id', $branch_id);
        $this->db->where('status', 1);
        $this->db->where_in('estado', array(1,2));
        if ($param3 != '') {
            $this->db->where('responsable', $param3);
        }
        
        if($_POST["search"]["value"] != '') {
            $this->db->like("code", $_POST["search"]["value"]); 
        }
        
        if(isset($_POST["order"])) {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        } else {  
            $this->db->order_by('date', 'DESC');  
        }  
    }
    
    function get_all_ventas_vendedor_data($param1, $param2, $param3 = '')  
    {
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('*'); 
        $this->db->from('sales');
        $this->db->where("DATE(date) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(date) <= DATE('$param2')", NULL, FALSE);
        $this->db->where('branch_id', $branch_id);
        $this->db->where('status', 1);
        $this->db->where_in('estado', array(1,2));
        if ($param3 != '') {
            $this->db->where('responsable', $param3);
        }
        return $this->db->count_all_results();  
    }
    
    function get_filtered_ventas_vendedor_data($param1, $param2, $param3 = '')
    {  
        $this->make_ventas_vendedor_query($param1, $param2, $param3);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    
    function get_clientes_por_usuario($param1, $param2, $param3 = '')
    {
        $fetch_data = $this->make_clientes_por_usuario_datatables($param1, $param2, $param3); 
        $data = array();  
        $n = 1;
        foreach($fetch_data as $row) {
            $sub_array = array();
            $usuario = $this->crud_model->getName("admin", $row->responsable);
            
            $sub_array[] = $n++;
            $sub_array[] = $usuario;
            $sub_array[] = $row->total_clientes;
            
            $data[] = $sub_array;  
        }  
        $output = array(  
            "draw"            => intval($_POST["draw"]),  
            "recordsTotal"    => $this->get_all_clientes_por_usuario_data($param1, $param2, $param3),  
            "recordsFiltered" => $this->get_filtered_clientes_por_usuario_data($param1, $param2, $param3),  
            "data"            => $data  
        );  
        echo json_encode($output); 
    }
    
    function make_clientes_por_usuario_datatables($param1, $param2, $param3 = '')
    {  
        $this->make_clientes_por_usuario_query($param1, $param2, $param3);  
        if($_POST["length"] != -1) {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_clientes_por_usuario_query($param1, $param2, $param3 = '')  
    {  
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('responsable, COUNT(*) as total_clientes'); 
        $this->db->from('client');
        $this->db->where("DATE(date) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(date) <= DATE('$param2')", NULL, FALSE);
        $this->db->where('branch_id', $branch_id);
        $this->db->where('status', 1);
        if ($param3 != '') {
            $this->db->where('responsable', $param3);
        }
        $this->db->group_by('responsable');
        
        if($_POST["search"]["value"] != '') {
            $this->db->like("responsable", $_POST["search"]["value"]); 
        }
        
        if(isset($_POST["order"])) {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        } else {  
            $this->db->order_by('total_clientes', 'DESC');  
        }  
    }
    
    function get_all_clientes_por_usuario_data($param1, $param2, $param3 = '')  
    {
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('responsable, COUNT(*) as total_clientes'); 
        $this->db->from('client');
        $this->db->where("DATE(date) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(date) <= DATE('$param2')", NULL, FALSE);
        $this->db->where('branch_id', $branch_id);
        $this->db->where('status', 1);
        if ($param3 != '') {
            $this->db->where('responsable', $param3);
        }
        $this->db->group_by('responsable');
        return $this->db->count_all_results();  
    }
    
    function get_filtered_clientes_por_usuario_data($param1, $param2, $param3 = '')
    {  
        $this->make_clientes_por_usuario_query($param1, $param2, $param3);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    
    function get_inventario()
    {
        $fetch_data = $this->make_inventario_datatables();  
        $tipo = $this->session->userdata('login_user_type');
           $data = array();  
           $n = 1;
           foreach($fetch_data as $row)  
           {  
                
                $sub_array = array();  
                
                $producto = $this->db->get_where('products',array('products_id'=>$row->products_id))->row();
                if($producto->presentation == 'Caja'){
                    $total = $this->crud_model->get_stock($producto->id_prod_matriz, $this->session->userdata('branch_id'));
                    $stock_inventory = ($total/$producto->cnt_prod_matriz);
                    
                    $total_Central = $this->crud_model->get_stock($producto->id_prod_matriz, 1);
                    $stock_inventory_central = ($total_Central/$producto->cnt_prod_matriz);                                                                            
                    
                    $tot_bodega= $this->crud_model->get_stock($producto->id_prod_matriz, 0);
                    $stock_bodega = ($tot_bodega/$producto->cnt_prod_matriz);
                }else{
                    $stock_inventory  = $this->crud_model->get_stock($row->products_id, $this->session->userdata('branch_id'));
                    $stock_inventory_central  = $this->crud_model->get_stock($row->products_id,1);                    
                   
                    $stock_bodega = $this->crud_model->get_stock($row->products_id, 0);
                }//aqui_voy 
                
                $sub_array[] = $n++;  
                $sub_array[] = $row->code;  
                $sub_array[] = $row->name;  
                $sub_array[] = '<div class="d-flex flex-shrink-0">&nbsp;
                    <a href="'.base_url().'admin/producto_detalle/'.$row->products_id.'" data-toggle="tooltip" data-original-title="Movimiento del producto" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
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
                    </a>&nbsp;
                    <a href="'.base_url().'admin/editar_producto/'.$row->products_id.'" data-toggle="tooltip" data-original-title="Editar producto" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="#8950FC" />
                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="#8950FC" />
                            </svg>
                        </span>
                    </a>&nbsp;
                    <a href="javascript:;" data-toggle="tooltip" data-original-title="Eliminar producto" onclick="eliminarProducto(\''.$row->products_id.'\')" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero" />
                                    <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3" />
                                </g>
                            </svg>
                        </span>
                    </a>
                </div>';
                
                $sucursales = $this->db->get_where('branch',array('status'=> 1))->row();
                
                $sub_array[] = '<span class="text-success"><b>'.($stock_inventory + $stock_bodega).'</b></span>';
                $sub_array[] = '<span class="text-success"><b>'.$stock_inventory.'</b></span>';
                $sub_array[] = '<span class="text-success"><b>'.$stock_bodega.'</b></span>';
               if($tipo==1){ $sub_array[] = $row->cost;} 
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($this->crud_model->last_price_buy($row->products_id),2,'.',',');  
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($row->farma,2,'.',','); 
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($row->precio_mayorista,2,'.',',');  
                if(($stock_inventory + $stock_bodega) > $row->alert)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-success label-inline">Disponible</span>';  
                }
                
                elseif(($stock_inventory + $stock_bodega) <= $row->alert && ($stock_inventory + $stock_bodega) > 0)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-warning label-inline">Alerta</span>';  
                }
                
                elseif(($stock_inventory + $stock_bodega) == 0)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-danger label-inline">Sin disponiblidad</span>';  
                }
                
                $sub_array[] = '<div class="d-flex flex-shrink-0">&nbsp;
                                    <a href="'.base_url().'admin/producto_detalle/'.$row->products_id.'" data-toggle="tooltip" data-original-title="Movimiento del producto" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
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
                                    </a>&nbsp;
                                    <a href="'.base_url().'admin/editar_producto/'.$row->products_id.'" data-toggle="tooltip" data-original-title="Editar producto" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="#8950FC" />
                                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="#8950FC" />
                                            </svg>
                                        </span>
                                    </a>&nbsp;
                                    <a href="javascript:;" data-toggle="tooltip" data-original-title="Eliminar producto" onclick="eliminarProducto(\''.$row->products_id.'\')" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero" />
                                                    <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3" />
                                                </g>
                                            </svg>
                                        </span>
                                    </a>
                                </div>';
                
                $data[] = $sub_array;  
           }  
                $output = array(  
                "draw"                      =>      intval($_POST["draw"]),  
                "recordsTotal"              =>      $this->get_all_inventario_data(),  
                "recordsFiltered"           =>      $this->get_filtered_inventario_data(),  
                "data"                      =>      $data  
           );  
           echo json_encode($output); 
    }
    
    
    
    function make_inventario_datatables()
	{  
        $this->make_inventario_query();  
        if($_POST["length"] != -1)  
        {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_inventario_query()  
    {  
        $this->db->select('*'); 
        $this->db->from('products');
        $this->db->where('status', 1);
        
       // $this->db->where('admin_id<>', $this->session->userdata('login_user_id'));
        
        
       if($_POST["search"]["value"] != '')  
       {  
           $this->db->group_start();
           $this->db->like("name", $_POST["search"]["value"]);
           $this->db->or_like("code", $_POST["search"]["value"]);  
           $this->db->group_end();
       }  
       if(isset($_POST["order"]))  
       {  
           $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
       } else {
            $this->db->order_by('name', 'ASC');
       }
       // $this->db->order_by('code', 'ASC');
       // $this->db->order_by('name', 'ASC');
    }
    
    function get_all_inventario_data()  
    {  
        $this->db->select('*'); 
        $this->db->from('products');
        $this->db->where('status', 1);
        //$this->db->where('admin_id<>', $this->session->userdata('login_user_id'));
        return $this->db->count_all_results();  
    }
    
    function get_filtered_inventario_data()
    {  
        $this->make_inventario_query();  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    

    
    
    function get_increase($param1, $param2)
    {
        $moneda = $this->crud_model->get_info("moneda");
        $fetch_data = $this->make_increase_datatables($param1, $param2);  
        $tipo = $this->session->userdata('login_user_type');
        $data = array();  
        $n = 1;
        foreach($fetch_data as $row)  
        {  
            $sub_array = array();
            $sub_array[] = $n++;  
            $sub_array[] = $row['code'];
            $sub_array[] = date("d/m/Y h:i a", strtotime($row['datetime']));
            $sub_array[] = $this->crud_model->getName("admin", $row['responsable']);
            $sub_array[] = $row['num_products'];
            $sub_array[] = $moneda.number_format($row['total'],2,'.',',');   
            $sub_array[] = '
                        <a href="'.base_url().'admin/ingreso_detalles/'.$row['code'].'" data-toggle="tooltip" data-original-title="Movimiento del producto" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
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
                        </a>&nbsp;
                        <a href="'.base_url().'admin/export_pdf/ingreso_detalles/'.$row['increase_id'].'"
                            class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                            data-toggle="tooltip" title="" data-original-title="Imprimir">
                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none"
                                        fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z"
                                            fill="#000000" />
                                        <rect fill="#000000" opacity="0.3" x="8" y="2" width="8"
                                            height="2" rx="1" />
                                    </g>
                                </svg>
                            </span>
                        </a>'; 
            
            $data[] = $sub_array;  
        }  
            
        $output = array(  
            "draw"                      =>      intval($_POST["draw"]),  
            "recordsTotal"              =>      $this->get_all_increase_data($param1, $param2),  
            "recordsFiltered"           =>      $this->get_filtered_increase_data($param1, $param2),  
            "data"                      =>      $data  
        );
        
        echo json_encode($output); 
    }
    
    function make_increase_datatables($param1, $param2)
	{  
        $this->make_increase_query($param1, $param2);  
        if($_POST["length"] != -1)  
        {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result_array();  
    }
    
    function make_increase_query($param1, $param2)  
    {  
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('*'); 
        $this->db->from('increase');
        $this->db->where('branch_id', $branch_id);
        $this->db->where('status', 1);
        $this->db->where("DATE(datetime) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(datetime) <= DATE('$param2')", NULL, FALSE);
        
        if($_POST["search"]["value"] != '')  
        {  
            $this->db->like("code", $_POST["search"]["value"]);  
        }  
        
        $this->db->order_by('datetime', 'DESC');
        
        if(isset($_POST["order"]))  
        {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        }  
        
        else  
        {  
            $this->db->order_by('increase_id', 'DESC');  
        }  
    }
    
    function get_all_increase_data($param1, $param2)  
    {  
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('*'); 
        $this->db->from('increase');
        $this->db->where('branch_id', $branch_id);
        $this->db->where('status', 1);
        $this->db->where("DATE(datetime) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(datetime) <= DATE('$param2')", NULL, FALSE);
        return $this->db->count_all_results();  
    }
    
    function get_filtered_increase_data($param1, $param2)
    {  
        $this->make_increase_query($param1, $param2);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    
    
    function get_losses($param1, $param2)
    {
        log_message("error", "Initial: $param1, Final: $param2");
        $moneda = $this->crud_model->get_info("moneda");
        $fetch_data = $this->make_losses_datatables($param1, $param2);  
        $tipo = $this->session->userdata('login_user_type');
        $data = array();  
        $n = 1;
        foreach($fetch_data as $row)  
        {  
            $sub_array = array();
            $sub_array[] = $n++;  
            $sub_array[] = $row->code;
            $sub_array[] = date("d/m/Y h:i a", strtotime($row->datetime));
            $sub_array[] = $this->crud_model->getName("admin", $row->responsable);
            $sub_array[] = $moneda.number_format($row->loss,2,'.',',');
            $sub_array[] = '
                        <a href="'.base_url().'admin/perdida_detalles/'.$row->code.'" data-toggle="tooltip" data-original-title="Movimiento del producto" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
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
                        </a>&nbsp;
                        <a href="'.base_url().'admin/export_pdf/perdidas_detalles/'.$row->losse_returns_id.'"
                            class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                            data-toggle="tooltip" title="" data-original-title="Imprimir">
                            <span class="svg-icon svg-icon-primary svg-icon-2x">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                    height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none"
                                        fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path
                                            d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z"
                                            fill="#000000" />
                                        <rect fill="#000000" opacity="0.3" x="8" y="2" width="8"
                                            height="2" rx="1" />
                                    </g>
                                </svg>
                            </span>
                        </a>
                    ';
            
            $data[] = $sub_array;  
        }  
            
        $output = array(  
            "draw"                      =>      intval($_POST["draw"]),  
            "recordsTotal"              =>      $this->get_all_losses_data($param1, $param2),  
            "recordsFiltered"           =>      $this->get_filtered_losses_data($param1, $param2),  
            "data"                      =>      $data  
        );
        
        echo json_encode($output); 
    }
    
    function make_losses_datatables($param1, $param2)
	{  
        $this->make_losses_query($param1, $param2);
        log_message("error", "Lenght: ".$_POST['length'].", Start: ".$_POST['start']);
        if($_POST["length"] != -1)  
        {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_losses_query($param1, $param2)  
    {  
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('*'); 
        $this->db->from('losse_returns');
        $this->db->where('branch_id', $branch_id);
        $this->db->where('status', 1);
        $this->db->where('concept', 'Pérdida');
        $this->db->where("DATE(datetime) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(datetime) <= DATE('$param2')", NULL, FALSE);
        
        if($_POST["search"]["value"] != '')  
        {  
            $this->db->like("code", $_POST["search"]["value"]);  
        }  
        
        $this->db->order_by('datetime', 'DESC');
        
        if(isset($_POST["order"]))  
        {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        }  
        
        else  
        {  
            $this->db->order_by('losse_returns_id', 'DESC');  
        }  
    }
    
    function get_all_losses_data($param1, $param2)  
    {  
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('*'); 
        $this->db->from('losse_returns');
        $this->db->where('branch_id', $branch_id);
        $this->db->where('status', 1);
        $this->db->where('concept', 'Pérdida');
        $this->db->where("DATE(datetime) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(datetime) <= DATE('$param2')", NULL, FALSE);
        return $this->db->count_all_results();  
    }
    
    function get_filtered_losses_data($param1, $param2)
    {  
        $this->make_losses_query($param1, $param2);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    




    function get_vencidos($param1, $param2)
    {
        $moneda = $this->crud_model->get_info("moneda");
        $fetch_data = $this->make_expired_datatables($param1, $param2);  
        $tipo = $this->session->userdata('login_user_type');
        $data = array();  
        $n = 1;
        foreach($fetch_data as $row)  
        {
            $total = $row->existencia * $row->precio;
            $prod = $this->db->get_where('products', array('products_id'=>$row->id_producto))->row_array();
            $sub_array = array();
            $sub_array[] = $n++;  
            $sub_array[] = $row->code;
            $sub_array[] = $prod['code'];
            $sub_array[] = $prod['name'];
            $sub_array[] = date("d/m/Y", strtotime($row->fecha));
            $sub_array[] = date("d/m/Y", strtotime($row->fecha_vencimiento));
            $sub_array[] = number_format($row->cantidad,0,'','');
            $sub_array[] = number_format($row->existencia,0,'','');
            $sub_array[] = $moneda.number_format($row->precio,2,'.',',');
            $sub_array[] = $moneda.number_format($total,2,'.',',');
            
            $data[] = $sub_array;  
        }  
            
        $output = array(  
            "draw"                      =>      intval($_POST["draw"]),  
            "recordsTotal"              =>      $this->get_all_expired_data($param1, $param2),  
            "recordsFiltered"           =>      $this->get_filtered_expired_data($param1, $param2),  
            "data"                      =>      $data  
        );
        
        echo json_encode($output); 
    }
    
    function make_expired_datatables($param1, $param2)
	{  
        $this->make_expired_query($param1, $param2);
        log_message("error", "Lenght: ".$_POST['length'].", Start: ".$_POST['start']);
        if($_POST["length"] != -1)  
        {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_expired_query($param1, $param2)  
    {  
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('*'); 
        $this->db->from('lotes');
        $this->db->where('branch_id', $branch_id);
        $this->db->where('status', 1);
        $this->db->where('existencia >', '0');
        $this->db->where("DATE(fecha_vencimiento) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(fecha_vencimiento) <= DATE('$param2')", NULL, FALSE);
        
        if($_POST["search"]["value"] != '')  
        {  
            $this->db->like("code", $_POST["search"]["value"]);  
        }  
        
        if(isset($_POST["order"]))  
        {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        }
        
        $this->db->order_by('fecha_vencimiento', 'ASC');
        
    }
    
    function get_all_expired_data($param1, $param2)  
    {  
        $branch_id = $this->session->userdata('branch_id');
        $this->db->select('*'); 
        $this->db->from('lotes');
        $this->db->where('branch_id', $branch_id);
        $this->db->where('status', 1);
        $this->db->where('existencia >', '0');
        $this->db->where("DATE(fecha_vencimiento) >= DATE('$param1')", NULL, FALSE);
        $this->db->where("DATE(fecha_vencimiento) <= DATE('$param2')", NULL, FALSE);
        return $this->db->count_all_results();  
    }
    
    function get_filtered_expired_data($param1, $param2)
    {  
        $this->make_expired_query($param1, $param2);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    
    
 
    function get_inventario_by_category($category_id)
    {
        $fetch_data = $this->make_inventario_by_category_datatables($category_id);  
        $tipo = $this->session->userdata('login_user_type');
           $data = array();  
           $n = 1;
           foreach($fetch_data as $row)  
           {  
                
                $sub_array = array();  
                
                $producto = $this->db->get_where('products',array('products_id'=>$row->products_id))->row();
                if($producto->presentation == 'Caja'){
                    $total = $this->crud_model->get_stock($producto->id_prod_matriz, $this->session->userdata('branch_id'));
                    $stock_inventory = ($total/$producto->cnt_prod_matriz);
                    
                    $total_Central = $this->crud_model->get_stock($producto->id_prod_matriz, 1);
                    $stock_inventory_central = ($total_Central/$producto->cnt_prod_matriz);
                    
                    
                    $total_Salca = $this->crud_model->get_stock($producto->id_prod_matriz, 23);
                    $stock_inventory_salcaja = ($total_Salca/$producto->cnt_prod_matriz);
                    
                    
                    $tot_bodega= $this->crud_model->get_stock($producto->id_prod_matriz, 0);
                    $stock_bodega = ($tot_bodega/$producto->cnt_prod_matriz);
                }else{
                    $stock_inventory  = $this->crud_model->get_stock($row->products_id, $this->session->userdata('branch_id'));
                    $stock_inventory_central  = $this->crud_model->get_stock($row->products_id, 1);
                    $stock_inventory_salcaja  = $this->crud_model->get_stock($row->products_id, 23);
                    $stock_bodega = $this->crud_model->get_stock($row->products_id, 0);
                }
                
                $sub_array[] = $n++;  
                $sub_array[] = $row->code;  
                $sub_array[] = $row->name;  
                
                $sub_array[] = '<span class="label label-lg font-weight-bold label-light-info label-inline">'.$this->db->get_where('type_product', array('type_product_id'=>$row->type_product_id))->row()->name.'</span>';
                $sub_array[] = '<span class="label label-lg font-weight-bold label-light-info label-inline">'.$this->db->get_where('provider', array('provider_id'=>$row->provider))->row()->name.'</span>';
                $sucursales = $this->db->get_where('branch',array('status'=> 1))->row();
                if($this->session->userdata('branch_id')!=(23)){
                $sub_array[] = '<span class="text-success"><b>'.$stock_inventory_salcaja.'</b></span>';
                }
                if($this->session->userdata('branch_id')!=(1)){
                $sub_array[] = '<span class="text-success"><b>'.$stock_inventory_central.'</b></span>';
                }
                $sub_array[] = '<span class="text-success"><b>'.$stock_inventory.'</b></span>';
                $sub_array[] = '<span class="text-success"><b>'.$stock_bodega.'</b></span>';
               if($tipo==1){ $sub_array[] = $row->cost;} 
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($this->crud_model->last_price_buy($row->products_id),2,'.',',');  
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($row->farma,2,'.',','); 
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($row->precio_mayorista,2,'.',',');  
                if(($stock_inventory + $stock_bodega) > $row->alert)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-success label-inline">Disponible</span>';  
                }
                
                elseif(($stock_inventory + $stock_bodega) <= $row->alert && ($stock_inventory + $stock_bodega) > 0)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-warning label-inline">Alerta</span>';  
                }
                
                elseif(($stock_inventory + $stock_bodega) == 0)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-danger label-inline">Sin disponiblidad</span>';  
                }
                
                $data[] = $sub_array;  
           }  
                $output = array(  
                "draw"                      =>      intval($_POST["draw"]),  
                "recordsTotal"              =>      $this->get_all_inventario_by_category_data($category_id),  
                "recordsFiltered"           =>      $this->get_filtered_inventario_by_category_data($category_id),  
                "data"                      =>      $data  
           );  
           echo json_encode($output); 
    }
    
    function get_inventario_by_class($category_id)
    {
        $fetch_data = $this->make_inventario_by_class_datatables($category_id);  
        $tipo = $this->session->userdata('login_user_type');
           $data = array();  
           $n = 1;
           foreach($fetch_data as $row)  
           {  
                
                $sub_array = array();  
                
                $producto = $this->db->get_where('products',array('products_id'=>$row->products_id))->row();
                if($producto->presentation == 'Caja'){
                    $total = $this->crud_model->get_stock($producto->id_prod_matriz, $this->session->userdata('branch_id'));
                    $stock_inventory = ($total/$producto->cnt_prod_matriz);
                    
                    $total_Central = $this->crud_model->get_stock($producto->id_prod_matriz, 1);
                    $stock_inventory_central = ($total_Central/$producto->cnt_prod_matriz);
                    
                    
                    $total_Salca = $this->crud_model->get_stock($producto->id_prod_matriz, 23);
                    $stock_inventory_salcaja = ($total_Salca/$producto->cnt_prod_matriz);
                    
                    
                    $tot_bodega= $this->crud_model->get_stock($producto->id_prod_matriz, 0);
                    $stock_bodega = ($tot_bodega/$producto->cnt_prod_matriz);
                }else{
                    $stock_inventory  = $this->crud_model->get_stock($row->products_id, $this->session->userdata('branch_id'));
                    $stock_inventory_central  = $this->crud_model->get_stock($row->products_id, 1);
                    $stock_inventory_salcaja  = $this->crud_model->get_stock($row->products_id, 23);
                    $stock_bodega = $this->crud_model->get_stock($row->products_id, 0);
                }
                
                $sub_array[] = $n++;  
                $sub_array[] = $row->code;  
                $sub_array[] = $row->name;  
                
                $sub_array[] = '<span class="label label-lg font-weight-bold label-light-info label-inline">'.$this->db->get_where('type_product', array('type_product_id'=>$row->type_product_id))->row()->name.'</span>';
                $sub_array[] = '<span class="label label-lg font-weight-bold label-light-info label-inline">'.$this->db->get_where('provider', array('provider_id'=>$row->provider))->row()->name.'</span>';
                $sucursales = $this->db->get_where('branch',array('status'=> 1))->row();
                if($this->session->userdata('branch_id')!=(23)){
                $sub_array[] = '<span class="text-success"><b>'.$stock_inventory_salcaja.'</b></span>';
                }
                if($this->session->userdata('branch_id')!=(1)){
                $sub_array[] = '<span class="text-success"><b>'.$stock_inventory_central.'</b></span>';
                }
                $sub_array[] = '<span class="text-success"><b>'.$stock_inventory.'</b></span>';
                $sub_array[] = '<span class="text-success"><b>'.$stock_bodega.'</b></span>';
               if($tipo==1){ $sub_array[] = $row->cost;} 
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($this->crud_model->last_price_buy($row->products_id),2,'.',',');  
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($row->farma,2,'.',','); 
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($row->precio_mayorista,2,'.',',');  
                if(($stock_inventory + $stock_bodega) > $row->alert)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-success label-inline">Disponible</span>';  
                }
                
                elseif(($stock_inventory + $stock_bodega) <= $row->alert && ($stock_inventory + $stock_bodega) > 0)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-warning label-inline">Alerta</span>';  
                }
                
                elseif(($stock_inventory + $stock_bodega) == 0)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-danger label-inline">Sin disponiblidad</span>';  
                }
                
                $data[] = $sub_array;  
           }  
                $output = array(  
                "draw"                      =>      intval($_POST["draw"]),  
                "recordsTotal"              =>      $this->get_all_inventario_by_class_data($category_id),  
                "recordsFiltered"           =>      $this->get_filtered_inventario_by_class_data($category_id),  
                "data"                      =>      $data  
           );  
           echo json_encode($output); 
    }
    
    function get_inventario_by_type($category_id)
    {
        $fetch_data = $this->make_inventario_by_type_datatables($category_id);  
        $tipo = $this->session->userdata('login_user_type');
           $data = array();  
           $n = 1;
           foreach($fetch_data as $row)  
           {  
                
                $sub_array = array();  
                
                $producto = $this->db->get_where('products',array('products_id'=>$row->products_id))->row();
                if($producto->presentation == 'Caja'){
                    $total = $this->crud_model->get_stock($producto->id_prod_matriz, $this->session->userdata('branch_id'));
                    $stock_inventory = ($total/$producto->cnt_prod_matriz);
                    
                    $total_Central = $this->crud_model->get_stock($producto->id_prod_matriz, 1);
                    $stock_inventory_central = ($total_Central/$producto->cnt_prod_matriz);
                    
                    
                    $total_Salca = $this->crud_model->get_stock($producto->id_prod_matriz, 23);
                    $stock_inventory_salcaja = ($total_Salca/$producto->cnt_prod_matriz);
                    
                    
                    $tot_bodega= $this->crud_model->get_stock($producto->id_prod_matriz, 0);
                    $stock_bodega = ($tot_bodega/$producto->cnt_prod_matriz);
                }else{
                    $stock_inventory  = $this->crud_model->get_stock($row->products_id, $this->session->userdata('branch_id'));
                    $stock_inventory_central  = $this->crud_model->get_stock($row->products_id, 1);
                    $stock_inventory_salcaja  = $this->crud_model->get_stock($row->products_id, 23);
                    $stock_bodega = $this->crud_model->get_stock($row->products_id, 0);
                }
                
                $sub_array[] = $n++;  
                $sub_array[] = $row->code;  
                $sub_array[] = $row->name;  
                
                $sub_array[] = '<span class="label label-lg font-weight-bold label-light-info label-inline">'.$this->db->get_where('type_product', array('type_product_id'=>$row->type_product_id))->row()->name.'</span>';
                $sub_array[] = '<span class="label label-lg font-weight-bold label-light-info label-inline">'.$this->db->get_where('provider', array('provider_id'=>$row->provider))->row()->name.'</span>';
                $sucursales = $this->db->get_where('branch',array('status'=> 1))->row();
                if($this->session->userdata('branch_id')!=(23)){
                $sub_array[] = '<span class="text-success"><b>'.$stock_inventory_salcaja.'</b></span>';
                }
                if($this->session->userdata('branch_id')!=(1)){
                $sub_array[] = '<span class="text-success"><b>'.$stock_inventory_central.'</b></span>';
                }
                $sub_array[] = '<span class="text-success"><b>'.$stock_inventory.'</b></span>';
                $sub_array[] = '<span class="text-success"><b>'.$stock_bodega.'</b></span>';
               if($tipo==1){ $sub_array[] = $row->cost;} 
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($this->crud_model->last_price_buy($row->products_id),2,'.',',');  
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($row->farma,2,'.',','); 
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($row->precio_mayorista,2,'.',',');  
                if(($stock_inventory + $stock_bodega) > $row->alert)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-success label-inline">Disponible</span>';  
                }
                
                elseif(($stock_inventory + $stock_bodega) <= $row->alert && ($stock_inventory + $stock_bodega) > 0)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-warning label-inline">Alerta</span>';  
                }
                
                elseif(($stock_inventory + $stock_bodega) == 0)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-danger label-inline">Sin disponiblidad</span>';  
                }
                
                $data[] = $sub_array;  
           }  
                $output = array(  
                "draw"                      =>      intval($_POST["draw"]),  
                "recordsTotal"              =>      $this->get_all_inventario_by_type_data($category_id),  
                "recordsFiltered"           =>      $this->get_filtered_inventario_by_type_data($category_id),  
                "data"                      =>      $data  
           );  
           echo json_encode($output); 
    }
    
    function get_inventario_by_marca($category_id)
    {
        $fetch_data = $this->make_inventario_by_marca_datatables($category_id);  
        $tipo = $this->session->userdata('login_user_type');
           $data = array();  
           $n = 1;
           foreach($fetch_data as $row)  
           {  
                
                $sub_array = array();  
                
                $producto = $this->db->get_where('products',array('products_id'=>$row->products_id))->row();
                if($producto->presentation == 'Caja'){
                    $total = $this->crud_model->get_stock($producto->id_prod_matriz, $this->session->userdata('branch_id'));
                    $stock_inventory = ($total/$producto->cnt_prod_matriz);
                    
                    $total_Central = $this->crud_model->get_stock($producto->id_prod_matriz, 1);
                    $stock_inventory_central = ($total_Central/$producto->cnt_prod_matriz);
                    
                    $total_Salca = $this->crud_model->get_stock($producto->id_prod_matriz, 23);
                    $stock_inventory_salcaja = ($total_Salca/$producto->cnt_prod_matriz);
                    
                    
                    $tot_bodega= $this->crud_model->get_stock($producto->id_prod_matriz, 0);
                    $stock_bodega = ($tot_bodega/$producto->cnt_prod_matriz);
                }else{
                    $stock_inventory  = $this->crud_model->get_stock($row->products_id, $this->session->userdata('branch_id'));
                    $stock_inventory_central  = $this->crud_model->get_stock($row->products_id, 1);
                    $stock_inventory_salcaja  = $this->crud_model->get_stock($row->products_id, 23);
                    $stock_bodega = $this->crud_model->get_stock($row->products_id, 0);
                }
                
                $sub_array[] = $n++;  
                $sub_array[] = $row->code;  
                $sub_array[] = $row->name;  
                
                $sub_array[] = '<span class="label label-lg font-weight-bold label-light-info label-inline">'.$this->db->get_where('type_product', array('type_product_id'=>$row->type_product_id))->row()->name.'</span>';
                $sub_array[] = '<span class="label label-lg font-weight-bold label-light-info label-inline">'.$this->db->get_where('provider', array('provider_id'=>$row->provider))->row()->name.'</span>';
                $sucursales = $this->db->get_where('branch',array('status'=> 1))->row();
                if($this->session->userdata('branch_id')!=(23)){
                $sub_array[] = '<span class="text-success"><b>'.$stock_inventory_salcaja.'</b></span>';
                }
                if($this->session->userdata('branch_id')!=(1)){
                $sub_array[] = '<span class="text-success"><b>'.$stock_inventory_central.'</b></span>';
                }
                $sub_array[] = '<span class="text-success"><b>'.$stock_inventory.'</b></span>';
                $sub_array[] = '<span class="text-success"><b>'.$stock_bodega.'</b></span>';
               if($tipo==1){ $sub_array[] = $row->cost;} 
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($this->crud_model->last_price_buy($row->products_id),2,'.',',');  
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($row->farma,2,'.',','); 
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($row->precio_mayorista,2,'.',',');  
                if(($stock_inventory + $stock_bodega) > $row->alert)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-success label-inline">Disponible</span>';  
                }
                
                elseif(($stock_inventory + $stock_bodega) <= $row->alert && ($stock_inventory + $stock_bodega) > 0)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-warning label-inline">Alerta</span>';  
                }
                
                elseif(($stock_inventory + $stock_bodega) == 0)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-danger label-inline">Sin disponiblidad</span>';  
                }
                
                $data[] = $sub_array;  
           }  
                $output = array(  
                "draw"                      =>      intval($_POST["draw"]),  
                "recordsTotal"              =>      $this->get_all_inventario_by_marca_data($category_id),  
                "recordsFiltered"           =>      $this->get_filtered_inventario_by_marca_data($category_id),  
                "data"                      =>      $data  
           );  
           echo json_encode($output); 
    }
    
    
    function make_inventario_by_category_datatables($category_id)
	{  
        $this->make_inventario_by_category_query($category_id);  
        if($_POST["length"] != -1)  
        {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_inventario_by_class_datatables($category_id)
	{  
        $this->make_inventario_by_class_query($category_id);  
        if($_POST["length"] != -1)  
        {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_inventario_by_type_datatables($category_id)
	{  
        $this->make_inventario_by_type_query($category_id);  
        if($_POST["length"] != -1)  
        {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_inventario_by_marca_datatables($category_id)
	{  
        $this->make_inventario_by_marca_query($category_id);  
        if($_POST["length"] != -1)  
        {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_inventario_by_category_query($category_id)  
    {  
        $this->db->select('*'); 
        $this->db->from('products');
        $this->db->where('category', $category_id);
        $this->db->where('status', 1);
        
       // $this->db->where('admin_id<>', $this->session->userdata('login_user_id'));
        
        if($_POST["search"]["value"] != '')  
        {  
            $this->db->like("name", $_POST["search"]["value"]);
            $this->db->or_like("code", $_POST["search"]["value"]);  
        }  
        
        if(isset($_POST["order"]))  
        {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        }  
        
        else  
        {  
            $this->db->order_by('products_id', 'DESC');  
        }  
    }
    
    function make_inventario_by_class_query($category_id)  
    {  
        $this->db->select('*'); 
        $this->db->from('products');
        $this->db->where('class_product_id', $category_id);
        $this->db->where('status', 1);
        
       // $this->db->where('admin_id<>', $this->session->userdata('login_user_id'));
        
        if($_POST["search"]["value"] != '')  
        {  
            $this->db->like("name", $_POST["search"]["value"]);
            $this->db->or_like("code", $_POST["search"]["value"]);  
        }  
        
        if(isset($_POST["order"]))  
        {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        }  
        
        else  
        {  
            $this->db->order_by('products_id', 'DESC');  
        }  
    }
    
    function make_inventario_by_type_query($category_id)  
    {  
        $this->db->select('*'); 
        $this->db->from('products');
        $this->db->where('type_product_id', $category_id);
        $this->db->where('status', 1);
        
       // $this->db->where('admin_id<>', $this->session->userdata('login_user_id'));
        
        if($_POST["search"]["value"] != '')  
        {  
            $this->db->like("name", $_POST["search"]["value"]);
            $this->db->or_like("code", $_POST["search"]["value"]);  
        }  
        
        if(isset($_POST["order"]))  
        {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        }  
        
        else  
        {  
            $this->db->order_by('products_id', 'DESC');  
        }  
    }
    
    function make_inventario_by_marca_query($category_id)  
    {  
        $this->db->select('*'); 
        $this->db->from('products');
        $this->db->where('mark', $category_id);
        $this->db->where('status', 1);
        
       // $this->db->where('admin_id<>', $this->session->userdata('login_user_id'));
        
        if($_POST["search"]["value"] != '')  
        {  
            $this->db->like("name", $_POST["search"]["value"]);
            $this->db->or_like("code", $_POST["search"]["value"]);  
        }  
        
        if(isset($_POST["order"]))  
        {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        }  
        
        else  
        {  
            $this->db->order_by('products_id', 'DESC');  
        }  
    }
    
    function get_all_inventario_by_category_data($category_id)  
    {  
        $this->db->select('*'); 
        $this->db->from('products');
        $this->db->where('category', $category_id);
        $this->db->where('status', 1);
        //$this->db->where('admin_id<>', $this->session->userdata('login_user_id'));
        return $this->db->count_all_results();  
    }
    
    function get_all_inventario_by_class_data($category_id)  
    {  
        $this->db->select('*'); 
        $this->db->from('products');
        $this->db->where('class_product_id', $category_id);
        $this->db->where('status', 1);
        //$this->db->where('admin_id<>', $this->session->userdata('login_user_id'));
        return $this->db->count_all_results();  
    }
    
    function get_all_inventario_by_type_data($category_id)  
    {  
        $this->db->select('*'); 
        $this->db->from('products');
        $this->db->where('type_product_id', $category_id);
        $this->db->where('status', 1);
        //$this->db->where('admin_id<>', $this->session->userdata('login_user_id'));
        return $this->db->count_all_results();  
    }
    
    function get_all_inventario_by_marca_data($category_id)  
    {  
        $this->db->select('*'); 
        $this->db->from('products');
        $this->db->where('type_product_id', $category_id);
        $this->db->where('status', 1);
        //$this->db->where('admin_id<>', $this->session->userdata('login_user_id'));
        return $this->db->count_all_results();  
    }
    
    function get_filtered_inventario_by_category_data($category_id)
    {  
        $this->make_inventario_by_category_query($category_id);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    function get_filtered_inventario_by_class_data($category_id)
    {  
        $this->make_inventario_by_class_query($category_id);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    function get_filtered_inventario_by_type_data($category_id)
    {  
        $this->make_inventario_by_marca_query($category_id);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    function get_filtered_inventario_by_marca_data($category_id)
    {  
        $this->make_inventario_by_marca_query($category_id);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
    
    
    
    function get_inventario_by_mark($mark_id)
    {
        $fetch_data = $this->make_inventario_by_mark_datatables($mark_id);  
        $tipo = $this->session->userdata('login_user_type');
        $permisos = unserialize($this->session->userdata('permissions'));
        log_message("error", "Editar productos: ".$permisos['editar_productos'].", Eliminar productos: ".$permisos['estado_productos']);
           $data = array();  
           $n = 1;
           foreach($fetch_data as $row)  
           {  
                
                $sub_array = array();  
                
                $producto = $this->db->get_where('products',array('products_id'=>$row->products_id))->row();
                 if($producto->presentation == 'Caja'){
                    $total = $this->crud_model->get_stock($producto->id_prod_matriz, $this->session->userdata('branch_id'));
                    $stock_inventory = ($total/$producto->cnt_prod_matriz);
                    
                    $total_Central = $this->crud_model->get_stock($producto->id_prod_matriz, 1);
                    $stock_inventory_central = ($total_Central/$producto->cnt_prod_matriz);
                    
                    
                    $total_Salca = $this->crud_model->get_stock($producto->id_prod_matriz, 23);
                    $stock_inventory_salcaja = ($total_Salca/$producto->cnt_prod_matriz);
                    
                    
                    $tot_bodega= $this->crud_model->get_stock($producto->id_prod_matriz, 0);
                    $stock_bodega = ($tot_bodega/$producto->cnt_prod_matriz);
                }else{
                    $stock_inventory  = $this->crud_model->get_stock($row->products_id, $this->session->userdata('branch_id'));
                    $stock_inventory_central  = $this->crud_model->get_stock($row->products_id,1);
                    $stock_inventory_salcaja  = $this->crud_model->get_stock($row->products_id, 23);
                    $stock_bodega = $this->crud_model->get_stock($row->products_id, 0);
                }
                
                $sub_array[] = $n++;  
                $sub_array[] = $row->code;  
                $sub_array[] = $row->name;  
                $actions = '<div class="d-flex flex-shrink-0">&nbsp;
                    <a href="'.base_url().'admin/producto_detalle/'.$row->products_id.'" data-toggle="tooltip" data-original-title="Movimiento del producto" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
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
                    </a>';
                if ($permisos['editar_productos'] == 1) {
                    $actions .= '&nbsp;
                    <a href="'.base_url().'admin/editar_producto/'.$row->products_id.'" data-toggle="tooltip" data-original-title="Editar producto" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                        <span class="svg-icon svg-icon-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="#8950FC" />
                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="#8950FC" />
                            </svg>
                        </span>
                    </a>';
                }
                if ($permisos['estado_productos'] == 1) {
                    $actions .= '&nbsp;
                    <a href="javascript:;" data-toggle="tooltip" data-original-title="Eliminar producto" onclick="eliminarProducto(\''.$row->products_id.'\')" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero" />
                                    <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3" />
                                </g>
                            </svg>
                        </span>
                    </a>';
                }
                $actions .= '</div>';
                $sub_array[] = $actions;
                /*
                $sub_array[] = '<span class="label label-lg font-weight-bold label-light-info label-inline">'.$this->db->get_where('categories', array('category_id'=>$row->category))->row()->name.'</span>';
                $sub_array[] = '<span class="text-primary">'.$this->db->get_where('type_product', array('type_product_id'=>$row->type_product_id))->row()->name.'</span>';
                $sub_array[] = '<span class="text-primary">'.$this->db->get_where('class_product', array('class_product_id'=>$row->class_product_id))->row()->name_class.'</span>';
                $sub_array[] = '<span class="label label-lg font-weight-bold label-light-info label-inline">'.$this->db->get_where('provider', array('provider_id'=>$row->provider))->row()->name.'</span>';*/
                
                $sub_array[] = '<span class="text-success"><b>'.$stock_inventory.'</b></span>';
                $sub_array[] = '<span class="text-success"><b>'.$stock_bodega.'</b></span>';
               if($tipo==1){ $sub_array[] = $row->cost;} 
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($this->crud_model->last_price_buy($row->products_id),2,'.',',');  
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($row->farma,2,'.',','); 
                $sub_array[] = $this->crud_model->get_info("moneda").number_format($row->precio_mayorista,2,'.',',');  
                if(($stock_inventory + $stock_bodega) > $row->alert)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-success label-inline">Disponible</span>';  
                }
                
                elseif(($stock_inventory + $stock_bodega) <= $row->alert && ($stock_inventory + $stock_bodega) > 0)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-warning label-inline">Alerta</span>';  
                }
                
                elseif(($stock_inventory + $stock_bodega) == 0)
                {
                    $sub_array[] = '<span class="label label-lg font-weight-bold label-light-danger label-inline">Sin disponiblidad</span>';  
                }
                
                $actions = '<div class="d-flex flex-shrink-0">&nbsp;
                                    <a href="'.base_url().'admin/producto_detalle/'.$row->products_id.'" data-toggle="tooltip" data-original-title="Movimiento del producto" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
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
                                    </a>';
                if ($permisos['editar_productos'] == 1) {
                    $actions .= '&nbsp;
                                    <a href="'.base_url().'admin/editar_producto/'.$row->products_id.'" data-toggle="tooltip" data-original-title="Editar producto" class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                        <span class="svg-icon svg-icon-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                <path opacity="0.3" d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z" fill="#8950FC" />
                                                <path d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z" fill="#8950FC" />
                                            </svg>
                                        </span>
                                    </a>';
                }
                if ($permisos['estado_productos'] == 1) {
                    $actions .= '&nbsp;
                                    <a href="javascript:;" data-toggle="tooltip" data-original-title="Eliminar producto" onclick="eliminarProducto(\''.$row->products_id.'\')" class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                                        <span class="svg-icon svg-icon-primary svg-icon-2x">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero" />
                                                    <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3" />
                                                </g>
                                            </svg>
                                        </span>
                                    </a>';
                }
                $actions .= '</div>';
                $sub_array[] = $actions;
                
                $data[] = $sub_array;  
           }  
                $output = array(  
                "draw"                      =>      intval($_POST["draw"]),  
                "recordsTotal"              =>      $this->get_all_inventario_by_mark_data($mark_id),  
                "recordsFiltered"           =>      $this->get_filtered_inventario_by_mark_data($mark_id),  
                "data"                      =>      $data  
           );  
           echo json_encode($output); 
    }
    
    
    
    function make_inventario_by_mark_datatables($mark_id)
	{  
        $this->make_inventario_by_mark_query($mark_id);  
        if($_POST["length"] != -1)  
        {  
            $this->db->limit($_POST['length'], $_POST['start']);  
        }  
        $query = $this->db->get();  
        return $query->result();  
    }
    
    function make_inventario_by_mark_query($mark_id)  
    {  
        $this->db->select('*'); 
        $this->db->from('products');
        $this->db->where('status', 1);
        if($mark_id != '') $this->db->where('mark', $mark_id);
        
       // $this->db->where('admin_id<>', $this->session->userdata('login_user_id'));
        
        if($_POST["search"]["value"] != '')  
        {  
            $this->db->like("name", $_POST["search"]["value"]);
            $this->db->or_like("code", $_POST["search"]["value"]);  
        }  
        
        if(isset($_POST["order"])) {  
            $this->db->order_by($this->order_column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);  
        } else {  
            $this->db->order_by('name', 'ASC');  
        }  
    }
    
    function get_all_inventario_by_mark_data($mark_id)  
    {  
        $this->db->select('*'); 
        $this->db->from('products');
        $this->db->where('status', 1);
        if($mark_id != '') $this->db->where('mark', $mark_id);
        //$this->db->where('admin_id<>', $this->session->userdata('login_user_id'));
        return $this->db->count_all_results();  
    }
    
    function get_filtered_inventario_by_mark_data($mark_id)
    {  
        $this->make_inventario_by_mark_query($mark_id);  
        $query = $this->db->get();  
        return $query->num_rows();  
    }
    
}
