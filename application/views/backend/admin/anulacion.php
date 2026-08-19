<?php  
	$sale = $this->db->get_where('sales',array('code'=>$code,'status'=>1))->row();
	$sale_id = $sale->sales_id;
    $moneda = $this->crud_model->get_info("moneda");
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 col-xxl-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <br>
                                    <form method="post" action="<?php echo base_url(); ?>admin/anulacion/">
                                        <label>Ingrese el código de orden:</label>
                                        <?php if($sale_id == "" && $code != ""):?>
                                        <div class="alert alert-danger" role="alert">
                                            El código ingresado no es válido o no existe.
                                        </div>
                                        <?php endif;?>
                                        <div class="input-group">
                                            <input type="text" name="code" class="form-control" placeholder=""
                                                aria-describedby="basic-addon2" value="<?php echo $sale->code;?>"
                                                required>
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
                                        <option <?php echo $sale->sales_id == "Cheque" ? "selected" :""; ?>>Cheque
                                        </option>
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
                            <div class="col-lg-12 col-xxl-12">
                                <div class="form-group">
                                    <label>Motivo de la anulación</label>
                                    <textarea class="form-control" required="" name="reason"></textarea>
                                </div>
                                <span>* La anulación también revertirá los cobros realizados al cliente.</span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
        </div>

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
                                <label><b>Cliente:</b></label>
                                <br>
                                <?php echo $sale->client_id != 0 ? $this->crud_model->getName('client',$sale->client_id): $sale->name; ?>
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
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Dirección de entrega:</b></label><br>
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
                                    class="label label-lg font-weight-bold label-light-success label-inline">Completada</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label><b>Fecha:</b></label><br>
                                <span><?php echo $sale->date;?> | <?php echo $sale->time;?></span>
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
                                    <th class="text-center">Descuento</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $mayorista = $this->db->get_where('client', array('client_id' => $sale->client_id))->row()->type;?>
                                <?php 
									$products = json_decode($sale->products,true); 
									foreach($products as $product):
									$pr = $this->db->get_where('products',array('products_id'=>$product['product']))->row();
								?>
                                <tr>
                                    <td class="cell-with-media">
                                        <img class="h-75 align-self-end" width="50" <?php if($pr->img != ""): ?>
                                            src="<?php echo base_url().'uploads/productos/'.$pr->img ?>" <?php else: ?>
                                            src="<?php echo base_url().'uploads/productos/default_product.png';?>"
                                            <?php endif; ?> alt="photo"><span> <?php echo $pr->name?></span>
                                    </td>
                                    <td class="text-center"><?php echo $product['amount']?></td>
                                    <td class="text-center bolder"> 
                                     <?php if($mayorista == 1):?>
                                            <span class="text-success"><?php echo $moneda; if($product['price_my'] > 0) echo number_format($product['price_my'],2,'.',','); else echo '0.00';?></span></td>
                                        <?php else:?>
                                            <span class="text-success"><?php echo $moneda; if($product['price'] > 0) echo number_format($product['price'],2,'.',','); else echo '0.00';?></span></td>
                                        <?php endif;?>
                                    <td class="text-center">
                                        <?php echo $product['discount'] == ""? 0:$product['discount'] ?>%
                                    </td>
                                    <td class="text-right bolder">
                                         <?php if($mayorista == 1):?>
                                            <span class="text-success"><?php echo $moneda; if($product['sub_my'] > 0) echo number_format($product['price_my'],2,'.',','); else echo '0.00';?></span></td>
                                        <?php else:?>
                                            <span class="text-success"><?php echo $moneda; if($product['sub'] > 0) echo number_format($product['price'],2,'.',','); else echo '0.00';?></span></td>
                                        <?php endif;?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-light-danger font-weight-bolder">
                        Confirmar anulación
                    </button>
                    </form>
                </div>
            </div>
            <?php endif;?>
        </div>
    </div>
</div>