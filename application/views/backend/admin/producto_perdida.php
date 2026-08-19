<?php 
    $code      = $this->crud_model->getCodePerdida();
    $moneda    = $this->crud_model->get_info("moneda");
    $prod	   = $this->db->get_where('products' , array('products_id' => $param2))->row_array();
    $ventas    = $this->db->select_sum('amount', 'amount')->get_where('product_details', array('products_id' => $param2, 'type' => 0, 'status' => 1))->row()->amount;
    $stock     = 0;
    $branch_id = $this->session->userdata('branch_id');
?>
<form class="form" action="<?php echo base_url().'admin/producto_detalle/perdida/'.$param2;?>" method="POST"
    enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <h3><strong><?php echo 'Pérdida - '.$code;?></strong></h3>
            </div>
            <input type="hidden" name="code" value="<?php echo $code;?>"/>
        </div>
        <div class="col-sm-12">
            <div class="form-group">
                <div class="alert alert-custom alert-default" role="alert">
                    <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                    <div class="alert-text">
                        Los campos marcados con * son obligatorios. <?php echo $ventas;?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Origen</label>
                <select name="branch"  class='form-control' >
                      <option value="0">Bodega</option>
                      <option value="1">Tienda</option>
                 </select>
            </div>
        </div>
        
        <div class="col-sm-6">
            <div class="form-group">
                <label>Producto</label>
                <div class="input-group">
                    <label class="form-control" style="text-transform: uppercase; height: auto;"><?php echo $prod['name'].' - '.$prod['code'];?></label>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group">
                <label>Fecha <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input class="form-control" type="date" name="date" value="<?php echo date('Y-m-d');?>" />
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <small class="text-danger">En caso de que no devolver productos dejar en blanco o con cantidad 0 las casillas de "Cantidad de pérdida"</small>
        </div>
        <style>
        .tbl td {
            padding: 5px !important;
        }
        </style>
        <div class="col-sm-12">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Lote</th>
                            <th>Fecha</th>
                            <th>Cantidad disponible</th>
                            <th>Costo de compra</th>
                            <th>Cantidad de pérdida</th>
                            <th>Pérdida</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $this->db->order_by('product_details_id', 'ASC');
                            $this->db->where('products_id', $prod['products_id']);
                            $this->db->where('status', '1');
                            $this->db->group_start();
                            $this->db->where('type', 1);
                            $this->db->or_where('type', 2);
                            $this->db->group_end();
                            $lotes = $this->db->get('product_details'); ?>
                        <?php foreach ($lotes->result_array() as $det):
                            $stock = $det['amount'] - $ventas; 
                            $ventas -= $det['amount']; 
                            $data = $this->crud_model->get_product_details($det['products_id']);
                            if($stock > 0) {$ventas = 0;}
                                $perdidas = $this->crud_model->products_returned_lost($det['product_details_id']); $stock -= $perdidas; 
                                
                                if($stock > 0): ?>
                        <tr>
                            <input type="hidden" name="product_details_id[]" value="<?php echo $det['product_details_id'];?>" />
                            <td>
                                <?php if ($det['activity_ref'] != ""):?>
                                <span class="text-info" ><?php echo $det['activity_ref'];?></span>
                                <?php else:?>
                                <span class="text-success">Inicial</span>
                                <?php endif;?>
                            </td>
                            <td>
                                <span class="text-info font-weight-bolder"><?php echo date("d-m-Y", strtotime($det['date']));?></span>
                            </td>
                            <td>
                                <?php if($product->presentation == 'Caja'): ?>
                                            <span class="text-muted font-weight-bolder font-size-lg"><?php  $stock_inventory = $this->crud_model->get_stock($det['id_prod_matriz'], $this->session->userdata('branch_id')); echo $stock_inventory/$product->cnt_prod_matriz; ?></span>
                                        <?php endif;?>
                                        <?php if($product->presentation != 'Caja'): ?>
                                            <span class="text-muted font-weight-bolder font-size-lg"><?php echo $stock_inventory = $this->crud_model->get_stock($det['products_id'], $this->session->userdata('branch_id'));?></span>
                                        <?php endif;?>
                                <input type="hidden" id="amount_orig-<?php echo $det['product_details_id'];?>" name="amount_orig[]" value="<?php echo $stock_inventory ?>"/>
                            </td>
                            <td>
                                <span class="text-info font-weight-bolder"><?php echo $moneda.number_format($det['cost'],2,'.',',');?></span>
                                <input type="hidden" id="cost_orig-<?php echo $det['product_details_id'];?>" name="cost_orig[]" value="<?php echo $det['cost']; ?>"/>
                            </td>
                            <td>
                                <input type="number" class='form-control' id="amount_per-<?php echo $det['product_details_id'];?>" name="amount_per[]" min='0' step="1" max='<?php echo $stock_inventory;?>' value='' oninput="sum('<?php echo $det['product_details_id'];?>')">
                            </td>
                            <td>
                                <span class="text-danger font-weight-b" id="perdidal-<?php echo $det['product_details_id'];?>"><?php echo $moneda;?>0.00</span>
                                <input type="hidden" id="perdida-<?php echo $det['product_details_id'];?>" name="perdida[]" class='total' min='0' value=''>
                            </td>
                        </tr>
                            <?php endif;
                        endforeach;?>
                    </tbody>
                    <input type="hidden" name="ttl" id='ttl' value='0'>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary font-weight-bold" id="submit_perdida" disabled>Registrar</button>
    </div>
