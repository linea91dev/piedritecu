<?php ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-sm-12">
                                <h3 class="card-label">Configuración del sistema
                                    <?php echo $this->db->get_where('settings', array('type'=>'name'))->row()->description;?>
                                    <span class="d-block text-muted pt-2 font-size-sm"></span>
                                </h3>
                            </div>
                            <div class="col-sm-12 alert alert-blue">
                                <span class="d-block pt-2 font-size-sm">Coloca el mes, el dia  y el id de la sucursal central(1), Cpixels (17), Opixels(18), Apixels(19), Hpixels(21), Todos los campos son requeridos
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-7">
                        <div class="row align-items-center">
                            <form class="form" action="<?php echo base_url();?>admin/lesli" method="POST" enctype="multipart/form-data" id="confiForm" name='confiForm'>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                    <input name="mes" type="number" max="12" min="1" class="form-control" required placeholder="mes">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <input name="dia" type="date" max="31" min="1" class="form-control" required placeholder="dia">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                        <input name="branch_id" type="number" max="31" min="1" class="form-control" required placeholder="sucursal">
                                        </div>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Enviar </button>
                                        </div>
                                    </div>
                                </div>
                                
                                
            
        

       
</form>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

