<?php $data = $this->db->order_by('income_id','desc')->get('income'); $moneda = $this->crud_model->get_info("moneda");?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar ingresos
                            <span class="d-block text-muted pt-2 font-size-sm">Gestiona los ingresos económicos de tus
                                tiendas.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <?php if($data->num_rows() > 0):?>
                        <div class="dropdown dropdown-inline mr-2">
                            <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="svg-icon svg-icon-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path
                                                d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z"
                                                fill="#000000" opacity="0.3" />
                                            <path
                                                d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z"
                                                fill="#000000" />
                                        </g>
                                    </svg>
                                </span>Exportar
                            </button>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                <ul class="navi flex-column navi-hover py-2">
                                    <li
                                        class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                        Exportar como:</li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_excel/ingresos'?>"
                                            class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-excel-o"></i>
                                            </span>
                                            <span class="navi-text">Excel</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_pdf/ingresos'?>" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-pdf-o"></i>
                                            </span>
                                            <span class="navi-text">PDF</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($data->num_rows() > 0):?>

                    <form class="mb-15">
                        <div class="row mb-6">
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Fecha:</label>
                                <div class="input-daterange input-group" id="kt_datepicker">
                                    <input type="text" class="form-control datatable-input" name="start"
                                        autocomplete="off" placeholder="mm/dd/aaaa" data-col-index="1" readonly>
                                </div>
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Referencia:</label>
                                <input type="text" class="form-control datatable-input" placeholder="VNTAFJ57"
                                    data-col-index="3">
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Destino:</label>
                                <select class=" form-control datatable-input" data-col-index="4">
                                    <option value="">Seleccionar</option>
                                    <option value="Caja chica">Caja chica</option>
                                    <?php $bancos = $this->db->get_where('account_bank', array('bank_id !='=>0))->result_array(); foreach ($bancos as $bc) :?>
                                    <option
                                        value="<?php echo $this->db->get_where('bank', array('bank_id'=>$bc['bank_id']))->row()->name ;?>">
                                        <?php echo $bc['name_account'].'('.$this->db->get_where('bank', array('bank_id'=>$bc['bank_id']))->row()->name.')' ;?>
                                    </option>
                                    <?php endforeach ;?>
                                </select>
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Responsable:</label>
                                <select class=" form-control datatable-input" data-col-index="5">
                                    <option value="">Seleccionar</option>
                                    <?php $admins = $this->db->get_where('admin', array('status'=>1))->result_array(); foreach ($admins as $ad) :?>
                                    <option value="<?php echo $ad['name'].' '.$ad['last_name'];?>"><?php echo $ad['name'].' '.$ad['last_name'];?></option>
                                    <?php endforeach ;?>
                                </select>
                            </div>

                        </div>
                        <div class="row mt-8">
                            <div class="col-lg-12">
                                <button class="btn btn-primary btn-primary--icon" id="kt_search">
                                    <span>
                                        <i class="la la-search"></i>
                                        <span>Buscar</span>
                                    </span>
                                </button>&nbsp;&nbsp;
                                <button class="btn btn-secondary btn-secondary--icon" id="kt_reset">
                                    <span>
                                        <i class="la la-close"></i>
                                        <span>Limpiar</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable no-footer dtr-inline" id="kt_datatable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Monto</th>
                                    <th>Referencia</th>
                                    <th>Destino</th>
                                    <th>Responsable</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $n = 1;  foreach($data->result_array() as $income):
                                    ?>

                                <tr>
                                    <td><?php echo $n++;?></td>
                                    <td><b> <?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d-m-Y", strtotime( $income['date'] ));				
                                        $Mes_Anyo = strftime("%m/%d/%Y", strtotime($Nueva_Fecha)); 
                                        echo $Mes_Anyo;?></b></td>
                                    <td><span
                                            class="text-warning font-weight-bolder"><?php echo $moneda.number_format($income['amount'],2,'.',',');?></span>
                                    </td>
                                    <td><span
                                            class="label label-lg font-weight-bold label-light-info label-inline"><?php echo $income['sale_ref'];?></span>
                                    </td>
                                    <td><span
                                            class="label label-lg font-weight-bold label-light-success label-inline"><?php if( $income['origin'] == 0 ){echo 'Caja chica';}else{ echo $this->db->get_where('bank', array('bank_id'=>$income['origin']))->row()->name;};?></span>
                                    </td>
                                    <td><b><?php echo $this->crud_model->getName('admin', $income['responsable']);?></b></td>
                                    <td>
                                        <div class="d-flex flex-shrink-0">
                                            <a href="<?php echo base_url().'admin/detalles_ingreso/'.$income['income_id'];?>"
                                                class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                                                data-toggle="tooltip" title="" data-original-title="Detalles">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10">
                                                            </circle>
                                                            <rect fill="#000000" x="11" y="10" width="2" height="7"
                                                                rx="1"></rect>
                                                            <rect fill="#000000" x="11" y="7" width="2" height="2"
                                                                rx="1"></rect>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            &nbsp;
                                            <a href="<?php echo base_url().'admin/export_pdf/ingreso/'.$income['income_id'];?>"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1"
                                                data-toggle="tooltip" title="" data-original-title="Imprimir">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24"></rect>
                                                            <path
                                                                d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z"
                                                                fill="#000000"></path>
                                                            <rect fill="#000000" opacity="0.3" x="8" y="2" width="8"
                                                                height="2" rx="1"></rect>
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="card-body"
                        style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
                        <center>
                            <h3>Sin datos</h3><br>
                            <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:25%">
                        </center>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModalSizeLg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-dialog  modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar nuevo empleado</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form">
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
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Nombres <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="Text input with checkbox" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Apellidos <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="Text input with checkbox" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Fecha de nacimiento <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="date" class="form-control" aria-label="Text input with checkbox" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Fecha de contratación</label>
                                <div class="input-group">
                                    <input type="date" class="form-control" aria-label="Text input with checkbox" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Celular</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="Text input with checkbox" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>CUI:</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" aria-label="Text input with checkbox" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Puesto <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control" disabled>
                                        <option value="">Administrador</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Salario</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="Text input with checkbox" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Dirección</label>
                                <div class="input-group">
                                    <textarea class="form-control" aria-label="Text input with checkbox"></textarea>
                                </div>
                            </div>
                            <span class="text-danger"><b>* Las credenciales de acceso se envirán al correo que
                                    ingresaste.</b></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold"
                    data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary font-weight-bold">Continuar</button>
            </div>
        </div>
    </div>
</div>