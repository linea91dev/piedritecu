<?php $data = $this->crud_model->get_product_details($ID); $moneda = $this->crud_model->get_info("moneda"); $product = $this->db->get_where('products',array('products_id'=> $ID))->row();
$branchid= $this->session->userdata('branch_id');?>
<div class="container-fluid">

    <div class="d-flex flex-row">
        <div class="flex-row-fluid">
            <div class="row">
                <div class="col-md-7 col-lg-12 col-xxl-7">
                    <div class="card card-custom card-stretch gutter-b">
                        <div class="card-header border-0 pt-6 mb-2">
                            <div class="card-toolbar">
                                <a href="<?php echo  base_url().'admin/inventario' ;?>"
                                    class="btn btn-light-primary font-weight-bolder">
                                    <span class="svg-icon svg-icon-md">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
                                            viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <circle fill="#000000" cx="9" cy="15" r="6" />
                                                <path
                                                    d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                                    fill="#000000" opacity="0.3" />
                                            </g>v
                                        </svg>
                                    </span> Regresar
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-15 pb-20">
                            <div class="row mb-17">
                                <div class="col-xxl-5 mb-11 mb-xxl-0">
                                    <div class="card card-custom card-stretch">
                                        <div
                                            class="card-body p-0 rounded px-10 py-15 d-flex align-items-center justify-content-center">
                                            <img src="<?php echo ($product->img!='') ? base_url().'uploads/productos/'.$product->img :base_url().'uploads/productos/default_product.png';?> "
                                                class="mw-100 w-200px" style="transform: scale(1.6);">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xxl-7 pl-xxl-11">

                                    <h2 class="font-weight-bolder text-dark mb-7" style="font-size: 32px;">
                                         <?php echo $product->name.'('.$product->code.')';?> 
                                    </h2>

                                    <div class="font-size-h2 mb-7 text-dark-50">Precio
                                        <span
                                            class="text-info font-weight-boldest ml-2"><?php echo $moneda.number_format($product->price,2,'.',',');?></span>
                                    </div>
                                    <div class="line-height-xl"><?php echo $product->description;?></div>
                                </div>
                            </div>
                            <div class="row mb-6">
                              <div class="col-6 col-md-4">
                                    <div class="mb-8 d-flex flex-column">
                                        <span class="text-dark font-weight-bold mb-4">Inventario</span>
                                        <?php if($product->presentation == 'Caja'): ?>
                                            <span class="text-muted font-weight-bolder font-size-lg"><?php  $stock_inventory = $this->crud_model->get_stock($product->id_prod_matriz, $this->session->userdata('branch_id')); echo $stock_inventory/$product->cnt_prod_matriz; ?></span>
                                        <?php endif;?>
                                        <?php if($product->presentation != 'Caja'): ?>
                                            <span class="text-muted font-weight-bolder font-size-lg"><?php echo $stock_inventory = $this->crud_model->get_stock($ID, $this->session->userdata('branch_id'));?></span>
                                        <?php endif;?>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4">
                                    <div class="mb-8 d-flex flex-column">
                                        <span class="text-dark font-weight-bold mb-4">Bodega</span>
                                            <?php if($product->presentation == 'Caja'): ?>
                                            <span class="text-muted font-weight-bolder font-size-lg"><?php  $stock_inventory = $this->crud_model->get_stock($product->id_prod_matriz, 0); echo $stock_inventory/$product->cnt_prod_matriz; ?></span>
                                        <?php endif;?>
                                        <?php if($product->presentation != 'Caja'): ?>
                                            <span class="text-muted font-weight-bolder font-size-lg"><?php echo $stock_inventory = $this->crud_model->get_stock($ID, 0);?></span>
                                        <?php endif;?>
                                    </div>
                                </div>

                                <div class="col-6 col-md-4">
                                    <div class="mb-8 d-flex flex-column">
                                        <span class="text-dark font-weight-bold mb-4">Categoría</span>
                                        <span
                                            class="text-muted font-weight-bolder font-size-lg"><?php echo $this->db->get_where('categories',array('category_id'=> $product->category))->row()->name; ?></span>
                                    </div>
                                </div>
                                <?php if($stock_bodega > 0):?>
                                <div class="col-6 col-md-6">
                                    <div class="mb-8 d-flex flex-column">
                                        <span class="text-dark font-weight-bold mb-4">Ubicación</span>
                                        <span
                                            class="text-muted font-weight-bolder font-size-lg"><?php echo $this->db->get_where('product_details', array('products_id'=> $ID))->row()->location;?></span>
                                    </div>
                                </div>
                                <?php endif;?>
                            </div>
                            <?php if($user_type == 1 || $permisos['editar_productos'] == 1):?>
                            <div class="card-toolbar">
                                <a href="javascript:void(0)" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/producto_devolucion/<?php echo $ID;?>/')"
                                    class="btn btn-light-warning font-weight-bolder">
                                    <span class="svg-icon svg-icon-2x">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24"/>
                                                <path d="M8.42034438,20 L21,20 C22.1045695,20 23,19.1045695 23,18 L23,6 C23,4.8954305 22.1045695,4 21,4 L8.42034438,4 C8.15668432,4 7.90369297,4.10412727 7.71642146,4.28972363 L0.653241109,11.2897236 C0.260966303,11.6784895 0.25812177,12.3116481 0.646887666,12.7039229 C0.648995955,12.7060502 0.651113791,12.7081681 0.653241109,12.7102764 L7.71642146,19.7102764 C7.90369297,19.8958727 8.15668432,20 8.42034438,20 Z" fill="#000000" opacity="0.3"/>
                                                <path d="M12.5857864,12 L11.1715729,10.5857864 C10.7810486,10.1952621 10.7810486,9.56209717 11.1715729,9.17157288 C11.5620972,8.78104858 12.1952621,8.78104858 12.5857864,9.17157288 L14,10.5857864 L15.4142136,9.17157288 C15.8047379,8.78104858 16.4379028,8.78104858 16.8284271,9.17157288 C17.2189514,9.56209717 17.2189514,10.1952621 16.8284271,10.5857864 L15.4142136,12 L16.8284271,13.4142136 C17.2189514,13.8047379 17.2189514,14.4379028 16.8284271,14.8284271 C16.4379028,15.2189514 15.8047379,15.2189514 15.4142136,14.8284271 L14,13.4142136 L12.5857864,14.8284271 C12.1952621,15.2189514 11.5620972,15.2189514 11.1715729,14.8284271 C10.7810486,14.4379028 10.7810486,13.8047379 11.1715729,13.4142136 L12.5857864,12 Z" fill="#000000"/>
                                            </g>
                                        </svg>
                                    </span>
                                    Devolución
                                </a>
                                
                                <a href="javascript:void(0)" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/producto_perdida/<?php echo $ID;?>/')"
                                    class="btn btn-light-danger font-weight-bolder">
                                    <span class="svg-icon svg-icon-2x">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                <path d="M10.5857864,13 L9.17157288,11.5857864 C8.78104858,11.1952621 8.78104858,10.5620972 9.17157288,10.1715729 C9.56209717,9.78104858 10.1952621,9.78104858 10.5857864,10.1715729 L12,11.5857864 L13.4142136,10.1715729 C13.8047379,9.78104858 14.4379028,9.78104858 14.8284271,10.1715729 C15.2189514,10.5620972 15.2189514,11.1952621 14.8284271,11.5857864 L13.4142136,13 L14.8284271,14.4142136 C15.2189514,14.8047379 15.2189514,15.4379028 14.8284271,15.8284271 C14.4379028,16.2189514 13.8047379,16.2189514 13.4142136,15.8284271 L12,14.4142136 L10.5857864,15.8284271 C10.1952621,16.2189514 9.56209717,16.2189514 9.17157288,15.8284271 C8.78104858,15.4379028 8.78104858,14.8047379 9.17157288,14.4142136 L10.5857864,13 Z" fill="#000000"/>
                                            </g>
                                        </svg>
                                    </span>
                                    Pérdida
                                </a>
                            </div>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <div class="col-md-5 col-lg-12 col-xxl-5">
                    <?php $ventas = $this->db->order_by('date','DECS')->limit(3)->get_where('product_details',array('products_id'=>$ID ,'branch_id'=>$branchid, 'type'=>0,'status'=>1, 'description'=>'Venta' ));?>
                    <?php //$ventas = $this->db->query('SELECT * FROM `product_details` WHERE `description` LIKE "compra" and products_id = '.$ID.' and (branch_id = 0 OR branch_id ='.$branchid.' ) and status =1 ORDER BY `description` DESC');?>
                    
                    <div class="card card-custom card-stretch card-stretch-half gutter-b" style="height: auto;">
                        <div class="card-header border-0 pt-6 mb-2">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bold font-size-h4 text-dark-75 mb-3">Ventas</span>
                                <span class="text-muted font-weight-bold font-size-sm">Total de Ventas:
                                    <?php echo count($ventas->result_array());?></span>
                            </h3>
                            <?php if($user_type == 1 || $permisos['graficas_ventas'] == 1):?>
                            <div class="card-toolbar">
                                <a href="<?php echo base_url().'admin/reportes/ventas/';?>"
                                    class="btn btn-light-success btn-sm font-weight-bolder font-size-sm py-3 px-6">Reporte
                                    de
                                    Ventas</a>
                            </div>
                            <?php endif;?>
                        </div>
                        <div class="card-body pt-2">
                            <?php if($ventas->num_rows() >0):?>

                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <?php foreach ($ventas->result_array() as $vow):?>
                                        <tr>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                                <div class="symbol symbol-40 symbol-light-success">
                                                    <span class="symbol-label">
                                                        <span class="svg-icon svg-icon-lg svg-icon-success">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                height="24px" viewBox="0 0 24 24" version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none"
                                                                    fill-rule="evenodd">
                                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                                    <path
                                                                        d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z"
                                                                        fill="#000000" fill-rule="nonzero"
                                                                        opacity="0.3"></path>
                                                                    <path
                                                                        d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z"
                                                                        fill="#000000"></path>
                                                                </g>
                                                            </svg>
                                                        </span>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="align-middle pb-6">
                                                <div class="font-size-lg font-weight-bolder text-dark-75 mb-1">
                                                    Venta</div>
                                                <div class="font-weight-bold text-muted">
                                                    <?php $date = date_create($vow['date']); echo date_format($date, 'd/m/Y');?>
                                                </div>

                                            </td>
                                            <td class="font-weight-bold text-muted align-middle text-right pb-6">
                                                <span class="svg-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                                                                fill="#000000">
                                                                <rect x="0" y="7" width="16" height="2" rx="1"></rect>
                                                                <rect opacity="0.3"
                                                                    transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)"
                                                                    x="0" y="7" width="16" height="2" rx="1"></rect>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </span>
                                                <span
                                                    class="text-success font-size-h5 font-weight-bolder ml-1"><?php echo $vow['amount'];?></span>
                                            </td>
                                            <td class="text-right align-middle pb-6">
                                                <div class="font-weight-bold text-muted mb-1">Precio</div>
                                                <div class="font-size-lg font-weight-bolder text-dark-75">
                                                    <?php echo $moneda.number_format($vow['price'],2,'.',',');?></div>
                                            </td>
                                        </tr>

                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <center>

                                <h3>Sin datos</h3><br>
                                <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:35%">
                            </center>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php 
                     $compras = $this->db->query('SELECT * FROM `product_details` WHERE `description` LIKE "compra" and products_id = '.$ID.' and (branch_id = 0 OR branch_id ='.$branchid.' ) and status =1 ORDER BY `description` DESC');?>
                    <div class="card card-custom card-stretch card-stretch-half gutter-b" style="height: auto;">
                        <div class="card-header border-0 pt-6 mb-2">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bold font-size-h4 text-dark-75 mb-3">Compras
                                </span>
                                <span class="text-muted font-weight-bold font-size-sm">Total de compras:
                                    <?php echo count($compras->result_array());?></span>
                            </h3>                            
                        </div>
                        <div class="card-body pt-2">
                            <?php if($compras->num_rows() >0):?>
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <?php $n = 1; foreach ($compras->result_array() as $cow):?>
                                        <tr>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                                <div class="symbol symbol-40 symbol-light-info">
                                                    <span class="symbol-label">
                                                        <span class="svg-icon svg-icon-lg svg-icon-info">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                height="24px" viewBox="0 0 24 24" version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none"
                                                                    fill-rule="evenodd">
                                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                                    <path
                                                                        d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z"
                                                                        fill="#000000" fill-rule="nonzero"
                                                                        opacity="0.3"></path>
                                                                    <path
                                                                        d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z"
                                                                        fill="#000000"></path>
                                                                </g>
                                                            </svg>
                                                        </span>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="align-middle pb-6">
                                                <div class="font-size-lg font-weight-bolder text-dark-75 mb-1">
                                                    Compra </div>
                                                <div class="font-weight-bold text-muted">
                                                    <?php echo $this->crud_model->get_shopping_info_provider($cow['activity_ref']); ?>
                                                </div>
                                                <div class="font-weight-bold text-muted">
                                                    <?php echo $cow['id']; $date = date_create($cow['date']); echo date_format($date, 'd/m/Y');?>
                                                </div>
                                            </td>
                                            <td class="font-weight-bold text-muted align-middle text-right pb-6">
                                                <span class="svg-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                                                                fill="#000000">
                                                                <rect x="0" y="7" width="16" height="2" rx="1"></rect>
                                                                <rect opacity="0.3"
                                                                    transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)"
                                                                    x="0" y="7" width="16" height="2" rx="1"></rect>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </span>
                                                <span
                                                    class="text-info font-size-h5 font-weight-bolder ml-1"><?php echo $cow['amount'];?></span>
                                            </td>
                                            <td class="text-right align-middle pb-6">
                                                <div class="font-weight-bold text-muted mb-1">Precio</div>
                                                <div class="font-size-lg font-weight-bolder text-dark-75">
                                                    <?php echo $moneda.number_format($cow['price'],2,'.',',');?></div>

                                            </td>
                                            <!-- <td class="text-right align-middle pb-6">

                                                <a href="<?php echo base_url().'admin/producto_lote_edit/'.$cow['product_details_id'];?>"
                                                    data-toggle="tooltip" data-original-title="Editar producto"
                                                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <path opacity="0.3"
                                                                d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                                fill="#8950FC" />
                                                            <path
                                                                d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                                fill="#8950FC" />
                                                        </svg>
                                                    </span>
                                                </a>

                                            </td> -->
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <center>
                                <h3>Sin datos</h3><br>
                                <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:35%">
                            </center>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12 ">
                    <div class="card card-custom card-stretch card-stretch-half gutter-b" style="height: auto;">
                        <div class="card-header border-0 pt-6 mb-2">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bold font-size-h4 text-dark-75 mb-3">Detalles
                                </span>
                            </h3>
                        </div>
                        <div class="card-body pt-2">
                            <div class="table-responsive">
                                <?php
                                $date_now = date('Y-m-d');
                                $date_past = date('Y-m-d', strtotime('-30 days'));
                                
                                $bandera = 0;
                                
                                // Determinar columna a usar según presentación
                                $column = 'products_id';
                                if ($product->presentation == 'Caja') {
                                    $column = 'products_id_2';
                                    $bandera = 2;
                                } else {
                                    $bandera = 1;
                                }
                                
                                // Preparar la consulta con bindings para evitar inyección SQL
                                $sql = "SELECT * FROM `product_details` 
                                        WHERE $column = ? 
                                        AND (branch_id = ? OR branch_id = 0) 
                                        AND status = 1 
                                        AND date >= ? 
                                        AND date <= ?";
                                
                                $details = $this->db->query($sql, [$ID, $branchid, $date_past, $date_now])->result_array();
                                ?>
                                <table class="table table-bordered dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Codigo</th>
                                            <th>Fecha</th>
                                            <th>Movimiento</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($details as $row):?>
                                        <tr>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                             <?php echo $row['product_details_id'];?>
                                            </td>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                             <?php if($row['type']==1){echo 'Inicial';
                                             }else{echo $row['activity_ref'];}
                                             ?>
                                            </td>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                             <?php echo $row['date'];?>
                                            </td>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                             <?php if($row['type']==1){echo 'Inicial';
                                             }else{echo $row['description'];}
                                             ?>
                                            </td>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                                <?php 
                                                    if($product->presentation != 'Caja')
                                                    {
                                                       echo $row['amount'];
                                                        
                                                    }
                                                    
                                                    if($product->presentation == 'Caja')
                                                    {
                                                       echo $row['amount']/$product->cnt_prod_matriz;
                                                    } 
                                                    ?>
                                            </td>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                            <?php if($row['price']==''){echo '00.00';
                                             }else{echo $row['price'];}
                                             ?>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 col-lg-12 ">
                    <div class="card card-custom card-stretch card-stretch-half gutter-b" style="height: auto;">
                        <div class="card-header border-0 pt-6 mb-2">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bold font-size-h4 text-dark-75 mb-3">Lotes
                                </span>
                            </h3>
                        </div>
                        <div class="card-body pt-2">
                            <div class="table-responsive">
                                <?php $lotes = $this->db->query("SELECT * FROM `lotes` WHERE id_producto = ".$ID." and (branch_id = ".$branchid." or branch_id = 0) and status = 1;")->result_Array();?>
                                <table class="table table-bordered dataTable no-footer dtr-inline">
                                    <thead>
                                        <tr>
                                            <th>Lote</th>
                                            <th>Codigo</th>
                                            <th>Fecha vencimiento</th>
                                            <th>Fecha Ingreso</th>
                                            <th>Movimiento</th>
                                            <th>Cantidad Inicial</th>
                                            <th>Cantidad Disponible</th>
                                            <th>Costo de compra</th>
                                            <th>Sucursal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($lotes as $row):?>
                                        <tr>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                             <?php echo $row['lote_id'];?>
                                            </td>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                             <?php echo $row['code'];?>
                                            </td>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                             <?php echo $row['fecha_vencimiento'];?>
                                            </td>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                             <?php echo $row['fecha'];?>
                                            </td>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                             <?php echo $row['transaccion'];?>
                                            </td>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                             <?php echo $row['cantidad'];?>
                                            </td>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                             <?php echo $row['existencia'];?>
                                            </td>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                             <?php echo $row['precio'];?>
                                            </td>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                             <?php if($row['branch_id']!= '0'){ echo $this->db->get_where('branch',array('branch_id'=>$row['branch_id']))->row()->name;}else{ echo 'Bodega';}?>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xxl-6">
                <?php $cambios = $this->db->order_by('date','DECS')->limit(3)->get_where('product_details',array('products_id'=>$ID , 'estado'=>2 ));?>
                    <div class="card card-custom card-stretch card-stretch-half gutter-b" style="height: auto;">
                        <div class="card-header border-0 pt-6 mb-2">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bold font-size-h4 text-dark-75 mb-3">Cambios
                                </span>
                                <span class="text-muted font-weight-bold font-size-sm">Total:
                                    <?php echo count($cambios->result_array());?></span>
                            </h3>
                        </div>
                        <div class="card-body pt-2">
                            <?php if($cambios->num_rows() >0):?>

                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <?php $n = 1; foreach ($cambios->result_array() as $cow):?>
                                        <tr>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                                <div class="symbol symbol-40 symbol-light-info">
                                                    <span class="symbol-label">
                                                        <span class="svg-icon svg-icon-lg svg-icon-info">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                height="24px" viewBox="0 0 24 24" version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none"
                                                                    fill-rule="evenodd">
                                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                                    <path
                                                                        d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z"
                                                                        fill="#000000" fill-rule="nonzero"
                                                                        opacity="0.3"></path>
                                                                    <path
                                                                        d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z"
                                                                        fill="#000000"></path>
                                                                </g>
                                                            </svg>
                                                        </span>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="align-middle pb-6">
                                                <div class="font-size-lg font-weight-bolder text-dark-75 mb-1">
                                                    Cambio</div>
                                                <div class="font-weight-bold text-muted">
                                                    <?php $date = date_create($cow['date']); echo date_format($date, 'd/m/Y');?>
                                                </div>

                                            </td>
                                            <td class="font-weight-bold text-muted align-middle text-right pb-6">
                                                <span class="svg-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                                                                fill="#000000">
                                                                <rect x="0" y="7" width="16" height="2" rx="1"></rect>
                                                                <rect opacity="0.3"
                                                                    transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)"
                                                                    x="0" y="7" width="16" height="2" rx="1"></rect>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </span>
                                                <span
                                                    class="text-info font-size-h5 font-weight-bolder ml-1"><?php echo $cow['amount'];?></span>
                                            </td>
                                            <td class="text-right align-middle pb-6">
                                                <div class="font-weight-bold text-muted mb-1">Precio</div>
                                                <div class="font-size-lg font-weight-bolder text-dark-75">
                                                    <?php echo $moneda.number_format($cow['price'],2,'.',',');?></div>

                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <center>
                                <h3>Sin datos</h3><br>
                                <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:35%">
                            </center>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xxl-6">
                    <?php $anulados = $this->db->order_by('date','DECS')->limit(3)->get_where('product_details',array('products_id'=>$ID , 'estado'=>3, 'status'=>0 ));?>
                    <div class="card card-custom card-stretch card-stretch-half gutter-b" style="height: auto;">
                        <div class="card-header border-0 pt-6 mb-2">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bold font-size-h4 text-dark-75 mb-3">Anulados
                                </span>
                                <span class="text-muted font-weight-bold font-size-sm">Total:
                                    <?php echo count($anulados->result_array());?></span>
                            </h3>
                        </div>
                        <div class="card-body pt-2">
                            <?php if($anulados->num_rows() >0):?>

                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <?php $n = 1; foreach ($anulados->result_array() as $cow):?>
                                        <tr>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                                <div class="symbol symbol-40 symbol-light-info">
                                                    <span class="symbol-label">
                                                        <span class="svg-icon svg-icon-lg svg-icon-info">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                                height="24px" viewBox="0 0 24 24" version="1.1">
                                                                <g stroke="none" stroke-width="1" fill="none"
                                                                    fill-rule="evenodd">
                                                                    <rect x="0" y="0" width="24" height="24"></rect>
                                                                    <path
                                                                        d="M12,4.56204994 L7.76822128,9.6401844 C7.4146572,10.0644613 6.7840925,10.1217854 6.3598156,9.76822128 C5.9355387,9.4146572 5.87821464,8.7840925 6.23177872,8.3598156 L11.2317787,2.3598156 C11.6315738,1.88006147 12.3684262,1.88006147 12.7682213,2.3598156 L17.7682213,8.3598156 C18.1217854,8.7840925 18.0644613,9.4146572 17.6401844,9.76822128 C17.2159075,10.1217854 16.5853428,10.0644613 16.2317787,9.6401844 L12,4.56204994 Z"
                                                                        fill="#000000" fill-rule="nonzero"
                                                                        opacity="0.3"></path>
                                                                    <path
                                                                        d="M3.5,9 L20.5,9 C21.0522847,9 21.5,9.44771525 21.5,10 C21.5,10.132026 21.4738562,10.2627452 21.4230769,10.3846154 L17.7692308,19.1538462 C17.3034221,20.271787 16.2111026,21 15,21 L9,21 C7.78889745,21 6.6965779,20.271787 6.23076923,19.1538462 L2.57692308,10.3846154 C2.36450587,9.87481408 2.60558331,9.28934029 3.11538462,9.07692308 C3.23725479,9.02614384 3.36797398,9 3.5,9 Z M12,17 C13.1045695,17 14,16.1045695 14,15 C14,13.8954305 13.1045695,13 12,13 C10.8954305,13 10,13.8954305 10,15 C10,16.1045695 10.8954305,17 12,17 Z"
                                                                        fill="#000000"></path>
                                                                </g>
                                                            </svg>
                                                        </span>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="align-middle pb-6">
                                                <div class="font-size-lg font-weight-bolder text-dark-75 mb-1">
                                                    Anulación</div>
                                                <div class="font-weight-bold text-muted">
                                                    <?php $date = date_create($cow['date']); echo date_format($date, 'd/m/Y');?>
                                                </div>

                                            </td>
                                            <td class="font-weight-bold text-muted align-middle text-right pb-6">
                                                <span class="svg-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                                                                fill="#000000">
                                                                <rect x="0" y="7" width="16" height="2" rx="1"></rect>
                                                                <rect opacity="0.3"
                                                                    transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)"
                                                                    x="0" y="7" width="16" height="2" rx="1"></rect>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </span>
                                                <span
                                                    class="text-info font-size-h5 font-weight-bolder ml-1"><?php echo $cow['amount'];?></span>
                                            </td>
                                            <td class="text-right align-middle pb-6">
                                                <div class="font-weight-bold text-muted mb-1">Precio</div>
                                                <div class="font-size-lg font-weight-bolder text-dark-75">
                                                    <?php echo $moneda.number_format($cow['price'],2,'.',',');?></div>

                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <center>
                                <h3>Sin datos</h3><br>
                                <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:35%">
                            </center>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xxl-6">
                    <?php $devoluciones = $this->db->order_by('date','DECS')->limit(3)->get_where('product_details',array('products_id'=>$ID , 'estado'=>1, 'type'=>3 ));?>
                    <div class="card card-custom card-stretch card-stretch-half gutter-b" style="height: auto;">
                        <div class="card-header border-0 pt-6 mb-2">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bold font-size-h4 text-dark-75 mb-3">Devoluciones
                                </span>
                                <span class="text-muted font-weight-bold font-size-sm">Total:
                                    <?php echo count($devoluciones->result_array());?></span>
                            </h3>
                        </div>
                        <div class="card-body pt-2">
                            <?php if($devoluciones->num_rows() >0):?>

                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <?php $n = 1; foreach ($devoluciones->result_array() as $cow):?>
                                        <tr>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                                <div class="symbol symbol-40 symbol-light-warning">
                                                    <span class="svg-icon svg-icon-warning svg-icon-2x">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24"/>
                                                                <path d="M8.42034438,20 L21,20 C22.1045695,20 23,19.1045695 23,18 L23,6 C23,4.8954305 22.1045695,4 21,4 L8.42034438,4 C8.15668432,4 7.90369297,4.10412727 7.71642146,4.28972363 L0.653241109,11.2897236 C0.260966303,11.6784895 0.25812177,12.3116481 0.646887666,12.7039229 C0.648995955,12.7060502 0.651113791,12.7081681 0.653241109,12.7102764 L7.71642146,19.7102764 C7.90369297,19.8958727 8.15668432,20 8.42034438,20 Z" fill="#000000" opacity="0.3"/>
                                                                <path d="M12.5857864,12 L11.1715729,10.5857864 C10.7810486,10.1952621 10.7810486,9.56209717 11.1715729,9.17157288 C11.5620972,8.78104858 12.1952621,8.78104858 12.5857864,9.17157288 L14,10.5857864 L15.4142136,9.17157288 C15.8047379,8.78104858 16.4379028,8.78104858 16.8284271,9.17157288 C17.2189514,9.56209717 17.2189514,10.1952621 16.8284271,10.5857864 L15.4142136,12 L16.8284271,13.4142136 C17.2189514,13.8047379 17.2189514,14.4379028 16.8284271,14.8284271 C16.4379028,15.2189514 15.8047379,15.2189514 15.4142136,14.8284271 L14,13.4142136 L12.5857864,14.8284271 C12.1952621,15.2189514 11.5620972,15.2189514 11.1715729,14.8284271 C10.7810486,14.4379028 10.7810486,13.8047379 11.1715729,13.4142136 L12.5857864,12 Z" fill="#000000"/>
                                                            </g>
                                                        </svg><!--end::Svg Icon-->
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="align-middle pb-6">
                                                <div class="font-size-lg font-weight-bolder text-dark-75 mb-1">
                                                    Devolución</div>
                                                <div class="font-weight-bold text-muted">
                                                    <?php $date = date_create($cow['date']); echo date_format($date, 'd/m/Y');?>
                                                </div>

                                            </td>
                                            <td class="font-weight-bold text-muted align-middle text-right pb-6">
                                                <span class="svg-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                                                                fill="#000000">
                                                                <rect x="0" y="7" width="16" height="2" rx="1"></rect>
                                                                <rect opacity="0.3"
                                                                    transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)"
                                                                    x="0" y="7" width="16" height="2" rx="1"></rect>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </span>
                                                <span
                                                    class="text-info font-size-h5 font-weight-bolder ml-1"><?php echo $cow['amount'];?></span>
                                            </td>
                                            <td class="text-right align-middle pb-6">
                                                <div class="font-weight-bold text-muted mb-1">Precio</div>
                                                <div class="font-size-lg font-weight-bolder text-dark-75">
                                                    <?php echo $moneda.number_format($cow['cost'],2,'.',',');?></div>

                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <center>
                                <h3>Sin datos</h3><br>
                                <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:35%">
                            </center>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-lg-6 col-xxl-6">
                    <?php $perdidas = $this->db->order_by('date','DECS')->limit(3)->get_where('product_details',array('products_id'=>$ID , 'estado'=>1, 'type'=>4 ));?>
                    <div class="card card-custom card-stretch card-stretch-half gutter-b" style="height: auto;">
                        <div class="card-header border-0 pt-6 mb-2">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bold font-size-h4 text-dark-75 mb-3">Pérdidas
                                </span>
                                <span class="text-muted font-weight-bold font-size-sm">Total:
                                    <?php echo count($perdidas->result_array());?></span>
                            </h3>
                        </div>
                        <div class="card-body pt-2">
                            <?php if($perdidas->num_rows() >0):?>

                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <tbody>
                                        <?php $n = 1; foreach ($perdidas->result_array() as $cow):?>
                                        <tr>
                                            <td class="w-40px align-middle pb-6 pl-0 pr-2">
                                                <div class="symbol symbol-40 symbol-light-danger">
                                                    <span class="svg-icon svg-icon-danger svg-icon-2x">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                <polygon points="0 0 24 0 24 24 0 24"/>
                                                                <path d="M5.85714286,2 L13.7364114,2 C14.0910962,2 14.4343066,2.12568431 14.7051108,2.35473959 L19.4686994,6.3839416 C19.8056532,6.66894833 20,7.08787823 20,7.52920201 L20,20.0833333 C20,21.8738751 19.9795521,22 18.1428571,22 L5.85714286,22 C4.02044787,22 4,21.8738751 4,20.0833333 L4,3.91666667 C4,2.12612489 4.02044787,2 5.85714286,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                                                <path d="M10.5857864,13 L9.17157288,11.5857864 C8.78104858,11.1952621 8.78104858,10.5620972 9.17157288,10.1715729 C9.56209717,9.78104858 10.1952621,9.78104858 10.5857864,10.1715729 L12,11.5857864 L13.4142136,10.1715729 C13.8047379,9.78104858 14.4379028,9.78104858 14.8284271,10.1715729 C15.2189514,10.5620972 15.2189514,11.1952621 14.8284271,11.5857864 L13.4142136,13 L14.8284271,14.4142136 C15.2189514,14.8047379 15.2189514,15.4379028 14.8284271,15.8284271 C14.4379028,16.2189514 13.8047379,16.2189514 13.4142136,15.8284271 L12,14.4142136 L10.5857864,15.8284271 C10.1952621,16.2189514 9.56209717,16.2189514 9.17157288,15.8284271 C8.78104858,15.4379028 8.78104858,14.8047379 9.17157288,14.4142136 L10.5857864,13 Z" fill="#000000"/>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="align-middle pb-6">
                                                <div class="font-size-lg font-weight-bolder text-dark-75 mb-1">
                                                    Pérdida</div>
                                                <div class="font-weight-bold text-muted">
                                                    <?php $date = date_create($cow['date']); echo date_format($date, 'd/m/Y');?>
                                                </div>

                                            </td>
                                            <td class="font-weight-bold text-muted align-middle text-right pb-6">
                                                <span class="svg-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <g transform="translate(12.000000, 12.000000) rotate(-45.000000) translate(-12.000000, -12.000000) translate(4.000000, 4.000000)"
                                                                fill="#000000">
                                                                <rect x="0" y="7" width="16" height="2" rx="1"></rect>
                                                                <rect opacity="0.3"
                                                                    transform="translate(8.000000, 8.000000) rotate(-270.000000) translate(-8.000000, -8.000000)"
                                                                    x="0" y="7" width="16" height="2" rx="1"></rect>
                                                            </g>
                                                        </g>
                                                    </svg>
                                                </span>
                                                <span
                                                    class="text-info font-size-h5 font-weight-bolder ml-1"><?php echo $cow['amount'];?></span>
                                            </td>
                                            <td class="text-right align-middle pb-6">
                                                <div class="font-weight-bold text-muted mb-1">Precio</div>
                                                <div class="font-size-lg font-weight-bolder text-dark-75">
                                                    <?php echo $moneda.number_format($cow['cost'],2,'.',',');?></div>

                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <center>
                                <h3>Sin datos</h3><br>
                                <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:35%">
                            </center>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <div class="card card-custom">
                <div class="card-header border-0 py-5">
                    <h3 class="card-title align-items-start flex-column">
                        <span class="card-label font-weight-bolder text-dark">Gráfica</span>
                    </h3>
                </div>

                <div class="card-body py-0">
                </div>
            </div> -->
        </div>
    </div>
</div>