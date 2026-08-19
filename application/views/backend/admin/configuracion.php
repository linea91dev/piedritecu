<style>
.picker {
    border-radius: 5px;
    width: 36px;
    height: 36px;
    cursor: pointer;
    -webkit-transition: all linear .2s;
    -moz-transition: all linear .2s;
    -ms-transition: all linear .2s;
    -o-transition: all linear .2s;
    transition: all linear .2s;
    border: thin solid #eee;
}

.picker:hover {
    transform: scale(1.1);
}

</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="card-label">Configuración del sistema
                                    <?php echo $this->db->get_where('settings', array('type'=>'name'))->row()->description;?>
                                    <span class="d-block text-muted pt-2 font-size-sm"></span>
                                </h3>
                            </div>
                            <div class="col-sm-12 alert alert-blue">
                                <span class="d-block pt-2 font-size-sm">Mantén la información de tu negocio actualizada,
                                    verifica los datos antes de aplicar los cambios ya que es información sensible la
                                    que estarás modificando y podrá afectar módulos importantes como facturación
                                    electrónica (si cuentas con el servicio).
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-7">
                        <div class="row align-items-center">
                            <form class="form" action="<?php echo base_url().'admin/configuracion/update/'.$this->session->userdata('login_user_id').'/1';?>" method="POST" enctype="multipart/form-data" id="confiForm" name='confiForm'>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group text-center">
                                            <div class="col-lg-12 col-xl-12">
                                                <div class="image-input image-input-outline" id="kt_profile_avatar" style="background-color: #fff">
                                                    <div class="image-input-wrapper" style="background-image: url(<?php echo ($this->db->get_where('settings', array('type'=>'logo'))->row()->description != '')?  base_url().'uploads/img/'.$this->db->get_where('settings', array('type'=>'logo'))->row()->description : base_url().'public/assets/media/users/blank.png' ;?>);background-size:contain;background-position:center;">
                                                    </div>
                                                    <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Cambiar logotipo">
                                                        <i class="fa fa-pen icon-sm text-muted"></i>
                                                        <input type="file" name="logo" accept=".png, .jpg, .jpeg" onchange="onLoadImage(event.target.files)" />
                                                        <input type="hidden" name="profile_avatar_remove" />
                                                    </label>
                                                    <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancelar cambio" onclick="ocultarlabel()">
                                                        <i class="ki ki-bold-close icon-xs text-muted"></i>
                                                    </span>
                                                </div><br>
                                                <label id="imgLabel" style="display: none;">Archivo seleccionado:
                                                    <b><span id="imgName">Niguno</span></b></label>
                                                <span class="form-text text-muted">Formatos permitidos: png, jpg,
                                                    jpeg.</span>
                                            </div>
                                        </div><br>
                                    </div>
                                    <!-- <div class="col-sm-3">
                                        <label>Elige un color para tu sistema</label>
                                        <div class="picker"></div>
                                        <input type="hidden" id="theme" name="theme"
                                            value='<?php echo $this->db->get_where('settings', array('type'=>'theme'))->row()->description;?>'>

                                    </div> -->
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Nombre <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" aria-label="Text input with checkbox" name='name' required value='<?php echo $this->db->get_where('settings', array('type'=>'name'))->row()->description;?>' />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Título del sistema <span class="text-danger">*</span>
                                                <small>(Eslogan)</small></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name='slogan' required value='<?php echo $this->db->get_where('settings', array('type'=>'slogan'))->row()->description;?>' aria-label="Text input with checkbox" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Teléfono</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" pattern="[0-9]{8}" placeholder='54488866' aria-label="Text input with checkbox" name='phone' value='<?php echo $this->db->get_where('settings', array('type'=>'phone'))->row()->description;?>' />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Correo <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="tel" class="form-control" aria-label="Text input with checkbox" name='email' id='email' value='<?php echo $this->db->get_where('settings', array('type'=>'email'))->row()->description;?>' oninput="searchEmail()" onblur="searchEmail()" required />
                                            </div>
                                            <label id="ms_error" class="control-label text-danger"></label>

                                        </div>
                                    </div>


                                    <div class="col-sm-4 center">
                                        <div class="form-group">
                                            <label>Régimen <small>(Se utilizará para calcular IVA)</small><span class="text-danger">*</span></label>
                                            <div class="checkbox-inline text-center">
                                                <label class="checkbox checkbox-rounded">
                                                    <input type="radio" value='12' <?php echo ($this->db->get_where('settings', array('type'=>'regimen'))->row()->description == 12) ? 'checked' : '' ;?> name="regimen">
                                                    <span></span>12%
                                                </label>
                                                <label class="checkbox checkbox-rounded">
                                                    <input type="radio" name="regimen" value='5' <?php echo ($this->db->get_where('settings', array('type'=>'regimen'))->row()->description == 5) ? 'checked' : '' ;?>>
                                                    <span></span>5%
                                                </label>
                                                <label class="checkbox checkbox-rounded">
                                                    <input type="radio" name="regimen" value='0' <?php echo ($this->db->get_where('settings', array('type'=>'regimen'))->row()->description == 0) ? 'checked' : '' ;?>>
                                                    <span></span>No aplica
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Pago de planilla <span class="text-danger">*</span></label>
                                            <div class="checkbox-inline text-center">
                                                <label class="checkbox checkbox-rounded">
                                                    <input type="radio" value='mensual' name="planilla" <?php echo ($this->db->get_where('settings', array('type'=>'planilla'))->row()->description == 'mensual') ? 'checked' : '' ;?>>
                                                    <span></span>Mensual
                                                </label>
                                                <label class="checkbox checkbox-rounded">
                                                    <input type="radio" name="planilla" value='quincenal' <?php echo ($this->db->get_where('settings', array('type'=>'planilla'))->row()->description == 'quincenal') ? 'checked' : '' ;?>>
                                                    <span></span>Quincenal
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Tipo cierre de caja <span class="text-danger">*</span></label>
                                            <div class="checkbox-inline text-center">
                                                <label class="checkbox checkbox-rounded">
                                                    <input type="radio" value='horarios' <?php echo ($this->db->get_where('settings', array('type'=>'cierre'))->row()->description == 'horarios') ? 'checked' : '' ;?> name="cierre" onchange='tipoCierre(this.value)'>
                                                    <span></span>Horarios
                                                </label>
                                                <label class="checkbox checkbox-rounded">
                                                    <input type="radio" value='monto' <?php echo ($this->db->get_where('settings', array('type'=>'cierre'))->row()->description == 'monto') ? 'checked' : '' ;?> name="cierre" onchange='tipoCierre(this.value)'>
                                                    <span></span>Monto
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group" id='Horario'>
                                            <label>Ingrese el horario de corte <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" name='corte' id='corte' value='<?php echo $this->db->get_where('settings', array('type'=>'corte'))->row()->description;?>'>
                                            </div>
                                        </div>
                                        <div class="form-group" id='Monto'>
                                            <label>Ingrese monto límite para el corte <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name='monto_limite' id='monto' min='0' value='<?php echo $this->db->get_where('settings', array('type'=>'monto_limite'))->row()->description;?>'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Meta mensual <small> (De ventas)</small> <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name='meta_mensual' min='0' value='<?php echo $this->db->get_where('settings', array('type'=>'meta_mensual'))->row()->description;?>'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label> Moneda <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" placeholder="$" class="form-control" name='moneda' required value='<?php echo $this->db->get_where('settings', array('type'=>'moneda'))->row()->description;?>'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>Días de vencimiento<span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" min='0' max='100' class="form-control" placeholder='Días de antes de que venca un producto' name='vencimiento' required value='<?php echo $this->db->get_where('settings', array('type'=>'vencimiento'))->row()->description;?>'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Descuento a mayoristas <span class="text-danger">*</span> (%)
                                            </label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" name='mayorista' required value='<?php echo $this->db->get_where('settings', array('type'=>'mayorista'))->row()->description;?>'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Hora límite de acceso <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="time" class="form-control" name='horario_limite' required value='<?php echo $this->db->get_where('settings', array('type'=>'horario_limite'))->row()->description;?>'>
                                            </div>
                                            <label>Después de este horario solo podrá acceder el
                                                administrador.</label>
                                        </div>
                                    </div>

                                    <div class="col-sm-6"><br>
                                        <div class="form-group">
                                            <a href="javascript:;" onclick="close_sessions()" style="background:#eee5ff;color:#8950FC; width:100%;" class="btn btn-light-primary font-weight-bolder">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path d="M11.7573593,15.2426407 L8.75735931,15.2426407 C8.20507456,15.2426407 7.75735931,15.6903559 7.75735931,16.2426407 C7.75735931,16.7949254 8.20507456,17.2426407 8.75735931,17.2426407 L11.7573593,17.2426407 L11.7573593,18.2426407 C11.7573593,19.3472102 10.8619288,20.2426407 9.75735931,20.2426407 L5.75735931,20.2426407 C4.65278981,20.2426407 3.75735931,19.3472102 3.75735931,18.2426407 L3.75735931,14.2426407 C3.75735931,13.1380712 4.65278981,12.2426407 5.75735931,12.2426407 L9.75735931,12.2426407 C10.8619288,12.2426407 11.7573593,13.1380712 11.7573593,14.2426407 L11.7573593,15.2426407 Z" fill="#f64e8b" opacity="0.3" transform="translate(7.757359, 16.242641) rotate(-45.000000) translate(-7.757359, -16.242641) " />
                                                            <path d="M12.2426407,8.75735931 L15.2426407,8.75735931 C15.7949254,8.75735931 16.2426407,8.30964406 16.2426407,7.75735931 C16.2426407,7.20507456 15.7949254,6.75735931 15.2426407,6.75735931 L12.2426407,6.75735931 L12.2426407,5.75735931 C12.2426407,4.65278981 13.1380712,3.75735931 14.2426407,3.75735931 L18.2426407,3.75735931 C19.3472102,3.75735931 20.2426407,4.65278981 20.2426407,5.75735931 L20.2426407,9.75735931 C20.2426407,10.8619288 19.3472102,11.7573593 18.2426407,11.7573593 L14.2426407,11.7573593 C13.1380712,11.7573593 12.2426407,10.8619288 12.2426407,9.75735931 L12.2426407,8.75735931 Z" fill="#f64e8b" transform="translate(16.242641, 7.757359) rotate(-45.000000) translate(-16.242641, -7.757359) " />
                                                            <path d="M5.89339828,3.42893219 C6.44568303,3.42893219 6.89339828,3.87664744 6.89339828,4.42893219 L6.89339828,6.42893219 C6.89339828,6.98121694 6.44568303,7.42893219 5.89339828,7.42893219 C5.34111353,7.42893219 4.89339828,6.98121694 4.89339828,6.42893219 L4.89339828,4.42893219 C4.89339828,3.87664744 5.34111353,3.42893219 5.89339828,3.42893219 Z M11.4289322,5.13603897 C11.8194565,5.52656326 11.8194565,6.15972824 11.4289322,6.55025253 L10.0147186,7.96446609 C9.62419433,8.35499039 8.99102936,8.35499039 8.60050506,7.96446609 C8.20998077,7.5739418 8.20998077,6.94077682 8.60050506,6.55025253 L10.0147186,5.13603897 C10.4052429,4.74551468 11.0384079,4.74551468 11.4289322,5.13603897 Z M0.600505063,5.13603897 C0.991029355,4.74551468 1.62419433,4.74551468 2.01471863,5.13603897 L3.42893219,6.55025253 C3.81945648,6.94077682 3.81945648,7.5739418 3.42893219,7.96446609 C3.0384079,8.35499039 2.40524292,8.35499039 2.01471863,7.96446609 L0.600505063,6.55025253 C0.209980772,6.15972824 0.209980772,5.52656326 0.600505063,5.13603897 Z" fill="#f64e8b" opacity="0.3"
                                                                  transform="translate(6.014719, 5.843146) rotate(-45.000000) translate(-6.014719, -5.843146) " />
                                                            <path d="M17.9142136,15.4497475 C18.4664983,15.4497475 18.9142136,15.8974627 18.9142136,16.4497475 L18.9142136,18.4497475 C18.9142136,19.0020322 18.4664983,19.4497475 17.9142136,19.4497475 C17.3619288,19.4497475 16.9142136,19.0020322 16.9142136,18.4497475 L16.9142136,16.4497475 C16.9142136,15.8974627 17.3619288,15.4497475 17.9142136,15.4497475 Z M23.4497475,17.1568542 C23.8402718,17.5473785 23.8402718,18.1805435 23.4497475,18.5710678 L22.0355339,19.9852814 C21.6450096,20.3758057 21.0118446,20.3758057 20.6213203,19.9852814 C20.2307961,19.5947571 20.2307961,18.9615921 20.6213203,18.5710678 L22.0355339,17.1568542 C22.4260582,16.76633 23.0592232,16.76633 23.4497475,17.1568542 Z M12.6213203,17.1568542 C13.0118446,16.76633 13.6450096,16.76633 14.0355339,17.1568542 L15.4497475,18.5710678 C15.8402718,18.9615921 15.8402718,19.5947571 15.4497475,19.9852814 C15.0592232,20.3758057 14.4260582,20.3758057 14.0355339,19.9852814 L12.6213203,18.5710678 C12.2307961,18.1805435 12.2307961,17.5473785 12.6213203,17.1568542 Z" fill="#f64e8b" opacity="0.3"
                                                                  transform="translate(18.035534, 17.863961) scale(1, -1) rotate(45.000000) translate(-18.035534, -17.863961) " />
                                                        </g>
                                                    </svg>
                                                </span> Desconectar usuarios</a><br>
                                            <small>Se desconectarán todos los usuarios incluída tu cuenta.</small>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label>Correo eléctronico </label>
                                                    <input type="checkbox" name="noti_email" value="1" <?php echo ($this->db->get_where('settings', array('type'=>'noti_email'))->row()->description == 1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                            <small class="text-justify">Si activas, se enviarán notificaciones cuando
                                                hagas una solicitud de compra y facturas al realizar una venta.</small>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label> WhatsApp </label>
                                                    <input type="checkbox" name="whatsapp" value="1" onchange="whts()" <?php echo ($this->db->get_where('settings', array('type'=>'whatsapp'))->row()->description == 1) ? 'checked':'' ;?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                        <div class="form-group" id='codigo'>
                                            <label for="">Código: <small><a href="javascript:void(0);"> (Token) </a>
                                                </small></label>
                                            <input type="text" class='form-control' name="code" value='<?php echo $this->db->get_where('settings', array('type'=>'code'))->row()->description;?>'>
                                            </span>
                                            <small><a href='https://msalerts.com/login' target="_blank">Puedes obtenerlo
                                                    aquí</a></small>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <?php $fel_payed = $this->db->get_where('settings', array('type'=>'FEL_payed'))->row()->description;
                                                $fel = $this->db->get_where('settings', array('type'=>'FEL'))->row()->description;?>
                                            <span class="switch switch-icon">
                                                <label>
                                                    <label>Facturación <small>(FEL)</small> </label>
                                                    <input type="checkbox" name="FEL" value="1" onchange="fel()" <?php if($fel_payed && $fel) echo "checked"; if(!$fel_payed) echo "disabled";?>><br>
                                                    <span></span>
                                                </label>
                                            </span>
                                            <small>Al tener activo el servicio de FEL, podrás activar esta opción para posteriormente emitir documentos.</small>
                                        </div>
                                    </div>


                                    <div class="col-sm-4 fel">
                                        <div class="form-group fel">
                                            <label><b>NIT:</b> <span class="text-danger">*</span></label>
                                            <div class=" spinner-success spinner-left" id='spinnerNit'>
                                                <input type="text" placeholder="Ej: 89907865 sin guiones" class="form-control emisor " name="nit" id="nit" min='0' minlength="7" maxlength="12" onblur="getNit(this.value)" autocomplete="off" value='<?php echo $this->db->get_where('settings', array('type'=>'nit'))->row()->description;?>' required>
                                            </div>
                                            <div id='errorNit'></div>
                                            <small>Presiona TAB para obtener los datos del contribuyente.</small>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 fel">
                                        <div class="form-group fel">
                                            <label for="">Nombre: <span class="text-danger">*</span></label>
                                            <div class=" spinner-success spinner-left" id='spinnerName'>
                                                <input type="text" class='form-control emisor' id='name_fel' name="name_fel" value='<?php echo $this->db->get_where('settings', array('type'=>'name_fel'))->row()->description;?>'>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 fel">
                                        <div class="form-group fel">
                                            <label for="">JWT: <span class="text-danger">*</span></label>
                                            <input type="text" class='form-control emisor' name="jwt" value='<?php echo $this->db->get_where('settings', array('type'=>'jwt'))->row()->description;?>'>
                                            </span>
                                            <small><a href='https://customer.mayansource.com/' target="_blank">Puedes
                                                    obtenerlo aquí</a></small>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 fel">
                                        <div class="form-group">
                                            <label>Nombre comercial <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class='form-control emisor' name="nombreComercial" value='<?php echo $this->db->get_where('settings', array('type'=>'nombreComercial'))->row()->description;?>'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 fel">
                                        <div class="form-group">
                                            <label>Dirección emisor <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class='form-control emisor' name="direccionemisor" value='<?php echo $this->db->get_where('settings', array('type'=>'direccionEmisor'))->row()->description;?>'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 fel">
                                        <div class="form-group">
                                            <label>Código postal <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class='form-control emisor' name="codigoPostal" value='<?php echo $this->db->get_where('settings', array('type'=>'codigoPostal'))->row()->description;?>'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 fel">
                                        <div class="form-group">
                                            <label>Municipio <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class='form-control emisor' name="municipio" value='<?php echo $this->db->get_where('settings', array('type'=>'municipio'))->row()->description;?>'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-4 fel">
                                        <div class="form-group">
                                            <label>Departamento <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="text" class='form-control emisor' name="departamento" value='<?php echo $this->db->get_where('settings', array('type'=>'departamento'))->row()->description;?>'>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Dirección </label>
                                            <div class="input-group">
                                                <textarea class="form-control emisor" name='address' aria-label="Text input with checkbox"><?php echo $this->db->get_where('settings', array('type'=>'address'))->row()->description;?></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group" id='codigoAuth'>
                                            <label> Código de autorización para aplicar configuración: <span class="text-danger">*</span></label>
                                            <div class=" spinner-success spinner-left" id='spinnerCode'>
                                                <input type="password" autocomplete="off" class='form-control' id='code' placeholder='Ingresa el código de autorización' onblur="getCodigo(this.value)">
                                            </div>
                                            <div id='mensajeError'></div>
                                            <small class='text-info'>Para verificar el código utiliza la tecla <b>TAB</b></small>

                                        </div>
                                    </div>

                                    <div class="col-sm-12" style='text-align: end;'>
                                        <button class="btn btn-primary font-weight-bolder" type='submit' disabled id='aplicar'>Aplicar cambios</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link href="https://demo.medicaby.com/public/assets/theme/css/colorpicker.css" rel="stylesheet">
<link href="https://demo.medicaby.com/public/assets/theme/css/colorPick.css" rel="stylesheet">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://demo.medicaby.com/public/assets/back/js/colorPick.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    var cierre = '<?php echo $this->db->get_where('settings', array('type'=>'cierre'))->row()->description;?>';
    tipoCierre(cierre);
});

