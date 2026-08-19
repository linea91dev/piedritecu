<?php 
  $info = $this->db->get_where('ventas', array('id' => $id))->result_array();
  foreach($info as $row):
?>
        <div class="content-w">
          <div class="content-i">
            <div class="content-box">
              <a href="http://<?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->dirip;?>/update/login/impresion/?atendio=<?php echo $this->db->get_where('admin', array('admin_id' => $row['admin_id']))->row()->name;?>&productos=<?php echo base64_encode($row['productos']);?>&total=<?php echo $row['total'];?>&fecha=<?php echo $row['fecha'];?>&codigo=<?php echo $row['codigo'];?>&cliente=<?php echo $this->db->get_where('clientes', array('id' => $row['client_id']))->row()->nombre;?>&direccion=<?php echo $this->db->get_where('clientes', array('id' => $row['client_id']))->row()->direccion;?>&tel=<?php echo $this->db->get_where('clientes', array('id' => $row['client_id']))->row()->telefono;?>/" target="_blank" class="btn btn-primary btn-sm">Imprimir este comprobante</a>
              <div class="element-wrapper">
                <div class="invoice-w">
                  <div class="infos">
                    <div class="info-1">
                      <div class="invoice-logo-w">
                        <img alt="" src="<?php echo base_url();?>uploads/ferre.jpg" style="max-width:200px">
                      </div><br>
                      <div class="company-name">
                        <?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->nombre;?>
                      </div>
                      <div class="company-address"><b>Dirección:</b> <?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->direccion;?><br><b>Teléfono:</b> <?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->telefono;?></div>
                    </div>
                    <div class="info-2">
                      <div class="company-name"><?php echo $this->db->get_where('clientes', array('id' => $row['client_id']))->row()->nombre;?></div>
                      <div class="company-address"><b>Dirección:</b> <?php echo $this->db->get_where('clientes', array('id' => $row['client_id']))->row()->direccion;?><br><b>Teléfono:</b> <?php echo $this->db->get_where('clientes', array('id' => $row['client_id']))->row()->telefono;?></div>
                    </div>
                  </div>
                  <div class="invoice-heading">
                    <h3>Recibo</h3>
                    <div class="invoice-date"><?php echo $row['fecha'];?> <br> <b>Atendió:</b> <?php echo $this->db->get_where('admin', array('admin_id' => $row['admin_id']))->row()->name;?></div>
                  </div>
                  <div class="invoice-body">
                    <div class="invoice-desc">
                      <div class="desc-label">Recibo #<?php echo $row['id'];?></div>
                      <div class="desc-value">Detalles de la compra</div>
                    </div>
                    <div class="invoice-table">
                      <table class="table">
                        <thead>
                          <tr>
                            <th>Descripción</th>
                            <th>Cantidad</th>
                            <th class="text-right">Precio Unidad</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php 
                          $array = unserialize($row['productos']);
                          foreach($array as $ro):                          
                        ?>   
                          <tr>
                            <td><?php echo $this->db->get_where('producto', array('id' => $ro['id']))->row()->nombre;?></td>
                            <td><?php echo $ro['qty'];?></td>
                            <td class="text-right">Q<?php echo number_format($ro['price']);?></td>
                          </tr>
                        <?php endforeach;?>
                        </tbody>
                        <tfoot>
                          <tr>
                            <td>Total</td>
                            <td class="text-right" colspan="2">Q<?php echo number_format($row['total']);?></td>
                          </tr>
                        </tfoot>
                      </table>
                      <div class="terms">
                        <div class="terms-header">Nota</div>
                          <div class="terms-content">Este recibo es para uso contable solamente, no representa una factura o documento legal.</div>
                      </div>
                    </div>
                  </div>
                  <div class="invoice-footer">
                    <div class="invoice-logo">
                      <img alt="" src="<?php echo base_url();?>uploads/ferre.jpg"><span>Ferretería La Estrella</span>
                    </div>
                    <div class="invoice-info"><span> <?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->correo;?></span><span> <?php echo $this->db->get_where('sucursales', array('id_sucursal' => $this->session->userdata('id_sucursal')))->row()->telefono;?></span></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
<?php endforeach;?>