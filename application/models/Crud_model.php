<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crud_model extends CI_Model {
	
    private $moneda = '';
	function __construct()
    {   
        parent::__construct();
        $this->load->library('excel');
        $moneda = $this->get_info("moneda");
    }
	
	function diferencia($id_)
	{
        return  $this->db->get_where('producto', array('id' => $id_))->row()->precio - $this->db->get_where('producto', array('id' => $id_))->row()->costo;
	}
	
	function get_local_ipv4() {
        $localIP = getHostByName(getHostName());
        return $localIP;
	}
	
    public function deleteAll_nots()
    {
        $data = $this->db->query("SELECT * FROM `notificacion` WHERE DATEDIFF(NOW(), date) > 30")->result_array();
        foreach($data as $row){
            $this->db->where('notificacion_id', $row['notificacion_id']);
            $this->db->delete('notificacion');
        }
    }

    public function search_email($email = '')
    {
        if ($email != "") {
            $credential = array('email' => $email);
            $admin_query = $this->db->get_where('admin', $credential);
            if ($admin_query->num_rows() > 0) {
                return 'success';
            }
        }
    }

    function get_nots_mes()
    {
        $user_id = $this->session->userdata('login_user_id');
        $data = $this->db->query("SELECT * FROM `notificacion` WHERE DATEDIFF(NOW(), date) <= 30 AND user_id = $user_id AND readed = 0 ORDER BY date DESC LIMIT 10");
        return $data;
    }

	function obtener_ganancia($inicial, $final)
	{
	    $total = 0;
	    $retornar = 0;
	    $this->db->where("status", 1);
        $this->db->where("fecha >=", $inicial);
        $this->db->where("fecha <=", $final);
        $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
        $data  = $this->db->get('ganancias')->result_array();
        foreach($data as $row)
        {
            $total += $row['ganancia']; 
        }
        return $total;
	}
	
	function clear_cache()
	{
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
	}

    function get_dates_within_range($time_start , $time_end)
    {
        $begin      = new DateTime($time_start);
        $end        = new DateTime($time_end);
        $interval   = DateInterval::createFromDateString('1 day');
        $period     = new DatePeriod($begin, $interval, $end);
        return $period;
    }  

    function count_payroll_quincenas($date_start, $date_end)
    {
        try {
            $start = new DateTime($date_start);
            $end   = new DateTime($date_end);
        } catch (Exception $e) {
            return 0;
        }

        if ($end < $start) {
            return 0;
        }

        $count  = 0;
        $cursor = new DateTime($start->format('Y-m-01'));

        while ($cursor <= $end) {
            $year  = (int) $cursor->format('Y');
            $month = (int) $cursor->format('m');
            $q1_start = new DateTime(sprintf('%04d-%02d-01', $year, $month));
            $q1_end   = new DateTime(sprintf('%04d-%02d-15', $year, $month));
            $q2_start = new DateTime(sprintf('%04d-%02d-16', $year, $month));
            $q2_end   = new DateTime($cursor->format('Y-m-t'));

            if ($start <= $q1_end && $end >= $q1_start) {
                $count++;
            }
            if ($start <= $q2_end && $end >= $q2_start) {
                $count++;
            }

            $cursor->modify('first day of next month');
        }

        return $count;
    }

    function calculate_proportional_salary($monthly_salary, $date_start, $date_end)
    {
        $monthly_salary = (float) $monthly_salary;

        try {
            $start = new DateTime($date_start);
            $end   = new DateTime($date_end);
        } catch (Exception $e) {
            return 0;
        }

        if ($monthly_salary <= 0 || $end < $start) {
            return 0;
        }

        // Mes comercial 360/12 = 30 días; cada quincena paga exactamente 15 días.
        $quincenas = $this->count_payroll_quincenas($date_start, $date_end);
        $days      = $quincenas * 15;

        return round(($monthly_salary / 30) * $days, 2);
    }

    function get_employee_base_salary($employee, $payroll_name = 'Oficial')
    {
        if (is_array($employee)) {
            $salary = isset($employee['salary']) ? $employee['salary'] : 0;
            $complemento = isset($employee['complemento']) ? $employee['complemento'] : 0;
        } elseif (is_object($employee)) {
            $salary = isset($employee->salary) ? $employee->salary : 0;
            $complemento = isset($employee->complemento) ? $employee->complemento : 0;
        } else {
            return 0;
        }

        if ($payroll_name === 'Interna') {
            return max(0, (float) $complemento);
        }

        return max(0, (float) $salary);
    }

    function calculate_payroll_total($employees, $discounts, $advances, $remunerations, $date_start, $date_end, $other_discounts = array(), $payroll_name = '')
    {
        if (!is_array($employees)) {
            return 0;
        }

        $has_other_discount = in_array($payroll_name, array('Oficial', 'Interna'), true);
        $total = 0;
        foreach ($employees as $index => $employee_id) {
            $employee = $this->db->select('salary, complemento')->get_where('admin', array('admin_id' => $employee_id))->row();
            if (!$employee) {
                continue;
            }

            $salary         = $this->calculate_proportional_salary($this->get_employee_base_salary($employee, $payroll_name), $date_start, $date_end);
            $discount       = max(0, (float) (isset($discounts[$index]) ? $discounts[$index] : 0));
            $advance        = max(0, (float) (isset($advances[$index]) ? $advances[$index] : 0));
            $other_discount = $has_other_discount ? max(0, (float) (isset($other_discounts[$index]) ? $other_discounts[$index] : 0)) : 0;
            $remuneration   = max(0, (float) (isset($remunerations[$index]) ? $remunerations[$index] : 0));
            $total         += $salary - $discount - $advance - $other_discount + $remuneration;
        }

        return round($total, 2);
    }

    function calculate_bonus_salary($monthly_salary, $date_start, $date_end)
    {
        $monthly_salary = max(0, (float) $monthly_salary);

        try {
            $start = new DateTime($date_start);
            $end   = new DateTime($date_end);
        } catch (Exception $e) {
            return 0;
        }

        if ($monthly_salary <= 0 || $end < $start) {
            return 0;
        }

        $days = (int) $start->diff($end)->days + 1;
        if ($days >= 365) {
            return round($monthly_salary, 2);
        }

        return round($monthly_salary * ($days / 365), 2);
    }

    function calculate_bonus_payroll_total($employees, $salaries, $date_starts = array(), $date_ends = array(), $discounts = array(), $advances = array(), $remunerations = array())
    {
        if (!is_array($employees)) {
            return 0;
        }

        $total = 0;
        foreach ($employees as $index => $employee_id) {
            $employee = $this->db->select('salary')->get_where('admin', array('admin_id' => $employee_id))->row();
            if (!$employee) {
                continue;
            }

            $date_start = isset($date_starts[$index]) ? $date_starts[$index] : '';
            $date_end   = isset($date_ends[$index]) ? $date_ends[$index] : '';
            $base       = max(0, (float) $employee->salary);
            $salary     = $this->calculate_bonus_salary($base, $date_start, $date_end);
            $discount     = max(0, (float) (isset($discounts[$index]) ? $discounts[$index] : 0));
            $advance      = max(0, (float) (isset($advances[$index]) ? $advances[$index] : 0));
            $remuneration = max(0, (float) (isset($remunerations[$index]) ? $remunerations[$index] : 0));
            $total       += $salary - $discount - $advance + $remuneration;
        }

        return round($total, 2);
    }

    function parse_date_input($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        foreach (array('d/m/Y', 'm/d/Y', 'Y-m-d') as $format) {
            $date = DateTime::createFromFormat('!'.$format, $value);
            $errors = DateTime::getLastErrors();
            if ($date && empty($errors['warning_count']) && empty($errors['error_count'])) {
                return $date->format('Y-m-d');
            }
        }

        $timestamp = strtotime($value);
        return $timestamp ? date('Y-m-d', $timestamp) : null;
    }

    function create_slug($string)
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/','-',$string);
        $slug = strtolower($slug);
        return $slug;
    }

	function get_image_url($type = '', $id = '') 
    {
        if (file_exists('uploads/' . $type . '_image/' . $id . '.png'))
            $image_url = base_url() . 'uploads/' . $type . '_image/' . $id . '.png';
        else
            $image_url = base_url() . 'uploads/user.png';
        return $image_url;
    }
	
	function get_type_name_by_id($type,$type_id='',$field='name')
	{
		return	$this->db->get_where($type,array($type.'_id'=>$type_id))->row()->$field;	
	}
	
	function slugify($text)
	{ 
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);
		$text = trim($text, '-');
		$text = strtolower($text);
		$text = preg_replace('~[^-\w]+~', '', $text);
		if (empty($text))
			return 'n-a';
		return $text;
	}
	
	function make_clickable_links($text)
	{
		return preg_replace('@(https?://([-\w\.]+)+(:\d+)?(/([\w/_\.-]*(\?\S+)?)?)?)@', '<a href="$1" target="_blank">$1</a>', $text);
	}
	
	function get_system_settings()
	{
		$query	=	$this->db->get('settings' );
		return $query->result_array();
	}

    function getCode() 
    {
        return strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6)); 
    }

    function getCodeCotizacion() 
    {
        return  'CTZ'.strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5)); 
    }

    function getCodeVenta() 
    {
        return  'VNT'.strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6)); 
    }

    function getCodeEntrega() 
    {
        return  'NTRG'.strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5)); 
    }
    
    function getCodeCompra() 
    {
        return  'CMPR'.strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5)); 
    }

    function getCodeSolicitud() 
    {
        return  'SLCTD'.strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5)); 
    }

    function getCodeDevolucion() 
    {
        return  'DVLCN'.strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5)); 
    }

    function getCodePerdida() 
    {
        return  'PRDD'.strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5)); 
    }
    function getCodeTraslado() 
    {
        return  'TRAS'.strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5)); 
    }
    function getCodeIngreso() 
    {
        return  'INGR'.strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5)); 
    }

    function getPassword() 
    {
        return strtoupper(substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 8)); 
    }

    function getPin() 
    {
        return strtoupper(substr(str_shuffle("0123456789"), 0, 6)); 
    }

    function total_cuentas()
    {
        $total = $this->db->query("SELECT sum(current_balance) as total FROM `account_bank`")->row()->total;
        return $total;
    }

    function ingresos_caja()
    {
        $branch_id = $this->session->userdata('branch_id');
        $date = date('Y-m-d');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE date = '$date' AND branch_id = '$branch_id' AND metodo = 'Efectivo' AND status = 1 AND estado = 2")->row()->total;
        return $total;
    }
    
    function ingresos_credito()
    {
        $branch_id = $this->session->userdata('branch_id');
        $date = date('Y-m-d');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE date = '$date' AND branch_id = '$branch_id' AND credito = 1 AND status = 1 AND estado = 1")->row()->total;
        return $total;
    }

    function ingresos_caja_date($date)
    {
        $branch_id = $this->session->userdata('branch_id');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE date = '$date' AND branch_id = '$branch_id' AND metodo = 'Efectivo' AND status = 1 AND estado = 2")->row()->total;
        return $total;
    }
    
    function ingresos_credito_date($date)
    {
        $branch_id = $this->session->userdata('branch_id');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE date = '$date' AND branch_id = '$branch_id' AND credito = 1 AND status = 1 AND estado = 1")->row()->total;
        return $total;
    }

    function get_price($price)
    {
            $ganancia = $this->db->get_where('settings', array('type'=>'ganancia'))->row()->description/100 ;
            $total = ($price * $ganancia)+$price;
            return $total;
        
    }


    function egresos_caja()
    {
        $branch_id = $this->session->userdata('branch_id');
        $date = date('Y-m-d');
        $total = $this->db->query("SELECT sum(amount) as total FROM `expense` where status = '1' AND date = '$date'")->row()->total;
        return $total;
    }

    function egresos_caja_date($date)
    {
        $branch_id = $this->session->userdata('branch_id');
        $date = date('Y-m-d');
        $total = $this->db->query("SELECT sum(amount) as total FROM `expense` where status = '1' AND date = '$date'")->row()->total;
        return $total;
    }

    function total_caja()
    {
        $branch_id = $this->session->userdata('branch_id');
        $total = $this->db->get_where("account_bank", array('bank_id' => 0, 'branch_id' =>$branch_id, 'status' => 1))->row()->current_balance;
        return $total;
    }

    /*function total_inventario(){
        $branch_id = $this->session->userdata('branch_id');
        $np = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.type='1' AND d.status='1' AND p.status = 1")->row()->total;
        $traslado = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description LIKE 'Traslado%' AND d.type='0' AND p.status = 1")->row()->total;
        $venta = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description='Venta' AND d.type='0' AND d.status = '1' AND p.status = 1")->row()->total;
        $devol = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description='Devolución' AND d.type='3' AND p.status = 1")->row()->total;
        $perd = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description='Pérdida' AND d.type='4' AND p.status = 1")->row()->total;
        $compra = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.type='2' AND d.description='Compra' AND p.status = 1")->row()->total;
        $ingresos = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.type='2' AND d.description='Producto' AND p.status = 1")->row()->total;
        $total = (($np+$compra+$ingresos)-$traslado)-($venta + $devol + $perd);
        return number_format($total,2,'.',',');
        //return $venta;
    }*/
    function total_inventario(){
        /*$branch_id = $this->session->userdata('branch_id');
        $query = $this->db->query('
            SELECT SUM(calcinvent(products.products_id, '.$branch_id.', products.id_prod_matriz, products.cnt_prod_matriz) * products.cost) AS total 
            FROM products 
            WHERE products.status = 1 AND products.presentation = "Unidad";
        ');
        
        $total = $query->row()->total;

        $query = $this->db->query('select products.products_id, products.name, products.code,products.cost, calcinvent(products.products_id, '.$branch_id.',products.id_prod_matriz, products.cnt_prod_matriz) as tienda from products where products.status = 1 and products.presentation = "Unidad" order by products.products_id desc;');
        $tt=0;
        foreach($query->result_array() as $row)
        {
            $tt = $tt + ($row['tienda']*$row['cost']);
        }*/
        
        $branch_id = $this->session->userdata('branch_id');
        $np = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND type='1' AND status='1' ")->row()->total;
        
        //$traslado = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Traslado' AND type='0'")->row()->total;
        //$venta = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Venta' AND type='0' AND status = '1'")->row()->total;
        
        $salidas        = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND type='0' AND status = 1;")->row()->total;
        
        $devol = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Devolución' AND type='3'")->row()->total;
        $perd = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Pérdida' AND type='4'")->row()->total;
        $compra = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND type='2' AND status='1'")->row()->total;
        $total = ($np+$compra)-($salidas + $devol + $perd);
        return number_format($total,2,'.',',');
        
    } 

    /*function total_bodega(){
        $branch_id = '0';
        $np = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.type='1' AND d.status='1' AND p.status = 1")->row()->total;
        $traslado = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description LIKE 'Traslado%' AND d.type='0' AND p.status = 1")->row()->total;
        $venta = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description='Venta' AND d.type='0' AND p.status = 1")->row()->total;
        $devol = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description='Devolución' AND d.type='3' AND p.status = 1")->row()->total;
        $perd = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description='Pérdida' AND d.type='4' AND p.status = 1")->row()->total;
        $compra = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.type='2' AND p.status = 1")->row()->total;
        $ingresos = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.type='2' AND d.description='Producto' AND p.status = 1")->row()->total;
        // $traslado = 0;
        $total = (($np+$compra+$ingresos)-$traslado)-($venta + $devol + $perd);
        log_message("error", "NP: $np, Traslado: $traslado, Venta: $venta, Devolucion: $devol, Perdida: $perd, Compra: $compra, Ingresos: $ingresos, Total: $total");
        if ($total < 0) $total = 0;
        return number_format($total,2,'.',',');
    }*/
    
    function total_bodega(){
        $branch_id      = '0'; //Branch id Bodega es 0!!!!!!!
        $np             = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND type='1' AND status='1';")->row()->total; // total de iniciales
        
        $salidas        = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND type='0' AND status = 1;")->row()->total;
        
        //$traslado = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Traslado' AND type='0;'")->row()->total;
        //$venta = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Venta' AND type='0' AND status = 1;")->row()->total;
        $devol = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Devolución' AND type='3';")->row()->total;
        $perd = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Pérdida' AND type='4';")->row()->total;
        $compra = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND type='2'")->row()->total;
        //log_message('error'," np= ".$np.'/ compra='.$compra.'/traslado='.$traslado.'/venta='.$venta.'/devolucion='.$devol.'/perdida='.$perd);
        //$total = (($np+$compra)-$traslado)-($venta + $devol + $perd);
        $total = ($np+$compra)-($salidas + $devol + $perd);
        /*$branch_id = 0;
        $query = $this->db->query('
            SELECT SUM(calcinvent(products.products_id, '.$branch_id.', products.id_prod_matriz, products.cnt_prod_matriz) * products.cost) AS total 
            FROM products 
            WHERE products.status = 1 AND products.presentation = "Unidad";
        ');
        
        $total = $query->row()->total;*/
        return number_format($total,2,'.',',');
    }

    /*function total_inversion(){
        $branch_id = $this->session->userdata('branch_id');
        $np = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.type='1' AND d.status='1' AND p.status = 1")->row()->total;
        $traslado = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description LIKE 'Traslado%' AND d.type='0' AND p.status = 1")->row()->total;
        $venta = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description='Venta' AND d.type='0' AND d.status = '1' AND p.status = 1")->row()->total;
        $devol = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description='Devolución' AND d.type='3' AND p.status = 1")->row()->total;
        $perd = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description='Pérdida' AND d.type='4' AND p.status = 1")->row()->total;
        $compra = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.type='2' AND d.description='Compra' AND p.status = 1")->row()->total;
        $ingresos = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.type='2' AND d.description='Producto' AND p.status = 1")->row()->total;
        $total_in = (($np+$compra+$ingresos)-$traslado)-($venta  + $devol + $perd);
        
        $branch_id = '0';
        $np = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.type='1' AND d.status='1' AND p.status = 1")->row()->total;
        $traslado = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description LIKE 'Traslado%' AND d.type='0' AND p.status = 1")->row()->total;
        $venta = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description='Venta' AND d.type='0' AND p.status = 1")->row()->total;
        $devol = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description='Devolución' AND d.type='3' AND p.status = 1")->row()->total;
        $perd = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.description='Pérdida' AND d.type='4' AND p.status = 1")->row()->total;
        $compra = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.type='2' AND p.status = 1")->row()->total;
        $ingresos = $this->db->query("SELECT sum(d.cost * d.amount) as total FROM `product_details` AS d INNER JOIN products AS p ON d.products_id = p.products_id WHERE d.branch_id = '$branch_id' AND d.type='2' AND d.description='Producto' AND p.status = 1")->row()->total;
        $total_b = (($np+$compra+$ingresos)-$traslado)-($venta  + $devol + $perd);
        if($total_b < 0) $total_b = 0;
        
        $total = $total_in + $total_b;
        return number_format($total,2,'.',',');
    }*/
    function total_inversion(){
        $branch_id = $this->session->userdata('branch_id');
        
        $np = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND type='1' AND status='1' ")->row()->total;
        
        //$traslado = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Traslado' AND type='0'")->row()->total;
        //$venta = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Venta' AND type='0'")->row()->total;
        
        $salidas        = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND type='0' AND status = 1;")->row()->total;
        
        $devol = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Devolución' AND type='3'")->row()->total;
        $perd = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Pérdida' AND type='4'")->row()->total;
        $compra = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND type='2' AND status='1'")->row()->total;
        
        $total_in = ($np+$compra)-($salidas  + $devol + $perd);
        
        $branch_id = '0';
        $np = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND type='1' AND status='1' ")->row()->total;
        
        //$traslado = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Traslado' AND type='0'")->row()->total;
        //$venta = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Venta' AND type='0'")->row()->total;
        
        $salidas        = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND type='0' AND status = 1;")->row()->total;
        
        $devol = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Devolución' AND type='3'")->row()->total;
        $perd = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND description='Pérdida' AND type='4'")->row()->total;
        $compra = $this->db->query("SELECT sum(cost * amount) as total FROM `product_details` WHERE branch_id = '$branch_id' AND type='2'")->row()->total;
        $total_b = ($np+$compra)-($salidas  + $devol + $perd);
        
        $total = $total_in + $total_b;
        return number_format($total,2,'.',',');
    }
    
    function total_vendido_por_dia(){
        $branch_id = $this->session->userdata('branch_id');
        $hoy = date('Y-m-d');
        $data = $this->db->get_where('sales', array('date'=>$hoy, 'branch_id'=>$branch_id, 'estado'=>'2' , 'credito' => '0'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost']*$pro[$i]['amount'];
                }
                $totall+= $row['total'];
        }
        $total = $totall-$cost; 
        return number_format($total,2,'.',',');
    }
    function total_vendido_por_dia2($dia,$branch_id){
        //$branch_id = 18;
        $hoy = $dia;
        $data = $this->db->get_where('sales', array('date'=>$hoy, 'branch_id'=>$branch_id, 'estado'=>'2' , 'credito' => '0'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost']*$pro[$i]['amount'];
                }
                $totall+= $row['total'];
        }
        $total = $totall-$cost; 
        return number_format($total,2,'.',',');
    }
    
        /*function total_transf()
        {
        $branch_id = $this->session->userdata('branch_id');
        $hoy = date('Y-m-d');
        $data = $this->db->get_where('sales', array('date'=>$hoy, 'branch_id'=>$branch_id, 'estado'=>'2' , 'credito' => '0','metodo' => 'Transferencia'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost']*$pro[$i]['amount'];
                }
                $totall+= $row['total'];
        }
        //$total = $totall-$cost; 
        return number_format($totall,2,'.',',');
    }*/
    
    function total_transf()
        {
        $branch_id = $this->session->userdata('branch_id');
        $hoy = date('Y-m-d');
        $data = $this->db->get_where('sales', array('date'=>$hoy, 'branch_id'=>$branch_id, 'estado'=>'2' , 'credito' => '0','metodo' => 'Transferencia'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost']*$pro[$i]['amount'];
                }
                $totall+= $row['total'];
        }
        //$total = $totall-$cost; 
        return $totall;
    }
    function total_tarjeta()
        {
        $branch_id = $this->session->userdata('branch_id');
        $hoy = date('Y-m-d');
        $data = $this->db->get_where('sales', array('date'=>$hoy, 'branch_id'=>$branch_id, 'estado'=>'2' , 'credito' => '0','metodo' => 'Tarjeta'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost']*$pro[$i]['amount'];
                }
                $totall+= $row['total'];
        }
        //$total = $totall-$cost; 
        return $totall;
    }
    
    function total_tarjeta_date($date)
        {
        $branch_id = $this->session->userdata('branch_id');
        $hoy = $date;
        $data = $this->db->get_where('sales', array('date'=>$hoy, 'branch_id'=>$branch_id, 'estado'=>'2' , 'credito' => '0','metodo' => 'Tarjeta'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost']*$pro[$i]['amount'];
                }
                $totall+= $row['total'];
        }
        //$total = $totall-$cost; 
        return $totall;
    }

    function total_transf_date($date)
    {
        $branch_id = $this->session->userdata('branch_id');
        $hoy = $date;
        $data = $this->db->get_where('sales', array('date'=>$hoy, 'branch_id'=>$branch_id, 'estado'=>'2' , 'credito' => '0','metodo' => 'Transferencia'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost']*$pro[$i]['amount'];
                }
                $totall+= $row['total'];
        }
        //$total = $totall-$cost; 
        return $totall;
    }

        function capital_vendido_hoy()
        {
        $branch_id = $this->session->userdata('branch_id');
        $hoy = date('Y-m-d');
        $data = $this->db->get_where('sales', array('date'=>$hoy, 'branch_id'=>$branch_id, 'estado'=>'2' , 'credito' => '0'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost']*$pro[$i]['amount'];
                }
                $totall+= $row['total'];
        }
       // $total = $totall-$cost; 
        return number_format($cost,2,'.',',');
    }
       function capital_vendido_hoy2($dia,$branch_id)
        {
        //$branch_id = 18;
        $hoy = $dia;
        $data = $this->db->get_where('sales', array('date'=>$hoy, 'branch_id'=>$branch_id, 'estado'=>'2' , 'credito' => '0'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost']*$pro[$i]['amount'];
                }
                $totall+= $row['total'];
        }
       // $total = $totall-$cost; 
        return number_format($cost,2,'.',',');
    }

    function total_vendido_por_dia_sinFEL(){
        $branch_id = $this->session->userdata('branch_id');
        $hoy = date('Y-m-d');
        $data = $this->db->get_where('sales', array('date'=>$hoy, 'branch_id'=>$branch_id, 'estado'=>'2', 'FEL' => '0' , 'credito' => '0'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost'];
                }
                $totall+= $row['total'];
        }
        $total = $totall-$cost; 
        return number_format($total,2,'.',',');
    }
    

    function total_vendido_por_dia_FEL(){
        $branch_id = $this->session->userdata('branch_id');
        $hoy = date('Y-m-d');
        $data = $this->db->get_where('sales', array('date'=>$hoy, 'branch_id'=>$branch_id, 'estado'=>'2', 'FEL' => '1' , 'credito' => '0'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost'];
                }
                $totall+= $row['total'];
        }
        $total = $totall-$cost; 
        return number_format($total,2,'.',',');
    }

    function total_vendido_por_semana_sinFEL(){
        $branch_id = $this->session->userdata('branch_id');
        $week = date('W');
        $year = date('Y');
        $data = $this->db->get_where('sales', array('week'=>$week, 'year'=>$year, 'branch_id'=>$branch_id, 'estado'=>'2', 'FEL' => '0' , 'credito' => '0'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost'];
                }
                $totall+= $row['total'];
        }
        $total = $totall-$cost; 
        return number_format($total,2,'.',',');
    }

    function total_vendido_por_semana_FEL(){
        $branch_id = $this->session->userdata('branch_id');
        $week = date('W');
        $year = date('Y');
        $data = $this->db->get_where('sales', array('week'=>$week, 'year'=>$year, 'branch_id'=>$branch_id, 'estado'=>'2', 'FEL' => '1' , 'credito' => '0'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost'];
                }
                $totall+= $row['total'];
        }
        $total = $totall-$cost; 
        return number_format($total,2,'.',',');
    }

    function total_vendido_por_mes_sinFEL(){
        $branch_id = $this->session->userdata('branch_id');
        $month = date('m');
        $year = date('Y');
        $data = $this->db->get_where('sales', array('month'=>$month, 'year'=>$year, 'branch_id'=>$branch_id, 'estado'=>'2', 'FEL' => '0' , 'credito' => '0'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost'];
                }
                $totall+= $row['total'];
        }
        $total = $totall-$cost; 
        return number_format($total,2,'.',',');
    }

    function total_vendido_por_mes_FEL(){
        $branch_id = $this->session->userdata('branch_id');
        $month = date('m');
        $year = date('Y');
        $data = $this->db->get_where('sales', array('month'=>$month, 'year'=>$year, 'branch_id'=>$branch_id, 'estado'=>'2', 'FEL' => '1' , 'credito' => '0'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost'];
                }
                $totall+= $row['total'];
        }
        $total = $totall-$cost; 
        return number_format($total,2,'.',',');
    }
    
    function total_vendido_por_semana(){
        $branch_id = $this->session->userdata('branch_id');
        $week = date('W');
        $year = date('Y');
        $data = $this->db->get_where('sales', array('week'=>$week, 'year'=>$year, 'branch_id'=>$branch_id, 'estado'=>'2', 'credito' => '0'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost']*$pro[$i]['amount'];
                }
                $totall+= $row['total'];
        }
        $total = $totall-$cost; 
        return number_format($total,2,'.',',');
    }
    
    function total_vendido_por_mes(){
        $branch_id = $this->session->userdata('branch_id');
        $month = date('m');
        $year = date('Y');
        $data = $this->db->get_where('sales', array('month'=>$month, 'year'=>$year, 'branch_id'=>$branch_id, 'estado'=>'2' , 'credito' => '0'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost']*$pro[$i]['amount'];
                }
                $totall+= $row['total'];
        }
        $total = $totall-$cost; 
        return number_format($total,2,'.',',');
    }
    function total_vendido_por_mes2($mes, $year, $branch_id){
        //$branch_id = 18;
        $month = $mes;
        // $year = date('Y');
        $data = $this->db->get_where('sales', array('month'=>$month, 'year'=>$year, 'branch_id'=>$branch_id, 'estado'=>'2' , 'credito' => '0'));
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost']*$pro[$i]['amount'];
                }
                $totall+= $row['total'];
        }
        $total = $totall-$cost; 
        return number_format($total,2,'.',',');
    }

    function total_vendido_por_fechas($initial, $final, $branch_id){
        //$branch_id = 18;
        $month = $mes;
        // $year = date('Y');
        $data = $this->db->query("SELECT * FROM sales WHERE DATE(date) >= DATE('$initial') AND DATE(date) <= DATE('$final') AND branch_id = '$branch_id' AND estado = 2 AND credito = 0");
        $cost=0;
        $totall=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost']*$pro[$i]['amount'];
                }
                $totall+= $row['total'];
        }
        $total = $totall-$cost; 
        return number_format($total,2,'.',',');
    }

    function total_vendido_sinFEL(){
        $branch_id = $this->session->userdata('branch_id');
        $hoy = date('Y-m-d');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE date = '$hoy' AND branch_id = '$branch_id' AND FEL = '0'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_vendido_FEL(){
        $branch_id = $this->session->userdata('branch_id');
        $hoy = date('Y-m-d');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE date = '$hoy' AND branch_id = '$branch_id' AND FEL = '1'")->row()->total;
        return $total;
    }

    function total_vendido_sinFEL_s(){
        $branch_id = $this->session->userdata('branch_id');
        $week = date('W');
        $year = date('Y');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE week = '$week' AND year = '$year'  AND branch_id = '$branch_id' AND FEL = '0'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_vendido_FEL_s(){
        $branch_id = $this->session->userdata('branch_id');
        $week = date('W');
        $year = date('Y');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE week = '$week' AND year = '$year' AND branch_id = '$branch_id' AND FEL = '1'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_vendido_sinFEL_m(){
        $branch_id = $this->session->userdata('branch_id');
        $month = date('m');
        $year = date('Y');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE month = '$month' AND year = '$year' AND branch_id = '$branch_id' AND FEL = '0'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_vendido_FEL_m(){
        $branch_id = $this->session->userdata('branch_id');
        $month = date('m');
        $year = date('Y');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE month = '$month' AND year = '$year' AND branch_id = '$branch_id' AND FEL = '1'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_vendido_semana(){
        $branch_id = $this->session->userdata('branch_id');
        $week = date('W');
        $year = date('Y');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE  week = '$week' AND year = '$year' AND branch_id = '$branch_id'  AND estado = '2'  AND credito = '0' ")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_vendido_mes(){
        $branch_id = $this->session->userdata('branch_id');
        $month = date('m');
        $year = date('Y');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE  month = '$month' AND year = '$year' AND branch_id = '$branch_id'  AND estado = '2'  AND credito = '0' ")->row()->total;
        return number_format($total,2,'.',',');
    }
    function total_vendido_mes2($mes, $year, $branch_id){
        //$branch_id = 18;
        $month = $mes;
        // $year = date('Y');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE  month = '$month' AND year = '$year' AND branch_id = '$branch_id'  AND estado = '2'  AND credito = '0' ")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_vendido_fechas($initial, $final, $branch_id){
        //$branch_id = 18;
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE DATE(date) >= DATE('$initial') AND DATE <= DATE('$final') AND branch_id = '$branch_id'  AND estado = '2'  AND credito = '0' ")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_vendido($initial = '', $final = ''){
        $branch_id = $this->session->userdata('branch_id');
        $hoy = date('Y-m-d');
        $query = "SELECT sum(total) as total FROM `sales` WHERE date = '$hoy' AND branch_id = '$branch_id' AND credito = '0' AND estado = '2'";
        if($initial != '' && $final != '') $query = "SELECT sum(total) as total FROM `sales` WHERE DATE(date) >= DATE('$initial') AND DATE(date) <= DATE('$final') AND branch_id = '$branch_id' AND credito = '0' AND estado = '2'";
        $total = $this->db->query($query)->row()->total;
        return number_format($total,2,'.',',');
    }
    function total_vendido2($dia,$branch_id){
        //$branch_id = 18;
        $hoy = $dia;
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE date = '$hoy' AND branch_id = '$branch_id' AND credito = '0' AND estado = '2'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_egresos($initial = '', $final = ''){
        $branch_id = $this->session->userdata('branch_id');
        $hoy = date('Y-m-d');
        $query = "SELECT sum(amount) as total FROM `expense` where status = '1' AND branch_id = '$branch_id' AND date = '$hoy'";
        if($initial != '' && $final != '') $query = "SELECT sum(amount) as total FROM `expense` where status = '1' AND branch_id = '$branch_id' AND DATE(date) >= DATE('$initial') AND DATE(date) <= DATE('$final')";
        $total = $this->db->query($query)->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_ingresos(){
        $month = date('m');
        $year  = date('Y');
        $total = $this->db->query("SELECT sum(amount) as total FROM `income` where status = '1' AND month = '$month' AND year ='$year' ")->row()->total;
        return $total;
    }

    function total_egresoss(){
        $month = date('m');
        $year  = date('Y');
        $total = $this->db->query("SELECT sum(amount) as total FROM `expense` WHERE status = '1' AND month = '$month' AND year ='$year'")->row()->total;
        return $total;
    }

    function expenses_dates($date_initial, $date_final, $tipo = '')
    {
        $branch_id = $this->session->userdata('branch_id');
        if ($tipo != '') {
            $total = $this->db->query("SELECT sum(amount) as total FROM `expense` WHERE branch_id = '$branch_id' AND status = '1' AND table_reference = '$tipo' AND (date BETWEEN '$date_initial' AND '$date_final')")->row()->total;
        }
        else{
            $total = $this->db->query("SELECT sum(amount) as total FROM `expense` WHERE branch_id = '$branch_id' AND status = '1' AND (table_reference = '' OR table_reference IS NULL) AND (date BETWEEN '$date_initial' AND '$date_final')")->row()->total;
        }
        return $total;
    }

    function total_losses_dates($date_initial, $date_final)
    {
        $branch_id = $this->session->userdata('branch_id');
        $total = $this->db->query("SELECT SUM(loss) AS total FROM losse_returns WHERE status = 1 AND branch_id = '$branch_id' AND (date BETWEEN '$date_initial' AND '$date_final')")->row()->total;
        return $total;
    }

    function ganancia_dates($date_initial, $date_final)
    {
        $branch_id = $this->session->userdata('branch_id');
        $data = $this->db->query("SELECT * FROM sales WHERE branch_id = '$branch_id' AND status = 1 AND (estado = 2 OR estado = 4) AND (date BETWEEN '$date_initial' AND '$date_final')");
        $cost=0;
        $total=0;
        foreach($data->result_array() as $row){
                for ($i=0; $i < $row['num_products'] ; $i++){
                    if ($row['products'] != "" || $row['products'] != null) {
                        $pro = json_decode($row['products'],true);
                        }else{
                        $pro = array();
                    }
                    $cost += $pro[$i]['cost'];
                }
                $total += $row['total'];
        }
        $total = $total-$cost; 
        return $total;
    }

    function total_ingresos_ms(){
        $month = date('m')-1;
        $year  = date('Y');
        $total = $this->db->query("SELECT sum(amount) as total FROM `income` where status = '1' AND month = '$month' AND year ='$year' ")->row()->total;
        return $total;
    }

    function total_egresoss_ms(){
        $month = date('m')-1;
        $year  = date('Y');
        $total = $this->db->query("SELECT sum(amount) as total FROM `expense` WHERE status = '1' AND month = '$month' AND year ='$year'")->row()->total;
        return $total;
    }

    function total_empleados(){
        $total = $this->db->query("SELECT count(type) as total FROM `admin` WHERE type = '2'")->row()->total;
        return $total;
    }

    function total_admins(){
        $total = $this->db->query("SELECT count(type) as total FROM `admin` WHERE type = '1'")->row()->total;
        return $total;
    }

    function total_proveedores(){
        $total = $this->db->query("SELECT count(provider_id) as total FROM `provider` WHERE status = '1'")->row()->total;
        return $total;
    }

    function total_clientes(){
        $total = $this->db->query("SELECT count(client_id) as total FROM `client` WHERE status = '1'")->row()->total;
        return $total;
    }

    function ganancia_dia(){
        $branch_id = $this->session->userdata('branch_id');
        $hoy = date('Y-m-d');
        $total = $this->db->query("SELECT sum(amount) as total FROM `income` WHERE  status ='1' AND date ='$hoy' AND branch_id = '$branch_id'")->row()->total;
        return number_format($total,2,'.',',');
    }


    function ganancia_semana(){
        $semana = date('W');
        $year = date('Y');
        $total = $this->db->query("SELECT sum(amount) as total FROM `income` WHERE  status ='1' AND week ='$semana' AND year = '$year'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function ganancia_mes()
    {
        $mes = date('m');
        $year = date('Y');
        $total = $this->db->query("SELECT sum(amount) as total FROM `income` WHERE  status ='1' AND month ='$mes' AND year = '$year'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_anulaciones(){
        $year  = date('Y');
        for ($i=1; $i < 13 ; $i++) { 
            $total = $this->db->query("SELECT count(estado) as total FROM `sales` WHERE estado = '3' AND month = '$i' AND year ='$year' ")->row()->total;
            echo $total.',';
        }
    }

    function total_cambios(){
        $year  = date('Y');
        for ($i=1; $i < 13 ; $i++) { 
            $total = $this->db->query("SELECT count(estado) as total FROM `sales` WHERE estado = '4' AND month = '$i' AND year ='$year' ")->row()->total;
            echo $total.',';
        }
    }

    function total_ventas(){
        $year  = date('Y');
        for ($i=1; $i < 13 ; $i++) { 
            $total = $this->db->query("SELECT count(estado) as total FROM `sales` WHERE estado = '2' AND month = '$i' AND year ='$year' ")->row()->total;
            echo $total.',';
        }
    }

    function total_anulaciones_ms(){
        $month = date('m');
        $year  = date('Y');
        if($month == 1 ){
            $month = 12;
            $year  = date('Y')-1;
        }else{
            $month = date('m')-1;
        }
        $total = $this->db->query("SELECT count(estado) as total FROM `sales` WHERE estado = '3' AND month = '$month' AND year ='$year' ")->row()->total;
        return $total;
    }

    function total_cambios_ms(){
        $month = date('m');
        $year  = date('Y');
        if($month == 1 ){
            $month = 12;
            $year  = date('Y')-1;
        }else{
            $month = date('m')-1;
        }
        $total = $this->db->query("SELECT count(estado) as total FROM `sales` WHERE estado = '4' AND month = '$month' AND year ='$year' ")->row()->total;
        return $total;
    }

    function total_ventas_ms(){
        $month = date('m');
        $year  = date('Y');
        if($month == 1 ){
            $month = 12;
            $year  = date('Y')-1;
        }else{
            $month = date('m')-1;
        }
        $total = $this->db->query("SELECT count(estado) as total FROM `sales` WHERE estado = '2' AND month = '$month' AND year ='$year' ")->row()->total;
        return $total;
    }

    function get_venta_diarias($i){
        $month = date('m');
        $year  = date('Y');
        $total = $this->db->query("SELECT count(sales_id) as total FROM `sales` WHERE estado = '2' AND day ='$i' AND month = '$month' AND year ='$year'")->row()->total;
        return $total;
    }

    function get_venta_mesuales(){
        $hoy = date('m');
        $ingresos = $this->db->query("SELECT sum(amount) as total FROM `income` WHERE  status ='1' AND month ='$hoy'")->row()->total;
        $monto_limite = $this->db->get_where('settings', array('type'=>'meta_mensual'))->row()->description;
        $total  = (100*$ingresos)/$monto_limite;
        if($total > 0){
            return number_format($total,2,'.',',');
        }else{
            return number_format(0,2,'.',',');
        }
    }

    function total_anulaciones_diarias(){
        $hoy   = date('Y-m-d');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE estado = '3' AND applied_date = '$hoy'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_anulaciones_semanales(){
        $semana = date('W');
        $year   = date('Y');
        $total  = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE estado = '3' AND WEEK(applied_date) = '$semana' AND year ='$year' ")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_anulaciones_mensuales(){
        $mont  = date('m');
        $year  = date('Y');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE estado = '3' AND MONTH(applied_date) = '$mont' AND year ='$year' ")->row()->total;
        return number_format($total,2,'.',',');
    }
    
    function get_anulaciones_diarias($i){
        $total = $this->db->query("SELECT count(estado) as total FROM `sales` WHERE estado = '3' AND DAY(applied_date) ='$i'")->row()->total;
        return $total;
    }

    function meses_anulaciones(){
        for ($i=1; $i < 13 ; $i++) { 
            $total = $this->db->query("SELECT COUNT(sales_id) as total FROM sales WHERE estado = '3' AND MONTH(applied_date) ='$i'");
            echo $total->row()->total.',';
        }
    }

    function total_cambios_diarios(){
        $hoy   = date('Y-m-d');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE estado = '4' AND applied_date = '$hoy'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_cambios_semanales(){
        $semana = date('W');
        $year   = date('Y');
        $total  = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE estado = '4' AND WEEK(applied_date) = '$semana' AND year ='$year' ")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_cambios_mensuales(){
        $mont  = date('m');
        $year  = date('Y');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE estado = '4' AND MONTH(applied_date) = '$mont' AND year ='$year' ")->row()->total;
        return number_format($total,2,'.',',');
    }
    
    function get_cambios_diarios($i){
        $total = $this->db->query("SELECT count(estado) as total FROM `sales` WHERE estado = '4' AND DAY(applied_date) ='$i'")->row()->total;
        return $total;
    }

    function meses_cambios(){
        for ($i=1; $i < 13 ; $i++) { 
            $total = $this->db->query("SELECT COUNT(sales_id) as total FROM sales WHERE estado = '4' AND MONTH(applied_date) ='$i'");
            echo $total->row()->total.',';
        }
    }
    
    function total_venta_f(){
        $month = date('m');
        $year  = date('Y');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE estado = 2 AND month = '$month' AND year ='$year'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_efectivo_f(){
        $branch_id = $this->session->userdata('branch_id');
        $month = date('m');
        $year  = date('Y');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE estado = 2 AND month = '$month' AND year ='$year' AND metodo = 'Efectivo' AND branch_id = '$branch_id'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_transferencia_f(){
        $branch_id = $this->session->userdata('branch_id');
        $month = date('m');
        $year  = date('Y');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE estado = 2 AND month = '$month' AND year ='$year' AND metodo = 'Transferencia' AND branch_id = '$branch_id'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_cheque_f(){
        $branch_id = $this->session->userdata('branch_id');
        $month = date('m');
        $year  = date('Y');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE estado = 2 AND month = '$month' AND year ='$year' AND metodo = 'Cheque' AND branch_id = '$branch_id'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_deposito_f(){
        $branch_id = $this->session->userdata('branch_id');
        $month = date('m');
        $year  = date('Y');
        $total = $this->db->query("SELECT sum(total) as total FROM `sales` WHERE estado = 2 AND month = '$month' AND year ='$year' AND metodo = 'Deposito' AND branch_id = '$branch_id'")->row()->total;
        return number_format($total,2,'.',',');
    }

    function total_banco_f($banco){
        $month = date('m');
        $year  = date('Y');
        $branch_id = $this->session->userdata('branch_id');
        $total = $this->db->query("SELECT sum(amount) as total FROM `income` WHERE status = 1 AND month = '$month' AND year ='$year' AND origin = '$banco' AND branch_id = '$branch_id' ")->row()->total;
        return number_format($total,2,'.',',');
    }

    function products_returned_lost($product_details_id)
    {
        return $this->db->query("SELECT SUM(amount) as total FROM `product_details` WHERE activity_ref = '$product_details_id' AND status = 1 AND (type = 3 OR type = 4)")->row()->total;
    }

    function getName($type, $id){
        $name      = $this->db->get_where($type, array($type.'_id'=>$id))->row()->name;
        $last_name = $this->db->get_where($type, array($type.'_id'=>$id))->row()->last_name;
        return $name.' '.$last_name;
    }

    function getSingleName($type, $id){
        $name      = $this->db->get_where($type, array($type.'_id'=>$id))->row()->name;
        return $name;
    }

    function getBranch($ID){
        $name = $this->db->get_where('branch', array('branch_id'=>$ID))->row()->name;
        return $name;
    }

    function getNameSistema(){
        $name =  $this->db->get_where('settings', array('type'=>'name'))->row()->description;
        return $name;
    }

    function top_8_products()
    {
        $productos = $this->db->query("SELECT count(type) as total, products_id FROM `product_details` WHERE type = '0' GROUP BY  products_id ORDER BY total DESC LIMIT 8");
        return $productos;
    }

    function create_sucursal(){
        $data['name']    = $this->input->post('name');
        $data['manager'] = $this->input->post('manager');    
        $data['phone']   = $this->input->post('phone');    
        $data['tel']     = $this->input->post('tel');    
        $data['address'] = trim($this->input->post('address'));    
        $data['email']   = $this->input->post('email');    
        $this->db->insert('branch', $data);
        $id = $this->db->insert_id();
        
        $data2['sucursal'] = serialize($id); 
        $this->db->where('admin_id',$data['manager']);
        $this->db->update('admin',$data2);
        
        
        $data_sucursal = $this->db->get_where('admin', array('admin_id'=>1))->row()->sucursal;
        $sucursales = unserialize($data_sucursal);
            array_push($sucursales, $id);
        $data3['sucursal'] = serialize($sucursales); 
        $this->db->where('admin_id','1');
        $this->db->update('admin',$data3);
        
        
        $message = 'Ha ingresado una nueva sucursal denominada '.$this->input->post('name');
        $this->insert_binnacle($message);
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
        $this->insert_binnacle($message);
        
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
        

        $message = 'Ha eliminado la sucursal denominada '.$this->db->get_where('branch', array('branch_id'=>$ID))->row()->name;
        $this->insert_binnacle($message);
    }

    function create_admins(){
        
        $contraseña = $this->getPassword();
        $username = $this->getPin();

        if($_FILES['foto']['tmp_name'] != ''){
                
            $data['img'] = $username.'.jpg';

            move_uploaded_file($_FILES["foto"]["tmp_name"], "uploads/img/". $username.'.jpg');
        }

        $data['name']          = $this->input->post('name');
        $data['last_name']     = $this->input->post('last_name');
        $data['email']         = $this->input->post('email');    
        $data['password']      = sha1($contraseña); 
        $data['username']      = $username;
        $data['type']          = 1;
        $data['phone']         = $this->input->post('phone'); 
        $data['productos']     = 1;
        $data['reportes']      = 1;
        $data['usuarios']      = 1;
        $data['ajustes']       = 1;
        $data['herramientas']  = 1;
        $data['contabilidad']  = 1;
        $data['sucursales']    = 1; 
        $data['cui']           = $this->input->post('cui');    
        $data['birthday']      = date("Y-m-d", strtotime($this->input->post('birthday')));    
        $data['salary']        = $this->input->post('salary');    
        $data['address']       = trim($this->input->post('address'));
        $data['hiring']        = date("Y-m-d", strtotime($this->input->post('hiring')));
        $data['sucursal']      = serialize($this->input->post('branch'));
        $data['prueba']        = base64_encode($contraseña);

        $this->db->insert('admin', $data);

        $message = 'Ha creado un nuevo administrador llamado '.$this->input->post('name').' '.$this->input->post('last_name');
        $this->insert_binnacle($message);

        $destino = $this->input->post('email');

        $msg = 'Nuevo mensaje recibido a tráves del sitio web, los datos son los siguientes: <br><br>';
        $msg .= '<b>Nombre:</b> '.$this->input->post('name').''.$this->input->post('last_name')."<br>";
        $msg .= '<b>Correo:</b> '.$this->input->post('email')."<br>";
        $msg .= '<b>Usuario:</b> '.$username."<br>";
        $msg .= '<b>Contraseña:</b> '.$contraseña."<br>";

        require("class.phpmailer.php");
        $emails = $destino;
        $mail = new PHPMailer(); 
        $mail->CharSet = 'UTF-8';
        $mail->IsHTML(true);
        $mail->IsMail();
        $mail->addReplyTo($this->input->post('email'),$this->input->post('name'));
        $mail->Subject = 'Datos de Ingreso';
        $mail->SetFrom('no-reply@msbox.gt', 'Datos de Ingreso');
        $mail->AddAddress($emails);
        $data = array(
            'email_msg' => $msg,
            'asuntico' => 'Datos de Ingreso'
        );
        $mail->Body = $this->load->view('backend/mails/notify.php',$data,TRUE);
        if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }

    }

    function update_admins($ID){

        if($_FILES['img']['tmp_name'] != ''){
                
            $data['img'] = $this->input->post('username').'.jpg';

            move_uploaded_file($_FILES["img"]["tmp_name"], "uploads/img/". $this->input->post('username').'.jpg');
        }

        $data['name']          = $this->input->post('name');
        $data['last_name']     = $this->input->post('last_name');
        $data['email']         = $this->input->post('email');    
        $data['phone']         = $this->input->post('phone');
        $data['type']          = $this->input->post('type');    
        $data['salary']        = $this->input->post('salary');    
        $data['address']       = trim($this->input->post('address'));
        $data['gm_id']         = $this->input->post('gm_id');    
        $data['gm_email']      = $this->input->post('gm_email');
        $data['sucursal']      = serialize($this->input->post('branch'));


        $anterior = $this->input->post('password');
        $new      = $this->input->post('new_password');
        $antigua  = $this->db->get_where('admin', array('admin_id'=>$ID))->row()->password;

        if(sha1($anterior) == $antigua){
            if($new!=''){
                $data['password'] = sha1($new);
            }
        }

        
        $this->db->where('admin_id', $ID);
        $this->db->update('admin', $data);

        $message = 'Ha actualizado un administrador llamado '.$this->getName('admin', $ID);
        $this->insert_binnacle($message);

        $message2 = 'Ha modificado tu información.';
        $this->insert_notification($message2, base64_encode('admin/perfil/'), 'perfil', 'Perfil', $ID);
    }
    
    function update_admin_modal($ID){
        if($_FILES['img']['tmp_name'] != ''){
                
            $data['img'] = $this->input->post('username').'.jpg';

            move_uploaded_file($_FILES["img"]["tmp_name"], "uploads/img/". $this->input->post('username').'.jpg');
        }
        
        $data['name']          = $this->input->post('name');
        $data['last_name']     = $this->input->post('last_name');
        $data['email']         = $this->input->post('email');    
        $data['phone']         = $this->input->post('phone'); 
        $data['productos']     = $this->input->post('productos');
        $data['reportes']      = $this->input->post('reportes');
        $data['usuarios']      = $this->input->post('usuarios');
        $data['herramientas']  = $this->input->post('herramientas');
        $data['contabilidad']  = $this->input->post('contabilidad');
        $data['sucursales']    = $this->input->post('sucursales');
        $data['cui']           = $this->input->post('cui');
        $data['birthday']      = date("Y-m-d", strtotime($this->input->post('birthday')));    
        $data['salary']        = $this->input->post('salary');    
        $data['address']       = trim($this->input->post('address'));
        $data['hiring']        = date("Y-m-d", strtotime($this->input->post('hiring')));
        $data['job']           = $this->input->post('job');    
        $data['sucursal']      = serialize($this->input->post('branch'));
        $new_password          = $this->input->post('new_password');
        if($new_password != ''){
            $data['password']  = sha1($new_password);
        }

        $this->db->where('admin_id', $ID);
        $this->db->update('admin', $data);

        $message = 'Ha actualizado un empleado llamado '.$this->getName('admin', $ID);
        $this->insert_binnacle($message);
        
        $message2 = 'Ha modificado tu información.';
        $this->insert_notification($message2, base64_encode('admin/perfil/'), 'perfil', 'Perfil', $ID);
    }

    function update_empleado_profile($ID){

        if($_FILES['img']['tmp_name'] != ''){
                
            $data['img'] = $this->input->post('username').'.jpg';

            move_uploaded_file($_FILES["img"]["tmp_name"], "uploads/img/". $this->input->post('username').'.jpg');
        }
        
        $data['name']          = $this->input->post('name');
        $data['last_name']     = $this->input->post('last_name');
        $data['email']         = $this->input->post('email');    
        $data['phone']         = $this->input->post('phone'); 
        $data['emergency_phone'] = $this->input->post('emergency_phone');
        $data['account_number'] = trim((string) $this->input->post('account_number'));
        $data['bank_reference'] = trim((string) $this->input->post('bank_reference'));
        $data['cui']           = $this->input->post('cui');    
        $data['birthday']      = $this->parse_date_input($this->input->post('birthday'));    
        $data['salary']        = $this->input->post('salary');  
        $data['bonus']         = $this->input->post('bonus');
        $complemento = $this->input->post('complemento');
        $data['complemento']   = ($complemento === '' || $complemento === null) ? 0 : max(0, (float) $complemento);
        $data['address']       = trim($this->input->post('address'));
        $data['hiring']        = $this->parse_date_input($this->input->post('hiring'));
        $data['job']           = $this->input->post('job');    
        $data['sucursal']      = serialize($this->input->post('branch'));

        $anterior = $this->input->post('password');
        $new      = $this->input->post('new_password');
        $antigua  = $this->db->get_where('admin', array('admin_id'=>$ID))->row()->password;

        if(sha1($anterior) == $antigua){
            if($new!=''){
                $data['password'] = sha1($new);
            }
        }
       
        $this->db->where('admin_id', $ID);
        $this->db->update('admin', $data);

        $message = 'Ha actualizado un empleado llamado '.$this->getName('admin', $ID);
        $this->insert_binnacle($message);
        
        $message2 = 'Ha modificado tu información.';
        $this->insert_notification($message2, base64_encode('admin/perfil/'), 'informacion', 'Perfil', $ID);
    }

    function delete_admins($ID){
        $data['status']    = 0;
        $this->db->where('admin_id', $ID);
        $this->db->update('admin', $data);

        $message = 'Ha eliminado un administrador llamado '.$this->getName('admin', $ID);
        $this->insert_binnacle($message);
    }
    
    function active_admins($ID){
        $data['status']    = 1;
        $this->db->where('admin_id', $ID);
        $this->db->update('admin', $data);
        $message = 'Ha re-activado un administrador llamado '.$this->getName('admin', $ID);
        $this->insert_binnacle($message);
    }

    function create_employee(){
        $contraseña = $this->getPassword();
        $username = $this->getPin();

        if($_FILES['foto']['tmp_name'] != ''){
                
            $data['img'] = $username.'.jpg';

            move_uploaded_file($_FILES["foto"]["tmp_name"], "uploads/img/". $username.'.jpg');
        }
        $salary = $this->input->post('salary');
        if ($salary == "") {
            $salary = 0;
        }
        $bonus = $this->input->post('bonus');
        if ($bonus == "") {
            $bonus = 0;
        }
        $complemento = $this->input->post('complemento');
        if ($complemento == "") {
            $complemento = 0;
        }

        $data['name']          = $this->input->post('name');
        $data['last_name']     = $this->input->post('last_name');
        $data['email']         = $this->input->post('email');    
        $data['password']      = sha1($contraseña); 
        $data['username']      = $username;
        $data['type']          = 2;
        $data['phone']         = $this->input->post('phone'); 
        $data['emergency_phone'] = $this->input->post('emergency_phone');
        $data['sucursal']      = serialize($this->input->post('branch'));
        $data['cui']           = $this->input->post('cui');    
        $data['birthday']      = $this->parse_date_input($this->input->post('birthday'));    
        $data['salary']        = $salary;
        $data['bonus']         = $bonus;
        $data['complemento']   = max(0, (float) $complemento);
        $data['address']       = trim($this->input->post('address'));
        $data['hiring']        = $this->parse_date_input($this->input->post('hiring'));
        $data['job']           = $this->input->post('job');
        $data['prueba']        = base64_encode($contraseña);
        $this->db->insert('admin', $data);
        $empleado_id = $this->db->insert_id();

        $message = 'Ha creado un nuevo empleado llamado '.$this->input->post('name').' '.$this->input->post('last_name');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/perfil_empleado/'.$empleado_id), 'empleados', 'Usuarios');

        $destino = $this->input->post('email');

        $msg = 'Nuevo mensaje recibido a tráves del sitio web, los datos son los siguientes: <br><br>';
        $msg .= '<b>Nombre:</b> '.$this->input->post('name').''.$this->input->post('last_name')."<br>";
        $msg .= '<b>Correo:</b> '.$this->input->post('email')."<br>";
        $msg .= '<b>Usuario:</b> '.$username."<br>";
        $msg .= '<b>Contraseña:</b> '.$contraseña."<br>";

        require("class.phpmailer.php");
        $emails = $destino;
        $mail = new PHPMailer(); 
        $mail->CharSet = 'UTF-8';
        $mail->IsHTML(true);
        $mail->IsMail();
        $mail->addReplyTo($this->input->post('email'),$this->input->post('name'));
        $mail->Subject = 'Datos de Ingreso';
        $mail->SetFrom('admin@depot.com', 'Datos de Ingreso');
        $mail->AddAddress($emails);
        $data = array(
            'email_msg' => $msg,
            'asuntico' => 'Datos de Ingreso'
        );
        $mail->Body = $this->load->view('backend/mails/notify.php',$data,TRUE);
        if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }

    function update_employee($ID){
        
        $data['name']          = $this->input->post('name');
        $data['last_name']     = $this->input->post('last_name');
        $data['email']         = $this->input->post('email');    
        $data['phone']         = $this->input->post('phone'); 
        $data['cui']           = $this->input->post('cui');    
        $data['birthday']      = $this->parse_date_input($this->input->post('birthday'));    
        $data['salary']        = $this->input->post('salary'); 
        $data['bonus']         = $this->input->post('bonus');   
        $data['address']       = trim($this->input->post('address'));
        $data['hiring']        = $this->parse_date_input($this->input->post('hiring'));
        $data['job']           = $this->input->post('job');    
        $data['sucursal']      = serialize($this->input->post('branch'));   
        $this->db->where('admin_id', $ID);
        $this->db->update('admin', $data);

        $message = 'Ha actualizado un empleado llamado '.$this->getName('admin', $ID);
        $this->insert_binnacle($message);
    }

    function delete_employee($ID){
        $data['status']    = 0;
        $this->db->where('admin_id', $ID);
        $this->db->update('admin', $data);

        $message = 'Ha eliminado un empleado llamado '.$this->getName('admin', $ID);
        $this->insert_binnacle($message);
    }
    
    function active_employee($ID){
        $data['status']    = 1;
        $this->db->where('admin_id', $ID);
        $this->db->update('admin', $data);
        $message = 'Ha re-activado un empelado llamado '.$this->getName('admin', $ID);
        $this->insert_binnacle($message);
    }

    function send_credentials($ID){

        $destino = $this->db->get_where('admin', array('admin_id'=>$ID))->row()->email;
        $username = $this->db->get_where('admin', array('admin_id'=>$ID))->row()->username;
        $contraseña = $this->db->get_where('admin', array('admin_id'=>$ID))->row()->prueba;


        $msg = 'Nuevo mensaje recibido a tráves del sitio web, los datos son los siguientes: <br><br>';
        $msg .= '<b>Usuario:</b> '.$username."<br>";
        $msg .= '<b>Contraseña:</b> '.base64_decode($contraseña)."<br>";

        require("class.phpmailer.php");
        $emails = $destino;
        $mail = new PHPMailer(); 
        $mail->CharSet = 'UTF-8';
        $mail->IsHTML(true);
        $mail->IsMail();
        $mail->addReplyTo($destino);
        $mail->Subject = 'Credenciales';
        $mail->SetFrom('admin@depot.com', 'Credenciales');
        $mail->AddAddress($emails);
        $data = array(
            'email_msg' => $msg,
            'asuntico' => 'Credenciales'
        );
        $mail->Body = $this->load->view('backend/mails/notify.php',$data,TRUE);
        if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    }

    function create_rol()
    {
        $data['name']        = $this->input->post('name');
        $data['description'] = trim($this->input->post('description'));

        $usuarios = $this->input->post('usuarios');
        if ($usuarios == 1) {
            $empleados = $this->input->post('empleados');
            if ($empleados == 1) {
                $crear_empleados = $this->input->post('crear_empleados');
                $editar_empleados = $this->input->post('editar_empleados');
                $estado_empleados = $this->input->post('estado_empleados');
                $reportes_empleados = $this->input->post('reportes_empleados');
            }
            else {
                $crear_empleados = 0;
                $editar_empleados = 0;
                $estado_empleados = 0;
                $reportes_empleados = 0;
            }
            $roles = $this->input->post('roles');
            if ($roles == 1) {
                $crear_roles = $this->input->post('crear_roles');
                $editar_roles = $this->input->post('editar_roles');
                $estado_roles = $this->input->post('estado_roles');
                $reportes_roles = $this->input->post('reportes_roles');
            }
            else {
                $crear_roles = 0;
                $editar_roles = 0;
                $estado_roles = 0;
                $reportes_roles = 0;
            }
            $admins = $this->input->post('admins');
            if ($admins == 1) {
                $crear_admins = $this->input->post('crear_admins');
                $editar_admins = $this->input->post('editar_admins');
                $estado_admins = $this->input->post('estado_admins');
                $reportes_admins = $this->input->post('reportes_admins');
            }
            else {
                $crear_admins = 0;
                $editar_admins = 0;
                $estado_admins = 0;
                $reportes_admins = 0;
            }
            $clientes = $this->input->post('clientes');
            if ($clientes == 1) {
                $crear_clientes = $this->input->post('crear_clientes');
                $editar_clientes = $this->input->post('editar_clientes');
                $estado_clientes = $this->input->post('estado_clientes');
                $reportes_clientes = $this->input->post('reportes_clientes');
            }
            else {
                $crear_clientes = 0;
                $editar_clientes = 0;
                $estado_clientes = 0;
                $reportes_clientes = 0;
            }
            $proveedores = $this->input->post('proveedores');
            if ($proveedores == 1) {
                $crear_proveedores = $this->input->post('crear_proveedores');
                $editar_proveedores = $this->input->post('editar_proveedores');
                $estado_proveedores = $this->input->post('estado_proveedores');
                $reportes_proveedores = $this->input->post('reportes_proveedores');
            }
            else {
                $crear_proveedores = 0;
                $editar_proveedores = 0;
                $estado_proveedores = 0;
                $reportes_proveedores = 0;
            }
            $codigos = $this->input->post('codigos');
            if ($codigos == 1) {
                $guardar_codigos = $this->input->post('guardar_codigos');
            }
            else {
                $guardar_codigos = 0;
            }
        }
        else{
            $empleados = 0;
            $crear_empleados = 0;
            $editar_empleados = 0;
            $estado_empleados = 0;
            $reportes_empleados = 0;
            $roles = 0;
            $crear_roles = 0;
            $editar_roles = 0;
            $estado_roles = 0;
            $reportes_roles = 0;
            $admins = 0;
            $crear_admins = 0;
            $editar_admins = 0;
            $estado_admins = 0;
            $reportes_admins = 0;
            $clientes = 0;
            $crear_clientes = 0;
            $editar_clientes = 0;
            $estado_clientes = 0;
            $reportes_clientes = 0;
            $proveedores = 0;
            $crear_proveedores = 0;
            $editar_proveedores = 0;
            $estado_proveedores = 0;
            $reportes_proveedores = 0;
            $codigos = 0;
            $guardar_codigos = 0;
        }
        $productos = $this->input->post('productos');
        if ($productos == 1) {
            $inventario = $this->input->post('inventario');
            if ($inventario == 1) {
                $crear_productos = $this->input->post('crear_productos');
                $editar_productos = $this->input->post('editar_productos');
                $estado_productos = $this->input->post('estado_productos');
                $reportes_productos = $this->input->post('reportes_productos');
                $alertas_productos = $this->input->post('alertas_productos');
                $reportes_alertas = $this->input->post('reportes_alertas');
            }
            else {
                $crear_productos = 0;
                $editar_productos = 0;
                $estado_productos = 0;
                $reportes_productos = 0;
                $alertas_productos = 0;
                $reportes_alertas = 0;
            }
            $traslados = $this->input->post('traslados');
            if ($traslados == 1) {
                $crear_traslados = $this->input->post('crear_traslados');
                $eliminar_traslados = $this->input->post('eliminar_traslados');
                $reportes_traslados = $this->input->post('reportes_traslados');
            }
            else {
                $crear_traslados = 0;
                $eliminar_traslados = 0;
                $reportes_traslados = 0;
            }
            $categorias = $this->input->post('categorias');
            if ($categorias == 1) {
                $crear_categorias = $this->input->post('crear_categorias');
                $editar_categorias = $this->input->post('editar_categorias');
                $eliminar_categorias = $this->input->post('eliminar_categorias');
                $reportes_categorias = $this->input->post('reportes_categorias');
            }
            else {
                $crear_categorias = 0;
                $editar_categorias = 0;
                $eliminar_categorias = 0;
                $reportes_categorias = 0;
            }
            $marcas = $this->input->post('marcas');
            if ($marcas == 1) {
                $crear_marcas = $this->input->post('crear_marcas');
                $editar_marcas = $this->input->post('editar_marcas');
                $eliminar_marcas = $this->input->post('eliminar_marcas');
                $reportes_marcas = $this->input->post('reportes_marcas');
            }
            else {
                $crear_marcas = 0;
                $editar_marcas = 0;
                $eliminar_marcas = 0;
                $reportes_marcas = 0;
            }
        }
        else {
            $inventario = 0;
            $crear_productos = 0;
            $editar_productos = 0;
            $estado_productos = 0;
            $reportes_productos = 0;
            $alertas_productos = 0;
            $reportes_alertas = 0;
            $traslados = 0;
            $crear_traslados = 0;
            $eliminar_traslados = 0;
            $reportes_traslados = 0;
            $categorias = 0;
            $crear_categorias = 0;
            $editar_categorias = 0;
            $eliminar_categorias = 0;
            $reportes_categorias = 0;
            $marcas = 0;
            $crear_marcas = 0;
            $editar_marcas = 0;
            $eliminar_marcas = 0;
            $reportes_marcas = 0;
        }
        $herramientas = $this->input->post('herramientas');
        if ($herramientas == 1) {
            $ventas = $this->input->post('ventas');
            if ($ventas == 1) {
                $ver_ventas = $this->input->post('ver_ventas');
                $crear_ventas = $this->input->post('crear_ventas');
                $eliminar_ventas = $this->input->post('eliminar_ventas');
                $reportes_ventas = $this->input->post('reportes_ventas');
            }
            else {
                $ver_ventas = 0;
                $crear_ventas = 0;
                $eliminar_ventas = 0;
                $reportes_ventas = 0;
            }
            $compras = $this->input->post('compras');
            if ($compras == 1) {
                $ver_compras = $this->input->post('ver_compras');
                $crear_compras = $this->input->post('crear_compras');
                $crear_solicitud = $this->input->post('crear_solicitud');
                $ver_solicitud = $this->input->post('ver_solicitud');
                $completar_solicitud = $this->input->post('completar_solicitud');
                $reportes_compras = $this->input->post('reportes_compras');
            }
            else {
                $ver_compras = 0;
                $crear_compras = 0;
                $crear_solicitud = 0;
                $ver_solicitud = 0;
                $completar_solicitud = 0;
                $reportes_compras = 0;
            }
            $cotizaciones = $this->input->post('cotizaciones');
            if ($cotizaciones == 1) {
                $crear_cotizaciones = $this->input->post('crear_cotizaciones');
                $ver_cotizaciones = $this->input->post('ver_cotizaciones');
                $asignar_cotizaciones = $this->input->post('asignar_cotizaciones');
                $editar_cotizaciones = $this->input->post('editar_cotizaciones');
                $eliminar_cotizaciones = $this->input->post('eliminar_cotizaciones');
                $reportes_cotizaciones = $this->input->post('reportes_cotizaciones');
            }
            else {
                $crear_cotizaciones = 0;
                $ver_cotizaciones = 0;
                $asignar_cotizaciones = 0;
                $editar_cotizaciones = 0;
                $eliminar_cotizaciones = 0;
                $reportes_cotizaciones = 0;
            }
            $creditos = $this->input->post('creditos');
            if ($creditos == 1) {
                $eliminar_creditos = $this->input->post('eliminar_creditos');
                $reportes_creditos = $this->input->post('reportes_creditos');
                $pagos             = $this->input->post('pagos');
                $eliminar_pagos    = $this->input->post('eliminar_pagos');
            }
            else {
                $eliminar_creditos = 0;
                $reportes_creditos = 0;
                $pagos             = 0;
                $eliminar_pagos    = 0;
            }
            $anulaciones = $this->input->post('anulaciones');
            if ($anulaciones == 1) {
                $ver_anulaciones = $this->input->post('ver_anulaciones');
                $crear_anulaciones = $this->input->post('crear_anulaciones');
                $eliminar_anulaciones = $this->input->post('eliminar_anulaciones');
                $reportes_anulaciones = $this->input->post('reportes_anulaciones');
            }
            else {
                $ver_anulaciones = 0;
                $crear_anulaciones = 0;
                $eliminar_anulaciones = 0;
                $reportes_anulaciones = 0;
            }
            $cambios = $this->input->post('cambios');
            if ($cambios == 1) {
                $ver_cambios = $this->input->post('ver_cambios');
                $crear_cambios = $this->input->post('crear_cambios');
                $eliminar_cambios = $this->input->post('eliminar_cambios');
                $reportes_cambios = $this->input->post('reportes_cambios');
            }
            else {
                $ver_cambios = 0;
                $crear_cambios = 0;
                $eliminar_cambios = 0;
                $reportes_cambios = 0;
            }
            $envios = $this->input->post('envios');
            if ($envios == 1) {
                $ver_envios = $this->input->post('ver_envios');
                $registrar_entregas = $this->input->post('registrar_entregas');
                $cancelar_envios = $this->input->post('cancelar_envios');
                $reportes_envios = $this->input->post('reportes_envios');
                $editar_entregas = $this->input->post('editar_entregas');
                $eliminar_entregas = $this->input->post('eliminar_entregas');
            }
            else {
                $ver_envios = 0;
                $registrar_entregas = 0;
                $cancelar_envios = 0;
                $reportes_envios = 0;
                $editar_entregas = 0;
                $eliminar_entregas = 0;
            }
            $transportes = $this->input->post('transportes');
            if ($transportes == 1) {
                $crear_transportes = $this->input->post('crear_transportes');
                $editar_transportes = $this->input->post('editar_transportes');
                $eliminar_transportes = $this->input->post('eliminar_transportes');
                $reportes_transportes = $this->input->post('reportes_transportes');
                $registrar_servicios = $this->input->post('registrar_servicios');
                $reportes_servicios = $this->input->post('reportes_servicios');
            }
            else {
                $crear_transportes = 0;
                $editar_transportes = 0;
                $eliminar_transportes = 0;
                $reportes_transportes = 0;
                $registrar_servicios = 0;
                $reportes_servicios = 0;
            }
        }
        else{
            $ventas = 0;
            $ver_ventas = 0;
            $crear_ventas = 0;
            $eliminar_ventas = 0;
            $reportes_ventas = 0;
            $compras = 0;
            $ver_compras = 0;
            $crear_compras = 0;
            $crear_solicitud = 0;
            $ver_solicitud = 0;
            $completar_solicitud = 0;
            $reportes_compras = 0;
            $cotizaciones = 0;
            $crear_cotizaciones = 0;
            $ver_cotizaciones = 0;
            $asignar_cotizaciones = 0;
            $editar_cotizaciones = 0;
            $eliminar_cotizaciones = 0;
            $reportes_cotizaciones = 0;
            $creditos = 0;
            $eliminar_creditos = 0;
            $reportes_creditos = 0;
            $pagos = 0;
            $eliminar_pagos = 0;
            $anulaciones = 0;
            $ver_anulaciones = 0;
            $crear_anulaciones = 0;
            $eliminar_anulaciones = 0;
            $reportes_anulaciones = 0;
            $cambios = 0;
            $ver_cambios = 0;
            $crear_cambios = 0;
            $eliminar_cambios = 0;
            $reportes_cambios = 0;
            $envios = 0;
            $ver_envios = 0;
            $registrar_entregas = 0;
            $cancelar_envios = 0;
            $reportes_envios = 0;
            $editar_entregas = 0;
            $eliminar_entregas = 0;
            $transportes = 0;
            $crear_transportes = 0;
            $editar_transportes = 0;
            $eliminar_transportes = 0;
            $reportes_transportes = 0;
            $registrar_servicios = 0;
            $reportes_servicios = 0;
        }
        $reportes = $this->input->post('reportes');
        if ($reportes == 1) {
            $graficas_generales = $this->input->post('graficas_generales');
            $graficas_ventas = $this->input->post('graficas_ventas');
            $graficas_compras = $this->input->post('graficas_compras');
            $graficas_anulaciones = $this->input->post('graficas_anulaciones');
            $graficas_cambios = $this->input->post('graficas_cambios');
            $graficas_financiero = $this->input->post('graficas_financiero');
            $actividad_usuarios = $this->input->post('actividad_usuarios');
        }
        else{
            $graficas_generales = 0;
            $graficas_ventas = 0;
            $graficas_compras = 0;
            $graficas_anulaciones = 0;
            $graficas_cambios = 0;
            $graficas_financiero = 0;
            $actividad_usuarios = 0;
        }
        $contabilidad = $this->input->post('contabilidad');
        if ($contabilidad == 1) {
            $ingresos = $this->input->post('ingresos');
            $egresos = $this->input->post('egresos');
            if ($egresos == 1) {
                $ver_egresos = $this->input->post('ver_egresos');
                $crear_egresos = $this->input->post('crear_egresos');
                $editar_egresos = $this->input->post('editar_egresos');
                $reportes_egresos = $this->input->post('reportes_egresos');
            }
            else {
                $ver_egresos = 0;
                $crear_egresos = 0;
                $editar_egresos = 0;
                $reportes_egresos = 0;
            }
            $planillas = $this->input->post('planillas');
            if ($planillas == 1) {
                $pagar_planillas = $this->input->post('pagar_planillas');
                $editar_planillas = $this->input->post('editar_planillas');
                $estado_planillas = $this->input->post('estado_planillas');
                $reportes_planillas = $this->input->post('reportes_planillas');
            }
            else {
                $pagar_planillas = 0;
                $editar_planillas = 0;
                $estado_planillas = 0;
                $reportes_planillas = 0;
            }
            $vacaciones = $this->input->post('vacaciones');
            if ($vacaciones == 1) {
                $crear_vacaciones = $this->input->post('crear_vacaciones');
                $editar_vacaciones = $this->input->post('editar_vacaciones');
                $estado_vacaciones = $this->input->post('estado_vacaciones');
            }
            else {
                $crear_vacaciones = 0;
                $editar_vacaciones = 0;
                $estado_vacaciones = 0;
            }
            $cuentas_bancarias = $this->input->post('cuentas_bancarias');
            if ($cuentas_bancarias == 1) {
                $crear_cuentas = $this->input->post('crear_cuentas');
                $editar_cuentas = $this->input->post('editar_cuentas');
                $transferir_cuentas = $this->input->post('transferir_cuentas');
                $eliminar_cuentas = $this->input->post('eliminar_cuentas');
                $reportes_cuentas = $this->input->post('reportes_cuentas');
            }
            else {
                $crear_cuentas = 0;
                $editar_cuentas = 0;
                $transferir_cuentas = 0;
                $eliminar_cuentas = 0;
                $reportes_cuentas = 0;
            }
            $historial_caja = $this->input->post('historial_caja');
            if ($historial_caja == 1) {
                $registrar_cortes = $this->input->post('registrar_cortes');
                $ver_cortes = $this->input->post('ver_cortes');
            }
            else {
                $registrar_cortes = 0;
                $ver_cortes = 0;
            }
            $historial_fel = $this->input->post('historial_fel');
            if ($historial_fel == 1) {
                $reportes_fel = $this->input->post('reportes_fel');
                $eliminar_fel = $this->input->post('eliminar_fel');
            }
            else {
                $reportes_fel = 0;
                $eliminar_fel = 0;
            }
        }
        else{
            $ingresos = 0;
            $egresos = 0;
            $ver_egresos = 0;
            $crear_egresos = 0;
            $editar_egresos = 0;
            $reportes_egresos = 0;
            $planillas = 0;
            $pagar_planillas = 0;
            $editar_planillas = 0;
            $estado_planillas = 0;
            $reportes_planillas = 0;
            $vacaciones = 0;
            $crear_vacaciones = 0;
            $editar_vacaciones = 0;
            $estado_vacaciones = 0;
            $cuentas_bancarias = 0;
            $crear_cuentas = 0;
            $editar_cuentas = 0;
            $transferir_cuentas = 0;
            $eliminar_cuentas = 0;
            $reportes_cuentas = 0;
            $historial_caja = 0;
            $registrar_cortes = 0;
            $ver_cortes = 0;
            $historial_fel = 0;
            $reportes_fel = 0;
            $eliminar_fel = 0;
        }
        $sucursales = $this->input->post('sucursales');
        if ($sucursales == 1) {
            $crear_sucursales = $this->input->post('crear_sucursales');
            $editar_sucursales = $this->input->post('editar_sucursales');
            $eliminar_sucursales = $this->input->post('eliminar_sucursales');
        }
        else{
            $crear_sucursales = 0;
            $editar_sucursales = 0;
            $eliminar_sucursales = 0;
        }
        $calendario = $this->input->post('calendario');
        if ($calendario == 1) {
            $crear_eventos = $this->input->post('crear_eventos');
            $editar_eventos = $this->input->post('editar_eventos');
        }
        else{
            $crear_eventos = 0;
            $editar_eventos = 0;
        }
        $configuracion = $this->input->post('configuracion');
        if ($configuracion == 1) {
            $editar_configuracion = $this->input->post('editar_configuracion');
        }
        else{
            $editar_configuracion = 0;
        }

        $permisos = array(
            "usuarios" => $usuarios,
            "empleados" => $empleados,
            "crear_empleados" => $crear_empleados,
            "editar_empleados" => $editar_empleados,
            "estado_empleados" => $estado_empleados,
            "reportes_empleados" => $reportes_empleados,
            "roles" => $roles,
            "crear_roles" => $crear_roles,
            "editar_roles" => $editar_roles,
            "estado_roles" => $estado_roles,
            "reportes_roles" => $reportes_roles,
            "admins" => $admins,
            "crear_admins" => $crear_admins,
            "editar_admins" => $editar_admins,
            "estado_admins" => $estado_admins,
            "reportes_admins" => $reportes_admins,
            "clientes" => $clientes,
            "crear_clientes" => $crear_clientes,
            "editar_clientes" => $editar_clientes,
            "estado_clientes" => $estado_clientes,
            "reportes_clientes" => $reportes_clientes,
            "proveedores" => $proveedores,
            "crear_proveedores" => $crear_proveedores,
            "editar_proveedores" => $editar_proveedores,
            "estado_proveedores" => $estado_proveedores,
            "reportes_proveedores" => $reportes_proveedores,
            "codigos" => $codigos,
            "guardar_codigos" => $guardar_codigos,
            "productos" => $productos,
            "inventario" => $inventario,
            "crear_productos" => $crear_productos,
            "editar_productos" => $editar_productos,
            "estado_productos" => $estado_productos,
            "reportes_productos" => $reportes_productos,
            "alertas_productos" => $alertas_productos,
            "reportes_alertas" => $reportes_alertas,
            "traslados" => $traslados,
            "crear_traslados" => $crear_traslados,
            "eliminar_traslados" => $eliminar_traslados,
            "reportes_traslados" => $reportes_traslados,
            "categorias" => $categorias,
            "crear_categorias" => $crear_categorias,
            "editar_categorias" => $editar_categorias,
            "eliminar_categorias" => $eliminar_categorias,
            "reportes_categorias" => $reportes_categorias,
            "marcas" => $marcas,
            "crear_marcas" => $crear_marcas,
            "editar_marcas" => $editar_marcas,
            "eliminar_marcas" => $eliminar_marcas,
            "reportes_marcas" => $reportes_marcas,
            "herramientas" => $herramientas,
            "ventas" => $ventas,
            "ver_ventas" => $ver_ventas,
            "crear_ventas" => $crear_ventas,
            "eliminar_ventas" => $eliminar_ventas,
            "reportes_ventas" => $reportes_ventas,
            "compras" => $compras,
            "ver_compras" => $ver_compras,
            "crear_compras" => $crear_compras,
            "crear_solicitud" => $crear_solicitud,
            "ver_solicitud" => $ver_solicitud,
            "completar_solicitud" => $completar_solicitud,
            "reportes_compras" => $reportes_compras,
            "cotizaciones" => $cotizaciones,
            "crear_cotizaciones" => $crear_cotizaciones,
            "ver_cotizaciones" => $ver_cotizaciones,
            "asignar_cotizaciones" => $asignar_cotizaciones,
            "editar_cotizaciones" => $editar_cotizaciones,
            "eliminar_cotizaciones" => $eliminar_cotizaciones,
            "reportes_cotizaciones" => $reportes_cotizaciones,
            "creditos" => $creditos,
            "eliminar_creditos" => $eliminar_creditos,
            "reportes_creditos" => $reportes_creditos,
            "pagos" => $pagos,
            "eliminar_pagos" => $eliminar_pagos,
            "anulaciones" => $anulaciones,
            "ver_anulaciones" => $ver_anulaciones,
            "crear_anulaciones" => $crear_anulaciones,
            "eliminar_anulaciones" => $eliminar_anulaciones,
            "reportes_anulaciones" => $reportes_anulaciones,
            "cambios" => $cambios,
            "ver_cambios" => $ver_cambios,
            "crear_cambios" => $crear_cambios,
            "eliminar_cambios" => $eliminar_cambios,
            "reportes_cambios" => $reportes_cambios,
            "envios" => $envios,
            "ver_envios" => $ver_envios,
            "registrar_entregas" => $registrar_entregas,
            "cancelar_envios" => $cancelar_envios,
            "reportes_envios" => $reportes_envios,
            "editar_entregas" => $editar_entregas,
            "eliminar_entregas" => $eliminar_entregas,
            "transportes" => $transportes,
            "crear_transportes" => $crear_transportes,
            "editar_transportes" => $editar_transportes,
            "eliminar_transportes" => $eliminar_transportes,
            "reportes_transportes" => $reportes_transportes,
            "registrar_servicios" => $registrar_servicios,
            "reportes_servicios" => $reportes_servicios,
            "reportes" => $reportes,
            "graficas_generales" => $graficas_generales,
            "graficas_ventas" => $graficas_ventas,
            "graficas_compras" => $graficas_compras,
            "graficas_anulaciones" => $graficas_anulaciones,
            "graficas_cambios" => $graficas_cambios,
            "graficas_financiero" => $graficas_financiero,
            "actividad_usuarios" => $actividad_usuarios,
            "contabilidad" => $contabilidad,
            "ingresos" => $ingresos,
            "egresos" => $egresos,
            "ver_egresos" => $ver_egresos,
            "crear_egresos" => $crear_egresos,
            "editar_egresos" => $editar_egresos,
            "reportes_egresos" => $reportes_egresos,
            "planillas" => $planillas,
            "pagar_planillas" => $pagar_planillas,
            "editar_planillas" => $editar_planillas,
            "estado_planillas" => $estado_planillas,
            "reportes_planillas" => $reportes_planillas,
            "vacaciones" => $vacaciones,
            "crear_vacaciones" => $crear_vacaciones,
            "editar_vacaciones" => $editar_vacaciones,
            "estado_vacaciones" => $estado_vacaciones,
            "cuentas_bancarias" => $cuentas_bancarias,
            "crear_cuentas" => $crear_cuentas,
            "editar_cuentas" => $editar_cuentas,
            "transferir_cuentas" => $transferir_cuentas,
            "eliminar_cuentas" => $eliminar_cuentas,
            "reportes_cuentas" => $reportes_cuentas,
            "historial_caja" => $historial_caja,
            "registrar_cortes" => $registrar_cortes,
            "ver_cortes" => $ver_cortes,
            "historial_fel" => $historial_fel,
            "reportes_fel" => $reportes_fel,
            "eliminar_fel" => $eliminar_fel,
            "sucursales" => $sucursales,
            "crear_sucursales" => $crear_sucursales,
            "editar_sucursales" => $editar_sucursales,
            "eliminar_sucursales" => $eliminar_sucursales,
            "calendario" => $calendario,
            "crear_eventos" => $crear_eventos,
            "editar_eventos" => $editar_eventos,
            "configuracion" => $configuracion,
            "editar_configuracion" => $editar_configuracion
        );

        $data['permissions'] = serialize($permisos);

        $data['status']      = 1;
        $this->db->insert('job', $data);
        
        $message = 'Ha creado un nuevo rol con nombre: '.$this->input->post('name');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/roles/'), 'roles', 'Roles');
    }

    function update_rol($ID)
    {
        $data['name']        = $this->input->post('name');
        $data['description'] = trim($this->input->post('description'));

        $usuarios = $this->input->post('usuarios');
        if ($usuarios == 1) {
            $empleados = $this->input->post('empleados');
            if ($empleados == 1) {
                $crear_empleados = $this->input->post('crear_empleados');
                $editar_empleados = $this->input->post('editar_empleados');
                $estado_empleados = $this->input->post('estado_empleados');
                $reportes_empleados = $this->input->post('reportes_empleados');
            }
            else {
                $crear_empleados = 0;
                $editar_empleados = 0;
                $estado_empleados = 0;
                $reportes_empleados = 0;
            }
            $roles = $this->input->post('roles');
            if ($roles == 1) {
                $crear_roles = $this->input->post('crear_roles');
                $editar_roles = $this->input->post('editar_roles');
                $estado_roles = $this->input->post('estado_roles');
                $reportes_roles = $this->input->post('reportes_roles');
            }
            else {
                $crear_roles = 0;
                $editar_roles = 0;
                $estado_roles = 0;
                $reportes_roles = 0;
            }
            $admins = $this->input->post('admins');
            if ($admins == 1) {
                $crear_admins = $this->input->post('crear_admins');
                $editar_admins = $this->input->post('editar_admins');
                $estado_admins = $this->input->post('estado_admins');
                $reportes_admins = $this->input->post('reportes_admins');
            }
            else {
                $crear_admins = 0;
                $editar_admins = 0;
                $estado_admins = 0;
                $reportes_admins = 0;
            }
            $clientes = $this->input->post('clientes');
            if ($clientes == 1) {
                $crear_clientes = $this->input->post('crear_clientes');
                $editar_clientes = $this->input->post('editar_clientes');
                $estado_clientes = $this->input->post('estado_clientes');
                $reportes_clientes = $this->input->post('reportes_clientes');
            }
            else {
                $crear_clientes = 0;
                $editar_clientes = 0;
                $estado_clientes = 0;
                $reportes_clientes = 0;
            }
            $proveedores = $this->input->post('proveedores');
            if ($proveedores == 1) {
                $crear_proveedores = $this->input->post('crear_proveedores');
                $editar_proveedores = $this->input->post('editar_proveedores');
                $estado_proveedores = $this->input->post('estado_proveedores');
                $reportes_proveedores = $this->input->post('reportes_proveedores');
            }
            else {
                $crear_proveedores = 0;
                $editar_proveedores = 0;
                $estado_proveedores = 0;
                $reportes_proveedores = 0;
            }
            $codigos = $this->input->post('codigos');
            if ($codigos == 1) {
                $guardar_codigos = $this->input->post('guardar_codigos');
            }
            else {
                $guardar_codigos = 0;
            }
        }
        else{
            $empleados = 0;
            $crear_empleados = 0;
            $editar_empleados = 0;
            $estado_empleados = 0;
            $reportes_empleados = 0;
            $roles = 0;
            $crear_roles = 0;
            $editar_roles = 0;
            $estado_roles = 0;
            $reportes_roles = 0;
            $admins = 0;
            $crear_admins = 0;
            $editar_admins = 0;
            $estado_admins = 0;
            $reportes_admins = 0;
            $clientes = 0;
            $crear_clientes = 0;
            $editar_clientes = 0;
            $estado_clientes = 0;
            $reportes_clientes = 0;
            $proveedores = 0;
            $crear_proveedores = 0;
            $editar_proveedores = 0;
            $estado_proveedores = 0;
            $reportes_proveedores = 0;
            $codigos = 0;
            $guardar_codigos = 0;
        }
        $productos = $this->input->post('productos');
        if ($productos == 1) {
            $inventario = $this->input->post('inventario');
            if ($inventario == 1) {
                $crear_productos = $this->input->post('crear_productos');
                $editar_productos = $this->input->post('editar_productos');
                $estado_productos = $this->input->post('estado_productos');
                $reportes_productos = $this->input->post('reportes_productos');
                $alertas_productos = $this->input->post('alertas_productos');
                $reportes_alertas = $this->input->post('reportes_alertas');
            }
            else {
                $crear_productos = 0;
                $editar_productos = 0;
                $estado_productos = 0;
                $reportes_productos = 0;
                $alertas_productos = 0;
                $reportes_alertas = 0;
            }
            $traslados = $this->input->post('traslados');
            if ($traslados == 1) {
                $crear_traslados = $this->input->post('crear_traslados');
                $eliminar_traslados = $this->input->post('eliminar_traslados');
                $reportes_traslados = $this->input->post('reportes_traslados');
            }
            else {
                $crear_traslados = 0;
                $eliminar_traslados = 0;
                $reportes_traslados = 0;
            }
            $categorias = $this->input->post('categorias');
            if ($categorias == 1) {
                $crear_categorias = $this->input->post('crear_categorias');
                $editar_categorias = $this->input->post('editar_categorias');
                $eliminar_categorias = $this->input->post('eliminar_categorias');
                $reportes_categorias = $this->input->post('reportes_categorias');
            }
            else {
                $crear_categorias = 0;
                $editar_categorias = 0;
                $eliminar_categorias = 0;
                $reportes_categorias = 0;
            }
            $marcas = $this->input->post('marcas');
            if ($marcas == 1) {
                $crear_marcas = $this->input->post('crear_marcas');
                $editar_marcas = $this->input->post('editar_marcas');
                $eliminar_marcas = $this->input->post('eliminar_marcas');
                $reportes_marcas = $this->input->post('reportes_marcas');
            }
            else {
                $crear_marcas = 0;
                $editar_marcas = 0;
                $eliminar_marcas = 0;
                $reportes_marcas = 0;
            }
        }
        else {
            $inventario = 0;
            $crear_productos = 0;
            $editar_productos = 0;
            $estado_productos = 0;
            $reportes_productos = 0;
            $alertas_productos = 0;
            $reportes_alertas = 0;
            $traslados = 0;
            $crear_traslados = 0;
            $eliminar_traslados = 0;
            $reportes_traslados = 0;
            $categorias = 0;
            $crear_categorias = 0;
            $editar_categorias = 0;
            $eliminar_categorias = 0;
            $reportes_categorias = 0;
            $marcas = 0;
            $crear_marcas = 0;
            $editar_marcas = 0;
            $eliminar_marcas = 0;
            $reportes_marcas = 0;
        }
        $herramientas = $this->input->post('herramientas');
        if ($herramientas == 1) {
            $ventas = $this->input->post('ventas');
            if ($ventas == 1) {
                $ver_ventas = $this->input->post('ver_ventas');
                $crear_ventas = $this->input->post('crear_ventas');
                $eliminar_ventas = $this->input->post('eliminar_ventas');
                $reportes_ventas = $this->input->post('reportes_ventas');
            }
            else {
                $ver_ventas = 0;
                $crear_ventas = 0;
                $eliminar_ventas = 0;
                $reportes_ventas = 0;
            }
            $compras = $this->input->post('compras');
            if ($compras == 1) {
                $ver_compras = $this->input->post('ver_compras');
                $crear_compras = $this->input->post('crear_compras');
                $crear_solicitud = $this->input->post('crear_solicitud');
                $ver_solicitud = $this->input->post('ver_solicitud');
                $completar_solicitud = $this->input->post('completar_solicitud');
                $reportes_compras = $this->input->post('reportes_compras');
            }
            else {
                $ver_compras = 0;
                $crear_compras = 0;
                $crear_solicitud = 0;
                $ver_solicitud = 0;
                $completar_solicitud = 0;
                $reportes_compras = 0;
            }
            $cotizaciones = $this->input->post('cotizaciones');
            if ($cotizaciones == 1) {
                $crear_cotizaciones = $this->input->post('crear_cotizaciones');
                $ver_cotizaciones = $this->input->post('ver_cotizaciones');
                $asignar_cotizaciones = $this->input->post('asignar_cotizaciones');
                $editar_cotizaciones = $this->input->post('editar_cotizaciones');
                $eliminar_cotizaciones = $this->input->post('eliminar_cotizaciones');
                $reportes_cotizaciones = $this->input->post('reportes_cotizaciones');
            }
            else {
                $crear_cotizaciones = 0;
                $ver_cotizaciones = 0;
                $asignar_cotizaciones = 0;
                $editar_cotizaciones = 0;
                $eliminar_cotizaciones = 0;
                $reportes_cotizaciones = 0;
            }
            $creditos = $this->input->post('creditos');
            if ($creditos == 1) {
                $eliminar_creditos = $this->input->post('eliminar_creditos');
                $reportes_creditos = $this->input->post('reportes_creditos');
                $pagos             = $this->input->post('pagos');
                $eliminar_pagos    = $this->input->post('eliminar_pagos');
            }
            else {
                $eliminar_creditos = 0;
                $reportes_creditos = 0;
                $pagos             = 0;
                $eliminar_pagos    = 0;
            }
            $anulaciones = $this->input->post('anulaciones');
            if ($anulaciones == 1) {
                $ver_anulaciones = $this->input->post('ver_anulaciones');
                $crear_anulaciones = $this->input->post('crear_anulaciones');
                $eliminar_anulaciones = $this->input->post('eliminar_anulaciones');
                $reportes_anulaciones = $this->input->post('reportes_anulaciones');
            }
            else {
                $ver_anulaciones = 0;
                $crear_anulaciones = 0;
                $eliminar_anulaciones = 0;
                $reportes_anulaciones = 0;
            }
            $cambios = $this->input->post('cambios');
            if ($cambios == 1) {
                $ver_cambios = $this->input->post('ver_cambios');
                $crear_cambios = $this->input->post('crear_cambios');
                $eliminar_cambios = $this->input->post('eliminar_cambios');
                $reportes_cambios = $this->input->post('reportes_cambios');
            }
            else {
                $ver_cambios = 0;
                $crear_cambios = 0;
                $eliminar_cambios = 0;
                $reportes_cambios = 0;
            }
            $envios = $this->input->post('envios');
            if ($envios == 1) {
                $ver_envios = $this->input->post('ver_envios');
                $registrar_entregas = $this->input->post('registrar_entregas');
                $cancelar_envios = $this->input->post('cancelar_envios');
                $reportes_envios = $this->input->post('reportes_envios');
                $editar_entregas = $this->input->post('editar_entregas');
                $eliminar_entregas = $this->input->post('eliminar_entregas');
            }
            else {
                $ver_envios = 0;
                $registrar_entregas = 0;
                $cancelar_envios = 0;
                $reportes_envios = 0;
                $editar_entregas = 0;
                $eliminar_entregas = 0;
            }
            $transportes = $this->input->post('transportes');
            if ($transportes == 1) {
                $crear_transportes = $this->input->post('crear_transportes');
                $editar_transportes = $this->input->post('editar_transportes');
                $eliminar_transportes = $this->input->post('eliminar_transportes');
                $reportes_transportes = $this->input->post('reportes_transportes');
                $registrar_servicios = $this->input->post('registrar_servicios');
                $reportes_servicios = $this->input->post('reportes_servicios');
            }
            else {
                $crear_transportes = 0;
                $editar_transportes = 0;
                $eliminar_transportes = 0;
                $reportes_transportes = 0;
                $registrar_servicios = 0;
                $reportes_servicios = 0;
            }
        }
        else{
            $ventas = 0;
            $ver_ventas = 0;
            $crear_ventas = 0;
            $eliminar_ventas = 0;
            $reportes_ventas = 0;
            $compras = 0;
            $ver_compras = 0;
            $crear_compras = 0;
            $crear_solicitud = 0;
            $ver_solicitud = 0;
            $completar_solicitud = 0;
            $reportes_compras = 0;
            $cotizaciones = 0;
            $crear_cotizaciones = 0;
            $ver_cotizaciones = 0;
            $asignar_cotizaciones = 0;
            $editar_cotizaciones = 0;
            $eliminar_cotizaciones = 0;
            $reportes_cotizaciones = 0;
            $creditos = 0;
            $eliminar_creditos = 0;
            $reportes_creditos = 0;
            $pagos = 0;
            $eliminar_pagos = 0;
            $anulaciones = 0;
            $ver_anulaciones = 0;
            $crear_anulaciones = 0;
            $eliminar_anulaciones = 0;
            $reportes_anulaciones = 0;
            $cambios = 0;
            $ver_cambios = 0;
            $crear_cambios = 0;
            $eliminar_cambios = 0;
            $reportes_cambios = 0;
            $envios = 0;
            $ver_envios = 0;
            $registrar_entregas = 0;
            $cancelar_envios = 0;
            $reportes_envios = 0;
            $editar_entregas = 0;
            $eliminar_entregas = 0;
            $transportes = 0;
            $crear_transportes = 0;
            $editar_transportes = 0;
            $eliminar_transportes = 0;
            $reportes_transportes = 0;
            $registrar_servicios = 0;
            $reportes_servicios = 0;
        }
        $reportes = $this->input->post('reportes');
        if ($reportes == 1) {
            $graficas_generales = $this->input->post('graficas_generales');
            $graficas_ventas = $this->input->post('graficas_ventas');
            $graficas_compras = $this->input->post('graficas_compras');
            $graficas_anulaciones = $this->input->post('graficas_anulaciones');
            $graficas_cambios = $this->input->post('graficas_cambios');
            $graficas_financiero = $this->input->post('graficas_financiero');
            $actividad_usuarios = $this->input->post('actividad_usuarios');
        }
        else{
            $graficas_generales = 0;
            $graficas_ventas = 0;
            $graficas_compras = 0;
            $graficas_anulaciones = 0;
            $graficas_cambios = 0;
            $graficas_financiero = 0;
            $actividad_usuarios = 0;
        }
        $contabilidad = $this->input->post('contabilidad');
        if ($contabilidad == 1) {
            $ingresos = $this->input->post('ingresos');
            $egresos = $this->input->post('egresos');
            if ($egresos == 1) {
                $ver_egresos = $this->input->post('ver_egresos');
                $crear_egresos = $this->input->post('crear_egresos');
                $editar_egresos = $this->input->post('editar_egresos');
                $reportes_egresos = $this->input->post('reportes_egresos');
            }
            else {
                $ver_egresos = 0;
                $crear_egresos = 0;
                $editar_egresos = 0;
                $reportes_egresos = 0;
            }
            $planillas = $this->input->post('planillas');
            if ($planillas == 1) {
                $pagar_planillas = $this->input->post('pagar_planillas');
                $editar_planillas = $this->input->post('editar_planillas');
                $estado_planillas = $this->input->post('estado_planillas');
                $reportes_planillas = $this->input->post('reportes_planillas');
            }
            else {
                $pagar_planillas = 0;
                $editar_planillas = 0;
                $estado_planillas = 0;
                $reportes_planillas = 0;
            }
            $vacaciones = $this->input->post('vacaciones');
            if ($vacaciones == 1) {
                $crear_vacaciones = $this->input->post('crear_vacaciones');
                $editar_vacaciones = $this->input->post('editar_vacaciones');
                $estado_vacaciones = $this->input->post('estado_vacaciones');
            }
            else {
                $crear_vacaciones = 0;
                $editar_vacaciones = 0;
                $estado_vacaciones = 0;
            }
            $cuentas_bancarias = $this->input->post('cuentas_bancarias');
            if ($cuentas_bancarias == 1) {
                $crear_cuentas = $this->input->post('crear_cuentas');
                $editar_cuentas = $this->input->post('editar_cuentas');
                $transferir_cuentas = $this->input->post('transferir_cuentas');
                $eliminar_cuentas = $this->input->post('eliminar_cuentas');
                $reportes_cuentas = $this->input->post('reportes_cuentas');
            }
            else {
                $crear_cuentas = 0;
                $editar_cuentas = 0;
                $transferir_cuentas = 0;
                $eliminar_cuentas = 0;
                $reportes_cuentas = 0;
            }
            $historial_caja = $this->input->post('historial_caja');
            if ($historial_caja == 1) {
                $registrar_cortes = $this->input->post('registrar_cortes');
                $ver_cortes = $this->input->post('ver_cortes');
            }
            else {
                $registrar_cortes = 0;
                $ver_cortes = 0;
            }
            $historial_fel = $this->input->post('historial_fel');
            if ($historial_fel == 1) {
                $reportes_fel = $this->input->post('reportes_fel');
                $eliminar_fel = $this->input->post('eliminar_fel');
            }
            else {
                $reportes_fel = 0;
                $eliminar_fel = 0;
            }
        }
        else{
            $ingresos = 0;
            $egresos = 0;
            $ver_egresos = 0;
            $crear_egresos = 0;
            $editar_egresos = 0;
            $reportes_egresos = 0;
            $planillas = 0;
            $pagar_planillas = 0;
            $editar_planillas = 0;
            $estado_planillas = 0;
            $reportes_planillas = 0;
            $vacaciones = 0;
            $crear_vacaciones = 0;
            $editar_vacaciones = 0;
            $estado_vacaciones = 0;
            $cuentas_bancarias = 0;
            $crear_cuentas = 0;
            $editar_cuentas = 0;
            $transferir_cuentas = 0;
            $eliminar_cuentas = 0;
            $reportes_cuentas = 0;
            $historial_caja = 0;
            $registrar_cortes = 0;
            $ver_cortes = 0;
            $historial_fel = 0;
            $reportes_fel = 0;
            $eliminar_fel = 0;
        }
        $sucursales = $this->input->post('sucursales');
        if ($sucursales == 1) {
            $crear_sucursales = $this->input->post('crear_sucursales');
            $editar_sucursales = $this->input->post('editar_sucursales');
            $eliminar_sucursales = $this->input->post('eliminar_sucursales');
        }
        else{
            $crear_sucursales = 0;
            $editar_sucursales = 0;
            $eliminar_sucursales = 0;
        }
        $calendario = $this->input->post('calendario');
        if ($calendario == 1) {
            $crear_eventos = $this->input->post('crear_eventos');
            $editar_eventos = $this->input->post('editar_eventos');
        }
        else{
            $crear_eventos = 0;
            $editar_eventos = 0;
        }
        $configuracion = $this->input->post('configuracion');
        if ($configuracion == 1) {
            $editar_configuracion = $this->input->post('editar_configuracion');
        }
        else{
            $editar_configuracion = 0;
        }

        $permisos = array(
            "usuarios" => $usuarios,
            "empleados" => $empleados,
            "crear_empleados" => $crear_empleados,
            "editar_empleados" => $editar_empleados,
            "estado_empleados" => $estado_empleados,
            "reportes_empleados" => $reportes_empleados,
            "roles" => $roles,
            "crear_roles" => $crear_roles,
            "editar_roles" => $editar_roles,
            "estado_roles" => $estado_roles,
            "reportes_roles" => $reportes_roles,
            "admins" => $admins,
            "crear_admins" => $crear_admins,
            "editar_admins" => $editar_admins,
            "estado_admins" => $estado_admins,
            "reportes_admins" => $reportes_admins,
            "clientes" => $clientes,
            "crear_clientes" => $crear_clientes,
            "editar_clientes" => $editar_clientes,
            "estado_clientes" => $estado_clientes,
            "reportes_clientes" => $reportes_clientes,
            "proveedores" => $proveedores,
            "crear_proveedores" => $crear_proveedores,
            "editar_proveedores" => $editar_proveedores,
            "estado_proveedores" => $estado_proveedores,
            "reportes_proveedores" => $reportes_proveedores,
            "codigos" => $codigos,
            "guardar_codigos" => $guardar_codigos,
            "productos" => $productos,
            "inventario" => $inventario,
            "crear_productos" => $crear_productos,
            "editar_productos" => $editar_productos,
            "estado_productos" => $estado_productos,
            "reportes_productos" => $reportes_productos,
            "alertas_productos" => $alertas_productos,
            "reportes_alertas" => $reportes_alertas,
            "traslados" => $traslados,
            "crear_traslados" => $crear_traslados,
            "eliminar_traslados" => $eliminar_traslados,
            "reportes_traslados" => $reportes_traslados,
            "categorias" => $categorias,
            "crear_categorias" => $crear_categorias,
            "editar_categorias" => $editar_categorias,
            "eliminar_categorias" => $eliminar_categorias,
            "reportes_categorias" => $reportes_categorias,
            "marcas" => $marcas,
            "crear_marcas" => $crear_marcas,
            "editar_marcas" => $editar_marcas,
            "eliminar_marcas" => $eliminar_marcas,
            "reportes_marcas" => $reportes_marcas,
            "herramientas" => $herramientas,
            "ventas" => $ventas,
            "ver_ventas" => $ver_ventas,
            "crear_ventas" => $crear_ventas,
            "eliminar_ventas" => $eliminar_ventas,
            "reportes_ventas" => $reportes_ventas,
            "compras" => $compras,
            "ver_compras" => $ver_compras,
            "crear_compras" => $crear_compras,
            "crear_solicitud" => $crear_solicitud,
            "ver_solicitud" => $ver_solicitud,
            "completar_solicitud" => $completar_solicitud,
            "reportes_compras" => $reportes_compras,
            "cotizaciones" => $cotizaciones,
            "crear_cotizaciones" => $crear_cotizaciones,
            "ver_cotizaciones" => $ver_cotizaciones,
            "asignar_cotizaciones" => $asignar_cotizaciones,
            "editar_cotizaciones" => $editar_cotizaciones,
            "eliminar_cotizaciones" => $eliminar_cotizaciones,
            "reportes_cotizaciones" => $reportes_cotizaciones,
            "creditos" => $creditos,
            "eliminar_creditos" => $eliminar_creditos,
            "reportes_creditos" => $reportes_creditos,
            "pagos" => $pagos,
            "eliminar_pagos" => $eliminar_pagos,
            "anulaciones" => $anulaciones,
            "ver_anulaciones" => $ver_anulaciones,
            "crear_anulaciones" => $crear_anulaciones,
            "eliminar_anulaciones" => $eliminar_anulaciones,
            "reportes_anulaciones" => $reportes_anulaciones,
            "cambios" => $cambios,
            "ver_cambios" => $ver_cambios,
            "crear_cambios" => $crear_cambios,
            "eliminar_cambios" => $eliminar_cambios,
            "reportes_cambios" => $reportes_cambios,
            "envios" => $envios,
            "ver_envios" => $ver_envios,
            "registrar_entregas" => $registrar_entregas,
            "cancelar_envios" => $cancelar_envios,
            "reportes_envios" => $reportes_envios,
            "editar_entregas" => $editar_entregas,
            "eliminar_entregas" => $eliminar_entregas,
            "transportes" => $transportes,
            "crear_transportes" => $crear_transportes,
            "editar_transportes" => $editar_transportes,
            "eliminar_transportes" => $eliminar_transportes,
            "reportes_transportes" => $reportes_transportes,
            "registrar_servicios" => $registrar_servicios,
            "reportes_servicios" => $reportes_servicios,
            "reportes" => $reportes,
            "graficas_generales" => $graficas_generales,
            "graficas_ventas" => $graficas_ventas,
            "graficas_compras" => $graficas_compras,
            "graficas_anulaciones" => $graficas_anulaciones,
            "graficas_cambios" => $graficas_cambios,
            "graficas_financiero" => $graficas_financiero,
            "actividad_usuarios" => $actividad_usuarios,
            "contabilidad" => $contabilidad,
            "ingresos" => $ingresos,
            "egresos" => $egresos,
            "ver_egresos" => $ver_egresos,
            "crear_egresos" => $crear_egresos,
            "editar_egresos" => $editar_egresos,
            "reportes_egresos" => $reportes_egresos,
            "planillas" => $planillas,
            "pagar_planillas" => $pagar_planillas,
            "editar_planillas" => $editar_planillas,
            "estado_planillas" => $estado_planillas,
            "reportes_planillas" => $reportes_planillas,
            "vacaciones" => $vacaciones,
            "crear_vacaciones" => $crear_vacaciones,
            "editar_vacaciones" => $editar_vacaciones,
            "estado_vacaciones" => $estado_vacaciones,
            "cuentas_bancarias" => $cuentas_bancarias,
            "crear_cuentas" => $crear_cuentas,
            "editar_cuentas" => $editar_cuentas,
            "transferir_cuentas" => $transferir_cuentas,
            "eliminar_cuentas" => $eliminar_cuentas,
            "reportes_cuentas" => $reportes_cuentas,
            "historial_caja" => $historial_caja,
            "registrar_cortes" => $registrar_cortes,
            "ver_cortes" => $ver_cortes,
            "historial_fel" => $historial_fel,
            "reportes_fel" => $reportes_fel,
            "eliminar_fel" => $eliminar_fel,
            "sucursales" => $sucursales,
            "crear_sucursales" => $crear_sucursales,
            "editar_sucursales" => $editar_sucursales,
            "eliminar_sucursales" => $eliminar_sucursales,
            "calendario" => $calendario,
            "crear_eventos" => $crear_eventos,
            "editar_eventos" => $editar_eventos,
            "configuracion" => $configuracion,
            "editar_configuracion" => $editar_configuracion
        );

        $data['permissions'] = serialize($permisos);
        $this->db->where('job_id', $ID);
        $this->db->update('job', $data);
        
        $message = 'Ha actualizado un rol con nombre: '.$this->input->post('name');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/roles/'), 'roles', 'Roles_update');
    }

    function delete_rol($ID)
    {
        $data['status'] = 0;
        $this->db->where('job_id', $ID);
        $this->db->update('job', $data);
        
        $message = 'Ha desactivado un rol con ID: '.$ID;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/roles/'), 'roles', 'Roles_delete');
    }

    function active_rol($ID)
    {
        $data['status'] = 1;
        $this->db->where('job_id', $ID);
        $this->db->update('job', $data);
        
        $message = 'Ha activado un rol con ID: '.$ID;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/roles/'), 'roles', 'Roles_active');
    }

    function get_credits()
    {   
        $branch_id = $this->session->userdata('branch_id');
        $this->db->where('branch_id', $branch_id);
        $this->db->where('status',1);
        $this->db->where('credito',1);
        $this->db->order_by('sales_id','desc');
        return $this->db->get('sales');
    }

    function get_credits_client($client_id)
    {   
        $branch_id = $this->session->userdata('branch_id');
        $this->db->order_by('sales_id','desc');
        return $this->db->get_where('sales', array('branch_id'=>$branch_id, 'credito'=>1 , 'status'=>1, 'client_id'=>$client_id));
    }

    function get_sale()
    {   
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->get_where('sales', array('branch_id'=>$branch_id, 'credito'=>0 , 'status'=>1));
    }

    function get_sale_date($initial, $final) {
        $branch_id = $this->session->userdata('branch_id');
        $this->db->where("DATE(date) >= DATE('$initial')", NULL, FALSE);
        $this->db->where("DATE(date) <= DATE('$final')", NULL, FALSE);
        $this->db->where('branch_id',$branch_id);
        $this->db->where('status',1);
        //$this->db->where('credito', 0);
        return $this->db->get('sales');
    }

    function getVentasTraslados($initial, $final, $type = '') {
        $branch_id = $this->session->userdata('branch_id');
        log_message("error", "Type: $type");
        log_message("error", "branch--: $branch_id");
        log_message("error", "inicial--: $initial");
        log_message("error", "final--: $final");
        
        $query = "SELECT d.*, SUM(d.amount) AS total FROM product_details AS d INNER JOIN lotes AS l ON d.code = l.code WHERE DATE(date) >= DATE('$initial') AND DATE(date) <= DATE('$final') AND d.type = 0 AND d.status = 1 AND d.estado = 1 AND d.branch_id = '$branch_id' AND l.chk_factura = 1 GROUP BY d.code, d.products_id, d.products_id_2, d.activity_ref ORDER BY d.date DESC";
        if ($type != '') $query = "SELECT d.*, SUM(d.amount) AS total FROM product_details AS d INNER JOIN lotes AS l ON d.code = l.code WHERE d.description LIKE '%$type%' AND DATE(d.date) >= DATE('$initial') AND DATE(d.date) <= DATE('$final') AND d.type = 0 AND d.status = 1 AND d.estado = 1 AND d.branch_id = '$branch_id' AND l.chk_factura = 1 GROUP BY d.code, d.products_id, d.products_id_2, d.activity_ref ORDER BY d.date DESC";
        
        //$response= $this->db->query($query);
        
        return $response= $this->db->query($query);
    }

    function get_anulations()
    {   
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->get_where('sales', array('branch_id'=>$branch_id, 'credito'=>0 , 'status'=>1,'estado'=>3));
    }

    function get_change()
    {   
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->order_by('sales_id','DESC')->get_where('sales', array('branch_id'=>$branch_id, 'credito'=>0 , 'status'=>1,'estado'=>4));
    }
    
    function get_shippings()
    {   
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->order_by('sales_id','ASC')->get_where('sales', array('branch_id'=>$branch_id, 'shipping'=>1 , 'status'=>1, 'estado'=>2));
    }

    function get_deliveries()
    {
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->order_by('delivery_id','DESC')->get_where('delivery', array('branch_id'=>$branch_id));
    }

    function create_sale($code, $user_sale = ''){
        $branch_id = $this->session->userdata('branch_id');
        if($this->input->post('ctz')==1){
            $dataCTZ['status'] = 0;
            $this->db->where('code', $code)->update('quotes',$dataCTZ);
        }
        
        if($this->input->post('cui_v')==1) {
            $cui = 1;
        } else {
            $cui = 0;
        }

        $responsable = $this->input->post('responsable_cot');
        $name_res = $this->input->post('name_res');
        
        if ($name_res == '') $name_res = $this->getName('admin',$this->session->userdata('login_user_id'));
        if ($responsable == '') $responsable = $this->session->userdata('login_user_id');
        
        $data['responsable'] = $responsable;
        $data['name_res']    = $name_res;
        $data['branch_id']   = $this->session->userdata('branch_id');
        $data['code']        = $code;
        $data['sala']        = 0;
        $data['cui']        = $cui;
        $data['day']         = date('d');
        $data['week']        = date('W');
        $data['month']       = date('m');
        $data['year']        = date('Y');
        $data['date']        = date('Y-m-d');
        $data['time']        = date('H:i:s');
        
        if ($user_sale != '') {
            $data['user_sale'] = $user_sale;
        }
        
        if($this->input->post('new_client') == '1' ) {
            
            $name  = $this->input->post('name');
            $nm = explode(',',$name);
            $datacl['name']      = $nm[0];
            $datacl['last_name'] = $nm[1];
            $datacl['nit']       = str_replace('-','', $this->input->post('nit'));
            $datacl['phone']     = $this->input->post('phone');
            $datacl['email']     = $this->input->post('email');
            $datacl['address']   = trim($this->input->post('address'));
            $datacl['type']      = $this->input->post('type');
            $datacl['branch_id'] = $this->session->userdata('branch_id');
            $datacl['status']    = 1;
            $datacl['responsable'] = $this->session->userdata('login_user_id');
            $datacl['date'] = date('Y-m-d H:i:s');
            $this->db->insert('client',$datacl);
            $client_id = $this->db->insert_id();

        }else {
            $client_id = $this->input->post('client_id');
        }
        
        $products = $this->input->post('product');
        $amount   = $this->input->post('amount');
        $price    = $this->input->post('price');
        $cost     = $this->input->post('cost');
        $price_my = $this->input->post('price_my');
        $price_farma = $this->input->post('price_farma');
        $discount = $this->input->post('discount');
        $sub      = $this->input->post('sub');
        $sub_my   = $this->input->post('sub_my');
        $sub_farma   = $this->input->post('sub_farma');
        
        $num_products = sizeof($products);
        $cliente_type = 2;
        if ($client_id > 0) $cliente_type = $this->db->get_where('client',array('client_id'=>$client_id))->row()->type;
        $data['my']   = $cliente_type;
        $producto = array();
        
        for ($i=0; $i < $num_products ; $i++) { 
            $producto_indi =  $this->db->get_where('products',array('products_id '=>$products[$i]))->row();
            $iva = 0;
            if (!$producto_indi->iva) $iva = 0;
            else $iva = 1;
            $new_producto = array('product'=>$products[$i], 'amount'=>$amount[$i], 'price'=>$price[$i], 'cost'=>$cost[$i], 'price_my'=>$price_my[$i], 'price_farma'=>$price_farma[$i], 'discount'=>$discount[$i], 'sub'=>$sub[$i], 'sub_my'=>$sub_my[$i], 'sub_farma'=>$sub_farma[$i], 'iva'=>$iva);
            array_push($producto, $new_producto);
            
            $products_id_2 = 0; $cost2 = $cost[$i];
            if($producto_indi->presentation == 'Caja') {
                $products_id_2 = $products[$i];
                $id_produ = $producto_indi->id_prod_matriz;
                $amount2  = $amount[$i]*$producto_indi->cnt_prod_matriz;
                if($producto_indi->cnt_prod_matriz > 0) $cost2 = $cost[$i] / $producto_indi->cnt_prod_matriz;
            } else {
                $id_produ = $products[$i];
                $amount2  = $amount[$i];
            }
            
            $lotes = $this->db->order_by('lote_id','ASC')->get_where('lotes',array('id_producto'=>$id_produ,'branch_id'=>$branch_id,'existencia >'=>0))->result_array();
            
      
                
                if($cliente_type==1) {
                    $dat2['price']       = $sub_my[$i];
                }
                if($cliente_type==2) {
                    $dat2['price']       = $sub[$i];
                }
                if($cliente_type==3) {
                    $dat2['price']       = $sub_farma[$i];
                }
                
                $dat2['date']          = date('Y-m-d');
                $dat2['code']          = $lote['code'];
                $dat2['products_id']   = $id_produ;
                $dat2['user_id']       = $this->session->userdata('login_user_id');
                $dat2['type']          = 0;
                $dat2['branch_id']     = $this->session->userdata('branch_id');
                $dat2['amount']        = $amount2;
                $dat2['provider']      = $producto_indi->provider; 
                $dat2['cost']          = $cost2;
                $dat2['description']   = "Venta";
                $dat2['activity_ref']  = $code;
                $dat2['products_id_2'] = $products_id_2;
                $dat2['iva']           = $iva;
                $dat2['status']           = 1;            
                $this->db->insert('product_details', $dat2);  
                                                           
            $this->alerta_stock($id_produ);

            $name = $producto_indi->name;
            $message = 'Vendió '.$name.', ID: '.$products[$i].', Venta: '.$code;
            $this->insert_binnacle($message);
        }
        
        $data['num_products'] = $num_products;
        $data['products']     = json_encode($producto);

        $metodo = $this->input->post('metodo');
        $total = $this->input->post('ttl');
        $data['total'] = $total;

        if($metodo == 'Efectivo'){
            $total_pago = $this->input->post('pago') - $this->input->post('change');
            $data['total_pagado'] = $total_pago + $this->db->get_where('sales', array('code'=>$code))->row()->total_pagado;
            $data['cambio'] = $this->input->post('change');
        }elseif($metodo == 'Tarjeta'){
            $data['total_pagado'] =  $total;
            $data['no_documento'] = $this->input->post('voucher');
        }elseif($metodo == 'Transferencia'){
            $data['total_pagado'] =  $total;
            $data['no_documento'] = $this->input->post('trans');
        }elseif($metodo == 'Cheque'){
            $data['total_pagado'] =  $total;
            $data['no_documento'] = $this->input->post('cheque');
        }

        $data['metodo'] = $this->input->post('metodo');
        
        if($this->input->post('credito') !=''){
            $crd = $this->input->post('credito');
        }
        else
        {
            $crd = 0;
            $data['estado'] = 2;
        }
            
        $data['credito']       = $crd;
        $data['type']          = $this->input->post('type');
        $data['shipping']      = $this->input->post('shipping');
        $data['shipping_cost'] = $this->input->post('shipping_cost');
        $data['client_id']     = $client_id;
        
        if($this->input->post('name') !=''){
            $data['name']  = $this->input->post('name').$this->input->post('last_name');
        }else{
            $data['name']  = 'Consumidor Final';
        }
        if($this->input->post('nit') != ''){
            $data['nit'] = str_replace('-','', $this->input->post('nit'));
        }else{
            $data['nit'] = 'CF';
        }
        
        $data['phone']         = $this->input->post('phone');
        $data['address']       = trim($this->input->post('address'));
        $data['factura']       = 1;
        $data['delivery']      = trim($this->input->post('delivery'));
        $data['details']       = trim($this->input->post('details'));
        $data['FEL']           = $this->input->post('FEL');
        $data['xml_fel']       = $this->input->post('xml_fel');
        $data['code_fel']      = $this->input->post('code_fel');
        $data['date_fel']      = $this->input->post('date_fel');
        $data['serie_fel']     = $this->input->post('serie_fel');
        $data['numero_fel']    = $this->input->post('numero_fel');
       
        $this->db->insert('sales', $data);
        $sales_id = $this->db->insert_id();
        
        if($this->input->post('credito') == 1 && $this->input->post('pago') != "" && $this->input->post('pago') != 0)
        {
            $datain['date']         = date('Y-m-d');
            $datain['week']         = date('W');
            $datain['month']        = date('m');
            $datain['year']         = date('Y');
            $datain['datetime']     = date('Y-m-d H:i');
            $datain['amount']       = $this->input->post('pago');
            $datain['responsable']  = $this->db->get_where('sales', array('code'=>$code))->row()->responsable;
            $datain['sale_ref']     = $code;
            $datain['origin']       = $this->input->post('metodo');
            $datain['details']      = trim($this->input->post('details'));
            $datain['branch_id']    = $this->session->userdata('branch_id');
            $datain['status']       = 1;
            $this->db->insert('income', $datain);
        }

        if(!$this->input->post('credito'))
        {
            $datain['date']         = date('Y-m-d');
            $datain['week']         = date('W');
            $datain['month']        = date('m');
            $datain['year']         = date('Y');
            $datain['datetime']     = date('Y-m-d H:i');
            $datain['amount']       = $this->input->post('ttl');
            $datain['responsable']  = $this->db->get_where('sales', array('code'=>$code))->row()->responsable;
            $datain['sale_ref']     = $code;
            $datain['origin']       =  $this->input->post('metodo');
            $datain['details']      = trim($this->input->post('details'));
            $datain['branch_id']    = $this->session->userdata('branch_id');
            $datain['status']       = 1;
            $this->db->insert('income', $datain);
        }
        
        $message = 'Ha creado una  venta con código: '.$code;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/detalles_ventas/'.$code), 'ver_ventas', 'Ventas_update');
        
        if($this->input->post('credito') == 1)
        {
            $message = 'Ha creado una venta con código: '.$code.' a disposición de crédito';
            $this->insert_notification($message, base64_encode('admin/detalles_ventas/'.$code), 'creditos', 'Credito');
            return 1;
        }else {
            return 0;
        }
    }

    function apply_sale($code){
        $branch_id = $this->session->userdata('branch_id');
        if($this->input->post('ctz')==1){
            $dataCTZ['status'] = 0;
            $this->db->where('code', $code)->update('quotes',$dataCTZ);
        }
        
        if($this->input->post('factura')==1){
            $sala = 1;
        }else{$sala=0;}
         if($this->input->post('cui_v')==1) {
            $cui = 1;
        } else {
            $cui = 0;
        }
        
        $data['responsable'] = $this->session->userdata('login_user_id');
        $data['name_res']    = $this->getName('admin',$this->session->userdata('login_user_id'));
        $data['branch_id']   = $this->session->userdata('branch_id');
        $data['code']        = $code;
        $data['sala']        = $sala;
        $data['cui']        = $cui;
        $data['day']         = date('d');
        $data['week']        = date('W');
        $data['month']       = date('m');
        $data['year']        = date('Y');
        $data['date']        = date('Y-m-d');
        
        if(  $this->input->post('new_client') == '1' ) {
            
            $name  = $this->input->post('name');
            $nm = explode(',',$name);
            $datacl['name']      = $nm[0];
            $datacl['last_name'] = $nm[1];
            $datacl['nit']       = str_replace('-','', $this->input->post('nit'));
            $datacl['phone']     = $this->input->post('phone');
            $datacl['email']     = $this->input->post('email');
            $datacl['address']   = trim($this->input->post('address'));
            $datacl['type']      = $this->input->post('type');
            $datacl['branch_id'] = $this->session->userdata('branch_id');
            $datacl['status']    = 1;
            $datacl['responsable'] = $this->session->userdata('login_user_id');
            $datacl['date'] = date('Y-m-d H:i:s');
            $this->db->insert('client',$datacl);
            $client_id = $this->db->insert_id();

        }else {
            $client_id = $this->input->post('client_id');
        }
        
        $products    = $this->input->post('product');
        $amount      = $this->input->post('amount');
        $price       = $this->input->post('price');
        $cost        = $this->input->post('cost');
        $price_my    = $this->input->post('price_my');
        $price_farma = $this->input->post('price_farma');
        $discount    = $this->input->post('discount');
        $sub         = $this->input->post('sub');
        $sub_my      = $this->input->post('sub_my');
        $sub_farma   = $this->input->post('sub_farma');
        
        $num_products = sizeof($products);
        $cliente_type = 2;
        if ($client_id > 0) $cliente_type = $this->db->get_where('client',array('client_id'=>$client_id))->row()->type;
        $data['my']   = $cliente_type;
    
        $producto = array();
        
        for ($i=0; $i < $num_products ; $i++) {
            $producto_indi =  $this->db->get_where('products',array('products_id '=>$products[$i]))->row();
            $iva = 0;
            if (!$producto_indi->iva) $iva = 0;
            else $iva = 1;
            $new_producto = array('product'=>$products[$i], 'amount'=>$amount[$i], 'price'=>$price[$i], 'cost'=>$cost[$i], 'price_my'=>$price_my[$i], 'price_farma'=>$price_farma[$i], 'discount'=>$discount[$i], 'sub'=>$sub[$i], 'sub_my'=>$sub_my[$i], 'sub_farma'=>$sub_farma[$i], 'iva'=>$iva);
            array_push($producto, $new_producto);
            
            $products_id_2 = 0; $cost2 = $cost[$i];
            if($producto_indi->presentation == 'Caja') {
                $products_id_2 = $products[$i];
                $id_produ      = $producto_indi->id_prod_matriz;
                $amount2       = $amount[$i]*$producto_indi->cnt_prod_matriz;
                if($producto_indi->cnt_prod_matriz > 0) $cost2 = $cost[$i] / $producto_indi->cnt_prod_matriz;
            } else {
                $id_produ = $products[$i];
                $amount2  = $amount[$i];
            }
            
            $lotes = $this->db->order_by('lote_id','ASC')->get_where('lotes',array('id_producto'=>$id_produ,'branch_id'=>$branch_id,'existencia >'=>0))->result_array();
            
            //foreach ($lotes as $lote) {
                # code...
              //  $stock = $lote['existencia'];
                
               if($producto_indi->presentation == 'Caja') {
                    
                      if($cliente_type==2) {
                        $dat2['price'] = $price[$i] / $producto_indi->cnt_prod_matriz;
                    }
                    if($cliente_type==1) {
                        $dat2['price'] = $price_my[$i] / $producto_indi->cnt_prod_matriz;
                    }
                    if($cliente_type==3) {
                        $dat2['price'] = $price_farma[$i] / $producto_indi->cnt_prod_matriz;
                    }else{
                        $dat2['price'] = $price[$i] / $producto_indi->cnt_prod_matriz;
                    }
                    
                }
                
                else{
                    
                    if($cliente_type==1) {
                        $dat2['price']       = $price[$i];
                    }
                    if($cliente_type==2) {
                        $dat2['price']       = $price_my[$i];
                    }
                    if($cliente_type==3) {
                        $dat2['price']       = $price_farma[$i];
                    }
                    else
                    {
                        $dat2['price']       = $price[$i];
                    }
                }
              /*  if($amount2 > 0) {
                    if($lote['existencia'] > $amount2) {*/
                        
                        $dat2['date']          = date('Y-m-d');
                        $dat2['code']          = $lote['code'];
                        $dat2['products_id']   = $id_produ;
                        $dat2['user_id']       = $this->session->userdata('login_user_id');
                        $dat2['type']          = 0;
                        $dat2['branch_id']     = $this->session->userdata('branch_id');
                        $dat2['amount']        = $amount2;
                        $dat2['provider']      = $producto_indi->provider; 
                        $dat2['cost']          = $cost2;
                        $dat2['description']   = "Venta";
                        $dat2['activity_ref']  = $code;
                        $dat2['status']           = 1;
                        $dat2['products_id_2'] = $products_id_2;
                        $dat2['iva']           = $iva;
                    
                        $this->db->insert('product_details', $dat2);
                        
                    /*  $new_existencia = $lote['existencia'] - $amount2;
                        $this->db->where('lote_id', $lote['lote_id']);
                        $this->db->update('lotes',array('existencia'=>$new_existencia));
                        break;
                    } else {
                        $dat2['date']          = date('Y-m-d');
                        $dat2['code']          = $lote['code'];
                        $dat2['products_id']   = $id_produ;
                        $dat2['user_id']       = $this->session->userdata('login_user_id');
                        $dat2['type']          = 0;
                        $dat2['branch_id']     = $this->session->userdata('branch_id');
                        $dat2['amount']        = $lote['existencia'];
                        $dat2['provider']      = $producto_indi->provider;
                        $dat2['cost']          = $cost2;
                        $dat2['description']   = "Venta";
                        $dat2['activity_ref']  = $code;
                        $dat2['status']           = 1;
                        $dat2['products_id_2'] = $products_id_2;
                        $dat2['iva']           = $iva;
                        
                        $this->db->insert('product_details', $dat2);  
                        
                        $amount2 = $amount2 - $lote['existencia'];
                        
                        $new_existencia = 0;
                        $this->db->where('lote_id', $lote['lote_id']);
                        $this->db->update('lotes',array('existencia'=>$new_existencia));
                    }
                }
            }*/
            
            $name = $producto_indi->name;
            $message = 'Aplicó '.$name.', ID: '.$products[$i].', Venta: '.$code;
            $this->insert_binnacle($message);
        }
        
        $data['num_products'] = $num_products;
        $data['products']     = json_encode($producto);

        $metodo = $this->input->post('metodo');
        $total = $this->input->post('ttl');
        $data['total'] = $total;

        if($metodo == 'Efectivo'){
            $total_pago = $this->input->post('pago') - $this->input->post('change');
            $data['total_pagado'] = $total_pago + $this->db->get_where('sales', array('code'=>$code))->row()->total_pagado;
            $data['cambio'] = $this->input->post('change');
        }elseif($metodo == 'Tarjeta'){
            $data['total_pagado'] =  $total;
            $data['no_documento'] = $this->input->post('voucher');
        }elseif($metodo == 'Transferencia'){
            $data['total_pagado'] =  $total;
            $data['no_documento'] = $this->input->post('trans');
        }elseif($metodo == 'Cheque'){
            $data['total_pagado'] =  $total;
            $data['no_documento'] = $this->input->post('cheque');
        }

        $data['metodo'] = $this->input->post('metodo');
        
        if($this->input->post('credito') !='')
            $crd = $this->input->post('credito');
        else
            $crd = 0;
            
        $data['credito']       = $crd;
        $data['type']          = $this->input->post('type');
        $data['shipping']      = $this->input->post('shipping');
        $data['shipping_cost'] = $this->input->post('shipping_cost');
        $data['client_id']     = $client_id;
        
        if($this->input->post('name') !=''){
            $data['name']  = $this->input->post('name').$this->input->post('last_name');
        }else{
            $data['name']  = 'Consumidor Final';
        }
        if($this->input->post('nit') != ''){
            $data['nit'] = str_replace('-','', $this->input->post('nit'));
        }else{
            $data['nit'] = 'CF';
        }
        
        $data['phone']         = $this->input->post('phone');
        $data['address']       = trim($this->input->post('address'));
        $data['factura']       = 1;
        $data['delivery']      = trim($this->input->post('delivery'));
        $data['details']       = trim($this->input->post('details'));
        $data['FEL']           = $this->input->post('FEL');
        $data['xml_fel']       = $this->input->post('xml_fel');
        $data['code_fel']      = $this->input->post('code_fel');
        $data['date_fel']      = $this->input->post('date_fel');
        $data['serie_fel']     = $this->input->post('serie_fel');
        $data['numero_fel']    = $this->input->post('numero_fel');
        $data['estado']        = 5;

        $this->db->insert('sales', $data);
        $sales_id = $this->db->insert_id();
        
        $message = 'Ha creado una venta con código: '.$code;
        $this->insert_binnacle($message);
        
        if($this->input->post('credito') == 1)
        {
            $message = 'Ha creado una venta con código: '.$code.' a disposición de crédito';
            $this->insert_notification($message, base64_encode('admin/detalles_ventas/'.$code), 'creditos', 'Credito');
            return 1;
        }else {
            return 0;
        }
    }
    
    function eliminarPAplicado($id, $code)
    {
        $product_id = $id; $restar = 0; $total = 0;
        $prod = $this->db->get_where('products', array('products_id'=>$id))->row_array();
        if ($prod['presentation'] == "Caja") $product_id = $prod['id_prod_matriz'];
        //$to = $this->db->get_where('sales', array('code' => $code))->row()->total;
        $info = $this->db->get_where('product_details', array('products_id' => $product_id, 'activity_ref'=>$code, 'estado'=>1))->result_array();
        foreach ($info as $if) {
            //$lote = $this->db->get_where('lotes', array('code'=>$if['code'], 'id_producto'=>$product_id))->row_array();
            //$new_existencia = $lote['existencia'] + $if['amount'];
            //$this->db->where('lote_id', $lote['lote_id']);
            //$this->db->update('lotes',array('existencia'=>$new_existencia));
            
            $data1['estado'] = 0;
            $data1['status'] = 0;
            $this->db->where('product_details_id',$if['product_details_id']);
            $this->db->update('product_details', $data1);
        }
        /*
        $info = $this->db->query("SELECT * FROM product_details WHERE estado = 1 AND activity_ref = '$code' GROUP BY products_id")->result_array();
        foreach ($info as $if) {
            $prd = $if['price'];  
            $total += $prd;
        }
        $data['total'] = $total;
        $this->db->where('code',$code);
        $this->db->update('sales', $data);*/
        //log_message('error','total'.$total);
    }
    
    function apply_to_sale($code) {
        $branch_id = $this->session->userdata('branch_id');
        $total = 0; $subtotal = 0;
        
        $client_id            = $this->db->get_where('sales',array('code'=>$code))->row()->client_id;
        $cliente_type         = $this->db->get_where('client',array('client_id'=>$client_id))->row()->type;
        
        
        
        // $data['responsable'] = $this->session->userdata('login_user_id');
        $data['name_res']    = $this->getName('admin',$this->session->userdata('login_user_id'));
        $data['code']        = $code;
        $data['day']         = date('d');
        $data['week']        = date('W');
        $data['month']       = date('m');
        $data['year']        = date('Y');
        $data['date']        = date('Y-m-d');
        $data['time']        = date('H:i:s');
        $data['user_sale']   = $this->session->userdata('login_user_id');
        $data['cui']         = $this->input->post('cui');
        
        $metodo = $this->input->post('metodo');
        $total = $this->input->post('ttl');
        $data['total'] = $total;

        if($metodo == 'Efectivo'){
            $total_pago = $this->input->post('pago') - $this->input->post('change');
            $data['total_pagado'] = $total_pago + $this->db->get_where('sales', array('code'=>$code))->row()->total_pagado;
            $data['cambio'] = $this->input->post('change');
        }elseif($metodo == 'Tarjeta'){
            $data['total_pagado'] =  $total;
            $data['no_documento'] = $this->input->post('voucher');
        }elseif($metodo == 'Transferencia'){
            $data['total_pagado'] =  $total;
            $data['no_documento'] = $this->input->post('trans');
        }elseif($metodo == 'Cheque'){
            $data['total_pagado'] =  $total;
            $data['no_documento'] = $this->input->post('cheque');
        }

        $data['metodo'] = $this->input->post('metodo');
        
        $producto = array();
        
        $products    = $this->input->post('product_id');
        $amount      = $this->input->post('amountx');
        $cost        = $this->input->post('costx');
        $price       = $this->input->post('pricex');
        $price_my    = $this->input->post('pricex_my');
        $price_farma = $this->input->post('pricex_farma');
        $discount    = $this->input->post('discountx');
        $sub         = $this->input->post('subx');
        $sub_my      = $this->input->post('subx_my');
        $sub_farma   = $this->input->post('subx_farma');
        $num_productsx = sizeof($products);
        
/*      $detalle_venta = $this->db->get_where('product_details',array('activity_ref'=>$code))->result_array();
        foreach($detalle_venta as $row){
        $lote = $this->db->get_where('lotes',array('code'=>$row['code'],'id_producto'=>$row['products_id']))->row_array();
        $data_lote['existencia'] = $lote['existencia']-$row['amount'];
        $this->db->where('lote_id', $lote['lote_id']);
        $this->db->update('lotes', $data_lote);    
        }*/
        
        $data3['status'] = 1;
        $this->db->where('estado', 1);
        $this->db->where('activity_ref', $code);
        $this->db->update('product_details', $data3);
            
        for ($i=0; $i < $num_productsx ; $i++) {
            $producto_indi =  $this->db->get_where('products',array('products_id '=>$products[$i]))->row();
            $iva = 0;
            if (!$producto_indi->iva) $iva = 0;
            else $iva = 1;
            $new_producto = array('product'=>$products[$i], 'amount'=>$amount[$i], 'price'=>$price[$i], 'cost'=>$cost[$i], 'price_my'=>$price_my[$i], 'price_farma'=>$price_farma[$i], 'discount'=>$discount[$i], 'sub'=>$sub[$i], 'sub_my'=>$sub_my[$i], 'sub_farma'=>$sub_farma[$i], 'iva'=>$iva);
            array_push($producto, $new_producto);
        }
        
        $products    = $this->input->post('product');
        $amount      = $this->input->post('amount');
        $cost        = $this->input->post('cost');
        $price       = $this->input->post('price');
        $price_my    = $this->input->post('price_my');
        $price_farma = $this->input->post('price_farma');
        $discount    = $this->input->post('discount');
        $sub         = $this->input->post('sub');
        $sub_my      = $this->input->post('sub_my');
        $sub_farma   = $this->input->post('sub_farma');
        
        $num_products = sizeof($products);
        for ($i=0; $i < $num_products ; $i++) { 
            $subtotal = 0;
            $producto_indi =  $this->db->get_where('products',array('products_id '=>$products[$i]))->row();
            $iva = 0;
            if (!$producto_indi->iva) $iva = 0;
            else $iva = 1;
            $new_producto = array('product'=>$products[$i], 'amount'=>$amount[$i], 'price'=>$price[$i], 'cost'=>$cost[$i], 'price_my'=>$price_my[$i], 'price_farma'=>$price_farma[$i], 'discount'=>$discount[$i], 'sub'=>$sub[$i], 'sub_my'=>$sub_my[$i], 'sub_farma'=>$sub_farma[$i], 'iva'=>$iva);
            array_push($producto, $new_producto);
            
            $subtotal = ($amount[$i] * $price[$i]);
            $total += $subtotal;
            
            $products_id_2 = 0; $cost2 = $cost[$i];
            if($producto_indi->presentation == 'Caja') {
                $products_id_2 = $products[$i];
                $id_produ = $producto_indi->id_prod_matriz;
                $amount2  = $amount[$i]*$producto_indi->cnt_prod_matriz;
                if($producto_indi->cnt_prod_matriz > 0) 
                $cost2 = $cost[$i] / $producto_indi->cnt_prod_matriz;
            } else {
                $id_produ = $products[$i];
                $amount2  = $amount[$i];
            }
            
            $lotes = $this->db->order_by('lote_id','ASC')->get_where('lotes',array('id_producto'=>$id_produ,'branch_id'=>$branch_id,'existencia >'=>0))->result_array();
            
           // foreach ($lotes as $lote) {
                # code...
             //   $stock = $lote['existencia'];
                
                if($producto_indi->presentation == 'Caja') {
                    
                      if($cliente_type==2) {
                        $dat2['price'] = $price[$i] / $producto_indi->cnt_prod_matriz;
                    }
                    if($cliente_type==1) {
                        $dat2['price'] = $price_my[$i] / $producto_indi->cnt_prod_matriz;
                    }
                    if($cliente_type==3) {
                        $dat2['price'] = $price_farma[$i] / $producto_indi->cnt_prod_matriz;
                    }else{
                        $dat2['price'] = $price[$i] / $producto_indi->cnt_prod_matriz;
                    }
                    
                }
                
                else{
                    
                    if($cliente_type==2) {
                        $dat2['price']       = $price[$i];
                    }
                    if($cliente_type==1) {
                        $dat2['price']       = $price_my[$i];
                    }
                    if($cliente_type==3) {
                        $dat2['price']       = $price_farma[$i];
                    }else{
                        $dat2['price']       = $price[$i];
                    }
                    
                }
                

            //    if ($amount2 > 0) {
                //    if($lote['existencia'] > $amount2) {
                        
                        $dat2['date']          = date('Y-m-d');
                        $dat2['code']          = $lote['code'];
                        $dat2['products_id']   = $id_produ;
                        $dat2['user_id']       = $this->session->userdata('login_user_id');
                        $dat2['type']          = 0;
                        $dat2['branch_id']     = $this->session->userdata('branch_id');
                        $dat2['amount']        = $amount2;
                        $dat2['provider']      = $producto_indi->provider; 
                        $dat2['cost']          = $cost2;
                        $dat2['description']   = "Venta";
                        $dat2['activity_ref']  = $code;
                        $dat2['products_id_2'] = $products_id_2;
                        $dat2['iva']           = $iva;
                    
                        $this->db->insert('product_details', $dat2);  
                 /*       
                        $new_existencia = $lote['existencia'] - $amount2;
                        $this->db->where('lote_id', $lote['lote_id']);
                        $this->db->update('lotes',array('existencia'=>$new_existencia));
                        break;
                    } else {
                        $dat2['date']          = date('Y-m-d');
                        $dat2['code']          = $lote['code'];
                        $dat2['products_id']   = $id_produ;
                        $dat2['user_id']       = $this->session->userdata('login_user_id');
                        $dat2['type']          = 0;
                        $dat2['branch_id']     = $this->session->userdata('branch_id');
                        $dat2['amount']        = $lote['existencia'];
                        $dat2['provider']      = $producto_indi->provider;
                        $dat2['cost']          = $cost2;
                        $dat2['description']   = "Venta";
                        $dat2['activity_ref']  = $code;
                        $dat2['products_id_2'] = $products_id_2;
                        $dat2['iva']           = $iva;
                        
                        $this->db->insert('product_details', $dat2);  
                        
                        $amount2 = $amount2 - $lote['existencia'];
                        
                        $new_existencia = 0;
                        $this->db->where('lote_id', $lote['lote_id']);
                        $this->db->update('lotes',array('existencia'=>$new_existencia));
                    }
                }
            }*/
            
            $this->alerta_stock($id_produ);

            $name = $producto_indi->name;
            $message = 'Vendió '.$name.', ID: '.$products[$i].', Venta: '.$code;
            $this->insert_binnacle($message);
        }
        $total = $this->input->post('ttl');
        $total_pagado = $this->input->post('total_pagado');
        $data['estado'] = 2;
         if ($this->input->post('credito')!=1) {
            $data['estado'] = 2;
            $data['credito'] = 0;
        } else {
            $data['estado'] = 1;
            $data['credito'] = 1;
        }
        
        $data['num_products'] = $num_products + $num_productsx;
        $data['products']     = json_encode($producto);

        $data['total'] = $this->input->post('ttl');

        $this->db->where('code', $code);
        $query = $this->db->update('sales', $data);
        
        $message = 'Ha creado una venta con código: '.$code;
        $this->insert_binnacle($message);
        
        return $query;
    }
    
    function edit_sale($code) 
    {
        log_message('error','aca entro');
        $branch_id            = $this->session->userdata('branch_id');
        
        $client_id            = $this->db->get_where('sales',array('code'=>$code))->row()->client_id;
        $cliente_type         = $this->db->get_where('client',array('client_id'=>$client_id))->row()->type;
        
        $total = 0; $subtotal = 0;
        $data['responsable'] = $this->session->userdata('login_user_id');
        $data['name_res']    = $this->getName('admin',$this->session->userdata('login_user_id'));
        $data['code']        = $code;
        $data['day']         = date('d');
        $data['week']        = date('W');
        $data['month']       = date('m');
        $data['year']        = date('Y');
        $data['date']        = date('Y-m-d');
        
        
        
        $products    = $this->input->post('product_id');
        $amount      = $this->input->post('amountx');
        $cost        = $this->input->post('costx');
        $price       = $this->input->post('pricex');
        $price_my    = $this->input->post('pricex_my');
        $price_farma = $this->input->post('pricex_farma');
        $discount    = $this->input->post('discountx');
        $sub         = $this->input->post('subx');
        $sub_my      = $this->input->post('subx_my');
        $sub_farma   = $this->input->post('subx_farma');
        $num_productsx = sizeof($products);
        
        
        
        $producto = array();
        
        for ($i=0; $i < $num_productsx ; $i++) {
            $producto_indi =  $this->db->get_where('products',array('products_id '=>$products[$i]))->row();
            $iva = 0;
            if (!$producto_indi->iva) $iva = 0;
            else $iva = 1;
            $new_producto = array('product'=>$products[$i], 'amount'=>$amount[$i], 'price'=>$price[$i], 'cost'=>$cost[$i], 'price_my'=>$price_my[$i], 'price_farma'=>$price_farma[$i], 'discount'=>$discount[$i], 'sub'=>$sub[$i], 'sub_my'=>$sub_my[$i], 'sub_farma'=>$sub_farma[$i], 'iva'=>$iva);
            array_push($producto, $new_producto);
        }
        
        $products    = $this->input->post('product');
        $amount      = $this->input->post('amount');
        $cost        = $this->input->post('cost');
        $price       = $this->input->post('price');
        $price_my    = $this->input->post('price_my');
        $price_farma = $this->input->post('price_farma');
        $discount    = $this->input->post('discount');
        $sub         = $this->input->post('sub');
        $sub_my      = $this->input->post('sub_my');
        $sub_farma   = $this->input->post('sub_farma');
        
        $num_products = sizeof($products);
        for ($i=0; $i < $num_products ; $i++) 
        { 
            $subtotal = 0;
            $producto_indi = $this->db->get_where('products',array('products_id '=>$products[$i]))->row();
            $iva = 0;
            if (!$producto_indi->iva) $iva = 0;
            else $iva = 1;
            $new_producto = array('product'=>$products[$i], 'amount'=>$amount[$i], 'price'=>$price[$i], 'cost'=>$cost[$i], 'price_my'=>$price_my[$i], 'price_farma'=>$price_farma[$i], 'discount'=>$discount[$i], 'sub'=>$sub[$i], 'sub_my'=>$sub_my[$i], 'sub_farma'=>$sub_farma[$i], 'iva'=>$iva);
            array_push($producto, $new_producto);
            
            $subtotal = ($amount[$i] * $price[$i]);
            $total += $subtotal;
            
            $products_id_2 = 0; $cost2 = $cost[$i];
            if($producto_indi->presentation == 'Caja') {
                $products_id_2 = $products[$i];
                $id_produ = $producto_indi->id_prod_matriz;
                $amount2  = $amount[$i]*$producto_indi->cnt_prod_matriz;
                if($producto_indi->cnt_prod_matriz > 0){
                    $cost2 = $cost[$i] / $producto_indi->cnt_prod_matriz;
                }
            }
            
            
            else {
                $id_produ = $products[$i];
                $amount2  = $amount[$i];
            }
            
            $lotes = $this->db->order_by('lote_id','ASC')->get_where('lotes',array('id_producto'=>$id_produ,'branch_id'=>$branch_id,'existencia >'=>0))->result_array();
            
           // foreach ($lotes as $lote) {
                # code...
                //$stock = $lote['existencia'];
                
                 if($producto_indi->presentation == 'Caja') {
                    
                     if($cliente_type==1) {
                        $dat2['price'] = $price_my[$i] / $producto_indi->cnt_prod_matriz;
                    }
                    elseif($cliente_type==2) {
                        $dat2['price'] = $price[$i] / $producto_indi->cnt_prod_matriz;
                    }
                    elseif($cliente_type==3) {
                        $dat2['price'] = $price_farma[$i] / $producto_indi->cnt_prod_matriz;
                    }
                    else{
                        $dat2['price'] = $price[$i] / $producto_indi->cnt_prod_matriz;
                    }
                    
                }
                
                else{
                    
                    if($cliente_type==1) {
                        $dat2['price']       = $price_my[$i];
                    }
                    elseif($cliente_type==2) {
                        $dat2['price']       = $price[$i];
                    }
                    elseif($cliente_type==3) {
                        $dat2['price']       = $price_farma[$i];
                    }
                    else {
                        $dat2['price']       = $price[$i];
                    }
                }
                
                
              //  if ($amount2 > 0) {
               //     if($lote['existencia'] > $amount2) {
                        
                        $dat2['date']          = date('Y-m-d');
                        $dat2['code']          = $lote['code'];
                        $dat2['products_id']   = $id_produ;
                        $dat2['user_id']       = $this->session->userdata('login_user_id');
                        $dat2['type']          = 0;
                        $dat2['branch_id']     = $this->session->userdata('branch_id');
                        $dat2['amount']        = $amount2;
                        $dat2['provider']      = $producto_indi->provider; 
                        $dat2['provider']      = $producto_indi->provider; 
                        $dat2['cost']          = $cost2;
                        $dat2['description']   = "Venta";
                        $dat2['activity_ref']  = $code;
                        $dat2['products_id_2'] = $products_id_2;
                        $dat2['iva']           = $iva;
                        $dat2['status']        = 1;
                    
                        $this->db->insert('product_details', $dat2);  
                        
                 /*       $new_existencia = $lote['existencia'] - $amount2;
                        $this->db->where('lote_id', $lote['lote_id']);
                        $this->db->update('lotes',array('existencia'=>$new_existencia));
                        break;
                    } else {
                        $dat2['date']          = date('Y-m-d');
                        $dat2['code']          = $lote['code'];
                        $dat2['products_id']   = $id_produ;
                        $dat2['user_id']       = $this->session->userdata('login_user_id');
                        $dat2['type']          = 0;
                        $dat2['branch_id']     = $this->session->userdata('branch_id');
                        $dat2['amount']        = $lote['existencia'];
                        $dat2['provider']      = $producto_indi->provider;
                        $dat2['cost']          = $cost2;
                        $dat2['description']   = "Venta";
                        $dat2['activity_ref']  = $code;
                        $dat2['products_id_2'] = $products_id_2;
                        $dat2['iva']           = $iva;
                        $dat2['status']        = 1;
                        
                        $this->db->insert('product_details', $dat2);  
                        
                        $amount2 = $amount2 - $lote['existencia'];
                        
                        $new_existencia = 0;
                        $this->db->where('lote_id', $lote['lote_id']);
                        $this->db->update('lotes',array('existencia'=>$new_existencia));
                    }
                }
            }*/
            
            $this->alerta_stock($id_produ);

            $name = $producto_indi->name;
            $message = 'Aplicó '.$name.', ID: '.$products[$i].', Venta: '.$code;
            $this->insert_binnacle($message);
        }
        $total = $this->input->post('total');
        
        $data['num_products'] = $num_products + $num_productsx;
        $data['products']     = json_encode($producto);

        $data['total'] = $this->input->post('ttl');

        $this->db->where('code', $code);
        $query = $this->db->update('sales', $data);
        
        $message = 'Ha editado una venta con código: '.$code;
        $this->insert_binnacle($message);
        
        return $query;
    }
    
    function generar_venta($code) {
        $total = 0;
        $sale = $this->db->get_where('sales', array('code'=>$code))->row_array();
        $prods = json_decode($sale['products']);
        $data['estado'] = 1;
        $data['date']   = date("Y-m-d");
        
        $data_det['status'] = 1;
        $this->db->where('description', 'Venta');
        $this->db->where('activity_ref', $code);
        $this->db->update('product_details', $data_det);
        
        for ($i=0; $i < count($prods); $i++) {
            $pd_id = $prods[$i]->product;
            $producto_indi =  $this->db->get_where('products',array('products_id '=>$pd_id))->row();
            if ($producto_indi->presentacion != 'Caja') {
                $lote_id=0;
                //echo 'producto: '.$pd_id.'- lote'.$lote_id;
                $lote = $this->db->get_where('lotes', array('status' => 1,'id_producto'=> $pd_id,'branch_id'=>$this->session->userdata('branch_id')))->result_array();
                //echo 'lotes'.print_r($lote);
                $bool=1;
                $existencia=0;
                $hoy = date('Y-m-d');
                /*foreach($lote as $row)
                {
                    //echo 'id_lote:  '.$row['lote_id'];
                    if(($row['existencia']>0) && ($row['fecha_vencimiento'] > $hoy)&&($bool=1)){
                     $lote_id= $row['lote_id'];
                     $existencia = $row['existencia'];
                     $bool = 0;
                    }
                }  
                //echo 'lote: '.$lote_id.'- existe'.$existencia;
                if($bool==0){
                    $dat_lote_detail['lote_id'] = $lote_id;
                    $dat_lote_detail['code']    = $code;
                    $dat_lote_detail['cantidad']= $prods[$i]->amount;
                    $this->db->insert('lote_detail', $dat_lote_detail);
                    
                    $datalot['existencia']       = $existencia-$prods[$i]->amount;
                    $this->db->where('lote_id', $lote_id);
                    $this->db->update('lotes', $datalot);
                }*/
            }
            $this->alerta_stock($pd_id);

            $name = $this->db->get_where('products',array('products_id'=>$pd_id))->row()->name;
            $message = 'Vendió '.$name.', ID: '.$pd_id.', Venta: '.$code;
            $this->insert_binnacle($message);
            
            $total += $prods[$i]->sub;
        }
        
        if(($sale['credito'] == 1 && $sale['total_pagado'] > 0)|| $this->input->post('credito') == 1)
        {
            /*$data_ct['date']      = date('d/m/Y');
            $data_ct['sales_id']  = $sale['sales_id'];
            $data_ct['method']    = $sale['metodo'];
            $data_ct['amount']    = $sale['total_pagado'];
            $data_ct['status']    = 1;

            $this->db->insert('credit_details', $data_ct);
            
            $datain['date']         = date('Y-m-d');
            $datain['week']         = date('W');
            $datain['month']        = date('m');
            $datain['year']         = date('Y');
            $datain['datetime']     = date('Y-m-d H:i');
            $datain['amount']       = $sale['total_pagado'];
            $datain['responsable']  = $sale['responsable'];
            $datain['sale_ref']     = $code;
            $datain['origin']       = $sale['metodo'];
            $datain['details']      = trim($sale['details']);
            $datain['branch_id']    = $this->session->userdata('branch_id');
            $datain['status']       = 1;
            $this->db->insert('income', $datain);*/
        }

        if(!$sale['credito'])
        {
            $datain['date']         = date('Y-m-d');
            $datain['week']         = date('W');
            $datain['month']        = date('m');
            $datain['year']         = date('Y');
            $datain['datetime']     = date('Y-m-d H:i');
            $datain['amount']       = $sale['total'];
            $datain['responsable']  = $sale['responsable'];
            $datain['sale_ref']     = $code;
            $datain['origin']       = $sale['metodo'];
            $datain['details']      = trim($sale['details']);
            $datain['branch_id']    = $this->session->userdata('branch_id');
            $datain['status']       = 1;
            $this->db->insert('income', $datain);
        }
        log_message('error','credito'.$this->input->post('credito'));
        if($this->input->post('credito') != 1){
            $data['estado'] = 2;
            $data['total_pagado']= 0;
        }else{
            $data['estado'] = 1;
            $data['credito'] = 1;
        }
        $this->db->where('code', $code);
        $this->db->update('sales', $data);
        
        if($sale['credito'] == 1) {
            $message = 'Ha creado una venta con código: '.$code.' a disposición de crédito';
            $this->insert_notification($message, base64_encode('admin/detalles_ventas/'.$code), 'creditos', 'Credito');
            return 1;
        } else {
            return 0;
        }
    }
    
    function alerta_stock($product_id='')
    {
        $name = $this->db->get_where('products',array('products_id'=>$product_id))->row()->name;
        $alert = $this->db->get_where('products',array('products_id'=>$product_id))->row()->alert;
        $stock_inventory = $this->get_stock($product_id, $this->session->userdata('branch_id'));
        $stock_bodega = $this->get_stock($product_id, 0);

        if (($stock_inventory + $stock_bodega) <= $alert && ($stock_inventory + $stock_bodega) > 0) {
            $message = 'Vendió el producto: '.$name.', y cambio a estado de Alerta';
            $this->insert_notification($message, base64_encode('admin/producto_detalle/'.$product_id), 'alertas_productos', 'Alerta');
        }
    }

    function send_email_sale($client_id, $code)
    {
        $destino = $this->db->get_where('client',array('client_id'=>$client_id))->row()->email;
        if($this->db->get_where('settings', array('type'=>'noti_email'))->row()->description == 1 && $destino!=''){

            $msg = 'Nuevo mensaje recibido a tráves del sitio web, los datos son los siguientes: <br><br>';
            $msg .= '<b>Ingresa al siguiente link:</b><a href="'.base_url().'admin/export_pdf/ventaFEL/'.$code.'"> Factura </a><br>';

            require("class.phpmailer.php");
            $emails = $destino;
            $mail = new PHPMailer(); 
            $mail->CharSet = 'UTF-8';
            $mail->IsHTML(true);
            $mail->IsMail();
            $mail->addReplyTo($destino);
            $mail->Subject = 'datos de la venta';
            $mail->SetFrom('no-reply@msbox.gt', 'Datos de la venta');
            $mail->AddAddress($emails);
            $data = array(
                'email_msg' => $msg,
                'asuntico' => 'Datos de la venta'
            );
            $mail->Body = $this->load->view('backend/mails/notify.php',$data,TRUE);
            if(!$mail->Send()) {
                echo "Mailer Error: " . $mail->ErrorInfo;
            }

        } 
        
        $phone = $this->db->get_where('client',array('client_id'=>$client_id))->row()->phone;
        if($this->db->get_where('settings', array('type'=>'whatsapp'))->row()->description == 1 && $this->db->get_where('settings', array('type'=>'code'))->row()->description != '' &&  $phone !='' ){
        $code = $this->db->get_where('settings', array('type'=>'code'))->row()->description ;
        $token  = $code;
        $telefono = '502'.$phone;
        $mensaje = 'Ingresa al siguiente link:'.base_url().'admin/export_pdf/ventaFEL/'.$code;
        
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://msalerts.com/api/sendMessage',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => '5L',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'token='.$token.'&phone='.$telefono.'&message='.urlencode($mensaje),
            CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
        ),
        ));
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err)
        {
            // Si entra en la validación ocurrió un error durante el envío.
        } 
        else 
        {
            // El mensaje fue enviado correctamente
        }
    
        }
    }

    function asignar_fecha($code)
    {
        $data['shipping_date'] = date("Y-m-d", strtotime($this->input->post('shipping_date')));
        $this->db->where('code', $code);
        $this->db->update('sales', $data);
        
        $message = 'Ha asignado fecha de entrega al envió sobre la venta con código: '.$code;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/envios/'), 'ver_envios', 'Envíos');
    }

    function cancel_shipping($code)
    {
        $data['shipping'] = 0;
        $this->db->where('code', $code);
        $this->db->update('sales', $data);
    }

    function completar_entrega($code)
    {
        $delivery = $this->db->get_where('delivery', array('code'=>$code))->row_array();

        $servicio = $this->db->get_where('service_transport', array('service_transport_id'=>$delivery['service_transport']))->row_array();

        $dattra['status'] = 1;
        $this->db->where('transport_id', $servicio['transport_id']);
        $this->db->update('transport', $dattra);

        $data['estado'] = 2;
        $this->db->where('code', $code);
        $this->db->update('delivery', $data);
    }

    function create_delivery()
    {
        $moneda        = $this->get_info('moneda');
        $branch_id     = $this->session->userdata('branch_id');
        $datetime      = date('Y-m-d H:i:s');
        $responsable   = $this->input->post('responsable');
        $resp_name     = $this->crud_model->getName('admin', $responsable);
        $transporte    = $this->input->post('transporte');
        $estado        = $this->input->post('estado');
        $sale_code     = $this->input->post('sale_code');
        if ($transporte == "new_interno") {
            $dattra['name']          = $this->input->post('transport_name');
            $dattra['license_plate'] = $this->input->post('transport_plate');
            $dattra['responsable']   = $responsable;
            $dattra['branch_id']     = $branch_id;
            if ($estado == "en_ruta") {
                $dattra['status'] = 2;
            }
            $this->db->insert('transport', $dattra);
            $datser['transport_id'] = $this->db->insert_id();
            $datser['type'] = 1;
        } elseif($transporte == "externo"){
            $datser['code'] = $this->input->post('delivery_code');

            if($this->input->post('new_company') == 1){
                $datcom['name'] = $this->input->post('company_name');
                $this->db->insert('delivery_company', $datcom);
                $datser['company_id'] = $this->db->insert_id();
            }
            else{
                $datser['company_id'] = $this->input->post('list_company');
            }
            $datser['type'] = 2;
        }
        else{
            $datser['transport_id'] = $transporte;
            $datser['type'] = 1;
            if ($estado == "en_ruta") {
                $datest['status'] = 2;
                $this->db->where('transport_id', $transporte);
                $this->db->update('transport', $datest);
            }
        }
        $delivery_date = $this->input->post('delivery_date');
        $origin        = $this->input->post('origin');
        $cost          = $this->input->post('cost');
        $cost_extra    = $this->input->post('cost_extra');
        $total         = $this->input->post('total');
        $address       = trim($this->input->post('address'));
        $notes         = trim($this->input->post('notes'));
        if ($notes == '') {
            $notes = 'Ninguna';
        }
        $code          = $this->input->post('code');
        $detalles      = 'Servicio de entrega de productos, con un costo total de: '.$moneda.number_format($total,2,'.',',').', con costo inicial de: '.$moneda.number_format($cost,2,'.',',').', con costo extra de: '.$moneda.number_format($cost_extra,2,'.',',').', y con anotación: '.$notes;

        $datser['date']           = $delivery_date;
        $datser['payment_method'] = $origin;
        if ($origin != 0) {
            $datser['account_bank_id'] = $this->input->post('account_bank');
        }
        else{
            $datser['account_bank_id'] = $this->db->get_where('account_bank', array('bank_id'=>0, 'branch_id'=>$branch_id, 'status'=> 1))->row()->account_bank_id;
        }
        $datser['price']          = $total;
        $datser['place_service']  = $address;
        $datser['responsable']    = $responsable;
        $datser['details']        = $detalles;
        $this->db->insert('service_transport', $datser);
        $data['service_transport'] = $this->db->insert_id();

        $datexp['date']          = $delivery_date;
        $datexp['week']          = date('W', strtotime($delivery_date));
        $datexp['month']         = date('m', strtotime($delivery_date));
        $datexp['year']          = date('Y', strtotime($delivery_date));
        $datexp['amount']        = $total;
        $datexp['responsable']   = $responsable;
        $datexp['provider']      = "Entrega de productos";
        if ($origin == 0) {
            $datexp['origin']    = $origin;
        }
        else{
            $datexp['origin']    = $this->input->post('account_bank');
        }
        if ($origin == 0) {
            $cuenta = $this->db->get_where('account_bank', array('bank_id' => $origin,'branch_id' => $branch_id,'status'=> 1))->row_array();
        }
        else {
            $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $this->input->post('account_bank')))->row_array();
        }
        $datexp['saldo_inicial']    = $cuenta['current_balance'];
        $saldo = $cuenta['current_balance'] - $total;
        $datexp['nuevo_saldo']      = $saldo;
        $dataacc['current_balance'] = $saldo;

        $this->db->where('account_bank_id', $cuenta['account_bank_id']);
        $this->db->update('account_bank', $dataacc);

        $datexp['details']         = $detalles;
        $datexp['datetime']        = $datetime;
        $datexp['admin_id']        = $this->session->userdata('login_user_id');
        $datexp['branch_id']       = $branch_id;
        $datexp['reference_id']    = $code;
        $datexp['table_reference'] = 'delivery';
        $datexp['status']          = 1;
        $this->db->insert('expense', $datexp);

        if ($estado == "en_ruta") {
            $data['estado'] = 1;
        }
        elseif ($estado == "entregado") {
            $data['estado'] = 2;
        }

        $data['code']           = $code;
        $data['sale_code']      = $sale_code;
        $data['responsable_id'] = $responsable;
        $data['branch_id']      = $branch_id;
        $data['fecha_asignada'] = date("Y-m-d", strtotime($this->input->post('shipping_date')));
        $data['fecha_entrega']  = $delivery_date;
        $data['cost']           = $cost;
        $data['cost_extra']     = $cost_extra;
        $data['total']          = $this->input->post('total');
        $data['address']        = $address;
        $data['notes']          = $notes;
        $data['status']         = 1;
        $this->db->insert('delivery', $data);

        $datsale['shipping'] = 2;
        $datsale['delivery'] = $address;
        $this->db->where('code', $sale_code);
        $this->db->update('sales', $datsale);
        
        $message = 'Ha registrado una entrega con código: '.$code;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/detalles_entrega/'.$code), 'ver_envios', 'Entregas');
    }

    function update_delivery($code)
    {
        $data['responsable_id'] = $this->input->post('responsable');
        $data['fecha_entrega']  = date("Y-m-d", strtotime($this->input->post('delivery_date')));
        $data['address']        = trim($this->input->post('address'));
        $data['notes']          = $this->input->post('notes');
        $this->db->where('code', $code);
        $this->db->update('delivery', $data);

        $delivery = $this->db->get_where('delivery', array('code'=>$code))->row_array();

        $detalles = 'Servicio de entrega de productos, con un costo total de: '.$delivery['total'].', con costo inicial de: '.$moneda.number_format($delivery['cost'],2,'.',',').', con costo extra de: '.$moneda.number_format($delivery['cost_extra'],2,'.',',').', y con anotación: '.$this->input->post('notes').'. (Editado)';
        $resp_name = $this->getName('admin', $this->input->post('responsable'));

        $transporte = $this->input->post('transporte');
        if ($transporte == "new_interno") {
            $dattra['name']          = $this->input->post('transport_name');
            $dattra['license_plate'] = $this->input->post('transport_plate');
            $dattra['responsable']   = $responsable;
            $dattra['branch_id']     = $branch_id;
            $this->db->insert('transport', $dattra);
            $datser['transport_id'] = $this->db->insert_id();
            $datser['type'] = 1;
            $datser['company_id'] = null;
        } elseif($transporte == "externo"){
            $datser['code'] = $this->input->post('delivery_code');

            if($this->input->post('new_company') == 1){
                $datcom['name'] = $this->input->post('company_name');
                $this->db->insert('delivery_company', $datcom);
                $datser['company_id'] = $this->db->insert_id();
            }
            else{
                $datser['company_id'] = $this->input->post('list_company');
            }
            $datser['type'] = 2;
            $datser['transport_id'] = null;
        } else{
            $datser['transport_id'] = $transporte;
            $datser['type'] = 1;
            $datser['company_id'] = null;
        }
        $datser['responsable']   = $this->input->post('responsable');
        $datser['date']          = date("Y-m-d", strtotime($this->input->post('delivery_date')));
        $datser['place_service'] = trim($this->input->post('address'));
        $datser['details']       = $detalles;
        $this->db->where('service_transport_id', $delivery['service_transport']);
        $this->db->update('service_transport', $datser);

        $datexp['date']        = date("Y-m-d", strtotime($this->input->post('delivery_date')));
        $datexp['responsable'] = $this->input->post('responsable');
        $datexp['details']     = $detalles;
        $this->db->where('reference_id', $code);
        $this->db->where('table_reference', 'delivery');
        $this->db->update('expense', $datexp);
        
        $message = 'Ha registrado una entrega con código: '.$code;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/detalles_entrega/'.$code), 'ver_envios', 'Entregas_update');
    }

    function delete_delivery($code)
    {
        $branch_id = $this->session->userdata('branch_id');
        $data['estado'] = 3;
        $data['status'] = 0;
        $data['reason'] = trim($this->input->post('reason'));
        $this->db->where('code', $code);
        $this->db->update('delivery', $data);

        $delivery = $this->db->get_where('delivery', array('code'=>$code))->row_array();
        $servicio = $this->db->get_where('service_transport', array('service_transport_id'=>$delivery['service_transport']))->row_array();

        $dattra['status'] = 1;
        $this->db->where('transport_id', $servicio['transport_id']);
        $this->db->update('transport', $dattra);

        $datser['status'] = 0;
        $this->db->where('service_transport_id', $delivery['service_transport']);
        $this->db->update('service_transport', $datser);

        $datexp['status'] = 0;
        $datexp['reason'] = 'Anulación de entrega, con razón: '.trim($this->input->post('reason'));
        $this->db->where('reference_id', $code);
        $this->db->where('table_reference', 'delivery');
        $this->db->update('expense', $datexp);

        $datsal['shipping'] = 0;
        $this->db->where('code', $delivery['sale_code']);
        $this->db->update('sales', $datsal);

        $servicio = $this->db->get_where('service_transport', array('service_transport_id'=>$delivery['service_transport']))->row_array();

        if ($servicio['payment_method'] == 0) {
            $cuenta = $this->db->get_where('account_bank', array('bank_id' => 0,'branch_id' =>$branch_id, 'status'=> 1))->row_array();
        }
        else {
            $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $servicio['account_bank_id']))->row_array();
        }
        $nuevo_saldo = $cuenta['current_balance'] + $delivery['total'];
        $datacc['current_balance'] = $nuevo_saldo;

        $this->db->where('account_bank_id', $cuenta['account_bank_id']);
        $this->db->update('account_bank', $datacc);

        $message = 'Ha eliminado una entrega con código: '.$code;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/entregas/'), 'ver_envios', 'Entregas_delete');
    }

    function add_pay($code){

        $sales_id = $this->db->get_where('sales', array('code'=>$code))->row()->sales_id;
        $total = $this->db->get_where('sales', array('code'=>$code))->row()->total;
        $total_pagado = $this->db->get_where('sales', array('code'=>$code))->row()->total_pagado;
        $datetime = date("Y-m-d H:i:s");
        $data_ct['responsable_id'] = $this->input->post('responsable');
        $data_ct['date']           = date("Y-m-d", strtotime($this->input->post('date')));
        $data_ct['sales_id']       = $sales_id;
        $data_ct['amount']         = $this->input->post('pago');
        $data_ct['method']         = $this->input->post('metodo');
        if ($data_ct['method'] != "Efectivo") {
            $data_ct['account_bank_id'] = $this->input->post('account_bank');
            $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $data_ct['account_bank_id']))->row_array();
            $data_bk['current_balance'] = $data_ct['amount'] + $cuenta['current_balance'];
            $this->db->where('account_bank_id', $data_ct['account_bank_id']);
            $this->db->update('account_bank', $data_bk);

            $data_in['origin'] = $this->input->post('account_bank');
        }
        else{
            $data_ct['account_bank_id'] = 0;
            
            $cuenta = $this->db->get_where('account_bank', array('bank_id' => 0, 'status' => 1, 'branch_id'=>$this->session->userdata('branch_id')))->row_array();
            $data_bk['current_balance'] = $data_ct['amount'] + $cuenta['current_balance'];
            $this->db->where('account_bank_id', $cuenta['account_bank_id']);
            $this->db->update('account_bank', $data_bk);

            $data_in['origin'] = 0;
        }
        $data_ct['notes']          = trim($this->input->post('notes'));
        $data_ct['datetime']       = $datetime;
        $data_ct['status']         = 1;

        $this->db->insert('credit_details', $data_ct);
        $message = 'Ha agregado un nuevo pago al crédito de la venta con código: '.$code;
        $this->insert_binnacle($message);

        $data_in['date']        = date("Y-m-d", strtotime($this->input->post('date')));
        $data_in['week']        = date("W");
        $data_in['month']       = date("m");
        $data_in['year']        = date("Y");
        $data_in['datetime']    = $datetime;
        $data_in['amount']      = $this->input->post('pago');
        $data_in['details']     = trim($this->input->post('notes'));
        $data_in['responsable'] = $this->input->post('responsable');
        $data_in['sale_ref']    = $code;
        $data_in['branch_id']   = $this->session->userdata('branch_id');
        $data_in['status']      = 1;
        $this->db->insert('income', $data_in);
         $month = date('m');
         $year = date('Y');
         $day = date('d');
         $hoy = date('Y-m-d');
        
        
        $data['metodo'] = $data_ct['method'];
        if(($total_pagado + $this->input->post('pago')) >= $total){

            $data['total_pagado']    = $this->input->post('pago') + $total_pagado;
            $data['estado']    = 2;
            $data['credito'] = 0;//aqui me quede XD cambio 18-05-2022
            //$data['date'] = $hoy;
            //$data['day']= $day;
            //$data['month']= $month;
 
            $this->db->where('sales_id',$sales_id);
            $this->db->update('sales', $data);

        }else {
            $data['total_pagado']    = $this->input->post('pago') + $total_pagado;
            $this->db->where('sales_id',$sales_id);
            $this->db->update('sales', $data);
        }
        /*echo $total_pagado.'<br>'; 
        echo $total_pagado + $this->input->post('pago');*/
    }
    
    function add_pay_prov($code){
        log_message('error',$code);
        $shopping_id = $this->db->get_where('shopping', array('code'=>$code))->row()->shopping_id;
        log_message('error','$shopping_id'.$shopping_id);
        $total = $this->db->get_where('shopping', array('code'=>$code))->row()->total;
        $proveedores = $this->db->get_where('shopping', array('code'=>$code))->row()->provider;
        $prov = $this->db->get_where('provider', array('provider_id'=>$proveedores))->row()->name;
        $total_pagado = $this->db->get_where('shopping', array('code'=>$code))->row()->total_pagado;
        $datetime = date("Y-m-d H:i:s");
        $data_ct['responsable_id'] = $this->input->post('responsable');
        $data_ct['date']           = date("Y-m-d", strtotime($this->input->post('date')));
        $data_ct['sales_id']       = $shopping_id;
        $data_ct['amount']         = $this->input->post('pago');
        $data_ct['method']         = $this->input->post('metodo');
        $data_ct['type']         = 1;
        if ($data_ct['method'] != "Efectivo") {
            
            $data_ct['account_bank_id'] = $this->input->post('account_bank');
            $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $data_ct['account_bank_id']))->row_array();
            
            $data_bk['current_balance'] =  $cuenta['current_balance'] - $data_ct['amount'];
            $this->db->where('account_bank_id', $data_ct['account_bank_id']);
            $this->db->update('account_bank', $data_bk);
            
            $dat3['saldo_inicial'] = $cuenta['current_balance'];
            $dat3['amount'] = $data_ct['amount'];
            $saldo = $cuenta['current_balance'] - $data_ct['amount'];
            $dat3['nuevo_saldo']   = $saldo;
            $dat3['origin'] = $this->input->post('account_bank');
        }
        else{
            $data_ct['account_bank_id'] = $this->input->post('cash_id');
            
            $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $data_ct['account_bank_id']))->row_array();
            $dat3['saldo_inicial'] = $cuenta['current_balance'];
            $data_bk['current_balance'] = $cuenta['current_balance'] - $data_ct['amount'];
            $saldo = $cuenta['current_balance'] - $data_ct['amount'];
            $dat3['amount'] = $data_ct['amount'];
            $dat3['nuevo_saldo']   = $saldo;
            $this->db->where('account_bank_id', $cuenta['account_bank_id']);
            $this->db->update('account_bank', $data_bk);

            $dat3['origin'] = $this->input->post('cash_id');
        }
        $data_ct['notes']          = trim($this->input->post('notes'));
        $data_ct['datetime']       = $datetime;
        $data_ct['status']         = 1;

        $this->db->insert('credit_details', $data_ct);
        $message = 'Ha agregado un nuevo pago al crédito de la Compra con código: '.$code;
        $this->insert_binnacle($message);

            $dat3['provider']        = $prov;
            $dat3['date']            = date("Y-m-d", strtotime($this->input->post('date')));
            $dat3['responsable']     = $this->input->post('responsable');
            $dat3['details']         = trim($this->input->post('details').' '.$this->input->post('note'));
            $dat3['datetime']        = $datetime;
            $dat3['admin_id']        = $this->session->userdata('login_user_id');
            $dat3['branch_id']       = $this->session->userdata('branch_id');
            $dat3['week']            = date('W');
            $dat3['month']           = date('m');
            $dat3['year']            = date('Y');
            $dat3['reference_id']    = $this->input->post('code');
            $dat3['table_reference'] = "shopping";
            $dat3['details']         = trim($this->input->post('notes'));
            $dat3['status']       = 1;
            $md5 = str_replace(' ', '', date('Y m d H i s'));
            if($_FILES["factura_img"]["name"] != ''){
                    $dat3["factura_img"] = $md5.str_replace(' ', '', $_FILES["factura_img"]["name"]);
                    if ($_FILES["factura_img"]["type"]=='application/pdf') {
                        $dat3["factura_type"] = 'pdf';
                    }
                    else{
                        $dat3["factura_type"] = 'image';
                    }
                    move_uploaded_file($_FILES["factura_img"]["tmp_name"], 'uploads/vouchers/' . $md5.str_replace(" ", "", $_FILES["factura_img"]["name"]));
                }
            
            $this->db->insert('expense', $dat3);
            //$expense_id = $this->db->insert_id();
            
            //move_uploaded_file($_FILES["factura_img"]["tmp_name"], 'uploads/vouchers/' . $md5.str_replace(" ", "", $_FILES["factura_img"]["name"]));
        
        if(($total_pagado + $this->input->post('pago')) >= $total){
            $data['total_pagado']    = $this->input->post('pago') + $total_pagado;
            $data['credito'] = 0;//aqui me quede XD cambio 18-05-2022
            $this->db->where('shopping_id',$shopping_id);
            $this->db->update('shopping', $data);

        }else {
            $data['total_pagado']    = $this->input->post('pago') + $total_pagado;
    
            $this->db->where('shopping_id',$shopping_id);
            $this->db->update('shopping', $data);
        }
        /*echo $total_pagado.'<br>'; 
        echo $total_pagado + $this->input->post('pago');*/
    }

    function cancelar_pago($id_pago, $code)
    {
        $branch_id = $this->session->userdata('branch_id');
        $pago = $this->db->get_where('credit_details', array('credit_details_id'=>$id_pago))->row_array();
        $sale = $this->db->get_where('sales', array('sales_id'=>$pago['sales_id']))->row_array();

        $data_in['status'] = 0;
        $this->db->where('sale_ref', $code);
        $this->db->where('datetime', $pago['datetime']);
        $this->db->update('income', $data_in);

        if ($pago['account_bank_id'] == 0) {
            $cuenta = $this->db->get_where('account_bank', array('bank_id' => 0, 'status' => 1, 'branch_id'=>$branch_id))->row_array();
        }
        else{
            $cuenta = $this->db->get_where('account_bank', array('account_bank_id', $pago['account_bank_id']))->row_array();
        }

        $data_ct['current_balance'] = $cuenta['current_balance'] - $pago['amount'];
        $this->db->where('account_bank_id', $cuenta['account_bank_id']);
        $this->db->update('account_bank', $data_ct);

        $data_sl['total_pagado'] = $sale['total_pagado'] - $pago['amount'];
        if ($data_sl['total_pagado'] < $sale['total']) {
            $data_sl['estado'] = 1;
        }
        $this->db->where('sales_id', $sale['sales_id']);
        $this->db->update('sales', $data_sl);

        $data['status'] = 0;
        $this->db->where('credit_details_id', $id_pago);
        $this->db->update('credit_details', $data);
    }
    
    function cancelar_pago_prov($id_pago, $code)
    {
        $branch_id = $this->session->userdata('branch_id');
        $pago = $this->db->get_where('credit_details', array('credit_details_id'=>$id_pago))->row_array();
        $shopp = $this->db->get_where('shopping', array('shopping_id'=>$pago['sales_id']))->row_array();

        $data_in['status'] = 0;
        $this->db->where('reference_id', $code);
        $this->db->where('datetime', $pago['datetime']);
        $this->db->update('expense', $data_in);

        if ($pago['account_bank_id'] == 0) {
            $cuenta = $this->db->get_where('account_bank', array('bank_id' => 0, 'status' => 1, 'branch_id'=>$branch_id))->row_array();
        }
        else{
            $cuenta = $this->db->get_where('account_bank', array('account_bank_id', $pago['account_bank_id']))->row_array();
        }

        $data_ct['current_balance'] = $cuenta['current_balance'] + $pago['amount'];
        $this->db->where('account_bank_id', $cuenta['account_bank_id']);
        $this->db->update('account_bank', $data_ct);

        $data_sl['total_pagado'] = $shopp['total_pagado'] - $pago['amount'];
        if ($data_sl['total_pagado'] < $shopp['total']) {
            $data_sl['credito'] = 1;
        }
        $this->db->where('shopping_id', $shopp['shopping_id']);
        $this->db->update('shopping', $data_sl);

        $data['status'] = 0;
        $this->db->where('credit_details_id', $id_pago);
        $this->db->update('credit_details', $data);
    }

    function delete_sale($code){
        $data['estado']    = 3;
        $this->db->where('code', $code);
        $this->db->update('sales', $data);
        
        $data2['status']    = 0;
        $this->db->where('sale_ref', $code);
        $this->db->update('income', $data2);
        
        $data3['status']    = 0;
        $data3['estado']    = 3;
        $this->db->where('activity_ref', $code);
        $this->db->where('description','Venta');
        $this->db->update('product_details', $data);

        $message = 'Ha eliminado una venta con código: '.$code;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/ventas/'), 'ver_ventas', 'Venta_eliminada');
    }

    function sale_anulation()
    {
        $code = $this->input->post('sale_code');
        $data['estado']       = 3;
        $data['motivo']       = trim($this->input->post('reason'));
        $data['applied_date'] = date("Y-m-d H:i:s");
        $this->db->where('code', $code);
        $this->db->update('sales', $data);

        $dets = $this->db->get_where('product_details',array('activity_ref'=>$code,'estado'=>1))->result_array();
        /*foreach($dets as $row){
            $stock = $this->db->get_where('lotes', array('code' => $row['code'],'id_producto'=>$row['products_id']))->row()->existencia;
            $stock += $row['amount'];
            $this->db->where('code',$row['code']);
            $this->db->where('id_producto',$row['products_id']);
            $this->db->update('lotes',array('existencia'=>$stock));
        }*/

        $data2['status']    = 0;
        $data2['estado']    = 3;
        $this->db->where('activity_ref', $code);
        $this->db->update('product_details', $data2);

        $message = 'Ha anulado la venta con código: '.$code;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/detalles_venta/'.$code), 'ver_anulaciones', 'Anulacion');
    }

function new_change()
{
    $code = $this->input->post('sale_code');
    $data['estado']    = 4;
    $data['motivo']    = trim($this->input->post('reason'));

    $products = $this->input->post('product');
    $amount   = $this->input->post('amount');
    $price    = $this->input->post('price');
    $discount = $this->input->post('discount');
    $sub      = $this->input->post('sub');
    $change   = $this->input->post('change');
    
    $num_products = sizeof($products);

    $producto = array();
    
    for ($i=0; $i < $num_products ; $i++) { 

        if($change[$i] == 1)
        {
            $new_producto = array('product'=>$products[$i], 'amount'=>$amount[$i], 'price'=>$price[$i], 'discount'=>$discount[$i], 'sub'=>$sub[$i] );
            array_push($producto, $new_producto);

            $total += $sub[$i];

            $data2['date']         = date('Y-m-d');
            $data2['products_id']  = $products[$i];
            $data2['user_id']      = $this->session->userdata('login_user_id');
            $data2['branch_id']    = $this->session->userdata('branch_id');
            $data2['type']         = 0;
            $data2['amount']       = $amount[$i];
            $data2['price']        = $price[$i];
            $data2['description']  = 'Cambio';
            $data2['activity_ref'] = $code;
            $data2['status']       = 1;
            
            $this->db->insert('product_details', $data2); 
            
        }
        else
        {
            $data2['status'] = 0;
            $data2['estado'] = 2;
            $this->db->where('products_id', $products[$i]);
            $this->db->where('activity_ref', $code);
            $this->db->update('product_details', $data2);
        }

    }
    $data['total']        = $total;
    $data['num_products'] = $num_products;
    $data['products']     = json_encode($producto);
    $data['applied_date'] = date("Y-m-d H:i:s");

    $this->db->where('code', $code);
    $this->db->update('sales', $data);

    $dataChange['amount'] = $total;
            
    $this->db->where('sale_ref', $code);
    $this->db->update('income', $dataChange);

    $message = 'Se ha realizado un cambio:  '.$code;
    $this->insert_binnacle($message);

    $this->insert_notification($message, base64_encode('admin/detalles_venta/'.$code), 'ver_cambios', 'Cambio');
}

    
    function get_provider()
    {   
        return $this->db->order_by('name', 'ASC')->get_where('provider', array('status'=>1));
    }

    function get_branch()
    {   
        return $this->db->get_where('branch', array('status'=>1));
    }
    

    function create_provider(){
        
        $data['name']      = $this->input->post('name');
        $data['manager']   = $this->input->post('manager');
        $data['phone']     = $this->input->post('phone');
        $data['whatsapp']  = $this->input->post('whatsapp');
        $data['email']     = $this->input->post('email');
        $data['address']   = trim($this->input->post('address'));
        $data['nit']       = str_replace('-','', $this->input->post('nit'));
        $data['detail']    = $this->input->post('detail');
        $this->db->insert('provider', $data);

        $message = 'Ha creado un nuevo proveedor denominado '.$this->input->post('name');
        $this->insert_binnacle($message);

        $this->insert_notification($message, base64_encode('admin/proveedores/'), 'proveedores', 'Usuarios');
    }

    function update_provider($ID){
            
        $data['name']      = $this->input->post('name');
        $data['manager']   = $this->input->post('manager');
        $data['phone']     = $this->input->post('phone');
        $data['whatsapp']  = $this->input->post('whatsapp');
        $data['email']     = $this->input->post('email');
        $data['address']   = trim($this->input->post('address'));
        $data['nit']       = str_replace('-','', $this->input->post('nit'));
        $data['detail']    = $this->input->post('detail');
        $this->db->where('provider_id', $ID);
        $this->db->update('provider', $data);

        $message = 'Ha actualizado a un proveedor denominado '.$this->input->post('name');
        $this->insert_binnacle($message);
    }

    function delete_provider($ID){
        $data['status']    = 0;
        $this->db->where('provider_id', $ID);
        $this->db->update('provider', $data);

        $message = 'Ha eliminado a un proveedor denominado '.$this->db->get_where('provider', array('provider_id'=>$ID))->row()->name;
        $this->insert_binnacle($message);
    }
    
    function active_provider($ID){
        $data['status']    = 1;
        $this->db->where('provider_id', $ID);
        $this->db->update('provider', $data);

        $message = 'Ha re-activado a un proveedor denominado '.$this->db->get_where('provider', array('provider_id'=>$ID))->row()->name;
        $this->insert_binnacle($message);
    }


    function create_client(){
        
        $data['name']      = $this->input->post('name');
        $data['last_name'] = $this->input->post('last_name');
        $data['phone']     = $this->input->post('phone');
        $data['type']      = $this->input->post('type');
        $data['limite']    = $this->input->post('limite');
        $data['nit']       = $this->input->post('nit');
        $data['email']     = $this->input->post('email');
        $data['address']   = trim($this->input->post('address'));
        $data['branch_id'] = $this->input->post('branch');
        $data['responsable'] = $this->session->userdata('login_user_id');
        $data['date'] = date('Y-m-d H:i:s');
        $this->db->insert('client', $data);

        $message = 'Ha creado un nuevo cliente denominado '.$this->input->post('name');
        $this->insert_binnacle($message);

        $this->insert_notification($message, base64_encode('admin/clientes/'), 'clientes', 'Usuarios');
    }

    function update_client($ID){
            
        
        $data['name']      = $this->input->post('name');
        $data['last_name'] = $this->input->post('last_name');
        $data['phone']     = $this->input->post('phone');
        $data['type']      = $this->input->post('type');
        $data['limite']    = $this->input->post('limite');
        $data['nit']       = str_replace('-','', $this->input->post('nit'));
        $data['email']     = $this->input->post('email');
        $data['address']   = trim($this->input->post('address'));
        $data['branch_id'] = $this->input->post('branch');   
        $this->db->where('client_id', $ID);
        $this->db->update('client', $data);

        $message = 'Ha actualizado a un cliente denominado '.$this->input->post('name');
        $this->insert_binnacle($message);
    }

    function delete_client($ID){
        $data['status']    = 0;
        $this->db->where('client_id', $ID);
        $this->db->update('client', $data);

        $message = 'Ha eliminado a un cliente denominado '.$this->db->get_where('client', array('client_id'=>$ID))->row()->name;
        $this->insert_binnacle($message);
    }
    
    function active_client($ID){
        $data['status']    = 1;
        $this->db->where('client_id', $ID);
        $this->db->update('client', $data);

        $message = 'Ha re-activado a un cliente denominado '.$this->db->get_where('client', array('client_id'=>$ID))->row()->name;
        $this->insert_binnacle($message);
    }

    function create_expense(){
        $branch_id = $this->session->userdata('branch_id');
        $md5 = str_replace(' ', '', date('Y m d H i s'));

        $data['amount'] = $this->input->post('amount');

        $data['origin'] = $this->input->post('origin');
        if ($data['origin'] == 0) {
            $cuenta = $this->db->get_where('account_bank', array('bank_id' => $data['origin'],'branch_id' => $branch_id,'status'=> 1))->row_array();
        }
        else {
            $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $data['origin']))->row_array();
        }
        $data['saldo_inicial'] = $cuenta['current_balance'];
        $saldo = $cuenta['current_balance'] - $data['amount'];
        $data2['current_balance'] = $saldo;
        $data['nuevo_saldo'] = $saldo;
        $this->db->where('account_bank_id', $cuenta['account_bank_id']);
        $this->db->update('account_bank', $data2);

        if($_FILES["factura_img"]["name"] != ''){
            $data["factura_img"] = $md5.str_replace(' ', '', $_FILES["factura_img"]["name"]);
            if ($_FILES["factura_img"]["type"]=='application/pdf') {
                $data["factura_type"] = 'pdf';
            }
            else{
                $data["factura_type"] = 'image';
            }
        }

        $data['date']        = date("Y-m-d", strtotime($this->input->post('date')));
        $data['responsable'] = $this->input->post('responsable');
        $data['provider']    = $this->input->post('provider');
        $data['details']     = $this->input->post('details');
        $data['datetime']    = date('Y-m-d H:i:s');
        $data['admin_id']    = $this->session->userdata('login_user_id');
        $data['branch_id']   = $branch_id;
        $data['status']      = 1;
        $data['week']        = date('W');
        $data['month']       = date('m');
        $data['year']        = date('Y');
        $this->db->insert('expense', $data);
        $expense_id = $this->db->insert_id();
        move_uploaded_file($_FILES["factura_img"]["tmp_name"], 'uploads/vouchers/' . $md5.str_replace(" ", "", $_FILES["factura_img"]["name"]));

        $message    = 'Ha ingresado un nuevo gasto con id: '.$expense_id.' con concepto: '.$data['details'].' y con un total de: '.$data['amount'];
        $this->insert_binnacle($message);

        $this->insert_notification($message, base64_encode('admin/egresos/'), 'ver_egresos', 'Egreso');
    }

    function update_expense($ID)
    {
        $branch_id = $this->session->userdata('branch_id');
        $md5 = str_replace(' ', '', date('Y m d H i s'));

        if($_FILES["factura_img"]["name"] != ''){
            $img = $this->db->get_where('expense', array('expense_id' => $ID))->row()->factura_img;
            unlink('uploads/vouchers/' . $img);

            $data["factura_img"] = $md5.str_replace(' ', '', $_FILES["factura_img"]["name"]);
            if ($_FILES["factura_img"]["type"]=='application/pdf') {
                $data["factura_type"] = 'pdf';
            }
            else{
                $data["factura_type"] = 'image';
            }
        }

        $data['date'] = date("Y-m-d", strtotime($this->input->post('date')));
        $data['responsable'] = $this->input->post('responsable');
        $data['provider'] = $this->input->post('provider');
        $data['details'] = $this->input->post('details');

        if ($this->input->post('deactivate') != '1') {
            $message    = 'Ha editado un gasto con id: '.$ID.' y con fecha: '.date('Y/m/d H:i:s');
            $this->insert_binnacle($message);
            $this->insert_notification($message, base64_encode('admin/egresos/'), 'ver_egresos', 'Egreso_update');
        }
        elseif ($this->input->post('deactivate') == '1') {
            $data['status'] = 0;
            $data['reason'] = $this->input->post('reason');
            $message    = 'Ha anulado un gasto con id: '.$ID.', por concepto: '.$this->input->post('reason').' y con fecha: '.date('Y/m/d H:i:s');
            $this->insert_binnacle($message);
            $this->insert_notification($message, base64_encode('admin/egresos/'), 'ver_egresos', 'Egreso_deactivate');

            $datos = $this->db->get_where('expense', array('expense_id' => $ID))->row_array();

            if ($datos['origin'] == 0) {
                $cuenta = $this->db->get_where('account_bank', array('bank_id' => 0,'branch_id'=>$branch_id, 'status'=> 1))->row_array();
            }
            else {
                $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $datos['origin']))->row_array();
            }
            $nuevo_daldo = $cuenta['current_balance'] + $datos['amount'];
            $data2['current_balance'] = $nuevo_daldo;

            $this->db->where('account_bank_id', $cuenta['account_bank_id']);
            $this->db->update('account_bank', $data2);
        }

        $this->db->where('expense_id', $ID);
        $this->db->update('expense', $data);
        move_uploaded_file($_FILES["factura_img"]["tmp_name"], 'uploads/vouchers/' . $md5.str_replace(" ", "", $_FILES["factura_img"]["name"]));
    }

    function delete_expense($ID)
    {
        $branch_id = $this->session->userdata('branch_id');
        $data['status'] = 0;
        $data['reason'] = $this->input->post('reason');
        $message    = 'Ha anulado un gasto con id: '.$ID.' y con fecha: '.date('Y/m/d H:i:s');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/egresos/'), 'ver_egresos', 'Egreso_delete');

        $datos = $this->db->get_where('expense', array('expense_id' => $ID))->row_array();

        if ($datos['origin'] == 0) {
            $cuenta = $this->db->get_where('account_bank', array('bank_id' => 0,'branch_id' =>$branch_id, 'status'=> 1))->row_array();
        }
        else {
            $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $datos['origin']))->row_array();
        }
        $nuevo_daldo = $cuenta['current_balance'] + $datos['amount'];
        $data2['current_balance'] = $nuevo_daldo;

        $this->db->where('account_bank_id', $cuenta['account_bank_id']);
        $this->db->update('account_bank', $data2);

        $this->db->where('expense_id', $ID);
        $this->db->update('expense', $data);
    }

    function create_payroll(){
        $branch_id = $this->session->userdata('branch_id');
        $datetime = date('Y-m-d H:i:s');

        $planilla = $this->input->post('planilla');
        if ($planilla == 'mensual') {
            $data['type'] = 1;
        }
        elseif ($planilla == 'quincenal') {
            $data['type'] = 2;
        }

        $payroll_name = $this->input->post('payroll_name');
        $data['payroll_name'] = in_array($payroll_name, array('Oficial', 'Interna', 'Bono 14', 'Aguinaldo'), true) ? $payroll_name : 'Oficial';
        $data['date_start'] = date("Y-m-d", strtotime($this->input->post('date_start')));
        $data['date_end']   = date("Y-m-d", strtotime($this->input->post('date_end')));
        $data['month_pay']  = date("Y-m", strtotime($data['date_start']));

        $data['datetime'] = $datetime;
        $data['week']     = date('W');
        $data['month']    = date('m');
        $data['year']     = date('Y');

        $employee     = $this->input->post('employee');
        $discount     = $this->input->post('discount');
        $advance      = $this->input->post('advance');
        $other_discount = $this->input->post('other_discount');
        $remuneration = $this->input->post('remuneration');
        $salary_posted = $this->input->post('salary');
        $emp_date_start = $this->input->post('emp_date_start');
        $emp_date_end   = $this->input->post('emp_date_end');
        $note         = $this->input->post('note');
        $num_empleados = sizeof($employee);
        $sueldo  = array();
        $payroll_total = 0;
        $is_bonus = in_array($data['payroll_name'], array('Bono 14', 'Aguinaldo'), true);
        $has_other_discount = in_array($data['payroll_name'], array('Oficial', 'Interna'), true);
        $bonus_min_start = null;
        $bonus_max_end = null;
        for ($i=0; $i < $num_empleados ; $i++) {
                $employee_data = $this->db->select('salary, complemento')->get_where('admin', array('admin_id' => $employee[$i]))->row();
                if (!$employee_data) {
                    continue;
                }

                if ($is_bonus) {
                    $row_start = isset($emp_date_start[$i]) ? $emp_date_start[$i] : $data['date_start'];
                    $row_end   = isset($emp_date_end[$i]) ? $emp_date_end[$i] : $data['date_end'];
                    $salary_value         = $this->calculate_bonus_salary($employee_data->salary, $row_start, $row_end);
                    $discount_value       = max(0, (float) (isset($discount[$i]) ? $discount[$i] : 0));
                    $advance_value        = max(0, (float) (isset($advance[$i]) ? $advance[$i] : 0));
                    $other_discount_value = 0;
                    $remuneration_value   = max(0, (float) (isset($remuneration[$i]) ? $remuneration[$i] : 0));
                    if ($bonus_min_start === null || $row_start < $bonus_min_start) {
                        $bonus_min_start = $row_start;
                    }
                    if ($bonus_max_end === null || $row_end > $bonus_max_end) {
                        $bonus_max_end = $row_end;
                    }
                } else {
                    $row_start = $data['date_start'];
                    $row_end   = $data['date_end'];
                    $salary_value         = $this->calculate_proportional_salary($this->get_employee_base_salary($employee_data, $data['payroll_name']), $data['date_start'], $data['date_end']);
                    $discount_value       = max(0, (float) (isset($discount[$i]) ? $discount[$i] : 0));
                    $advance_value        = max(0, (float) (isset($advance[$i]) ? $advance[$i] : 0));
                    $other_discount_value = $has_other_discount ? max(0, (float) (isset($other_discount[$i]) ? $other_discount[$i] : 0)) : 0;
                    $remuneration_value   = max(0, (float) (isset($remuneration[$i]) ? $remuneration[$i] : 0));
                }
                $sub_value            = round($salary_value - $discount_value - $advance_value - $other_discount_value + $remuneration_value, 2);
                $payroll_total       += $sub_value;

                $new_sueldo = array(
                    'employee'       => $employee[$i],
                    'salary'         => $salary_value,
                    'discount'       => $discount_value,
                    'advance'        => $advance_value,
                    'other_discount' => $other_discount_value,
                    'remuneration'   => $remuneration_value,
                    'sub'            => $sub_value,
                    'note'           => isset($note[$i]) ? $note[$i] : '',
                    'date_start'     => $row_start,
                    'date_end'       => $row_end
                );
                array_push($sueldo, $new_sueldo);
        }

        if ($is_bonus && $bonus_min_start && $bonus_max_end) {
            $data['date_start'] = $bonus_min_start;
            $data['date_end']   = $bonus_max_end;
            $data['month_pay']  = date('Y-m', strtotime($bonus_min_start));
        }
        
        $data['num_employee'] = count($sueldo);
        $data['employee']     = json_encode($sueldo);
        $data['bank']         = $this->input->post('bank');
        $data['responsable']  = $this->input->post('responsable');
        $data['branch_id']    = $branch_id;
        $data['total']        = round($payroll_total, 2);
        $this->db->insert('payroll', $data);
        $payroll_id = $this->db->insert_id();
        $data3['reference_id'] = $payroll_id;
        $data3['table_reference'] = 'payroll';

        if ($data['bank'] == 0) {
            $cuenta = $this->db->get_where('account_bank',array('bank_id'=>$data['bank'],'branch_id'=>$branch_id,'status'=> 1))->row_array();
        }
        else {
            $cuenta = $this->db->get_where('account_bank',array('account_bank_id'=>$data['bank']))->row_array();
        }
        $data3['saldo_inicial']   = $cuenta['current_balance'];
        $saldo = $cuenta['current_balance'] - $data['total'];
        $data3['nuevo_saldo']     = $saldo;
        $data2['current_balance'] = $saldo;

        $this->db->where('account_bank_id', $cuenta['account_bank_id']);
        $this->db->update('account_bank', $data2);

        $data3['provider']    = "Planillas";
        $data3['date']        = date('Y-m-d');
        $data3['week']        = date('W');
        $data3['month']       = date('m');
        $data3['year']        = date('Y');
        $data3['amount']      = $data['total'];
        $data3['responsable'] = $this->input->post('responsable');
        $data3['origin']      = $this->input->post('bank');
        $data3['details']     = $is_bonus
            ? ("Pago de ".$data['payroll_name']." período ".$data['date_start']." a ".$data['date_end'])
            : ("Pago de planillas desde ".$this->input->post('date_start')." hasta ".$this->input->post('date_end'));
        $data3['datetime']    = $datetime;
        $data3['admin_id']    = $this->session->userdata('login_user_id');
        $data3['branch_id']   = $this->session->userdata('branch_id');
        $data3['status']      = 1;
        $this->db->insert('expense', $data3);
 
        $message = $is_bonus ? ('Ha creado un pago de '.$data['payroll_name']) : 'Ha creado un nuevo pago ';
        $this->insert_binnacle($message);
        
        $notify_path = $is_bonus ? 'admin/bonos/' : 'admin/planillas/';
        $this->insert_notification($message, base64_encode($notify_path), 'planillas', $is_bonus ? $data['payroll_name'] : 'Planilla');

        return $payroll_id;
    }

    function calculate_vacation_worked_days($date_start, $date_end)
    {
        try {
            $start = new DateTime($date_start);
            $end   = new DateTime($date_end);
        } catch (Exception $e) {
            return 0;
        }

        if ($end < $start) {
            return 0;
        }

        return (int) $start->diff($end)->days + 1;
    }

    function calculate_vacation_days($date_start, $date_end, $hiring_date = null)
    {
        $effective_start = $date_start;
        if (!empty($hiring_date)) {
            try {
                $hiring = new DateTime($hiring_date);
                $start  = new DateTime($date_start);
                if ($hiring > $start) {
                    $effective_start = $hiring->format('Y-m-d');
                }
            } catch (Exception $e) {
                // keep original start
            }
        }

        $worked_days = $this->calculate_vacation_worked_days($effective_start, $date_end);
        if ($worked_days <= 0) {
            return 0;
        }

        // Caso 1: días proporcionales = (días trabajados × 15) / 365
        return round(($worked_days * 15) / 365, 3);
    }

    function calculate_vacation_amount($monthly_salary, $vacation_days)
    {
        $monthly_salary = max(0, (float) $monthly_salary);
        $vacation_days  = max(0, (float) $vacation_days);
        if ($monthly_salary <= 0 || $vacation_days <= 0) {
            return 0;
        }

        // Caso 2: total = días acumulados × (salario ordinario mensual / 30)
        $daily = $monthly_salary / 30;
        return round($vacation_days * $daily, 2);
    }

    function create_vacation()
    {
        $employee_id = (int) $this->input->post('employee_id');
        $date_start  = date('Y-m-d', strtotime($this->input->post('date_start')));
        $date_end    = date('Y-m-d', strtotime($this->input->post('date_end')));
        $type        = $this->input->post('type');

        if (!in_array($type, array('Gozada', 'Pagada'), true)) {
            $type = 'Gozada';
        }

        $employee = $this->db->select('salary, hiring')->get_where('admin', array('admin_id' => $employee_id))->row();
        $hiring   = ($employee && !empty($employee->hiring)) ? date('Y-m-d', strtotime($employee->hiring)) : null;
        $salary   = $employee ? max(0, (float) $employee->salary) : 0;

        if ($hiring && $date_start < $hiring) {
            $date_start = $hiring;
        }

        $vacation_days = $this->calculate_vacation_days($date_start, $date_end, $hiring);
        $amount = ($type === 'Pagada')
            ? $this->calculate_vacation_amount($salary, $vacation_days)
            : 0;

        $data['employee_id'] = $employee_id;
        $data['date_start']  = $date_start;
        $data['date_end']    = $date_end;
        $data['days']        = $vacation_days;
        $data['type']        = $type;
        $data['amount']      = $amount;
        $data['note']        = trim((string) $this->input->post('note'));
        $data['responsable'] = (int) $this->input->post('responsable');
        $data['branch_id']   = $this->session->userdata('branch_id');
        $data['datetime']    = date('Y-m-d H:i:s');
        $data['status']      = 1;

        $this->db->insert('vacations', $data);

        $message = 'Ha registrado vacaciones '.$type.' para '.$this->getName('admin', $data['employee_id']);
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/vacaciones/'), 'vacaciones', 'Vacaciones');
    }

    function update_vacation($ID)
    {
        $employee_id = (int) $this->input->post('employee_id');
        $date_start  = date('Y-m-d', strtotime($this->input->post('date_start')));
        $date_end    = date('Y-m-d', strtotime($this->input->post('date_end')));
        $type        = $this->input->post('type');

        if (!in_array($type, array('Gozada', 'Pagada'), true)) {
            $type = 'Gozada';
        }

        $employee = $this->db->select('salary, hiring')->get_where('admin', array('admin_id' => $employee_id))->row();
        $hiring   = ($employee && !empty($employee->hiring)) ? date('Y-m-d', strtotime($employee->hiring)) : null;
        $salary   = $employee ? max(0, (float) $employee->salary) : 0;

        if ($hiring && $date_start < $hiring) {
            $date_start = $hiring;
        }

        $vacation_days = $this->calculate_vacation_days($date_start, $date_end, $hiring);
        $amount = ($type === 'Pagada')
            ? $this->calculate_vacation_amount($salary, $vacation_days)
            : 0;

        $data['employee_id'] = $employee_id;
        $data['date_start']  = $date_start;
        $data['date_end']    = $date_end;
        $data['days']        = $vacation_days;
        $data['type']        = $type;
        $data['amount']      = $amount;
        $data['note']        = trim((string) $this->input->post('note'));
        $data['responsable'] = (int) $this->input->post('responsable');

        $this->db->where('vacation_id', $ID);
        $this->db->update('vacations', $data);

        $message = 'Ha actualizado el registro de vacaciones #'.$ID;
        $this->insert_binnacle($message);
    }

    function delete_vacation($ID)
    {
        $data['status'] = 0;
        $this->db->where('vacation_id', $ID);
        $this->db->update('vacations', $data);

        $message = 'Ha anulado el registro de vacaciones #'.$ID;
        $this->insert_binnacle($message);
    }

    function active_vacation($ID)
    {
        $data['status'] = 1;
        $this->db->where('vacation_id', $ID);
        $this->db->update('vacations', $data);

        $message = 'Ha reactivado el registro de vacaciones #'.$ID;
        $this->insert_binnacle($message);
    }

    function update_payroll($ID, $param2)
    {
        $branch_id = $this->session->userdata('branch_id');
        $bank_id        = $this->db->get_where('payroll', array('payroll_id'=>$ID))->row()->bank;
        $total_anterior = $this->db->get_where('payroll', array('payroll_id'=>$ID))->row()->total;

        if ($bank_id == 0) {
            $cuenta = $this->db->get_where('account_bank',array('bank_id'=>$bank_id,'branch_id'=>$branch_id, 'status'=> 1))->row_array();
        }
        else {
            $cuenta = $this->db->get_where('account_bank',array('account_bank_id'=>$bank_id))->row_array();
        }

        $total = ($cuenta['current_balance'] + $total_anterior) - $this->input->post('ttl-');
        $data2['current_balance'] = $total;

        $this->db->where('account_bank_id', $cuenta['account_bank_id']);
        $this->db->update('account_bank', $data2);

        $employee     = $this->input->post('employee');
        $salary       = $this->input->post('salary');
        $discount     = $this->input->post('discount');
        $advance      = $this->input->post('advance');
        $other_discount = $this->input->post('other_discount');
        $remuneration = $this->input->post('remuneration');
        $note         = $this->input->post('note');
        $sub          = $this->input->post('sub');

        $num_empleados = sizeof($employee);
        
        $sueldo  = array();
        $payroll_name_post = $this->input->post('payroll_name');
        $has_other_discount = in_array($payroll_name_post, array('Oficial', 'Interna'), true);

        for ($i=0; $i < $num_empleados ; $i++) {
                $other_discount_value = $has_other_discount ? max(0, (float) (isset($other_discount[$i]) ? $other_discount[$i] : 0)) : 0;
                $new_sueldo = array(
                    'employee'       => $employee[$i],
                    'salary'         => $salary[$i],
                    'discount'       => $discount[$i],
                    'advance'        => $advance[$i],
                    'other_discount' => $other_discount_value,
                    'remuneration'   => $remuneration[$i],
                    'sub'            => $sub[$i],
                    'note'           => $note[$i]
                );
                array_push($sueldo, $new_sueldo);
        }
        
        $data['num_employee'] = $num_empleados;
        $data['bank']         = $this->input->post('bank');
        $data['employee']     = json_encode($sueldo);
        $data['responsable']  = $this->input->post('responsable');
        $data['total']        = $this->input->post('ttl-');
        $payroll_name         = $payroll_name_post;
        $data['payroll_name'] = in_array($payroll_name, array('Oficial', 'Interna', 'Bono 14', 'Aguinaldo'), true) ? $payroll_name : 'Oficial';

        $this->db->where('payroll_id', $ID);
        $this->db->update('payroll', $data);
        
        $data3['amount']      = $this->input->post('ttl-');
        $data3['responsable'] = $this->input->post('responsable');
        $data3['origin']      = $this->input->post('bank');
        $data3['details']     = "Pago de planillas desde ".$this->input->post('date_start')." hasta ".$this->input->post('date_end')." (Editado)";
        $this->db->where('reference_id', $ID);
        $this->db->where('table_reference', 'payroll');
        $this->db->update('expense', $data3);

        $message = 'Ha actualizado un nuevo pago ';
        $this->insert_binnacle($message);
    }
    
    function delete_payroll($ID)
    {
        $branch_id = $this->session->userdata('branch_id');
        $bank_id        = $this->db->get_where('payroll', array('payroll_id'=>$ID))->row()->bank;
        $total_anterior = $this->db->get_where('payroll', array('payroll_id'=>$ID))->row()->total;
        if ($bank_id == 0) {
            $bank_balance   = $this->db->get_where('account_bank', array('bank_id'=>$bank_id,'branch_id'=>$branch_id, 'status'=> 1))->row()->current_balance; 
        }
        else {
            $bank_balance   = $this->db->get_where('account_bank', array('account_bank_id'=>$bank_id))->row()->current_balance; 
        }
        $total = $bank_balance + $total_anterior;
        $data2['current_balance'] = $total;
        if ($bank_id == 0) {
            $this->db->where('bank_id', $bank_id);
        }
        else {
            $this->db->where('account_bank_id', $bank_id);
        }
        $this->db->update('account_bank', $data2);

        $data['status']    = 0;
        $this->db->where('payroll_id', $ID);
        $this->db->update('payroll', $data);

        $data3['status']   = 0;
        $data3['reason']   = "Anulación de planilla";
        $this->db->where('reference_id', $ID);
        $this->db->where('table_reference', 'payroll');
        $this->db->update('expense', $data3);

        $message = 'Ha eliminado una planilla con ID: '.$ID;
        $this->insert_binnacle($message);
    }
    
    function active_payroll($ID)
    {
        $branch_id = $this->session->userdata('branch_id');
        $bank_id        = $this->db->get_where('payroll', array('payroll_id'=>$ID))->row()->bank;
        $total_anterior = $this->db->get_where('payroll', array('payroll_id'=>$ID))->row()->total;
        if ($bank_id == 0) {
            $bank_balance   = $this->db->get_where('account_bank', array('bank_id'=>$bank_id,'branch_id'=>$branch_id,'status' =>1))->row()->current_balance; 
        }
        else {
            $bank_balance   = $this->db->get_where('account_bank', array('account_bank_id'=>$bank_id))->row()->current_balance; 
        }
        $total = $bank_balance - $total_anterior;
        $data2['current_balance'] = $total;
        if ($bank_id == 0) {
            $this->db->where('bank_id', $bank_id);
        }
        else{
            $this->db->where('account_bank_id', $bank_id);
        }
        $this->db->update('account_bank', $data2);

        $data['status']    = 1;
        $this->db->where('payroll_id', $ID);
        $this->db->update('payroll', $data);
        
        $data3['status']   = 1;
        $this->db->where('reference_id', $ID);
        $this->db->where('table_reference', 'payroll');
        $this->db->update('expense', $data3);

        $message = 'Ha re-activado una planilla con ID '.$ID;
        $this->insert_binnacle($message);
    }

    function create_event(){
        
        $data['date_start']  = date("Y-m-d", strtotime($this->input->post('date_start')));
        $data['date_end']    = date("Y-m-d", strtotime($this->input->post('date_end')));
        $data['time_start']  = $this->input->post('time_start');
        $data['time_end']    = $this->input->post('time_end');
        $data['title']       = $this->input->post('title');
        $data['description'] = trim($this->input->post('description'));
        $data['color']       = $this->input->post('color');
        $data['branch_id']   =  $this->session->userdata('branch_id');
        $data['view_all']    = $this->input->post('view_all');
        $this->db->insert('events', $data);

        $message = 'Ha creado un nuevo evento con el título'.$this->input->post('title');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/calendario/'), 'calendario', 'Evento');
    }

    function update_event($ID){
        
        $data['date_start']  = date("Y-m-d", strtotime($this->input->post('date_start')));
        $data['date_end']    = date("Y-m-d", strtotime($this->input->post('date_end')));
        $data['time_start']  = $this->input->post('time_start');
        $data['time_end']    = $this->input->post('time_end');
        $data['title']       = $this->input->post('title');
        $data['description'] = trim($this->input->post('description')); 
        $data['color']       = $this->input->post('color');
        $eliminar            = $this->input->post('delete');

        $accion = '';

        if ($eliminar == '1') {
            $this->db->where('events_id', $ID);
            $this->db->delete('events');

            $message = 'Ha eliminado un evento con el título'.$this->input->post('title');
            $this->insert_binnacle($message);
            $this->insert_notification($message, base64_encode('admin/calendario/'), 'calendario', 'Evento_eliminado');
            $accion = 'delete';
        }
        else {
            $this->db->where('events_id', $ID);
            $this->db->update('events',  $data);

            $message = 'Ha actualizado un evento con el título'.$this->input->post('title');
            $this->insert_binnacle($message);
            $this->insert_notification($message, base64_encode('admin/calendario/'), 'calendario', 'Evento_update');
            $accion = 'update';
        }

        return $accion;

    }

    function update_event2($ID){
        
        $data['date_start']  = date("Y-m-d", strtotime($this->input->post('start')));
        $data['date_end']    = date("Y-m-d", strtotime($this->input->post('end')));
        $this->db->where('events_id', $ID);
        $this->db->update('events',  $data); 
    }

    function delete_event($ID){
        $data['status']    = 0;
        $this->db->where('client_id', $ID);
        $this->db->update('client', $data);

        $message = 'Ha eliminado a un cliente denominado '.$this->db->get_where('client', array('client_id'=>$ID))->row()->name;
        $this->insert_binnacle($message);
    }
    
    function active_event($ID){
        $data['status']    = 1;
        $this->db->where('client_id', $ID);
        $this->db->update('client', $data);

        $message = 'Ha re-activado a un cliente denominado '.$this->db->get_where('client', array('client_id'=>$ID))->row()->name;
        $this->insert_binnacle($message);
    }

    
    function get_mark()
    {   
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->order_by('name','ASC')->get_where('mark', array('status'=>1));
    }
    

    function create_mark(){
        
        $data['name']        = $this->input->post('name');
        $data['description'] = trim($this->input->post('description'));
        $data['branch_id']   =  $this->session->userdata('branch_id');
        $this->db->insert('mark', $data);

        $message = 'Ha creado una marca llamada: '.$this->input->post('name');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/marcas/'), 'marcas', 'Marcas');
    }

    function update_mark($ID){
        
        $data['name']        = $this->input->post('name');
        $data['description'] = trim($this->input->post('description')); 
        $this->db->where('mark_id', $ID);
        $this->db->update('mark',  $data);
        $message = 'Ha actualizado la marca: '.$this->input->post('name');
        $this->insert_binnacle($message);
    }

    function delete_mark($ID){
        $data['status'] = 0;
        $this->db->where('mark_id', $ID);
        $this->db->update('mark', $data);
        $message = 'Ha eliminado la marca:'.$this->db->get_where('mark', array('mark_id'=>$ID))->row()->name;
        $this->insert_binnacle($message);
    }

    function get_cotizaciones()
    {   
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->order_by('quotes_id', 'DESC')->get_where('quotes', array('status'=>1, 'branch_id'=>$branch_id));
    }

    function create_quote(){
        $new_cliend_id = 0;
        if($this->input->post('new-client') == '1'){
            $name = explode(',',$this->input->post('c_name'));

            if(count($name) ==1){

                $dat2['name']      = $name[0];

            }elseif(count($name) == 2){
                
                $dat2['name']      = $name[0];
                $dat2['last_name'] = $name[1];
            }

            // $dat2['phone']     = $this->input->post('c_phone');
            $dat2['type']      = 2;
            $dat2['limite']    = 100;
            $dat2['nit']       = $this->input->post('nit');
            $dat2['date'] = date('Y-m-d H:i:s');
            $dat2['address']   = 'Ciudad';
            $dat2['branch_id'] = $this->session->userdata('branch_id');
            $dat2['responsable'] = $this->session->userdata('login_user_id');
            $this->db->insert('client', $dat2);
            $new_cliend_id = $this->db->insert_id();

            $message = 'Ha creado un nuevo cliente denominado '.$this->input->post('name');
            $this->insert_binnacle($message);
    
            $this->insert_notification($message, base64_encode('admin/clientes/'), 'clientes', 'Usuarios');
        }


        if($this->input->post('client_id') != 0){
            $data['client_id'] = $this->input->post('client_id');
        }else{
            $data['client_id'] = $new_cliend_id; 
        }

        $dat2['nit']         = $this->input->post('nit');
        $dat2['name']        = $this->input->post('c_name');
        $dat2['phone']       = $this->input->post('c_phone');
        $dat2['email']       = $this->input->post('c_email');
        $data['date_end']    = date("Y-m-d", strtotime($this->input->post('date_end')));
        $data['branch_id']   = $this->session->userdata('branch_id');
        $data['discount']    = $this->input->post('dsc');
        $data['total']       = $this->input->post('ttl');
        $data['responsable'] = $this->input->post('responsable');
        $data['code']        = $this->input->post('code');

        $products    = $this->input->post('product');
        $amount      = $this->input->post('amount');
        $price       = $this->input->post('price');
        $cost        = $this->input->post('cost');
        $price_my    = $this->input->post('price_my');
        $price_farma = $this->input->post('price_farma');
        $discount    = $this->input->post('discount');
        $sub         = $this->input->post('sub');
        $sub_my      = $this->input->post('sub_my');
        $sub_farma   = $this->input->post('sub_farma');
        
        // log_message("error", "Farma: ".sizeof($price_farma).", Sub farma: ".sizeof($sub_farma));
        // log_message("error", "Total: ".$this->input->post('ttl'));

        $num_products = sizeof($products);
        $quote =  array();

        for ($i=0; $i < $num_products; $i++) { 
            $new_quote = array('product'=>$products[$i], 'amount'=>$amount[$i], 'cost'=>$cost[$i], 'price'=>$price[$i], 'price_my'=>$price_my[$i], 'price_farma'=>$price_farma[$i], 'discount'=>$discount[$i], 'sub'=>$sub[$i], 'sub_my'=>$sub_my[$i], 'sub_farma'=>$sub_farma[$i]);
            array_push($quote, $new_quote);
        }

        $data['products'] = json_encode($quote);
        $data['num_products'] = $num_products;
        
        $this->db->insert('quotes', $data);
        $cot_id = $this->db->insert_id();

        $message = 'Ha creado una cotización que termina: '.$this->input->post('date_end').' con código: '.$this->input->post('code');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/detalles_cotizacion/'.$cot_id), 'ver_cotizaciones', 'Cotizacion');

    }

    function update_quote($ID){

        $data['date_end']    = date("Y-m-d", strtotime($this->input->post('date_end')));
        $data['discount']    = $this->input->post('dsc');
        $data['total']       = $this->input->post('ttl');

        $productos   = $this->input->post('productos');
        $amount      = $this->input->post('amount');
        $cost        = $this->input->post('cost');
        $price       = $this->input->post('price');
        $price_my    = $this->input->post('price_my');
        $price_farma = $this->input->post('price_farma');
        $discount    = $this->input->post('discount');
        $sub         = $this->input->post('sub');
        $sub_my      = $this->input->post('sub_my');
        $sub_farma   = $this->input->post('sub_farma');

        $num_products = sizeof($productos);
        $quote =  array();

        for ($i=0; $i < $num_products ; $i++) { 
            $new_quote = array('product'=>$productos[$i], 'amount'=>$amount[$i], 'cost'=>$cost[$i], 'price'=>$price[$i], 'price_my'=>$price_my[$i], 'price_farma'=>$price_farma[$i], 'discount'=>$discount[$i], 'sub'=>$sub[$i], 'sub_my'=>$sub_my[$i], 'sub_farma'=>$sub_farma[$i]);
            array_push($quote, $new_quote);
        }

        $data['products'] = json_encode($quote);
        $data['num_products'] = $num_products;

        $this->db->where('quotes_id', $ID);
        $this->db->update('quotes',  $data);
        
        $message = 'Ha actualizado una cotización que termina: '.$this->input->post('date').' con código:'.$this->input->post('code');
        $this->insert_binnacle($message);
    }

    function delete_quote($ID){
        $data['status'] = 0;
        $this->db->where('quotes_id', $ID);
        $this->db->update('quotes', $data);
        $message = 'Ha eliminado la cotizacion con ID:'.$ID;
        $this->insert_binnacle($message);
    }

    function get_products($branch_id ='')
    {   
         $data = $this->db->order_by('products_id','DESC')->select('products_id,name,code')->get_where('products', array('status'=>1));
        return $data;
        
    }

    function get_product_details($products_id)
    {   
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->get_where('product_details', array('status'=>1, 'products_id'=>$products_id));
    }

    function get_transfer()
    {   
        $this->db->where('branch_id', $this->session->userdata('branch_id'));
        $this->db->order_by('date','DESC');
        $this->db->order_by('product_details_id','DESC');
        return $this->db->get_where('product_details', array('description LIKE "%Traslado%"'=>null, 'status'=>1));
    }
    
    function get_transfer2()
    {   
        /* $this->db->group_start();
        $this->db->where('Desde', $this->session->userdata('branch_id'));
        $this->db->or_where('Hacia', $this->session->userdata('branch_id'));
        $this->db->group_end(); */
        $branch_id = $this->input->post('id_branch');
        $initial = $this->input->post('initial');
        $final   = $this->input->post('final');
        log_message("error", "Branch id: $branch_id, Initial: $initial, Final: $final");
        if ($initial == '') $initial = date("Y-m-01");
        if ($final == '') $final = date("Y-m-t");
        log_message("error", "Branch id: $branch_id, Initial: $initial, Final: $final");
        if ($branch_id != '') $this->db->where('Hacia', $branch_id);
        $this->db->where("DATE(date) >= DATE('$initial')", NULL, FALSE);
        $this->db->where("DATE(date) <= DATE('$final')", NULL, FALSE);
        $this->db->order_by('date','DESC');
        $this->db->order_by('id_traslado','DESC');
        return $this->db->get_where('traslado', array('status'=>1));
    }
    
    function get_transfer3($initial,$final)
    {   
        /* $this->db->group_start();
        $this->db->where('Desde', $this->session->userdata('branch_id'));
        $this->db->or_where('Hacia', $this->session->userdata('branch_id'));
        $this->db->group_end(); */
        $branch_id = $this->session->userdata('branch_id');
        //$initial = $this->input->post('initial');
        //$final   = $this->input->post('final');
        log_message("error", "Branch id: $branch_id, Initial: $initial, Final: $final");
        if ($initial == '') $initial = date("Y-m-01");
        if ($final == '') $final = date("Y-m-t");
        log_message("error", "Branch id: $branch_id, Initial: $initial, Final: $final");
        if ($branch_id != '') $this->db->where('Hacia', $branch_id);
        $this->db->where("DATE(date) >= DATE('$initial')", NULL, FALSE);
        $this->db->where("DATE(date) <= DATE('$final')", NULL, FALSE);
        $this->db->order_by('date','DESC');
        $this->db->order_by('id_traslado','DESC');
        return $this->db->get_where('traslado', array('status'=>1));
    }

    function get_product_changes($products_id)
    {   
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->get_where('product_details', array('estado'=>2, 'products_id'=>$products_id));
    }

    function get_product_overrides($products_id)
    {   
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->get_where('product_details', array('estado'=>3, 'products_id'=>$products_id));
    }


    function get_stock($product_id,$branch_id)
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
            return $total;
        else
            return 0;
    }
    
    
    function get_stock_inventario($product_id,$branch_id)
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
            return $total;
        else
            return 0;
    }

    function last_price($product_id, $branch_id = '')
    {
        /*if ($branch_id == '') {
            $this->db->select('price');
            $this->db->where('products_id',$product_id);
            $this->db->group_start();
            $this->db->where('type',1);
            $this->db->or_where('type',2);
            $this->db->group_end();
            $this->db->where('status',1);
            $this->db->order_by('product_details_id', 'DESC');
            $this->db->limit(1);
            return $this->db->get('product_details')->row()->price;
        }
        else {
            $this->db->select('price');
            $this->db->where('products_id',$product_id);
            $this->db->where('branch_id',$branch_id);
            $this->db->group_start();
            $this->db->where('type',1);
            $this->db->or_where('type',2);
            $this->db->group_end();
            $this->db->where('status',1);
            $this->db->order_by('product_details_id', 'DESC');
            $this->db->limit(1);
            return $this->db->get('product_details')->row()->price;
        }*/
        /* if ($branch_id == '') {
            $this->db->select('price');
            $this->db->where('products_id',$product_id);
            $this->db->where('status',1);
            $this->db->order_by('products_id', 'DESC');
            $this->db->limit(1);
            return $this->db->get('products')->row()->price;
        }
        else {
            $this->db->select('price');
            $this->db->where('products_id',$product_id);
            $this->db->where('branch_id',$branch_id);
            $this->db->where('status',1);
            $this->db->order_by('products_id', 'DESC');
            $this->db->limit(1);
            return $this->db->get('products')->row()->price;
        } */
        $this->db->select('price');
        $this->db->where('products_id',$product_id);
        $this->db->where('status',1);
        $this->db->order_by('products_id', 'DESC');
        $this->db->limit(1);
        return $this->db->get('products')->row()->price;
    }
    
    function last_price_buy($product_id)
    {
            $this->db->select('price');
            $this->db->where('products_id',$product_id);
            return $this->db->get('products')->row()->price;
    }
    function last_price_buy_farma($product_id)
    {
            $this->db->select('farma');
            $this->db->where('products_id',$product_id);
            return $this->db->get('products')->row()->farma;
    }
    function last_price_buy_my($product_id)
    {
            $this->db->select('precio_mayorista');
            $this->db->where('products_id',$product_id);
            return $this->db->get('products')->row()->precio_mayorista;
    }
    
    function last_cost($product_id, $branch_id = '')
    {
        /*if ($branch_id == '') {
            $this->db->select('cost');
            $this->db->where('products_id',$product_id);
            $this->db->group_start();
            $this->db->where('type',1);
            $this->db->or_where('type',2);
            $this->db->group_end();
            $this->db->where('status',1);
            $this->db->order_by('product_details_id', 'DESC');
            $this->db->limit(1);
            return $this->db->get('product_details')->row()->cost;
        }
        else {
            $this->db->select('cost');
            $this->db->where('products_id',$product_id);
            $this->db->where('branch_id',$branch_id);
            $this->db->group_start();
            $this->db->where('type',1);
            $this->db->or_where('type',2);
            $this->db->group_end();
            $this->db->where('status',1);
            $this->db->order_by('product_details_id', 'DESC');
            $this->db->limit(1);
            return $this->db->get('product_details')->row()->cost;
        }*/
        /* if ($branch_id == '') {
            $this->db->select('cost');
            $this->db->where('products_id',$product_id);
            $this->db->where('status',1);
            $this->db->order_by('products_id', 'DESC');
            $this->db->limit(1);
            return $this->db->get('products')->row()->cost;
        }
        else {
            $this->db->select('cost');
            $this->db->where('products_id',$product_id);
            $this->db->where('branch_id',$branch_id);
            $this->db->where('status',1);
            $this->db->order_by('products_id', 'DESC');
            $this->db->limit(1);
            return $this->db->get('products')->row()->cost;
        } */
        $this->db->select('cost');
        $this->db->where('products_id',$product_id);
        $this->db->where('status',1);
        $this->db->order_by('products_id', 'DESC');
        $this->db->limit(1);
        return $this->db->get('products')->row()->cost;
    }
    
    function primerDiaSemana($year, $month, $day){
        // Número de la semana
        $semana=date("W",mktime(0,0,0,$month,$day,$year));
        // Día de la semana de la fecha dada
        $diaSemana=date("w",mktime(0,0,0,$month,$day,$year));
        // 0 es el domingo
        if($diaSemana==0){ $diaSemana=7; }
        // A la fecha recibida, le restamos el dia de la semana y obtendremos el lunes
        $primerDia= date("d/m",mktime(0,0,0,$month,$day-$diaSemana+1,$year));
        return $primerDia;
    }

    public function ultimoDiaSemana($year, $month, $day){
        // Número de la semana
        $semana=date("W",mktime(0,0,0,$month,$day,$year));
        // Día de la semana de la fecha dada 
        $diaSemana=date("w",mktime(0,0,0,$month,$day,$year));
        // 0 es domingo
        if($diaSemana==0){ $diaSemana=7; }
        // A la fecha recibida, le sumamos el dia de la semana menos siete y obtendremos el domingo
        $ultimoDia=date("d/m",mktime(0,0,0,$month,$day+(7-$diaSemana),$year));
        
        return $ultimoDia;
    }

    function last_price_my($product_id, $branch_id = '')
    {
        if ($branch_id == '') 
        {
            $this->db->select('price');
            $this->db->where('products_id',$product_id);
            $this->db->group_start();
            $this->db->where('type',1);
            $this->db->or_where('type',2);
            $this->db->group_end();
            $this->db->where('status',1);
            $this->db->order_by('product_details_id', 'DESC');
            $this->db->limit(1);
            $price = $this->db->get('product_details')->row()->price;
            
            $mayorista = $this->db->get_where('settings', array('type'=>'mayorista'))->row()->description/100 ;
            $total = $price-($price * $mayorista);
            return $total;
        }
        else 
        {
            $this->db->select('price');
            $this->db->where('products_id',$product_id);
            $this->db->where('branch_id',$branch_id);
            $this->db->group_start();
            $this->db->where('type',1);
            $this->db->or_where('type',2);
            $this->db->group_end();
            $this->db->where('status',1);
            $this->db->order_by('product_details_id', 'DESC');
            $this->db->limit(1);
            $price = $this->db->get('product_details')->row()->price;
            
            $mayorista = $this->db->get_where('settings', array('type'=>'mayorista'))->row()->description/100 ;
            $total = $price-($price * $mayorista);
            return $total;
        }
    }


    function create_product()
    {
        $codigo = $this->input->post('code');
        
        if($_FILES['img']['size'] > 0)
        {
                
            $data['img'] = $codigo.'.jpg';

            move_uploaded_file($_FILES["img"]["tmp_name"], "uploads/productos/".$codigo.".jpg");
        }

        if ($this->input->post('branch') == 'Nuevo') 
        {
            $branch['name']    = $this->input->post('new_branch_name');
            $branch['manager'] = $this->session->userdata('login_user_id');
            $branch['tel']     = $this->input->post('new_branch_phone');    
            $branch['address'] = $this->input->post('new_branch_address');    
            $this->db->insert('branch', $branch);
            $branch_id = $this->db->insert_id();
            $data['branch_id'] = $branch_id; 

            $message = 'Ha ingresado una nueva sucursal denominada '.$this->input->post('new_branch_name');
            $this->insert_binnacle($message);

            $data['stock_inventory'] = $this->input->post('stock');
            $data['stock_bodega']    = 0;

        }
        
        elseif($this->input->post('branch') == 0)
        {
            $branch_id               = $this->input->post('branch');
            $data['branch_id']       = $branch_id; 
            $data['stock_inventory'] = 0;
            $data['stock_bodega']    = $this->input->post('stock');

        }
        
        elseif($this->input->post('branch') >= 1)
        {
            $branch_id               = $this->input->post('branch');
            $data['branch_id']       = $this->input->post('branch'); 
            $data['stock_inventory'] = $this->input->post('stock');
            $data['stock_bodega']    = 0;
        }

        if (is_numeric($this->input->post('mark'))) 
        {
            $data['mark'] = $this->input->post('mark');
        }
        
        else
        {
            $mark['name']      = $this->input->post('mark');
            $mark['branch_id'] = $this->session->userdata('branch_id');
            $mark['status']    = 1;
            $this->db->insert('mark', $mark);
            $data['mark'] = $this->db->insert_id();
        }

        if ($this->input->post('provider') == 'Nuevo') 
        {
            $provider['name']      = $this->input->post('new_provider_name');
            $provider['manager']   = $this->input->post('new_provider_encargado');
            $provider['phone']     = $this->input->post('new_provider_phone');
            $provider['whatsapp']  = $this->input->post('new_provider_whatsapp');
            $provider['email']     = $this->input->post('new_provider_email');
            $this->db->insert('provider', $provider);
            $data['provider'] = $this->db->insert_id();

            $message = 'Ha creado un nuevo proveedor denominado '.$this->input->post('new_provider_name');
            $this->insert_binnacle($message);
    
            $this->insert_notification($message, base64_encode('admin/proveedores/'), 'proveedores', 'Usuarios');
        }
        
        else
        {
            $data['provider'] = $this->input->post('provider');
        }

        if (is_numeric($this->input->post('category'))) 
        {
            $data['category'] = $this->input->post('category');
        }
        
        else
        {
            $category['name']      = $this->input->post('category');
            $category['branch_id'] = $this->session->userdata('branch_id');
            $category['status']    = 1;
            $this->db->insert('categories', $category);
            $data['category'] = $this->db->insert_id();
        }

        if (is_numeric($this->input->post('type_product_id'))) 
        {
            $data['type_product_id'] = $this->input->post('type_product_id');
        }
        
        else
        {
            $tipo_p['name']      = $this->input->post('type_product_id');
            $tipo_p['branch_id'] = $this->session->userdata('branch_id');
            $tipo_p['status']    = 1;
            $this->db->insert('type_product', $tipo_p);
            $data['type_product_id'] = $this->db->insert_id();
        }

        $data['name']               = $this->input->post('name');
        $data['code']               = $codigo;
        $data['alert']              = $this->input->post('alert');
        $data['presentation']       = $this->input->post('selected_caja');
        $data['weight']             = $this->input->post('weight');
        $data['farma']              = $this->input->post('farma');
        $data['precio_mayorista']   = $this->input->post('may');
        $data['old_may']            = $this->input->post('old_may');
        $data['unit']               = $this->input->post('unit');
        $data['description']        = trim($this->input->post('description'));
        $data['price']              = $this->input->post('price');
        $data['old_price']          = $this->input->post('old_price');
        $data['cost']               = $this->input->post('cost');
        $data['costo_iva']          = $this->input->post('costo_iva');
        $data['gain']               = $this->input->post('totalGanancia');
        $data['corridor']           = $this->input->post('corridor');
        $data['pallet']             = $this->input->post('pallet');
        $data['shelf']              = $this->input->post('shelf');
        $data['level']              = $this->input->post('level');
        if($this->input->post('selected_caja')=='Caja'){
            $data['id_prod_matriz']     = $this->db->get_where('products',array('code'=>$this->input->post('prod_matriz'), 'status'=>1))->row()->products_id;
        $data['prod_matriz']        = $this->input->post('prod_matriz');
        $data['cnt_prod_matriz']    = $this->input->post('cnt_prod_matriz');
        }
        $data['class_product_id'] = $this->input->post('class_product_id');
        
        $data['iva']    = $this->input->post('iva_check');
        $this->db->insert('products', $data); 
        $product_id = $this->db->insert_id();
        
        $message    = 'Ha creado un producto llamado: '.$this->input->post('name');
        $this->insert_binnacle($message);

        $dat2['products_id'] = $product_id;
        $dat2['user_id']     = $this->session->userdata('login_user_id');
        $dat2['branch_id']   = $branch_id;
        $dat2['type']        = 1;
        $dat2['provider']    = $this->input->post('provider');
        $dat2['amount']      = $this->input->post('stock');
        $dat2['price']       = $this->input->post('price');
        $dat2['cost']        = $this->input->post('cost');
        $dat2['description'] = $this->input->post('description');
        $dat2['expiration']  = date("Y-m-d", strtotime($this->input->post('expiration')));
        $dat2['date']        = date('Y-m-d');
        $dat2['iva']         = $this->input->post('iva_check');
        
        $this->db->insert('product_details', $dat2); 
        
                $dat_lote['code']       = 'Inicial';
                $dat_lote['id_producto']= $product_id;
                $dat_lote['fecha']      = date('Y-m-d');
                $dat_lote['fecha_vencimiento']= date("Y-m-d", strtotime($this->input->post('expiration')));
                $dat_lote['transaccion']= 'Compra';
                $dat_lote['cantidad']   = $this->input->post('stock');
                $dat_lote['existencia'] = $this->input->post('stock');
                $dat_lote['precio']     = $this->input->post('cost');
                $dat_lote['branch_id']  = $branch_id;
                $this->db->insert('lotes', $dat_lote); 
                
        $name    = $this->db->get_where('products',array('products_id'=>$product_id))->row()->name;
        $message = 'Ha agregado un nuevo lote de '.$name;
        $this->insert_binnacle($message);

        $this->insert_notification($message, base64_encode('admin/producto_detalle/'.$product_id), 'inventario', 'Inventario');
    }

    function update_product($ID)
    {
        log_message("error",'entro a editar');
        $codigo = $this->db->get_where('products', array('products_id'=>$ID))->row()->code;
        
        if($_FILES['img']['size'] > 0){
                
            $data['img'] = $codigo.'.jpg';

            move_uploaded_file($_FILES["img"]["tmp_name"], "uploads/productos/".$codigo.".jpg");
        }

        if (is_numeric($this->input->post('mark'))) {
            $data['mark'] = $this->input->post('mark');
        }
        else{
            $mark['name']      = $this->input->post('mark');
            $mark['branch_id'] = $this->session->userdata('branch_id');
            $mark['status']    = 1;
            $this->db->insert('mark', $mark);
            $data['mark'] = $this->db->insert_id();
        }

        if (is_numeric($this->input->post('category'))) {
            $data['category'] = $this->input->post('category');
        }
        else{
            $category['name']      = $this->input->post('category');
            $category['branch_id'] = $this->session->userdata('branch_id');
            $category['status']    = 1;
            $this->db->insert('categories', $category);
            $data['category'] = $this->db->insert_id();
        }

        if (is_numeric($this->input->post('type_product_id'))) {
            $data['type_product_id'] = $this->input->post('type_product_id');
        }
        else{
            $tipo_p['name']      = $this->input->post('type_product_id');
            $tipo_p['branch_id'] = $this->session->userdata('branch_id');
            $tipo_p['status']    = 1;
            $this->db->insert('type_product', $tipo_p);
            $data['type_product_id'] = $this->db->insert_id();
        }
        
        $data['class_product_id'] = $this->input->post('class_product_id');
        $data['type_product_id'] = $this->input->post('type_product_id');
        $data['name']               = $this->input->post('name');
        $data['code']               = $this->input->post('code');
        $data['precio_mayorista']   = $this->input->post('may');
        $data['provider']           = $this->input->post('provider');
        $data['price']              = $this->input->post('price');
        $data['farma']              = $this->input->post('farma');
        $data['cost']               = $this->input->post('cost');
        $data['costo_iva']          = $this->input->post('costo_iva');
        $data['alert']              = $this->input->post('alert');
        $data['presentation']       = $this->input->post('selected_caja');
        $data['weight']             = $this->input->post('weight');
        $data['unit']               = $this->input->post('unit');
        $data['corridor']           = $this->input->post('corridor');
        $data['pallet']             = $this->input->post('pallet');
        $data['shelf']              = $this->input->post('shelf');
        $data['level']              = $this->input->post('level');
        $data['description']        = trim($this->input->post('description'));
        if($this->input->post('selected_caja')=='Caja'){
        $data['prod_matriz']        = $this->input->post('prod_matriz');
        $data['id_prod_matriz']     = $this->db->get_where('products',array('code'=>$this->input->post('prod_matriz')))->row()->products_id; 
        $data['cnt_prod_matriz']    = $this->input->post('cnt_prod_matriz');   
        }
        $data['iva']                = $this->input->post('iva_check');
        //log_message("error",'IVA'.$data['iva']);
        $this->db->where('products_id', $ID);
        $this->db->update('products',  $data);
        $message = 'Ha actualizado el producto: '.$this->input->post('name');
        $this->insert_binnacle($message);        
    }

    function delete_product($ID){
        $data['status'] = 0;
        $this->db->where('products_id', $ID);
        $this->db->update('products', $data);

        $message = 'Ha eliminado el producto:'.$this->get_products()->row()->name;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/inventario/'), 'inventario', 'Inventario');
    }

    function create_lote(){
        $products_id = $this->input->post('products_id');

        $data['products_id'] = $this->input->post('products_id');
        $data['user_id']     = $this->session->userdata('login_user_id');
        $data['branch_id']   = $this->session->userdata('branch_id');
        $data['type']        = 1;
        $data['provider']    = $this->input->post('provider_id');
        $data['amount']      = $this->input->post('amount');
        $data['cost']        = $this->input->post('cost');
        $data['price']       = $this->input->post('price');
        $data['description'] = $this->input->post('description');
        $dat2['iva']         = $this->db->get_where('products', array('products_id'=>$this->input->post('products_id')));
        
        $this->db->insert('product_details', $data); 
        $name = $this->db->get_where('products',array('products_id'=>$this->input->post('products_id')))->row()->name;
        $message = 'Ha agregado un nuevo lote de '.$name;
        $this->insert_binnacle($message);

    }

    function edit_lote(){
        
        $data['user_id']     = $this->session->userdata('login_user_id');
        $data['branch_id']   = $this->session->userdata('branch_id');
        $data['provider']    = $this->input->post('provider_id');
        $data['amount']      = $this->input->post('amount');
        $data['cost']        = $this->input->post('cost');
        $data['price']       = $this->input->post('price');
        $data['description'] = $this->input->post('description');
        
        $this->db->where('product_details_id',$this->input->post('product_details_id'));
        $this->db->update('product_details', $data); 

        $name = $this->db->get_where('products',array('products_id'=>$this->input->post('products_id')))->row()->name;
        $message = 'Ha actualizado un lote de '.$name;
        $this->insert_binnacle($message);

    }

    function delete_lote($product_details_id){
        

        $data['status'] =0;
        
        $this->db->where('product_details_id',$product_details_id);
        $this->db->update('product_details', $data); 

        $message = 'Ha eliminado un nuevo lote de '.$name;
        $this->insert_binnacle($message);

    }

    /*function devolucion_producto($product_id) Old
    {
        $branch_id   = $this->session->userdata('branch_id');
        $datetime    = date('Y-m-d H:i:s');

        $detalle_id  = $this->input->post('product_details_id');
        $amount_orig = $this->input->post('amount_orig');
        $cost_orig   = $this->input->post('cost_orig');
        $amount_dev  = $this->input->post('amount_dev');
        $cost_dev    = $this->input->post('cost_dev');
        $sub_dev     = $this->input->post('sub_dev');
        $dif         = $this->input->post('dif');
        $concepto    = $this->input->post('concepto_dif');

        $producto = $this->db->get_where('products', array('products_id'=>$product_id))->row_array();
        $num_lotes = sizeof($detalle_id);
        $lotes = array();
        $count = 0;
        for ($i=0; $i < $num_lotes ; $i++) { 
            $new_products = array('product' => $product_id, 'detail_id' => $detalle_id[$i], 'amount' => $amount_dev[$i], 'price' => $cost_dev[$i], 'sub'=>$sub_dev[$i], 'dif'=>$dif[$i], 'concepto'=>$concepto[$i]);
            array_push($lotes, $new_products);
            if ($amount_dev[$i] != "" || $amount_dev[$i] != null || $amount_dev[$i] > 0) {

                $data['date']         = date('Y-m-d');
                $data['products_id']  = $product_id;
                $data['branch_id']    = $branch_id;
                $data['user_id']      = $this->session->userdata('login_user_id');
                $data['type']         = 3;
                $data['provider']     = $producto['provider'];
                $data['amount']       = $amount_dev[$i];
                $data['cost']         = $cost_orig[$i];
                $data['price']        = $cost_dev[$i];
                $data['activity_ref'] = $detalle_id[$i];
                $data['description']  = 'Devolución';
                $this->db->insert('product_details', $data); 
                $message = 'Ha devuelto el producto '.$producto['name'].' con id de lote: '.$detalle_id[$i];
                $this->insert_binnacle($message);
                $this->insert_notification($message, base64_encode('admin/inventario/'), 'inventario', 'Inventario_devolucion');
                $count++;
            }
        }

        $dat2['code']      = $this->input->post('code');
        $dat2['branch_id'] = $branch_id;
        $dat2['datetime']  = $datetime;
        $dat2['date']      = date("Y-m-d", strtotime($this->input->post('date')));
        $dat2['week']      = date('W');
        $dat2['month']     = date('m');
        $dat2['year']      = date('Y');
        $dat2['num_lotes'] = $count;
        $dat2['lotes']     = json_encode($lotes);
        $dat2['origin']    = $this->input->post('origin');
        $dat2['total']     = $this->input->post('ttl');
        $dat2['loss']      = $this->input->post('perdida');
        $dat2['type']      = 1;
        $dat2['concept']   = 'Devolución';
        $this->db->insert('losse_returns', $dat2);
    }*/
    
    function devolucion_producto($product_id)
        {
            $branch_id   = $this->session->userdata('branch_id');
            $datetime    = date('Y-m-d H:i:s');
            $code_dev = $this->input->post('code');
            $detalle_id  = $this->input->post('product_details_id');
            $amount_orig = $this->input->post('amount_orig');
            $cost_orig   = $this->input->post('cost_orig');
            $amount_dev  = $this->input->post('amount_dev');
            $cost_dev    = $this->input->post('cost_dev');
            $sub_dev     = $this->input->post('sub_dev');
            $dif         = $this->input->post('dif');
            $concepto    = $this->input->post('concepto_dif');
    
            $producto = $this->db->get_where('products', array('products_id'=>$product_id))->row_array();
            $num_lotes = sizeof($detalle_id);
            $lotes = array();
            $count = 0;
            for ($i=0; $i < $num_lotes ; $i++) { 
                $lote = $this->db->get_where('lotes', array('lote_id'=>$detalle_id[$i]))->row();
                $new_products = array('product' => $product_id, 'detail_id' => $detalle_id[$i], 'amount' => $amount_dev[$i], 'price' => $cost_dev[$i], 'sub'=>$sub_dev[$i], 'dif'=>$dif[$i], 'concepto'=>$concepto[$i]);
                array_push($lotes, $new_products);
                if ($amount_dev[$i] != "" || $amount_dev[$i] != null || $amount_dev[$i] > 0) {
    
                    $data['date']         = date('Y-m-d');
                    $data['products_id']  = $product_id;
                    $data['branch_id']    = $branch_id;
                    $data['user_id']      = $this->session->userdata('login_user_id');
                    $data['type']         = 3;
                    $data['provider']     = $producto['provider'];
                    $data['amount']       = $amount_dev[$i];
                    $data['cost']         = $cost_orig[$i];
                    $data['activity_ref'] = $code_dev;
                    $data['description']  = 'Devolución';
                    $this->db->insert('product_details', $data); 
                    $message = 'Ha devuelto el producto '.$producto['name'].' con id de lote: '.$detalle_id[$i];
                    $this->insert_binnacle($message);
                    $this->insert_notification($message, base64_encode('admin/inventario/'), 'inventario', 'Inventario_devolucion');
                    $count++;
                    
                   /* $lot_det['lote_id'] = $detalle_id[$i];
                    $lot_det['code'] = $code_dev;
                    $lot_det['cantidad'] = $amount_dev[$i];
                    $lot_det['date'] = Date('Y-m-d');
                    $this->db->insert('lote_detail', $lot_det); 
                    
                    $dat_lot['existencia'] = ($lote->existencia - $amount_dev[$i]);
                    $this->db->where('lote_id', $lote->lote_id);
                    $this->db->update('lotes', $dat_lot);*/
                    
                }
            }
    
            $dat2['code']        = $this->input->post('code');
            $dat2['branch_id']   = $branch_id;
            $dat2['datetime']    = $datetime;
            $dat2['date']        = date("Y-m-d", strtotime($this->input->post('date')));
            $dat2['week']        = date('W');
            $dat2['month']       = date('m');
            $dat2['year']        = date('Y');
            $dat2['num_lotes']   = $count;
            $dat2['lotes']       = json_encode($lotes);
            $dat2['origin']      = $this->input->post('origin');
            $dat2['total']       = $this->input->post('ttl');
            $dat2['loss']        = $this->input->post('perdida');
            $dat2['type']        = 1;
            $dat2['concept']     = 'Devolución';
            $dat2['responsable'] = $this->session->userdata('login_user_id');
            $this->db->insert('losse_returns', $dat2);
        }
    function perdida_producto($product_id)
    {
        $branch_id   = $this->input->post('branch');
        $datetime    = date('Y-m-d H:i:s');

        $detalle_id  = $this->input->post('product_details_id');
        $amount_orig = $this->input->post('amount_orig');
        $cost_orig   = $this->input->post('cost_orig');
        $amount_per  = $this->input->post('amount_per');
        $cost_orig    = $this->input->post('cost_orig');
        $sub_per     = $this->input->post('sub_per');
        $per         = $this->input->post('per');

        $producto = $this->db->get_where('products', array('products_id'=>$product_id))->row_array();
        $num_lotes = sizeof($detalle_id);
        $lotes = array();
        $count = 0;
        for ($i=0; $i < $num_lotes ; $i++) { 
            $new_products = array('product' => $product_id, 'detail_id' => $detalle_id[$i], 'amount' => $amount_per[$i], 'price' => $cost_orig[$i], 'perdida'=>$sub_per[$i]);
            array_push($lotes, $new_products);
            if ($amount_per[$i] != "" || $amount_per[$i] != null || $amount_per[$i] > 0) {

                $data['date']         = date('Y-m-d');
                $data['products_id']  = $product_id;
                $data['branch_id']    = $branch_id;
                $data['user_id']      = $this->session->userdata('login_user_id');
                $data['type']         = 4;
                $data['provider']     = $producto['provider'];
                $data['amount']       = $amount_per[$i];
                $data['cost']         = $cost_orig[$i];
                $data['price']        = 0;
                $data['activity_ref'] = $detalle_id[$i];
                $data['description']  = 'Pérdida';
                $this->db->insert('product_details', $data); 
                $message = 'Ha registrado como pérdida el producto '.$producto['name'].' con id de lote: '.$detalle_id[$i];
                $this->insert_binnacle($message);
                $this->insert_notification($message, base64_encode('admin/inventario/'), 'inventario', 'Inventario_perdida');
                $count++;
            }
        }

        $dat2['code']      = $this->input->post('code');
        $dat2['branch_id'] = $branch_id;
        $dat2['datetime']  = $datetime;
        $dat2['date']      = date("Y-m-d", strtotime($this->input->post('date')));
        $dat2['week']      = date('W');
        $dat2['month']     = date('m');
        $dat2['year']      = date('Y');
        $dat2['num_lotes'] = $count;
        $dat2['lotes']     = json_encode($lotes);
        $dat2['loss']      = $this->input->post('ttl');
        $dat2['type']      = 1;
        $dat2['concept']   = 'Pérdida';
        $dat2['responsable'] = $this->session->userdata('login_user_id');
        $this->db->insert('losse_returns', $dat2);
    }
    
    function create_traslado(){

        $num = sizeof($this->input->post('products'));
        $products = $this->input->post('products');
        $envia = $this->input->post('branch_envia');
        $branch2 = $this->input->post('branch_recibe');
        $amount2 = $this->input->post('amount2');
        $lotes  = $this->input->post('lote');
        
        $code = $this->getCodeTraslado();
        $data_tras['code_traslado'] = $code;
        $data_tras['Desde']         = $envia;
        $data_tras['Hacia']         = $branch2;
        $data_tras['cantidad_prod'] = $num;
        $data_tras['responsable']   = $this->session->userdata('login_user_id');
        $data_tras['motivo']        = trim($this->input->post('motivo'));
        $data_tras['recibe']        = trim($this->input->post('recibe'));
        $data_tras['date']          = date("Y-m-d H:i:s");
        //echo 'Data traslado'."\n";
        //print_r($data_tras);
        $this->db->insert('traslado', $data_tras); 
        $id_traslado = $this->db->insert_id();
        
        $branch_name = $this->getBranch($envia); if ($envia == 0) $branch_name = "Bodega";
        $branch2_name = $this->getBranch($branch2); if ($branch2 == 0) $branch2_name = "Bodega";
        
        for($i=0; $i < count($products); $i++){
            $producto  = $this->db->get_where('products',array('products_id'=>$products[$i]))->row();
            $amount = $amounts[$i]; $cost = $producto->cost;
            $iva = 0;
            if (!$producto->iva) $iva = 0;
            else $iva = 1;
            $products_id_2 = 0;
            if($producto->presentation == 'Caja') {
                $id_produ      = $producto->id_prod_matriz;
                $amount        = $amount2[$i]*$producto->cnt_prod_matriz;
                $products_id_2 = $products[$i];
                if($producto->cnt_prod_matriz > 0) $cost = $producto->cost/$producto->cnt_prod_matriz;
                //echo "Lote.......";
                //print_r($lote_sel);
            } else {
                $id_produ = $products[$i];
                $amount   = $amount2[$i];
            }
            
            $lote_sel  = $this->db->get_where('lotes',array('id_producto'=>$id_produ,'branch_id'=>$envia,'existencia >'=>0))->row();
            $lotes_com = $this->db->order_by('lote_id','ASC')->get_where('lotes',array('id_producto'=>$id_produ,'branch_id'=>$envia,'existencia >'=>0))->result_array();
            
            $dat_lote['code']              = $code;
            $dat_lote['id_producto']       = $id_produ;
            $dat_lote['fecha']             = date('Y-m-d');
            $dat_lote['fecha_vencimiento'] = $lote_sel->fecha_vencimiento;
            $dat_lote['transaccion']       = 'Traslado';
            $dat_lote['cantidad']          = $amount;
            $dat_lote['existencia']        = $amount;
            $dat_lote['precio']            = $lote_sel->precio;
            $dat_lote['branch_id']         = $branch2;
            $this->db->insert('lotes', $dat_lote);
            
            //lote detail
            /* $detail_lote['lote_id'] = $lote_sel->lote_id;
            $detail_lote['code']    = $code;
            $detail_lote['cantidad']= $amount;
            $detail_lote['type']    = '2';
            $this->db->insert('lote_detail', $detail_lote);
            //print_r($detail_lote);
            
            //lotes
            $data_lote['lote_id']    = $lote_sel->lote_id;
            $data_lote['existencia'] = ($lote_sel->existencia - $amount);
            $this->db->where('lote_id',$lote_sel->lote_id);
            $this->db->update('lotes',$data_lote);
            //print_r($data_lote);*/
            
          /*  foreach ($lotes_com as $lote) {
                # code...
                $stock = $lote['existencia'];

                if($amount > 0) {
                    if($lote['existencia'] >= $amount) {*/
                        
                        $dat2['date']          = date('Y-m-d');
                        $dat2['code']          = $lote['code'];
                        $dat2['products_id']   = $id_produ;
                        $dat2['user_id']       = $this->session->userdata('login_user_id');
                        $dat2['type']          = 0;
                        $dat2['branch_id']     = $envia;
                        $dat2['amount']        = $amount;
                        $dat2['provider']      = $producto->provider; 
                        $dat2['price']         = $lote['precio'];
                        $dat2['cost']          = $cost;
                        $dat2['activity_ref']  = $code;
                        $dat2['description']   = 'Traslado a '.$branch2_name;
                        $dat2['id_traslado']   = $id_traslado;
                        $dat2['expiration']    = $lote['fecha_vencimiento'];
                        $dat2['products_id_2'] = $products_id_2;
                        $dat2['iva']           = $iva;
                    
                        $this->db->insert('product_details', $dat2);  
                        
                        $dat3['date']          = date('Y-m-d');
                        $dat3['code']          = $lote['code'];
                        $dat3['products_id']   = $id_produ;
                        $dat3['user_id']       = $this->session->userdata('login_user_id');
                        $dat3['type']          = 1;
                        $dat3['branch_id']     = $branch2;
                        $dat3['amount']        = $amount;
                        $dat3['provider']      = $producto->provider; 
                        $dat3['price']         = $lote['precio'];
                        $dat3['cost']          = $cost;
                        $dat3['activity_ref']  = $code;
                        $dat3['description']   = 'Traslado desde '.$branch_name;
                        $dat3['id_traslado']   = $id_traslado;
                        $dat3['expiration']    = $lote['fecha_vencimiento'];
                        $dat3['products_id_2'] = $products_id_2;
                        $dat3['iva']           = $iva;
                    
                        $this->db->insert('product_details', $dat3); 
    
                   /*     $new_existencia = $lote['existencia'] - $amount;
                        $this->db->where('lote_id', $lote['lote_id']);
                        $this->db->update('lotes',array('existencia'=>$new_existencia));
                        break;
                    } else {
                        $dat2['date']          = date('Y-m-d');
                        $dat2['code']          = $lote['code'];
                        $dat2['products_id']   = $id_produ;
                        $dat2['user_id']       = $this->session->userdata('login_user_id');
                        $dat2['type']          = 0;
                        $dat2['branch_id']     = $envia;
                        $dat2['amount']        = $lote['existencia'];
                        $dat2['provider']      = $producto->provider;
                        $dat2['price']         = $lote['precio'];
                        $dat2['cost']          = $cost;
                        $dat2['activity_ref']  = $code;
                        $dat2['description']   = 'Traslado a '.$branch2_name;
                        $dat2['id_traslado']   = $id_traslado;
                        $dat2['expiration']    = $lote['fecha_vencimiento'];
                        $dat2['products_id_2'] = $products_id_2;
                        $dat2['iva']           = $iva;
                    
                        $this->db->insert('product_details', $dat2);  
                        
                        $dat3['date']          = date('Y-m-d');
                        $dat3['code']          = $lote['code'];
                        $dat3['products_id']   = $id_produ;
                        $dat3['user_id']       = $this->session->userdata('login_user_id');
                        $dat3['type']          = 1;
                        $dat3['branch_id']     = $branch2;
                        $dat3['amount']        = $lote['existencia'];
                        $dat3['provider']      = $producto->provider;
                        $dat3['price']         = $lote['precio'];
                        $dat3['cost']          = $cost;
                        $dat3['activity_ref']  = $code;
                        $dat3['description']   = 'Traslado desde '.$branch_name;
                        $dat3['id_traslado']   = $id_traslado;
                        $dat3['expiration']    = $lote['fecha_vencimiento'];
                        $dat3['products_id_2'] = $products_id_2;
                        $dat3['iva']           = $iva;
                    
                        $amount = $amount - $lote['existencia'];
                        $this->db->insert('product_details', $dat3); 
    
                        $new_existencia = 0;
                        $this->db->where('lote_id', $lote['lote_id']);
                        $this->db->update('lotes',array('existencia'=>$new_existencia));
                    }
                }
            }*/
        }
    }


    function delete_traslado($traslado_id){
        
        $ref = $this->db->get_where('product_details', array('product_details_id'=>$traslado_id))->row()->activity_ref;
        $this->db->where('product_details_id', $ref);
        $this->db->where('description', 'Traslado');
        $this->db->delete('product_details');
        
        $this->db->where('activity_ref', $traslado_id);
        $this->db->where('description', 'Traslado');
        $this->db->delete('product_details');
        
        $this->db->where('product_details_id', $traslado_id);
        $this->db->delete('product_details');
        
    }
    
    function create_category()
    {
        $data['name']        = $this->input->post('name_category');
        $data['description'] = $this->input->post('description_category');
        $data['branch_id']   = $this->session->userdata('branch_id');
        $this->db->insert('categories', $data);  
        $message = 'Ha ingresado una nueva categoría denominada '.$this->input->post('name_category');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/categorias/'), 'categorias', 'Categoria');
    }
    
    function update_category($id_category)
    {
        $data['name']        = $this->input->post('name_category');
        $data['description'] = $this->input->post('description_category');
        $this->db->where('category_id', $id_category);
        $this->db->update('categories', $data);
        
        $message = 'Ha actualizado la categoría denominada '.$this->input->post('name_category');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/categorias/'), 'categorias', 'Categoria_update');
    }
    
    function delete_category($id_category)
    {
        $data['status']        = 0;
        $this->db->where('category_id', $id_category);
        $this->db->update('categories', $data);
        
        $message = 'Ha elimado la categoría con ID '.$id_category;
        $this->insert_binnacle($message);
    }

    function create_type_product()
    {
        $data['name']        = $this->input->post('name_type');
        $data['description'] = $this->input->post('description_type');
        $data['branch_id']   = $this->session->userdata('branch_id');
        $this->db->insert('type_product', $data);  
        $message = 'Ha ingresado un nuevo tipo de producto denominado '.$this->input->post('name_type');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/type_product/'), 'tipo_producto', 'Tipo_producto');
    }
    
    function update_type_product($id_type_product)
    {
        $data['name']        = $this->input->post('name_type');
        $data['description'] = $this->input->post('description_type');
        $this->db->where('type_product_id', $id_type_product);
        $this->db->update('type_product', $data);
        
        $message = 'Ha actualizado el tipo de producto denominado '.$this->input->post('name_type');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/type_product/'), 'tipo_producto', 'Tipo_producto_update');
    }
    
    function delete_type_product($id_type_product)
    {
        $data['status']        = 0;
        $this->db->where('type_product_id', $id_type_product);
        $this->db->update('type_product', $data);
        
        $message = 'Ha elimado el tipo de producto con ID '.$id_type_product;
        $this->insert_binnacle($message);
    }
    
    function create_class_product()
    {
        $data['name_class']        = $this->input->post('name_class');
        $data['description_class'] = $this->input->post('description_class');
        $this->db->insert('class_product', $data);  
        $message = 'Ha ingresado una nueva clase de producto denominado '.$this->input->post('name_class');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/clase_p/'), 'clase_producto', 'Clase_producto');
    }
    
    function update_class_product($id_type_product)
    {
        $data['name_class']        = $this->input->post('name_class');
        $data['description_class'] = $this->input->post('description_class');
        $this->db->where('class_product_id', $id_type_product);
        $this->db->update('class_product', $data);
        
        $message = 'Ha actualizado la clase de producto denominado '.$this->input->post('name_class');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/clase_p/'), 'clase_producto', 'Clase_producto');
    }
    
    function delete_class_product($id_class_product)
    {
        $data['status']        = 0;
        $this->db->where('class_product_id', $id_class_product);
        $this->db->update('class_product', $data);
        
        $message = 'Ha elimado la clase de producto con ID '.$id_class_product;
        $this->insert_binnacle($message);
    }

    function get_request(){
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->get_where('shopping', array('status' => 1, 'branch_id'=>$branch_id));
    }

    function create_request($code)
    {
        $data['code']         = $code;
        $data['responsable']  = $this->input->post('responsable');
        $data['date_request'] = date("Y-m-d", strtotime($this->input->post('date_request')));
        $data['date']         = date("Y-m-d", strtotime($this->input->post('date')));
        $data['address']      = $this->input->post('address');
        $data['details']      = $this->input->post('details');
        $data['note']         = $this->input->post('note');
        $data['total']        = $this->input->post('ttl');
        $data['location']     = $this->input->post('location');
        $data['destiny']      = $this->input->post('destiny');

        $provider = $this->input->post('provider');
        if ($provider == "") {
            $provider = 0;
        }

        $data['provider']     = $provider;

        $product    = $this->input->post('name');
        $available  = $this->input->post('available');
        $request    = $this->input->post('amount_request');
        $give       = $this->input->post('amount_give');
        $price      = $this->input->post('price_buy');
        $sub        = $this->input->post('sub');

        $num_products = sizeof($product);

        $products_request = array();
        $products_update = array();
        for ($i=0; $i < $num_products ; $i++) {
            $new_products = array( 'product' => $product[$i], 'available'=>$available[$i], 'amount_request' => $request[$i], 'amount_give' => $give[$i], 'price_buy' => $price[$i], 'sub'=>$sub[$i]);
            array_push($products_request, $new_products);
            if ($available[$i] == '1') {
                $edit_products = array('product' => $product[$i], 'amount' => $give[$i], 'price_buy' => $price[$i], 'sub'=>$sub[$i]);
                array_push($products_update, $edit_products);
            }
        }

        $data['products']     = json_encode($products_request);
        $data['num_products'] = $num_products;
        $this->db->insert('request', $data); 
    
        $dat2['products']     = json_encode($products_update);
        $dat2['num_products'] = $num_products;
        $dat2['total']        = $this->input->post('ttl');
        $dat2['type']         = 2; 
        $this->db->where('code', $code);
        $this->db->update('shopping', $dat2);

        $message = 'Ha ingresado una nueva solicitud de compra con el código '.$code;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/compras/'.$code), 'ver_compras', 'Solicitud');
    }

    function get_shopping(){
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->order_by('shopping_id','DESC')->get_where('shopping', array('status' => 1, 'branch_id'=>$branch_id));
    }

    function get_shopping_table() {
        $branch_id = $this->session->userdata('branch_id');
        $initial = $this->input->post('initial');
        $final   = $this->input->post('final');
        //log_message("error", "Initial: $initial, Final: $final");
        if ($initial == '') $initial = date("Y-m-d");
        if ($final == '') $final = date("Y-m-d");
        //log_message("error", "Initial: $initial, Final: $final");
        $code = trim($this->input->post('code'));
        $type = $this->input->post('type');
        $admin_id = $this->input->post('admin_id');
        
        if ($code != '') $this->db->like('code', $code);
        if ($type != '') $this->db->where('type', $type);
        if ($admin_id != '') $this->db->where('provider', $admin_id);
        
        $this->db->where("DATE(date) >= DATE('$initial')", NULL, FALSE);
        $this->db->where("DATE(date) <= DATE('$final')", NULL, FALSE);
        $this->db->where('status', 1);
        $this->db->where('branch_id', $branch_id);
        $this->db->order_by('shopping_id', 'DESC');
        return $this->db->get('shopping');
    }
    
    function get_shopping_date($initial, $final) {
        $branch_id = $this->session->userdata('branch_id');
        $this->db->where("DATE(date) >= DATE('$initial')", NULL, FALSE);
        $this->db->where("DATE(date) <= DATE('$final')", NULL, FALSE);
        $this->db->where('status', 1);
        $this->db->where('branch_id', $branch_id);
        return $this->db->get('shopping');
    }
    
    function get_shopping_credit(){
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->order_by('shopping_id','DESC')->get_where('shopping', array('status' => 1,'credito' => 1, 'branch_id'=>$branch_id));
    }

    function create_shopping()
    {
        $branch_id = $this->session->userdata('branch_id');
        $destino = $this->input->post('location');
        $datetime = date('Y-m-d H:i:s');
        $credito = $this->input->post('credito');
        $code = $this->input->post('code');
        $data['type']        = $this->input->post('type');
        $data['code']        = $this->input->post('code');
        $data['factura']     = $this->input->post('factura');
        $data['chk_factura'] = $this->input->post('chk_factura');
        $data['responsable'] = $this->input->post('responsable');
        $data['date']        = date("Y-m-d", strtotime($this->input->post('date')));
        $data['location']    = $this->input->post('location');
        $data['destiny']     = $destino;
        $data['details']     = $this->input->post('details');
        $data['total']       = $this->input->post('ttl');

        $provider_id        = $this->input->post('provider');
        if ($provider_id == "" || $provider_id == null) {
            $provider_id    = 0;
        }
        $data['branch_id']   = $branch_id;
        $data['datetime']    = $datetime;
        $proveedores         = '';
        $anterior = 0;

        $product    = $this->input->post('product');
        $expiration = $this->input->post('expiration');
        $amount     = $this->input->post('amount');
        $price_buy  = $this->input->post('price_buy');
        $discount   = $this->input->post('discount');
        $sub        = $this->input->post('sub');

        $num_products = sizeof($product);

        $products = array();
        for ($i=0; $i < $num_products ; $i++) { 
            $producto = $this->db->get_where('products',array('products_id'=>$product[$i]))->row();
            $new_products = array('product' => $product[$i], 'expiration' => $expiration[$i],'amount' => $amount[$i], 'price_buy' => $price_buy[$i], 'discount' => $discount[$i], 'sub'=>$sub[$i] );
            array_push($products, $new_products);
            if (($price_buy[$i] != "" || $price_buy[$i] != null) && ($sub[$i] != "" || $sub[$i] != null)) {
                $iva = 0;
                if (!$producto->iva) $iva = 0;
                else $iva = 1;

                if($producto->presentation == 'Caja'){
                    $id_producto = $producto->id_prod_matriz;
                    $cantidad = ($producto->cnt_prod_matriz*$amount[$i]);
                    if ($producto->cnt_prod_matriz > 0) $cost = $price_buy[$i] / $producto->cnt_prod_matriz;
                    else $cost = $price_buy[$i];
                }else{
                    $id_producto = $product[$i];
                    $cantidad = $amount[$i];
                    $cost = $price_buy[$i];
                }
                $dat2['date']         = date('Y-m-d');
                $dat2['products_id']  = $id_producto;
                $dat2['expiration']   = $expiration[$i];
                $dat2['branch_id']    = $destino;
                $dat2['user_id']      = $this->session->userdata('login_user_id');
                $dat2['type']         = 2;
                $dat2['provider']     = $this->db->get_where('products', array('products_id'=>$product[$i]))->row()->provider;
                $dat2['amount']       = $cantidad;
                $dat2['cost']         = $cost;
                $dat2['price']        = $this->last_price_buy($id_producto);
                $dat2['activity_ref'] = $this->input->post('code');
                $dat2['description']  = 'Compra';
                $dat2['iva']          = $iva;
                $this->db->insert('product_details', $dat2); 
                
                $dat_lote['code']       = $code;
                $dat_lote['id_producto']= $id_producto;
                $dat_lote['fecha']      = date('Y-m-d');;
                $dat_lote['fecha_vencimiento']= $expiration[$i];
                $dat_lote['transaccion']= 'Compra';
                $dat_lote['cantidad']   = $cantidad;
                $dat_lote['existencia'] = $cantidad;
                $dat_lote['precio']     = $cost;
                $dat_lote['branch_id']  = $destino;
                $dat_lote['chk_factura'] = $this->input->post('chk_factura');
                $this->db->insert('lotes', $dat_lote); 
                
                // Calculate weighted average cost
                $this->db->select('SUM(existencia * precio) as total_value, SUM(existencia) as total_quantity');
                $this->db->where('id_producto', $id_producto);
                $this->db->where('existencia >', 0);
                $this->db->where('branch_id', $destino);
                $result = $this->db->get('lotes')->row();
                if ($result && $result->total_quantity > 0) {
                    $new_cost = $result->total_value / $result->total_quantity;
                } else {
                    $new_cost = $cost;
                }
                
                $name    = $this->db->get_where('products',array('products_id'=>$dat2['products_id']))->row()->name;
                $message = 'Ha agregado un nuevo lote de '.$name;
                $this->insert_binnacle($message);
                $this->insert_notification($message, base64_encode('admin/inventario/'), 'inventario', 'Inventario');

                if ($anterior != $dat2['provider']) {
                    $proveedores .= $this->db->get_where('provider', array('provider_id'=>$dat2['provider']))->row()->name;
                
                    if (($i+1) != $num_products) {
                        $proveedores .= ', ';
                    }
                }
                $anterior = $dat2['provider'];

                if($producto->presentation == 'Caja'){
                    $dat_matr['cost'] = $new_cost;
                    $this->db->where('products_id', $id_producto);
                    $this->db->update('products', $dat_matr);
                    $dat_caja['cost'] = $new_cost * $producto->cnt_prod_matriz;
                    $this->db->where('products_id', $product[$i]);
                    $this->db->update('products', $dat_caja);
                } else {
                    $dat_prod['cost'] = $new_cost;
                    $this->db->where('products_id', $product[$i]);
                    $this->db->update('products', $dat_prod);
                    $caja = $this->db->get_where('products', array('id_prod_matriz'=>$id_producto, 'status'=>1));
                    if ($caja->num_rows() > 0) {
                        $dat_caja['cost'] = $new_cost * $caja->row()->cnt_prod_matriz;
                        $this->db->where('products_id', $caja->row()->products_id);
                        $this->db->update('products', $dat_caja);
                    }
                }
            }
        }

       /* if($num_products == 1){
            $provider_id = $this->db->get_where('products', array('products_id'=>$product[0]))->row()->provider;
        }*/
        
        $data['provider']    = $provider_id;
        if($credito == 1){
        $data['credito']    = 1 ;
        }else{ 
            $data['credito']    = 0;
        }

        $data['products']     = json_encode($products);
        $data['num_products'] = $num_products;
        
        $data['promocion']    = $this->input->post('promocion');
        $md5 = str_replace(' ', '', date('Y m d H i s'));
        if($_FILES["factura_img"]["name"] != ''){
                $data["factura_img"] = $md5.str_replace(' ', '', $_FILES["factura_img"]["name"]);
                if ($_FILES["factura_img"]["type"]=='application/pdf') {
                    $data["factura_file"] = 'pdf';
                }
                else{
                    $data["factura_file"] = 'image';
                }
            }
        $this->db->insert('shopping', $data);  
        $message = 'Ha ingresado una nueva compra con el código '.$this->input->post('code');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/detalles_compra/'.$this->input->post('code')), 'ver_compras', 'Compra');

        
        
        
        if($this->input->post('credito') != 1){
            $dat3['amount'] = $this->input->post('ttl');
    
            $dat3['origin'] = $this->input->post('origin');
            if ($dat3['origin'] == 0) {
                $cuenta = $this->db->get_where('account_bank', array('bank_id' => $dat3['origin'],'branch_id' => $branch_id,'status' => 1))->row_array();
            }
            else {
                $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $dat3['origin']))->row_array();
            }
            $saldo = $cuenta['current_balance'] - $dat3['amount'];
            $dat3['saldo_inicial'] = $cuenta['current_balance'];
            $dat3['nuevo_saldo']   = $saldo;
    
            $dat4['current_balance'] = $saldo;
    
            $this->db->where('account_bank_id', $cuenta['account_bank_id']);
            $this->db->update('account_bank', $dat4);
            
            if($_FILES["factura_img"]["name"] != ''){
                    $dat3["factura_img"] = $md5.str_replace(' ', '', $_FILES["factura_img"]["name"]);
                    if ($_FILES["factura_img"]["type"]=='application/pdf') {
                        $dat3["factura_type"] = 'pdf';
                    }
                    else{
                        $dat3["factura_type"] = 'image';
                    }
                }
            
            if ($this->input->post('ttl') == 0 || $this->input->post('ttl') == "" || $this->input->post('ttl') == null){
                $dat3['status']       = 2;
            }
            else{
                $dat3['status']       = 1;
            }
            
            $dat3['provider']        = $proveedores;
            $dat3['date']            = date("Y-m-d", strtotime($this->input->post('date')));
            $dat3['responsable']     = $this->input->post('responsable');
            $dat3['details']         = trim($this->input->post('details').' '.$this->input->post('note'));
            $dat3['datetime']        = $datetime;
            $dat3['admin_id']        = $this->session->userdata('login_user_id');
            $dat3['branch_id']       = $branch_id;
            $dat3['week']            = date('W');
            $dat3['month']           = date('m');
            $dat3['year']            = date('Y');
            $dat3['reference_id']    = $this->input->post('code');
            $dat3['table_reference'] = "shopping";
            $this->db->insert('expense', $dat3);
            $expense_id = $this->db->insert_id();
            move_uploaded_file($_FILES["factura_img"]["tmp_name"], 'uploads/vouchers/' . $md5.str_replace(" ", "", $_FILES["factura_img"]["name"]));
    
            if ($dat3['status'] == 1) {
                $message    = 'Ha ingresado un nuevo gasto con id: '.$expense_id.' con concepto: '.$dat3['details'].' y con un total de: '.$dat3['amount'];
                $this->insert_binnacle($message);
                $this->insert_notification($message, base64_encode('admin/egresos/'), 'ver_egresos', 'Egreso');
            }
    
            $this->send_email_shopping($provider_id, $this->input->post('code'));
        }
        else{
            move_uploaded_file($_FILES["factura_img"]["tmp_name"], 'uploads/vouchers/' . $md5.str_replace(" ", "", $_FILES["factura_img"]["name"]));
            
        }
    }

    function add_stock()
    {
        $branch_id = $this->input->post('branch');
        $datetime = date('Y-m-d H:i:s');
        $code = $this->input->post('code');
        $data['code']        = $code;
        $data['total']       = $this->input->post('ttl');
        $data['branch_id']   = $branch_id;
        $data['datetime']    = $datetime;
        $data['responsable'] = $this->session->userdata('login_user_id');

        $product    = $this->input->post('product');
        $expiration = $this->input->post('expiration');
        $amount     = $this->input->post('amount');
        $price_buy  = $this->input->post('price_buy');
        $discount   = $this->input->post('discount');
        $sub        = $this->input->post('sub');

        $num_products = sizeof($product);

        $products = array();
        for ($i=0; $i < $num_products ; $i++) { 
            $producto = $this->db->get_where('products',array('products_id'=>$product[$i]))->row();
            $new_products = array('product' => $product[$i], 'expiration' => $expiration[$i],'amount' => $amount[$i], 'price_buy' => $price_buy[$i], 'discount' => $discount[$i], 'sub'=>$sub[$i] );
            array_push($products, $new_products);
            if (($price_buy[$i] != "" || $price_buy[$i] != null) && ($sub[$i] != "" || $sub[$i] != null)) {
                $iva = 0;
                if (!$producto->iva) $iva = 0;
                else $iva = 1;

                if($producto->presentation == 'Caja'){
                    $id_producto = $producto->id_prod_matriz;
                    $cantidad = ($producto->cnt_prod_matriz*$amount[$i]);
                    if ($producto->cnt_prod_matriz > 0) $cost = $price_buy[$i] / $producto->cnt_prod_matriz;
                    else $cost = $price_buy[$i];
                }else{
                    $id_producto = $product[$i];
                    $cantidad = $amount[$i];
                    $cost = $price_buy[$i];
                }
                $dat2['date']         = date('Y-m-d');
                $dat2['products_id']  = $id_producto;
                $dat2['expiration']   = $expiration[$i];
                $dat2['branch_id']    = $branch_id;
                $dat2['user_id']      = $this->session->userdata('login_user_id');
                $dat2['type']         = 2;
                $dat2['provider']     = $this->db->get_where('products', array('products_id'=>$product[$i]))->row()->provider;
                $dat2['amount']       = $cantidad;
                $dat2['cost']         = $cost;
                $dat2['price']        = $this->last_price_buy($id_producto);
                $dat2['activity_ref'] = $code;
                $dat2['description']  = 'Producto';
                $dat2['iva']          = $iva;
                $this->db->insert('product_details', $dat2); 
                
                
                
                $name    = $this->db->get_where('products',array('products_id'=>$dat2['products_id']))->row()->name;
                $message = 'Ha agregado un nuevo lote de '.$name;
                $this->insert_binnacle($message);
                $this->insert_notification($message, base64_encode('admin/inventario/'), 'inventario', 'Inventario');
            }
        }

        $data['products']     = json_encode($products);
        $data['num_products'] = $num_products;
        
        $this->db->insert('increase', $data);  
        $message = 'Ha ingresado un aumento de existencia con el código '.$code;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/inventario/'), 'ver_compras', 'Compra');
    }

    function record_loss() {
        $branch_id   = $this->input->post('branch');
        $datetime    = date('Y-m-d H:i:s');

        $product_id = $this->input->post('product');
        $cost       = $this->input->post('cost');
        $amount     = $this->input->post('amount');
        $sub        = $this->input->post('sub');

        $num_lotes = sizeof($product_id);
        $producto = array();
        $count = 0;
        
        
        for ($i=0; $i < $num_lotes ; $i++) { 
            $producto_indi =  $this->db->get_where('products',array('products_id '=>$product_id[$i]))->row();
            $iva = 0;
            if (!$producto_indi->iva) $iva = 0;
            else $iva = 1;
            $new_producto = array('product'=>$product_id[$i], 'amount'=>$amount[$i], 'price'=>$cost[$i], 'perdida'=>$sub[$i], 'iva'=>$iva);
            array_push($producto, $new_producto);
            
            $products_id_2 = 0; $cost2 = $cost[$i];
            if($producto_indi->presentation == 'Caja') {
                $products_id_2 = $product_id[$i];
                $id_produ = $producto_indi->id_prod_matriz;
                $amount2  = $amount[$i]*$producto_indi->cnt_prod_matriz;
                if($producto_indi->cnt_prod_matriz > 0) $cost2 = $cost[$i] / $producto_indi->cnt_prod_matriz;
            } else {
                $id_produ = $product_id[$i];
                $amount2  = $amount[$i];
            }
            
            $lotes = $this->db->order_by('lote_id','ASC')->get_where('lotes',array('id_producto'=>$id_produ,'branch_id'=>$branch_id,'existencia >'=>0))->result_array();
           /* 
            foreach ($lotes as $lote) {
                # code...
                $stock = $lote['existencia'];
                $dat2['price']       = $sub[$i];

                if ($amount2 > 0) {
                    if($lote['existencia'] > $amount2) {*/
                        $dat2['date']          = date('Y-m-d');
                        $dat2['code']          = $lote['code'];
                        $dat2['products_id']   = $id_produ;
                        $dat2['user_id']       = $this->session->userdata('login_user_id');
                        $dat2['type']          = 4;
                        $dat2['branch_id']     = $this->session->userdata('branch_id');
                        $dat2['amount']        = $amount2;
                        $dat2['provider']      = $producto_indi->provider; 
                        $dat2['cost']          = $cost2;
                        $dat2['description']   = "Pérdida";
                        $dat2['activity_ref']  = $this->input->post('code');
                        $dat2['products_id_2'] = $products_id_2;
                        $dat2['iva']           = $iva;
                    
                        $this->db->insert('product_details', $dat2);  
                        
                        /*$new_existencia = $lote['existencia'] - $amount2;
                        $this->db->where('lote_id', $lote['lote_id']);
                        $this->db->update('lotes',array('existencia'=>$new_existencia));
                        break;
                    } else {
                        $dat2['date']          = date('Y-m-d');
                        $dat2['code']          = $lote['code'];
                        $dat2['products_id']   = $id_produ;
                        $dat2['user_id']       = $this->session->userdata('login_user_id');
                        $dat2['type']          = 4;
                        $dat2['branch_id']     = $this->session->userdata('branch_id');
                        $dat2['amount']        = $lote['existencia'];
                        $dat2['provider']      = $producto_indi->provider;
                        $dat2['cost']          = $cost2;
                        $dat2['description']   = "Pérdida";
                        $dat2['activity_ref']  = $this->input->post('code');
                        $dat2['products_id_2'] = $products_id_2;
                        $dat2['iva']           = $iva;
                        $this->db->insert('product_details', $dat2);  
                        
                        $amount2 = $amount2 - $lote['existencia'];
                        
                        $new_existencia = 0;
                        $this->db->where('lote_id', $lote['lote_id']);
                        $this->db->update('lotes',array('existencia'=>$new_existencia));
                    }
                }
            }*/
            
            $this->alerta_stock($id_produ);

            $name = $producto_indi->name;
            $message = 'Registro como perdida '.$name.', ID: '.$products[$i].', Código: '.$code;
            $this->insert_binnacle($message);
            $this->insert_notification($message, base64_encode('admin/inventario/'), 'inventario', 'Inventario_perdida');
        }
        
        $data['code']        = $this->input->post('code');
        $data['concept']     = $this->input->post('concept');
        $data['branch_id']   = $branch_id;
        $data['datetime']    = $datetime;
        $data['date']        = date("Y-m-d", strtotime($this->input->post('date')));
        $data['week']        = date('W');
        $data['month']       = date('m');
        $data['year']        = date('Y');
        $data['num_lotes']   = $num_lotes;
        $data['lotes']       = json_encode($producto);
        $data['loss']        = $this->input->post('ttl');
        $data['type']        = 2;        
        $data['responsable'] = $this->session->userdata('login_user_id');
        $this->db->insert('losse_returns', $data);
    }

    function send_email_shopping($provider_id, $code)
    {
        $provider = $this->db->get_where('provider', array('provider_id'=>$provider_id));
        if ($provider->num_rows() > 0 && ($provider->row()->email)) {

            if($this->db->get_where('settings', array('type'=>'noti_email'))->row()->description == 1){
                $correo  = $this->db->get_where('settings', array('type'=>'email'))->row()->description;
                $destino = $this->db->get_where('provider',array('provider_id'=>$provider_id))->row()->email;
                
                $msg = 'Nuevo mensaje recibido a tráves del sitio web, los datos son los siguientes: <br><br>';
                $msg .= '<b>Ingresa al siguiente link:</b><a href="'.base_url().'provider/purchase_details/'.$code.'"> Solicitud de compra </a> <br>';
                
                require("class.phpmailer.php");
                $emails = $destino;
                $mail = new PHPMailer(); 
                $mail->CharSet = 'UTF-8';
                $mail->IsHTML(true);
                $mail->IsMail();
                $mail->addReplyTo($destino);
                $mail->Subject = 'Datos de solicitud';
                $mail->SetFrom('no-reply@msbox.gt', 'Datos de solicitud');
                $mail->AddAddress($emails);
                $data = array(
                    'email_msg' => $msg,
                    'asuntico' => 'Datos de solicitud'
                );
                $mail->Body = $this->load->view('backend/mails/notify.php',$data,TRUE);
                if(!$mail->Send()) {
                    echo "Mailer Error: " . $mail->ErrorInfo;
                }
            }
        }

        if ($provider->num_rows() > 0 && ($provider->row()->whatsapp)) {
            if($this->db->get_where('settings', array('type'=>'whatsapp'))->row()->description == 1 && $this->db->get_where('settings', array('type'=>'code'))->row()->description != ''){
                $code = $this->db->get_where('settings', array('type'=>'code'))->row()->description ;
                $token  = $code;
                $phone = $this->db->get_where('provider',array('provider_id'=>$provider))->row()->whatsapp;
                $telefono = '502'.$phone;
                $mensaje = 'Ingresa al siguiente link:'.base_url().'provider/purchase_details/'.$code;
                
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://msalerts.com/api/sendMessage',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => '5L',
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => 'token='.$token.'&phone='.$telefono.'&message='.urlencode($mensaje),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/x-www-form-urlencoded',
                    ),
                ));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if ($err){
                    // Si entra en la validación ocurrió un error durante el envío.
                }else {
                    // El mensaje fue enviado correctamente
                }
        
            }
        }
    }
    
    
    function update_shopping($code)
    {
        $data['credito']     = $this->input->post('credito');
        $data['abono']       = $this->input->post('abono');
        $data['responsable'] = $this->input->post('responsable');
        $data['date']        = date("Y-m-d", strtotime($this->input->post('date')));
        $data['location']    = $this->input->post('location');
        $data['destiny']     = $this->input->post('destiny');
        $data['details']     = $this->input->post('details');
        $data['total']       = $this->input->post('ttl');
        $data['branch_id']   = $this->session->userdata('branch_id');

        $product    = $this->input->post('product');
        $provider   = $this->input->post('provider');
        $amount     = $this->input->post('amount');
        $price_buy  = $this->input->post('price_buy');
        $price_sale = $this->input->post('price_sale');
        $sub        = $this->input->post('sub');

        $num_products = sizeof($product);

        $products = array();
        for ($i=0; $i < $num_products ; $i++) { 
            $new_products = array( 'product' => $product[$i], 'provider' => $provider[$i], 'amount' => $amount[$i], 'price_buy' => $price_buy[$i], 'price_sale'=>$price_sale[$i], 'sub'=>$sub[$i] );
            array_push($products, $new_products);
            
            $data_prod['cost'] = $price_buy[$i];
            $this->db->where('products_id', $product[$i]);
            $this->db->update('products', $data_prod);
        }

        $data['products']     = json_encode($products);
        $data['num_products'] = $num_products;

        $this->db->where('code', $code);
        $this->db->update('shopping', $data);
        
        $message = 'Ha actualizado la compra con código '.$code;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/detalles_compra/'.$code), 'ver_compras', 'Compra_update');
    }
    
    function delete_shopping($code)
    {
        $data['status']        = 0;
        $this->db->where('code', $code);
        $this->db->update('shopping', $data);

        $this->db->where('table_reference', 'shopping');
        $this->db->where('reference_id', $code);
        $this->db->delete('expense');
        
        $message = 'Ha elimado la compra con código: '.$code;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/compras/'), 'ver_compras', 'Compra_eliminada');
    }

    function complete_shopping($code)
    {
        $branch_id = $this->session->userdata('branch_id');
        $datetime = date('Y-m-d H:i:s');
        $data['datetime'] = $datetime;
        $data['type']     = 1;
        $this->db->where('code', $code);
        $this->db->update('shopping', $data);
        
        $message = 'Ha completado una compra con código: '.$code;
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/detalles_compra/'.$code), 'ver_compras', 'Compra_completa');
        
        $expiration = $this->input->post('expiration');

        $request= $this->db->get_where('request', array('code'=>$code))->result_array();
        foreach ($request as $row) {
            for ($i=0; $i < $row['num_products'] ; $i++){
                if ($row['products'] != "" || $row['products'] != null) {
                    $pro = json_decode($row['products'],true);
                } else {
                    $pro = array();
                }
                if ($pro[$i]['available'] == 1) {
                    $id_product = $pro[$i]['product']; $amount = $pro[$i]['amount_give']; $cost = $pro[$i]['price_buy'];
                    $prod = $this->db->get_where('products', array('products_id'=>$pro[$i]['product']))->row_array();
                    $iva = 0;
                    if (!$producto->iva) $iva = 0;
                    else $iva = 1;

                    if ($prod['presentation'] == 'Caja') {
                        $id_product = $prod['id_prod_matriz'];
                        $amount = $pro[$i]['amount_give']*$prod['cnt_prod_matriz'];
                        if ($prod['cnt_prod_matriz'] > 0) $cost = $pro[$i]['price_buy'] / $prod['cnt_prod_matriz'];
                        else $cost = $pro[$i]['price_buy'];
                    }
                    
                    $dat2['date']         = $row['date'];
                    $dat2['products_id']  = $id_product;
                    $dat2['expiration']   = $expiration[$i];
                    $dat2['branch_id']    = $row['destiny'];
                    $dat2['user_id']      = $this->session->userdata('login_user_id');
                    $dat2['type']         = 2;
                    $dat2['provider']     = $prod['provider'];
                    $dat2['amount']       = $amount;
                    $dat2['cost']         = $cost;
                    $dat2['price']        = $this->last_price($id_product,$row['destiny']);
                    $dat2['activity_ref'] = $code;
                    $dat2['description']  = 'Compra';
                    $dat2['iva']          = $iva;
                    $this->db->insert('product_details', $dat2); 
                    $name    = $this->db->get_where('products',array('products_id'=>$id_product))->row()->name;
                    $message = 'Ha agregado un nuevo lote de '.$name;
                    $this->insert_binnacle($message);
                    $this->insert_notification($message, base64_encode('admin/inventario/'), 'inventario', 'Inventario');
                    
                    
                    $dat_lote['code']       = $code;
                    $dat_lote['id_producto']= $id_product;
                    $dat_lote['fecha']      = date('Y-m-d');;
                    $dat_lote['fecha_vencimiento']= $expiration[$i];
                    $dat_lote['transaccion']= 'Compra';
                    $dat_lote['cantidad']   = $amount;
                    $dat_lote['existencia'] = $amount;
                    $dat_lote['precio']     = $cost;
                    $dat_lote['branch_id']  = $row['destiny'];
                    $this->db->insert('lotes', $dat_lote);
                    
                    // Calculate weighted average cost
                    $this->db->select('SUM(existencia * precio) as total_value, SUM(existencia) as total_quantity');
                    $this->db->where('id_producto', $id_product);
                    $this->db->where('existencia >', 0);
                    $this->db->where('branch_id', $row['destiny']);
                    $result = $this->db->get('lotes')->row();
                    if ($result && $result->total_quantity > 0) {
                        $new_cost = $result->total_value / $result->total_quantity;
                    } else {
                        $new_cost = $cost;
                    }
                    
                    $data_prod['cost'] = $new_cost;
                    
                    if ($prod['presentation'] == 'Caja'){
                        $dat_matr['cost'] = $new_cost;
                        $this->db->where('products_id', $id_product);
                        $this->db->update('products', $dat_matr);
                        $dat_caja['cost'] = $new_cost * $prod['cnt_prod_matriz'];
                        $this->db->where('products_id', $pro[$i]['product']);
                        $this->db->update('products', $dat_caja);
                    } else {
                        $this->db->where('products_id', $pro[$i]['product']);
                        $this->db->update('products', $data_prod);
                        $caja = $this->db->get_where('products', array('id_prod_matriz'=>$id_product, 'status'=>1));
                        if ($caja->num_rows() > 0) {
                            $dat_caja['cost'] = $new_cost * $caja->row()->cnt_prod_matriz;
                            $this->db->where('products_id', $caja->row()->products_id);
                            $this->db->update('products', $dat_caja);
                        }
                    }
                }
            }
        }

        $md5 = str_replace(' ', '', date('Y m d H i s'));

        $dat3['amount'] = $this->input->post('ttl');

        $dat3['origin'] = $this->input->post('origin');
        if ($dat3['origin'] == 0) {
            $cuenta = $this->db->get_where('account_bank', array('bank_id' => $dat3['origin'],'branch_id' => $branch_id,'status'=> 1))->row_array();
        }
        else {
            $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $dat3['origin']))->row_array();
        }
        $saldo = $cuenta['current_balance'] - $dat3['amount'];
        $dat3['saldo_inicial'] = $cuenta['current_balance'];
        $dat3['nuevo_saldo']   = $saldo;

        $dat4['current_balance'] = $saldo;

        $this->db->where('account_bank_id', $cuenta['account_bank_id']);
        $this->db->update('account_bank', $dat4);

        if($_FILES["factura_img"]["name"] != ''){
            $dat3["factura_img"] = $md5.str_replace(' ', '', $_FILES["factura_img"]["name"]);
            if ($_FILES["factura_img"]["type"]=='application/pdf') {
                $data["factura_type"] = 'pdf';
            }
            else{
                $data["factura_type"] = 'image';
            }
        }

        $dat3['datetime']        = $datetime;
        $dat3['status']          = 1;
        $this->db->where('reference_id', $code);
        $this->db->where('table_reference', 'shopping');
        $this->db->update('expense', $dat3);
        move_uploaded_file($_FILES["factura_img"]["tmp_name"], 'uploads/vouchers/' . $md5.str_replace(" ", "", $_FILES["factura_img"]["name"]));
        $expense = $this->db->get_where('expense', array('reference_id'=>$code,'table_reference'=>'shopping'))->row_array();

        $message    = 'Ha ingresado un nuevo gasto con id: '.$expense['expense_id'].' y con un total de: '.$dat3['amount'];
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/egresos/'), 'ver_egresos', 'Egreso');
    }
    
    
    function insert_binnacle($message)
    {
        $binnacle['message']        = '<b>'.$this->getName($this->session->userdata('login_type'), $this->session->userdata('login_user_id')).'</b> '.$message;
        $binnacle['date']           = date('Y-m-d H:i:s');
        $binnacle['branch_id']      = $this->session->userdata('branch_id');
        $binnacle['user_id']        = $this->session->userdata('login_user_id');
        $binnacle['user_type']      = $this->session->userdata('login_type');
        $this->db->insert('binnacle', $binnacle);  
    }
    
    function insert_notification($message='', $url='', $permiso='', $type='', $id='')
    {
        $url_decode = base64_decode($url);
        if ($id != '') {
            $users = $this->db->query("SELECT * FROM `admin` WHERE status = 1 AND admin_id = $id")->result_array();
        }
        else{
            $users = $this->db->query("SELECT a.admin_id, a.job, j.job_id, a.type, j.permissions FROM `admin` AS a LEFT JOIN `job` AS j on a.job = j.job_id WHERE a.status = 1 ")->result_array();
        }

        foreach ($users as $us) {
            $permisos = unserialize($us['permissions']);
            $branchs = unserialize($us['sucursal']);
            if ($us['type'] == 1 || $permisos[$permiso] == 1 || $id != '') {
                $notificacion['notify']          = '<b>'.$this->getName($this->session->userdata('login_type'), $this->session->userdata('login_user_id')).'</b> '.$message;
                $notificacion['date']            = date('Y-m-d H:i:s');
                $notificacion['branch_id']       = $branchs[0];
                $notificacion['user_id']         = $us['admin_id'];
                $notificacion['user_type']       = $us['type'];
                $notificacion['type']            = $type;
                $notificacion['categoria']       = $category;
                $notificacion['url']             = $url_decode;
                $notificacion['original_id']     = $this->session->userdata('login_user_id');
                $notificacion['original_type']   = $this->session->userdata('login_user_type');
                $notificacion['original_branch'] = $this->session->userdata('branch_id');
                $this->db->insert('notificacion', $notificacion);
            }
        }
    }

    function read_notificacion($not_id='')
    {
        $data['readed'] = 1;
        $this->db->where('notificacion_id', $not_id);
        $this->db->update('notificacion', $data);
    }
    
    function get_categories()
    {
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->order_by('name', 'ASC')->get_where('categories', array('status' => 1));
    }

    function get_types_product()
    {
        return $this->db->get_where('type_product', array('status' => 1));
    }
    function get_class_product()
    {
        return $this->db->get_where('class_product', array('status' => 1));
    }

    function get_proveedores()
    {   
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->get_where('provider', array('branch_id'=>$branch_id));
    }

    function get_clientes()
    {   
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->get_where('client', array('branch_id'=>$branch_id));
    }
    
    function get_clients()
    {   
        return $this->db->get_where('client', array('status'=>1));
    }
    
    
    public function downloadExcelCategories()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Nombre');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Cantidad de Productos');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Descripción');
        
        foreach(range('A','D') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2;

        $query = $this->get_categories();
        foreach($query->result_array() as $row)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $row['category_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, count($this->db->get_where('products', array('category' => $row['category_id']))->result_array()));
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['description']);
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Categorias_de_Producto');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="categorias_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    
    public function downloadExcelTypesProduct()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Nombre');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Cantidad de Productos');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Descripción');
        
        foreach(range('A','D') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2;

        $query = $this->get_types_product();
        foreach($query->result_array() as $row)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $row['type_product_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, count($this->db->get_where('products', array('type_product_id' => $row['type_product_id']))->result_array()));
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['description']);
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Tipos_de_Producto');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="tipos_productos_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadExcelMarks()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Nombre');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Cantidad de Productos');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Descripción');
        
        foreach(range('A','D') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2;

        $query = $this->get_mark();
        foreach($query->result_array() as $row)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $row['mark_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, count($this->db->get_where('products', array('mark' => $row['mark_id']))->result_array()));
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['description']);
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Marcas_de_Producto');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="marcas_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadExcelAccountBanks()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Nombre de la cuenta');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'No. Cuenta');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Banco');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Tipo cuenta');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Moneda');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Saldo actual');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Sucursal');
        
        foreach(range('A','G') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2; $e =2; $f =2; $g =2; $h =2;

        $branch_id = $this->session->userdata('branch_id');

        $this->db->where("status",1);
        $this->db->group_start();
        $this->db->where('branch_id',$branch_id);
        $this->db->or_where('branch_id',0);
        $this->db->group_end(); 
        $query = $this->db->get('account_bank');
        foreach($query->result_array() as $row)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $row['account_bank_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name_account']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $row['no_account']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $this->db->get_where('bank', array('bank_id' => $row['bank_id']))->row()->name);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $row['type']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $row['currency']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, number_format($row['current_balance'],2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $this->db->get_where('branch', array('branch_id' => $row['branch_id']))->row()->name);
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Cuentas_Bancarias');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="cuentas_bancarias'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadExcelTransports()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Transporte');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Placas');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Recorrido actual');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Responsable');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Próximo servicio');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Notas');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Estado');
        
        foreach(range('A','H') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2; $e =2; $f =2; $g =2;$h =2;

        $query = $this->get_transports();
        foreach($query->result_array() as $row)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $row['transport_id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $row['license_plate']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, number_format($row['km'], '2', '.', ',').' km.');
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $this->crud_model->getName('admin', $row['responsable']));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, date('d M, Y', strtotime($row['next_service'])));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $row['notes']);
            if($row['status'] == 1)
            {
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, 'Disponible');    
            }
            else
            {
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, 'En ruta');
            }
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Transportes');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="transportes_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadExcelTransportServices()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Transporte');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Próximo servicio');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Método de pago');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Precio');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Lugar de Servicio');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Responsable');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Detalles');
        
        $moneda = $this->crud_model->get_info("moneda");
        
        foreach(range('A','I') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2; $e =2; $f =2; $g =2;$h =2; $i =2;
        $price_format = '';

        $query = $this->get_transportservices();
        foreach($query->result_array() as $row)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $row['service_transport_id']);
            $transporte = '';
            if($row['transport_id']){
                $vehiculo = $this->db->get_where('transport', array('transport_id' => $row['transport_id']))->row_array();
                $transporte = $vehiculo['name'].' - '.$vehiculo['license_plate'];
            }
            elseif($row['company_id']){
                $company = $this->db->get_where('delivery_company', array('delivery_company_id' => $row['company_id']))->row_array();
                $transporte = $company['name'].' - '.$row['code'];
            }
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $transporte);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, date('d M, Y', strtotime($row['date'])));
            $next_service = '';
            if ($row['next_service']) {
                $next_service = date('d M, Y', strtotime($row['next_service']));
            }
            else{
                $next_service = 'No definido';
            }
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $next_service);
            if ($row['payment_method'] == 0) $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, 'Efectivo');
            if ($row['payment_method'] == 1) $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, 'Cheque');
            if ($row['payment_method'] == 2) $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, 'Tarjeta Débito');
            if ($row['payment_method'] == 3) $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, 'Transferencia');
            $price_format = number_format($row['price'],2,'.',',');
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $moneda.$price_format);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $row['place_service']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $this->getName('admin', $row['responsable']));
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i++, $row['details']);
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Servicio_transportes');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="servicio_de_transportes_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadExcelTransfers($ini,$fin)
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Desde');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Hacia');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Cantidad');
        
        
        foreach(range('A','E') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2; $e =2;

        $query = $this->get_transfer3($ini,$fin);
        foreach($query->result_array() as $row)
        {
            setlocale(LC_TIME, "spanish");
            $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));
            $Mes_Anyo = strftime("%m/%d/%Y", strtotime($Nueva_Fecha)); 
            if ($row['branch_id'] == 0) {
                $sucursal = "Bodega";
            }
            else{
                $sucursal = $this->db->get_where('branch',array('branch_id'=>$row['branch_id']))->row()->name;
            }
            if ($row['branch_id2'] == 0) {
                $sucursal2 = "Bodega";
            }
            else{
                $sucursal2 = $this->db->get_where('branch',array('branch_id'=>$row['branch_id2']))->row()->name;
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $row['code_traslado']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $Mes_Anyo);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $sucursal);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $sucursal2);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $row['cantidad_prod']);
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Traslados');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="traslados_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    public function downloadExcelBodega()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Producto');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Categoría');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Stock');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Variantes');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Precio');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Costo');

        foreach(range('A','G') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2; $f=2; $g=2; $h=2; $i=2; $j=2; $k=2; $l=2; $m=2; $n=2;

        $query = $this->get_bodega();
        $rr = 1;
        foreach($query->result_array() as $row)
        {
            if($row['variantes']==1){$vt='Si';}else{$vt='No';}
            
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $rr++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $this->db->get_where('categories', array('category_id' => $row['category']))->row()->name );
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['stock']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $vt );
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $row['price_buy']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $row['price_sale']);
        }

        $objPHPExcel->getActiveSheet()->setTitle('Bodega');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Bodega_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    public function downloadExcelInventario()
    {
        ini_set('memory_limit', '2G');
        $branch_id      = $this->input->post('branch_id');
        log_message('error','branch'.$branch_id);
        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Codigo');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Producto');
        if($this->input->post('branch_id') == 0){
            $objPHPExcel->getActiveSheet()->setCellValue('C1', 'En Bodega');
        }
        else{
            $objPHPExcel->getActiveSheet()->setCellValue('C1', $this->db->get_where('branch', array('branch_id' => $branch_id))->row()->name);
        }
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Costo');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Inversion');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'AFECTO');

        foreach(range('A','F') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2;$f=2;
        
        $moneda     = $this->get_info("moneda");
        $tt = 0;
        
        $query = $this->db->query('select products.products_id, products.name, products.iva, products.code,products.cost, calcinvent(products.products_id, '.$branch_id.',products.id_prod_matriz, products.cnt_prod_matriz) as tienda from products where products.status = 1 and products.presentation = "Unidad" order by products.products_id desc;');
        foreach($query->result_array() as $row)
        {
            if($row['iva']!=1){
                $iva = 'NO';
            }else{$iva = 'SI'; }
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $row['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $row['tienda']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['cost']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, ($row['tienda']*$row['cost']));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $iva);
            $tt = $tt + ($row['tienda']*$row['cost']);
        }
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, 'Total');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $tt);
        $objPHPExcel->getActiveSheet()->setTitle('Inventario');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="inventario_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        ob_end_clean();
        ob_start();
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
        exit;
    }
    
    public function downloadExcelInventario_Mark()
{
    ini_set('memory_limit', '2G');
    
    // Validamos el branch_id para evitar el error SQL 1064
    $branch_id = $this->input->post('branch_id');
    if ($branch_id === null || $branch_id === '') {
        $branch_id = 1; 
    }

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $sheet = $objPHPExcel->getActiveSheet();

    // Estilo para los separadores de marca
    $styleMarca = array(
        'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF')),
        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '2C3E50')),
        'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
    );

    // Encabezados de columna
    $sheet->setCellValue('A1', 'Codigo');
    $sheet->setCellValue('B1', 'Producto');
    
    if($branch_id == 0){
        $sheet->setCellValue('C1', 'En Bodega');
    } else {
        $branch_info = $this->db->get_where('branch', array('branch_id' => $branch_id))->row();
        $sheet->setCellValue('C1', isset($branch_info) ? $branch_info->name : 'Sucursal');
    }
    
    $sheet->setCellValue('D1', 'Costo');
    $sheet->setCellValue('E1', 'Inversion');
    $sheet->setCellValue('F1', 'AFECTO');

    // --- CONSULTA CON JOIN A LA TABLA MARK ---
    $sql = "SELECT 
                p.products_id, 
                p.name as producto_nombre, 
                p.iva, 
                p.code, 
                p.cost, 
                m.name as marca_nombre,
                calcinvent(p.products_id, " . (int)$branch_id . ", p.id_prod_matriz, p.cnt_prod_matriz) as tienda 
            FROM products p
            LEFT JOIN mark m ON p.mark = m.mark_id
            WHERE p.status = 1 AND p.presentation = 'Unidad' 
            ORDER BY m.name ASC, p.name ASC";
    
    $query = $this->db->query($sql);

    $fila = 2; 
    $tt = 0;
    $current_mark = "INICIO_LOOP"; 

    foreach($query->result_array() as $row)
    {
        // Verificar si cambió la marca para poner el encabezado de grupo
        $marca_actual = ($row['marca_nombre'] == "") ? "SIN MARCA" : $row['marca_nombre'];

        if ($current_mark !== $marca_actual) {
            $current_mark = $marca_actual;
            
            // Fila decorativa de marca
            $sheet->mergeCells("A$fila:F$fila");
            $sheet->setCellValue("A$fila", "MARCA: " . strtoupper($current_mark));
            $sheet->getStyle("A$fila:F$fila")->applyFromArray($styleMarca);
            $fila++;
        }

        $iva = ($row['iva'] != 1) ? 'NO' : 'SI';
        $inversion = $row['tienda'] * $row['cost'];

        $sheet->setCellValue('A'.$fila, $row['code']);
        $sheet->setCellValue('B'.$fila, $row['producto_nombre']);
        $sheet->setCellValue('C'.$fila, $row['tienda']);
        $sheet->setCellValue('D'.$fila, $row['cost']);
        $sheet->setCellValue('E'.$fila, $inversion);
        $sheet->setCellValue('F'.$fila, $iva);
        
        $tt += $inversion;
        $fila++;
    }

    // Auto-ajustar ancho de columnas
    foreach(range('A','F') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Fila de Total General
    $fila++;
    $sheet->setCellValue('D'.$fila, 'TOTAL GENERAL:');
    $sheet->setCellValue('E'.$fila, $tt);
    $sheet->getStyle("D$fila:E$fila")->getFont()->setBold(true);

    $sheet->setTitle('Inventario Agrupado');

    // Salida del archivo
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="inventario_marcas_'.date('d-m-Y').'.xlsx"');
    header('Cache-Control: max-age=0');
    
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean();
    $objWriter->save('php://output');
    exit;
}
    public function downloadExcelProductos()
    {
        ini_set('memory_limit', '2G');
        $branch_id      = $this->input->post('branch_id');
        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Codigo');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Producto');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Costo');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Precio');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Precio Mayorista');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Precio Especial');

        foreach(range('A','E') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2; $f=2;$g=2;
        
        $moneda     = $this->get_info("moneda");
        
            $query = $this->db->query('select products.products_id, products.name, products.code, products.cost, products.price, products.precio_mayorista, products.farma from products where products.status = 1 order by products.products_id desc;');
        foreach($query->result_array() as $row)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $row['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $row['cost']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['price']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $row['precio_mayorista']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $row['farma']);
            
        }

        $objPHPExcel->getActiveSheet()->setTitle('Inventario');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Productos_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        ob_end_clean();
        ob_start();
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
        exit;
    }
    
    public function downloadExcelInventarioMark()
    {
        ini_set('memory_limit', '2G');
        $mark      = $this->input->post('mark_id');
        $branch_id = $this->session->userdata('branch_id');
        $objPHPExcel    = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Codigo');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Producto');
        if($branch_id == 0){
            $objPHPExcel->getActiveSheet()->setCellValue('C1', 'En Bodega');
        }
        else{
            $objPHPExcel->getActiveSheet()->setCellValue('C1', $this->db->get_where('branch', array('branch_id' => $branch_id))->row()->name);
        }
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Costo');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Precio');

        foreach(range('A','E') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2;
        
        $moneda     = $this->get_info("moneda");
        
            $query = $this->db->query('select products.products_id, products.name, products.code,products.cost,products.price, calcinvent(products.products_id, '.$branch_id.',products.id_prod_matriz, products.cnt_prod_matriz) as tienda from products where products.status = 1 and products.mark = '.$mark.' order by products.products_id desc;');
        foreach($query->result_array() as $row)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $row['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $row['tienda']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['cost']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $row['price']);
            
        }

        $objPHPExcel->getActiveSheet()->setTitle('Inventario');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="inventario_Marca_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        ob_end_clean();
        ob_start();
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
        exit;
    }
    
    public function downloadExcelProveedores()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Empresa');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Encargado');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Teléfono');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Whatsapp');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Correo');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Dirección');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Nit');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Detalles');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Estado');
        
        foreach(range('A','J') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2; $e =2; $f =2; $g =2; $h =2; $i =2; $j =2;

        $query = $this->db->get('provider');
        $n = 1;
        foreach($query->result_array() as $row)
        {
            if ($row['status'] == 1) {
                $estado = 'Activo';
            }
            else{
                $estado = 'Inactivo';
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $n++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $row['manager']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['phone']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $row['whatsapp']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $row['email']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $row['address']);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $row['nit']);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i++, $row['detail']);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$j++, $estado);
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Proveedores');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="proveedores_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    public function downloadExcelClientes()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Nombre');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Teléfono');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Correo');
        
        foreach(range('A','D') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2;

        $query = $this->get_clientes();
        $n = 1;
        foreach($query->result_array() as $row)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $n++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name'].' '.$row['last_name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $row['phone']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['email']);
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Clientes');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Clientes_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    public function downloadExcelActividades($ID, $date)
    {
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Actividad');
        
        foreach(range('A','C') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; 

        $branch_id = $this->session->userdata('branch_id');

        if ($ID == '0' ) {
            $query = $this->db->query("SELECT * FROM `binnacle` WHERE date LIKE '%$date%'ORDER BY binnacle_id DESC");
        }else{
            $query = $this->db->query("SELECT * FROM `binnacle` WHERE user_id = '$ID' AND date LIKE '%$date%'ORDER BY binnacle_id DESC");
        }
        
        $n = 1;
        foreach($query->result_array() as $row)
        {
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $n++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['date']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $row['message']);
            
        }
        $objPHPExcel->getActiveSheet()->setTitle('Actividades');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Actividades_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    public function downloadExcelEmpleados()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Nombre');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Puesto');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Celular');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'CUI');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Correo');
        
        foreach(range('A','F') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2; $f=2; 

        $branch_id = $this->session->userdata('branch_id');

        $query = $this->db->get_where('admin', array('type'=>2) );
        
        $n = 1;
        foreach($query->result_array() as $row)
        {
            $branchs = unserialize($row['sucursal']);
            if ($branchs[0] == $branch_id) {
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $n++);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name'].' '.$row['last_name']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $row['job']);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['phone']);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $row['cui']);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $row['email']);
            }
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Empleados');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Empleados_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    public function downloadExcelRoles()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Nombre');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Descripción');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Permisos');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Estado');
        
        foreach(range('A','E') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2;

        $query = $this->db->get('job');
        $estado = '';
        
        $n = 1;
        foreach($query->result_array() as $row)
        {
            $permisos = '';
            $permissions = unserialize($row['permissions']);
            $count = count($permissions);
            for ($i=0; $i < $count ; $i++) { 
                $permisos .= strtoupper($permissions[$i]);
                if (($i+1) != $count) {
                    $permisos .= ' | ';
                }
            }
            if ($permissions == null || $count == 0) {
                $permisos = 'Sin asignar';
            }
            if ($row['status'] == 1) {
                $estado = 'Activo';
            }
            else{
                $estado = 'Inactivo';
            }
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $n++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $row['description']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $permisos);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $estado);
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Roles');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Roles_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    public function downloadExcelPlanillas()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Fecha de inicio');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha final');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Origen');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Empleado');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Responsable');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Sueldo');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Adelantos');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Descuentos');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Total');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Notas');
        
        foreach(range('A','J') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2; $f=2; $g=2; $h=2; $i=2; $j=2;

        $branch_id = $this->session->userdata('branch_id');

        $query = $this->db->get_where('payroll', array('branch_id'=>$branch_id, 'status'=>1) );
        
        $n = 1;
        foreach($query->result_array() as $row)
        {
            if ($row['employee'] != "" || $row['employee'] != null) {
                $employee = json_decode($row['employee'],true);
            } else {
                $employee = array();
            } ;
            for ($z=0; $z < $row['num_employee'] ; $z++) { 
            
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $row['date_start']);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['date_end']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $this->db->get_where('bank', array('bank_id'=>$row['bank']))->row()->name);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $this->crud_model->getName('admin',$employee[$z]['employee']));
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $this->crud_model->getName('admin',$row['responsable']));
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $employee[$z]['salary']);
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $employee[$z]['advance']);
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $employee[$z]['discount']);
                $objPHPExcel->getActiveSheet()->setCellValue('I'.$i++, $employee[$z]['sub']);
                $objPHPExcel->getActiveSheet()->setCellValue('J'.$j++, $employee[$z]['note']);
            }
        }
        $objPHPExcel->getActiveSheet()->setTitle('Planillas');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="planillas_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }


    public function downloadExcelCotizaciones()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Cliente');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Celular');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Subtotal');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Descuento');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Total');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Estado');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Responsable');

        foreach(range('A','I') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2; $f=2; $g=2; $h=2; $i=2;

        $query = $this->get_cotizaciones();
        $rr = 1;
        foreach($query->result_array() as $row)
        {
            $hoy = date('Y-m-d') ;
            $vencimiento = $row['date_end'];
            $date1 = new DateTime($hoy);
            $date2 = new DateTime($vencimiento);
            $diff = $date1->diff($date2);
            if($date1 < $date2){
                $stado = 'válido';
            }
            elseif($diff->days == 5 || $diff->days == 4 || $diff->days == 3 || $diff->days == 2 ){
                $stado = 'Por vencer';
            }
            elseif($date1 > $date2){
                $stado = 'Vencido';
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $rr++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $this->getName('client',$row['client_id']));
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $this->db->get_where('client', array('client_id'=>$row['client_id']))->row()->phone );
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['date_end']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $moneda.number_format(($row['total']+$row['discount']),2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $row['discount'] );
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $row['total'] );
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $stado);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i++, $this->getName('admin',$row['responsable']));
        }

        $objPHPExcel->getActiveSheet()->setTitle('Cotizaciones');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="cotización_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }


    public function downloadExcelVentas($initial, $final)
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Estado');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Cliente');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Forma de pago');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Vendedor');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Envío');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Total');

        foreach(range('A','H') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2; $f=2; $g=2; $h=2; 
        $moneda = 'Q';
        $query = $this->get_sale_date($initial, $final);
        $rr = 1;
        $total_ventas = 0 ;
        foreach($query->result_array() as $row)
        {
            if($row['estado']==1 ){
                $stado = 'Activo';
            }elseif($row['estado']==2){
                $stado = 'Confirmado';
            }elseif($row['estado'] == 3){
                $stado = 'Anulado';
            }elseif($row['estado'] == 4){
                $stado = 'Cambio';
            } else {
                $stado = 'Aplicado/Guardado';
            }

            setlocale(LC_TIME, "spanish");
            $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));				
            $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));

            if($row['shipping']==1){
                $shipping ='Si';
            }else{
                $shipping = 'No';
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $rr++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $Mes_Anyo);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $stado);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $row['metodo'] );
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $this->crud_model->getName('admin',$row['responsable']));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $shipping);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $moneda.number_format($row['total'],2,'.',','));
            $total_ventas = ($total_ventas + $row['total']);
        }
         $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $moneda.number_format($total_ventas,2,'.',','));
        $objPHPExcel->getActiveSheet()->setTitle('Ventas');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Ventas_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    public function downloadExcelVentasAnuladas()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Estado');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Cliente');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Forma de pago');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Vendedor');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Envío');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Total');

        foreach(range('A','H') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2; $f=2; $g=2; $h=2; 

        $query = $this->get_anulations();
        $rr = 1;
        foreach($query->result_array() as $row)
        {
            if($row['estado']==1 ){
                $stado = 'Activo';
            }elseif($row['estado']==2){
                $stado = 'Confirmado';
            }elseif($row['estado'] == 3){
                $stado = 'Anulado';
            }elseif($row['estado'] == 4){
                $stado = 'Cambio';
            }

            setlocale(LC_TIME, "spanish");
            $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));				
            $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));

            if($row['shipping']==1){
                $shipping ='Si';
            }else{
                $shipping = 'No';
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $rr++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $Mes_Anyo);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $stado);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $row['metodo'] );
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $this->crud_model->getName('admin',$row['responsable']));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $shipping);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $moneda.number_format($row['total'],2,'.',','));
        }

        $objPHPExcel->getActiveSheet()->setTitle('Ventas');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Anulaciones_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    public function downloadExcelVentasCambiadas()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Estado');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Cliente');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Forma de pago');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Vendedor');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Envío');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Total');

        foreach(range('A','H') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2; $f=2; $g=2; $h=2; 

        $query = $this->get_change();
        $rr = 1;
        foreach($query->result_array() as $row)
        {
            if($row['estado']==1 ){
                $stado = 'Activo';
            }elseif($row['estado']==2){
                $stado = 'Confirmado';
            }elseif($row['estado'] == 3){
                $stado = 'Anulado';
            }elseif($row['estado'] == 4){
                $stado = 'Cambio';
            }

            setlocale(LC_TIME, "spanish");
            $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));				
            $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));

            if($row['shipping']==1){
                $shipping ='Si';
            }else{
                $shipping = 'No';
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $rr++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $Mes_Anyo);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $stado);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $row['metodo'] );
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $this->crud_model->getName('admin',$row['responsable']));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $shipping);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $moneda.number_format($row['total'],2,'.',','));
        }

        $objPHPExcel->getActiveSheet()->setTitle('Ventas');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Cambios_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadExcelEnvios()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Estado');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Cliente');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Forma de pago');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Vendedor');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Envío');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Costo de envío');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Total');

        foreach(range('A','H') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2; $f=2; $g=2; $h=2; $i=2;

        $query = $this->get_shippings();
        $rr = 1;
        foreach($query->result_array() as $row)
        {
            
            if($row['estado']==1 ){
                $stado = 'Activo';
            }elseif($row['estado']==2){
                $stado = 'Confirmado';
            }elseif($row['estado'] == 3){
                $stado = 'Anulado';
            }elseif($row['estado'] == 4){
                $stado = 'Cambio';
            }

            setlocale(LC_TIME, "spanish");
            $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));				
            $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));

            if($row['shipping']==1){
                $shipping ='Pendiente';
            } elseif($row['shipping']==0){
                $shipping = 'No';
            } elseif($row['shipping']==2){
                $shipping = 'Entregada';
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $rr++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $Mes_Anyo);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $stado);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $row['metodo'] );
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $this->crud_model->getName('admin',$row['responsable']));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $shipping);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $moneda.number_format($row['shipping_cost'],2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i++, $moneda.number_format($row['total'],2,'.',','));
        }

        $objPHPExcel->getActiveSheet()->setTitle('Envios Pendientes');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Envios_pendientes'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadExcelEntregas()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Código de Entrega');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Código de Venta');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Encargado');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Cliente');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Dirección de Entrega');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Vehículo');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Fecha asignada');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Fecha y hora de entrega');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Costo inicial');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'Costo extra');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'Costo total');
        $objPHPExcel->getActiveSheet()->setCellValue('M1', 'Origen');
        $objPHPExcel->getActiveSheet()->setCellValue('N1', 'Notas');
        $objPHPExcel->getActiveSheet()->setCellValue('O1', 'Estado');

        foreach(range('A','O') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
        $moneda = $this->get_info("moneda");
        $a=2; $b=2; $c=2; $d=2; $e=2; $f=2; $g=2; $h=2; $i=2; $j=2; $k=2; $l=2; $m=2; $n=2; $o=2;

        $query = $this->get_deliveries();
        setlocale(LC_TIME, "spanish");
        $rr = 1;
        foreach($query->result_array() as $row)
        {
            $venta = $this->db->get_where('sales', array('code'=>$row['sale_code']))->row_array();
            $cliente = $this->getName('client',$venta['client_id']).' '.$venta['nit'];
            $servicio = $this->db->get_where('service_transport', array('service_transport_id'=>$row['service_transport']))->row_array();
            $transport = '';
            if($servicio['transport_id']){
                $vehiculo = $this->db->get_where('transport', array('transport_id' => $servicio['transport_id']))->row_array();
                $transport = $vehiculo['name'].' - '.$vehiculo['license_plate'];
            }
            elseif($servicio['company_id']){
                $company = $this->db->get_where('delivery_company', array('delivery_company_id' => $servicio['company_id']))->row_array();
                $transport = $company['name'].' - '.$servicio['code'];
            }
            $fecha_asig = date("d-m-Y ", strtotime($row['fecha_asignada'])); $nueva_asig = strftime("%d de %B de %Y", strtotime($fecha_asig));
            $fecha_entr = date("d-m-Y h:i A", strtotime($row['fecha_entrega'])); $nueva_entr = strftime("%d de %B de %Y - %H:%M", strtotime($fecha_entr));
            $origen = ''; $metodo = $servicio['payment_method'];
            if($metodo == 0){ 
                $origen = '(Caja Chica) - Efectivo';
            } 
            else{
                $cuenta = $this->db->get_where('account_bank', array('account_bank_id' => $servicio['account_bank_id']))->row_array();
                $bank = $this->db->get_where('bank', array('bank_id' => $cuenta['bank_id']))->row()->name;
                $origen = '('.$bank.') - '.$cuenta['name_account'];
            }
            $estado = '';
            if ($row['status'] == 0) {
                $estado = 'Inactivo';
            }
            else{
                $estado = 'Activo';
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $rr++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $row['sale_code']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $this->getName('admin',$row['responsable_id']));
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $cliente);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $row['address']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $transport);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $nueva_asig);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i++, $fecha_entr);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$j++, $moneda.number_format($row['cost'],2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$k++, $moneda.number_format($row['cost_extra'],2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('L'.$l++, $moneda.number_format($row['total'],2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$m++, $origen);
            $objPHPExcel->getActiveSheet()->setCellValue('N'.$n++, $row['notes']);
            $objPHPExcel->getActiveSheet()->setCellValue('O'.$o++, $estado);
        }

        $objPHPExcel->getActiveSheet()->setTitle('Entregas Realizadas');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Entregas_realizadas'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    public function downloadExcelCompras($initial, $final)
    {
        $total_tt = 0;
        $moneda = $this->get_info("moneda");
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Código');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Proveedor');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Cantidad de Productos');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Total');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Estado');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Encargado');

        foreach(range('A','H') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2; $f=2; $g=2; $h=2;

        $query = $this->get_shopping_date($initial, $final);
        $rr = 1;
        foreach($query->result_array() as $row)
        {
            
            if($row['type']==1 ){
                $stado = 'Completado';
            }elseif($row['type']==2){
                $stado = 'Orden de compra';
            }elseif($row['type'] == 3){
                $stado = 'Solicitud de compra';
            }

            setlocale(LC_TIME, "spanish");
            $Nueva_Fecha = date("d-m-Y", strtotime( $row['datetime'] ));
            $provider = $this->db->get_where('provider', array('provider_id' => $row['provider']))->row()->name;
            $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)).' - '.date('h:i:s a', strtotime($row['datetime']));
            $total_format = $moneda.number_format($row['total'],2,'.',',');

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $rr++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $Mes_Anyo);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $provider);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $row['num_products']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $total_format);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $stado);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $this->getName('admin',$row['responsable']));
            $total_tt = $total_tt + $row['total'];
        }
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, 'Total:');
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, 'Q'.$total_tt);
        $objPHPExcel->getActiveSheet()->setTitle('Compras');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Compras_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    public function downloadExcelCreditos()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Cliente');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'total');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Restante');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Estado');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Responsable');

        foreach(range('A','G') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2; $f=2; $g=2;  

        $branch_id = $this->session->userdata('branch_id');
        $query = $this->db->get_where('sales', array('status'=>1, 'credito'=>1, 'branch_id'=>$branch_id));

        $rr = 1;
        foreach($query->result_array() as $row)
        {
            
            if($row['estado']==1 ){
                $stado = 'Activo';
            }elseif($row['estado']==2){
                $stado = 'Completado';
            }elseif($row['estado'] == 3){
                $stado = 'Anulado';
            }
            $restante = $row['total']- $row['total_pagado'];
            setlocale(LC_TIME, "spanish");
            $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));				
            $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $rr++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $Mes_Anyo);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $moneda.number_format($row['total'],2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $moneda.number_format($restante,2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $stado);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $this->getName('admin',$row['responsable']));

        }

        $objPHPExcel->getActiveSheet()->setTitle('Creditos');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Creditos_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    public function downloadExcelAlertas()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Productos');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Categoria');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Proveedores');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'En tienda');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'En bodega');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Precio');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Estado');

        foreach(range('A','H') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2; $f=2; $g=2; $h=2;  

        $branch_id = $this->session->userdata('branch_id');
        $query = $this->get_products( $branch_id );

        $rr = 1;
        foreach($query->result_array() as $row)
        {
            $category = $this->db->get_where('categories', array('category_id'=>$row['category']))->row()->name;
            $provider = $this->db->get_where('provider', array('provider_id'=>$row['provider']))->row()->name;
            $moneda = $this->crud_model->get_info("moneda");


            $stock_bodega = $this->get_stock($row['products_id'], 0);
            $stock_inventory = $this->get_stock($row['products_id'], $branch_id);
            if(($stock_inventory + $stock_bodega) <= $row['alert'] && ($stock_inventory + $stock_bodega) > 0){
                $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $rr++);
                $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['name']);
                $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $category);
                $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $provider);
                $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $stock_inventory);
                $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $stock_bodega);
                $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $moneda.$row['price_sale']);
                $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, 'Alerta');
            }
        }

        $objPHPExcel->getActiveSheet()->setTitle('Alertas');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Alertas_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    public function downloadExcelIngresos()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Monto');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Referencia');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Destino');

        foreach(range('A','E') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2;   

        $branch_id = $this->session->userdata('branch_id');
        $moneda = $this->crud_model->get_info("moneda");
        $query = $this->db->order_by('income_id','DESC')->get('income');

        $rr = 1;
        foreach($query->result_array() as $row)
        {
            setlocale(LC_TIME, "spanish");
            $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));				
            $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); 
            if($row['origin'] == 0){
                $destino = 'Caja Chica';
            }else{
                $destino = $this->db->get_where('bank', array('bank_id' => $row['origin']))->row()->name;
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $rr++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $Mes_Anyo);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $moneda.$row['amount']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $row['sale_ref']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $destino);
        }

        $objPHPExcel->getActiveSheet()->setTitle('Ingresos');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Ingresos_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadExcelEgresos($status)
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Proveedor');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Destino');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Saldo inicial');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Débito');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Nuevo saldo');

        foreach(range('A','G') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a=2; $b=2; $c=2; $d=2; $e=2; $f=2; $g=2;

        $branch_id = $this->session->userdata('branch_id');
        $moneda = $this->crud_model->get_info("moneda");
        $query = $this->db->order_by('expense_id','DESC')->get_where('expense', array('status'=>$status));

        $rr = 1;
        foreach($query->result_array() as $row)
        {
            setlocale(LC_TIME, "spanish");
            $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));
            $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));
            if($row['origin'] == 0){
                $destino = '(Caja Chica) - Efectivo';
            }else{
                $account = $this->db->get_where('account_bank', array('account_bank_id' => $row['origin']))->row()->name_account;
                $bank_id = $this->db->get_where('account_bank', array('account_bank_id' => $row['origin']))->row()->bank_id;
                $bank = $this->db->get_where('bank', array('bank_id' => $bank_id))->row()->name;
                $destino = '('.$bank.') - '.$account;
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $rr++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $Mes_Anyo);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $row['provider']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $destino);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $moneda.number_format($row['saldo_inicial'],2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $moneda.number_format($row['amount'],2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $moneda.number_format($row['nuevo_saldo'],2,'.',','));
        }

        $objPHPExcel->getActiveSheet()->setTitle('Egresos');
    
        $state = '';
        if ($status == 1) {
            $state = 'activos';
        }
        else if ($status == 0) {
            $state = 'anulados';
        }

        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Egresos_'.$state.'_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadExcelCashHistory()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Responsable');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Fecha de corte');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Hora de Corte');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Monto inicial');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Monto final');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Diferencia');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Notas');
        
        foreach(range('A','G') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2; $e =2; $f =2; $g =2; $h=2;
        $moneda = $this->get_info("moneda");
        $branch_id = $this->session->userdata('branch_id');

        $query = $this->db->get_where('cash_history', array('branch_id'=>$branch_id));
        foreach($query->result_array() as $row)
        {
            setlocale(LC_TIME, "spanish");
            $Nueva_Fecha = date("d-m-Y", strtotime($row['date_close']));
            $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));
            $Nueva_Hora = date("H:i:s", strtotime($row['time_close']));
            $Hora = strftime("%I:%M", strtotime($Nueva_Hora));

            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $row['id']);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $this->getName('admin', $row['user_id']));
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $Mes_Anyo);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $Hora);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $moneda.number_format($row['amount_initial'],2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $moneda.number_format($row['amount_final'],2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $moneda.number_format($row['difference_cash'],2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $row['notes']);
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Cortes_de_caja');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="cortes_caja'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadExcelVentaTraslado($initial, $final, $type = '')
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '#');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Código');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Producto');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Tipo');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Cantidad');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Precio');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Total');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Lote');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Factura');
        
        foreach(range('A','J') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2; $e =2; $f =2; $g =2; $h=2; $i =2; $j =2;
        $moneda = $this->get_info("moneda"); $cont = 1;
        $branch_id = $this->session->userdata('branch_id');

        $query = $this->getVentasTraslados($initial, $final, $type);
        foreach($query->result_array() as $row)
        {
            if($row['products_id_2'] > 0) $prod = $this->db->get_where("products", array("products_id"=>$row['products_id_2']))->row_array();
            else $prod = $this->db->get_where("products", array("products_id"=>$row['products_id']))->row_array();
            $amount = $row['amount']; $divisor = $row['amount'];
            $divisor = $row['amount'] + $this->db->query("SELECT SUM(amount) AS total FROM product_details WHERE status = 1 AND products_id = '".$row['products_id']."' AND activity_ref = '".$row['activity_ref']."' AND product_details_id != '".$row['product_details_id']."'")->row()->total;
            if ($row['products_id_2'] > 0 && $prod['cnt_prod_matriz'] > 0) {
                $amount = $row['amount'] / $prod['cnt_prod_matriz'];
                $divisor = $divisor / $prod['cnt_prod_matriz'];
            }
            $chk_factura = $this->db->get_where("shopping", array("code"=>$row['code']))->row()->chk_factura;
            $product = $prod['name']; if ($row['iva']) $product .= " (Afecto)"; else $product .= " (Exento)";
            $factura = ''; if($chk_factura == 1) $factura = "SI"; else $factura = "NO";
            $price = $row['price']; if ($divisor >= 1 && $row['description'] == 'Venta') $price = $row['price']/$divisor;
            $total = $row['price']; if ($row['description'] != 'Venta') $total = $row['price'] * $amount;
            if ($row['price'] == '') {
                $price = $moneda.number_format($row['cost'],2,'.',',');
                $total = $amount * $row['cost'];
            }
            
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $cont++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $prod['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $product);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, date("d/m/Y", strtotime($row['date'])));
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $row['description'].' - '.$row['activity_ref']);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $amount);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $moneda.number_format($price,2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $moneda.number_format($total,2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i++, $row['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$j++, $factura);
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Movimientos_de_productos');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="movimiento_productos_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
  public function downloadExcelVentasProducto($initial, $final, $products_id = '')
{
    PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
    
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', '#');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Producto');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Venta');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Fecha');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Cantidad');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Precio');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Total');
    
    foreach(range('A','G') as $columnID) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }

    $rowIndex = 2;
    $moneda = $this->get_info("moneda"); 
    $cont = 1;
    $branch_id = $this->session->userdata('branch_id');

    $query = $this->get_sales_product($initial, $final, $products_id);
    $gran_total = 0;

    foreach ($query->result_array() as $row) {
        if ($products_id != '') {
            // Manejo cuando se filtra por producto
            $prod = ($row['products_id_2'] > 0) 
                ? $this->db->get_where("products", array("products_id" => $row['products_id_2']))->row_array() 
                : $this->db->get_where("products", array("products_id" => $row['products_id']))->row_array();
            
            
            $productName .= $row['iva'] ? " (Afecto)" : " (Exento)";
            $amount = $row['amount'];
            if ($row['products_id_2'] > 0 && $prod['cnt_prod_matriz'] > 0) {
                $amount /= $prod['cnt_prod_matriz'];
            }
            $price = $moneda . number_format($row['price'], 2, '.', ',');
        } else {
            // Manejo cuando no se filtra por producto
            $prods = json_decode($row['products'], true);
            foreach ($prods as $pro) {
                $prod = $this->db->get_where("products", array("products_id" => $pro['product']))->row_array();
                $productName = $prod['code'] . ' ' . $prod['name'];
                $productName .= $pro['iva'] ? " (Afecto)" : " (Exento)";
                $amount = $pro['amount'];
                $price = $moneda . number_format($pro['price'], 2, '.', ',');
                
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowIndex, $cont++);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowIndex, $prod['code']);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowIndex, $prod['name']);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowIndex, $row['code']);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowIndex, date("d/m/Y", strtotime($row['date'])));
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowIndex, $amount);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowIndex, $price);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowIndex, $moneda . number_format($pro['amount'] * $pro['price'], 2, '.', ','));
                $gran_total += $pro['amount'] * $pro['price'];
                $rowIndex++;
            }
            continue;
        }

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowIndex, $cont++);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowIndex, $prod['code']);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowIndex, $prod['name']);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowIndex, $row['code']);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowIndex, date("d/m/Y", strtotime($row['date'])));
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowIndex, $amount);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowIndex, $price);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowIndex, $moneda . number_format($row['total'], 2, '.', ','));
        $gran_total += $row['total'];
        $rowIndex++;
    }

    $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowIndex, 'Total');
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowIndex, $moneda . number_format($gran_total, 2, '.', ','));
    
    $objPHPExcel->getActiveSheet()->setTitle('Ventas_por_producto');

    header("Content-Type: application/vnd.ms-excel");
    header('Content-Disposition: attachment;filename="ventas_por_producto_' . date('d-m-y_h:i:s') . '.xlsx"');
    header("Cache-Control: max-age=0");
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
}

    
    public function downloadExcelVentasMarca($initial, $final, $mark_id = '')
    {
        log_message("error", "Excel Ventas por marca");
        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
        log_message("error", "Mark ID: $mark_id");
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '#');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Marca');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Código Producto');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Nombre Producto');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Venta');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Cliente');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Cantidad');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Precio');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Total');
        
        foreach(range('A','J') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        $a = 2; $b =2; $c =2; $d =2; $e =2; $f =2; $g =2; $h =2; $i =2; $j =2;
        $moneda = $this->get_info("moneda"); $cont = 1;
        $branch_id = $this->session->userdata('branch_id');
    
        $query = $this->get_sales_mark($initial, $final, $mark_id);
        foreach($query->result_array() as $row)
        {
            $mark = ''; $product_code = ''; $product_name = ''; $amount = ''; $price = ''; $client = "Consumidor Final"; 
            $total = $moneda.number_format($row['total'],2,'.',',');
            
            if($row['client_id']) {
                $client = $this->crud_model->getName("client", $row['client_id']);
            }
            
            if ($mark_id != '') {
                $prod = $this->db->get_where("products", array("products_id"=>$row['products_id']))->row_array();
                $mark = $this->db->get_where('mark', array('mark_id'=>$row['mark']))->row()->name;
                $product_code = $prod['code'];
                $product_name = $prod['name'];
                if ($row['iva']) $product_name .= " (Afecto)";
                else $product_name .= " (Exento)";
                $amount = $row['amount'];
                $price = $moneda.number_format($row['price'],2,'.',',');
            } else {
                $prods = json_decode($row['products'], true); 
                $i = 1; 
                $count = count($prods);
                foreach($prods as $pro){
                    $prod = $this->db->get_where("products", array("products_id"=>$pro['product']))->row_array();
                    $ma = $this->db->get_where("mark", array("mark_id"=>$prod['mark']))->row_array();
                    $mark .= $ma['name'].'.';
                    $product_code .= $prod['code'];
                    $product_name .= $prod['name'];
                    if ($pro['iva']) $product_name .= " (Afecto)";
                    else $product_name .= " (Exento)";
                    $amount .= $pro['amount'];
                    if ($i < $count) {
                        $mark .= ''.PHP_EOL.'';
                        $product_code .= ''.PHP_EOL.'';
                        $product_name .= ''.PHP_EOL.'';
                        $amount .= ''.PHP_EOL.'';
                    }
                    $i++;
                }
            }
            
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $cont++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, date("d/m/Y", strtotime($row['date'])));
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $mark);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $product_code);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $product_name);
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $row['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $client);
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $amount);
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i++, $price);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$j++, $total);
        }
        
        $objPHPExcel->getActiveSheet()->setTitle('Ventas_por_marca');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ventas_por_marca_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }

    
    public function downloadExcelVentasAfectas($initial, $final)
    {
        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
    
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
    
        // Encabezados
        $headers = ['#', 'Fecha', 'Código', 'Cliente', 'Codigo', 'Producto', 'Cantidad', 'Precio', 'Total'];
        $columnLetters = range('A', 'I');
    
        foreach ($headers as $index => $header) {
            $sheet->setCellValue($columnLetters[$index] . '1', $header);
        }
    
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        $rowNumber = 2; // Empezamos desde la segunda fila
        $moneda = $this->get_info("moneda");
        $cont = 1;
    
        $query = $this->get_sales_affects($initial, $final);
    
        foreach ($query->result_array() as $row) {
            $client = "Consumidor Final";
            if ($row['client_id']) {
                $client = $this->getName("client", $row['client_id']);
            }
    
            $prods = json_decode($row['products'], true);
    
            foreach ($prods as $pro) {
                if ($pro['iva']) {
                    $prod = $this->db->get_where("products", array("products_id" => $pro['product']))->row_array();
                    $product = $prod['name'] . " (Afecto)";
    
                    if ($row['my'] == '1') {
                        $price = $pro['price_my'];
                    } elseif ($row['my'] == '2') {
                        $price = $pro['price'];
                    } elseif ($row['my'] == '3') {
                        $price = $pro['price_farma'];
                    }
    
                    $total = $pro['amount'] * $price;
    
                    // Escribimos en la fila actual
                    $sheet->setCellValue('A' . $rowNumber, $cont);
                    $sheet->setCellValue('B' . $rowNumber, date("d/m/Y", strtotime($row['date'])));
                    $sheet->setCellValue('C' . $rowNumber, $row['code']);
                    $sheet->setCellValue('D' . $rowNumber, $client);
                    $sheet->setCellValue('E' . $rowNumber, $prod['code']);
                    $sheet->setCellValue('F' . $rowNumber, $product);
                    $sheet->setCellValue('G' . $rowNumber, $pro['amount']);
                    $sheet->setCellValue('H' . $rowNumber, $price);
                    $sheet->setCellValue('I' . $rowNumber, $moneda . number_format($total, 2, '.', ','));
    
                    $rowNumber++; // Aumentamos la fila para el siguiente producto
                }
            }
    
            $cont++; // Incrementamos el número de venta
        }
    
        $sheet->setTitle('Ventas_afectas');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="ventas_afectas_' . date('d-m-y_h-i-s') . '.xlsx"');
        header("Content-Transfer-Encoding: binary");
    
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
    }

    
   public function downloadExcelVentasExentas($initial, $final)
    {
        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
    
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $sheet = $objPHPExcel->getActiveSheet();
    
        // Encabezados
        $headers = ['#', 'Fecha', 'Código', 'Cliente', 'Codigo', 'Producto', 'Cantidad', 'Precio', 'Total'];
        $columnLetters = range('A', 'I');
    
        foreach ($headers as $index => $header) {
            $sheet->setCellValue($columnLetters[$index] . '1', $header);
        }
    
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }
    
        $rowNumber = 2; // Empezamos desde la segunda fila
        $moneda = $this->get_info("moneda");
        $cont = 1;
    
        $query = $this->get_sales_exents($initial, $final);
    
        foreach ($query->result_array() as $row) {
            $client = "Consumidor Final";
            if ($row['client_id']) {
                $client = $this->getName("client", $row['client_id']);
            }
    
            $prods = json_decode($row['products'], true);
    
            foreach ($prods as $pro) {
                if (!$pro['iva']) { // Solo productos exentos
                    $prod = $this->db->get_where("products", array("products_id" => $pro['product']))->row_array();
                    $product = $prod['name'] . " (Exento)";
    
                    if ($row['my'] == '1') {
                        $price = $pro['price_my'];
                    } elseif ($row['my'] == '2') {
                        $price = $pro['price'];
                    } elseif ($row['my'] == '3') {
                        $price = $pro['price_farma'];
                    }
    
                    $total = $pro['amount'] * $price;
    
                    // Escribimos en la fila actual
                    $sheet->setCellValue('A' . $rowNumber, $cont);
                    $sheet->setCellValue('B' . $rowNumber, date("d/m/Y", strtotime($row['date'])));
                    $sheet->setCellValue('C' . $rowNumber, $row['code']);
                    $sheet->setCellValue('D' . $rowNumber, $client);
                    $sheet->setCellValue('E' . $rowNumber, $prod['code']);
                    $sheet->setCellValue('F' . $rowNumber, $product);
                    $sheet->setCellValue('G' . $rowNumber, $pro['amount']);
                    $sheet->setCellValue('H' . $rowNumber, $price);
                    $sheet->setCellValue('I' . $rowNumber, $moneda . number_format($total, 2, '.', ','));
    
                    $rowNumber++; // Aumentamos la fila para el siguiente producto
                }
            }
    
            $cont++; // Incrementamos el número de venta
        }
    
        $sheet->setTitle('Ventas_Exentas');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Disposition: attachment;filename="ventas_exentas_' . date('d-m-y_h-i-s') . '.xlsx"');
        header("Content-Transfer-Encoding: binary");
    
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
    }

    
    public function downloadExcelCuentasCobrar($initial, $final, $client_id = '')
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '#');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Venta');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Cliente NIT');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Cliente');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Productos');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Pendiente');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Total');
        
        foreach(range('A','G') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2; $e =2; $f =2; $g =2; $h =2;
        $moneda = $this->get_info("moneda"); $cont = 1;
        $branch_id = $this->session->userdata('branch_id');

        $query = $this->get_accounts_receivable($initial, $final, $client_id);
        foreach($query->result_array() as $row)
        {
            $product = ''; $amount = ''; $price = ''; $total = ''; $cliente = "Consumidor Final"; $pendiente = $row['total']; $actions = '';
            
            if ($row['client_id'] > 0) {
                $cli = $this->db->get_where('client', array('client_id'=>$row['client_id']))->row_array();
                $cliente = trim($cli['name']).' '.trim($cli['last_name']);
            } 
            // log_message("error", "Product: ".$row->products);
            $prods = json_decode($row['products'], true); $i = 1;
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
            $pagos = $this->db->query("SELECT SUM(IFNULL(amount,0)) AS total FROM credit_details WHERE sales_id = '".$row['sales_id']."' AND status = 1")->row()->total;
            if ($pagos > 0) $pendiente -= $pagos;
            
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $cont++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $cli['nit']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $cliente);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, date("d/m/Y", strtotime($row['date'])));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $product);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $moneda.number_format($pendiente,2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $moneda.number_format($row['total'],2,'.',','));
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Cuentas por cobrar');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="cuentas_por_cobrar_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadExcelCuentasPagar($initial, $final, $client_id = '')
    {
        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '#');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Compra');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Proveedor');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Pendiente');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Total');
        
        foreach(range('A','G') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2; $e =2; $f =2; $g =2;
        $moneda = $this->get_info("moneda"); $cont = 1;
        $branch_id = $this->session->userdata('branch_id');

        $query = $this->get_accounts_payable($initial, $final, $client_id);
        foreach($query->result_array() as $row)
        {
            $product = ''; $amount = ''; $price = ''; $total = ''; $provider = ''; $pendiente = $row['total']; $actions = '';
            
            $prov = $this->db->get_where('provider', array('provider_id'=>$row['provider']))->row_array();
            if($row['provider']) $provider = trim($prov['nit']).' - ';
            $provider .= trim($prov['name']);
            // log_message("error", "Product: ".$row->products);
            $prods = json_decode($row['products'], true); $i = 1;
            $count = count($prods);
            foreach($prods as $pro){
                // log_message("error", "Product: ".$pro['product']);
                $prod = $this->db->get_where("products", array("products_id"=>$pro['product']))->row_array();
                
                $product .= $prod['code'].' '.$prod['name'];
                if ($pro['iva']) $product .= " (Afecto)";
                else $product .= " (Exento)";
                
                if ($i<$count) $product .= ''.PHP_EOL.'';
                $i++;
            }
            $pagos = $this->db->query("SELECT SUM(IFNULL(amount,0)) AS total FROM credit_details WHERE sales_id = '".$row['shopping_id']."' AND status = 1")->row()->total;
            if ($pagos > 0) $pendiente -= $pagos;
            
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $cont++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $provider);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, date("d/m/Y", strtotime($row['date'])));
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $moneda.number_format($pendiente,2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $moneda.number_format($row['total'],2,'.',','));
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Cuentas por pagar');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="cuentas_por_pagar_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadExcelVentasVendedor($initial, $final, $vendedor_id = '')
    {
        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '#');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Vendedor');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Venta');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Cliente');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Cantidad');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Total');
        
        foreach(range('A','G') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2; $e =2; $f =2; $g =2;
        $moneda = $this->get_info("moneda"); $cont = 1;
        $branch_id = $this->session->userdata('branch_id');

        $query = $this->get_sales_seller($initial, $final, $vendedor_id);
        foreach($query->result_array() as $row)
        {
            $client = "Consumidor Final"; $vendedor = '';
            
            if($row['client_id']) {
                $client = $this->crud_model->getName("client", $row['client_id']);
            }
            /*if ($row['user_sale']) {
                $vendedor = $this->crud_model->getName("admin", $row['user_sale']);
            } else {*/
                $vendedor = $this->crud_model->getName("admin", $row['responsable']);
            //}
            
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $cont++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $vendedor);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $row['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $client);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, date("d/m/Y", strtotime($row['date'])));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $row['num_products']);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $moneda.number_format($row['total'],2,'.',','));
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Ventas por vendedor');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="ventas_por_vendedor_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
     
    public function downloadExcelPerdidaDetallesFechas($initial, $final)
    {
        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '#');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Transacción');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Código');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Producto');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Unidades');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Costo');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Total');
        
        foreach(range('A','G') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2; $e =2; $f =2; $g =2;$h =2;
        $moneda = $this->get_info("moneda"); $cont = 1; $total = 0;
        $branch_id = $this->session->userdata('branch_id');

        $query = $this->get_losses_products($initial, $final);
        foreach($query->result_array() as $data)
        {
            $rows = json_decode($data['lotes'], true); $sub = 0;
            foreach($rows as $row){
                $sub = 0;
                if ($row['amount'] > 0) {
                    $prod = $this->db->get_where('products', array('products_id'=>$row['product']))->row_array();
				    $sub = $row['amount']*$row['price'];
                    $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $cont++);
                    $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $data['code']);
                    $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, date("d/m/Y", strtotime($data['datetime'])));
                    $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $prod['code']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $prod['name']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, $row['amount']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, $moneda.$row['price']);
                    $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $moneda.number_format($sub,2,'.',','));
                    $total += $sub;
                }
            }
        }
        
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, "Total");
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $moneda.number_format($total,0,'',''));
       
        $objPHPExcel->getActiveSheet()->setTitle('Productos en pérdida');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="productos_en_pérdida_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadExcelProductosVencidos($initial, $final)
    {
        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '#');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Lote');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Código');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Producto');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Fecha de compra');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Vencimiento');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Unidades compradas');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Unidades vencidas');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Precio de compra');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Total en pérdida');
        
        foreach(range('A','J') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2; $e =2; $f =2; $g =2; $h =2; $i =2; $j =2;
        $moneda = $this->get_info("moneda"); $cont = 1; $total = 0; $sub = 0;
        $branch_id = $this->session->userdata('branch_id');

        $query = $this->get_expired_products_dates($initial, $final);
        foreach($query->result_array() as $row)
        {
            $sub = $row['existencia'] * $row['precio'];
            $prod = $this->db->get_where('products', array('products_id'=>$row['id_producto']))->row_array();
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $cont++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $row['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $prod['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $prod['name']);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, date("d/m/Y", strtotime($row['fecha'])));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, date("d/m/Y", strtotime($row['fecha_vencimiento'])));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, number_format($row['cantidad'],0,'',''));
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, number_format($row['existencia'],0,'',''));
            $objPHPExcel->getActiveSheet()->setCellValue('I'.$i++, $moneda.number_format($row['precio'],0,'',''));
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$j++, $moneda.number_format($sub,0,'',''));
            $total += $sub;
        }
        
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('I'.$i++, "Total");
        $objPHPExcel->getActiveSheet()->setCellValue('J'.$j++, $moneda.number_format($total,0,'',''));
       
        $objPHPExcel->getActiveSheet()->setTitle('Productos vencidos');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="productos_vencidos_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    public function downloadExcelincreases_details($initial, $final)
    {
        PHPExcel_Cell::setValueBinder(new PHPExcel_Cell_AdvancedValueBinder());
        
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', '#');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Codigo');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Producto');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Fecha');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Responsable');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Unidades');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Costo');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Total');
        
        foreach(range('A','J') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2; $e =2; $f =2; $g =2; $h =2; $i =2; $j =2;
        $moneda = $this->get_info("moneda"); $cont = 1; $total = 0; $sub = 0;
        $branch_id = $this->session->userdata('branch_id');
        $datoss = $this->crud_model->get_increases_details($initial,$final); 
        foreach($datoss->result_array() as $rows)
        {
            $products_id = $rows['products_id'];
            if ($rows['products_id_2']) {
            $products_id = $rows['products_id_2'];
            }
            $prod = $this->db->get_where('products', array('products_id'=>$products_id))->row();
            $sub = $rows['amount'] * $rows['cost'];
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $cont++);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $rows['code']);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $prod->code.' '.$prod->name);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, date("d/m/Y h:i a", strtotime($rows['datetime'])));
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $this->crud_model->getName("admin", $rows['responsable']));
            $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, number_format($rows['amount'],2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, number_format($rows['cost'],2,'.',','));
            $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, number_format($sub,2,'.',','));
            $total += $sub;
        }
        
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$f++, '');
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$g++, "Total");
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$h++, $moneda.number_format($total,2,'.',','));
        
       
        $objPHPExcel->getActiveSheet()->setTitle('Productos vencidos');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Ingreso_productos_'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    /* .pdf */
    public function downloadPDFCategories()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/categories.php',$data,TRUE); 
        $pdfFilePath = "reporte_categorias-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }
    
    public function downloadPDFTypesProduct()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/types_product.php',$data,TRUE); 
        $pdfFilePath = "reporte_tipos_productos-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFMarks()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/marks.php',$data,TRUE); 
        $pdfFilePath = "reporte_marcas-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }
    
    public function downloadPDFAccountBanks()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/account_banks.php',$data,TRUE); 
        $pdfFilePath = "reporte_cuentas_bancarias-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }
    
    public function downloadPDFTransports()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/transports.php',$data,TRUE); 
        $pdfFilePath = "reporte_transportes-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }
    
    public function downloadPDFTransportServices()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/transportservices.php',$data,TRUE); 
        $pdfFilePath = "reporte_servicio_transportes-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFTransfers()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/transfers.php',$data,TRUE); 
        $pdfFilePath = "reporte_traslados-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFProveedores()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/proveedores.php',$data,TRUE); 
        $pdfFilePath = "reporte_de_proveedores-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFClientes()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/clientes.php',$data,TRUE); 
        $pdfFilePath = "reporte_de_clientes-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }
    public function downloadPDFActividades($ID,$date)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        'ID' => $ID,
        'date' => $date,
        );
        $html = $this->load->view('backend/viewspdf/actividades.php',$data,TRUE); 
        $pdfFilePath = "reporte_de_actividades-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFEmpleados()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        );
        $html = $this->load->view('backend/viewspdf/empleados.php',$data,TRUE); 
        $pdfFilePath = "reporte_de_empleados-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFEmpleado($ID)
    {
        $employee = $this->db->get_where('admin', array('admin_id' => $ID, 'type' => 2))->row_array();
        if (!$employee) {
            show_404();
            return;
        }

        $data = array(
            'employee' => $employee,
        );
        $html = $this->load->view('backend/viewspdf/empleado.php', $data, TRUE);
        $pdfFilePath = "perfil_empleado-".$ID."-".date('Ymd_His').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8', 'A4');
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html, 2);
        $mpdf->Output($pdfFilePath, "I");
    }

    public function downloadPDFRoles()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        );
        $html = $this->load->view('backend/viewspdf/roles.php',$data,TRUE); 
        $pdfFilePath = "reporte_de_roles-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFRol($ID)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'ID' => $ID,
        );
        $html = $this->load->view('backend/viewspdf/rol.php',$data,TRUE); 
        $pdfFilePath = "reporte_de_rol-".$ID.'-'.date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function imprimir_payroll($ID, $post)
    {
        $data = array(
            'status' => '1',
            'ID' => $ID,
            'post' => $post,
        );
        $payroll_name = $this->db->get_where('payroll', array('payroll_id' => $ID))->row()->payroll_name;
        if (empty($payroll_name)) {
            $payroll_name = 'Oficial';
        }

        $is_bonus = in_array($payroll_name, array('Bono 14', 'Aguinaldo'), true);
        $employee_id = (int) $post;
        // Resumen del pago (planilla/bono) con /0; boleta solo por empleado.
        $use_boleta = ($employee_id > 0);

        if ($use_boleta) {
            $html = $this->load->view('backend/viewspdf/payroll_boleta.php', $data, TRUE);
            $pdf_label = $is_bonus
                ? 'Boleta_'.str_replace(' ', '_', $payroll_name)
                : 'Boleta_planilla_'.strtolower($payroll_name);
        } else {
            $html = $this->load->view('backend/viewspdf/payroll.php', $data, TRUE);
            $pdf_label = in_array($payroll_name, array('Oficial', 'Interna'), true)
                ? 'Planilla_'.strtolower($payroll_name)
                : str_replace(' ', '_', $payroll_name);
        }

        $pdfFilePath = $pdf_label."-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4');
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html, 2);
        $mpdf->Output($pdfFilePath, "I");
    }
    
    public function downloadPDFplanilla()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/planilla.php',$data,TRUE); 
        $pdfFilePath = "Reporte_de_planillas-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFBodega()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/bodega.php',$data,TRUE); 
        $pdfFilePath = "Reporte_de_bodega-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFInventario()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/inventario.php',$data,TRUE); 
        $pdfFilePath = "Reporte_de_inventario-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFCotizaciones()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/cotizaciones.php',$data,TRUE); 
        $pdfFilePath = "Reporte_de_cotizaciones-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFQuotes($ID)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        'ID' =>$ID
        );
        //$html = $this->load->view('backend/viewspdf/quotes.php',$data,TRUE); 
        $html = $this->load->view('backend/viewspdf/reciboCotizacion.php',$data,TRUE); 
        $pdfFilePath = "Cotización-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFVentas($initial, $final)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
            'status' => '1',
            'initial' => $initial,
            'final' => $final
        );
        $html = $this->load->view('backend/viewspdf/ventas.php',$data,TRUE); 
        $pdfFilePath = "Ventas-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFVenta($code)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        'code' => $code
        );
        $html = $this->load->view('backend/viewspdf/venta.php',$data,TRUE); 
        $pdfFilePath = "Venta-".$code.date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }
    
     public function downloadPDFVentaFel($code)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        'code' => $code
        );
        $venta = $this->db->get_where('sales',array('code'=>$code))->row();
        if($venta->credito==1){
            $html = $this->load->view('backend/viewspdf/fac_cambiaria.php',$data,TRUE); 
        }else{
           $html = $this->load->view('backend/viewspdf/ventafel.php',$data,TRUE);     
        }
        
        
        $pdfFilePath = "Venta-".$code.date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }
    
    public function downloadPDFReciboVenta($code)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        'code' => $code
        );
        $html = $this->load->view('backend/viewspdf/reciboVenta1.php',$data,TRUE); 
        $pdfFilePath = "Venta-".$code.date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
        exit();
    }
    
    public function downloadPDFReciboVenta1($code)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        'code' => $code
        );
        $html = $this->load->view('backend/viewspdf/reciboVenta1.php',$data,TRUE); 
        $pdfFilePath = "Venta-".$code.date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
        exit();
    }


    public function downloadPDFCompras($initial, $final)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
            'status' => '1',
            'initial' => $initial,
            'final' => $final
        );
        $html = $this->load->view('backend/viewspdf/compras.php',$data,TRUE); 
        $pdfFilePath = "Compras-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFCompra($code)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        'code' => $code
        );
        $html = $this->load->view('backend/viewspdf/compra.php',$data,TRUE); 
        $pdfFilePath = "Compra-".$code.date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFEntregas()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/entregas.php',$data,TRUE); 
        $pdfFilePath = "Entregas-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFEntrega($code)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        'code' => $code
        );
        $html = $this->load->view('backend/viewspdf/entrega.php',$data,TRUE); 
        $pdfFilePath = "Entrega-".$code.date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFCreditos()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/creditos.php',$data,TRUE); 
        $pdfFilePath = "Creditos-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFCredito($code)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        'code' => $code
        );
        $venta = $this->db->get_where('sales',array('code'=>$code))->row();
        if($venta->FEL==1){
            $html = $this->load->view('backend/viewspdf/fac_cambiaria.php',$data,TRUE); 
        }else{
           $html = $this->load->view('backend/viewspdf/reciboVenta1.php',$data,TRUE); 
        }
        
        $pdfFilePath = "Creditos-".$code.date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function printRecibopago($code)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        'code' => $code
        );
        $html = $this->load->view('backend/viewspdf/recibo_pago_credito.php',$data,TRUE); 
        $pdfFilePath = "Creditos-Recibo ".$code.date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFAlertas()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/alertas.php',$data,TRUE); 
        $pdfFilePath = "Alertas-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFIngresos()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/ingresos.php',$data,TRUE); 
        $pdfFilePath = "Ingresos-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFingreso($ID)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        'ID' => $ID
        );
        $html = $this->load->view('backend/viewspdf/ingreso.php',$data,TRUE); 
        $pdfFilePath = "Ingreso-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }
    
    public function downloadPDFEgresos($status)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => $status
        );
        $state = '';
        if ($status == 1) {
            $state = 'activos';
        }
        elseif ($status == 0) {
            $state = 'anulados';
        }
        $html = $this->load->view('backend/viewspdf/egresos.php',$data,TRUE); 
        $pdfFilePath = "Egresos-".$state."-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFVentasAnuladas()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        );
        $html = $this->load->view('backend/viewspdf/anuladas.php',$data,TRUE); 
        $pdfFilePath = "Anuladas-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFEnvios()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        );
        $html = $this->load->view('backend/viewspdf/envios.php',$data,TRUE); 
        $pdfFilePath = "EnviosPendientes-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");
    }

    public function downloadPDFCashHistory()
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1'
        );
        $html = $this->load->view('backend/viewspdf/cash_history.php',$data,TRUE); 
        $pdfFilePath = "reporte_cortes_caja-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFTraslado($traslado_id)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
            'traslado_id' => $traslado_id
        );
        $html = $this->load->view('backend/viewspdf/traslado.php',$data,TRUE); 
        $pdfFilePath = "Traslado-".date('d-m-Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
        exit();
    }
    
    function printVoucherSale($code)
    {
        $hoy = date("Y-m-d-H-i-s");
        $data['code'] = $code;
        $html = $this->load->view('backend/viewspdf/voucherSale.php',$data,TRUE);
        $pdfFilePath = "voucher-venta-$hoy.pdf";
        $this->load->library('M_pdf');
        $fel      = $this->db->get_where('sales',array('code'=>$code))->row()->FEL;
        $products = $this->db->get_where('product_details',array('activity_ref'=>$code))->num_rows();
        $normal = 110;
        if($fel)
        {
            $normal = 190;
        }
        $height = ($products*15) + $normal;
        
        $mpdf = new mPDF('utf-8', [80,$height], 0, '', 3, 3, 1, 5, 0, 0); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");
    }
    
    function printVoucherShop($code)
    {
        $hoy = date("Y-m-d-H-i-s");
        $data['code'] = $code;
        $html = $this->load->view('backend/viewspdf/voucherShop.php',$data,TRUE);
        $pdfFilePath = "voucher-compra-$hoy.pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8', [58,200], 0, '', 3, 3, 5, 5, 0, 0); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");
    }

    public function downloadPDFIncrease($ini,$final)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        'ini'=>$ini,
        'final'=>$final
        );
        $html = $this->load->view('backend/viewspdf/increases.php',$data,TRUE); 
        $pdfFilePath = "reporte_ingresos_existencia-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFIncreaseDetails($ini,$final)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
        'status' => '1',
        'ini'=>$ini,
        'final'=>$final
        );
        $html = $this->load->view('backend/viewspdf/increases_details.php',$data,TRUE); 
        $pdfFilePath = "reporte_ingresos_existencia-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFIngresoDetalles($increase_id)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
            'increase_id' => $increase_id
        );
        $html = $this->load->view('backend/viewspdf/ingreso_detalles.php',$data,TRUE); 
        $pdfFilePath = "reporte_ingreso_detalles-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFLosseDetails($losse_return_id)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
            'losse_return_id' => $losse_return_id
        );
        $html = $this->load->view('backend/viewspdf/perdida_detalles.php',$data,TRUE); 
        $pdfFilePath = "reporte_perdida_details-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    public function downloadPDFLosseDateDetails($initial, $final)
    {
        $hoy = date('d-m-Y_h:i:s');
        $data = array(
            'initial' => $initial,
            'final' => $final
        );
        $html = $this->load->view('backend/viewspdf/perdida_detalles_fecha.php',$data,TRUE); 
        $pdfFilePath = "reporte_perdida_detalles_fechas-".date('d/m/Y H:i:s').".pdf";
        $this->load->library('M_pdf');
        $mpdf = new mPDF('utf-8','A4'); 
        $mpdf->packTableData = true;
        $mpdf->WriteHTML($html,2);
        $mpdf->Output($pdfFilePath, "I");  
    }

    /* end pdf */
    
    function get_increases($initial,$final) {
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->query("SELECT * FROM `increase` WHERE branch_id = '$branch_id' AND status = 1 AND DATE(datetime) >= DATE('$initial') AND DATE(datetime) <= DATE('$final') ORDER BY datetime ASC");
    }
    
    function get_increases_details($initial,$final) {
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->query("SELECT i.code, i.datetime, i.branch_id, i.responsable, d.products_id, d.amount, d.cost, d.products_id_2 FROM increase AS i INNER JOIN product_details AS d ON i.code = d.activity_ref WHERE  i.status = 1 AND d.status = 1 AND DATE(datetime) >= DATE('$initial') AND DATE(datetime) <= DATE('$final') ORDER BY i.datetime DESC");
    }
    
    function get_increases_total($initial, $final) {
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->query("SELECT SUM(total) AS total FROM increase WHERE branch_id = '$branch_id' AND status = 1 AND DATE(datetime) >= DATE('$initial') AND DATE(datetime) <= DATE('$final')")->row()->total;
    }
    
    function get_losses_products($initial, $final) {
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->query("SELECT * FROM losse_returns WHERE branch_id = '$branch_id' AND status = 1 AND concept = 'Pérdida' AND DATE(datetime) >= DATE('$initial') AND DATE(datetime) <= DATE('$final')");
        // return $this->db->get_where("losse_returns", array('branch_id'=>$branch_id, 'status'=>1, 'concept'=>"Pérdida"));
    }
    
    function get_losses_total($initial, $final) {
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->query("SELECT SUM(loss) AS total FROM losse_returns WHERE branch_id = '$branch_id' AND status = 1 AND concept = 'Pérdida' AND DATE(datetime) >= DATE('$initial') AND DATE(datetime) <= DATE('$final')")->row()->total;
    }
    
    function get_expired_products() {
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->query("SELECT * FROM `lotes` WHERE status = 1 AND DATE(fecha_vencimiento) <= DATE(NOW()) AND branch_id = '$branch_id' AND existencia > 0 ORDER BY fecha_vencimiento ASC");
        // return $this->db->get_where("losse_returns", array('branch_id'=>$branch_id, 'status'=>1, 'concept'=>"Pérdida"));
    }
    
    function get_expired_total() {
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->query("SELECT SUM(IFNULL(existencia,0) * IFNULL(precio,0)) AS total FROM `lotes` WHERE status = 1 AND DATE(fecha_vencimiento) <= DATE(NOW()) AND branch_id = '$branch_id' AND existencia > 0 ORDER BY fecha_vencimiento ASC")->row()->total;
    }
    
    function get_expired_products_dates($initial, $final) {
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->query("SELECT * FROM `lotes` WHERE status = 1 AND DATE(fecha_vencimiento) >= DATE('$initial') AND DATE(fecha_vencimiento) <= DATE('$final') AND branch_id = '$branch_id' AND existencia > 0 ORDER BY fecha_vencimiento ASC");
        // return $this->db->get_where("losse_returns", array('branch_id'=>$branch_id, 'status'=>1, 'concept'=>"Pérdida"));
    }
    
    function get_expired_total_dates($initial, $final) {
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->query("SELECT SUM(IFNULL(existencia,0) * IFNULL(precio,0)) AS total FROM `lotes` WHERE status = 1 AND DATE(fecha_vencimiento) >= DATE('$initial') AND DATE(fecha_vencimiento) <= DATE('$final') AND DATE(fecha_vencimiento) <= DATE(NOW()) AND branch_id = '$branch_id' AND existencia > 0 ORDER BY fecha_vencimiento ASC")->row()->total;
    }
    
    
    
    function get_transports()
    {
        return $this->db->get_where('transport', array('status<>' => '0'));
    }

    function get_transportservices()
    {
        return $this->db->get('service_transport');
    }
    
    function create_transport()
    {
        $data['name']           = $this->input->post('name_transport');
        $data['license_plate']  = $this->input->post('license_plate');
        $data['km']             = $this->input->post('km');
        $data['responsable']    = $this->input->post('responsable');
        $data['next_service']   = date("Y-m-d", strtotime($this->input->post('next_service')));
        $data['notes']          = $this->input->post('notes');
        $data['status']         = 1;
        $data['branch_id']      = $this->session->userdata('branch_id');
        $this->db->insert('transport', $data);
        $message = 'Ha ingresado un nuevo transporte denominado '.$this->input->post('name_transport');
        $this->insert_binnacle($message);
        $message2 = 'Ha ingresado un nuevo transporte denominado '.$this->input->post('name_transport').', y su proximo servicio es en la fecha: '.$this->input->post('next_service');
        $this->insert_notification($message2, base64_encode('admin/transporte/'), 'transportes', 'Transporte_servicio');
    }
    
    
    function update_transport($id_tr)
    {
        $data['name']           = $this->input->post('name_transport');
        $data['license_plate']  = $this->input->post('license_plate');
        $data['km']             = $this->input->post('km');
        $data['responsable']    = $this->input->post('responsable');
        $data['next_service']   = date("Y-m-d", strtotime($this->input->post('next_service')));
        $data['notes']          = $this->input->post('notes');
        $this->db->where('transport_id', $id_tr);
        $this->db->update('transport', $data);
        
        $message = 'Ha actualizado el transporte con ID: '.$id_tr;
        $this->insert_binnacle($message);
        
    }
    
    function delete_transport($id_tr)
    {
        $data['status']        = 0;
        $this->db->where('transport_id', $id_tr);
        $this->db->update('transport', $data);
        
        $message = 'Ha elimado el transporte con ID '.$id_tr;
        $this->insert_binnacle($message);
    }
    
    
    function get_services_transports($id_transport)
    {
        return $this->db->get_where('service_transport', array('transport_id' => $id_transport));
    }
    
    
    function get_accounts_bank()
    {
        return $this->db->get_where('account_bank', array('status' => '1'));
    }

    function get_accounts_to_pay()
    {
        $branch_id = $this->session->userdata('branch_id');
        $this->db->where('status', 1);
        $this->db->group_start();
        $this->db->where('bank_id !=', 0);
        $this->db->or_where('bank_id IS NULL', NULL, FALSE);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('branch_id', $branch_id);
        $this->db->or_where('branch_id', 0);
        $this->db->group_end();
        return $this->db->get('account_bank');
    }
    
    function get_expenses()
    {
        return $this->db->order_by('datetime', 'DESC')->get_where('expense', array('status' => '1', 'branch_id' => $this->session->userdata('branch_id')));
    }
    
    function expenses_canceled()
    {
        return $this->db->order_by('datetime', 'DESC')->get_where('expense', array('status' => '0', 'branch_id' => $this->session->userdata('branch_id')));
    }
    
    
    function create_service($transport_id)
    {
        $branch_id = $this->session->userdata('branch_id');
        $datetime = date('Y-m-d H:i:s');
        $data['transport_id']   = $transport_id;
        $data['date']           = date("Y-m-d", strtotime($this->input->post('fecha')));
        $data['datetime']       = $datetime;
        $data['next_service']   = date("Y-m-d", strtotime($this->input->post('fecha_prox')));
        $data['price']          = $this->input->post('precio');
        $data['responsable']    = $this->input->post('responsable');
        $data['place_service']  = $this->input->post('provider');
        $data['details']        = $this->input->post('details');
        $data['payment_method'] = $this->input->post('origin');
        $data['account_bank_id'] = $this->input->post('account_bank');
        $this->db->insert('service_transport', $data);
        $data33['reference_id'] = $this->db->insert_id();
        $data33['table_reference'] = 'service_transport';
         
        $data22['next_service']  = date("Y-m-d", strtotime($this->input->post('fecha_prox')));
        $this->db->where('transport_id', $transport_id);
        $this->db->update('transport', $data22);
       
        $message    = 'Ha ingresado un nuevo detalle de servicio para el transporte con ID '.$transport_id.' y con Precio: '.$this->input->post('precio');
        $this->insert_binnacle($message);
         
        $data33['date']         = date("Y-m-d", strtotime($this->input->post('fecha')));
        $data33['amount']       = $this->input->post('precio');
        $data33['responsable']  = $this->input->post('responsable');
        $data33['provider']     = $this->input->post('provider');

        if ($this->input->post('origin') == 0) {
            $cuenta = $this->db->get_where('account_bank', array('bank_id'=>0,'status'=>1,'branch_id'=>$branch_id))->row_array();
            $data33['origin']       = 0;
        }
        else{
            $cuenta = $this->db->get_where('account_bank', array('account_bank_id'=>$this->input->post('account_bank')))->row_array();
            $data33['origin']       = $this->input->post('account_bank');
        }
        $data33['saldo_inicial'] = $cuenta['current_balance'];
        $saldo = $cuenta['saldo_inicial'] - $this->input->post('precio');
        $data33['nuevo_saldo'] = $saldo;
        $data44['current_balance'] = $saldo;

        $this->db->where('account_bank_id', $cuenta['account_bank_id']);
        $this->db->update('account_bank', $data44);

        $data33['details']   = 'Egreso por servicio de vehículo de transporte';
        $data33['datetime']  = $datetime;
        $data33['admin_id']  = $this->session->userdata('login_user_id');
        $data33['branch_id'] = $branch_id;
        $data33['week']      = date('W');
        $data33['month']     = date('m');
        $data33['year']      = date('Y');
        
        $this->db->insert('expense', $data33);
    }
    
    
    function create_account_bank()
    {
        if($this->input->post('bank') < 0 && $this->input->post('name_bank') != ''){
            $bank['name']= $this->input->post('name_bank');
            $this->db->insert('bank', $bank);    
            $bank_id = $this->db->insert_id();
        } else{
            $bank_id = $this->input->post('bank');
        }
        
        $data['bank_id']         = $bank_id;
        $data['branch_id']       = $this->input->post('branch');
        $data['name_account']    = $this->input->post('property');
        $data['no_account']      = $this->input->post('no_account');
        $data['type']            = $this->input->post('type_account');
        $data['currency']        = $this->input->post('currency');
        $data['current_balance'] = $this->input->post('current_balance');
        $this->db->insert('account_bank', $data);
        
        $message    = 'Ha ingresado una nueva cuenta de banco denominada '.$this->input->post('property').' del banco con ID: '.$bank_id;
        $this->insert_binnacle($message);

        $this->insert_notification($message, base64_encode('admin/cuentas/bancarias/'), 'cuentas_bancarias', 'Cuenta_bancaria');
    }
    
    
    function update_account_bank($account_id)
    {
        $data['name_account']    = $this->input->post('property');
        $data['no_account']      = $this->input->post('no_account');
        $data['branch_id']       = $this->input->post('branch');
        $data['current_balance'] = $this->input->post('sld');
        $this->db->where('account_bank_id', $account_id);
        $this->db->update('account_bank', $data);
        
        $message    = 'Ha modificado la cuenta bancaria registrada con ID: '.$account_id;
        $this->insert_binnacle($message);
    }
    
    function delete_account_bank($account_id)
    {
        $data['status']         = 0;
        $this->db->where('account_bank_id', $account_id);
        $this->db->update('account_bank', $data);
        
        $message    = 'Ha desactivado la cuenta bancaria registrada con ID: '.$account_id;
        $this->insert_binnacle($message);
    }
    
    function transfer_transaction_bank($account_id)
    {
        $balance1 = $this->db->get_where('account_bank', array('account_bank_id' => $account_id))->row()->current_balance;
        $balance2 = $this->db->get_where('account_bank', array('account_bank_id' => $this->input->post('destiny')))->row()->current_balance;
        
        if($this->input->post('amount') <= $balance1)
        {
            $data['date_time']              = date('Y-m-d H:i:s');
            $data['account_origin']         = $account_id;
            $data['account_destiny']        = $this->input->post('destiny');
            $data['amount']                 = $this->input->post('amount');
            $data['responsable']            = $this->session->userdata('login_user_id');
            $this->db->insert('transaction_accounts', $data);
            
            $data2['current_balance']         = $balance2 + $this->input->post('amount');
            if ($this->input->post('destiny') > 0) {
                $this->db->where('account_bank_id', $this->input->post('destiny'));
            } else {
                $this->db->where('bank_id', 0);
                $this->db->where('branch_id', $this->session->userdata('branch_id'));
            }
            $this->db->update('account_bank', $data2);
            
            $data3['current_balance']         = $balance1 - $this->input->post('amount');
            $this->db->where('account_bank_id', $account_id);
            $this->db->update('account_bank', $data3);
            
            $message    = 'Ha hecho un registro de transacción entre dos cuentas bancarias Origen ID: '.$account_id.' a la cuenta Destino ID: '.$this->input->post('destiny').' la cantidad de: '.$this->input->post('amount');
            $this->insert_binnacle($message);
        }
    }

    function get_admin()
    {   
        $admins = $this->db->get_where('admin')->result_array(); 
        $responsables = array();
       foreach($admins as $admin) {

           $sucursales = unserialize($admin['sucursal']);

           if(in_array($this->session->userdata('branch_id'), $sucursales))
           array_push($responsables,$admin);
       }

       return $responsables;
    }
    
    function update_settings()
    {
        $md5 = md5(date('d-m-y H:i:s'));
        if($_FILES['logo']['tmp_name'] != ''){
                
            $logo = $this->db->get_where('settings',array('type'=>'logo'))->row()->description;
            $data['description'] = $md5.str_replace(' ', '', $_FILES['logo']['name']);

            move_uploaded_file($_FILES["logo"]["tmp_name"], "uploads/img/". $md5.str_replace(' ', '', $_FILES['logo']['name']));
            
            $this->db->where('type', 'logo' );
            $this->db->update('settings', $data);

            unlink("uploads/img/".$logo);
        }


        $data['description'] = $this->input->post('theme');
        $this->db->where('type', 'theme' );
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('name');
        $this->db->where('type', 'name' );
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('slogan');
        $this->db->where('type', 'slogan' );
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('phone');
        $this->db->where('type', 'phone' );
        $this->db->update('settings', $data);
        
        $data['description'] = $this->input->post('regimen');
        $this->db->where('type', 'regimen' );
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('email');
        $this->db->where('type', 'email' );
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('cierre');
        $this->db->where('type', 'cierre' );
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('corte');
        $this->db->where('type', 'corte' );
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('monto_limite');
        $this->db->where('type', 'monto_limite');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('moneda');
        $this->db->where('type', 'moneda');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('horario_limite');
        $this->db->where('type', 'horario_limite' );
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('address');
        $this->db->where('type', 'address');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('planilla');
        $this->db->where('type', 'planilla');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('meta_mensual');
        $this->db->where('type', 'meta_mensual');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('noti_email');
        $this->db->where('type', 'noti_email');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('whatsapp');
        $this->db->where('type', 'whatsapp');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('code');
        $this->db->where('type', 'code');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('vencimiento');
        $this->db->where('type', 'vencimiento');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('mayorista');
        $this->db->where('type', 'mayorista');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('FEL');
        $this->db->where('type', 'FEL');
        $this->db->update('settings', $data);
        
        $data['description'] = str_replace('-','', $this->input->post('nit'));
        $this->db->where('type', 'nit');
        $this->db->update('settings', $data);
        
        $data['description'] = $this->input->post('name_fel');
        $this->db->where('type', 'name_fel');
        $this->db->update('settings', $data);
        
        $data['description'] = $this->input->post('jwt');
        $this->db->where('type', 'jwt');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('nombreComercial');
        $this->db->where('type', 'nombreComercial');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('direccionemisor');
        $this->db->where('type', 'direccionemisor');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('codigoPostal');
        $this->db->where('type', 'codigoPostal');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('municipio');
        $this->db->where('type', 'municipio');
        $this->db->update('settings', $data);

        $data['description'] = $this->input->post('departamento');
        $this->db->where('type', 'departamento' );
        $this->db->update('settings', $data);

        $message = 'Ha actualizado la configuración del sistema';
        $this->insert_binnacle($message);
        
    }

    function get_info($type = '')
    {
        return $this->db->get_where('settings', array('type' => $type))->row()->description;
    }

    function create_box_cut()
    {
        $data['user_id']          = $this->session->userdata('login_user_id');
        $data['branch_id']        = $this->session->userdata('branch_id');
        $data['date_close']       = date('Y-m-d');
        $data['time_close']       = date('H:i:s');
        $data['amount_initial']   = $this->input->post('caja_actual');
        $amount_initial   = $this->input->post('caja_actual');
        $data['amount_final']     = $this->input->post('caja_nueva');
        $amount_final     = $this->input->post('caja_nueva');
        $data['difference_cash']  = $this->input->post('diferencia_cash');
        $difference_cash  = $this->input->post('diferencia_cash');
        $data['difference_day']   = $this->input->post('diferencia_day');
        $data['notes']            = trim($this->input->post('notes'));
        $this->db->insert('cash_history', $data);
        
        $data2['current_balance']     = ($amount_initial + $amount_final)-$difference_cash ;
        $this->db->where('bank_id','0');
        $this->db->where('branch_id',$this->session->userdata('branch_id'));
        $this->db->update('account_bank', $data2);
        
        $message = 'Ha registrado el corte de caja del día de hoy: '.date('Y-m-d');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/historial_cortes/'), 'ver_cortes', 'Corte_caja');
    }
    
    function create_box_cut_date()
    {
        $data['user_id']          = $this->session->userdata('login_user_id');
        $data['branch_id']        = $this->session->userdata('branch_id');
        $data['date_close']       = $this->input->post('date');
        $data['time_close']       = date('H:i:s');
        $data['amount_initial']   = $this->input->post('caja_actual');
        $amount_initial   = $this->input->post('caja_actual');
        $data['amount_final']     = $this->input->post('caja_nueva');
        $amount_final     = $this->input->post('caja_nueva');
        $data['difference_cash']  = $this->input->post('diferencia_cash');
        $difference_cash  = $this->input->post('diferencia_cash');
        $data['difference_day']   = $this->input->post('diferencia_day');
        $data['notes']            = trim($this->input->post('notes'));
        $this->db->insert('cash_history', $data);
        
        $data2['current_balance']     = ($amount_initial + $amount_final)-$difference_cash ;
        $this->db->where('bank_id','0');
        $this->db->where('branch_id',$this->session->userdata('branch_id'));
        $this->db->update('account_bank', $data2);
        
        $message = 'Ha registrado el corte de caja del día de hoy: '.date('Y-m-d');
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/historial_cortes/'), 'ver_cortes', 'Corte_caja');
    }
    
    function get_vencimiento($product_id, $branch_id = '')
    {
        if ($branch_id == '') {
            $this->db->select('expiration');
            $this->db->where('products_id',$product_id);
            $this->db->group_start();
            $this->db->where('type',1);
            $this->db->or_where('type',2);
            $this->db->group_end();
            $this->db->where('status',1);
            $this->db->order_by('product_details_id', 'DESC');
            $this->db->limit(1);
            return $this->db->get_where('product_details', array('expiration !='=>'0000-00-00'))->row()->expiration;
        }
        else {
            $this->db->select('expiration');
            $this->db->where('products_id',$product_id);
            $this->db->where('branch_id',$branch_id);
            $this->db->group_start();
            $this->db->where('type',1);
            $this->db->or_where('type',2);
            $this->db->group_end();
            $this->db->where('status',1);
            $this->db->order_by('product_details_id', 'DESC');
            $this->db->limit(1);
            return $this->db->get_where('product_details', array('expiration !='=>'0000-00-00'))->row()->expiration;
        }
    }
    
    function codigos_update()
    {
        $entries = sizeof($this->input->post('admin_id'));
        $_id =$this->input->post('admin_id');
        
        for($i = 0; $i < $entries; $i++) {
            
            log_message('error', $_id[$i]);
            
            $data['code_authorization'] = $this->input->post('code_authorization'.$_id[$i]);
            $data['anulacion']          = $this->input->post('anulacion'.$_id[$i]);
            $data['descuentos']         = $this->input->post('descuentos'.$_id[$i]);
            $data['compras']            = $this->input->post('compras'.$_id[$i]);
            $data['cambios']            = $this->input->post('cambios'.$_id[$i]);
            $data['cierre']             = $this->input->post('cierre'.$_id[$i]);
            $data['configuracion']      = $this->input->post('configuracion'.$_id[$i]);
            $data['eliminar_pagos']     = $this->input->post('eliminar_pagos'.$_id[$i]);
        
            $this->db->where('admin_id', $_id[$i]);
            $this->db->update('admin', $data);

            $message = 'Ha actualizado códigos de autorización.';
            $this->insert_binnacle($message);
            $this->insert_notification($message, base64_encode('admin/codigos/'), 'codigos', 'Códigos', $_id[$i]);
        }

        $message = 'Ha actualizado códigos de autorización.';
        $this->insert_binnacle($message);
        $this->insert_notification($message, base64_encode('admin/codigos/'), 'codigos', 'Códigos');
    }

    public function bulkProducts()
    {
        $path   = $_FILES["prod"]["tmp_name"];
        $object = PHPExcel_IOFactory::load($path);
        foreach($object->getWorksheetIterator() as $worksheet)
        {
           $highestRow = $worksheet->getHighestRow();
           $highestColumn = $worksheet->getHighestColumn();
           for($row=2; $row <= $highestRow; $row++)
           {                     
                $data['code']    =  $worksheet->getCellByColumnAndRow(0, $row)->getValue();
                $data['name']     =  $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                $data['category']         =  $worksheet->getCellByColumnAndRow(2, $row)->getValue();
                $data['provider']         =  $worksheet->getCellByColumnAndRow(3, $row)->getValue();
                $data['mark']         =  $worksheet->getCellByColumnAndRow(4, $row)->getValue();
                $data['stock_bodega']           =  $worksheet->getCellByColumnAndRow(5, $row)->getValue();
                $data['cost']      =  $worksheet->getCellByColumnAndRow(6, $row)->getValue();
                $data['precio_mayorista']      =  $worksheet->getCellByColumnAndRow(7, $row)->getValue();
                $data['price']       =  $worksheet->getCellByColumnAndRow(8, $row)->getValue();
                $data['status']       =1;
                //$data['since']         =  $this->crud->getDateFormat();
                if($data['name'] != "")
                {
                    $this->db->insert('products',$data);
                    $products_id = $this->db->insert_id();
                    $data2['products_id']   =   $products_id;
                    $data2['user_id']    =   1;
                    $data2['type']      =   1;
                    $data2['branch_id']      =   0;
                    $data2['amount']      =  $data['stock_bodega'];
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
    
    
    public function get_stock_compare($cant)
    {
        if($cant != '')
        {
            $ex = explode('|',$cant);
            
            $ex[0];
            $ex[1];
            $producto = $this->db->get_where('products',array('products_id'=>$ex[1]))->row();
                if($producto->presentation == 'Caja'){
                    $total = $this->crud_model->get_stock($producto->id_prod_matriz, $this->session->userdata('branch_id'));
                    $stock_inventory = ($total/$producto->cnt_prod_matriz); 
                    //$tot_bodega= $this->crud_model->get_stock($producto->id_prod_matriz, 0);
                    //$stock_bodega = ($tot_bodega/$producto->cnt_prod_matriz);
                }else{
                    $stock_inventory  = $this->crud_model->get_stock($ex[1], $this->session->userdata('branch_id'));
                    //$stock_bodega = $this->crud_model->get_stock($row->products_id, 0);
                }
           //$query = $this->get_stock($ex[1],$this->session->userdata('branch_id'));
           if ($ex[0] <= $stock_inventory) 
           {
               return 'success'; 
           }
           else
           {
                return $ex[1]; 
           }
        }
        else
        {
           return '0'; 
        }
    }
    
    function get_marks_branch() {
        $branch_id = $this->session->userdata('branch_id');
        return $this->db->order_by('name', 'ASC')->get_where('mark', array('branch_id'=>$branch_id, "status"=>1));
    }

    function update_iva_details() {
        log_message("error", "Iva--------------------------------------------------");
        $prods = $this->db->get_where('products', array('status'=>1))->result_array();
        foreach($prods as $pd) {
            $iva = 0; log_message("error", "Prod ID: ".$pd['products_id'].", IVA: ".$pd['iva']);
            if (!$pd['iva']) $iva = 0;
            else $iva = 1;
            $data['iva'] = $iva;
            $this->db->where('status', 1);
            $this->db->where('products_id', $pd['products_id']);
            $this->db->update('product_details', $data);
        }
    }
    
    function get_sales_product($initial, $final, $products_id = '') {
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT date, code, client_id, num_products AS amount, total, my, products FROM sales WHERE DATE(date) >= DATE('$initial') AND DATE(date) <= DATE('$final') AND status = 1 AND branch_id = '$branch_id' AND estado IN (1,2) ORDER BY date DESC";
        if ($products_id != '') $query = "SELECT s.date, s.code, s.client_id, SUM(d.amount) AS amount, SUM(IFNULL((d.price/d.amount),d.cost)) AS price, SUM(d.amount * IFNULL((d.price/d.amount), d.cost)) AS total, s.my, d.products_id, d.products_id_2 FROM sales AS s INNER JOIN product_details AS d ON s.code = d.activity_ref WHERE d.products_id = '$products_id' AND DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.status = 1 AND s.branch_id = '$branch_id' AND s.estado IN (1,2) AND d.status = 1 GROUP BY d.activity_ref, d.products_id_2 ORDER BY s.date DESC";
        $data = $this->db->query($query);
        return $data;
    }
    
    function get_totals_sales_products($initial, $final, $products_id = '') {
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT COUNT(sales_id) AS contador, SUM(total) AS total, SUM(num_products) AS productos FROM sales WHERE DATE(date) >= DATE('$initial') AND DATE(date) <= DATE('$final') AND status = 1 AND branch_id = '$branch_id' AND estado IN (1,2) ORDER BY date DESC";
        if ($products_id != '') $query = "SELECT COUNT(DISTINCT(d.activity_ref)) AS contador, SUM(d.amount * IFNULL(d.price, d.cost)) AS total, SUM(d.amount) AS productos FROM sales AS s INNER JOIN product_details AS d ON s.code = d.activity_ref WHERE d.products_id = '$products_id' AND DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.status = 1 AND s.branch_id = '$branch_id' AND s.estado IN (1,2) AND d.status = 1 ORDER BY s.date DESC";
        $data = $this->db->query($query)->row_array();
        return $data;
    }
    
    function get_sales_mark($initial, $final, $mark_id = '') {
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT date, code, client_id, num_products AS amount, total, my, products FROM sales WHERE DATE(date) >= DATE('$initial') AND DATE(date) <= DATE('$final') AND status = 1 AND branch_id = '$branch_id' AND estado IN (1,2) ORDER BY date DESC";
        if ($mark_id != '') $query = "SELECT s.date, s.code, s.client_id, SUM(d.amount) AS amount, SUM(IFNULL((d.price/d.amount),d.cost)) AS price, SUM(d.amount * IFNULL((d.price/d.amount), d.cost)) AS total, s.my, d.products_id, d.products_id_2, p.mark FROM sales AS s INNER JOIN product_details AS d ON s.code = d.activity_ref INNER JOIN products AS p ON d.products_id = p.products_id WHERE p.mark = '$mark_id' AND DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.status = 1 AND s.branch_id = '$branch_id' AND s.estado IN (1,2) AND d.status = 1 GROUP BY d.activity_ref, d.products_id_2 ORDER BY s.date DESC";
        $data = $this->db->query($query);
        return $data;
    }
    
    function get_totals_sales_marks($initial, $final, $mark_id = '') {
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT COUNT(sales_id) AS contador, SUM(total) AS total, SUM(num_products) AS productos FROM sales WHERE DATE(date) >= DATE('$initial') AND DATE(date) <= DATE('$final') AND status = 1 AND branch_id = '$branch_id' AND estado IN (1,2) ORDER BY date DESC";
        if ($mark_id != '') $query = "SELECT COUNT(DISTINCT(d.activity_ref)) AS contador, SUM(d.amount * IFNULL(d.price, d.cost)) AS total, SUM(d.amount) AS productos FROM sales AS s INNER JOIN product_details AS d ON s.code = d.activity_ref INNER JOIN products AS p ON d.products_id = p.products_id WHERE p.mark = '$mark_id' AND DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.status = 1 AND s.branch_id = '$branch_id' AND s.estado IN (1,2) AND d.status = 1 ORDER BY s.date DESC";
        $data = $this->db->query($query)->row_array();
        return $data;
    }
    
    function get_sales_affects($initial, $final) {
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT s.date, s.code, s.client_id, SUM(d.amount) AS amount, SUM(IFNULL(d.price,d.cost)) AS price, SUM(d.amount * (IFNULL(d.price, d.cost))/d.amount) AS total, s.my, s.products FROM sales AS s INNER JOIN product_details AS d ON s.code = d.activity_ref WHERE d.iva = 1 AND DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.status = 1 AND s.branch_id = '$branch_id' AND s.estado IN (1,2) AND d.status = 1 GROUP BY d.activity_ref ORDER BY s.date DESC";
        $data = $this->db->query($query);
        return $data;
    }
    
    function get_totals_sales_affects($initial, $final) {
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT COUNT(DISTINCT(d.activity_ref)) AS contador, SUM(d.amount * (IFNULL(d.price, d.cost))/d.amount) AS total, SUM(d.amount) AS productos FROM sales AS s INNER JOIN product_details AS d ON s.code = d.activity_ref WHERE d.iva = 1 AND DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.status = 1 AND s.branch_id = '$branch_id' AND s.estado IN (1,2) AND d.status = 1 ORDER BY s.date DESC";
        $data = $this->db->query($query)->row_array();
        return $data;
    }
    
    function get_sales_exents($initial, $final) {
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT s.date, s.code, s.client_id, SUM(d.amount) AS amount, SUM(IFNULL(d.price,d.cost)) AS price, SUM(d.amount * (IFNULL(d.price, d.cost))/d.amount) AS total, s.my, s.products FROM sales AS s INNER JOIN product_details AS d ON s.code = d.activity_ref WHERE (d.iva = 0 OR d.iva IS NULL OR d.iva = '') AND DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.status = 1 AND s.branch_id = '$branch_id' AND s.estado IN (1,2) AND d.status = 1 GROUP BY d.activity_ref, d.products_id_2 ORDER BY s.date DESC";
        $data = $this->db->query($query);
        return $data;
    }
    
    function get_totals_sales_exents($initial, $final) {
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT COUNT(DISTINCT(d.activity_ref)) AS contador, SUM(d.amount * (IFNULL(d.price, d.cost))/d.amount) AS total, SUM(d.amount) AS productos FROM sales AS s INNER JOIN product_details AS d ON s.code = d.activity_ref WHERE (d.iva = 0 OR d.iva IS NULL OR d.iva = '') AND DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.status = 1 AND s.branch_id = '$branch_id' AND s.estado IN (1,2) AND d.status = 1 ORDER BY s.date DESC";
        $data = $this->db->query($query)->row_array();
        return $data;
    }
    
    function get_accounts_receivable($initial, $final, $clients_id = '') {
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT s.* FROM sales AS s LEFT JOIN credit_details AS c ON s.sales_id = c.sales_id WHERE DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.estado = 1 AND (s.status = 1 OR c.status = 1) GROUP BY s.sales_id ORDER BY s.date DESC";
        if ($clients_id != '') $query = "SELECT s.* FROM sales AS s LEFT JOIN credit_details AS c ON s.sales_id = c.sales_id WHERE s.client_id = '$clients_id' AND DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.estado = 1 AND (s.status = 1 OR c.status = 1) GROUP BY s.sales_id ORDER BY s.date DESC";
        $data = $this->db->query($query);
        return $data;
    }
    
    function get_totals_accounts_receivable($initial, $final, $clients_id = '') {
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT SUM(s.total) AS total, COUNT(DISTINCT(s.sales_id)) AS contador, SUM(IFNULL(c.amount,0)) AS pagado, (SUM(s.total) - SUM(IFNULL(c.amount,0))) AS pendiente FROM sales AS s LEFT JOIN credit_details AS c ON s.sales_id = c.sales_id WHERE DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.estado = 1 AND (s.status = 1 OR c.status = 1) ORDER BY s.date DESC";
        if ($clients_id != '') $query = "SELECT SUM(s.total) AS total, COUNT(DISTINCT(s.sales_id)) AS contador, SUM(IFNULL(c.amount,0)) AS pagado, (SUM(s.total) - SUM(IFNULL(c.amount,0))) AS pendiente FROM sales AS s LEFT JOIN credit_details AS c ON s.sales_id = c.sales_id WHERE s.client_id = '$clients_id' AND DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.estado = 1 AND (s.status = 1 OR c.status = 1) ORDER BY s.date DESC";
        $data = $this->db->query($query)->row_array();
        return $data;
    }
    
    function get_accounts_payable($initial, $final, $provider_id = '') {
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT s.* FROM shopping AS s LEFT JOIN credit_details AS c ON s.shopping_id = c.sales_id WHERE DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.credito = 1 AND (s.status = 1 OR c.status = 1) GROUP BY s.shopping_id ORDER BY s.date DESC";
        if ($provider_id != '') $query = "SELECT s.* FROM shopping AS s LEFT JOIN credit_details AS c ON s.shopping_id = c.sales_id WHERE s.provider = '$provider_id' AND DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.credito = 1 AND (s.status = 1 OR c.status = 1) GROUP BY s.shopping_id ORDER BY s.date DESC";
        $data = $this->db->query($query);
        return $data;
    }
    
    function get_totals_accounts_payable($initial, $final, $provider_id = '') {
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT SUM(s.total) AS total, COUNT(DISTINCT(s.shopping_id)) AS contador, SUM(IFNULL(c.amount,0)) AS pagado, (SUM(s.total) - SUM(IFNULL(c.amount,0))) AS pendiente FROM shopping AS s LEFT JOIN credit_details AS c ON s.shopping_id = c.sales_id WHERE DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.credito = 1 AND (s.status = 1 OR c.status = 1) ORDER BY s.date DESC";
        if ($provider_id != '') $query = "SELECT SUM(s.total) AS total, COUNT(DISTINCT(s.shopping_id)) AS contador, SUM(IFNULL(c.amount,0)) AS pagado, (SUM(s.total) - SUM(IFNULL(c.amount,0))) AS pendiente FROM shopping AS s LEFT JOIN credit_details AS c ON s.shopping_id = c.sales_id WHERE s.provider = '$provider_id' AND DATE(s.date) >= DATE('$initial') AND DATE(s.date) <= DATE('$final') AND s.credito = 1 AND (s.status = 1 OR c.status = 1) ORDER BY s.date DESC";
        $data = $this->db->query($query)->row_array();
        return $data;
    }
    
    function get_sales_seller($initial, $final, $vendedor_id = '') {
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT * FROM sales WHERE DATE(date) >= DATE('$initial') AND DATE(date) <= DATE('$final') AND status = 1 AND branch_id = '$branch_id' AND estado IN (1,2) ORDER BY date DESC";
        if ($vendedor_id != '') $query = "SELECT * FROM sales WHERE DATE(date) >= DATE('$initial') AND DATE(date) <= DATE('$final') AND status = 1 AND branch_id = '$branch_id' AND (responsable = '$vendedor_id' OR user_sale = '$vendedor_id') AND estado IN (1,2) ORDER BY date DESC";
        $data = $this->db->query($query);
        return $data;
    }
    
    function get_totals_sales_seller($initial, $final, $vendedor_id = '') {
        log_message('error','vendedor_id: '.$vendedor_id);
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT COUNT(sales_id) AS contador, SUM(total) AS total, SUM(num_products) AS productos FROM sales WHERE DATE(date) >= DATE('$initial') AND DATE(date) <= DATE('$final') AND status = 1 AND branch_id = '$branch_id' AND estado IN (1,2) ORDER BY date DESC";
        if ($vendedor_id != '') $query = "SELECT COUNT(sales_id) AS contador, SUM(total) AS total, SUM(num_products) AS productos FROM sales WHERE DATE(date) >= DATE('$initial') AND DATE(date) <= DATE('$final') AND status = 1 AND branch_id = '$branch_id' AND (responsable = '$vendedor_id') AND estado IN (1,2) ORDER BY date DESC";
        $data = $this->db->query($query)->row_array();
        return $data;
    }

    function get_totals_clients_user($initial, $final) {
        $branch_id = $this->session->userdata('branch_id');
        $query = "SELECT COUNT(client_id) AS contador FROM client WHERE DATE(date) >= DATE('$initial') AND DATE(date) <= DATE('$final') AND status = 1 AND branch_id = '$branch_id'";
        $data = $this->db->query($query)->row_array();
        return $data;
    }
    
    function get_products_traslado($id) {
        return $this->db->query("SELECT *, SUM(amount) AS total FROM product_details WHERE id_traslado = '$id' AND estado != 0 AND type != 0 GROUP BY products_id");
    }
    
    function echo_stock_lote($lote_id){
        echo $this->get_stock_lote($lote_id);
    }
    
    function get_stock_lote($lote_id) {
        $stock = $this->db->get_where('lotes', array('lote_id'=>$lote_id))->row()->existencia;
        return $stock;
    }
    
    function fixedDetailsCostProds() {
        log_message("error", "--------------------------------------------------------");
        $info = $this->db->query("SELECT d.*, p.cnt_prod_matriz FROM product_details AS d INNER JOIN products AS p ON d.products_id_2 = p.products_id WHERE d.type = 0 AND d.status = 1 AND d.description = 'Venta' AND d.products_id_2 > 0")->result_array();
        foreach($info as $dt) {
            $cost = $dt['cost'];
            log_message("error", "Product_details_id: ".$dt['product_details_id'].'--------');
            log_message("error", "Cost: $cost");
            log_message("error", "Cnt_prod_matriz: ".$dt['cnt_prod_matriz']);
            if ($dt['cnt_prod_matriz'] > 0) $cost = $dt['cost'] / $dt['cnt_prod_matriz'];
            log_message("error", "Cost: $cost");
            $data['cost'] = $cost;
            $this->db->where('product_details_id', $dt['product_details_id']);
            $this->db->update('product_details', $data);
            
        }
    }
    
    function fixedDetailsCostTransferProds() {
        log_message("error", "--------------------------------------------------------");
        $info = $this->db->query("SELECT d.*, p.cnt_prod_matriz FROM product_details AS d INNER JOIN products AS p ON d.products_id_2 = p.products_id WHERE d.status = 1 AND d.description LIKE 'Traslado%' AND d.products_id_2 > 0 AND DATE(d.date) < DATE(NOW())")->result_array();
        foreach($info as $dt) {
            $cost = $dt['cost'];
            log_message("error", "Product_details_id: ".$dt['product_details_id'].'--------');
            log_message("error", "Cost: $cost");
            log_message("error", "Cnt_prod_matriz: ".$dt['cnt_prod_matriz']);
            if ($dt['cnt_prod_matriz'] > 0) $cost = $dt['cost'] / $dt['cnt_prod_matriz'];
            log_message("error", "Cost: $cost");
            $data['cost'] = $cost;
            $this->db->where('product_details_id', $dt['product_details_id']);
            $this->db->update('product_details', $data);
            
        }
    }
    function fixedProductLotes() {
        // $branch_id = $this->session->userdata('branch_id');
        $branch_id = 0;
        echo "Branch ID: $branch_id.<br>"; $contador = 0;
        $prods = $this->db->get_where('products', array('status'=>1, 'presentation'=>'Unidad'))->result_array();
        foreach($prods as $pd) {
            $stock = $this->get_stock($pd['products_id'], $branch_id);
            $lotes = $this->db->query("SELECT SUM(existencia) AS total FROM lotes WHERE id_producto = '".$pd['products_id']."' AND status = 1 AND branch_id = '$branch_id'")->row()->total;
            if ($stock != $lotes) {
                echo "Producto: ------------------------------------------<br>";
                echo "ID: ".$pd['products_id'].", Nombre: ".$pd['name']."<br>";
                echo "Stock: $stock, Lotes: $lotes.<br>";
                if ($stock > $lotes) {
                    echo "Falta stock.<br>";
                    $diferencia = $stock - $lotes;
                    echo "Diferencia: $diferencia.<br>";
                    $desc = $this->db->order_by("lote_id", "DESC")->get_where("lotes", array('id_producto'=>$pd['products_id'], 'status'=>1, 'branch_id'=>$branch_id))->result_array();
                    foreach($desc as $lt) {
                        $dataL = array();
                        echo "Lote: ".$lt['code'].", cantidad: ".$lt['cantidad'].", existencia: ".$lt['existencia']."<br>";
                        if ($lt['cantidad'] == 0) {
                            $lt['cantidad'] = $diferencia - $lt['existencia'];
                            $dataL['cantidad'] = $lt['cantidad'] - $lt['existencia'];
                        }
                        $faltante = $lt['cantidad'] - $lt['existencia'];
                        echo "Faltante: $faltante.<br>";
                        if ($faltante > 0 && $diferencia > 0) {
                            if ($diferencia <= $faltante) {
                                $existencia = $lt['existencia'] + $diferencia;
                                echo "Nueva existencia: $existencia.<br>";
                                if ($existencia < 0) $existencia = 0;
                                $diferencia -= $diferencia;
                                $faltante = 0;
                                echo "Nueva existencia: $existencia, Diferencia: $diferencia.<br>";
                                /* $dataL['existencia'] = $existencia;
                                $this->db->where('lote_id', $lt['lote_id']);
                                $this->db->update('lotes', $dataL); */
                            } else {
                                if ($diferencia > $lt['cantidad']) {
                                    if ($lt['cantidad'] == 0) {
                                        $lt['cantidad'] = $faltante;
                                        $dataL['cantidad'] = $faltante;
                                    }
                                    $existencia = $lt['cantidad'];
                                    $diferencia -= $faltante;
                                    $faltante = 0;
                                    echo "Nueva existencia: $existencia, Diferencia: $diferencia.<br>";
                                    /* $dataL['existencia'] = $existencia;
                                    $this->db->where('lote_id', $lt['lote_id']);
                                    $this->db->update('lotes', $dataL); */
                                } else {
                                    if ($diferencia > $faltante) {
                                        if ($faltante > $lt['cantidad']) {
                                            $existencia = $lt['cantidad'];
                                            $diferencia -= $faltante;
                                            $faltante = 0;
                                            echo "Nueva existencia: $existencia, Diferencia: $diferencia.<br>";
                                            /* $dataL['existencia'] = $existencia;
                                            $this->db->where('lote_id', $lt['lote_id']);
                                            $this->db->update('lotes', $dataL); */
                                        } else {
                                            $existencia = $lt['existencia'] + $faltante;
                                            $diferencia -= $faltante;
                                            $faltante = 0;
                                            echo "Nueva existencia: $existencia, Diferencia: $diferencia.<br>";
                                            /* $dataL['existencia'] = $existencia;
                                            $this->db->where('lote_id', $lt['lote_id']);
                                            $this->db->update('lotes', $dataL); */
                                        }
                                    } else {
                                        $existencia = $lt['existencia'] + $diferencia;
                                        $diferencia -= $diferencia;
                                        $faltante = 0;
                                        echo "Nueva existencia: $existencia, Diferencia: $diferencia.<br>";
                                        /* $dataL['existencia'] = $existencia;
                                        $this->db->where('lote_id', $lt['lote_id']);
                                        $this->db->update('lotes', $dataL); */
                                    }
                                }
                            }
                        }
                    }
                } else {
                    echo "Sobra stock.<br>";
                    $diferencia = $lotes - $stock;
                    echo "Diferencia: $diferencia.<br>";
                    $asc = $this->db->order_by("lote_id", "ASC")->get_where("lotes", array('id_producto'=>$pd['products_id'], 'status'=>1, 'existencia >'=>0, 'branch_id'=>$branch_id))->result_array();
                    foreach($asc as $lt) {
                        $dataL = array();
                        echo "Lote: ".$lt['code'].", cantidad: ".$lt['cantidad'].", existencia: ".$lt['existencia']."<br>";
                        if ($diferencia > 0) {
                            if ($diferencia <= $lt['existencia']) {
                                $existencia = $lt['existencia'] - $diferencia;
                                $diferencia -= $diferencia;
                                echo "Nueva existencia: $existencia, Diferencia: $diferencia.<br>";
                                /* $dataL['existencia'] = $existencia;
                                $this->db->where('lote_id', $lt['lote_id']);
                                $this->db->update('lotes', $dataL); */
                            } else {
                                $existencia = 0;
                                $diferencia -= $lt['existencia'];
                                echo "Nueva existencia: $existencia, Diferencia: $diferencia.<br>";
                                /* $dataL['existencia'] = $existencia;
                                $this->db->where('lote_id', $lt['lote_id']);
                                $this->db->update('lotes', $dataL); */
                            }
                        }
                    }
                }
                $contador++;
                echo "<br>";
            }
        }
        echo "Contador: $contador";
    }
    
    function lotes_factura(){
        return $data = $this->db->query("SELECT * FROM `lotes` WHERE chk_factura = 1 and fecha <= '2024-05-15' AND existencia <> 0; ")->result_array();    
    }
    
    public function downloadExcelProductFactura()
    {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Nombre');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Cantidad');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Costo');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Precio');
        
        foreach(range('A','D') as $columnID) {
            $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
        }

        $a = 2; $b =2; $c =2; $d =2;$e =2;

        $query = $this->lotes_factura();
        $total = 0;
        foreach($query as $rw)
        {
            $prod = $this->db->get_where('products',array('products_id'=>$rw['id_producto']))->row();
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$a++, $prod->products_id);
            $objPHPExcel->getActiveSheet()->setCellValue('B'.$b++, $prod->name);
            $objPHPExcel->getActiveSheet()->setCellValue('C'.$c++, $rw['existencia']);
            $objPHPExcel->getActiveSheet()->setCellValue('D'.$d++, $prod->cost);
            $objPHPExcel->getActiveSheet()->setCellValue('E'.$e++, $prod->price);
        }
       
        $objPHPExcel->getActiveSheet()->setTitle('Productos con factura');
    
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="productos_con_factura'.date('d-m-y:h:i:s').'.xlsx"');
        header("Content-Transfer-Encoding: binary ");
        $objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); 
        $objWriter->setOffice2003Compatibility(true);
        $objWriter->save('php://output');
    }
    
    function total_compras(){
        $date = date('Y-m-d');
        $query = "SELECT SUM(amount) AS total FROM expense WHERE status = 1 and DATE(date) = ('$date') AND origin = 0 ";
        //log_message('error','query: '.$query);
        $data = $this->db->query($query)->row()->total;
        //log_message('error','data: '.$data);
        return $data;
    }
    
    function total_compras_date($date){
        //$date = date('Y-m-d');
        $query = "SELECT SUM(amount) AS total FROM expense WHERE status = 1 and DATE(date) = ('$date') AND origin = 0 ";
        //log_message('error','query: '.$query);
        $data = $this->db->query($query)->row()->total;
        //log_message('error','data: '.$data);
        return $data;
    }
    
    function total_compras_creditos($date){        
        $query = "SELECT SUM(amount) AS total FROM credit_details WHERE status = 1 and DATE(date) = ('$date') AND account_bank_id = 23 AND type = 1 ";        
        $data = $this->db->query($query)->row()->total;        
        return $data;
    }
    
    function getInfo($tipo) {
        return $this->db->get_where('settings', array('type' => $tipo))->row()->description;
    }  

    function get_shopping_info_provider($code) {
        $shopping =  $this->db->get_where('shopping', array('code' => $code))->row();
        return $this->get_provider_name($shopping->provider);
    }

    function get_provider_name($id) {
        return $this->db->get_where('provider', array('provider_id' => $id))->row()->name;
    }

}
