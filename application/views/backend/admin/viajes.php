<?php echo form_open(base_url() . 'admin/sales_order_add' , array('enctype' => 'multipart/from-data'));?>
<div class="content-i">
  <div class="content-box">
    <div class="row">
      <div class="col-sm-12">
        <div class="element-wrapper">
            <div class="element-box">
                <h5 class="form-header">Detalles del viaje</h5>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for=""> Código de viaje</label>
                        <input class="form-control" value="<?php echo substr(rand(0, 1000000), 0, 7);?>" type="text" name="order_code">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label for=""> Tipo de cliente</label>
                        <select name="type" onchange="check_user(this.value)" class="select form-control" onchange="get_address(this.value)" required>
                          <option value="">Seleccione</option>
                          <option value="1">Cliente existente</option>
                          <option value="2">Cliente nuevo</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row" id="nuevo">
                       <div class="col-sm-12">
                        <div class="form-group">
                          <label for="">Nombre</label><input class="form-control" name="nombre" type="text">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="">Correo</label><input class="form-control" name="correo" type="email">
                        </div>
                      </div>
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label for="">Celular</label><input class="form-control" name="telefono" type="text">
                        </div>
                      </div>
                  </div>
                  <div class="row" id="existente">
                    <div class="col-sm-12">
                      <div class="form-group">
                          <label for=""> Seleccionar cliente</label>
                            <select class="form-control" name="customer_user_id">
                              <option value="">Seleccionar</option>
                              <?php
                                $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
                                $clientes = $this->db->get('clientes')->result_array();
                                foreach($clientes as $key):
                              ?>
                                <option value="<?php echo $key['id']?>"><?php echo $key['nombre']?></option>
                              <?php endforeach;?>
                    </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                   <div class="col-sm-6">
                        <div class="form-group">
                          <label for="">Fecha</label><input class="form-control single-daterange" type="text" name="fecha">
                        </div>
                      </div>

                      <div class="col-sm-6">
                        <div class="form-group">
                          <label for="">Empleado responsable</label>
                            <select class="form-control" name="seller_user" required>
                              <option value="">Seleccionar empleado</option>
                                <?php
                                    $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
                                    $empleados = $this->db->get('admin')->result_array();
                                    foreach($empleados as $row):
                                ?>
                                <option value="<?php echo $row['admin_id'];?>"><?php echo $row['name'];?></option>
                              <?php endforeach;?>
                            </select>
                        </div>
                      </div>

<div class="col-sm-12">
                        <div class="form-group">
                          <label for="">Cantidad de combustible</label><input class="form-control" name="gas" type="text">
                        </div>
                      </div>

                  <div class="col-sm-12">
                        <div class="form-group">
                          <label for="">Dirección de envío</label><textarea class="form-control" rows="5" name="direccion"></textarea>
                        </div>
                  </div>
 
                <div class="col-sm-12">
                         <div class="form-group">
                          <label for="">Descripción</label><textarea class="form-control" rows="5" name="descripcion"></textarea>
                        </div>
                  </div>

 <div class="col-sm-12">
                         <div class="form-group">
                          <button class="btn btn-primary" type="submit">Crear viaje</button>
                        </div>
                  </div>


                </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo form_close();?>


<script type="text/javascript">
  $(document).ready(function() {
    $('#add_entry_button').prop('disabled' , true);
    if($.isFunction($.fn.select2))
    {
      $(".select2").each(function(i, el)
      {
        var $this = $(el),
          opts = {
            allowClear: attrDefault($this, 'allowClear', false)
          };
        $this.select2(opts);
        $this.addClass('visible');
      });
      if($.isFunction($.fn.niceScroll))
      {
        $(".select2-results").niceScroll({
          cursorcolor: '#d4d4d4',
          cursorborder: '1px solid #ccc',
          railpadding: {right: 3}
        });
      }
    }
    if($.isFunction($.fn.selectBoxIt))
    {
      $("select.selectboxit").each(function(i, el)
      {
        var $this = $(el),
          opts = {
            showFirstOption: attrDefault($this, 'first-option', true),
            'native': attrDefault($this, 'native', false),
            defaultText: attrDefault($this, 'text', ''),
          };
        $this.addClass('visible');
        $this.selectBoxIt(opts);
      });
    }
  });
</script>

<script type="text/javascript">
  var count = 1;
  function get_address(user_id)
  {
    $.ajax({
      url: '<?php echo base_url();?>admin/reload_customer_address/' + user_id,
      success: function(response)
      {
        jQuery('#selected_customer_address').html(response);
      }
    });
  }
  function show_response(variant_id)
  {
    $.ajax({
      url: '<?php echo base_url();?>admin/sales_order_entry_response/' + variant_id,
      success: function(response)
      {
        jQuery('#sales_order_entry_1').html(response);
      }
    });
  }
  function append_sales_order_entry()
  {
    var selected_variants = '';
    $(".variant").each(function() {
        selected_variants += $(this).val() + '.';
    });
    count++;
    $.ajax({
      url: '<?php echo base_url();?>admin/sales_order_append_entry_response/' + count + '/' + selected_variants,
      success: function(response)
      {
        jQuery('#sales_order_entry_append').append(response);
      }
    });
  }
  function deleteParentElement(n)
  {
    n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
    calculate_grand_total();
  }
</script>

<script>
    jQuery(document).ready(function () {
        $(".select2").select2();
    });
</script>

<script type="text/javascript">
  $('#existente').hide();
  $('#nuevo').hide();

  function check_user(value) {
    if(value == 1) {
      $('#existente').show(500);
      $('#nuevo').hide(500);
    } else if(value == 2) {
      $('#nuevo').show(500);
      $('#existente').hide(500);
    }
  }
</script>