function tipoCierre(value) {
    console.log(value);
    if (value == 'horarios') {
        $('#Horario').show(500);
        $('#corte').attr('required', true);

        $('#Monto').hide(500);
        $('#monto').removeAttr('required');

    } else if (value == 'monto') {
        $('#Horario').hide(500);
        $('#corte').removeAttr('required');

        $('#Monto').show(500);
        $('#monto').attr('required', true);


    }
}

function submitForm() {
    document.confiForm.submit();
}

function searchEmail() {
    var email = $('#email').val();
    var ID = '0';
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/search_email/admin',
        data: {
            email: email,
            ID: ID,
        },
        success: function(response) {
            if (response == 1) {
                $('#ms_error').html(" ");
                $('#submit').removeAttr('disabled');
            } else if (response == 0) {
                $('#ms_error').html("Correo eléctronico no disponible");
                $('#submit').attr('disabled', 'disabled');
            } else if (response == 2) {
                $('#ms_error').html(" ");
                $('#submit').removeAttr('disabled');
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });


}


$(".picker").colorPick({
    'initialColor': "#8950FC",
    'palette': ["#089bab", "#fead55", "#f36b7f", "#6b86f3", "#3734a9", "#0044e9", "#0d4290", "#fd4f57",
        "#a01a7a", "#232a3b", "#8950FC"
    ],
    'onColorSelected': function() {
        $("#theme").val(this.color);
        this.element.css({
            'backgroundColor': this.color,
            'color': this.color
        });
    }
});

