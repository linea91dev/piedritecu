<?php  
	$sale = $this->db->get_where('sales',array('code'=>$code))->row();
	$sale_id = $sale->sales_id;
    $moneda = $this->crud_model->get_info("moneda");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-toolbar">
                        <a href="<?php echo base_url().'admin/';?><?php if($sale->estado == 1){ echo 'creditos'; }elseif($sale->estado == 2){ echo 'ventas' ; }elseif($sale->estado == 3){echo 'anulaciones';}elseif($sale->estado == 4){echo 'cambios';}?>"
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
                            </span> Ir a <?php if($sale->estado == 1){ echo 'créditos'; }elseif($sale->estado == 2){ echo 'ventas' ; }elseif($sale->estado == 3){echo 'anulaciones';}elseif($sale->estado == 4){echo 'cambios';}?>
                        </a>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-xxl-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <form class="form" action="<?php echo base_url();?>admin/detalles_venta/"
                                        method="POST" enctype="multipart/form-data">
                                        <br>
                                        <label>Ingrese el código de orden:</label>
                                        <?php if($sale_id == "" && $code != ""):?>
                                        <div class="alert alert-danger" role="alert">
                                            El código ingresado no es válido o no existe.
                                        </div>
                                        <?php endif;?>
                                        <div class="input-group">
                                            <input type="text" name="code" class="form-control" placeholder=""
                                                aria-describedby="basic-addon2" value="<?php echo $sale->code;?>">
                                            <div class="input-group-append">
                                                <span class="input-group-text" style="padding: 5px;">
                                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                            height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                                fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24" />
                                                                <path
                                                                    d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
                                                                    fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                                <path
                                                                    d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
                                                                    fill="#000000" fill-rule="nonzero" />
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <br>
                                        <div class="form-group">
                                            <button type="submit" style='float: right;'
                                                class="btn btn-primary font-weight-bold">Buscar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <input type="hidden" name="sale_code" value="<?php echo $code?>">
            <?php if($sale_id != ""): ?>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <span><b>Forma de pago:</b></span>
                            <div class="border-bottom"></div><br>
                        </div>
                        <div class="col-lg-12 col-xxl-12">
                            <div class="form-group">
                                <label>Método:</label>
                                <select class="form-control" disabled>
                                    <option <?php echo $sale->sales_id == "Efectivo" ? "selected" :""; ?>>Efectivo
                                    </option>
                                    <option <?php echo $sale->sales_id == "Tarjeta" ? "selected" :""; ?>>Tarjeta de
                                        crédito/débito</option>
                                    <option <?php echo $sale->sales_id == "Transferencia" ? "selected" :""; ?>>
                                        Transferencia</option>
                                    <option <?php echo $sale->sales_id == "Cheque" ? "selected" :""; ?>>Cheque</option>
                                    <option <?php echo $sale->sales_id == "Deposito" ? "selected" :""; ?>>Depósito
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xxl-12">
                            <div class="form-group">
                                <label>Total:</label>
                                <input type="text" value="<?php echo $moneda.$sale->total?>" disabled
                                    class="form-control" />
                            </div>
                        </div>
                        <?php if ($sale->estado == "3" || $sale->estado == "4"):?>
                        <div class="col-lg-12 col-xxl-12">
                            <div class="form-group">
                                <label>Motivo
                                    <?php if($sale->estado == "3") echo 'de la anulación'; if ($sale->estado == "4") echo 'del cambio';?></label>
                                <textarea class="form-control" disabled="true"
                                    name="reason"><?php echo $sale->reason; ?></textarea>
                            </div>
                        </div>
                        <?php endif; if($sale->credito == "1" || $sale->estado == "1"): ?>
                        <div class="col-lg-12 col-xxl-12">
                            <a href="<?php echo base_url().'admin/detalles_credito/'.$sale->code;?>"
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
                                </span> Historial de pagos
                            </a>
                        </div>
                        <?php endif;?>
                        <!--
                        <div class="col-lg-6 col-xxl-6">
                            <div class="form-group">
                                <label>Pago con:</label>
                                <input type="number" value="Q. <?php echo $sale->total?>" disabled step="0.01" class="form-control" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-xxl-6">
                            <div class="form-group">
                                <label>Cambio:</label>
                                <input type="text" disabled value="Q. <?php echo $sale->total?>" class="form-control" />
                            </div>
                        </div>
						-->
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php $mayorista = $sale->my;?>
        <div class="col-sm-8">
            <?php if($sale_id != ""): ?>
            <div class="card">
                <div class="card-body">
                    <h5>Resumen de la orden:</h5>
                    <div class="border-bottom"></div><br>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Vendedor:</b></label><br>
                                <?php echo $this->crud_model->getName('admin',$sale->responsable) ;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Cliente:</b></label><br>
                                <?php if($sale->client_id == 0):?>
                                <?php echo $sale->name;?>
                                <?php elseif($sale->client_id > 0): $cliente = $this->db->get_where('client', array('client_id' => $sale->client_id));?>
                                    <?php if ($cliente->num_rows() > 0):?>
                                    <?php echo $this->crud_model->getName('client',$sale->client_id);?>
                                    <?php else: ?>
                                    <span class="label label-lg font-weight-bold label-light-danger label-inline">Eliminado</span>
                                    <?php endif;?>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>NIT:</b></label>
                                <br><?php echo $sale->nit;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Celular:</b></label>
                                <br><?php echo $sale->phone;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Dirección de facturación:</b></label><br>
                                <?php echo $sale->address;?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label><b>Tipo cliente:</b></label><br>
                                <?php if($sale->my==1){echo "Mayorista";}elseif($sale->my==3){echo "Farmacia";}else{echo "Publico";};?>
                            </div>
                        </div>

                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Entrega:</b></label><br>
                                <?php if($sale->shipping_cost > 0) {echo $moneda.number_format($sale->shipping_cost,2,'.',',');} ?><br>
                                <?php echo $sale->delivery;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Detalles:</b></label><br>
                                <?php echo $sale->details;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Estado:</b></label><br>
                                <span
                                    class="label label-lg font-weight-bold label-light-<?php if($sale->estado == 1){ echo 'warning';}elseif($sale->estado == 2){echo 'success';}elseif($sale->estado == 3){echo 'danger';}elseif($sale->estado == 4){echo 'info';}?> label-inline">
                                    <?php if($sale->estado == 1){ echo 'Crédito'; }elseif($sale->estado == 2){ echo 'Completados' ; }elseif($sale->estado == 3){echo 'Anulado';}elseif($sale->estado == 4){echo 'Cambio';}elseif($sale->estado == 5){echo 'Aplicado/Guardado';}elseif($sale->estado == 6){echo 'Error FEL';} ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Fecha:</b></label><br>
                                <span><?php echo $sale->date;?> | <?php echo $sale->time;?></span>
                            </div>
                        </div>
                         <?php if($sale->estado == 2 && $sale->FEL == 0  ): ?>
                        <div class="col-sm-6">
                            <div class="form-group">
                               <a id="reemitir" href="<?php echo base_url();?>admin/FEL/reemitir/<?php echo $sale->code; ?>"
                                class="btn btn-light-success font-weight-bolder" style='float: right;'>
                                Emitir FEL venta
                            </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($sale->estado == 6  ): ?>
                        <div class="col-sm-6">
                            <div class="form-group">
                               <a id="reemitir" href="<?php echo base_url();?>admin/FEL/reemitir/<?php echo $sale->code; ?>"
                                class="btn btn-light-success font-weight-bolder" style='float: right;'>
                                Reemitir FEL venta
                            </a>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <br>
            <div class="card ">
                <div class="card-body">
                    <h3 class="card-label text-info">Descripción</h3>
                    <div class="table-responsive">
                        <table class="table table-padded">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">P/U</th>
                                    <th class="text-center">IVA(%)</th>
                                    <th class="text-center">Descuento(%)</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
									$products = json_decode($sale->products,true); 
									foreach($products as $product):
									$pr = $this->db->get_where('products',array('products_id'=>$product['product']))->row();
								?>
                                <tr>
                                    <td class="cell-with-media" style='max-width: 225px;'>
                                        <?php if($pr->img != ""):?>
                                        <img class="h-75 align-self-end" width="50"
                                            src="<?php echo base_url().'uploads/productos/'.$pr->img ?>" alt="photo">
                                        <?php else: ?>
                                        <img class="h-75 align-self-end" width="70"
                                            src="<?php echo base_url().'uploads/productos/default_product.png' ?>"
                                            alt="photo">
                                        <?php endif; ?>
                                        <span> <?php echo $pr->name?></span>
                                    </td>
                                    <td class="text-center"><?php echo $product['amount']?></td>
                                    <td class="text-center bolder">
                                        <?php if($mayorista == 1):?>
                                            <span class="text-success"><?php echo $moneda; if($product['price_my'] > 0) echo number_format($product['price_my'],2,'.',','); else echo '0.00';?></span></td>
                                        <?php elseif($mayorista == 3):?>
                                            <span class="text-success"><?php echo $moneda; if($product['price_farma'] > 0) echo number_format($product['price_farma'],2,'.',','); else echo '0.00';?></span></td>
                                        <?php else:?>
                                            <span class="text-success"><?php echo $moneda; if($product['price'] > 0) echo number_format($product['price'],2,'.',','); else echo '0.00';?></span></td>
                                        <?php endif;?>
                                        
                                        
                                    <td class="text-center">
                                        <?php if($pr->iva == 1){?>
                                            <?php if($mayorista == 1):?>
                                                <span class="text-success"><?php echo $moneda; if($product['price_my'] > 0) echo number_format(($product['price_my']/1.12)*0.12,2,'.',','); else echo '0.00';?></span></td>
                                            <?php elseif($mayorista == 3):?>
                                                <span class="text-success"><?php echo $moneda; if($product['price_farma'] > 0) echo number_format(($product['price_farma']/1.12)*0.12,2,'.',','); else echo '0.00';?></span></td>
                                            <?php else:?>
                                                <span class="text-success"><?php echo $moneda; if($product['price'] > 0) echo number_format(($product['price']/1.12)*0.12,2,'.',','); else echo '0.00';?></span></td>
                                            <?php endif;?>
                                        <?php }else{?>
                                            <?php if($mayorista == 1):?>
                                                <span class="text-success"><?php echo $moneda;  echo '0.00';?></span></td>
                                            <?php elseif($mayorista == 3):?>
                                                <span class="text-success"><?php echo $moneda;  echo '0.00';?></span></td>
                                            <?php else:?>
                                            <span class="text-success"><?php echo $moneda;  echo '0.00';?></span></td>
                                            <?php endif;?>
                                        <?php } //aqui me quede?>
                                    </td>    
                                    <td class="text-center">
                                        <?php echo ($product['discount'] == "") ? number_format(0,0,'.',',').'%':number_format($product['discount'],0,'.',',').'%' ?>
                                    </td>
                                    <td class="text-right bolder">
                                        <?php if($mayorista == 1):?>
                                            <span class="text-success"><?php echo $moneda; if($product['sub_my'] > 0) echo number_format($product['sub_my'],2,'.',','); else echo '0.00';?></span>
                                        <?php elseif($mayorista == 3):?>
                                            <span class="text-success"><?php echo $moneda; if($product['sub_farma'] > 0) echo number_format($product['sub_farma'],2,'.',','); else echo '0.00';?></span>
                                        <?php else:?>
                                            <span class="text-success"><?php echo $moneda; if($product['sub'] > 0) echo number_format($product['sub'],2,'.',','); else echo '0.00';?></span>    
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
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
                            <div class="font-weight-boldest font-size-h5">TOTAL</div>
                            <div class="text-right d-flex flex-column">
                                <span class="font-weight-boldest font-size-h3 line-height-sm"
                                    id="total"><?php echo $moneda.' '.number_format($sale->total,2,'.',',')?></span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php if($sale->estado == 1 || $sale->estado == 1 ):?>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?php if($user_type == 1 || $permisos['crear_cambios'] == 1):?>
                            <a href="<?php echo base_url();?>admin/anulacion/<?php echo $sale->code; ?>"
                                class="btn btn-light-danger font-weight-bolder" style='float: right;'>
                                Generar anulación
                            </a>
                            <?php endif; if($user_type == 1 || $permisos['crear_anulaciones'] == 1):?>
                            <a href="<?php echo base_url();?>admin/nuevo_cambio/<?php echo $sale->code; ?>"
                                class="btn btn-light-warning font-weight-bolder mr-2 " style='float: right;'>
                                Generar cambio
                            </a>
                            <?php endif;?>
                        </div>
                    </div>
                    <?php endif;
                    if($sale->estado == 55):?>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <?php if($user_type == 1 || $permisos['crear_ventas'] == 1):?>
                            <a href="<?php echo base_url();?>admin/ventas/generar/<?php echo $sale->code; ?>"
                                class="btn btn-light-success font-weight-bolder" style='float: right;'>
                                Generar venta
                            </a>
                            <?php endif;?>
                        </div>
                    </div>
                    <?php endif;?>
                </div>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>