<?php $moneda = $this->crud_model->get_info("moneda");
$sales = $this->db->get_where('sales', array('code'=>$code)); 
if($sales->num_rows() > 0){
    $data = $sales;
}else{
    $data = $this->db->get_where('quotes', array('code'=>$code));
}
;?>

<div class="container-fluid">
    <form class="form" action="<?php echo base_url().'admin/ventas/update/'.$code;?>" method="POST"
        enctype="multipart/form-data">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-xxl-12">
                                <span>Código de orden: <b><?php echo $code ;?></b></span>
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
                                                foreach ( $products->result_array() as $product):
                                                    $stock = $this->crud_model->get_stock($product['products_id'], $this->session->userdata('branch_id'));
                                                    if($stock > 0 ):?>

                                                    <option value='<?php echo $product['name'];?>'>
                                                        <?php echo $product['name'];?></option>
                                                    <?php endif; endforeach;?>
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
            <div class="col-sm-12" id="list_products">
                <div class="card ">
                    <div class="card-body">
                        <h3 class="card-label text-info">Productos</h3>
                        <div class="table-responsive">
                            <table class="table table-padded">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio unitario</th>
                                        <th>Descuento</th>
                                        <th>Subtotal</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                <tbody id='products'>
                                    <?php  ;for($i=0; $i < $data->row()->num_products ; $i++):  
                                    if ($data->row()->products != "" || $data->row()->products != null) {
                                        $pro = json_decode($data->row()->products,true);
                                    } else {
                                        $pro = array();
                                    }?>
                                    <tr id='producto-<?php echo $i; ?>'>
                                        <td>
                                            <?php echo $pro[$i]['product'];?>
                                            <input type="hidden" name="product[]"
                                                value='<?php echo $pro[$i]['product'];?>'>
                                        </td>
                                        <td>
                                            <input min="1" max="999" class="form-control" type="number"
                                                style="width:70px" step="any" id="amount-<?php echo $i; ?>"
                                                name="amount[]" value="<?php echo $pro[$i]['amount']?>"
                                                onblur="sum('<?php echo $i;?>')">
                                        </td>
                                        <td>
                                            <?php echo 'Q.'.number_format($pro[$i]['price'],2,'.',',');?>
                                            <input min="1" max="999" class="form-control" type="hidden"
                                                style="width:110px" step="any" id="price-<?php echo $i;?>"
                                                name="price[]" value="<?php echo $pro[$i]['price']?>">
                                        </td>

                                        <td>
                                            <input min="1" max="999" class="form-control" type="number"
                                                style="width:70px" step="any" id="discount-<?php echo $i;?>"
                                                name="discount[]" value="" onblur="sum('<?php echo $i;?>')">
                                        </td>

                                        <td><span class="text-success"
                                                id='sub-<?php echo $i;?>'><?php echo $moneda.number_format($pro[$i]['sub'],2,'.',',');?></span>
                                            <input type="hidden" class='total' name="sub[]" id='subt-<?php echo $i;?>'>
                                        </td>
                                        <td>
                                            <a class="badge badge-danger" style="padding:3px;"
                                                onclick="removeOption('<?php echo $i;?>')" href="javascript:;">
                                                <span class="svg-icon svg-icon-white svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <path
                                                                d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                                                                fill="#000000" fill-rule="nonzero"></path>
                                                            <path
                                                                d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                                                fill="#000000" opacity="0.3"></path>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endfor;?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <div
                                class="bg-primary rounded d-flex  justify-content-between text-white position-relative ml-auto p-7">
                                <div class="position-absolute opacity-30 top-0 right-0">
                                    <span class="svg-icon svg-icon-2x svg-logo-white svg-icon-flip">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="176" height="165"
                                            viewBox="0 0 176 165" fill="none">
                                            <g clip-path="url(#clip0)">
                                                <path
                                                    d="M-10.001 135.168C-10.001 151.643 3.87924 165.001 20.9985 165.001C38.1196 165.001 51.998 151.643 51.998 135.168C51.998 118.691 38.1196 105.335 20.9985 105.335C3.87924 105.335 -10.001 118.691 -10.001 135.168Z"
                                                    fill="#AD84FF"></path>
                                                <path
                                                    d="M28.749 64.3117C28.749 78.7296 40.8927 90.4163 55.8745 90.4163C70.8563 90.4163 83 78.7296 83 64.3117C83 49.8954 70.8563 38.207 55.8745 38.207C40.8927 38.207 28.749 49.8954 28.749 64.3117Z"
                                                    fill="#AD84FF"></path>
                                                <path
                                                    d="M82.9996 120.249C82.9996 144.964 103.819 165 129.501 165C155.181 165 176 144.964 176 120.249C176 95.5342 155.181 75.5 129.501 75.5C103.819 75.5 82.9996 95.5342 82.9996 120.249Z"
                                                    fill="#AD84FF"></path>
                                                <path
                                                    d="M98.4976 23.2928C98.4976 43.8887 115.848 60.5856 137.249 60.5856C158.65 60.5856 176 43.8887 176 23.2928C176 2.69692 158.65 -14 137.249 -14C115.848 -14 98.4976 2.69692 98.4976 23.2928Z"
                                                    fill="#AD84FF"></path>
                                                <path
                                                    d="M-10.0011 8.37466C-10.0011 20.7322 0.409554 30.7493 13.2503 30.7493C26.0911 30.7493 36.5 20.7322 36.5 8.37466C36.5 -3.98287 26.0911 -14 13.2503 -14C0.409554 -14 -10.0011 -3.98287 -10.0011 8.37466Z"
                                                    fill="#AD84FF"></path>
                                                <path
                                                    d="M-2.24881 82.9565C-2.24881 87.0757 1.22081 90.4147 5.50108 90.4147C9.78135 90.4147 13.251 87.0757 13.251 82.9565C13.251 78.839 9.78135 75.5 5.50108 75.5C1.22081 75.5 -2.24881 78.839 -2.24881 82.9565Z"
                                                    fill="#AD84FF"></path>
                                                <path
                                                    d="M55.8744 12.1044C55.8744 18.2841 61.0788 23.2926 67.5001 23.2926C73.9196 23.2926 79.124 18.2841 79.124 12.1044C79.124 5.92653 73.9196 0.917969 67.5001 0.917969C61.0788 0.917969 55.8744 5.92653 55.8744 12.1044Z"
                                                    fill="#AD84FF"></path>
                                            </g>
                                        </svg>
                                    </span>
                                </div>
                                <div class="font-weight-boldest font-size-h5">TOTAL</div>
                                <div class="text-right d-flex flex-column">

                                    <span class="font-weight-boldest font-size-h3 line-height-sm"
                                        id='total'><?php echo 'Q.'.number_format($data->row()->total,2,'.',',');?></span>
                                    <input type="hidden" name="ttl" id='ttl'>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';

