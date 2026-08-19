<div class="container-fluid">
    <form class="form" action="<?php echo base_url();?>admin/compras/create" method="POST"
        enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-xxl-12">
                                <input type="hidden" name="code" value='<?php echo $code;?>'>
                                <input type="hidden" name="type" value='1'>
                                <span>Código de compra: <b>
                                        <? echo $code ?>
                                    </b> <span style="float:right"><b>Fecha de solicitud:</b> <?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d-m-Y", strtotime( date('Y-m-d') ));				
                                        $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); 
                                        echo $Mes_Anyo;?>
                                        <?php echo date('H:i:s');?> </span></span>
                                <br>
                                <div class="row">
                                    <div class="col-sm-12"> <br>
                                        <div class="input-group">
                                            <select class="js-example-basic-single-0 form-control" name="productos[]"
                                                id='selected-0' onchange="search(this.value)">
                                                <option value="">Seleccionar</option>
                                                <?php $categories = $this->crud_model->get_categories()->result_array() ; foreach($categories as $category):?>
                                                <optgroup label="<?php echo $category['name'];?>">
                                                    <?php $products = $this->db->get_where('products', array('status'=>1, 'branch_id'=>$this->session->userdata('branch_id'), 'category'=> $category['category_id'])); 
                                                foreach ( $products->result_array() as $product):?>
                                                    <option value='<?php echo $product['name'];?>'>
                                                        <?php echo $product['name'];?></option>
                                                    <?php endforeach;?>
                                                </optgroup>
                                                <?php endforeach;?>
                                            </select>
                                        </div>
                                        <br>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-padded">
                                        <tbody id="resultado" class="mostly-customized-scrollbars col-sm-12"
                                            style="background-color: #fcfcfc; margin-bottom: 0px !important;">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
            </div>

            <div class="col-sm-12">
                <div class="card ">
                    <div class="card-body">
                        <h3 class="card-label text-info">Productos a registrar</h3>
                        <div class="table-responsive">
                            <table class="table table-padded">
                                <thead>
                                    <tr>
                                        <th class='text-left'>Producto</th>
                                        <th class='text-left'>Provedor</th>
                                        <th class='text-left'>Cantidad</th>
                                        <th class='text-left'>Stock</th>
                                        <th class='text-left'>P/U</th>
                                        <th class='text-left'>C/U</th>
                                        <th class='text-left'>Subtotal</th>
                                        <th class='text-right'>-</th>
                                    </tr>
                                </thead>
                                <tbody id='products'>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.js-example-basic-single-0').select2({
        language: "es",
        placeholder: 'Selecionar',
        allowClear: true
    });

    $('.js-example-basic-single-').select2({
        language: "es",
        placeholder: 'Selecionar',
        allowClear: true
    });
    sum(0);
})

function search(value) {
    $name = value;
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/productSale',
        data: {
            name: $name,
        },
        success: function(response) {
            jQuery('#resultado').html(response);
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}

function sum(i) {

    let cantidad = $('#amount-' + i).val();
    let precio = $('#price_buy-' + i).val();
    let total = (parseInt(cantidad) * parseInt(precio));

    $('#sub-' + i).html('Q.' + total.toFixed(2));
    $('#subt-' + i).val(total.toFixed(2));

    let suma = 0;
    $('.total').each(function() {
        suma += parseFloat($(this).val());
    });
    $('#total').html('Q.' + suma.toFixed(2));
    $('#ttl').val(suma.toFixed(2));

}

function removeOption(i) {
    $('#producto-' + i).remove();
    sum()
}

function addOption($product_id) {

    var id = Math.floor(Math.random() * 300) + 10;
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/get_productos_compras/' + $product_id + '/' + id,
        success: function(response) {
            jQuery('#products').append(response);
            sum(id);
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}

function addVariantes($product_id, $i) {

    var id = Math.floor(Math.random() * 300) + 10;
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/get_variantes_compras/' + $product_id + '/' + id + '/' + $i,
        success: function(response) {
            jQuery('#products').append(response);
            sum(id);
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });

}
</script>