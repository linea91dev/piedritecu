<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar inventario
                            <span class="d-block text-muted pt-2 font-size-sm">Administra tus productos en
                                inventario.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="<?php echo base_url().'admin/inventario';?>" class="btn btn-light-primary font-weight-bolder">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <circle fill="#000000" cx="9" cy="15" r="6" />
                                        <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                              fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                            </span> Regresar
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form" action="<?php echo base_url();?>admin/nuevo_producto/create" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <div class="alert alert-custom alert-default" role="alert">
                                        <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                                        <div class="alert-text">
                                            Los campos marcados con * son obligatorios.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group text-center">
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="image-input image-input-outline" id="kt_profile_avatar" style="background-color: #fff">
                                            <div class="image-input-wrapper" style="background-image: url(<?php echo base_url().'uploads/productos/default_product.png' ?>);background-size:contain; background-position:center;">
                                            </div>
                                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Cambiar imagen">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="img" accept=".png, .jpg, .jpeg" />
                                                <input type="hidden" name="profile_avatar_remove" />
                                            </label>
                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancelar cambio">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                        </div>
                                        <span class="form-text text-muted">Formatos permitidos: png, jpg, jpeg.</span>
                                    </div>
                                </div><br>
                            </div>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label>Producto <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="Text input with checkbox" name='name' required />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Código <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="Text input with checkbox" placeholder="FDF34FS" name='code' id="code" required oninput="verificar()" />
                                    </div>
                                    <span class="text-danger" id="msg_error"></span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Clase de producto <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" name='class_product_id'  required >
                                            <option value=''>Seleccionar</option>
                                            <?php $types = $this->crud_model->get_class_product(); foreach ($types->result_array() as $type):?>
                                            <option value="<?php echo $type['class_product_id'];?>">
                                                <?php echo $type['name_class'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Tipo de producto <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" name='type_product_id' id='selected-4' required onchange="view_type_product(this.value)">
                                            <option value=''>Seleccionar</option>
                                            <?php $types = $this->crud_model->get_types_product(); foreach ($types->result_array() as $type):?>
                                            <option value="<?php echo $type['type_product_id'];?>">
                                                <?php echo $type['name'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <small class="text-success" id="tag_nuevo_tipo"></small>
                                    <small class='text-info'> Para agregar una nuevo <b>tipo de producto</b> utiliza la tecla <b>ENTER</b></small>
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Categoría <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" name='category' id='selected-3' required onchange="view_category(this.value)">
                                            <option value=''>Seleccionar</option>
                                            <?php $categories = $this->crud_model->get_categories(); foreach ($categories->result_array() as $category):?>
                                            <option value="<?php echo $category['category_id'];?>">
                                                <?php echo $category['name'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <small class="text-success" id="tag_nueva_categoria"></small>
                                    <small class='text-info'> Para Agregar una nueva <b>categoría</b> utiliza la tecla <b>ENTER</b></small>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> Marca <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" name='mark' id='selected-1' required onchange="view_mark(this.value)">
                                            <option value=''>Seleccionar</option>
                                            <?php $marks = $this->crud_model->get_mark(); foreach ($marks->result_array() as $mark):?>
                                            <option value="<?php echo $mark['mark_id'];?>"><?php echo $mark['name'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <small class="text-success" id="tag_nueva_marca"></small>
                                    <small class='text-info'> Para Agregar una nueva <b> marca </b> utiliza la tecla <b>ENTER</b></small>

                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Proveedor <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" name='provider' id='selected-2' required onchange="add_provider(this.value)">
                                            <option value=''>Seleccionar</option>
                                            <option value='Nuevo'>Nuevo</option>
                                            <?php $providers = $this->db->order_by('name', 'ASC')->get_where('provider',array('status'=>1)); foreach ($providers->result_array() as $provider):?>
                                            <option value="<?php echo $provider['provider_id'];?>">
                                                <?php echo $provider['name'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <br>
                                    <div id='new_provider'>

                                        <label>Empresa <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="new_provider_name" id="new_provider_name" placeholder="Ej:Empresa S.A" />
                                        </div>

                                        <br>
                                        <label>Correo</label>
                                        <div class="input-group">
                                            <input type="email" class="form-control" name="new_provider_email" id="new_provider_email" placeholder='Ej:ejemplo@msbox.gt' />
                                        </div>

                                        <br>
                                        <label>WhatsApp</label>
                                        <div class="input-group">
                                            <input type="number" min='0' class="form-control" name="new_provider_whatsapp" id="new_provider_whatsapp" placeholder="Ej:55449988" pattern="[0-9]{8}" />
                                        </div>

                                        <br>
                                        <label>Teléfono</label>
                                        <div class="input-group">
                                            <input type="number" min='0' class="form-control" name="new_provider_phone" id="new_provider_phone" placeholder="Ej:55339977" pattern="[0-9]{8}" />
                                        </div>

                                        <br>
                                        <label>Encargado</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="new_provider_encargado" id="new_provider_encargado" placeholder="Ej:Juan Perez " />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> Presentación de producto <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select name="selected_caja" id="selected_caja" class='form-control' required onchange="new_caja(this.value)">
                                                <option value="Unidad">Unidad</option>
                                                <option value="Caja">Caja</option>
                                        </select>
                                    <!--    <input type="text" name="presentation" class="form-control" required placeholder='Unidad, Caja'> --> 
                                    </div>
                                </div>
                            </div>
                            <div id="caja" class="col-sm-4">
                                <label>Codigo de Producto matriz <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="prod_matriz" id="prod_matriz" placeholder="Ej: A001 " />
                                </div>
                                <label>Cantidad de Producto matriz <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="cnt_prod_matriz" id="cnt_prod_matriz" placeholder="Ej: 10 " />
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> Peso del Producto <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" step='any' class="form-control" name="weight"  aria-label="Text input with dropdown button">
                                        <div class="input-group-append">
                                            <select name="unit" class='form-control'>
                                                <optgroup label="Unidades de Peso">
                                                    <option value="Kg">Kilogramos</option>
                                                    <option value="L">Libras</option>
                                                    <option value="G">Gramos</option>
                                                    <option value="Oz">Onzas</option>
                                                </optgroup>
                                                <optgroup label="Unidades de Capacida">
                                                    <option value="Ml">Mililitros</option>
                                                    <option value="Lt">Litros</option>
                                                    <option value="Gl">Galones</option>
                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> Fecha de vencimiento</label>
                                    <div class="input-group">
                                        <input type="text" name="expiration" class="form-control" placeholder="mm/dd/aaaa" id="kt_datepicker" readonly>
                                    </div>
                                    <small>(Dejar en blanco este campo indica que el producto es imperecedero)</small>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> Sucursal <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select name="branch" class="form-control" id='selected-0' required onchange="add_branch(this.value)">
                                            <option value="">Seleccionar</option>
                                            <option value='Nuevo'>Nueva</option>
                                            <option value="0">Bodega</option>
                                            <?php $sucursal = $this->db->get_where('branch',array('status'=>1))->result_array();
                                                foreach ($sucursal as $sc): ?>
                                            <option value="<?php echo $sc['branch_id'];?>"><?php echo $sc['name'];?></option>
                                            <?php endforeach ;?>
                                        </select>

                                    </div>
                                    <br>
                                    <div id='new_branch'>

                                        <label>Nombre <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="new_branch_name" id="new_branch_name" placeholder="Nombre de la Sucursal" />
                                        </div>

                                        <br>
                                        <label>Teléfono </label>
                                        <div class="input-group">
                                            <input type="number" oninput="if(value.length>8)value=value.slice(0,8)" class="form-control" name="new_branch_phone" id="new_branch_phone" placeholder="55449988" />
                                        </div>

                                        <br>
                                        <label>Dirección </label>
                                        <div class="input-group">
                                            <textarea class="form-control" name="new_branch_address" id="new_branch_address" placeholder='' rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>IVA <span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox" id='iva' readonly name='costo_iva' min='0' step='any'  />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Costo <span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox" id='cost' required name='cost' min='0' step='any' onchange="ganancia()" onblur="ganancia()" oninput="ganancia()" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Precio Publico<span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox" id='price' onchange="ganancia()" onblur="ganancia()" oninput="ganancia()" required name='price' min='0' step='any' />
                                    </div>
                                    <small class='text-success' id='msGanancia'></small>
                                    <input type="hidden" class="form-control" id='totalGanancia' required name='totalGanancia' min='0' step='any' />
                                    <input type="hidden" value="" name= "old_price" id="old_price">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Precio Socio <span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox" id='farma' required name='farma' min='0' step='any' onchange="ganancia()" onblur="ganancia()" oninput="ganancia()" />
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Precio Mayorista <span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox" id='may' required name='may' min='0' step='any' onchange="ganancia()" onblur="ganancia()" oninput="ganancia()" />
                                        <input type="hidden" value="" name = "old_may" id="old_may">
                                    </div>
                                </div>
                            </div>
                            

                            

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Stock inicial <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" min='0' class="form-control" aria-label="Text input with checkbox"  name='stock' value='0' />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Cantidad de alerta <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox" name='alert' min='0' required />
                                    </div>
                                    <small>(Se te notificará cuando el producto llegue a esta cantidad)</small>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><b> Ubicación </b></label>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Pasillo <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder='0-9 o A-Z' aria-label="Text input with checkbox" name='corridor'  />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Tarima <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder='0-9 o A-Z' aria-label="Text input with checkbox" name='pallet'  />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Estante <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder='0-9 o A-Z' aria-label="Text input with checkbox" name='shelf'  />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Nivel <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder='0-9 o A-Z' aria-label="Text input with checkbox" name='level'  />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label><b> Detalles Tributarios </b></label>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <span class="switch switch-icon">
                                        <label>
                                            <label>IVA</label>
                                            <input type="checkbox" name="iva_check" value="1" id="iva_check" onchange="fn_iva(this.value)" checked ><br>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label> <b> Descripción </b></label>
                                    <div class="input-group">
                                        <textarea class="form-control" aria-label="Text input with checkbox" name='description' rows="6" placeholder="Describe el producto aquí ..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12"><br><br>
                                <button type="submit" class="btn btn-primary font-weight-bold" id='submitPro' disabled style="float: right;">Guardar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    verificar();
    ganancia();
});

$(document).ready(function() {
    $("form").keypress(function(e) {
        if (e.which == 13) {
            return false;
        }
    });
});

$(document).ready(function() {
    //Select de sucursal
    $('#selected-0').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });
    //Select de marca
    $('#selected-1').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true,
        tags: true
    });
    //Select de proveedor
    $('#selected-2').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });
    //Select de categoria
    $('#selected-3').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true,
        tags: true
    });
    //Select de tipo de producto
    $('#selected-4').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true,
        tags: true
    });
    //Select de caja
    $('#selected_caja').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true,
        tags: true
    });

    $('#new_provider').hide();
    $('#caja').hide();
    $('#location').hide();
    $("#variantes").hide();
})

