<?php $moneda = $this->crud_model->get_info("moneda"); setlocale(LC_TIME, "spanish");
    $data = $this->crud_model->get_totals_accounts_receivable($initial, $final, $client_id);
    log_message("error", "Ventas: ".$data['contador']);?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="row">
                <div class="col-sm-12">
                    <form action="<?php echo base_url();?>admin/cuentas_por_cobrar/" method="POST">
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
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Cliente</label>
                                    <div class="input-group">
                                        <select class="form-control" name="client_id" id="selected-1">
                                            <option value="T">Todos</option>
                                            <?php $clients = $this->crud_model->get_clients(); 
                                                foreach ($clients->result_array() as $cl):?>
                                            <option value="<?php echo $cl['client_id'];?>" <?php if($cl['client_id'] == $client_id) echo "selected";?>><?php echo $cl['nit'].' - '.trim($cl['name']).' '.trim($cl['last_name']);?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <input type="submit" class="btn btn-rounded btn-success" value="Filtrar" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-xl-3">
                            <a href="javascript:;" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                                        Total</div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.number_format($data['total'],2,'.',',');?></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3">
                            <a href="javascript:;" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                                        Créditos</div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $data['contador'];?></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3">
                            <a href="javascript:;" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                                        Pagado</div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.number_format($data['pagado'],2,'.',',');?></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3">
                            <a href="javascript:;" class="card card-custom gutter-b">
                                <div class="card-body" style="padding-top:10px">
                                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                                        Pendiente</div>
                                    <div class="font-weight-bold text-inverse-white font-size-h6">
                                        <?php echo $moneda.number_format($data['pendiente'],2,'.',',');?></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card card-custom gutter-b">
                        <div class="card-header flex-wrap border-0 pt-6 pb-0">
                            <div class="card-title">
                                <h3 class="card-label">Cuentas por cobrar</h3>
                            </div>
                            <div class="card-toolbar">
                                <?php if($data['contador'] > 0) :?>
                                <div class="dropdown dropdown-inline mr-2">
                                    <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="svg-icon svg-icon-md">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                    <rect x="0" y="0" width="24" height="24" />
                                                    <path
                                                        d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                                                        fill="#000000" opacity="0.3" />
                                                    <path
                                                        d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                                                        fill="#000000" />
                                                </g>
                                            </svg>
                                        </span>Exportar
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                        <ul class="navi flex-column navi-hover py-2">
                                            <li
                                                class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                                Exportar como:</li>
                                            <li class="navi-item">
                                                <a href="<?php echo base_url().'admin/export_excel/cuentas_cobrar/'.$initial.'/'.$final.'/'.$client_id.'/';?>"
                                                    class="navi-link">
                                                    <span class="navi-icon">
                                                        <i class="la la-file-excel-o"></i>
                                                    </span>
                                                    <span class="navi-text">Excel</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if($data['contador'] > 0):?>
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable no-footer dtr-inline" id="user_data">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Venta</th>
                                            <th>Cliente</th>
                                            <th>Fecha</th>
                                            <th>Pendiente</th>
                                            <th>Total</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
        
                            <?php else: ?>
                            <div class="card-body"
                                style="padding-top: 120px;padding-bottom: 120px;">
                                <center>
                                    <h3>Sin datos</h3><br>
                                    <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:25%">
                                </center>
                            </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    var dataTable = $('#user_data').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            url: "<?php echo base_url() . 'admin/get_cuentas_por_cobrar/'.$initial.'/'.$final.'/'; ?>",
            type: "POST"
        },

        "columnDefs": [{
            "targets": 0,
            "orderable": false,
        }, ],

        language: {
            lengthMenu: "Mostrar _MENU_",
            infoFiltered: "(filtrado de _MAX_ entradas totales)",
            emptyTable: "No hay datos disponibles en la tabla",
            zeroRecords: "No se encontraron coincidencias",
            loadingRecords: "Cargando...",
            processing: "Procesando...",
            search: "Buscar:",
        },
    });
});
</script>
<script type="text/javascript">
$(document).ready(function() {
    $('#selected-1').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true,
        tags: true
    });
});
</script>