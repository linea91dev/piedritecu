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
                                <span class="d-block pt-2 font-size-sm">Coloca el mes, el dia  y la sucursal, Todos los campos son requeridos
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-7">
                        <div class="row align-items-center">
                            <form class="form" action="<?php echo base_url();?>admin/ventas_mensuales" method="POST" enctype="multipart/form-data" id="confiForm" name='confiForm'>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Mes a consultar: <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="month" class="form-control" name="mes"  required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Sucursal: <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select class="form-control" required name="branch_id">
                                                    <option value="">Seleccionar</option>
                                                    <?php $branch = $this->db->get_where('branch', array('status' => 1))->result_array();
                                                        foreach($branch as $row):?>
                                                    <option value="<?php echo $row['branch_id']?>" <?php if($rs['branch_id'] == $this->session->userdata('branch_id')) echo "selected";?>>
                                                        <?php echo $row['name'];?>
                                                    </option>
                                                    <?php endforeach;?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Fecha inicial: <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" name="initial" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Fecha final: <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" name="final" required />
                                            </div>
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