<?php $products_details = $this->db->get_where('product_details',array('product_details_id' => $ID))->row();?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Editar lote del producto
                            <span class="d-block text-muted pt-2 font-size-sm">Administra tus productos en
                                inventario.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="<?php echo ($destino == 1) ? base_url().'admin/new/lote' : base_url().'admin/bodega' ;?>"
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
                            </span> Regresar al <?php echo($destino == 1) ? 'inventario':'bodega' ?>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form" action="<?php echo base_url();?>admin/producto_lote/edit" method="POST"
                        enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-sm-5">
                                <div class="form-group text-center">
                                    <div
                                        class="card-body p-0 rounded px-10 py-15 d-flex align-items-center justify-content-center">
                                        <img src="<?php echo ($this->db->get_where('products',array('products_id' => $products_details->products_id))->row()->img!='') ? base_url().'uploads/productos/'.$this->db->get_where('products',array('products_id' => $products_details->products_id))->row()->img :base_url().'uploads/productos/default_product.png';?> "
                                            class="mw-100 w-200px" style="transform: scale(1.6);">
                                    </div>

                                </div><br>
                            </div>
                            <div class="col-sm-7">
                                <div class="row">
                                    <div class="col-sm-7">
                                        <div class="form-group">
                                            <label>Producto <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input disabled type="text" class="form-control"
                                                    aria-label="Text input with checkbox" name='name' required
                                                    value="<?php echo $this->db->get_where('products',array('products_id' => $products_details->products_id))->row()->name;?>" />
                                                <input type="hidden" name='products_id' required
                                                    value="<?php echo $products_details->products_id;?>" />
                                                <input type="hidden" name='product_details_id' required
                                                    value="<?php echo $ID;?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-5">
                                        <div class="form-group">
                                            <label>Código <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input disabled type="text" class="form-control"
                                                    aria-label="Text input with checkbox" placeholder="FDF34FS"
                                                    name='code' maxlength="6" required
                                                    value="<?php echo $this->db->get_where('products',array('products_id' => $products_details->products_id))->row()->code;?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Proveedor <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control" name='provider_id' required>
                                                    <option value=''>Seleccionar</option>
                                                    <?php $providers = $this->crud_model->get_provider(); foreach ($providers->result_array() as $provider):?>
                                                    <option value="<?php echo $provider['provider_id'];?>"
                                                        <?php echo $products_details->provider == $provider['provider_id']  ? "selected":""; ?>>
                                                        <?php echo $provider['name'];?>
                                                    </option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Cantidad <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" class="form-control"
                                                    aria-label="Text input with checkbox" required name='amount'
                                                    value="<?php echo $products_details->amount; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Precio de compra <span class="text-danger">*</span> </label>
                                            <div class="input-group">
                                                <input type="number" class="form-control"
                                                    aria-label="Text input with checkbox" required name='cost' min='0'
                                                    value="<?php echo $products_details->price; ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <div class="input-group">
                                        <textarea class="form-control" aria-label="Text input with checkbox"
                                            name='description'
                                            rows="6"><?php echo $products_details->description; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12"><br><br>
                                <button type="submit" class="btn btn-primary font-weight-bold"
                                    style="float: right;">Guardar</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$("#variantes").hide();

function post(value) {
    if (value == 1) {
        $("#variantes").show(500);
    } else if (value == 2) {
        $("#variantes").hide(500);
    }
}

function removeOption(i) {
    $('#variante-' + i).remove();
}

function addOption() {
    id = Math.floor(Math.random() * 300) + 10;
    var html = `
    <tr id='variante-${id}'>
        <td width="150px"><input class="form-control" type="text" placeholder="Ej: Color/Modelo" name="type_v[]"></td>
            <td><input class="form-control" type="text"
                    placeholder="Ej: Aplica solo a metal" name="description_v[]">
            </td>
            <td><input class="form-control" type="text" name="stock_v[]"></td>
            <td><input class="form-control" type="text" name="alert_v[]"></td>
            <td><input class="form-control" type="text" name="price_buy_v[]"></td>
            <td><input class="form-control" type="text" name="price_sale_v[]"></td>
            <td class="text-center">
                <a class="badge badge-danger"
                    onclick="removeOption('${id}')"
                    href="javascript:;">
                    <span class="svg-icon svg-icon-white svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink"
                            width="24px" height="24px" viewBox="0 0 24 24"
                            version="1.1">
                            <g stroke="none" stroke-width="1" fill="none"
                                fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path
                                    d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                                    fill="#000000" fill-rule="nonzero" />
                                <path
                                    d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                    fill="#000000" opacity="0.3" />
                        </g>
                    </svg>
                </span>
            </a>
        </td>
    </tr>`;
    $('#variants').append(html);

}
</script>