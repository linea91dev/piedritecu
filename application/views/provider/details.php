<!DOCTYPE html>
<html lang="es">

<head>
    <title>Detalles de la solicitud |
        <?php echo strtoupper($this->db->get_where('settings', array('type'=>'name'))->row()->description);?> </title>
    <meta charset="utf-8">
    <meta name="description" content="Updates and statistics" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php include 'topcss.php';?>
</head>
<?php $moneda = $this->crud_model->get_info("moneda"); ?>
<body id="kt_body" class="header-fixed header-mobile-fixed sidebar-enabled page-loading">
    <?php include 'mobile.php';?>
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-row flex-column-fluid page">
            <?php include 'aside.php';?>
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <br><br>
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <div class="d-flex flex-column-fluid">
                        <div class="container-fluid">
                            <?php $data = $this->db->get_where('shopping', array('code' => $code)) ;?>
                            <h3>¡Hola!
                                <b><?php echo strtoupper($this->db->get_where('settings', array('type'=>'name'))->row()->description);?></b>
                                te ha enviado una solicitud de compra.
                            </h3>
                            <form class="form" action="<?php echo base_url(),'provider/request/create/'.$code;?>"
                                method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">

                                                    <div class="col-sm-12 col-lg-4 ">
                                                        <b>Contáctanos:</b> <a
                                                            href="tel:<?php echo $this->db->get_where('settings', array('type'=>'phone'))->row()->description;?>">
                                                            <?php echo $this->db->get_where('settings', array('type'=>'phone'))->row()->description;?></a>
                                                    </div>
                                                    <div class="col-sm-12 col-lg-4 ">
                                                        <b>Encargado de la compra:</b>
                                                        <?php echo ucwords($this->crud_model->getName('admin',$data->row()->responsable));?>
                                                        <input type="hidden" name="responsable"
                                                            value='<?php echo $data->row()->responsable;?>'>
                                                    </div>
                                                    <div class="col-sm-12 col-lg-4 ">
                                                        <b>Código de solicitud:</b> <?php echo $code;?>
                                                    </div>
                                                    <div class="col-sm-12 col-lg-12 ">
                                                        <span><b>Fecha de solicitud:</b> <?php setlocale(LC_TIME, "spanish");
                                                                $Nueva_Fecha = date("d-m-Y", strtotime( $data->row()->date_request ));				
                                                                $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); 
                                                                echo $Mes_Anyo;?>
                                                        </span>
                                                        <input type="hidden" name="date_request"
                                                            value='<?php echo $data->row()->date_request ;?>'>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="card ">
                                            <div class="card-body">
                                                <h3 class="card-label text-info">Productos solicitados:</h3><br>
                                                <div class="alert alert-info">Tienes la opción de <b>cantidad
                                                        ofrecida</b> si no posees la cantidad que tu cliente solicitó,
                                                    si la modificas, o si marcas el botón al tener el producto disponible, enviaremos una notificación a
                                                    <b><?php echo $this->db->get_where('settings', array('type'=>'name'))->row()->description;?></b>
                                                    informando del cambio.
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-padded">
                                                        <thead>
                                                            <tr>
                                                                <th class='text-left'>Disponible</th>
                                                                <th class='text-left'>Producto</th>
                                                                <th class='text-center'>Cantidad solicitada</th>
                                                                <th class='text-center'>Cantidad ofrecida</th>
                                                                <th class='text-center'>Precio por Unidad</th>
                                                                <th class='text-right'>Subtotal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id='products'>
                                                            <?php for ($i=0; $i < $data->row()->num_products ; $i++): 
                                                                if ($data->row()->products != "" || $data->row()->products != null) {
                                                                    $pro = json_decode($data->row()->products,true);
                                                                } else {
                                                                    $pro = array();
                                                                }
                                                            ?>
                                                            <tr>
                                                                <td>
                                                                    <span class="switch switch-outline switch-icon switch-success">
                                                                        <label>
                                                                            <input type="checkbox" id="available-<?php echo $i;?>" name="available[]" value="1" onchange="disponible('<?php echo $i;?>')" checked><span></span>
                                                                        </label>
                                                                    </span>
                                                                </td>
                                                                <td><?php echo $this->db->get_where('products', array('products_id'=>$pro[$i]['product']))->row()->name ;?>
                                                                    <input type="hidden" name="name[]"
                                                                        value='<?php echo $pro[$i]['product'];?>'>
                                                                </td>
                                                                <td class='text-center'>
                                                                    <?php echo $pro[$i]['amount'];?>
                                                                    <input type="hidden" name="amount_request[]" id="amount_request-<?php echo $i;?>"
                                                                        value='<?php echo $pro[$i]['amount'];?>'>
                                                                </td>
                                                                <td><input type="number" class="form-control" step="any" style='max-width: fit-content;'
                                                                        name='amount_give[]'
                                                                        id='amount-<?php echo $i;?>'
                                                                        onblur="sum(<?php echo $i;?>)"
                                                                        value="<?php echo $pro[$i]['amount'];?>"
                                                                        min='0'>
                                                                </td>
                                                                <td><input type="number" class="form-control" step="any" style='max-width: fit-content;'
                                                                        name='price_buy[]' step="0.01"
                                                                        id='price_buy-<?php echo $i;?>'
                                                                        onblur="sum('<?php echo $i ;?>')"
                                                                        value="<?php echo $pro[$i]['price_buy'];?>"
                                                                        min='0.01'>
                                                                </td>
                                                                <td class='text-right'><span class="text-success"
                                                                        id='sub-<?php echo $i;?>'><b><?php echo $moneda;?>0.00</b></span>
                                                                    <input type="hidden" class='total' name="sub[]"
                                                                        id='subt-<?php echo $i;?>'>
                                                                </td>
                                                            </tr>
                                                            <?php endfor;?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <hr>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <?php include 'footer.php';?>
            </div>
            <?php include 'sidebar-left.php';?>
        </div>
    </div>
    <?php include 'notifications.php';?>
    <div id="kt_scrolltop" class="scrolltop">
        <span class="svg-icon">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24" />
                    <rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
                    <path
                        d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z"
                        fill="#000000" fill-rule="nonzero" />
                </g>
            </svg>
        </span>
    </div>
    <?php include 'scripts.php';?>

    <script type="text/javascript">
    var moneda = '<?php echo $moneda; ?>';

    $(document).ready(function() {
        <?php for ($i=0; $i < $data->row()->num_products; $i++):?>
        sum(<?php echo $i;?>);
        <?php endfor;?>
    })

    function sum(i) {

        let cantidad = $('#amount-' + i).val();
        let precio = $('#price_buy-' + i).val();
        if (precio == "" || precio == NaN) {
            precio = 0;
        }
        let total = parseInt(cantidad) * parseFloat(precio);
        var total_format = custom_number_format(total, '2',);

        $('#sub-' + i).html(moneda + total_format);
        $('#subt-' + i).val(total.toFixed(2));

        let suma = 0;
        $('.total').each(function() {
            suma += parseFloat($(this).val());
        });
        var suma_format = custom_number_format(suma, '2',);
        $('#total').html(moneda + suma_format);
        $('#ttl').val(suma.toFixed(2));
        var count_elements = $('.total').length;
        if (count_elements === 0)
            $('#confirm').attr("disabled", true);

    }

    function disponible(i) {
        if ($('#available-'+i).prop('checked')) {
            let amount = $('#amount_request-'+i).val();
            $('#price_buy-' + i).removeAttr('readonly');
            $('#amount-' + i).removeAttr('readonly');
            $('#amount-' + i).val(amount);
            $('#available-'+i).val(1);
        }
        else {
            $('#amount-' + i).val(0);
            $('#amount-' + i).attr('readonly', 'true');
            $('#price_buy-' + i).attr('readonly', 'true');
            $('#price_buy-' + i).val('');
            $('#available-'+i).val(0);
        }
        sum(i);
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

</body>

</html>