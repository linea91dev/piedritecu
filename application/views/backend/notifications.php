<?php $nots = $this->crud_model->get_nots_mes(); ?>
<div id="kt_quick_notifications" class="offcanvas offcanvas-left p-10">
    <div class="offcanvas-header d-flex align-items-center justify-content-between mb-10">
        <h3 class="font-weight-bold m-0">Notificaciones
            <small
                class="text-muted font-size-sm ml-2"><?php if($nots->num_rows() > 0) echo $nots->num_rows(); if($nots->num_rows() > 1) echo ' nuevas'; if($nots->num_rows() == 1) echo ' nueva';?></small>
        </h3>
        <a href="<?php echo base_url().'admin/notificaciones/';?>"
            class="btn btn-sm btn-icon btn-info btn-circle btn-hover-primary" data-toggle="tooltip"
            data-placement="right" data-container="body" data-boundary="window" title="Ver todas">
            <i class="flaticon-eye"></i>
        </a>
        <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_notifications_close">
            <i class="ki ki-close icon-xs text-muted"></i>
        </a>
    </div>
    <div class="offcanvas-content pr-5 mr-n5">
        <div class="navi navi-icon-circle navi-spacer-x-0">
            <?php foreach ($nots->result_array() as $not):?>
            <a href="<?php echo base_url().'admin/read_not/'.base64_encode($not['url']).'/'.$not['notificacion_id'];?>"
                class="navi-item">
                <div class="navi-link rounded">
                    <div class="symbol symbol-50 symbol-circle mr-3">
                        <div class="symbol-label">
                            <?php if ($not['type'] == 'Prueba'):?>
                            <i class="flaticon2-bell text-success icon-lg"></i>
                            <?php elseif ($not['type'] == 'Usuarios'):?>
                            <i class="flaticon-users text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Inventario'):?>
                            <i class="flaticon2-open-box text-success icon-lg"></i>
                            <?php elseif ($not['type'] == 'Alerta'):?>
                            <i class="flaticon2-open-box text-danger icon-lg"></i>
                            <?php elseif ($not['type'] == 'Traslado'):?>
                            <i class="flaticon2-lorry text-warning icon-lg"></i>
                            <?php elseif ($not['type'] == 'Categoria' || $not['type'] == 'Tipo_producto'):?>
                            <i class="flaticon-pie-chart text-success icon-lg"></i>
                            <?php elseif ($not['type'] == 'Categoria_update' || $not['type'] == 'Tipo_producto_update'):?>
                            <i class="flaticon-pie-chart text-warning icon-lg"></i>
                            <?php elseif ($not['type'] == 'Marcas'):?>
                            <i class="flaticon2-tag text-success icon-lg"></i>
                            <?php elseif ($not['type'] == 'Ventas'):?>
                            <i class="flaticon-shopping-basket text-success icon-lg"></i>
                            <?php elseif ($not['type'] == 'Ventas_update'):?>
                            <i class="flaticon-shopping-basket text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Venta_eliminada'):?>
                            <i class="flaticon-shopping-basket text-danger icon-lg"></i>
                            <?php elseif ($not['type'] == 'Compra'):?>
                            <i class="flaticon-cart text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Solicitud'):?>
                            <i class="flaticon2-shopping-cart text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Compra_completa'):?>
                            <i class="flaticon-cart text-success icon-lg"></i>
                            <?php elseif ($not['type'] == 'Compra_update'):?>
                            <i class="flaticon-cart text-warning icon-lg"></i>
                            <?php elseif ($not['type'] == 'Compra_eliminada'):?>
                            <i class="flaticon-cart text-danger icon-lg"></i>
                            <?php elseif ($not['type'] == 'Cotizacion'):?>
                            <i class="flaticon-list text-success icon-lg"></i>
                            <?php elseif ($not['type'] == 'Credito'):?>
                            <i class="flaticon-coins text-warning icon-lg"></i>
                            <?php elseif ($not['type'] == 'Anulacion'):?>
                            <i class="flaticon-cancel text-danger icon-lg"></i>
                            <?php elseif ($not['type'] == 'Cambio'):?>
                            <i class="flaticon2-size text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Transporte'):?>
                            <i class="flaticon-truck text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Transporte_servicio'):?>
                            <i class="flaticon2-delivery-truck text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Egreso'):?>
                            <i class="flaticon2-graph text-danger icon-lg"></i>
                            <?php elseif ($not['type'] == 'Egreso_update'):?>
                            <i class="flaticon2-graph text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Egreso_deactivate'):?>
                            <i class="flaticon2-graph text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Egreso_delete'):?>
                            <i class="flaticon2-graph text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Egreso'):?>
                            <i class="flaticon2-graph text-danger icon-lg"></i>
                            <?php elseif ($not['type'] == 'Planilla'):?>
                            <i class="flaticon-folder-1 text-danger icon-lg"></i>
                            <?php elseif ($not['type'] == 'Cuenta_bancaria'):?>
                            <i class="flaticon-piggy-bank text-success icon-lg"></i>
                            <?php elseif ($not['type'] == 'Perfil'):?>
                            <i class="flaticon2-user text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Evento'):?>
                            <i class="flaticon2-calendar-6 text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Evento_update'):?>
                            <i class="flaticon2-calendar-5 text-warning icon-lg"></i>
                            <?php elseif ($not['type'] == 'Evento_eliminado'):?>
                            <i class="flaticon2-calendar-9 text-danger icon-lg"></i>
                            <?php elseif ($not['type'] == 'Envios'):?>
                            <i class="fas fa-shipping-fast text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Entregas'):?>
                            <i class="fas fa-shipping-fast text-success icon-lg"></i>
                            <?php elseif ($not['type'] == 'Entregas_update'):?>
                            <i class="fas fa-shipping-fast text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Entregas_delete'):?>
                            <i class="fas fa-shipping-fast text-danger icon-lg"></i>
                            <?php elseif ($not['type'] == 'Roles'):?>
                            <i class="flaticon-user-settings text-success icon-lg"></i>
                            <?php elseif ($not['type'] == 'Roles_update'):?>
                            <i class="flaticon-user-settings text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Roles_delete'):?>
                            <i class="flaticon-user-settings text-danger icon-lg"></i>
                            <?php elseif ($not['type'] == 'Roles_active'):?>
                            <i class="flaticon-user-settings text-success icon-lg"></i>
                            <?php elseif ($not['type'] == 'Corte_caja'):?>
                            <i class="la la-coins text-success icon-lg"></i>
                            <?php elseif ($not['type'] == 'Códigos'):?>
                            <i class="fab fa-expeditedssl text-info icon-lg"></i>
                            <?php elseif ($not['type'] == 'Inventario_devolucion'):?>
                            <i class="fas fa-file-signature text-warning icon-lg"></i>
                            <?php elseif ($not['type'] == 'Inventario_perdida'):?>
                            <i class="fas fa-folder-minus text-danger icon-lg"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold font-size-lg"><?php echo $not['notify']; ?></div>
                        <?php $ex = explode("_", $not['type'])?>
                        <div class="text-muted"><?php echo $ex[0]; ?></div>
                        <small>Fecha y hora :
                            <b><?php setlocale(LC_TIME, "spanish"); 
							$hora=date_create($not['date']);
                            $Nueva_Fecha = date("d-m-Y ", strtotime( $not['date'] ));
                            $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); 
                            echo $Mes_Anyo.date_format($hora," h:i A");?></b></small>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>