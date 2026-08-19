<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">
                            Agregar rol
                            <span class="d-block text-muted pt-2 font-size-sm">Aquí podrás crear un nuevo rol con permisos asignados.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="<?php echo  base_url().'admin/roles/' ;?>"
                            class="btn btn-light-primary font-weight-bolder">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <circle fill="#000000" cx="9" cy="15" r="6" />
                                        <path
                                            d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                            fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                            </span> Regresar
                        </a>
                    </div>
                    <div class="card-toolbar">
                        <div class="alert alert-blue">
                            <span class="d-block pt-2 font-size-sm">En este módulo, puedes asignar cada uno de los permisos que tendra el usuario asignado con este puesto, los botones despliegan subcategorias ya seleccionadas que puedes cambiar según sea el caso, si desactivas el permiso general los permisos específicos no se otorgarán.
                                Existen permisos que no tienen la opción de "Ver", esto por que pueden generarse registros respectivos fuera de sus módulos de vista, en caso de que no tengan esa opción específica, el boton general la activa.
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form" action="<?php echo base_url();?>admin/roles/create" method="POST"
                        enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="alert alert-custom alert-default" role="alert">
                                        <div class="alert-icon"><i class="flaticon-warning text-primary"></i>
                                        </div>
                                        <div class="alert-text">
                                            Los campos marcados con * son obligatorios.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Nombres <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="Text input with checkbox"
                                            name="name" required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <div class="input-group">
                                        <textarea class="form-control" aria-label="Text input with checkbox"
                                            name="description"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="alert alert-danger" role="alert">Los permisos se aplicarán cuando los usuarios inicien sesión</div>
                            </div>
                    <!-- Inicio bloque de Permisos-->
                        <!-- Inicio permisos generales-->
                            <div class="col-sm-12 mb-5" class="col-sm-12" style="box-shadow: 12px 7px 30px -8px rgba(166,97,252,1); border-radius: 20px;">
                                <span class="text-primary"><b>* Permisos </b></span>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <input type="checkbox" name="usuarios" value="0" onclick="ver_permisos(this, 'grupo_usuarios')"><br>
                                                    <span></span>Usuarios
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <input type="checkbox" name="productos" value="0" onclick="ver_permisos(this, 'grupo_productos')"><br>
                                                    <span></span>Productos
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <input type="checkbox" name="herramientas" value="0" onclick="ver_permisos(this, 'grupo_herramientas')"><br>
                                                    <span></span>Herramientas
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <input type="checkbox" name="reportes" value="0" onclick="ver_permisos(this, 'grupo_reportes')"><br>
                                                    <span></span>Reportes / Gráficas
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <input type="checkbox" name="contabilidad" value="0" onclick="ver_permisos(this, 'grupo_contabilidad')"><br>
                                                    <span></span>Contabilidad
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <input type="checkbox" name="sucursales" value="0" onclick="ver_permisos(this, 'grupo_sucursales')"><br>
                                                    <span></span>Sucursales
                                                </label>
                                            </span>
                                        </div>
                                        <!-- Inicio permisos de Sucursales -->
                                        <div class="p-5 mt-2 mb-5" id="grupo_sucursales" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); 
                                            border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Sucursales </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_sucursales" checked onclick="select_group(this, 'grupo_sucursales')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-info">
                                                            <label>
                                                                <input type="checkbox" name="crear_sucursales" class="grupo_sucursales" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_sucursales', 'select_sucursales')"><br>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-info">
                                                            <label>
                                                                <input type="checkbox" name="editar_sucursales" class="grupo_sucursales" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_sucursales', 'select_sucursales')"><br>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-info">
                                                            <label>
                                                                <input type="checkbox" name="eliminar_sucursales" class="grupo_sucursales" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_sucursales', 'select_sucursales')"><br>
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin permisos de Sucursales -->
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <input type="checkbox" name="calendario" value="1" checked onclick="ver_permisos(this, 'grupo_calendario')"><br>
                                                    <span></span>Calendario
                                                </label>
                                            </span>
                                        </div>
                                        <!-- Inicio permisos de Calendario -->
                                        <div class="p-5 mt-2 mb-5" id="grupo_calendario" style="display: block; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); 
                                            border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Calendario/Eventos </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_calendario" onclick="select_group(this, 'grupo_calendario')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-info">
                                                            <label>
                                                                <input type="checkbox" class="grupo_calendario" name="crear_eventos" value="0" 
                                                                    onclick="change_value(this), view_group('grupo_calendario', 'select_calendario')"><br>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-info">
                                                            <label>
                                                                <input type="checkbox" class="grupo_calendario" name="editar_eventos" value="0" 
                                                                    onclick="change_value(this), view_group('grupo_calendario', 'select_calendario')"><br>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin permisos de Calendario -->
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <input type="checkbox" name="configuracion" value="0" onclick="ver_permisos(this, 'grupo_configuracion')"><br>
                                                    <span></span>Configuración
                                                </label>
                                            </span>
                                        </div>
                                        <!-- Inicio permisos de Configuración -->
                                        <div id="grupo_configuracion" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); 
                                            border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                            <span class="text-success"><b> &nbsp; * Configuración </b></span>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-info">
                                                            <label>
                                                                <input type="checkbox" name="editar_configuraciones" value="1" checked onclick="change_value(this)"><br>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Fin permisos de Configuración -->
                                    </div>
                                </div>
                            </div>
                        <!-- Fin permisos generales -->
                            <br>
                        <!-- Inicio grupos de permisos -->
                            <!-- Inicio permisos de Usuarios -->
                            <div class="col-sm-12 mb-5" id="grupo_usuarios" style="display: none; box-shadow: 12px 7px 30px -8px rgba(166,97,252,1); border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                <span class="text-info"><b>* Usuarios </b></span>
                                <div class="row">
                                    <!-- Inicio permisos de Empleados -->
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="empleados" value="0" onclick="ver_permisos(this, 'grupo_empleados')"><br>
                                                    <span></span>Empleados
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_empleados" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Empleados </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_empleados" checked onclick="select_group(this, 'grupo_empleados')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_empleados" name="crear_empleados" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_empleados', 'select_empleados')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_empleados" name="editar_empleados" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_empleados', 'select_empleados')">
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_empleados" name="estado_empleados" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_empleados', 'select_empleados')">
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_empleados" name="reportes_empleados" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_empleados', 'select_empleados')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Empleados -->
                                    <!-- Inicio permisos de Roles -->
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="roles" value="0" onclick="ver_permisos(this, 'grupo_roles')"><br>
                                                    <span></span>Roles
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_roles" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Roles </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_roles" checked onclick="select_group(this, 'grupo_roles')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_roles" name="crear_roles" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_roles', 'select_roles')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_roles" name="editar_roles" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_roles', 'select_roles')">
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_roles" name="estado_roles" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_roles', 'select_roles')">
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_roles" name="reportes_roles" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_roles', 'select_roles')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Roles -->
                                    <!-- Inicio permisos de Administradores -->
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="admins" value="0" onclick="ver_permisos(this, 'grupo_admins')"><br>
                                                    <span></span>Administradores
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_admins" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Administradores </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_admins" checked onclick="select_group(this, 'grupo_admins')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_admins" name="crear_admins" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_admins', 'select_admins')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_admins" name="editar_admins" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_admins', 'select_admins')">
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_admins" name="estado_admins" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_admins', 'select_admins')">
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_admins" name="reportes_admins" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_admins', 'select_admins')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Administradores -->
                                    <!-- Inicio permisos de Clientes -->
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="clientes" value="0" onclick="ver_permisos(this, 'grupo_clientes')"><br>
                                                    <span></span>Clientes
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_clientes" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Clientes </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_clientes" checked onclick="select_group(this, 'grupo_clientes')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_clientes" name="crear_clientes" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_clientes', 'select_clientes')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_clientes" name="editar_clientes" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_clientes', 'select_clientes')">
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_clientes" name="estado_clientes" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_clientes', 'select_clientes')">
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_clientes" name="reportes_clientes" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_clientes', 'select_clientes')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Clientes -->
                                    <!-- Inicio permisos de Proveedores -->
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="proveedores" value="0" onclick="ver_permisos(this, 'grupo_proveedores')"><br>
                                                    <span></span>Proveedores
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2 mb-5" id="grupo_proveedores" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Proveedores </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_proveedores" checked onclick="select_group(this, 'grupo_proveedores')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_proveedores" name="crear_proveedores" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_proveedores', 'select_proveedores')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_proveedores" name="editar_proveedores" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_proveedores', 'select_proveedores')">
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_proveedores" name="estado_proveedores" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_proveedores', 'select_proveedores')">
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_proveedores" name="reportes_proveedores" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_proveedores', 'select_proveedores')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Proveedores -->
                                    <!-- Inicio permisos de Códigos -->
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="codigos" value="0" onclick="ver_permisos(this, 'grupo_codigos')"><br>
                                                    <span></span>Códigos
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2 mb-5" id="grupo_codigos" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <span class="text-success"><b> &nbsp; * Códigos </b></span>
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" name="guardar_codigos" value="1" checked onclick="change_value(this)">
                                                                <span></span>Guardar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Códigos -->
                                </div>
                            </div>
                            <!-- Fin permisos de Usuarios -->
                            <!-- Inicio permisos de Productos -->
                            <div class="col-sm-12 mb-5" id="grupo_productos" style="display: none; box-shadow: 12px 7px 30px -8px rgba(166,97,252,1); border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                <span class="text-info"><b>* Productos </b></span>
                                <div class="row">
                                    <!-- Inicio permisos de Inventario -->
                                    <div class="col-sm-12">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="inventario" value="0" onclick="ver_permisos(this, 'grupo_inventario')"><br>
                                                    <span></span>Inventario
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_inventario" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Inventario </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_inventario" checked onclick="select_group(this, 'grupo_inventario')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_inventario" name="crear_productos" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_inventario', 'select_inventario')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_inventario" name="editar_productos" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_inventario', 'select_inventario')">
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_inventario" name="estado_productos" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_inventario', 'select_inventario')">
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_inventario" name="reportes_productos" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_inventario', 'select_inventario')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_inventario" name="alertas_productos" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_inventario', 'select_inventario')">
                                                                <span></span>Productos en alertas
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_inventario" name="reportes_alertas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_inventario', 'select_inventario')">
                                                                <span></span>Reportes de alertas
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Inventario -->
                                    <!-- Inicio permisos de Traslados -->
                                    <div class="col-sm-4">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="traslados" value="0" onclick="ver_permisos(this, 'grupo_traslados')"><br>
                                                    <span></span>Traslados
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2 mb-5" id="grupo_traslados" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Traslados </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_traslados" checked onclick="select_group(this, 'grupo_traslados')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_traslados" name="crear_traslados" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_traslados', 'select_traslados')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_traslados" name="eliminar_traslados" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_traslados', 'select_traslados')">
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_traslados" name="reportes_traslados" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_traslados', 'select_traslados')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Traslados -->
                                    <!-- Inicio permisos de Categorías -->
                                    <div class="col-sm-4">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="categorias" value="0" onchange="ver_permisos(this, 'grupo_categorias')"><br>
                                                    <span></span>Categorías/Tipos
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2 mb-5" id="grupo_categorias" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Categorías/Tipos </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_categorias" checked onclick="select_group(this, 'grupo_categorias')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_categorias" name="crear_categorias" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_categorias', 'select_categorias')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_categorias" name="editar_categorias" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_categorias', 'select_categorias')">
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_categorias" name="eliminar_categorias" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_categorias', 'select_categorias')">
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_categorias" name="reportes_categorias" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_categorias', 'select_categorias')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Categorías -->
                                    <!-- Inicio permisos de Marcas -->
                                    <div class="col-sm-4">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="marcas" value="0" onclick="ver_permisos(this, 'grupo_marcas')"><br>
                                                    <span></span>Marcas
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2 mb-5" id="grupo_marcas" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Marcas </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_marcas" checked onclick="select_group(this, 'grupo_marcas')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_marcas" name="crear_marcas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_marcas', 'select_marcas')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_marcas" name="editar_marcas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_marcas', 'select_marcas')">
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_marcas" name="eliminar_marcas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_marcas', 'select_marcas')">
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_marcas" name="reportes_marcas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_marcas', 'select_marcas')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Marcas -->
                                </div>
                            </div>
                            <!-- Fin permisos de Productos -->
                            <!-- Inicio permisos de Herramientas -->
                            <div class="col-sm-12 mb-5" id="grupo_herramientas" style="display: none; box-shadow: 12px 7px 30px -8px rgba(166,97,252,1); border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                <span class="text-info"><b>* Herramientas </b></span>
                                <div class="row">
                                    <!-- Inicio permisos de Ventas -->
                                    <div class="col-sm-4">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="ventas" value="0" onclick="ver_permisos(this, 'grupo_ventas')"><br>
                                                    <span></span>Ventas
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_ventas" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Ventas </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_ventas" checked onclick="select_group(this, 'grupo_ventas')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_ventas" name="ver_ventas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_ventas', 'select_ventas')">
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_ventas" name="crear_ventas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_ventas', 'select_ventas')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_ventas" name="eliminar_ventas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_ventas', 'select_ventas')">
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_ventas" name="reportes_ventas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_ventas', 'select_ventas')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Ventas -->
                                    <!-- Inicio permisos de Compras -->
                                    <div class="col-sm-8">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="compras" value="0" onclick="ver_permisos(this, 'grupo_compras')"><br>
                                                    <span></span>Compras
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_compras" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Compras </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_compras" checked onclick="select_group(this, 'grupo_compras')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_compras" name="ver_compras" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_compras', 'select_compras')">
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_compras" name="crear_compras" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_compras', 'select_compras')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_compras" name="crear_solicitud" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_compras', 'select_compras')">
                                                                <span></span>Crear solicitud
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_compras" name="ver_solicitud" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_compras', 'select_compras')">
                                                                <span></span>Ver solicitud
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_compras" name="completar_solicitud" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_compras', 'select_compras')">
                                                                <span></span>Completar solicitud
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_compras" name="reportes_compras" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_compras', 'select_compras')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Compras -->
                                    <!-- Inicio permisos de Cotizaciones -->
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="cotizaciones" value="0" onclick="ver_permisos(this, 'grupo_cotizaciones')"><br>
                                                    <span></span><?php if($job_id == '8') echo "ventas"; else echo "Preventas";?>
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_cotizaciones" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * <?php if($job_id == '8') echo "Ventas"; else echo "Preventas";?> </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_cotizaciones" checked onclick="select_group(this, 'grupo_cotizaciones')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cotizaciones" name="crear_cotizaciones" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cotizaciones', 'select_cotizaciones')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cotizaciones" name="ver_cotizaciones" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cotizaciones', 'select_cotizaciones')">
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cotizaciones" name="asignar_cotizaciones" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cotizaciones', 'select_cotizaciones')">
                                                                <span></span>Asignar a venta
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cotizaciones" name="editar_cotizaciones" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cotizaciones', 'select_cotizaciones')">
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cotizaciones" name="eliminar_cotizaciones" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cotizaciones', 'select_cotizaciones')">
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cotizaciones" name="reportes_cotizaciones" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cotizaciones', 'select_cotizaciones')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Cotizaciones -->
                                    <!-- Inicio permisos de Créditos -->
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="creditos" value="0" onclick="ver_permisos(this, 'grupo_creditos')"><br>
                                                    <span></span>Créditos
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_creditos" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Créditos </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_creditos" checked onclick="select_group(this, 'grupo_creditos')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_creditos" name="eliminar_creditos" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_creditos', 'select_creditos')">
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_creditos" name="reportes_creditos" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_creditos', 'select_creditos')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_creditos" name="pagos" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_creditos', 'select_creditos')">
                                                                <span></span>Pagos
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_creditos" name="eliminar_pagos" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_creditos', 'select_creditos')">
                                                                <span></span>Eliminar pagos
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Créditos -->
                                    <!-- Inicio permisos de Anulaciones -->
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="anulaciones" value="0" onclick="ver_permisos(this, 'grupo_anulaciones')"><br>
                                                    <span></span>Anulaciones
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_anulaciones" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Anulciones </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_anulaciones" checked onclick="select_group(this, 'grupo_anulaciones')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_anulaciones" name="ver_anulaciones" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_anulaciones', 'select_anulaciones')">
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_anulaciones" name="crear_anulaciones" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_anulaciones', 'select_anulaciones')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_anulaciones" name="eliminar_anulaciones" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_anulaciones', 'select_anulaciones')">
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_anulaciones" name="reportes_anulaciones" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_anulaciones', 'select_anulaciones')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Anulaciones -->
                                    <!-- Inicio permisos de Cambios -->
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="cambios" value="0" onclick="ver_permisos(this, 'grupo_cambios')"><br>
                                                    <span></span>Cambios
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_cambios" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Cambios </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_cambios" checked onclick="select_group(this, 'grupo_cambios')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cambios" name="ver_cambios" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cambios', 'select_cambios')">
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cambios" name="crear_cambios" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cambios', 'select_cambios')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cambios" name="eliminar_cambios" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cambios', 'select_cambios')">
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cambios" name="reportes_cambios" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cambios', 'select_cambios')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Cambios -->
                                    <!-- Inicio permisos de Envíos/Entregas -->
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="envios" value="0" onclick="ver_permisos(this, 'grupo_envios')"><br>
                                                    <span></span>Envíos/Entregas
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2 mb-5" id="grupo_envios" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Envíos/Entregas </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_envios" checked onclick="select_group(this, 'grupo_envios')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_envios" name="ver_envios" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_envios', 'select_envios')">
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_envios" name="registrar_entregas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_envios', 'select_envios')">
                                                                <span></span>Registrar entregas
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_envios" name="cancelar_envios" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_envios', 'select_envios')">
                                                                <span></span>Cancelar envíos
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_envios" name="reportes_envios" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_envios', 'select_envios')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_envios" name="editar_entregas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_envios', 'select_envios')">
                                                                <span></span>Editar entregas
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_envios" name="eliminar_entregas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_envios', 'select_envios')">
                                                                <span></span>Eliminar entregas
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Envíos/Entregas -->
                                    <!-- Inicio permisos de Transportes/Servicios -->
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="transportes" value="0" onclick="ver_permisos(this, 'grupo_transportes')"><br>
                                                    <span></span>Transportes/Servicios
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2 mb-5" id="grupo_transportes" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Transportes/Servicios </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_transportes" checked onclick="select_group(this, 'grupo_transportes')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_transportes" name="crear_transportes" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_transportes', 'select_transportes')">
                                                                <span></span>Crear transportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_transportes" name="editar_transportes" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_transportes', 'select_transportes')">
                                                                <span></span>Editar transportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_transportes" name="eliminar_transportes" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_transportes', 'select_transportes')">
                                                                <span></span>Eliminar transportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_transportes" name="reportes_transportes" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_transportes', 'select_transportes')">
                                                                <span></span>Reportes transportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_transportes" name="registrar_servicios" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_transportes', 'select_transportes')">
                                                                <span></span>Registrar servicios
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_transportes" name="reportes_servicios" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_transportes', 'select_transportes')">
                                                                <span></span>Reportes servicios
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Transportes/Servicios -->
                                </div>
                            </div>
                            <!-- Inicio permisos de Reportes/Gráficas -->
                            <div class="col-sm-12 mb-5" id="grupo_reportes" style="display: none; box-shadow: 12px 7px 30px -8px rgba(166,97,252,1); border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                <div class="row">
                                    <div class="col-sm-4 mt-2 mb-2">
                                        <span class="text-info"><b> &nbsp; * Reportes/Gráficas </b></span>
                                    </div>
                                    <div class="col-sm-8 mt-2 mb-2">
                                        <label class="checkbox">
                                            <input type="checkbox" id="select_reportes" checked onclick="select_group(this, 'grupo_reportes')"/>
                                            <span></span>Todos
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" class="grupo_reportes" name="graficas_generales" value="1" checked 
                                                        onclick="change_value(this), view_group('grupo_reportes', 'select_reportes')"><br>
                                                    <span></span>Generales
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" class="grupo_reportes" name="graficas_ventas" value="1" checked 
                                                        onclick="change_value(this), view_group('grupo_reportes', 'select_reportes')"><br>
                                                    <span></span>Ventas
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" class="grupo_reportes" name="graficas_compras" value="1" checked 
                                                        onclick="change_value(this), view_group('grupo_reportes', 'select_reportes')"><br>
                                                    <span></span>Compras
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" class="grupo_reportes" name="graficas_anulaciones" value="1" checked 
                                                        onclick="change_value(this), view_group('grupo_reportes', 'select_reportes')"><br>
                                                    <span></span>Anulaciones
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" class="grupo_reportes" name="graficas_cambios" value="1" checked 
                                                        onclick="change_value(this), view_group('grupo_reportes', 'select_reportes')"><br>
                                                    <span></span>Cambios
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" class="grupo_reportes" name="graficas_financiero" value="1" checked 
                                                        onclick="change_value(this), view_group('grupo_reportes', 'select_reportes')"><br>
                                                    <span></span>Financiero
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" class="grupo_reportes" name="actividad_usuarios" value="1" checked 
                                                        onclick="change_value(this), view_group('grupo_reportes', 'select_reportes')"><br>
                                                    <span></span>Actividad de usuarios
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin permisos de Reportes/Gráficas -->
                            <!-- Inicio permisos de Contabilidad -->
                            <div class="col-sm-12 mb-5" id="grupo_contabilidad" style="display: none; box-shadow: 12px 7px 30px -8px rgba(166,97,252,1); border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                <span class="text-info"><b>* Contabilidad </b></span>
                                <div class="row">
                                    <!-- Inicio permisos de Ingresos -->
                                    <div class="col-sm-2">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="ingresos" value="0" onclick="change_value(this)"><br>
                                                    <span></span>Ingresos
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Ingresos -->
                                    <!-- Inicio permisos de Egresos -->
                                    <div class="col-sm-5">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="egresos" value="0" onclick="ver_permisos(this, 'grupo_egresos')"><br>
                                                    <span></span>Egresos
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_egresos" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Egresos </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_egresos" checked onclick="select_group(this, 'grupo_egresos')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_egresos" name="ver_egresos" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_egresos', 'select_egresos')">
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_egresos" name="crear_egresos" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_egresos', 'select_egresos')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_egresos" name="editar_egresos" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_egresos', 'select_egresos')">
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_egresos" name="reportes_egresos" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_egresos', 'select_egresos')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Egresos -->
                                    <!-- Inicio permisos de Planillas -->
                                    <div class="col-sm-5">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="planillas" value="0" onclick="ver_permisos(this, 'grupo_planillas')"><br>
                                                    <span></span>Planillas
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_planillas" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Planillas </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_planillas" checked onclick="select_group(this, 'grupo_planillas')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_planillas" name="pagar_planillas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_planillas', 'select_planillas')">
                                                                <span></span>Pagar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_planillas" name="editar_planillas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_planillas', 'select_planillas')">
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_planillas" name="estado_planillas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_planillas', 'select_planillas')">
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_planillas" name="reportes_planillas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_planillas', 'select_planillas')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Planillas -->
                                    <!-- Inicio permisos de Vacaciones -->
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="vacaciones" value="0" onclick="ver_permisos(this, 'grupo_vacaciones')"><br>
                                                    <span></span>Vacaciones
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_vacaciones" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Vacaciones </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_vacaciones" checked onclick="select_group(this, 'grupo_vacaciones')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_vacaciones" name="crear_vacaciones" value="1" checked
                                                                    onclick="change_value(this), view_group('grupo_vacaciones', 'select_vacaciones')">
                                                                <span></span>Registrar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_vacaciones" name="editar_vacaciones" value="1" checked
                                                                    onclick="change_value(this), view_group('grupo_vacaciones', 'select_vacaciones')">
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_vacaciones" name="estado_vacaciones" value="1" checked
                                                                    onclick="change_value(this), view_group('grupo_vacaciones', 'select_vacaciones')">
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Vacaciones -->
                                    <!-- Inicio permisos de Cuentas Bancarias -->
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="cuentas_bancarias" value="0" onclick="ver_permisos(this, 'grupo_cuentas')"><br>
                                                    <span></span>Cuentas bancarias
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2 mb-5" id="grupo_cuentas" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Cuentas bancarias </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_cuentas" checked onclick="select_group(this, 'grupo_cuentas')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cuentas" name="crear_cuentas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cuentas', 'select_cuentas')">
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cuentas" name="editar_cuentas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cuentas', 'select_cuentas')">
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cuentas" name="transferir_cuentas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cuentas', 'select_cuentas')">
                                                                <span></span>Transferir fondos
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cuentas" name="eliminar_cuentas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cuentas', 'select_cuentas')">
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cuentas" name="reportes_cuentas" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_cuentas', 'select_cuentas')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Cuentas Bancarias -->
                                    <!-- Inicio permisos de Historial de cortes de caja -->
                                    <div class="col-sm-3">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="historial_caja" value="0" onclick="ver_permisos(this, 'grupo_historial_caja')"><br>
                                                    <span></span>Historial de cortes de caja
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2 mb-5" id="grupo_historial_caja" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Historial de cortes de caja </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_historial_caja" checked onclick="select_group(this, 'grupo_historial_caja')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_historial_caja" name="registrar_cortes" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_historial_caja', 'select_historial_caja')">
                                                                <span></span>Registrar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_historial_caja" name="ver_cortes" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_historial_caja', 'select_historial_caja')">
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Historial de cortes de caja -->
                                    <!-- Inicio permisos de Historial FEL -->
                                    <div class="col-sm-3">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="historial_fel" value="0" onclick="ver_permisos(this, 'grupo_historial_fel')"><br>
                                                    <span></span>Historial FEL
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2 mb-5" id="grupo_historial_fel" style="display: none; box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Historial FEL </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_historial_fel" checked onclick="select_group(this, 'grupo_historial_fel')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_historial_fel" name="reportes_fel" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_historial_fel', 'select_historial_fel')">
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_historial_fel" name="eliminar_fel" value="1" checked 
                                                                    onclick="change_value(this), view_group('grupo_historial_fel', 'select_historial_fel')">
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Fin permisos de Historial FEL -->
                                </div>
                            </div>
                            <!-- Fin permisos de Contabilidad -->
                        </div>
                        <!-- Fin grupos de permisos -->
                        <button type="submit" class="btn btn-primary font-weight-bold" id="add_emp_submit" style='float: right; margin-top: 5px;'>
                            Guardar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">

function ver_permisos(checkbox, grupo) {
    if ( $(checkbox).is(':checked') ) {
        $(checkbox).val(1);
        $('#'+grupo).show(500);
    }
    else {
        $(checkbox).val(0);
        $('#'+grupo).hide(500);
    }
}

function change_value(checkbox) {
    if ( $(checkbox).is(':checked') ) {
        $(checkbox).val(1);
    }
    else {
        $(checkbox).val(0);
    }
}

function select_group(checkbox, group) {
    $("."+group).prop("checked", checkbox.checked);
    $('.'+group).each(function(){
        change_value(this);
    });
}

function view_group(grupo, checkbox) {
  if ($("."+grupo).length == $("."+grupo+":checked").length) {
    $("#"+checkbox).prop("checked", true);
  } else {
    $("#"+checkbox).prop("checked", false);
  }
}
</script>