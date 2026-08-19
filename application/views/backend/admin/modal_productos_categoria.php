<?php $category_id = base64_decode($param2);
    $tipo = $this->session->userdata('login_user_type');
    $data = $this->db->get_where('categories' , array('category_id' => $category_id))->result_array();
    foreach ($data as $row):
        $products = $this->db->get_where('products', array('category'=>$category_id))->num_rows();
?>   
<div class="onboarding-media" style="margin-bottom:-50px;z-index:999">
    <img alt="" src="<?php echo base_url();?>uploads/ferre.jpg" width="200px">
</div>
<div class="onboarding-content with-gradient">
    <h4 class="onboarding-title" style="margin-top: 1.5rem;">Asignados a la categoria: <?php echo $row['name'];?></h4>
    <div class="card-body" style="">
        <?php if($products >0):?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover dataTable no-footer dtr-inline collapsed" id="user_data" role="grid" aria-describedby="kt_datatable_info">
                 <thead>
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Proveedores</th>
                        <?php  if($this->session->userdata('branch_id')!=(22)):?>
                        <th style="white-space: nowrap;">San Juan</th>
                        <?endif;?>
                        <?php  if($this->session->userdata('branch_id')!=(23)):?>
                        <th style="white-space: nowrap;">Salcaja</th>
                        <?endif;?>
                        <?php  if($this->session->userdata('branch_id')!=(1)):?>
                        <th style="white-space: nowrap;">Central</th>
                        <?endif;?>
                        <th style="white-space: nowrap;">En tienda</th>
                        <th style="white-space: nowrap;">En bodega</th>
                      <?php if($tipo==1){?>  <th>Costo</th> <?php } ?>
                        <th>Precio publico</th>
                        <th>Precio Farmacia</th>
                        <th>Precio Mayorista</th>
                        <th>Estado</th>
                    </tr>
                 </thead>
              </table>
            
        </div>
        <?php else: ?>
        <center>
            <h3>Sin datos</h3><br>
            <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:25%">
        </center>
        <?php endif; ?>
    </div>
</div>
<script type="text/javascript" language="javascript" >  
    $(document).ready(function(){  
        var dataTable = $('#user_data').DataTable({  
            "processing":true,  
            "serverSide":true,  
            "order":[],  
            "ajax":{  
                url:"<?php echo base_url() . 'admin/get_inventario_by_category/'.$category_id; ?>",  
                type:"POST"  
            },  
            "columnDefs":[  
                {  
                    "targets":[0, 3, 4],  
                    "orderable":false,  
                },  
            ],  
        });  
    });  
</script>
<?php endforeach; ?>