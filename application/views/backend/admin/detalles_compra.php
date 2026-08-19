<?php  
	$shopping = $this->db->get_where('shopping',array('code'=>$code))->row();
	$shopping_id = $shopping->shopping_id;
    $moneda = $this->crud_model->get_info("moneda");
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-toolbar">
                        <a href="<?php echo  base_url().'admin/compras/' ;?>"
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
                    <div class="row">
                        <div class="col-lg-12 col-xxl-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <form class="form" action="<?php echo base_url();?>admin/detalles_compra/"
                                        method="POST" enctype="multipart/form-data">
                                        <br>
                                        <label>Código de compra:</label>
                                        <?php if($shopping_id == "" && $code != ""):?>
                                        <div class="alert alert-danger" role="alert">
                                            El código ingresado no es valido o no existe.
                                        </div>
                                        <?php endif;?>
                                        <div class="input-group">
                                            <input type="text" name="code" class="form-control" placeholder=""
                                                aria-describedby="basic-addon2" value="<?php echo $shopping->code;?>">
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
            <form method="post" action="<?php echo base_url(); ?>admin/anulacion/confirm">
                <input type="hidden" name="sale_code" value="<?php echo $code?>">
                <?php if($shopping_id != ""): ?>
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
                                        <option <?php echo $shopping->sales_id == "Efectivo" ? "selected" :""; ?>>
                                            Efectivo</option>
                                        <option <?php echo $shopping->sales_id == "Tarjeta" ? "selected" :""; ?>>Tarjeta
                                            de crédito/débito</option>
                                        <option <?php echo $shopping->sales_id == "Transferencia" ? "selected" :""; ?>>
                                            Transferencia</option>
                                        <option <?php echo $shopping->sales_id == "Cheque" ? "selected" :""; ?>>Cheque
                                        </option>
                                        <option <?php echo $shopping->sales_id == "Deposito" ? "selected" :""; ?>>
                                            Depósito</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12 col-xxl-12">
                                <div class="form-group">
                                    <label>Total:</label>
                                    <input type="text" value="<?php echo $moneda.$shopping->total?>" disabled
                                        class="form-control" />
                                </div>
                            </div>
                            <!--
                        <div class="col-lg-6 col-xxl-6">
                            <div class="form-group">
                                <label>Pago con:</label>
                                <input type="number" value="Q. <?php echo $shopping->total?>" disabled step="0.01" class="form-control" />
                            </div>
                        </div>
                        <div class="col-lg-6 col-xxl-6">
                            <div class="form-group">
                                <label>Cambio:</label>
                                <input type="text" disabled value="Q. <?php echo $shopping->total?>" class="form-control" />
                            </div>
                        </div>
						
                            <div class="col-lg-12 col-xxl-12">
                                <div class="form-group">
                                    <label>Motivo de la anulación</label>
                                    <textarea class="form-control" required="" name="reason"></textarea>
                                </div>
                                <span>* La anulación también revertirá los cobros realizados al cliente.</span>
                            </div>
                            -->
                        </div>
                    </div>
                </div>
                <?php endif; ?>
        </div>

        <div class="col-sm-8">
            <?php if($shopping_id != ""): ?>
            <div class="card">
                <div class="card-body">
                    <h5>Resumen de la orden:</h5>
                    <div class="border-bottom"></div><br>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Fecha:</b></label><br>
                                <span><?php echo $shopping->date;?> | <?php echo $shopping->time;?></span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Responsable:</b></label><br>
                                <?php echo $this->crud_model->getName('admin',$shopping->responsable) ;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>factura:</b></label><br>
                                <?php echo $shopping->factura ;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Destino:</b></label><br>
                                <?php echo ($shopping->destiny == 0)? "Bodega":$this->db->get_where('branch',array('branch_id'=>$shopping->destiny))->row()->name;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Proveedor:</b></label><br>
                                <?php echo $this->db->get_where('provider',array('provider_id'=>$shopping->provider))->row()->name; ?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Estado:</b></label><br>
                                <span
                                    class="badge badge-<?php if($shopping->type==3){echo 'warning';}elseif($shopping->type==2){echo 'info';}elseif($shopping->type==1){echo 'success';} ?>"><?php if($shopping->type==3){echo 'Solicitud de compra ';}elseif($shopping->type==2){echo 'Orden de compra';}elseif($shopping->type==1){echo 'Completado';} ?></span>
                                <?php if($shopping->promocion == 1):?>
                                <span class="badge badge-info">Promoción</span>
                                <?php endif;?>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label><b>Detalles:</b></label><br>
                                <?php echo $shopping->details;?>
                            </div>
                        </div>
                        

                    </div>
                </div>
            </div>
            <br>
            <div class="card ">
                <div class="card-body">
                    <h3 class="card-label text-info">Productos</h3>
                    <div class="table-responsive">
                        <table class="table table-padded">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-center">P/U</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
									$products = json_decode($shopping->products,true); 
									foreach($products as $product):
									$pr = $this->db->get_where('products',array('products_id'=>$product['product']))->row();
								?>
                                <tr>
                                    <td class="cell-with-media">
                                        <img class="h-75 align-self-end" width="50"
                                            src="<?php echo ($pr->img == '') ? base_url().'uploads/productos/default_product.png' : base_url().'uploads/productos/'.$pr->img ;?>"
                                            alt="photo"><span> <?php echo $pr->name?></span>
                                    </td>
                                    <td class="text-center"><?php echo $product['amount']?></td>
                                    <td class="text-center bolder"> <span
                                            class="text-success"><?php echo $moneda.number_format($product['price_buy'],2,'.',',');?></span></td>
                                    <td class="text-right bolder"><span
                                            class="text-success"><?php echo $moneda.number_format($product['sub'],2,'.',',') ;?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="col-md-12">
                        <div
                            class="bg-primary bg-hover-state-primary rounded d-flex  justify-content-between text-white position-relative ml-auto p-7">
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
                                    id="total"><?php echo $moneda.number_format($shopping->total,2,'.',',');?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>