<?php 
    $code = $this->crud_model->getCodeDevolucion(); 
    $moneda = $this->crud_model->get_info("moneda");
    $prod	= $this->db->get_where('products' , array('products_id' => $param2))->row_array();
    $ventas = $this->db->select_sum('amount', 'amount')->get_where('product_details', array('products_id' => $param2, 'type' => 0, 'status' => 1))->row()->amount;
    $stock = 0;
?>
<form class="form" action="<?php echo base_url().'admin/producto_detalle/devolucion/'.$param2;?>" method="POST"
    enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <h3><strong><?php echo 'Devolucion- '.$code;?></strong></h3>
            </div>
            <input type="hidden" name="code" value="<?php echo $code;?>"/>
        </div>
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
            <small class="text-danger">En caso de que no devolver productos dejar en blanco o con cantidad 0 las casillas de "Cantidad a devolver"</small>
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
                            <th>Cantidad a devolver</th>
                            <th>Precio de devolucion<th>
                            <th>Total</th>
                            <th>Diferencia</th>
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
                            $this->db->where('branch_id', $this->session->userdata('branch_id'));
                            $lotes = $this->db->get('product_details'); ?>
                        <?php foreach ($lotes->result_array() as $det):
                            $stock = $det['amount'] - $ventas; 
                            $ventas -= $det['amount']; 
                            if($stock > 0) {$ventas = 0;}
                            $perdidas = $this->crud_model->products_returned_lost($det['product_details_id']); 
                            $stock -= $perdidas;
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
                                <span class="text-info font-weight-bolder"><?php echo $stock;?></span>
                                <input type="hidden" id="amount_orig-<?php echo $det['product_details_id'];?>" name="amount_orig[]" value="<?php echo $stock; ?>"/>
                            </td>
                            <td>
                                <span class="text-info font-weight-bolder"><?php echo $moneda.number_format($det['cost'],2,'.',',');?></span>
                                <input type="hidden" id="cost_orig-<?php echo $det['product_details_id'];?>" name="cost_orig[]" value="<?php echo $det['cost']; ?>"/>
                            </td>
                            <td>
                                <input type="number" class='form-control' id="amount_dev-<?php echo $det['product_details_id'];?>" name="amount_dev[]" min='0' step="1" max='<?php echo $stock;?>' value='' oninput="sum('<?php echo $det['product_details_id'];?>')">
                            </td>
                            <td>
                                <input type="number" class='form-control' id="cost_dev-<?php echo $det['product_details_id'];?>" name="cost_dev[]" min='0' step="0.01" max="<?php echo $det['cost']; ?>" value='' oninput="sum('<?php echo $det['product_details_id'];?>')">
                            </td>
                            <td>
                                <span class="text-info font-weight-b" id="sub_devl-<?php echo $det['product_details_id'];?>"><?php echo $moneda;?>0.00</span>
                                <input type="hidden" id="sub_dev-<?php echo $det['product_details_id'];?>" name="sub_dev[]" class='total' min='0' value=''>
                            </td>
                            <td>
                                <span class="text-info font-weight-b" id="difl-<?php echo $det['product_details_id'];?>"><?php echo $moneda;?>0.00</span>
                                <input type="hidden" id="dif-<?php echo $det['product_details_id'];?>" name="dif[]" class="perdida" min='0' value=''>
                                <input type="hidden" id="concepto_dif-<?php echo $det['product_details_id'];?>" name="concepto_dif[]" value=''>
                            </td>
                        </tr>
                            <?php endif;
                        endforeach;?>
                    </tbody>
                    <input type="hidden" name="ttl" id='ttl' value='0'>
                    <input type="hidden" name="perdida" id="perdida" value="0">
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary font-weight-bold" id="submit_devolucion" disabled>Registrar</button>
    </div>
</form>


<script type="text/javascript">
var moneda = '<?php echo $moneda; ?>';

$('document').ready(function() {
    <?php foreach ($lotes->result_array() as $dets):?>
        sum('<?php echo $dets['product_details_id'] ?>');
    <?php endforeach; ?>
});

function sum(i) {
    $amount_dev = $('#amount_dev-' + i).val();
    $cost_dev = $('#cost_dev-' + i).val();
    if ($amount_dev == "") {
        $amount_dev = 0;
    }
    if ($cost_dev == "") {
        $cost_dev = 0;
    }

    var total = parseInt($amount_dev) * parseFloat($cost_dev);
    var total_format = custom_number_format(total, '2',);

    $('#sub_devl-' + i).html(moneda + total_format);
    $('#sub_dev-' + i).val(total);

    $cost_orig = $('#cost_orig-' + i).val();

    var subt = parseInt($amount_dev) * parseFloat($cost_orig);

    var diferencia = total - subt;
    var diferencia_format = custom_number_format(diferencia, '2',);
    var msg_dif = '';
    var concepto = '';
    if (subt < total) {
        msg_dif = '<small class="text-success"> Excedente</small>';
        concepto = 'Excedente';
    }
    else if(subt > total){
        msg_dif = '<small class="text-danger"> Perdida</small>';
        concepto = 'Perdida';
    }
    else if (subt == total){
        if (subt != 0 || total != 0) {
            msg_dif = '<small class="text-info"> Equivalente</small>';
            concepto = 'Equivalente';
        }
    }

    $('#difl-' + i).html(moneda + diferencia_format + msg_dif);
    $('#dif-' + i).val(Math.abs(diferencia));
    $('#concepto_dif-' + i).val(concepto);

    var suma = 0;
    $('.total').each(function() {
        suma += parseFloat($(this).val());
    });
    $('#ttl').val(suma);
    if (suma <= 0) {
        $('#submit_devolucion').attr('disabled', 'true');
    }
    else{
        $('#submit_devolucion').removeAttr('disabled');
    }
    var perdida = 0;
    $('.perdida').each(function() {
        perdida += parseFloat($(this).val());
    });
    $('#perdida').val(perdida);
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