function ganancia() {
    iva();
    var cost = parseFloat($('#cost').val());
    var price = parseFloat($('#price').val());

    if (cost < price) {
        var total = ((price - cost) / cost) * 100;
        $('#msGanancia').html('La ganancia equivale al ' + total.toFixed(2) + '%');
        $('#totalGanancia').val(total.toFixed(2));
        $('#submitPro').removeAttr('disabled');
    } else {
        $('#msGanancia').html('El precio no puede ser menor al costo');
        $('#submitPro').attr('disabled', true);
    }
}
var x = 1;
var y = 1;

function iva(){
    var check = document.getElementById("iva_check").checked
    if (check == true){
    var costo = parseFloat($('#cost').val());
    var sin_iva = (costo/1.12);
    var mas_iva = (sin_iva * 0.12 );
    $('#iva').val(mas_iva.toFixed(2));
        
    }else{
        mas_iva=0;
        $('#iva').val(mas_iva.toFixed(2));
    }
}
function fn_iva() {
    var check = document.getElementById("iva_check").checked
    if (check == true){
    var costo = parseFloat($('#cost').val());
    var sin_iva = (costo/1.12);
    var mas_iva = (sin_iva * 0.12 );
    $('#iva').val(mas_iva.toFixed(2));
        
    }else{
        mas_iva=0;
        $('#iva').val(mas_iva.toFixed(2));
    }
    //var mas_iva=0;
      //  $('#iva').val(mas_iva.toFixed(2));
}

