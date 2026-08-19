<div class="content-w">
    <div class="content-i">
        <div class="content-box">
            <div class="row">
                <div class="col-sm-6">
                    <a class="element-box el-tablo centered trend-in-corner padded bold-label"
                        data-target="#agregarproducto" data-toggle="modal" href="#">
                        <div class="value"><i class="picons-thin-icon-thin-0464_shipping_box_delivery"></i></div>
                        <div class="label">Agregar Producto</div>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a class="element-box el-tablo centered trend-in-corner padded bold-label" data-target="#alerta"
                        data-toggle="modal" href="#">
                        <div class="value"><i class="picons-thin-icon-thin-0061_error_warning_alert_attention"></i>
                        </div>
                        <div class="label">Productos en Alerta</div>
                    </a>
                </div>
            </div>
            <!-- Crear una nueva categoría -->
            <div aria-hidden="true" class="onboarding-modal modal fade animated" id="categoria" role="dialog"
                tabindex="-1">
                <div class="modal-dialog modal-lg modal-centered" role="document">
                    <div class="modal-content text-center">
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                class="os-icon os-icon-close"></span></button>
                        <div class="onboarding-media" style="margin:3rem">
                            <img alt="" src="<?php echo base_url();?>uploads/ferre.jpg" width="200px">
                        </div>
                        <div class="onboarding-content with-gradient">
                            <h4 class="onboarding-title">Crear Categoría</h4>
                            <?php echo form_open(base_url() . 'admin/productos/categoria/' , array('enctype' => 'multipart/form-data'));?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Nombre de Categoría</label>
                                        <input class="form-control" name="nombre" placeholder="Nombre" type="text"
                                            required="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Descripción</label>
                                        <input class="form-control" placeholder="Descripción" name="descripcion"
                                            type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="form-buttons-w text-right compact">
                                <button class="btn btn-primary" type="submit"><span>Crear</span></button>
                            </div>
                            <?php echo form_close();?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Finaliza modal categoría -->
            <div aria-hidden="true" class="onboarding-modal modal fade animated" id="agregarproducto" role="dialog"
                tabindex="-1">
                <div class="modal-dialog modal-lg modal-centered" role="document">
                    <div class="modal-content text-center">
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                class="os-icon os-icon-close"></span></button>
                        <div class="onboarding-media" style="margin-bottom:-50px;z-index:999">
                            <img alt="" src="<?php echo base_url();?>uploads/ferre.jpg" width="200px">
                        </div>
                        <div class="onboarding-content with-gradient">
                            <h4 class="onboarding-title">Agregar producto</h4>
                            <?php echo form_open(base_url() . 'admin/productos/nuevo/' , array('enctype' => 'multipart/form-data'));?>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="">Producto</label><input class="form-control" placeholder="Producto"
                                            name="nombre" required="" type="text" value="">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="">Marca</label><input class="form-control" name="marca"
                                            placeholder="Marca" type="text" value="">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="">Foto</label> <input class="form-control" name="userfile"
                                            type="file">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Categoría</label>
                                        <select class="form-control" name="codigo_categoria" required="">
                                            <option value="">Seleccionar categoría</option>
                                            <?php 
                            $this->db->where('id_sucursal', $this->session->userdata('sucursales'));
                            $categorias = $this->db->get('categoria')->result_array();
                            foreach($categorias as $row):
                        ?>
                                            <option value="<?php echo $row['codigo'];?>"><?php echo $row['nombre'];?>
                                            </option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Descripción</label>
                                        <input class="form-control" name="descripcion" placeholder="Descripción"
                                            type="text" value="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Precio de Compra</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Q</div>
                                            </div>
                                            <input name="costo" class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="">Precio de Venta</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Q</div>
                                            </div>
                                            <input class="form-control" name="precio" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="">Código de producto</label>
                                        <input class="form-control" name="codigo" placeholder="Código de producto"
                                            type="text" value="">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="">Stock Inicial</label>
                                        <input class="form-control" name="stock" placeholder="Stock Inicial"
                                            type="number" value="">
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label for="">Cantidad de Alerta</label>
                                        <input class="form-control" name="alerta" placeholder="Cantidad de Alerta"
                                            type="number" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="form-buttons-w text-right compact">
                                <button class="btn btn-primary" type="submit"><span>Crear</span></button>
                            </div>
                            <?php echo form_close();?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Productos en alerta-->
            <div aria-hidden="true" class="onboarding-modal modal fade animated" id="alerta" role="dialog"
                tabindex="-1">
                <div class="modal-dialog modal-lg modal-centered" role="document">
                    <div class="modal-content text-center">
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span
                                class="os-icon os-icon-close"></span></button>
                        <div class="onboarding-media" style="margin-bottom:-50px;z-index:999">
                            <img alt="" src="<?php echo base_url();?>uploads/ferre.jpg" width="200px">
                        </div>
                        <div class="onboarding-content with-gradient">
                            <h4 class="onboarding-title">Productos en alerta</h4>
                            <div class="table-responsive">
                                <table class="table table-padded">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th class="text-center">Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                    $this->db->limit(70);
                    $this->db->select('id,stock,alerta,nombre');
                    $this->db->where('stock <=','alerta');
                    $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
                    $alertas = $this->db->get('producto')->result_array();
                    foreach($alertas as $row3):
                  ?>
                                        <?php if($row3['stock'] <= $row3['alerta']):?>
                                        <tr>
                                            <td class="text-left cell-with-media"><img alt=""
                                                    src="<?php echo base_url();?>uploads/productos/<?php echo $row3['id'];?>.jpg"
                                                    style="height: 30px;"><span><?php echo $row3['nombre'];?></span>
                                            </td>
                                            <td class="text-center bolder nowrap"><span
                                                    class="text-danger"><?php echo $row3['stock'];?></span></td>
                                        </tr>
                                        <?php endif;?>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-buttons-w text-right compact">
                                <button aria-label="Close" class="btn btn-primary" data-dismiss="modal"
                                    type="button"><span>Cerrar ventana</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Finaliza productos en alerta -->
            <div class="element-wrapper">
                <div class="element-box">
                    <h5 class="text-center element-box-header">Productos</h5>
                    <div class="table-responsive">
                        <table id="user_data" width="100%" class="table table-striped table-lightfont">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Marca</th>
                                    <th>Categoría</th>
                                    <th>Existencia</th>
                                    <th>Fecha de Ingreso</th>
                                    <th>Precio Compra</th>
                                    <th>Precio Venta</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" language="javascript">
$(document).ready(function() {
    var dataTable = $('#user_data').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: "<?php echo base_url() . 'admin/get_orders'; ?>",
            type: "POST"
        },
        "columnDefs": [{
            "targets": [0, 3, 4],
            "orderable": false,
        }, ],
    });
});
</script>