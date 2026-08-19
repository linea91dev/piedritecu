<?php $moneda = $this->crud_model->get_info("moneda"); $sucursal = $this->crud_model->getBranch( $this->session->userdata('branch_id') ); 
$tipo = $this->session->userdata('login_user_type'); $hoy = date("Y-m-d");?>
<div class="container">
    <br>
    <div class="alert alert-warning">
        Todos los datos que visualizarás están en base a la sucursal <b><?php echo $sucursal;?></b>, para gestionar las
        otras sucursales puedes utilizar el botón ubicado en la parte inferior izquierda. <b>(Si estás autorizado)</b>
        <?php echo date('y-m-d H:i:s');?>
    </div>
    <div class="row">
        <?php if($tipo==11){?>
        <div class="col col-xxl-3 col-xl-3 col-lg-6 col-md-12 col-sm-12 col-12 ">
            <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="background-position: right top; background-size: 30% auto; background-image: url(<?php echo base_url();?>public/assets/media/svg/shapes/abstract-1.svg)">
                <div class="card-body">
                    <a href="<?php if ($user_type == 1 || $permisos['inventario'] == 1) echo base_url().'admin/inventario/'; else echo "javascript:void(0)";?>">
                        <span class="svg-icon svg-icon-2x svg-icon-info">
                            <i class="flaticon2-open-box" style="font-size:28px"></i>
                        </span>
                        <span class="card-title font-weight-bolder text-dark-75 font-size-h3 mb-0 mt-6 d-block"><?php echo $moneda.$this->crud_model->total_inventario();?></span>
                        <span class="font-weight-bold text-muted font-size-sm">Total en inventario <small><br> (basado en costos)</small> </span>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col col-xxl-3 col-xl-3 col-lg-6 col-md-12 col-sm-12 col-12">
            <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="background-position: right top; background-size: 30% auto; background-image: url(<?php echo base_url();?>public/assets/media/svg/shapes/abstract-1.svg)">
                <div class="card-body">
                    <a href="<?php if ($user_type == 1 || $permisos['inventario'] == 1) echo base_url().'admin/inventario/'; else echo "javascript:void(0)";?>">
                        <span class="svg-icon svg-icon-2x svg-icon-info">
                            <i class="flaticon2-cube" style="font-size:28px"></i>
                        </span>
                        <span class="card-title font-weight-bolder text-dark-75 font-size-h3 mb-0 mt-6 d-block"><?php echo $moneda.$this->crud_model->total_bodega();?></span>
                        <span class="font-weight-bold text-muted font-size-sm">Total en bodega <small><br> (basado en costos)</small></span>
                    </a>
                </div>
            </div>
        </div>
        <?php } ?>
        <div class="col col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
            <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="background-position: right top; background-size: 30% auto; background-image: url(<?php echo base_url();?>public/assets/media/svg/shapes/abstract-1.svg)">
                <div class="card-body">
                    <a href="<?php if ($user_type == 1 || $permisos['graficas_ventas'] == 1) echo base_url().'admin/reportes/ventas/'; else echo "javascript:void(0)";?>">
                        <span class="svg-icon svg-icon-2x svg-icon-info">
                            <i class="flaticon-shopping-basket" style="font-size:28px"></i>
                        </span>
                        <span class="card-title font-weight-bolder text-dark-75 font-size-h3 mb-0 mt-6 d-block"><?php echo $moneda.$this->crud_model->total_vendido($initial, $final);?></span>
                        <span class="font-weight-bold text-muted font-size-sm">Total vendido <?php if($initial == $hoy && $final == $hoy) echo "hoy"; elseif($initial == $final) echo date("d/m/Y", strtotime($initial)); else echo ' del '.date("d/m/Y", strtotime($initial)).' al '.date("d/m/Y", strtotime($final));?></span>
                    </a>
                </div>
            </div>
        </div>
        <div class="col col-xxl-6 col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
            <div class="card card-custom bgi-no-repeat card-stretch gutter-b" style="background-position: right top; background-size: 30% auto; background-image: url(<?php echo base_url();?>public/assets/media/svg/shapes/abstract-1.svg)">
                <div class="card-body">
                    <a href="<?php if ($user_type == 1 || $permisos['ver_egresos'] == 1) echo base_url().'admin/egresos/'; else echo "javascript:void(0)";?>">
                        <span class="svg-icon svg-icon-2x svg-icon-info">
                            <i class="flaticon2-graph" style="font-size:28px"></i>
                        </span>
                        <span class="card-title font-weight-bolder text-dark-75 font-size-h3 mb-0 mt-6 d-block"><?php echo $moneda.$this->crud_model->total_egresos($initial, $final);?></span>
                        <span class="font-weight-bold text-muted font-size-sm">Egresos del <?php if($initial == $hoy && $final == $hoy) echo "día"; elseif($initial == $final) echo date("d/m/Y", strtotime($initial)); else echo date("d/m/Y", strtotime($initial)).' al '.date("d/m/Y", strtotime($final));?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="">
        <form method="POST" action="<?php echo base_url();?>admin/tablero/">
            <div class="row">
                <div class="col col-xxl-5 col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 "></div>
                <div class="col col-xxl-3 col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
                    <div class="form-group">
                        <label class="col-form-label">Inicial:</label>
                        <input type="date" class="form-control" name="initial" value="<?php echo $initial;?>" />
                    </div>
                </div>
                <div class="col col-xxl-3 col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12 ">
                    <div class="form-group">
                        <label class="col-form-label">Final:</label>
                        <input type="date" class="form-control" name="final" value="<?php echo $final;?>" />
                    </div>
                </div>
                <div class="col col-xxl-1 col-xl-1 col-lg-1 col-md-12 col-sm-12 col-12 "><br>
                    <button type="submit" class="btn btn-sm btn-rounded btn-success">Aplicar</button>
                </div>
            </div>
        </form>
    </div>
    <div class="row mt-0 mt-lg-8">
        <?php 
		$branch_id = $this->session->userdata('branch_id');
        $user_id = $this->session->userdata('login_user_id');
        $hoy = date('Y-m-d');
        $this->db->select('*');
        $this->db->from('binnacle');
		$this->db->limit('8'); 
        $this->db->where('branch_id', $branch_id); 
        $this->db->where('user_id', $user_id); 
        $this->db->like('date', "$hoy"); 
        $data = $this->db->get();?>
        <div class="col col-xxl-5 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card card-custom gutter-b">
                <div class="card-header align-items-center border-0 mt-4">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="font-weight-bolder text-dark">Tu actividad de hoy</span>
                        <span class="text-muted mt-3 font-weight-bold font-size-sm"><a class="font-weight-bolder" href="<?php echo base_url().'admin/actividad/';?>">Ver registros
                                completos</a></span>
                    </h3>
                </div>
                <div class="card-body pt-4">
                    <div class="timeline timeline-6 mt-3">
                        <?php if($data->num_rows() > 0):?>
                        <?php foreach ($data->result_array() as $act):?>

                        <div class="row timeline-item align-items-start">
                            <div class="col-sm-12">
                                <div class="timeline-label font-weight-bolder text-dark-75 font-size-xs">
                                    <?php setlocale(LC_TIME, "spanish");
                                    $Nueva_Fecha = date("d-m-Y", strtotime( $act['date']));				
                                    $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); 
                                    echo $Mes_Anyo; ?>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="font-weight-mormal font-size-sm timeline-content text-muted pl-3">
                                    <?php echo $act['message'];?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach ;?>
                        <?php else: ?>
                        <div class="card-body" style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
                            <center>
                                <h3>Sin datos</h3><br>
                                <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:25%">
                            </center>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-xxl-7 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card card-custom gutter-b">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">Ventas del día</span>
                    </h3>
                    <?php if($user_type == 1 || $permisos['ver_ventas'] == 1):?>
                    <div class="card-toolbar">
                        <a href="<?php echo base_url();?>admin/ventas/" class="btn btn-light-info font-weight-bolder font-size-sm">Ver todas las ventas</a>
                    </div>
                    <?php endif;?>
                </div>
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <?php $ventas  = $this->db->order_by('sales_id', 'DESC')->limit(8)->get_where('sales', array('branch_id'=>$branch_id, 'credito'=>0 , 'status'=>1 , 'date'=>date('Y-m-d'), 'estado'=>2)) ;
                        if($ventas->num_rows() > 0):?>
                        <table class="table table-head-custom table-vertical-center" id="kt_advance_table_widget_2">
                            <thead>
                                <tr class="text-uppercase">
                                    <th class="pl-0">CÓDIGO</th>
                                    <th>TOTAL</th>
                                    <th>
                                        <span class="text-primary">FECHA &amp; HORA</span>
                                        <span class="svg-icon svg-icon-sm svg-icon-primary">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                                    <rect fill="#000000" opacity="0.3" x="11" y="4" width="2" height="10" rx="1"></rect>
                                                    <path d="M6.70710678,19.7071068 C6.31658249,20.0976311 5.68341751,20.0976311 5.29289322,19.7071068 C4.90236893,19.3165825 4.90236893,18.6834175 5.29289322,18.2928932 L11.2928932,12.2928932 C11.6714722,11.9143143 12.2810586,11.9010687 12.6757246,12.2628459 L18.6757246,17.7628459 C19.0828436,18.1360383 19.1103465,18.7686056 18.7371541,19.1757246 C18.3639617,19.5828436 17.7313944,19.6103465 17.3242754,19.2371541 L12.0300757,14.3841378 L6.70710678,19.7071068 Z"
                                                          fill="#000000" fill-rule="nonzero" transform="translate(12.000003, 15.999999) scale(1, -1) translate(-12.000003, -15.999999)">
                                                    </path>
                                                </g>
                                            </svg>
                                        </span>
                                    </th>
                                    <th>RESPONSABLE</th>
                                    <th>ESTADO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
									foreach ($ventas->result_array() as $vts):?>
                                <tr>
                                    <td class="pl-0">
                                        <a href="<?php echo base_url().'admin/detalles_venta/'.$vts['code'];?>" class="text-dark font-weight-bolder text-hover-primary font-size-lg"><?php echo $vts['code'];?></a>
                                    </td>
                                    <td>
                                        <span class="text-dark-75 font-weight-bolder d-block font-size-lg"><?php echo $moneda.number_format($vts['total'],2,'.',',');?></span>
                                    </td>
                                    <td>
                                        <span class="text-dark-75 font-weight-bolder d-block font-size-lg"><?php echo $vts['date'];?></span>
                                        <span class="text-muted font-weight-bold"><?php echo $vts['time'];?></span>
                                    </td>
                                    <td>
                                        <span class="text-dark-75 font-weight-bolder d-block font-size-lg"><?php echo $this->crud_model->getName('admin', $vts['responsable']);?></span>
                                        <span class="text-muted font-weight-bold"><?php echo $this->db->get_where('admin', array('admin_id'=>$vts['responsable']) )->row()->job; ?></span>
                                    </td>
                                    <td>
                                        <?php if($vts['estado']==1){ $status = 'Activo'; }elseif($vts['estado']==2){$status = 'Completado'; }elseif($vts['estado']==3){$status = 'Anulado';};?>
                                        <?php if($vts['estado']==1){ $color = 'info'; }elseif($vts['estado']==2){$color = 'success'; }elseif($vts['estado']==3){$color = 'danger';};?>
                                        <span class="label label-lg label-light-<?php echo $color; ?> label-inline"><?php echo $status;?></span>
                                    </td>
                                </tr>
                                <?php endforeach ;?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <div class="card-body" style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
                            <center>
                                <h3>Sin datos</h3><br>
                                <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:25%">
                            </center>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
