<form class="form" action="<?php echo base_url().'admin/calendario/create/';?>" method="POST"
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
                        value='' />
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
                <input type="date" required id="start" name="date_start"
                    class="form-control" <?php echo ($param2 !='')? 'readonly': '';?>
                    value='<?php echo ($param2 != '' ) ? $param2 : date('Y-m-d');?>'>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="">Hora inicial <span class="text-danger">*</span></label>
                <div class="input-group clockpicker" data-align="top" data-autoclose="true">
                    <input type="time" required="" name="time_start" class="form-control"
                        value="<?php echo date('H:i');?>">
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="">Fecha final <span class="text-danger">*</span></label>
                <input type="date" required id="end" name="date_end" class="form-control"
                    value='<?php $hoy = date('Y-m-d') ; echo ($param3 != '' ) ? $param3 : date("Y-m-d", strtotime($hoy.'+ 1 days')) ;?>'>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <label for="">Hora final <span class="text-danger">*</span></label>
                <div class="input-group clockpicker" data-align="top" data-autoclose="true"><?php $hora = date('H:i');?>
                    <input type="time" required="" name="time_end" class="form-control" value="<?php echo date('H:i', strtotime($hora.'+ 1 hour'));?>">
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="form-group">
                <label for="">Descripción <span class="text-danger">*</span></label>
                <textarea name="description" class="form-control" rows="5" maxlength="500" required></textarea>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="checkbox-inline">
                <label class="checkbox checkbox-lg checkbox-success">
                    <input type="checkbox" name="view_all" id="" value="1">
                    <span></span>Público
                </label>
            </div>
            <small>Si marcas esta opción, todos los usuarios podran ver este evento</small>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary font-weight-bold">Continuar</button>
    </div>
</form>