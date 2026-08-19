<?php $moneda = $this->crud_model->get_info("moneda"); $data = $this->db->get_where('quotes', array('quotes_id'=>$ID))->result_array(); foreach ($data as $row) :

$mayorista = false;
    $type = $this->db->get_where('client', array('client_id'=>$row['client_id']))->row()->type;
           
    if($type == 1)
    {
        $mayorista = true;    
    }
    else
    {
        $mayorista = false;    
    }

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">
                            Detalles de la cotización
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="<?php echo  base_url().'admin/cotizaciones/' ;?>"
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

                    <div class="row">
                        <div class="col-lg-12 col-xxl-12">
                            <span>Código de cotización: <b><?php echo $row['code'];?></b> <span
                                    style="float:right"><b>Fecha de
                                        cotización:</b> <?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d-m-Y", strtotime( $row['date_start'] ));				
                                        $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); 
                                        echo $Mes_Anyo;?>
                                </span></span>
                            <br><br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><b>Cliente:</b></label>
                                        <input type="text" class='form-control'
                                            value='<?php echo $this->crud_model->getName('client', $row['client_id']) ;?>'
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><b>NIT Cliente:</b></label>
                                        <input type="text" class='form-control'
                                            value='<?php echo $this->db->get_where('client',array('client_id'=>$row['client_id']))->row()->nit ;?>'
                                            readonly>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label><b>Válido hasta:</b></label>
                                        <input type="date" class="form-control" readonly name='date_end'
                                            value='<?php echo $row['date_end'];?>' />
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label><b>Responsable:</b></label>
                                        <input type="text" class='form-control' readonly
                                            value='<?php echo $this->crud_model->getName('admin', $row['responsable']); ?>'>
                                    </div>
                                </div>
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
                    <h3 class="card-label text-info text-center"> PRODUCTOS COTIZADOS</h3>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-padded">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio unitario</th>
                                    <th>Descuento</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i=0; $i < $row['num_products'] ; $i++) : 
                                    if ($row['products'] != "" || $row['products'] != null) {
                                        $pro = json_decode($row['products'],true);
                                    } else {
                                        $pro = array();
                                    } ;?>
                                <tr>
                                    <td>
                                        <?php echo $this->crud_model->getName('products', $pro[$i]['product']);?>
                                    </td>
                                    <td><b><?php echo $pro[$i]['amount'];?></b></td>
                                    <?php if($mayorista == true):?>
                                    <td><b><?php echo $moneda.number_format($pro[$i]['price_my'],2,'.',',');?></b></td>
                                    <?php else:?>
                                    <td><b><?php echo $moneda.number_format($pro[$i]['price'],2,'.',',');?></b></td>
                                    <?php endif;?>
                                    <td><b><?php echo $pro[$i]['discount'].'%';?></b></td>
                                    <?php if($mayorista == true):?>
                                    <td>
                                        <span class="text-success"><?php echo $moneda.number_format($pro[$i]['sub_my'],2,'.',',') ;?></span>
                                    </td>
                                    <?php else:?>
                                    <td>
                                        <span class="text-success"><?php echo $moneda.number_format($pro[$i]['sub'],2,'.',',') ;?></span>
                                    </td>
                                    
                                    <?php endif;?>
                                </tr>
                                <?php endfor; ?>
                            </tbody>
                        </table>
                    </div>
                    <hr>
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
                            <div class="font-weight-boldest font-size-h5">TOTAL COTIZADO</div>
                            <div class="text-right d-flex flex-column">
                                <span class="font-weight-boldest font-size-h3 line-height-sm"
                                    id='total'><?php echo $moneda.number_format($row['total'],2,'.',',') ;?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php endforeach;?>