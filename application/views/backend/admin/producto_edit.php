<?php $data = $this->db->get_where('products', array('products_id'=>$ID)); foreach ($data->result_array() as $row): ?>
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
                        <a href="<?php echo base_url().'admin/inventario' ;?>" class="btn btn-light-primary font-weight-bolder">
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
                    <form class="form" action="<?php echo base_url().'admin/nuevo_producto/update/'.$ID;?>" method="POST" enctype="multipart/form-data">
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
                                    
                                    
                                </div><br>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group text-center">
                                    <div class="col-lg-12 col-xl-12">
                                        <div class="image-input image-input-outline" id="kt_profile_avatar" style="background-color: #fff">
                                            <div class="image-input-wrapper" style="background-image: url(<?php echo ($row['img']!='') ? base_url().'uploads/productos/'.$row['img'] :base_url().'uploads/productos/default_product.png';?> );background-size:contain; background-position:center;">
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
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Producto <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="Text input with checkbox" name='name' required value='<?php echo $row['name'];?>' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Código <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" aria-label="Text input with checkbox" placeholder="FDF34FS" name='code' maxlength="25" required value='<?php echo $row['code'];?>' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Clase de producto <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" name='class_product_id'  required >
                                            <option value=''>Seleccionar</option>
                                            <?php $types = $this->crud_model->get_class_product(); foreach ($types->result_array() as $type):?>
                                            <option value="<?php echo $type['class_product_id'];?>" <?php echo ($type['class_product_id']== $row['class_product_id'])?'selected':'' ?>>
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
                                        <select class="form-control" id='selected-type_product' name='type_product_id' required>
                                            <option value=''>Seleccionar</option>
                                            <?php $types = $this->crud_model->get_types_product(); foreach ($types->result_array() as $type):?>
                                            <option value="<?php echo $type['type_product_id'];?>" <?php echo ($type['type_product_id']== $row['type_product_id'])?'selected':'' ?>>
                                                <?php echo $type['name'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Categoría <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" id='selected-category' name='category' required>
                                            <option value=''>Seleccionar</option>
                                            <?php $categories = $this->crud_model->get_categories(); foreach ($categories->result_array() as $category):?>
                                            <option value="<?php echo $category['category_id'];?>" <?php echo ($category['category_id']== $row['category'])?'selected':'' ?>>
                                                <?php echo $category['name'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Marca <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" name='mark' id='selected-marca' required>
                                            <option value=''>Seleccionar</option>
                                            <?php $marks = $this->crud_model->get_mark(); foreach ($marks->result_array() as $mark):?>
                                            <option value="<?php echo $mark['mark_id'];?>" <?php echo ($mark['mark_id'] ==  $row['mark'])?'selected':'';?>>
                                                <?php echo $mark['name'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Proveedor <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select class="form-control" name='provider' id='selected-provider' required>
                                            <option value=''>Seleccionar</option>
                                            <?php $providers = $this->db->order_by('name', 'ASC')->get_where('provider',array('status'=>1)); foreach ($providers->result_array() as $provider):?>
                                            <option value="<?php echo $provider['provider_id'];?>" <?php echo ($provider['provider_id'] == $row['provider']) ? 'selected':'' ?>>
                                                <?php echo $provider['name'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label> Presentación de producto <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <select name="selected_caja" id="selected_caja" class='form-control' required onchange="new_caja(this.value)">
                                                <option value="Unidad" <?php if($row['presentation']=='Unidad'){echo 'selected';}?>>Unidad</option>
                                                <option value="Caja" <?php if($row['presentation']=='Caja'){echo 'selected';}?>>Caja</option>
                                        </select>
                                    <!--    <input type="text" name="presentation" class="form-control" required placeholder='Unidad, Caja'> --> 
                                    </div>
                                </div>
                            </div>
                            <div id="caja" class="col-sm-4" <?php if($row['presentation']=='Caja'){echo 'style = "display:block"';}else{echo 'style = "display:none"';}?> >
                                <label>Codigo de Producto matriz <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="prod_matriz" id="prod_matriz" value="<?php echo $row['prod_matriz'];?>" placeholder="Ej: A001 " />
                                </div>
                                <label>Cantidad de Producto matriz <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="cnt_prod_matriz" id="cnt_prod_matriz" value="<?php echo $row['cnt_prod_matriz'];?>" placeholder="Ej: 10 " />
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label> Peso del Producto <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="weight"  value='<?php echo $row['weight'];?>' aria-label="Text input with dropdown button">
                                        <div class="input-group-append">
                                            <select name="unit" class='form-control'>
                                                <optgroup label="Unidades de Peso">
                                                    <option value="Kg" <?php echo ($row['unit'] == 'Kg') ?'selected':'';?>>Kilogramo</option>
                                                    <option value="L" <?php echo ($row['unit'] == 'L') ?'selected':'';?>>Libras
                                                    </option>
                                                    <option value="G" <?php echo ($row['unit'] == 'G') ?'selected':'';?>>Gramos
                                                    </option>
                                                    <option value="Oz" <?php echo ($row['unit'] == 'Oz') ?'selected':'';?>>Onzas
                                                    </option>
                                                </optgroup>
                                                <optgroup label="Unidades de Capacida">
                                                    <option value="Ml" <?php echo ($row['unit'] == 'Ml') ?'selected':'';?>>Mililitros
                                                    </option>
                                                    <option value="Lt" <?php echo ($row['unit'] == 'Lt') ?'selected':'';?>>Litros
                                                    </option>
                                                    <option value="Gl" <?php echo ($row['unit'] == 'Gl') ?'selected':'';?>>Galones
                                                    </option>

                                                </optgroup>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Cantidad de alerta <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox" name='alert' min='0' required value='<?php echo $row['alert'];?>' />
                                    </div>
                                    <small>(Se te notificará cuando el producto llegue a esta cantidad)</small>
                                </div>
                            </div>
                            
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>IVA <span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox" id='iva' readonly name='costo_iva' min='0' step='any'  value='<?php echo $row['costo_iva'];?>' />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Costo <span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox" id='cost' required name='cost' min='0' step='any' onchange="ganancia()" onblur="ganancia()" oninput="ganancia()" value='<?php echo $row['cost'];?>' />
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Precio Socio <span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox" id='farma' required name='farma' min='0' step='any' onchange="ganancia()" onblur="ganancia()" oninput="ganancia()" value='<?php echo $row['farma'];?>' <?php if($user_type != '1') echo "";?>/>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Precio Mayorista <span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox" id='may' required name='may' min='0' step='any' value='<?php echo $row['precio_mayorista'];?>' <?php if($user_type != '1') echo "";?>/>
                                    <input type="hidden" value="<?php echo $row['old_may'];?>" name="old_may" id="old_may">
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Precio Publico <span class="text-danger">*</span> </label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" aria-label="Text input with checkbox" id='price' onchange="ganancia()" onblur="ganancia()" required name='price' min='0' step='any' value='<?php echo $row['price'];?>' <?php if($user_type != '1') echo "";?>/>
                                    </div>
                                    <small class='text-success' id='msGanancia'></small>
                                    <input type="hidden" class="form-control" id='totalGanancia' required name='totalGanancia' min='0' step='any' value='<?php echo $row['totalGanancia'];?>' />
                                    <input type="hidden" value="<?php echo $row['old_price'];?>" name="old_price" id="old_price">
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
                                        <input type="text" class="form-control" placeholder='0-9 o A-Z' aria-label="Text input with checkbox" name='corridor'  value='<?php echo $row['corridor']?>' />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Tarima <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder='0-9 o A-Z' aria-label="Text input with checkbox" name='pallet'  value='<?php echo $row['pallet']?>' />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Estante <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder='0-9 o A-Z' aria-label="Text input with checkbox" name='shelf'  value='<?php echo $row['shelf']?>' />
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Nivel <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder='0-9 o A-Z' aria-label="Text input with checkbox" name='level'  value='<?php echo $row['level']?>' />
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
                                            <input type="checkbox" name="iva_check" value="1" id="iva_check" <?php if($row['iva'] == 1){echo 'checked';}?> onchange="fn_iva(this.value)"><br>
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <div class="input-group">
                                        <textarea class="form-control" aria-label="Text input with checkbox" name='description' rows="6"><?php echo $row['description'];?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12"><br><br>
                                <button type="submit" class="btn btn-primary font-weight-bold" style="float: right;">Actualizar</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endforeach;?>
<script type="text/javascript">
$(document).ready(function() {

    $('#selected-sucursal').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });

    $('#selected-marca').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true,
        tags: true
    });

    $('#selected-provider').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });

    $('#selected-category').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true,
        tags: true
    });
    $('#selected-type_product').select2({
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
    
    ganancia();

})

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

function ganancia() {
    iva();
    var cost = parseInt($('#cost').val());
    var price = parseInt($('#price').val());
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
</script>
