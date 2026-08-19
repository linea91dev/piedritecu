<div class="content-w">
  <div class="content-i">
    <div class="content-box">
      <div class="element-wrapper">
        <div class="element-box">
<div class="text-left">
          <a href="<?php echo base_url();?>admin/viajes/" class="btn btn-lg btn-primary pull-right btn-sm">
            <span>Registrar viaje</span>
          </a>

          <a href="<?php echo base_url();?>admin/cierreviajes2/" class="btn btn-lg btn-success pull-right btn-sm">
            <span>Cerrar viajes manualmente</span>
          </a>
</div>
 
          <div class="text-right"><a href="<?php echo base_url();?>admin/report_sales_order_view/" class="btn btn-lg btn-success pull-right btn-sm" href="#">
            <span>Consultar viajes</span> 
          </a></div>
          <h5 class="text-center element-box-header">Registro de viajes</h5>           
            <div class="table-responsive">
            <table id="dataTable1" width="100%" class="table table-striped table-lightfont">
              <thead>
                <tr>
                  <th style="text-align: center;">Código</th>
                  <th style="text-align: center;">Cliente</th>
                  <th style="text-align: center;">Estado</th>
                  <th style="text-align: center;">Total</th>
                  <th style="text-align: center;">Fecha</th>
                  <th style="text-align: center;">Responsable</th>
                  <th style="text-align: center;">Opciones</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                    $this->db->where('id_sucursal', $this->session->userdata('id_sucursal'));
                    $sales_orders = $this->db->get('sales_order')->result_array(); 
                    foreach($sales_orders as $row):
               ?>
                 <tr>
                    <td style="text-align: center;"><strong><?php echo $row['order_code'];?></strong></td>
                    <td style="text-align: center;"><?php echo $this->db->get_where('clientes' , array('id' => $row['customer_user_id']))->row()->nombre;?></td>
                    <td style="text-align: center;"><?php if($row['order_status'] == 0):?><span class="badge badge-danger">Pendiente de cierre</span><?php endif;?><?php if($row['order_status'] != 0):?><span class="badge badge-success">Viaje cerrado</span><?php endif;?></td>
                    <td style="text-align: center;"><strong>Q<?php echo $row['total_amount'];?></strong></td>
                    <td style="text-align: center;"><?php echo date('D, d M Y' , $row['date_added']);?></td>
                    <td style="text-align: center;"><?php echo $this->db->get_where('admin', array('admin_id' => $row['seller_user']))->row()->name;?></td>
                    <td style="text-align: center;">
                        <a target="_blank" href="<?php echo base_url();?>admin/sales_order_invoice_print_view/<?php echo $row['order_id'];?>" class="btn btn-success btn-sm"><i class="picons-thin-icon-thin-0333_printer"></i></a>
                        <a href="<?php echo base_url();?>admin/detalles_viaje/<?php echo $row['order_id'];?>" class="btn btn-primary btn-sm"><i class="picons-thin-icon-thin-0033_search_find_zoom"></i></a>
                        <a onClick="return confirm('¿Seguro desea eliminar al cliente? Esta acción no se puede deshacer.')" href="<?php echo base_url();?>admin/viajes/delete/<?php echo $row['order_code'];?>" class="btn btn-danger btn-sm"><i class="os-icon os-icon-ui-15"></i></a>
                    </td>
                  </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
      </div>    
    </div>
  </div>
</div>