</form>


<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';

$('document').ready(function() {
    <?php foreach ($lotes->result_array() as $dets):?>
        sum('<?php echo $dets['lote_id'] ?>');
    <?php endforeach; ?>
});

function sum(i) {
    $amount_per = $('#amount_per-' + i).val();
    $cost_orig = $('#cost_orig-' + i).val();
    if ($amount_per == "") {
        $amount_per = 0;
    }
    if ($cost_orig == "") {
        $cost_orig = 0;
    }

    var total = parseInt($amount_per) * parseFloat($cost_orig);
    var total_format = custom_number_format(total, '2',);

    $('#perdidal-' + i).html(moneda + total_format);
    $('#perdida-' + i).val(total);

    var suma = 0;
    $('.total').each(function() {
        suma += parseFloat($(this).val());
    });
    $('#ttl').val(suma);
    var count = $('.total').length;
    if (suma <= 0 || count <= 0) {
        $('#submit_perdida').attr('disabled', 'true');
    }
    else{
        $('#submit_perdida').removeAttr('disabled');
    }
}

function custom_number_format( number_input, decimals, dec_point, thousands_sep ) {
    var number       = ( number_input + '' ).replace( /[^0-9+\-Ee.]/g, '' );
    var finite_number   = !isFinite( +number ) ? 0 : +number;
    var finite_decimals = !isFinite( +decimals ) ? 0 : Math.abs( decimals );
    var seperater     = ( typeof thousands_sep === 'undefined' ) ? ',' : thousands_sep;
    var decimal_pont   = ( typeof dec_point === 'undefined' ) ? '.' : dec_point;
    var number_output   = '';
    var toFixedFix = function ( n, prec ) {
        if( ( '' + n ).indexOf( 'e' ) === -1 ) {
            return +( Math.round( n + 'e+' + prec ) + 'e-' + prec );
        } else {
            var arr = ( '' + n ).split( 'e' );
            let sig = '';
            if ( +arr[1] + prec > 0 ) {
                sig = '+';
            }
            return ( +(Math.round( +arr[0] + 'e' + sig + ( +arr[1] + prec ) ) + 'e-' + prec ) ).toFixed( prec );
        }
    }
    number_output = ( finite_decimals ? toFixedFix( finite_number, finite_decimals ).toString() : '' + Math.round( finite_number ) ).split( '.' );
    if( number_output[0].length > 3 ) {
        number_output[0] = number_output[0].replace( /\B(?=(?:\d{3})+(?!\d))/g, seperater );
    }
    if( ( number_output[1] || '' ).length < finite_decimals ) {
        number_output[1] = number_output[1] || '';
        number_output[1] += new Array( finite_decimals - number_output[1].length + 1 ).join( '0' );
    }
    return number_output.join(decimal_pont );
}
</script>