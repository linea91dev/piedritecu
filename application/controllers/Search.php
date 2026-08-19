<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Search extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
    }
   
    public function index()
    {
		$data['page_name']		=	'search';
		$data['page_title']		=	"Buscar";
		$this->load->view('frontend/index' , $data);
    }

   	function query() 
    {
    	if($_POST['b'] != "")
    	{    	
        	$this->db->like('nombre' , $_POST['b']);
        	$this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
        	$query = $this->db->get('producto')->result_array();
        	if(count($query) > 0)
        	{
        		foreach ($query as $row) 
        		{
                    echo "<tr>";
                    echo "<td class='cell-with-media'>
                    <a href='#'><img alt='' src='".base_url()."uploads/productos/".$row['id'].".jpg' style='height: 30px;'>
                    <span>".$row['nombre']."</span></a></td> 
                    <td class='text-center bolder nowrap'><span class='text-primary'>".$row['stock']."</span></td>
                    <td class='text-center bolder nowrap'><span class='text-success'>Q".$row['costo']."</span></td>
                    <td class='text-center bolder nowrap'><span class='text-success'>Q".$row['precio']."</span></td>
                    <td class='text-center nowrap'><a class='btn btn-primary' href='".base_url()."admin/cart/".$row['id']."'>+ Agregar</a></td>";
                    echo "</tr>";
				}
        	} else{
        		echo '<p class="col-md-12" style="text-align: left; color: #000;">Lo sentimos, no hay resultados para tu busqueda.</p>';
        	}
        }
    }


}