function post(value) {
    if (value == 1) {
        $("#variantes").show(500);
    } else if (value == 2) {
        $("#variantes").hide(500);
    }
}


function add_branch(value) {
    if (value == "Nuevo") {
        $('#new_branch').show(500);
        $('#new_branch_name').attr('required', 'true');

    } else {
        $('#new_branch').hide(500);
        $('#new_branch_name').removeAttr('required');

    }
    if (value == 0) {
        $('#location').show(500);
        $('#location_b').attr('required', 'true');

    } else {
        $('#location').hide(500);
        $('#location_b').removeAttr('required');
    }
}



function add_provider(value) {
    if (value == "Nuevo") {
        $('#new_provider').show(500);
        $('#new_provider_name').attr('required', 'true');

    } else {

        $('#new_provider_name').removeAttr('required');
        $('#new_provider').hide(500);
    }
}

function new_caja(value) {
    if (value == "Caja") {
        $('#caja').show(500);
        $('#prod_matriz').attr('required', 'true');
        $('#cnt_prod_matriz').attr('required', 'true');

    } else {
        $('#prod_matriz').removeAttr('required');
        $('#cnt_prod_matriz').removeAttr('required');
        $('#caja').hide(500);
    }
}

function view_category(value) {
    if ($.isNumeric(value)) {
        $('#tag_nueva_categoria').html('');
    } else {
        $('#tag_nueva_categoria').html('(Nueva)');
    }
}

function view_type_product(value) {
    if ($.isNumeric(value)) {
        $('#tag_nuevo_tipo').html('');
    } else {
        $('#tag_nuevo_tipo').html('(Nuevo)');
    }
}

function view_mark(value) {
    if ($.isNumeric(value)) {
        $('#tag_nueva_marca').html('');
    } else {
        $('#tag_nueva_marca').html('(Nueva)');
    }
}

function verificar() {
    var codigo = '';
    var code = $('#code').val();

    codigo = code;

    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/codigo_producto/',
        data: {
            code: codigo,
        },
        success: function(response) {
            if (response == 0) {
                $('#msg_error').html("");
                $('#submitPro').removeAttr("hidden");
            } else {
                $('#msg_error').html('El código del producto ya existe');
                $('#submitPro').attr("hidden", "true");
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}
</script>
