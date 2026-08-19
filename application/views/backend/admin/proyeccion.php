<div class="content-box">
  <?php $id_venta = $this->db->get_where('creditos', array('id' => $id))->row()->id_venta; 
  $id_cliente = $this->db->get_where('creditos', array('id' => $id))->row()->client_id;
  ?>
  <div class="element-box">
    <h5 class="form-header">Detalles de la compra</h5>   
    <h6 class="form-header">
    </h6>
    <div class="row">
    <div class="col-sm-3">
    <p><b>Cliente:</b> <span class="text-primary"><?php echo $this->db->get_where('clientes', array('id' => $id_cliente))->row()->nombre;?></span></p>
  </div>
  <div class="col-sm-3">
    <p><b>Teléfono:</b> <span class="text-primary"><?php echo $this->db->get_where('clientes', array('id' => $id_cliente))->row()->telefono;?></span></p>
  </div>
  <div class="col-sm-3">
    <p><b>NIT:</b> <span class="text-primary"><?php echo $this->db->get_where('clientes', array('id' => $id_cliente))->row()->nit;?></span></p>
  </div>
  <div class="col-sm-3">
    <p><b>Dirección:</b> <span class="text-primary"><?php echo $this->db->get_where('clientes', array('id' => $id_cliente))->row()->direccion;?></span></p>
  </div>
  </div><hr>
    <div class="table-responsive">
            <table width="100%" class="table table-striped table-lightfont">
              <thead>
                <tr>
                  <th class="text-center">Fecha</th>
                  <th class="text-center">Producto</th>
                  <th class="text-center">Cantidad</th>
                  <th class="text-center">Precio Unidad</th>
                  <th class="text-center">Subtotal</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    $productos = $this->db->get_where('ventas', array('id' => $id_venta))->row()->productos;
                ?>
                  <?php 
                    $array = unserialize($productos);
                    foreach($array as $ro):                          
                  ?>                  
                 <tr>
                  <td class="text-center"><?php echo $this->db->get_where('ventas', array('id' => $id_venta))->row()->fecha;?></td>
                  <td class="text-center"><img alt="" src="<?php echo base_url();?>uploads/productos/<?php echo $ro['id'];?>.jpg" style="height: 30px;"><span> <?php echo $ro['name'];?></span>
                  </td>
                  <td class="text-center"><b><?php echo $ro['qty'];?></b></td>
                  <td class="text-center"><span class="text-success"><b>Q<?php echo $ro['price'];?></span></b></td>
                  <td class="text-center"><span class="text-primary"><b>Q<?php echo number_format($ro['price']*$ro['qty']);?></span></b></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
             <table class="table table-padded">
                      <tbody>
                        <tr>
                          <td class="text-right bolder">
                            TOTAL: <span class="text-success" style="margin-left:3rem;font-size:2rem">Q<?php echo number_format($this->db->get_where('creditos', array('id' => $id))->row()->total);?></span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
          </div>
  </div>



  <div class="element-wrapper">
      <div class="element-box">
        <h5 class="form-header">Pagos realizados</h5>
        <div class="form-desc">Aquí se visualizarán las cuotas canceladas para el crédito</div>
        <h5 class="form-header">Saldo restante: Q<?php echo $this->db->get_where('creditos', array('id' => $id))->row()->restante;?></h5>
        <div class="controls-above-table">
          <div class="row">
            <div class="col-sm-6">
              <a class="btn btn-sm btn-success" href="<?php echo base_url();?>admin/cancelar/<?php echo $id;?>" onClick="return confirm('El crédito se marcará como cancelado. ¿Desea continuar?')">Marcar como pagado</a>
              <?php if($this->db->get_where('creditos', array('id' => $id))->row()->restante > 0):?>
              <a class="btn btn-sm btn-primary" href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_registro/<?php echo $id;?>');">Agregar pago</a>
              <?php endif;?>
            </div>
        </div>
      </div>
    <div class="table-responsive">
      <table class="table table-lightborder">
        <thead>
          <tr>
            <th class="text-center">Fecha</th>
            <th class="text-center">Cuota</th>
          </tr>
        </thead>
        <tbody>
          <?php 
            $cuotas = $this->db->get_where('cuotas', array('id_credito' => $id))->result_array();
            foreach($cuotas as $row):
          ?>
          <tr>
              <?php
                $nuevo;
                $mes = $row['mes'];
                if($mes == 1){$nuevo = "Enero";}
                if($mes == 2){$nuevo = "Febrero";}
                if($mes == 3){$nuevo = "Marzo";}
                if($mes == 4){$nuevo = "Abril";}
                if($mes == 5){$nuevo = "Mayo";}
                if($mes == 6){$nuevo = "Junio";}
                if($mes == 7){$nuevo = "Julio";}
                if($mes == 8){$nuevo = "Agosto";}
                if($mes == 9){$nuevo = "Septiembre";}
                if($mes == 10){$nuevo = "Octubre";}
                if($mes == 11){$nuevo = "Noviembre";}
                if($mes == 12){$nuevo = "Diciembre";}
               ?>
            <td class="text-center"><?php echo $row['mes'];?></td>
            <td class="text-center"><b>Q<?php echo number_format($row['cuota'],2,'.','');?></b></td>
          </tr>
        <?php endforeach;?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>