var y = true;
<?php if($fel && $fel_payed):?>
$('.fel').show();
$('.emisor').attr('required', true);
y = false;
<?php else:?>
$('.fel').hide();
x = true;
<?php endif;?>


function fel() {
    if (y == true) {
        $('.fel').show(500);
        $('.emisor').attr('required', true);
        y = false;
    } else {
        $('.fel').hide(500);
        $('.emisor').removeAttr('required');

        y = true;
    }
}

var x = true;
<?php $whts = $this->db->get_where('settings', array('type'=>'whatsapp'))->row()->description; if($whts == '1'):?>
$('#codigo').show();
x = false;
<?php else:?>
$('#codigo').hide();
x = true;
<?php endif;?>


function whts() {

    if (x == true) {
        $('#codigo').show(500);
        x = false;
    } else {
        $('#codigo').hide(500);
        x = true;
    }
}

function onLoadImage(files) {
    if (files && files[0]) {
        document
            .getElementById('imgName')
            .innerHTML = files[0].name;
        $('#imgLabel').show(500);
    } else {
        document
            .getElementById('imgName')
            .innerHTML = 'Ninguno';
        $('#imgLabel').hide(500);
    }
}

function ocultarlabel() {
    document
        .getElementById('imgName')
        .innerHTML = 'Ninguno';
    $('#imgLabel').hide(500);
}

