<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">
                            Gestionar empleados
                            <span class="d-block text-muted pt-2 font-size-sm">Aquí podrás encontrar a todo tu equipo de
                                trabajo.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="<?php echo  base_url().'admin/empleados/' ;?>"
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
                    <form class="form" action="<?php echo base_url();?>admin/empleados/create" method="POST"
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
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Nombres <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="Text input with checkbox"
                                            name='name' required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Apellidos <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="Text input with checkbox"
                                            name='last_name' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Fecha de nacimiento <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="Text input with checkbox" id="kt_datepicker"
                                            required name='birthday' readonly placeholder="dd/mm/aaaa"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Fecha de contratación</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="Text input with checkbox" id="kt_datepicker_1"
                                            name='hiring' value='<?php echo date('d/m/Y');?>' placeholder="dd/mm/aaaa" readonly/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Celular</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control" aria-label="Text input with checkbox"
                                            placeholder='54598822' pattern="[0-9]{8}" name='phone'
                                            oninput="if(value.length>8)value=value.slice(0,8)" />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Teléfono de emergencia</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control" name="emergency_phone"
                                            pattern="[0-9]{8}" maxlength="8" placeholder="55555555"
                                            oninput="this.value=this.value.replace(/[^0-9]/g, '').slice(0,8)"  required/>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>CUI:</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" pattern="[0-9]{8}"
                                            aria-label="Text input with checkbox" maxlength="13" name='cui'
                                            oninput="if(value.length>13)value=value.slice(0,13)" onkeyup="validateDPI(this.value);" />
                                    </div>
                                    <div id='errorCUI'></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Correo <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="email" class='form-control' name='email' id="email_emp_add" aria-label="Text input with checkbox" >
                                    </div>
                                    <small>Enviaremos las credenciales de acceso a este correo.</small>
                                    <span id="msg_email_emp_add" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Salario</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox"
                                            name='salary' max='' min='0' step="0.01" value="0"/>
                                    </div>
                                    <small>Si no aplica dejar vacío o ingresar 0.
                                    </small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Bonificación</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox"
                                            name='bonus' max='' min='0' step="0.01" value="0"/>
                                    </div>
                                    <small>Si no aplica dejar vacío o ingresar 0.
                                    </small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Complemento</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox"
                                            name='complemento' max='' min='0' step="0.01" value="0"/>
                                    </div>
                                    <small>Base para planilla interna. Si no aplica dejar vacío o ingresar 0.</small>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> Puesto <span class="text-danger">*</span></label>
                                    <select name="job" class="form-control" required>
                                        <option value="">Seleccionar</option>
                                        <?php $rol = $this->db->get_where('job', array('status'=>1))->result_array();
                                            foreach($rol as $r): ?>
                                        <option value="<?php echo $r['job_id'];?>"><?php echo $r['name'];?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> Sucursal <span class="text-danger">*</span></label>
                                    <select name="branch[]" class="form-control" id='selected-0' multiple required>
                                        <option value="">Seleccionar</option>
                                        <?php $sucursal = $this->db->get_where('branch')->result_array(); foreach ($sucursal as $sc):
                                        ?>
                                        <option value="<?php echo $sc['branch_id'];?>"> <?php echo $sc['name'];?>
                                        </option>
                                        <?php  endforeach ;?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Fotografía</label>
                                    <div class="input-group">
                                        <input class="uppy-FileInput-input uppy-input-control" type="file" name="foto"
                                            accept="image/*" id="kt_uppy_5_input_control" style='display:none'
                                            onchange="onLoadImage_s(event.target.files)">
                                        <label class="uppy-input-label btn btn-light-primary btn-sm btn-bold"
                                            for="kt_uppy_5_input_control">Seleccionar Imagen</label>
                                    </div>
                                    <label>Archivo seleccionado: <b><span id="imgName_s">Niguno</span></b></label>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Dirección</label>
                                    <div class="input-group">
                                        <textarea class="form-control" aria-label="Text input with checkbox"
                                            name='address'></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <span class="text-danger">
                                    <b>* Las credenciales de acceso se envirán al correo que ingresaste.</b>
                                </span>
                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary font-weight-bold" id="add_emp_submit" style='float: right;'>Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">


