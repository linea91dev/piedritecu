<?php setlocale(LC_TIME, "spanish"); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Tus notificaciones de
                            <?php if($date == date("Y-m-d")) echo "hoy";
                        else{ $Fecha_title = date("Y-m-d", strtotime($date)); $desc_date = strftime("%d de %B de %Y", strtotime($Fecha_title)); echo $desc_date; } ?>
                        </h3>
                    </div>
                    <form class="form-inline" method="post" action="<?php echo base_url().'admin/notificaciones/';?>">
                        <div class="card-toolbar">
                            <div class="col-md-4 my-2 my-md-0">
                                <div class="input-icon">
                                    <input type="date" class="form-control" onchange="this.form.submit()" name="date"
                                        <?php if($date != ''){?> value="<?php echo $date;?>" <?php } ?> />
                                    <span><i class="flaticon2-search-1 text-muted"></i></span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <a href="<?php echo base_url().'admin/notificaciones/read_all';?>"
                        class="btn btn-lg btn-icon btn-light-success btn-circle btn-hover-success" data-toggle="tooltip"
                        data-placement="right" data-container="body" data-boundary="window"
                        title="Marcar todas las notificaciones como vistas">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="eliminarNotificaciones()"
                        class="btn btn-lg btn-icon btn-light-danger btn-circle btn-hover-danger" data-toggle="tooltip"
                        data-placement="right" data-container="body" data-boundary="window"
                        title="Borrar todas las notificaciones">
                        <i class="flaticon-delete-1"></i>
                    </a>
                </div>

                <div class="card-body">
                    <div class="alert alert-warning">Las notificaciones se borran cada <b>30 días.</b></div>
                    <?php if($data->num_rows() > 0):?>
                    <div class="timeline timeline-6 mt-3" id='actividad'>
                        <?php foreach ($data->result_array() as $row):?>

                        <div class="row">
                            <div class="col col-xl-1 col-lg-1 col-md-1 col-sm-1 col-1" style="text-align: center;">
                                <div class="symbol symbol-50 symbol-circle mr-3">
                                    <div class="symbol-label">
                                        <?php if ($row['type'] == 'Prueba'):?>
                                        <i class="flaticon2-bell text-success icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Usuarios'):?>
                                        <i class="flaticon-users text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Inventario'):?>
                                        <i class="flaticon2-open-box text-success icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Alerta'):?>
                                        <i class="flaticon2-open-box text-danger icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Traslado'):?>
                                        <i class="flaticon2-lorry text-warning icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Categoria' || $row['type'] == 'Tipo_producto'):?>
                                        <i class="flaticon-pie-chart text-success icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Categoria_update' || $row['type'] == 'Tipo_producto_update'):?>
                                        <i class="flaticon-pie-chart text-warning icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Marcas'):?>
                                        <i class="flaticon2-tag text-success icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Ventas'):?>
                                        <i class="flaticon-shopping-basket text-success icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Ventas_update'):?>
                                        <i class="flaticon-shopping-basket text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Venta_eliminada'):?>
                                        <i class="flaticon-shopping-basket text-danger icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Compra'):?>
                                        <i class="flaticon-cart text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Solicitud'):?>
                                        <i class="flaticon2-shopping-cart text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Compra_completa'):?>
                                        <i class="flaticon-cart text-success icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Compra_update'):?>
                                        <i class="flaticon-cart text-warning icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Compra_eliminada'):?>
                                        <i class="flaticon-cart text-danger icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Cotizacion'):?>
                                        <i class="flaticon-list text-success icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Credito'):?>
                                        <i class="flaticon-coins text-warning icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Anulacion'):?>
                                        <i class="flaticon-cancel text-danger icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Cambio'):?>
                                        <i class="flaticon2-size text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Transporte'):?>
                                        <i class="flaticon-truck text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Transporte_servicio'):?>
                                        <i class="flaticon2-delivery-truck text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Egreso'):?>
                                        <i class="flaticon2-graph text-danger icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Egreso_update'):?>
                                        <i class="flaticon2-graph text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Egreso_deactivate'):?>
                                        <i class="flaticon2-graph text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Egreso_delete'):?>
                                        <i class="flaticon2-graph text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Egreso'):?>
                                        <i class="flaticon2-graph text-danger icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Planilla'):?>
                                        <i class="flaticon-folder-1 text-danger icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Cuenta_bancaria'):?>
                                        <i class="flaticon-piggy-bank text-success icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Perfil'):?>
                                        <i class="flaticon2-user text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Evento'):?>
                                        <i class="flaticon2-calendar-6 text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Evento_update'):?>
                                        <i class="flaticon2-calendar-5 text-warning icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Evento_eliminado'):?>
                                        <i class="flaticon2-calendar-9 text-danger icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Envios'):?>
                                        <i class="fas fa-shipping-fast text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Entregas'):?>
                                        <i class="fas fa-shipping-fast text-success icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Entregas_update'):?>
                                        <i class="fas fa-shipping-fast text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Entregas_delete'):?>
                                        <i class="fas fa-shipping-fast text-danger icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Roles'):?>
                                        <i class="flaticon-user-settings text-success icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Roles_update'):?>
                                        <i class="flaticon-user-settings text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Roles_delete'):?>
                                        <i class="flaticon-user-settings text-danger icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Roles_active'):?>
                                        <i class="flaticon-user-settings text-success icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Corte_caja'):?>
                                        <i class="la la-coins text-success icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Códigos'):?>
                                        <i class="fab fa-expeditedssl text-info icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Inventario_devolucion'):?>
                                        <i class="fas fa-file-signature text-warning icon-lg"></i>
                                        <?php elseif ($row['type'] == 'Inventario_perdida'):?>
                                        <i class="fas fa-folder-minus text-danger icon-lg"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-badge">
                                <?php if ($row['readed'] == 0):?>
                                <i class="fa fa-genderless text-success icon-xl"></i>
                                <?php elseif ($row['readed'] == 1):?>
                                <i class="fa fa-genderless text-light icon-xl" data-toggle="tooltip"
                                    data-placement="top" data-container="body" data-boundary="window" title="Visto"></i>
                                <?php endif; ?>
                            </div>
                            <div class="col col-xl-10 col-lg-10 col-md-10 col-sm-10 col-10">
                                <a href="<?php echo base_url().'admin/read_not/'.base64_encode($row['url']).'/'.$row['notificacion_id']?>">
                                    <p class="font-weight-mormal font-size-lg timeline-content 
                                        <?php if($row['readed'] == 0) echo 'text-dark'; elseif($row['readed'] == 1) echo 'text-muted'; ?> pl-3">
                                        <?php echo $row['notify'];?>
                                        <b>(<?php $Nueva_Fecha = date("d-m-Y", strtotime($row['date'])); $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));  echo $Mes_Anyo.' '.date('h:i A', strtotime($row['date'])); ?>)</b>
                                    </p>
                                </a>
                            </div>
                        </div><br>
                        <?php endforeach;?>

                    </div>
                    <?php else: ?>
                    <div class="card-body"
                        style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
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
<script type="text/javascript">

let timerInterval

function eliminarNotificaciones() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminarán todas tus notificaciones.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#9fd13b',
        cancelButtonColor: '#fd4f57',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            Swal.fire({
                title: 'Eliminando información',
                type: 'success',
                icon: 'success',
                titleTextColor: '#000',
                html: 'Esta ventana se cerrará en <strong></strong>.',
                timer: 2000,
                onBeforeOpen: () => {
                    Swal.showLoading()
                    timerInterval = setInterval(() => {
                        Swal.getContent().querySelector('strong').textContent = Swal
                            .getTimerLeft()
                    }, 100)
                },
                onClose: () => {
                    clearInterval(timerInterval)
                }
            })
            location.href = "<?php echo base_url();?>admin/notificaciones/delete_all";
        }
    })
}
</script>