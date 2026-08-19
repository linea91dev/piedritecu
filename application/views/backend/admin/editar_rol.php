<?php $ID = base64_decode($rol_id); $edit_rol = $this->db->get_where('job', array('job_id' => $ID))->row_array(); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">
                            Actualizar rol
                            <span class="d-block text-muted pt-2 font-size-sm">Aquí podrás editar los permisos ya asignados a este rol.</span>
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
                </div>
                <div class="card-body">
                    <form class="form" action="<?php echo base_url();?>admin/roles/update/<?php echo $ID;?>" method="POST"
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
                                            name="name" required value="<?php echo $edit_rol['name']; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <div class="input-group">
                                        <textarea class="form-control" aria-label="Text input with checkbox"
                                            name="description"><?php echo $edit_rol['description']; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="alert alert-danger" role="alert">Los cambios se aplicarán cuando los usuarios vuelvan a iniciar sesión </div>
                            </div>
                        <?php $permisos = unserialize($edit_rol['permissions']);?>
                    <!-- Inicio bloque de Permisos-->
                        <!-- Inicio permisos generales-->
                            <div class="col-sm-12 mb-5" style="box-shadow: 12px 7px 30px -8px rgba(166,97,252,1); border-radius: 20px;">
                                <span class="text-primary"><b>* Permisos </b></span>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <input type="checkbox" name="usuarios" value="<?php echo $permisos['usuarios'];?>" onclick="ver_permisos(this, 'grupo_usuarios')"
                                                        <?php if ($permisos['usuarios'] == 1) echo "checked";?>><br>
                                                    <span></span>Usuarios
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <input type="checkbox" name="productos" value="<?php echo $permisos['productos'];?>" onclick="ver_permisos(this, 'grupo_productos')"
                                                        <?php if ($permisos['productos'] == 1) echo "checked";?>><br>
                                                    <span></span>Productos
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <input type="checkbox" name="herramientas" value="<?php echo $permisos['herramientas'];?>" onclick="ver_permisos(this, 'grupo_herramientas')"
                                                        <?php if ($permisos['herramientas'] == 1) echo "checked";?>><br>
                                                    <span></span>Herramientas
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <input type="checkbox" name="reportes" value="<?php echo $permisos['reportes'];?>" onclick="ver_permisos(this, 'grupo_reportes')"
                                                        <?php if ($permisos['reportes'] == 1) echo "checked";?>><br>
                                                    <span></span>Reportes / Gráficas
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <input type="checkbox" name="contabilidad" value="<?php echo $permisos['contabilidad'];?>" onclick="ver_permisos(this, 'grupo_contabilidad')"
                                                        <?php if ($permisos['contabilidad'] == 1) echo "checked";?>><br>
                                                    <span></span>Contabilidad
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <input type="checkbox" name="sucursales" value="<?php echo $permisos['sucursales'];?>" onclick="ver_permisos(this, 'grupo_sucursales')"
                                                        <?php if ($permisos['sucursales'] == 1) echo "checked";?>><br>
                                                    <span></span>Sucursales
                                                </label>
                                            </span>
                                        </div>
                                        <!-- Inicio permisos de Sucursales -->
                                        <div class='p-5 mt-2 mb-5' id="grupo_sucursales" style="display: <?php if ($permisos['sucursales'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Sucursales </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_sucursales" onclick="select_group(this, 'grupo_sucursales')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-info">
                                                            <label>
                                                                <input type="checkbox" class="grupo_sucursales" name="crear_sucursales" value="<?php echo $permisos['crear_sucursales'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_sucursales', 'select_sucursales')"
                                                                    <?php if ($permisos['crear_sucursales'] == 1) echo "checked";?>><br>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-info">
                                                            <label>
                                                                <input type="checkbox" class="grupo_sucursales" name="editar_sucursales" value="<?php echo $permisos['editar_sucursales'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_sucursales', 'select_sucursales')"
                                                                    <?php if ($permisos['editar_sucursales'] == 1) echo "checked";?>><br>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-info">
                                                            <label>
                                                                <input type="checkbox" class="grupo_sucursales" name="eliminar_sucursales" value="<?php echo $permisos['eliminar_sucursales'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_sucursales', 'select_sucursales')"
                                                                    <?php if ($permisos['eliminar_sucursales'] == 1) echo "checked";?>><br>
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
                                                    <input type="checkbox" name="calendario" value="<?php echo $permisos['calendario'];?>" onclick="ver_permisos(this, 'grupo_calendario')"
                                                        <?php if ($permisos['calendario'] == 1) echo "checked";?>><br>
                                                    <span></span>Calendario
                                                </label>
                                            </span>
                                        </div>
                                        <!-- Inicio permisos de Calendario -->
                                        <div class="p-5 mt-2" id="grupo_calendario" style="display: <?php if ($permisos['calendario'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Calendario </b></span>
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
                                                                <input type="checkbox" class="grupo_calendario" name="crear_eventos" value="<?php echo $permisos['crear_eventos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_calendario', 'select_calendario')"
                                                                    <?php if ($permisos['crear_eventos'] == 1) echo "checked";?>><br>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-info">
                                                            <label>
                                                                <input type="checkbox" class="grupo_calendario" name="editar_eventos" value="<?php echo $permisos['editar_eventos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_calendario', 'select_calendario')"
                                                                    <?php if ($permisos['editar_eventos'] == 1) echo "checked";?>><br>
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
                                                    <input type="checkbox" name="configuracion" value="<?php echo $permisos['configuracion'];?>" onclick="ver_permisos(this, 'grupo_configuracion')"
                                                        <?php if ($permisos['configuracion'] == 1) echo "checked";?>><br>
                                                    <span></span>Configuración
                                                </label>
                                            </span>
                                        </div>
                                        <!-- Inicio permisos de Configuración -->
                                        <div class="p-5 mt-2"  id="grupo_configuracion" style="display: <?php if ($permisos['configuracion'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                            <span class="text-success"><b> &nbsp; * Configuración </b></span>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-info">
                                                            <label>
                                                                <input type="checkbox" name="editar_configuracion" value="<?php echo $permisos['editar_configuracion'];?>" onclick="change_value(this)"
                                                                    <?php if ($permisos['editar_configuracion'] == 1) echo "checked";?>><br>
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
                            <br>
                            <div class="col-sm-12 mb-5" id="grupo_usuarios" style="display: <?php if ($permisos['usuarios'] == 1) echo "block"; else echo "none";?>; 
                                box-shadow: 12px 7px 30px -8px rgba(166,97,252,1); border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                <span class="text-info"><b>* Usuarios </b></span>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="empleados" value="<?php echo $permisos['empleados'];?>" onchange="ver_permisos(this, 'grupo_empleados')"
                                                        <?php if ($permisos['empleados'] == 1) echo "checked";?>><br>
                                                    <span></span>Empleados
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_empleados" style="display: <?php if ($permisos['empleados'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row p-5 ">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Empleados </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_empleados" onclick="select_group(this, 'grupo_empleados')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_empleados" name="crear_empleados" value="<?php echo $permisos['crear_empleados'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_empleados', 'select_empleados')"
                                                                    <?php if ($permisos['crear_empleados'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_empleados" name="editar_empleados" value="<?php echo $permisos['editar_empleados'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_empleados', 'select_empleados')"
                                                                    <?php if ($permisos['editar_empleados'] == 1) echo "checked";?>>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_empleados" name="estado_empleados" value="<?php echo $permisos['estado_empleados'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_empleados', 'select_empleados')"
                                                                    <?php if ($permisos['estado_empleados'] == 1) echo "checked";?>>
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_empleados" name="reportes_empleados" value="<?php echo $permisos['reportes_empleados'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_empleados', 'select_empleados')"
                                                                    <?php if ($permisos['reportes_empleados'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="roles" value="<?php echo $permisos['roles'];?>" onclick="ver_permisos(this, 'grupo_roles')"
                                                        <?php if ($permisos['roles'] == 1) echo "checked";?>><br>
                                                    <span></span>Roles
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_roles" style="display: <?php if ($permisos['roles'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row p-5 mt-2">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Roles </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_roles" onclick="select_group(this, 'grupo_roles')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_roles" name="crear_roles" value="<?php echo $permisos['crear_roles'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_roles', 'select_roles')"
                                                                    <?php if ($permisos['crear_roles'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_roles" name="editar_roles" value="<?php echo $permisos['editar_roles'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_roles', 'select_roles')"
                                                                    <?php if ($permisos['editar_roles'] == 1) echo "checked";?>>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_roles" name="estado_roles" value="<?php echo $permisos['estado_roles'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_roles', 'select_roles')"
                                                                    <?php if ($permisos['estado_roles'] == 1) echo "checked";?>>
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_roles" name="reportes_roles" value="<?php echo $permisos['reportes_roles'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_roles', 'select_roles')"
                                                                    <?php if ($permisos['reportes_roles'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="admins" value="<?php echo $permisos['admins'];?>" onclick="ver_permisos(this, 'grupo_admins')"
                                                        <?php if ($permisos['admins'] == 1) echo "checked";?>><br>
                                                    <span></span>Administradores
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_admins" style="display: <?php if ($permisos['admins'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6 mb-2">
                                                    <span class="text-success"><b> &nbsp; * Administradores </b></span>
                                                </div>
                                                <div class="col-sm-6 mb-2">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_admins" onclick="select_group(this, 'grupo_admins')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_admins" name="crear_admins" value="<?php echo $permisos['crear_admins'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_admins', 'select_admins')"
                                                                    <?php if ($permisos['crear_admins'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_admins" name="editar_admins" value="<?php echo $permisos['editar_admins'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_admins', 'select_admins')"
                                                                    <?php if ($permisos['editar_admins'] == 1) echo "checked";?>>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_admins" name="estado_admins" value="<?php echo $permisos['estado_admins'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_admins', 'select_admins')"
                                                                    <?php if ($permisos['estado_admins'] == 1) echo "checked";?>>
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_admins" name="reportes_admins" value="<?php echo $permisos['reportes_admins'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_admins', 'select_admins')"
                                                                    <?php if ($permisos['reportes_admins'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="clientes" value="<?php echo $permisos['clientes'];?>" onclick="ver_permisos(this, 'grupo_clientes')"
                                                        <?php if ($permisos['clientes'] == 1) echo "checked";?>><br>
                                                    <span></span>Clientes
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_clientes" style="display: <?php if ($permisos['clientes'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Clientes </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_clientes" onclick="select_group(this, 'grupo_clientes')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_clientes" name="crear_clientes" value="<?php echo $permisos['crear_clientes'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_clientes', 'select_clientes')"
                                                                    <?php if ($permisos['crear_clientes'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_clientes" name="editar_clientes" value="<?php echo $permisos['editar_clientes'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_clientes', 'select_clientes')"
                                                                    <?php if ($permisos['editar_clientes'] == 1) echo "checked";?>>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_clientes" name="estado_clientes" value="<?php echo $permisos['estado_clientes'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_clientes', 'select_clientes')"
                                                                    <?php if ($permisos['estado_clientes'] == 1) echo "checked";?>>
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_clientes" name="reportes_clientes" value="<?php echo $permisos['reportes_clientes'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_clientes', 'select_clientes')"
                                                                    <?php if ($permisos['reportes_clientes'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="proveedores" value="<?php echo $permisos['proveedores'];?>" onclick="ver_permisos(this, 'grupo_proveedores')"
                                                        <?php if ($permisos['proveedores'] == 1) echo "checked";?>><br>
                                                    <span></span>Proveedores
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2 mb-5" id="grupo_proveedores" style="display: <?php if ($permisos['proveedores'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Proveedores </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_proveedores" onclick="select_group(this, 'grupo_proveedores')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_proveedores" name="crear_proveedores" value="<?php echo $permisos['crear_proveedores'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_proveedores', 'select_proveedores')"
                                                                    <?php if ($permisos['crear_proveedores'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_proveedores" name="editar_proveedores" value="<?php echo $permisos['editar_proveedores'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_proveedores', 'select_proveedores')"
                                                                    <?php if ($permisos['editar_proveedores'] == 1) echo "checked";?>>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_proveedores" name="estado_proveedores" value="<?php echo $permisos['estado_proveedores'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_proveedores', 'select_proveedores')"
                                                                    <?php if ($permisos['estado_proveedores'] == 1) echo "checked";?>>
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_proveedores" name="reportes_proveedores" value="<?php echo $permisos['reportes_proveedores'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_proveedores', 'select_proveedores')"
                                                                    <?php if ($permisos['reportes_proveedores'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="codigos" value="<?php echo $permisos['codigos'];?>" onclick="ver_permisos(this, 'grupo_codigos')"
                                                        <?php if ($permisos['codigos'] == 1) echo "checked";?>><br>
                                                    <span></span>Códigos
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2 mb-5" id="grupo_codigos" style="display: <?php if ($permisos['codigos'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <span class="text-success"><b> &nbsp; * Códigos </b></span>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" name="guardar_codigos" value="<?php echo $permisos['guardar_codigos'];?>" 
                                                                    onclick="change_value(this)" <?php if ($permisos['guardar_codigos'] == 1) echo "checked";?>>
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
                            <div class="col-sm-12 mb-5" id="grupo_productos" style="display: <?php if ($permisos['productos'] == 1) echo "block"; else echo "none";?>; 
                                box-shadow: 12px 7px 30px -8px rgba(166,97,252,1); border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                <span class="text-info"><b>* Productos </b></span>
                                <div class="row">
                                    <!-- Inicio permisos de Inventario -->
                                    <div class="col-sm-12">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="inventario" value="<?php echo $permisos['inventario'];?>" onclick="ver_permisos(this, 'grupo_inventario')"
                                                        <?php if ($permisos['inventario'] == 1) echo "checked";?>><br>
                                                    <span></span>Inventario
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_inventario" style="display: <?php if ($permisos['inventario'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Inventario </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_inventario" onclick="select_group(this, 'grupo_inventario')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_inventario" name="crear_productos" value="<?php echo $permisos['crear_productos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_inventario', 'select_inventario')"
                                                                    <?php if ($permisos['crear_productos'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_inventario" name="editar_productos" value="<?php echo $permisos['editar_productos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_inventario', 'select_inventario')"
                                                                    <?php if ($permisos['editar_productos'] == 1) echo "checked";?>>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_inventario" name="estado_productos" value="<?php echo $permisos['estado_productos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_inventario', 'select_inventario')"
                                                                    <?php if ($permisos['estado_productos'] == 1) echo "checked";?>>
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_inventario" name="reportes_productos" value="<?php echo $permisos['reportes_productos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_inventario', 'select_inventario')"
                                                                    <?php if ($permisos['reportes_productos'] == 1) echo "checked";?>>
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_inventario" name="alertas_productos" value="<?php echo $permisos['alertas_productos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_inventario', 'select_inventario')"
                                                                    <?php if ($permisos['alertas_productos'] == 1) echo "checked";?>>
                                                                <span></span>Productos en alertas
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_inventario" name="reportes_alertas" value="<?php echo $permisos['reportes_alertas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_inventario', 'select_inventario')"
                                                                    <?php if ($permisos['reportes_alertas'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="traslados" value="<?php echo $permisos['traslados'];?>" onclick="ver_permisos(this, 'grupo_traslados')"
                                                        <?php if ($permisos['traslados'] == 1) echo "checked";?>><br>
                                                    <span></span>Traslados
                                                </label>
                                            </span>
                                        </div>
                                        <div  class="p-5 mt-2" id="grupo_traslados" style="display: <?php if ($permisos['traslados'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Traslados </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_traslados" onclick="select_group(this, 'grupo_traslados')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_traslados" name="crear_traslados" value="<?php echo $permisos['crear_traslados'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_traslados', 'select_traslados')"
                                                                    <?php if ($permisos['crear_traslados'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_traslados" name="eliminar_traslados" value="<?php echo $permisos['eliminar_traslados'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_traslados', 'select_traslados')"
                                                                    <?php if ($permisos['eliminar_traslados'] == 1) echo "checked";?>>
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_traslados" name="reportes_traslados" value="<?php echo $permisos['reportes_traslados'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_traslados', 'select_traslados')"
                                                                    <?php if ($permisos['reportes_traslados'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="categorias" value="<?php echo $permisos['categorias'];?>" onchange="ver_permisos(this, 'grupo_categorias')"
                                                        <?php if ($permisos['categorias'] == 1) echo "checked";?>><br>
                                                    <span></span>Categorías/Tipos
                                                </label>
                                            </span>
                                        </div>
                                        <div  class="p-5 mt-2" id="grupo_categorias" style="display: <?php if ($permisos['categorias'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Categorías/Tipos </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_categorias" onclick="select_group(this, 'grupo_categorias')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_categorias" name="crear_categorias" value="<?php echo $permisos['crear_categorias'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_categorias', 'select_categorias')"
                                                                    <?php if ($permisos['crear_categorias'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_categorias" name="editar_categorias" value="<?php echo $permisos['editar_categorias'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_categorias', 'select_categorias')"
                                                                    <?php if ($permisos['editar_categorias'] == 1) echo "checked";?>>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_categorias" name="eliminar_categorias" value="<?php echo $permisos['eliminar_categorias'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_categorias', 'select_categorias')"
                                                                    <?php if ($permisos['eliminar_categorias'] == 1) echo "checked";?>>
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_categorias" name="reportes_categorias" value="<?php echo $permisos['reportes_categorias'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_categorias', 'select_categorias')"
                                                                    <?php if ($permisos['reportes_categorias'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="marcas" value="<?php echo $permisos['marcas'];?>" onclick="ver_permisos(this, 'grupo_marcas')"
                                                        <?php if ($permisos['marcas'] == 1) echo "checked";?>><br>
                                                    <span></span>Marcas
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_marcas" style="display: <?php if ($permisos['marcas'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Marcas </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_marcas" onclick="select_group(this, 'grupo_marcas')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_marcas" name="crear_marcas" value="<?php echo $permisos['crear_marcas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_marcas', 'select_marcas')"
                                                                    <?php if ($permisos['crear_marcas'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_marcas" name="editar_marcas" value="<?php echo $permisos['editar_marcas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_marcas', 'select_marcas')"
                                                                    <?php if ($permisos['editar_marcas'] == 1) echo "checked";?>>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_marcas" name="eliminar_marcas" value="<?php echo $permisos['eliminar_marcas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_marcas', 'select_marcas')"
                                                                    <?php if ($permisos['eliminar_marcas'] == 1) echo "checked";?>>
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_marcas" name="reportes_marcas" value="<?php echo $permisos['reportes_marcas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_marcas', 'select_marcas')"
                                                                    <?php if ($permisos['reportes_marcas'] == 1) echo "checked";?>>
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
                            <div class="col-sm-12 mb-5" id="grupo_herramientas" style="display: <?php if ($permisos['herramientas'] == 1) echo "block"; else echo "none";?>; 
                                box-shadow: 12px 7px 30px -8px rgba(166,97,252,1); border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                <span class="text-info"><b>* Herramientas </b></span>
                                <div class="row">
                                    <!-- Inicio permisos de Ventas -->
                                    <div class="col-sm-4">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="ventas" value="<?php echo $permisos['ventas'];?>" onclick="ver_permisos(this, 'grupo_ventas')"
                                                        <?php if ($permisos['ventas'] == 1) echo "checked";?>><br>
                                                    <span></span>Ventas
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_ventas" style="display: <?php if ($permisos['ventas'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Ventas </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_ventas" onclick="select_group(this, 'grupo_ventas')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_ventas" name="ver_ventas" value="<?php echo $permisos['ver_ventas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_ventas', 'select_ventas')"
                                                                    <?php if ($permisos['ver_ventas'] == 1) echo "checked";?>>
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_ventas" name="crear_ventas" value="<?php echo $permisos['crear_ventas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_ventas', 'select_ventas')"
                                                                    <?php if ($permisos['crear_ventas'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_ventas" name="eliminar_ventas" value="<?php echo $permisos['eliminar_ventas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_ventas', 'select_ventas')"
                                                                    <?php if ($permisos['eliminar_ventas'] == 1) echo "checked";?>>
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_ventas" name="reportes_ventas" value="<?php echo $permisos['reportes_ventas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_ventas', 'select_ventas')"
                                                                    <?php if ($permisos['reportes_ventas'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="compras" value="<?php echo $permisos['compras'];?>" onclick="ver_permisos(this, 'grupo_compras')"
                                                        <?php if ($permisos['compras'] == 1) echo "checked";?>><br>
                                                    <span></span>Compras
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_compras" style="display: <?php if ($permisos['compras'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Compras </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_compras" onclick="select_group(this, 'grupo_compras')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_compras" name="ver_compras" value="<?php echo $permisos['ver_compras'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_compras', 'select_compras')"
                                                                    <?php if ($permisos['ver_compras'] == 1) echo "checked";?>>
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_compras" name="crear_compras" value="<?php echo $permisos['crear_compras'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_compras', 'select_compras')"
                                                                    <?php if ($permisos['crear_compras'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_compras" name="crear_solicitud" value="<?php echo $permisos['crear_solicitud'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_compras', 'select_compras')"
                                                                    <?php if ($permisos['crear_solicitud'] == 1) echo "checked";?>>
                                                                <span></span>Crear solicitud
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_compras" name="ver_solicitud" value="<?php echo $permisos['ver_solicitud'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_compras', 'select_compras')"
                                                                    <?php if ($permisos['ver_solicitud'] == 1) echo "checked";?>>
                                                                <span></span>Ver solicitud
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_compras" name="completar_solicitud" value="<?php echo $permisos['completar_solicitud'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_compras', 'select_compras')"
                                                                    <?php if ($permisos['completar_solicitud'] == 1) echo "checked";?>>
                                                                <span></span>Completar solicitud
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_compras" name="reportes_compras" value="<?php echo $permisos['reportes_compras'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_compras', 'select_compras')"
                                                                    <?php if ($permisos['reportes_compras'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="cotizaciones" value="<?php echo $permisos['cotizaciones'];?>" onclick="ver_permisos(this, 'grupo_cotizaciones')"
                                                        <?php if ($permisos['cotizaciones'] == 1) echo "checked";?>><br>
                                                    <span></span><?php if($job_id == '8') echo "Venta"; else echo "Preventa";?>
                                                </label>
                                            </span>
                                        </div>
                                        <div  class="p-5 mt-2" id="grupo_cotizaciones" style="display: <?php if ($permisos['cotizaciones'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * <?php if($job_id == '8') echo "Venta"; else echo "Preventa";?> </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_cotizaciones" onclick="select_group(this, 'grupo_cotizaciones')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cotizaciones" name="crear_cotizaciones" value="<?php echo $permisos['crear_cotizaciones'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cotizaciones', 'select_cotizaciones')"
                                                                    <?php if ($permisos['crear_cotizaciones'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cotizaciones" name="ver_cotizaciones" value="<?php echo $permisos['ver_cotizaciones'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cotizaciones', 'select_cotizaciones')"
                                                                    <?php if ($permisos['ver_cotizaciones'] == 1) echo "checked";?>>
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cotizaciones" name="asignar_cotizaciones" value="<?php echo $permisos['asignar_cotizaciones'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cotizaciones', 'select_cotizaciones')"
                                                                    <?php if ($permisos['asignar_cotizaciones'] == 1) echo "checked";?>>
                                                                <span></span>Asignar a venta
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cotizaciones" name="editar_cotizaciones" value="<?php echo $permisos['editar_cotizaciones'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cotizaciones', 'select_cotizaciones')"
                                                                    <?php if ($permisos['editar_cotizaciones'] == 1) echo "checked";?>>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cotizaciones" name="eliminar_cotizaciones" value="<?php echo $permisos['eliminar_cotizaciones'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cotizaciones', 'select_cotizaciones')"
                                                                    <?php if ($permisos['eliminar_cotizaciones'] == 1) echo "checked";?>>
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cotizaciones" name="reportes_cotizaciones" value="<?php echo $permisos['reportes_cotizaciones'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cotizaciones', 'select_cotizaciones')"
                                                                    <?php if ($permisos['reportes_cotizaciones'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="creditos" value="<?php echo $permisos['creditos'];?>" onclick="ver_permisos(this, 'grupo_creditos')"
                                                        <?php if ($permisos['creditos'] == 1) echo "checked";?>><br>
                                                    <span></span>Créditos
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_creditos" style="display: <?php if ($permisos['creditos'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Créditos </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_creditos" onclick="select_group(this, 'grupo_creditos')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_creditos" name="eliminar_creditos" value="<?php echo $permisos['eliminar_creditos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_creditos', 'select_creditos')"
                                                                    <?php if ($permisos['eliminar_creditos'] == 1) echo "checked";?>>
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_creditos" name="reportes_creditos" value="<?php echo $permisos['reportes_creditos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_creditos', 'select_creditos')"
                                                                    <?php if ($permisos['reportes_creditos'] == 1) echo "checked";?>>
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_creditos" name="pagos" value="<?php echo $permisos['pagos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_creditos', 'select_creditos')"
                                                                    <?php if ($permisos['pagos'] == 1) echo "checked";?>>
                                                                <span></span>Pagos
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_creditos" name="eliminar_pagos" value="<?php echo $permisos['eliminar_pagos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_creditos', 'select_creditos')"
                                                                    <?php if ($permisos['eliminar_pagos'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="anulaciones" value="<?php echo $permisos['anulaciones'];?>" onclick="ver_permisos(this, 'grupo_anulaciones')"
                                                        <?php if ($permisos['anulaciones'] == 1) echo "checked";?>><br>
                                                    <span></span>Anulaciones
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_anulaciones" style="display: <?php if ($permisos['anulaciones'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Anulaciones </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_anulaciones" onclick="select_group(this, 'grupo_anulaciones')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_anulaciones" name="ver_anulaciones" value="<?php echo $permisos['ver_anulaciones'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_anulaciones', 'select_anulaciones')"
                                                                    <?php if ($permisos['ver_anulaciones'] == 1) echo "checked";?>>
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_anulaciones" name="crear_anulaciones" value="<?php echo $permisos['crear_anulaciones'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_anulaciones', 'select_anulaciones')"
                                                                    <?php if ($permisos['crear_anulaciones'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_anulaciones" name="eliminar_anulaciones" value="<?php echo $permisos['eliminar_anulaciones'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_anulaciones', 'select_anulaciones')"
                                                                    <?php if ($permisos['eliminar_anulaciones'] == 1) echo "checked";?>>
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_anulaciones" name="reportes_anulaciones" value="<?php echo $permisos['reportes_anulaciones'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_anulaciones', 'select_anulaciones')"
                                                                    <?php if ($permisos['reportes_anulaciones'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="cambios" value="<?php echo $permisos['cambios'];?>" onclick="ver_permisos(this, 'grupo_cambios')"
                                                        <?php if ($permisos['cambios'] == 1) echo "checked";?>><br>
                                                    <span></span>Cambios
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_cambios" style="display: <?php if ($permisos['cambios'] == 1) echo "block"; else echo "none";?>; 
                                                box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Cambios </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_cambios" onclick="select_group(this, 'grupo_cambios')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cambios" name="ver_cambios" value="<?php echo $permisos['ver_cambios'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cambios', 'select_cambios')"
                                                                    <?php if ($permisos['ver_cambios'] == 1) echo "checked";?>>
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cambios" name="crear_cambios" value="<?php echo $permisos['crear_cambios'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cambios', 'select_cambios')"
                                                                    <?php if ($permisos['crear_cambios'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cambios" name="eliminar_cambios" value="<?php echo $permisos['eliminar_cambios'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cambios', 'select_cambios')"
                                                                    <?php if ($permisos['eliminar_cambios'] == 1) echo "checked";?>>
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cambios" name="reportes_cambios" value="<?php echo $permisos['reportes_cambios'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cambios', 'select_cambios')"
                                                                    <?php if ($permisos['reportes_cambios'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="envios" value="<?php echo $permisos['envios'];?>" onclick="ver_permisos(this, 'grupo_envios')"
                                                        <?php if ($permisos['envios'] == 1) echo "checked";?>><br>
                                                    <span></span>Envíos/Entregas
                                                </label>
                                            </span>
                                        </div>
                                        <div  class="p-5 mt-2" id="grupo_envios" style="display: <?php if ($permisos['envios'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Envíos </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_envios" onclick="select_group(this, 'grupo_envios')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_envios" name="ver_envios" value="<?php echo $permisos['ver_envios'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_envios', 'select_envios')"
                                                                    <?php if ($permisos['ver_envios'] == 1) echo "checked";?>>
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_envios" name="registrar_entregas" value="<?php echo $permisos['registrar_entregas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_envios', 'select_envios')"
                                                                    <?php if ($permisos['registrar_entregas'] == 1) echo "checked";?>>
                                                                <span></span>Registrar entregas
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_envios" name="cancelar_envios" value="<?php echo $permisos['cancelar_envios'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_envios', 'select_envios')"
                                                                    <?php if ($permisos['cancelar_envios'] == 1) echo "checked";?>>
                                                                <span></span>Cancelar envíos
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_envios" name="reportes_envios" value="<?php echo $permisos['reportes_envios'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_envios', 'select_envios')"
                                                                    <?php if ($permisos['reportes_envios'] == 1) echo "checked";?>>
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_envios" name="editar_entregas" value="<?php echo $permisos['editar_entregas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_envios', 'select_envios')"
                                                                    <?php if ($permisos['editar_entregas'] == 1) echo "checked";?>>
                                                                <span></span>Editar entregas
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_envios" name="eliminar_entregas" value="<?php echo $permisos['eliminar_entregas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_envios', 'select_envios')"
                                                                    <?php if ($permisos['eliminar_entregas'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="transportes" value="<?php echo $permisos['transportes'];?>" onclick="ver_permisos(this, 'grupo_transportes')"
                                                        <?php if ($permisos['transportes'] == 1) echo "checked";?>><br>
                                                    <span></span>Transportes/Servicios
                                                </label>
                                            </span>
                                        </div>
                                        <div  class="p-5 mt-2" id="grupo_transportes" style="display: <?php if ($permisos['transportes'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Transportes/Servicios </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_transportes" onclick="select_group(this, 'grupo_transportes')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_transportes" name="crear_transportes" value="<?php echo $permisos['crear_transportes'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_transportes', 'select_transportes')"
                                                                    <?php if ($permisos['crear_transportes'] == 1) echo "checked";?>>
                                                                <span></span>Crear transportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_transportes" name="editar_transportes" value="<?php echo $permisos['editar_transportes'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_transportes', 'select_transportes')"
                                                                    <?php if ($permisos['editar_transportes'] == 1) echo "checked";?>>
                                                                <span></span>Editar transportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_transportes" name="eliminar_transportes" value="<?php echo $permisos['eliminar_transportes'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_transportes', 'select_transportes')"
                                                                    <?php if ($permisos['eliminar_transportes'] == 1) echo "checked";?>>
                                                                <span></span>Eliminar transportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_transportes" name="reportes_transportes" value="<?php echo $permisos['reportes_transportes'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_transportes', 'select_transportes')"
                                                                    <?php if ($permisos['reportes_transportes'] == 1) echo "checked";?>>
                                                                <span></span>Reportes transportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_transportes" name="registrar_servicios" value="<?php echo $permisos['registrar_servicios'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_transportes', 'select_transportes')"
                                                                    <?php if ($permisos['registrar_servicios'] == 1) echo "checked";?>>
                                                                <span></span>Registrar servicios
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_transportes" name="reportes_servicios" value="<?php echo $permisos['reportes_servicios'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_transportes', 'select_transportes')"
                                                                    <?php if ($permisos['reportes_servicios'] == 1) echo "checked";?>>
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
                            <div class="col-sm-12 mb-5" id="grupo_reportes" style="display: <?php if ($permisos['reportes'] == 1) echo "block"; else echo "none";?>; 
                                box-shadow: 12px 7px 30px -8px rgba(166,97,252,1); border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <span class="text-info"><b> &nbsp; * Reportes/Gráficas </b></span>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="checkbox">
                                            <input type="checkbox" id="select_reportes" onclick="select_group(this, 'grupo_reportes')"/>
                                            <span></span>Todos
                                        </label>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" class="grupo_reportes" name="graficas_generales" value="<?php echo $permisos['graficas_generales'];?>" 
                                                        onclick="change_value(this), view_group('grupo_reportes', 'select_reportes')"
                                                        <?php if ($permisos['graficas_generales'] == 1) echo "checked";?>><br>
                                                    <span></span>Generales
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" class="grupo_reportes" name="graficas_ventas" value="<?php echo $permisos['graficas_ventas'];?>" 
                                                        onclick="change_value(this), view_group('grupo_reportes', 'select_reportes')"
                                                        <?php if ($permisos['graficas_ventas'] == 1) echo "checked";?>><br>
                                                    <span></span>Ventas
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" class="grupo_reportes" name="graficas_compras" value="<?php echo $permisos['graficas_compras'];?>" 
                                                        onclick="change_value(this), view_group('grupo_reportes', 'select_reportes')"
                                                        <?php if ($permisos['graficas_compras'] == 1) echo "checked";?>><br>
                                                    <span></span>Compras
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" class="grupo_reportes" name="graficas_anulaciones" value="<?php echo $permisos['graficas_anulaciones'];?>" 
                                                        onclick="change_value(this), view_group('grupo_reportes', 'select_reportes')"
                                                        <?php if ($permisos['graficas_anulaciones'] == 1) echo "checked";?>><br>
                                                    <span></span>Anulaciones
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" class="grupo_reportes" name="graficas_cambios" value="<?php echo $permisos['graficas_cambios'];?>" 
                                                        onclick="change_value(this), view_group('grupo_reportes', 'select_reportes')"
                                                        <?php if ($permisos['graficas_cambios'] == 1) echo "checked";?>><br>
                                                    <span></span>Cambios
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" class="grupo_reportes" name="graficas_financiero" value="<?php echo $permisos['graficas_financiero'];?>" 
                                                        onclick="change_value(this), view_group('grupo_reportes', 'select_reportes')"
                                                        <?php if ($permisos['graficas_financiero'] == 1) echo "checked";?>><br>
                                                    <span></span>Financiero
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" class="grupo_reportes" name="actividad_usuarios" value="<?php echo $permisos['actividad_usuarios'];?>" 
                                                        onclick="change_value(this), view_group('grupo_reportes', 'select_reportes')"
                                                        <?php if ($permisos['actividad_usuarios'] == 1) echo "checked";?>><br>
                                                    <span></span>Actividad de usuarios
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Fin permisos de Reportes/Gráficas -->
                            <!-- Inicio permisos de Contabilidad -->
                            <div class="col-sm-12 mb-5" id="grupo_contabilidad" style="display: <?php if ($permisos['contabilidad'] == 1) echo "block"; else echo "none";?>; 
                                box-shadow: 12px 7px 30px -8px rgba(166,97,252,1); border-radius: 20px; margin-top: 5px; margin-botton: 5px;">
                                <span class="text-info"><b>* Contabilidad </b></span>
                                <div class="row">
                                    <!-- Inicio permisos de Ingresos -->
                                    <div class="col-sm-2">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                            <span class="switch switch-outline switch-icon switch-info">
                                                <label>
                                                    <input type="checkbox" name="ingresos" value="<?php echo $permisos['ingresos'];?>" onclick="change_value(this)"
                                                        <?php if ($permisos['ingresos'] == 1) echo "checked";?>><br>
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
                                                    <input type="checkbox" name="egresos" value="<?php echo $permisos['egresos'];?>" onclick="ver_permisos(this, 'grupo_egresos')"
                                                        <?php if ($permisos['egresos'] == 1) echo "checked";?>><br>
                                                    <span></span>Egresos
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_egresos" style="display: <?php if ($permisos['egresos'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Egresos </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_egresos" onclick="select_group(this, 'grupo_egresos')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_egresos" name="ver_egresos" value="<?php echo $permisos['ver_egresos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_egresos', 'select_egresos')"
                                                                    <?php if ($permisos['ver_egresos'] == 1) echo "checked";?>>
                                                                <span></span>Ver
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_egresos" name="crear_egresos" value="<?php echo $permisos['crear_egresos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_egresos', 'select_egresos')"
                                                                    <?php if ($permisos['crear_egresos'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_egresos" name="editar_egresos" value="<?php echo $permisos['editar_egresos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_egresos', 'select_egresos')"
                                                                    <?php if ($permisos['editar_egresos'] == 1) echo "checked";?>>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_egresos" name="reportes_egresos" value="<?php echo $permisos['reportes_egresos'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_egresos', 'select_egresos')"
                                                                    <?php if ($permisos['reportes_egresos'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="planillas" value="<?php echo $permisos['planillas'];?>" onclick="ver_permisos(this, 'grupo_planillas')"
                                                                    <?php if ($permisos['planillas'] == 1) echo "checked";?>><br>
                                                    <span></span>Planillas
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_planillas" style="display: <?php if ($permisos['planillas'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Planillas </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_planillas" onclick="select_group(this, 'grupo_planillas')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_planillas" name="pagar_planillas" value="<?php echo $permisos['pagar_planillas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_planillas', 'select_planillas')"
                                                                    <?php if ($permisos['pagar_planillas'] == 1) echo "checked";?>>
                                                                <span></span>Pagar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_planillas" name="editar_planillas" value="<?php echo $permisos['editar_planillas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_planillas', 'select_planillas')"
                                                                    <?php if ($permisos['editar_planillas'] == 1) echo "checked";?>>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_planillas" name="estado_planillas" value="<?php echo $permisos['estado_planillas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_planillas', 'select_planillas')"
                                                                    <?php if ($permisos['estado_planillas'] == 1) echo "checked";?>>
                                                                <span></span>Activar / Desactivar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_planillas" name="reportes_planillas" value="<?php echo $permisos['reportes_planillas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_planillas', 'select_planillas')"
                                                                    <?php if ($permisos['reportes_planillas'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="vacaciones" value="<?php echo !empty($permisos['vacaciones']) ? $permisos['vacaciones'] : 0;?>" onclick="ver_permisos(this, 'grupo_vacaciones')"
                                                                    <?php if (!empty($permisos['vacaciones'])) echo "checked";?>><br>
                                                    <span></span>Vacaciones
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_vacaciones" style="display: <?php if (!empty($permisos['vacaciones'])) echo "block"; else echo "none";?>;
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Vacaciones </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_vacaciones" onclick="select_group(this, 'grupo_vacaciones')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_vacaciones" name="crear_vacaciones" value="<?php echo !empty($permisos['crear_vacaciones']) ? $permisos['crear_vacaciones'] : 0;?>"
                                                                    onclick="change_value(this), view_group('grupo_vacaciones', 'select_vacaciones')"
                                                                    <?php if (!empty($permisos['crear_vacaciones'])) echo "checked";?>>
                                                                <span></span>Registrar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_vacaciones" name="editar_vacaciones" value="<?php echo !empty($permisos['editar_vacaciones']) ? $permisos['editar_vacaciones'] : 0;?>"
                                                                    onclick="change_value(this), view_group('grupo_vacaciones', 'select_vacaciones')"
                                                                    <?php if (!empty($permisos['editar_vacaciones'])) echo "checked";?>>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_vacaciones" name="estado_vacaciones" value="<?php echo !empty($permisos['estado_vacaciones']) ? $permisos['estado_vacaciones'] : 0;?>"
                                                                    onclick="change_value(this), view_group('grupo_vacaciones', 'select_vacaciones')"
                                                                    <?php if (!empty($permisos['estado_vacaciones'])) echo "checked";?>>
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
                                                    <input type="checkbox" name="cuentas_bancarias" value="<?php echo $permisos['cuentas_bancarias'];?>" onclick="ver_permisos(this, 'grupo_cuentas')"
                                                        <?php if ($permisos['cuentas_bancarias'] == 1) echo "checked";?>><br>
                                                    <span></span>Cuentas bancarias
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_cuentas" style="display: <?php if ($permisos['cuentas_bancarias'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Cuentas bancarias </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_cuentas" onclick="select_group(this, 'grupo_cuentas')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cuentas" name="crear_cuentas" value="<?php echo $permisos['crear_cuentas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cuentas', 'select_cuentas')"
                                                                    <?php if ($permisos['crear_cuentas'] == 1) echo "checked";?>>
                                                                <span></span>Crear
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cuentas" name="editar_cuentas" value="<?php echo $permisos['editar_cuentas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cuentas', 'select_cuentas')"
                                                                    <?php if ($permisos['editar_cuentas'] == 1) echo "checked";?>>
                                                                <span></span>Editar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cuentas" name="transferir_cuentas" value="<?php echo $permisos['transferir_cuentas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cuentas', 'select_cuentas')"
                                                                    <?php if ($permisos['transferir_cuentas'] == 1) echo "checked";?>>
                                                                <span></span>Transferir fondos
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cuentas" name="eliminar_cuentas" value="<?php echo $permisos['eliminar_cuentas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cuentas', 'select_cuentas')"
                                                                    <?php if ($permisos['eliminar_cuentas'] == 1) echo "checked";?>>
                                                                <span></span>Eliminar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_cuentas" name="reportes_cuentas" value="<?php echo $permisos['reportes_cuentas'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_cuentas', 'select_cuentas')"
                                                                    <?php if ($permisos['reportes_cuentas'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="historial_caja" value="<?php echo $permisos['historial_caja'];?>" onclick="ver_permisos(this, 'grupo_historial_caja')"
                                                        <?php if ($permisos['historial_caja'] == 1) echo "checked";?>><br>
                                                    <span></span>Historial de cortes de caja
                                                </label>
                                            </span>
                                        </div>
                                        <div  class="p-5 mt-2" id="grupo_historial_caja" style="display: <?php if ($permisos['historial_caja'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Historial de cortes de caja </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_historial_caja" onclick="select_group(this, 'grupo_historial_caja')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_historial_caja" name="registrar_cortes" value="<?php echo $permisos['registrar_cortes'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_historial_caja', 'select_historial_caja')"
                                                                    <?php if ($permisos['registrar_cortes'] == 1) echo "checked";?>>
                                                                <span></span>Registrar
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_historial_caja" name="ver_cortes" value="<?php echo $permisos['ver_cortes'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_historial_caja', 'select_historial_caja')"
                                                                    <?php if ($permisos['ver_cortes'] == 1) echo "checked";?>>
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
                                                    <input type="checkbox" name="historial_fel" value="<?php echo $permisos['historial_fel'];?>" onclick="ver_permisos(this, 'grupo_historial_fel')"
                                                        <?php if ($permisos['historial_fel'] == 1) echo "checked";?>><br>
                                                    <span></span>Historial FEL
                                                </label>
                                            </span>
                                        </div>
                                        <div class="p-5 mt-2" id="grupo_historial_fel" style="display: <?php if ($permisos['historial_fel'] == 1) echo "block"; else echo "none";?>; 
                                            box-shadow: 12px 7px 30px -8px rgba(153,191,45,0.69); border-radius: 20px; margin-botton: 15px;">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <span class="text-success"><b> &nbsp; * Historial Fel </b></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <label class="checkbox checkbox-success">
                                                        <input type="checkbox" id="select_historial_fel" onclick="select_group(this, 'grupo_historial_fel')"/>
                                                        <span></span>Todos
                                                    </label>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_historial_fel" name="reportes_fel" value="<?php echo $permisos['reportes_fel'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_historial_fel', 'select_historial_fel')"
                                                                    <?php if ($permisos['reportes_fel'] == 1) echo "checked";?>>
                                                                <span></span>Reportes
                                                            </label>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group">
                                                        <span class="switch switch-outline switch-icon switch-success">
                                                            <label>
                                                                <input type="checkbox" class="grupo_historial_fel" name="eliminar_fel" value="<?php echo $permisos['eliminar_fel'];?>" 
                                                                    onclick="change_value(this), view_group('grupo_historial_fel', 'select_historial_fel')"
                                                                    <?php if ($permisos['eliminar_fel'] == 1) echo "checked";?>>
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
$(document).ready(function() {
    view_group('grupo_sucursales', 'select_sucursales');
    view_group('grupo_calendario', 'select_calendario');
    view_group('grupo_empleados', 'select_empleados');
    view_group('grupo_roles', 'select_roles');
    view_group('grupo_admins', 'select_admins');
    view_group('grupo_clientes', 'select_clientes');
    view_group('grupo_proveedores', 'select_proveedores');
    view_group('grupo_inventario', 'select_inventario');
    view_group('grupo_traslados', 'select_traslados');
    view_group('grupo_categorias', 'select_categorias');
    view_group('grupo_marcas', 'select_marcas');
    view_group('grupo_ventas', 'select_ventas');
    view_group('grupo_compras', 'select_compras');
    view_group('grupo_cotizaciones', 'select_cotizaciones');
    view_group('grupo_creditos', 'select_creditos');
    view_group('grupo_anulaciones', 'select_anulaciones');
    view_group('grupo_cambios', 'select_cambios');
    view_group('grupo_envios', 'select_envios');
    view_group('grupo_transportes', 'select_transportes');
    view_group('grupo_reportes', 'select_reportes');
    view_group('grupo_egresos', 'select_egresos');
    view_group('grupo_planillas', 'select_planillas');
    view_group('grupo_vacaciones', 'select_vacaciones');
    view_group('grupo_cuentas', 'select_cuentas');
    view_group('grupo_historial_caja', 'select_historial_caja');
    view_group('grupo_historial_fel', 'select_historial_fel');
});

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