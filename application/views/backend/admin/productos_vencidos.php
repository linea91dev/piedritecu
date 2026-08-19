<?php $data = $this->crud_model->get_expired_products_dates($initial, $final); 
    $total = $this->crud_model->get_expired_total_dates($initial, $final);
    $moneda = $this->crud_model->get_info("moneda");
    $tipo = $this->session->userdata('login_user_type');?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar inventario
                            <span class="d-block text-muted pt-2 font-size-sm"></span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <?php if($user_type == 1 || $permisos['crear_productos'] == 1):?>
                        <div class="dropdown dropdown-inline mr-2">
                            <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="svg-icon svg-icon-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3" />
                                            <path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000" />
                                        </g>
                                    </svg>
                                </span>Exportar
                            </button>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                <ul class="navi flex-column navi-hover py-2">
                                    <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                        Exportar como:</li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_excel/productos_vencidos/'.$initial.'/'.$final.'/';?>" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-excel-o"></i>
                                            </span>
                                            <span class="navi-text">Descargar listado</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo base_url();?>admin/productos_vencidos/">
                        <div class="row">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="col-form-label">Fecha inicial:</label>
                                    <input type="date" class="form-control" name="initial" value="<?php echo $initial;?>" />
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="col-form-label">Fecha final:</label>
                                    <input type="date" class="form-control" name="final" value="<?php echo $final;?>" />
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <input type="submit" class="btn btn-rounded btn-success" value="Filtrar" />
                            </div>
                            <div class="col-sm-4">
                                <a href="javascript:;" class="card card-custom gutter-b">
                                    <div class="card-body" style="padding-top:10px">
                                        <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                                            Total</div>
                                        <div class="font-weight-bold text-inverse-white font-size-h6">
                                            <?php echo $moneda.number_format($total,2,'.',',');?></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <?php if($data->num_rows() >0):?>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover dataTable no-footer dtr-inline collapsed" id="user_data" role="grid" aria-describedby="kt_datatable_info">
                             <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Lote</th>
                                    <th>Código</th>
                                    <th>Producto</th>
                                    <th>Fecha de compra</th>
                                    <th>Vencimiento</th>
                                    <th>Unidades compradas</th>
                                    <th>Unidades vencidas</th>
                                    <th>Precio de compra</th>
                                    <th>Total en pérdida</th>
                                </tr>
                             </thead>
                          </table>
                        
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
</div>


<link href="<?php echo base_url();?>public/style/datatables/jquery.dataTables.min.css" rel="stylesheet">
<script src="<?php echo base_url();?>public/style/datatables/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript" >  
    $(document).ready(function(){  
      var dataTable = $('#user_data').DataTable({  
           "processing":true,  
           "serverSide":true,  
           "order":[],  
           "ajax":{  
                url:"<?php echo base_url() . 'admin/get_vencidos/'.$initial.'/'.$final.'/'; ?>",  
                type:"POST"  
           },   
        });  
    });  
</script>