function close_sessions() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se cerrarán todas las sesiones abiertas en tus dispositivos, puede que alguna información no se guarde durante este proceso. ¿Aún así, desea continuar?",
        type: 'info',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#9fd13b',
        cancelButtonColor: '#fd4f57',
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            location.href = "<?php echo base_url(); ?>admin/configuracion/sessions/";
        }
    })
}

function getNit(value) {
    var str = value;
    var nit = str.replace(/-/g, "");
    var leng_nit = nit.length;
    if (leng_nit >= 7) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/getNit/',
            data: {
                nit: nit,
            },
            beforeSend: function() {
                $('#spinnerNit').addClass('spinner');
                $('#spinnerName').addClass('spinner');
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data == 'NIT no encontrado') {
                    $('#name_fel').val('NIT no encontrado');
                    $('#spinnerName').removeClass('spinner');
                } else {

                    if (data.length == 2) {
                        var data1 = data['1'].replace(',', ' ');
                        var data0 = data['0'].replace(',', ' ');
                        $('#name_fel').val(data1 + ' , ' + data0);
                    } else {

                        $('#name_fel').val(data['0']);
                    }

                    $('#spinnerNit').removeClass('spinner');
                    $('#spinnerName').removeClass('spinner');

                }


            },
            error: function(e) {
                console.log("ERROR : ", e);
            }
        });
    } else {
        console.log('datos no encontrados');
    }

}

function getCodigo(code) {
    var leng_code = code.length;
    var valor = 'configuracion';
    if (leng_code > 0) {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url();?>admin/checkCodigos/',
            data: {
                code: code,
                valor: valor,
            },
            beforeSend: function() {
                $('#spinnerCode').addClass('spinner');
            },
            success: function(response) {

                if (response == 1) {
                    $('#spinnerCode').removeClass('spinner');
                    $('#mensajeError').html('<small class="text-success" >Código aceptado</small>');
                    $('#aplicar').removeAttr('disabled');

                } else {
                    $('#spinnerCode').removeClass('spinner');
                    $('#mensajeError').html('<small class="text-danger" >Código incorrecto</small>');
                    $('#aplicar').attr('disabled', true);

                }

            },
            error: function(e) {
                console.log("ERROR : ", e);
            }
        });
    } else {
        $('#mensajeError').html('<small class="text-info" >Ingrese un código </small>');
    }
}
</script>