$(document).ready(function() {
    <?php for ($i=0; $i < $data->row()->num_products ; $i++):?>
    var i = '<?php echo $i;?>'
    sum(i)
    <?php endfor;?>

    $('.js-example-basic-single-0').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });
})


function cambio() {
    let pago = $('#pago').val();
    let cancelar = $('#ttl').val();
    let total = pago - cancelar;
    if (pago > 0) {
        $('#changee').val(total.toFixed(2));
        $('#change').html(moneda + total.toFixed(2));
    } else {
        $('#changee').val('0');
        $('#change').html(moneda + '0.0');
    }
    if (total < 0) {
        $('#changee').val('0');
        $('#change').html(moneda '0.0');

    }
}


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
    let precio = $('#price-' + i).val();
    let descuento = $('#discount-' + i).val();

    let mul = (parseInt(cantidad) * parseInt(precio));
    let des = mul * (descuento / 100);
    let total = mul - des;

    $('#sub-' + i).html(moneda + total.toFixed(2));
    $('#subt-' + i).val(total.toFixed(2));

    let suma = 0;
    $('.total').each(function() {
        suma += parseFloat($(this).val());
    });
    $('#total').html(moneda + suma.toFixed(2));
    $('#total_a').html(moneda + suma.toFixed(2));
    $('#ttl').val(suma.toFixed(2));


}

function removeOption(i) {
    $('#producto-' + i).remove();
    sum()
    if ($('.total').length == 0)
        $('#list_products').hide(500);
}

function addOption($product_id) {

    $('#list_products').show(500);
    var id = Math.floor(Math.random() * 300) + 10;
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/get_productos/' + $product_id + '/' + id,
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
        url: '<?php echo base_url();?>admin/get_variantes/' + $product_id + '/' + id + '/' + $i,
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