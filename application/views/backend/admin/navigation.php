<?php 
    $branch_id = $this->session->userdata('branch_id');
    $cierre = $this->crud_model->get_info('cierre');
    $monto_limite = $this->crud_model->get_info('monto_limite');
    $hora_limite = $this->crud_model->get_info('corte');
    $ingresos = $this->crud_model->ingresos_caja();
    $egresos = $this->crud_model->egresos_caja();
    $hoy = date("Y-m-d");
    $hora_actual = date("H:i");
    $close_today = $this->db->get_where('cash_history', array('date_close' => $hoy, 'branch_id' => $branch_id));
?>
<div id="kt_header" class="header header-fixed">
    <div class="header-wrapper rounded-top-xl d-flex flex-grow-1 align-items-center">
        <div class="container-fluid d-flex align-items-center justify-content-end justify-content-lg-between flex-wrap">
            <div class="header-menu-wrapper header-menu-wrapper-left py-lg-2" id="kt_header_menu_wrapper">
                <div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
                    <ul class="menu-nav">
                        <?php if($this->session->userdata('login_user_type') == '1'):?>
                        <li class="menu-item <?php if($page_name == 'tablero') echo 'menu-item-active';?>" aria-haspopup="true">
                            <a href="<?php echo base_url();?>admin/tablero/" class="menu-link">
                                <span class="menu-text">Tablero</span>
                            </a>
                        </li>
                        <?php endif;?>
                        <?php if($user_type == 1 || $permisos['usuarios'] == 1):?>
                        <li class="menu-item menu-item-submenu menu-item-rel <?php if($page_name == 'admins' ||$page_name == 'codigos' || $page_name == 'empleados'  || $page_name == 'agregar_empleado' || $page_name == 'editar_empleado'|| $page_name == 'proveedores' || $page_name == 'clientes' || $page_name == 'perfil_empleado' || $page_name == 'perfil_admin' || $page_name == 'perfil_proveedor' || $page_name == 'roles' || $page_name == 'agregar_rol' || $page_name == 'editar_rol') echo 'menu-item-active';?>" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-text">Usuarios</span>
                                <span class="menu-desc"></span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                <ul class="menu-subnav">
                                    <?php if($user_type == 1 || $permisos['roles'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/roles/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-user-settings"></i>
                                            </span>
                                            <span class="menu-text">Puestos</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['empleados'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/empleados/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-users"></i>
                                            </span>
                                            <span class="menu-text">Empleados</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['admins'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/admins/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon2-user-1"></i>
                                            </span>
                                            <span class="menu-text">Administradores</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['proveedores'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/proveedores/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon2-delivery-truck"></i>
                                            </span>
                                            <span class="menu-text">Proveedores</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['clientes'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/clientes/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon2-group"></i>
                                            </span>
                                            <span class="menu-text">Clientes</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['codigos'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/codigos/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class=" fab fa-expeditedssl"></i>
                                            </span>
                                            <span class="menu-text">Códigos de autorización</span>
                                        </a>
                                    </li>
                                    <?php endif;?>
                                </ul>
                            </div>
                        </li>
                        <?php endif; if($user_type == 1 || $permisos['productos'] == 1 ):?>
                        <li class="menu-item menu-item-submenu menu-item-rel <?php if($page_name == 'producto_detalle' ||$page_name == 'inventario' || $page_name == 'nuevo_producto'  || $page_name == 'producto_edit' || $page_name == 'traslados' || $page_name == 'traslados2' || $page_name == 'categorias' || $page_name == 'marcas' || $page_name == 'marcas' || $page_name == 'inventario_marca' || $page_name == 'increases' || $page_name == 'producto_traslado' || $page_name == 'perdidas' || $page_name == 'registrar_perdida' || $page_name == 'productos_vencidos') echo 'menu-item-active';?>" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-text">Productos</span>
                                <span class="menu-desc"></span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                <ul class="menu-subnav">
                                    <?php if($user_type == 1 || $this->session->userdata('login_user_id') == '45'):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/inventario/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon2-open-box"></i>
                                            </span>
                                            <span class="menu-text">En inventario</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['inventario'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/inventario_marca/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon2-open-box"></i>
                                            </span>
                                            <span class="menu-text">Inventario por marca</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/increases/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon2-open-box"></i>
                                            </span>
                                            <span class="menu-text">Lista de entradas registradas</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/perdidas/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon2-open-box"></i>
                                            </span>
                                            <span class="menu-text">Pérdidas registradas</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/productos_vencidos/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon2-open-box"></i>
                                            </span>
                                            <span class="menu-text">Productos vencidos</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['traslados'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/traslados/encabezado" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon2-lorry"></i>
                                            </span>
                                            <span class="menu-text">Traslados Documentos</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['categorias'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/categorias/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-pie-chart"></i>
                                            </span>
                                            <span class="menu-text">Categorías</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['categorias'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/tipos_p/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-pie-chart"></i>
                                            </span>
                                            <span class="menu-text">Tipos</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['categorias'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/class_p/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-pie-chart"></i>
                                            </span>
                                            <span class="menu-text">Clase</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['marcas'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/marcas/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon2-tag"></i>
                                            </span>
                                            <span class="menu-text">Marcas</span>
                                        </a>
                                    </li>
                                    <?php endif;?>
                                </ul>
                            </div>
                        </li>
                        <?php endif; if($user_type == 1 || ($permisos['herramientas'] == 1 && ($permisos['ver_ventas'] == 1 || $permisos['ver_compras'] == 1 || $permisos['ver_cotizaciones'] == 1 || $permisos['creditos'] == 1 || $permisos['ver_anulaciones'] == 1 || $permisos['ver_cambios'] == 1 || $permisos['ver_envios'] == 1 || $permisos['transporte'] == 1))):?>
                        <li class="menu-item menu-item-submenu menu-item-rel <?php if($page_name == 'nuevo_cambio' || $page_name == 'detalles_venta' || $page_name == 'detalles_compra' || $page_name == 'cambios' || $page_name == 'transporte' || $page_name == 'transporte_servicios' || $page_name == 'nueva_compra' || $page_name == 'nueva_solicitud' || $page_name == 'ventas' || $page_name == 'compras' || $page_name == 'cotizaciones' || $page_name == 'nueva_cotizacion' || $page_name == 'creditos' || $page_name == 'anulaciones' || $page_name == 'anulacion' || $page_name == 'envios' || $page_name == 'entregas') echo 'menu-item-active';?>" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-text">Herramientas</span>
                                <span class="menu-desc"></span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                <ul class="menu-subnav">
                                    <?php if($user_type == 1 || $permisos['ver_ventas'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/ventas/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-shopping-basket"></i>
                                            </span>
                                            <span class="menu-text">Ventas</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['ver_compras'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/compras/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-cart"></i>
                                            </span>
                                            <span class="menu-text">Compras</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['ver_cotizaciones'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/cotizaciones/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-list"></i>
                                            </span>
                                            <span class="menu-text">Cotizaciones</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['creditos'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/creditos/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-coins"></i>
                                            </span>
                                            <span class="menu-text">Créditos</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['creditos'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/creditos_compras/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-coins"></i>
                                            </span>
                                            <span class="menu-text">Créditos Compras</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['ver_anulaciones'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/anulaciones/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-cancel"></i>
                                            </span>
                                            <span class="menu-text">Anulaciones</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['ver_cambios'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/cambios/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-cancel"></i>
                                            </span>
                                            <span class="menu-text">Cambios</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['ver_envios'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/envios/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="fas fa-shipping-fast"></i>
                                            </span>
                                            <span class="menu-text">Envíos</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['transporte'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/transporte/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-truck"></i>
                                            </span>
                                            <span class="menu-text">Transporte</span>
                                        </a>
                                    </li>
                                    <?php endif;?>
                                </ul>
                            </div>
                        </li>
                        <?php endif; if($user_type == 1 || $permisos['reportes'] == 1):?>
                        <li class="menu-item menu-item-submenu menu-item-rel <?php if($page_name == 'reportes_financiero' || $page_name == 'reportes_cambios' || $page_name == 'reportes_generales' || $page_name == 'reportes_actividad' || $page_name == 'reportes_anulaciones' || $page_name == 'reportes_ventas' || $page_name == 'reportes_compras' || $page_name == 'ventas_traslados' || $page_name == 'ventas_vendedor' || $page_name == 'clientes_por_usuario' || $page_name == 'ventas_producto' || $page_name == 'ventas_marca' || $page_name == 'ventas_mensuales' || $page_name == 'ventas_afectas' || $page_name == 'ventas_exentas' || $page_name == 'cuentas_por_cobrar' || $page_name == 'cuentas_por_pagar') echo 'menu-item-active';?>" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-text">Reportes</span>
                                <span class="menu-desc"></span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                <ul class="menu-subnav">
                                    <?php if($user_type == 1 || $permisos['graficas_generales'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/reportes/generales/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Generales</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_ventas00'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/reportes/ventas/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Ventas</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_ventas'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/ventas_traslados/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Movimientos</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_ventas00'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/ventas_vendedor/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Ventas por vendedor</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_ventas00'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/clientes_por_usuario/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Clientes por usuario</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_ventas00'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/ventas_producto/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Ventas por producto</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_ventas'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/ventas_marca/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Ventas por marca</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_ventas00'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/reportes_mensual/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Ventas Mensual</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_ventas00'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/ventas_afectas/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Ventas Afectas</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_ventas00'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/ventas_exentas/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Ventas Exentas</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_ventas00'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/cuentas_por_cobrar/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Cuentas por cobrar</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_ventas00'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/cuentas_por_pagar/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Cuentas por pagar</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_compras'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/reportes/compras/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Compras</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_anulaciones'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/reportes/anulaciones/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Anulaciones</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_cambios'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/reportes/cambios/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Cambios</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['graficas_financiero'] == 1):?>
                                    <!--<li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/reportes_financieros/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Financiero</span>
                                        </a>
                                    </li>-->
                                    <?php endif; if($user_type == 1 || $permisos['actividad_usuarios'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/reportes/actividad/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-line-graph"></i>
                                            </span>
                                            <span class="menu-text">Actividad de usuarios</span>
                                        </a>
                                    </li>
                                    <?php endif;?>
                                </ul>
                            </div>
                        </li>
                        <?php endif; if($user_type == 1 || ($permisos['contabilidad'] == 1 && ($permisos['ingresos'] == 1 || $permisos['ver_egresos'] == 1 || $permisos['planillas'] == 1 || !empty($permisos['vacaciones']) || $permisos['cuentas_bancarias'] == 1 || $permisos['ver_cortes'] == 1 || $permisos['historial_fel'] == 1))):?>
                        <li class="menu-item menu-item-submenu menu-item-rel <?php if($page_name == 'ingresos' ||  $page_name == 'historial_fel' || $page_name == 'detalles_ingreso' || $page_name == 'egresos' || $page_name == 'detalles_egreso' || $page_name == 'planillas' || $page_name == 'bonos' || $page_name == 'pagar_bonos' || $page_name == 'vacaciones' || $page_name == 'registrar_vacacion' || $page_name == 'detalles_vacacion' || $page_name == 'cuentas_bancarias' || $page_name == 'historial_cortes' || $page_name == 'pagar_planillas'  ) echo 'menu-item-active';?>" data-menu-toggle="click" aria-haspopup="true">
                            <a href="javascript:;" class="menu-link menu-toggle">
                                <span class="menu-text">Contabilidad</span>
                                <span class="menu-desc"></span>
                                <i class="menu-arrow"></i>
                            </a>
                            <div class="menu-submenu menu-submenu-classic menu-submenu-left">
                                <ul class="menu-subnav">
                                    <?php if($user_type == 1 || $permisos['ingresos'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/ingresos/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-diagram"></i>
                                            </span>
                                            <span class="menu-text">Ingresos</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['ver_egresos'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/egresos/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon2-graph"></i>
                                            </span>
                                            <span class="menu-text">Egresos</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['planillas'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/planillas/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-folder-1"></i>
                                            </span>
                                            <span class="menu-text">Planillas</span>
                                        </a>
                                    </li>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/bonos/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-gift"></i>
                                            </span>
                                            <span class="menu-text">Bono 14 / Aguinaldo</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || !empty($permisos['vacaciones'])):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/vacaciones/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-calendar-with-a-clock-time-tools"></i>
                                            </span>
                                            <span class="menu-text">Vacaciones</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['cuentas_bancarias'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/cuentas/bancarias/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-piggy-bank"></i>
                                            </span>
                                            <span class="menu-text">Cuentas bancarias</span>
                                        </a>
                                    </li>
                                    <?php endif; if($user_type == 1 || $permisos['ver_cortes'] == 1):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/historial_cortes/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon-coins"></i>
                                            </span>
                                            <span class="menu-text">Historial de cortes de caja</span>
                                        </a>
                                    </li>
                                    <?php endif; if(($this->db->get_where('settings', array('type'=>'FEL'))->row()->description ==1) && ($user_type == 1 || $permisos['historial_fel'] == 1) ):?>
                                    <li class="menu-item menu-item-submenu" data-menu-toggle="hover" aria-haspopup="true">
                                        <a href="<?php echo base_url();?>admin/historial_fel/" class="menu-link">
                                            <span class="svg-icon menu-icon">
                                                <i class="flaticon2-paper"></i>
                                            </span>
                                            <span class="menu-text">Historial FEL</span>
                                        </a>
                                    </li>
                                    <?php endif;?>

                                </ul>
                            </div>
                        </li>
                        <?php if($page_name == 'empleados' || $page_name == 'detalles_credito' || $page_name == 'codigos' || $page_name == 'admins' || $page_name == 'inventario' || $page_name == 'ventas' || $page_name == 'nueva_cotizacion' || $page_name == 'detalles_venta' || $page_name == 'nuevo_cambio' || $page_name == 'anulacion' || $page_name == 'roles' ):?>
                        <li class='open_panel'>
                            <div class="topbar-item">
                                <div class="btn btn-icon btn-clean btn-lg mr-1" id="click_panel" onclick="open_panel()">
                                    <span class="svg-icon svg-icon-xl svg-icon-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"></rect>
                                                <rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5"></rect>
                                                <path d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z" fill="#000000" opacity="0.3"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </li>
                        <?php endif; ?>

                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <?php if ($close_today->num_rows() == 0 && ($user_type == 1 || $permisos['registrar_cortes'] == 1)): ?>
            <div class="d-flex align-items-center py-3">
                <div class="dropdown dropdown-inline" data-toggle="tooltip" data-placement="left">
                    <a href="javascript:void(0)" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/corte_caja/')" class="btn btn-danger font-weight-bold px-6" data-toggle="tooltip" data-original-title="Corte de caja" style="border-radius:25px; background-color: black !important; border-color: black !important;">
                        <i class="icon-2x la la-coins" style='font-size: 2.2rem !important;'></i>
                    </a>
                </div>
            </div>
            <?php endif; ?>
            &nbsp; &nbsp;
            <? if($page_name != 'nueva_venta'): ?>
            <div class="d-flex align-items-center py-3">
                <div class="dropdown dropdown-inline" data-toggle="tooltip" data-placement="left">
                    <a href="<?php echo base_url().'admin/nueva_venta/';?>" data-toggle="tooltip" data-original-title="Nueva Venta" class="btn btn-success font-weight-bold px-6" style="border-radius:25px;">
                        <i class="icon-2x flaticon2-shopping-cart-1"></i>

                    </a>
                </div>
            </div>
            <? endif; ?>
        </div>
    </div>
</div>
