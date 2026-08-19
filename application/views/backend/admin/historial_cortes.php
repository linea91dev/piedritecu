<?php $moneda = $this->crud_model->get_info("moneda");?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="col-sm-12 alert alert-blue">
                <span class="d-block pt-2 font-size-sm">Puedes ver el historial los cortes de caja, seleccionando los rangos de fecha respectivos y presionando el botón verde de buscar.
                </span>
            </div>
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="row">
                        <div class="col-sm-12 card-title">
                            <h3 class="card-label">Historial de Cortes de Caja</h3>
                        </div>

                    </div>
                    <div class="card-toolbar">
                        <?php if($data->num_rows() > 0 && ($user_type == 1 || $permisos['historial_caja'] == 1)): ?>
                        <div class="dropdown dropdown-inline mr-2">
                            <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="svg-icon svg-icon-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24" />
                                            <path d="M3,16 L5,16 C5.55228475,16 6,15.5522847 6,15 C6,14.4477153 5.55228475,14 5,14 L3,14 L3,12 L5,12 C5.55228475,12 6,11.5522847 6,11 C6,10.4477153 5.55228475,10 5,10 L3,10 L3,8 L5,8 C5.55228475,8 6,7.55228475 6,7 C6,6.44771525 5.55228475,6 5,6 L3,6 L3,4 C3,3.44771525 3.44771525,3 4,3 L10,3 C10.5522847,3 11,3.44771525 11,4 L11,19 C11,19.5522847 10.5522847,20 10,20 L4,20 C3.44771525,20 3,19.5522847 3,19 L3,16 Z" fill="#000000" opacity="0.3" />
                                            <path d="M16,3 L19,3 C20.1045695,3 21,3.8954305 21,5 L21,15.2485298 C21,15.7329761 20.8241635,16.200956 20.5051534,16.565539 L17.8762883,19.5699562 C17.6944473,19.7777745 17.378566,19.7988332 17.1707477,19.6169922 C17.1540423,19.602375 17.1383289,19.5866616 17.1237117,19.5699562 L14.4948466,16.565539 C14.1758365,16.200956 14,15.7329761 14,15.2485298 L14,5 C14,3.8954305 14.8954305,3 16,3 Z" fill="#000000" />
                                        </g>
                                    </svg>
                                </span>Exportar
                            </button>
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                <ul class="navi flex-column navi-hover py-2">
                                    <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                        Exportar como:</li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url();?>admin/export_excel/cash_history" class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-excel-o"></i>
                                            </span>
                                            <span class="navi-text">Excel</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url();?>admin/export_pdf/cash_history" class="navi-link">
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
                    <form class="form" method="post" action="<?php echo base_url().'admin/historial_cortes/';?>">
                        <div class="card-toolbar">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Desde:</label>
                                        <input type="text" class="form-control" name="date_initial" id="kt_datepicker" <?php if($date_initial != ''){?> placeholder="mm/dd/aaaa" <?php } ?> readonly />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Hasta:</label>
                                        <input type="tex" class="form-control" name="date_final" id="kt_datepicker_1" <?php if($date_final != ''){?> placeholder="mm/dd/aaaa" <?php } ?> readonly />
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div>
                                        <button type="submit" class="btn btn-lg btn-icon btn-success btn-circle btn-hover-success" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window" title="Buscar">
                                            <i class="flaticon-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php if($data->num_rows() > 0): setlocale(LC_TIME, "spanish");?>
                    <div class="timeline timeline-6 mt-3" id='actividad'>
                        <p>Cortes de caja desde fecha
                            <u><?php $date_initial = date("d-m-Y", strtotime($date_initial)); $initial_esp = strftime("%d de %B de %Y", strtotime($date_initial)); echo $initial_esp; ?></u>
                            hasta fecha
                            <u><?php $date_final = date("d-m-Y", strtotime($date_final)); $final_esp = strftime("%d de %B de %Y", strtotime($date_final)); echo $final_esp; ?></u>
                        </p>
                        <?php foreach ($data->result_array() as $row):?>
                        <div class="row">
                            <div class="col col-xl-12 col-lg-10 col-md-12 col-sm-12 col-12W">
                                <p class="font-weight-mormal font-size-lg timeline-content text-muted pl-3">
                                    <i class="fa fa-genderless text-info icon-xl"></i>
                                    Corte de caja realizado por: <b><?php echo $this->crud_model->getName('admin', $row['user_id']);?></b>
                                    en la sucursal: <b><?php echo $this->db->get_where('branch', array('branch_id' => $row['branch_id']))->row()->name;?></b>
                                    en fecha: <b><?php $Nueva_Fecha = date("d-m-Y", strtotime($row['date_close'])); $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha));  echo $Mes_Anyo.' '.date('h:i A', strtotime($row['time_close'])); ?></b>,
                                    con un monto inicial de <b><?php echo $moneda.number_format($row['amount_initial'],2,'.',',')?></b>
                                    y un monto final de <b><?php echo $moneda.number_format($row['amount_final'],2,'.',',')?></b>,
                                    con anotacion: <?php echo $row['notes'];?>
                                </p>
                            </div>
                        </div><br>
                        <?php endforeach;?>
                    </div>
                    <?php else: ?>
                    <div class="card-body" style="/* padding: 2rem 2.25rem; */padding-top: 120px;padding-bottom: 120px;">
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
