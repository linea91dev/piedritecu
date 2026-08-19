<?php $edit_data = $this->db->get_where('events', array('events_id'=>$param2))->result_array(); foreach ($edit_data as $row) :?>
<form class="form" action="<?php echo base_url().'admin/calendario/update/'.$param2;?>" method="POST"
    enctype="multipart/form-data">
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <div class="alert alert-custom alert-default" role="alert">
                    <div class="alert-icon"><i class="flaticon-warning text-primary"></i></div>
                    <div class="alert-text">
                        Los campos marcados con * son obligatorios.
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="form-group">
                <label>Título <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input type="text" class="form-control" aria-label="Text input with checkbox" required name='title'
                        value='<?php echo $row['title'];?>' />
                </div>
            </div>
        </div>

        <div class="col-sm-2">
            <div class="form-group">
                <label>Color <span class="text-danger">*</span></label>
                <div class="input-group">
                    <input class="form-control" type="color" value="#563d7c" id="example-color-input" name='color'>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="">Fecha inicial <span class="text-danger">*</span></label>
                <input type="date" required id="start" name="date_start" class="form-control"
                    value='<?php echo ($row['date_start'] != '' ) ? $row['date_start'] : date('Y-m-d');?>'>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="">Hora inicial <span class="text-danger">*</span></label>
                <div class="input-group clockpicker" data-align="top" data-autoclose="true">
                    <input type="time" required="" name="time_start" class="form-control"
                        value="<?php echo $row['time_start'];?>">
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="">Fecha final <span class="text-danger">*</span></label>
                <input type="date" required id="start" name="date_end" class="form-control"
                    value='<?php echo ($row['date_end'] != '' ) ? $row['date_end'] : date('Y-m-d');?>'>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="">Hora final <span class="text-danger">*</span></label>
                <div class="input-group clockpicker" data-align="top" data-autoclose="true">
                    <input type="time" required="" name="time_end" class="form-control"
                        value="<?php echo $row['time_end'];?>">
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label for="">Descripción <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="5"
                    maxlength="500" required><?php echo $row['description'];?></textarea>
            </div>
        </div>
        
        <div class="col-sm-6">
            <div class="checkbox-inline">
                <label class="checkbox checkbox-lg checkbox-danger">
                    <input type="checkbox" name="delete" id="" value="1">
                    <span></span>Eliminar
                </label>
            </div>
            <small>Si presionas esta opción, se eliminará este evento</small>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary font-weight-bold">Actualizar</button>
    </div>
</form>
<?php endforeach;?>