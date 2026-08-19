    <div class="onboarding-content with-gradient">
        <h4 class="onboarding-title">Descarga de inventario</h4>
        <?php echo form_open(base_url().'admin/export_excel/inventario/');?>
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Sucursales</label>
                        <div class="input-group">
                            <select name="branch_id" class="form-control">
                                <option value="">Seleccionar</option>
                                <option value="0">Bodega</option>
                                <?php
                                    $sucursal = $this->db->get_where('branch', array('status'=>1))->result_array(); 
                                    foreach ($sucursal as $sc):
                                ?>
                                    <option value="<?php echo $sc['branch_id'];?>"><?php echo $sc['name'];?></option>
                                <?php  
                                    endforeach;
                                ?>
                            </select>
                        </div>
                    </div>
                </div>      
            </div>
                    
            <div class="form-buttons-w text-right compact">
                <button class="btn btn-primary" type="submit">
                    <span>Descargar</span>
                </button>
            </div>
            
        <?php echo form_close();?>
    </div>