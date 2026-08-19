<?php $branch_id = $this->session->userdata('branch_id'); $user_id = $this->session->userdata('login_user_id');?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">
                            <?php if($ID == $user_id) echo 'Tu actividad'; else echo 'Actividad de '.$this->crud_model->getName('admin', $ID);?>
                            <span class="d-block text-muted pt-2 font-size-sm">Gestiona la actividad dentro de
                                <b>MSBox</b>, recuerda que la actividad no puede eliminarse.</span>
                        </h3>
                    </div>
                    <form class="form-inline" method="post" action="<?php echo base_url().'admin/actividad/'.$ID;?>">
                        <div class="card-toolbar">
                            <div class="col-md-4 my-2 my-md-0">
                                <label><strong>Buscar</strong></label>
                                <div class="input-icon">
                                    <input type="date" class="form-control" placeholder="Buscar..." id="name"
                                        onchange="this.form.submit()" name="date" <?php if($date != ''){?>
                                        value="<?php echo $date;?>" <?php } ?> />
                                    <span><i class="flaticon2-search-1 text-muted"></i></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <?php if($data->num_rows() > 0):?>
                    <div class="timeline timeline-6 mt-3">
                        <div class="dropdown dropdown-inline mr-2 mb-5">
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
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-left">
                                <ul class="navi flex-column navi-hover py-2">
                                    <li
                                        class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                        Exportar como:</li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_excel/actividades/'. $ID .'/'.$date?>"
                                            class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-excel-o"></i>
                                            </span>
                                            <span class="navi-text">Excel</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_pdf/actividades/'.$ID.'/'.$date?>"
                                            class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-pdf-o"></i>
                                            </span>
                                            <span class="navi-text">PDF</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <?php foreach ($data->result_array() as $row):?>
                            <div class="col col-xl-2 col-lg-2 col-md-4 col-sm-4 col-4">
                                <p class="timeline-label text-dark-75 font-size-lg">
                                    <b><?php setlocale(LC_TIME, "spanish"); $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'])); $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); echo $Mes_Anyo; ?></b>
                                </p>
                            </div>
                            <div class="timeline-badge">
                                <i class="fa fa-genderless text-success icon-xl"></i>
                            </div>
                            <div class="col col-xl-9 col-lg-9 col-md-12 col-sm-12 col12">
                                <p class="font-weight-mormal font-size-lg timeline-content text-muted pl-3">
                                    <?php echo $row['message'];?>
                                </p>
                            </div>
                            <?php endforeach;?>
                        </div>
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