function cuiIsValid(cui) {
    var console = window.console;
    
    if (!cui) {
        console.log("CUI vacío");
        return true;
    }

    var cuiRegExp = /^[0-9]{4}\s?[0-9]{5}\s?[0-9]{4}$/;

    if (!cuiRegExp.test(cui)) {
        console.log("CUI con formato inválido");
        return false;
    }

    cui = cui.replace(/\s/, '');
    var depto = parseInt(cui.substring(9, 11), 10);
    var muni = parseInt(cui.substring(11, 13));
    var numero = cui.substring(0, 8);
    var verificador = parseInt(cui.substring(8, 9));
    
    // Se asume que la codificación de Municipios y 
    // departamentos es la misma que esta publicada en 
    // http://goo.gl/EsxN1a

    // Listado de municipios actualizado segun:
    // http://goo.gl/QLNglm

    // Este listado contiene la cantidad de municipios
    // existentes en cada departamento para poder 
    // determinar el código máximo aceptado por cada 
    // uno de los departamentos.
    var munisPorDepto = [ 
        /* 01 - Guatemala tiene:      */ 17 /* municipios. */, 
        /* 02 - El Progreso tiene:    */  8 /* municipios. */, 
        /* 03 - Sacatepéquez tiene:   */ 16 /* municipios. */, 
        /* 04 - Chimaltenango tiene:  */ 16 /* municipios. */, 
        /* 05 - Escuintla tiene:      */ 13 /* municipios. */, 
        /* 06 - Santa Rosa tiene:     */ 14 /* municipios. */, 
        /* 07 - Sololá tiene:         */ 19 /* municipios. */, 
        /* 08 - Totonicapán tiene:    */  8 /* municipios. */, 
        /* 09 - Quetzaltenango tiene: */ 24 /* municipios. */, 
        /* 10 - Suchitepéquez tiene:  */ 21 /* municipios. */, 
        /* 11 - Retalhuleu tiene:     */  9 /* municipios. */, 
        /* 12 - San Marcos tiene:     */ 30 /* municipios. */, 
        /* 13 - Huehuetenango tiene:  */ 32 /* municipios. */, 
        /* 14 - Quiché tiene:         */ 21 /* municipios. */, 
        /* 15 - Baja Verapaz tiene:   */  8 /* municipios. */, 
        /* 16 - Alta Verapaz tiene:   */ 17 /* municipios. */, 
        /* 17 - Petén tiene:          */ 14 /* municipios. */, 
        /* 18 - Izabal tiene:         */  5 /* municipios. */, 
        /* 19 - Zacapa tiene:         */ 11 /* municipios. */, 
        /* 20 - Chiquimula tiene:     */ 11 /* municipios. */, 
        /* 21 - Jalapa tiene:         */  7 /* municipios. */, 
        /* 22 - Jutiapa tiene:        */ 17 /* municipios. */ 
    ];
    
    if (depto === 0 || muni === 0)
    {
        console.log("CUI con código de municipio o departamento inválido.");
        return false;
    }
    
    if (depto > munisPorDepto.length)
    {
        console.log("CUI con código de departamento inválido.");
        return false;
    }
    
    if (muni > munisPorDepto[depto -1])
    {
        console.log("CUI con código de municipio inválido.");
        return false;
    }
    
    // Se verifica el correlativo con base 
    // en el algoritmo del complemento 11.
    var total = 0;
    
    for (var i = 0; i < numero.length; i++)
    {
        total += numero[i] * (i + 2);
    }
    
    var modulo = (total % 11);
    
    console.log("CUI con módulo: " + modulo);
    return modulo === verificador;
};

function validateDPI(ddd) {
            var $this = $(this);
            var $parent = $this.parent();
            var $next = $this.next();
            var cui = ddd;
            
            if (cui && cuiIsValid(cui)) {
                $('#errorCUI').html('<p class="text-success"> DPI válido</p>');
            } else if (cui) {
                $('#errorCUI').html('<p class="text-danger">Debe ingresar un DPI válido</p>');
            } else {
                $('#errorCUI').html('<p class="text-danger">DPI no válido</p>');
            }
}
            

function searchEmail() {
    var email = $('#email_emp_add').val();
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
                $('#msg_email_emp_add').html(" ");
                $('#add_emp_submit').removeAttr('disabled');
            } else if (response == 0) {
                $('#msg_email_emp_add').html("Correo eléctronico no disponible");
                $('#add_emp_submit').attr('disabled', 'disabled');
            } else if (response == 2) {
                $('#msg_email_emp_add').html(" ");
                $('#add_emp_submit').removeAttr('disabled');
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}
</script>