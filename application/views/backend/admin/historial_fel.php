<?php $data = $this->db->order_by('sales_id','DESC')->get_where('sales', array('FEL'=>1 , 'branch_id'=> $this->session->userdata('branch_id')));   ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Historial FEL
                            <span class="d-block text-muted pt-2 font-size-sm">Gestiona todas las facturas
                                emitidas</span>
                        </h3>
                    </div>
                </div>
                <?php if($data->num_rows() > 0): ?>
                <div class="card-body">
                    <form class="mb-15">
                        <div class="row mb-6">

                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Fecha</label>
                                <div class="input-daterange input-group" id="kt_datepicker">
                                    <input type="text" class="form-control datatable-input" name="start" readonly
                                        autocomplete="off" placeholder="mm/dd/aaaa" data-col-index="1">
                                </div>
                            </div>

                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Estado:</label>
                                <select class=" form-control datatable-input" data-col-index="2">
                                    <option value="">Seleccionar</option>
                                    <option value="Completado">Completado</option>
                                    <option value="Cambio">Cambio</option>
                                    <option value="Anulado">Anulado</option>
                                </select>
                            </div>

                        </div>
                        <div class="row mt-8">
                            <div class="col-lg-12">
                                <button class="btn btn-primary btn-primary--icon" id="kt_search">
                                    <span>
                                        <i class="la la-search"></i>
                                        <span>Buscar</span>
                                    </span>
                                </button>&nbsp;&nbsp;
                                <button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                                    <span>
                                        <i class="la la-close"></i>
                                        <span>Limpiar</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable no-footer dtr-inline" id="kt_datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                    <th>Cliente</th>
                                    <th>Forma de pago</th>
                                    <th>Vendedor</th>
                                    <th>Envío</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id='table'>
                                <?php $n = 1; 
								foreach($data->result_array() as $row):?>
                                <tr>
                                    <td><?php echo $n++;?></td>
                                    <td class='text-info'> <b>

                                            <?php setlocale(LC_TIME, "spanish");
                $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));				
                $Mes_Anyo = strftime("%m/%d/%Y", strtotime($Nueva_Fecha)); 
                
                echo $Mes_Anyo; ?>
                                        </b>
                                    </td>
                                    <?php if($row['estado'] == 1)
                {
                    $estado = '<span class="label label-lg font-weight-bold label-light-warning label-inline">Credito</span>';  
                }
                elseif($row['estado'] == 2)
                {
                    $estado = '<span class="label label-lg font-weight-bold label-light-success label-inline">Emitida</span>';  
                }
                elseif($row['estado'] == 3)
                {
                    $estado = '<span class="label label-lg font-weight-bold label-light-danger label-inline">Anulado</span>';  
                }
                elseif($row['estado'] == 4)
                {
                    $estado = '<span class="label label-lg font-weight-bold label-light-info label-inline">Cambio</span>';  
                }?>
                                    <td>
                                        <?php echo $estado;?>
                                    </td>
                                    <td> <?php echo $row['name'].' '.$row['last_name'];?></td>
                                    <?php if($row['metodo'] != "")
                {
                    $metodo = '<span class="text-dark"><b>'.$row['metodo'].'</b></span>';
                }else {
                    $metodo = '<span class="text-dark"><b>Sin datos</b></span>';
                    
                }?>
                                    <td><?php echo $metodo;?></td>
                                    <td><?php echo  $this->crud_model->getName('admin', $row['responsable']);?></td>
                                    <?php if($row['shipping']==1)
                {
                    $envio =  '<center><i class="fas fa-check text-warning"></i></center>';
                }elseif($row['shipping']==0)
                {
                    $envio =  '<center><i class="fa fa-times text-danger"></i></center>';
                }elseif($row['shipping']==2)
                {
                    $envio =  '<center><i class="fa fa-check text-success"></i></center>';
                }?>
                                    <td>
                                        <?php echo $envio;?>
                                    </td>
                                    <td>
                                        <span class="label label-lg font-weight-bold label-light-success label-inline"><?php  $moneda = $this->crud_model->get_info("moneda");
                                        echo  $moneda.number_format($row['total'],2,'.',','); ;?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-shrink-0">
                                            &nbsp;
                                            <a href="<?php echo base_url().'admin/detalles_venta/'.$row['code'];?>"
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
                                            </a>
                                            &nbsp;
                                            <?php if($user_type == 1 || $permisos['reportes_fel'] == 1):?>
                                            <a href="<?php echo base_url().'admin/export_pdf/ventaFEL/'.$row['code'];?>"
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
                                            <?php endif; if($row['estado']!=3 && ($user_type == 1 || $permisos['eliminar_fel'] == 1)):?>
                                            &nbsp;
                                            <a href="javascript:;"
                                                onclick="showModalAn('<?php echo base_url();?>modal/popup/anulacionXML/<?php echo $row['code'];?>');"
                                                class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                                                data-toggle="tooltip" data-original-title="Anular">
                                                <span class="svg-icon svg-icon-info svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <polygon points="0 0 24 0 24 24 0 24" />
                                                            <path
                                                                d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z"
                                                                fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                            <path
                                                                d="M10.5857864,13 L9.17157288,11.5857864 C8.78104858,11.1952621 8.78104858,10.5620972 9.17157288,10.1715729 C9.56209717,9.78104858 10.1952621,9.78104858 10.5857864,10.1715729 L12,11.5857864 L13.4142136,10.1715729 C13.8047379,9.78104858 14.4379028,9.78104858 14.8284271,10.1715729 C15.2189514,10.5620972 15.2189514,11.1952621 14.8284271,11.5857864 L13.4142136,13 L14.8284271,14.4142136 C15.2189514,14.8047379 15.2189514,15.4379028 14.8284271,15.8284271 C14.4379028,16.2189514 13.8047379,16.2189514 13.4142136,15.8284271 L12,14.4142136 L10.5857864,15.8284271 C10.1952621,16.2189514 9.56209717,16.2189514 9.17157288,15.8284271 C8.78104858,15.4379028 8.78104858,14.8047379 9.17157288,14.4142136 L10.5857864,13 Z"
                                                                fill="#000000" />
                                                        </g>
                                                    </svg></span>
                                            </a>
                                            <?php endif;?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
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


<script type="text/javascript">
$(document).ready(function() {

})
</script>