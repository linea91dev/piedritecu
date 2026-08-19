    <div class="onboarding-content with-gradient">
        <h4 class="onboarding-title">Descarga de inventario por marca</h4>
        <?php echo form_open(base_url().'admin/export_excel/inventario_mark/');?>
            
            <div class="row">
                <div class="col-sm-12">
                   <div class="form-group">
                        <label class="col-form-label">Seleccione una marca</label>
                        <select class="form-control" name="mark_id" id="mark_id" onchange="this.form.submit()">
                            <?php $marks = $this->crud_model->get_mark();
                                foreach($marks->result_array() as $mk):?>
                            <option value="<?php echo $mk['mark_id'];?>" <?php if($mk['mark_id'] == $mark_id) echo "selected";?>><?php echo $mk['name'];?></option>
                            <?php endforeach;?>
                        </select>
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