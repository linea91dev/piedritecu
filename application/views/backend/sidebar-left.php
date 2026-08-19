<div class="sidebar sidebar-left d-flex flex-row-auto flex-column <?php if($page_name == 'empleados' ||  $page_name == 'codigos' ||  $page_name == 'roles' || $page_name == 'admins' || $page_name == 'inventario' || $page_name == 'inventario_marca' || $page_name == 'ventas' || $page_name == 'nueva_cotizacion' || $page_name == 'sale_details' || $page_name == 'planillas_pay' || $page_name == 'configuracion' || $page_name == 'nuevo_cambio' || $page_name == 'anulacion'){echo 'sidebar-oculto';}?>" id="kt_sidebar">
    <div class="sidebar-content flex-column-fluid pb-10 pt-lg-12 pb-5 px-5 px-lg-10">
        <?php if($page_name != 'nueva_venta' &&  $page_name != 'nueva_venta_c' && $page_name != 'nueva_solicitud' && $page_name != 'nueva_compra' && $page_name != 'nueva_compra_s' ):?>
        <div class="tab-content">
            <div class="tab-pane fade pt-5 pr-5 mr-n0 scroll active show" id="kt_quick_panel_logs" role="tabpanel" style="overflow: auto;">
                <div class="mb-15">
                    <div class="d-flex align-items-center flex-wrap mb-5">
                        <div class="flex-grow-1 bg-danger p-8 rounded-xl flex-grow-1 bgi-no-repeat" style="background-position: calc(100% + 0.5rem) bottom; background-size: auto 80%; background-image: url(<?php echo base_url();?>public/assets/media/svg/humans/custom-3.svg)">
                            <h4 class="text-inverse-danger mt-2 font-weight-bolder">¡Hola
                                <?php echo $this->crud_model->getSingleName('admin', $this->session->userdata('login_user_id'));?>!
                            </h4>
                            <p style='max-width: 200px;' class="text-inverse-danger my-6">Tu última visita al sistema
                                fue
                                <br><?php echo $this->db->get_where('admin', array('admin_id'=>$this->session->userdata('login_user_id')))->row()->last_info;?>
                            </p>
                            <a href="<?php echo base_url();?>admin/actividad/" class="btn btn-warning font-weight-bold py-2 px-6">Ver tu actividad</a>
                        </div>
                    </div>
                </div>
                <div class="mb-5">
                    <h5 class="font-weight-bold mb-5">Consultar</h5>
                    <div class="form-group">
                        <input type="text" class='form-control' id='name' onkeyup="find()" placeholder='Buscar' autocomplete="off">
                    </div>

                    <div class="table-responsive">
                        <table class="table table-padded">
                            <tbody id="resultados" class="mostly-customized-scrollbars col-sm-12" style="background-color: #fcfcfc; margin-bottom: 0px !important;">
                            </tbody>
                        </table>
                    </div>

                    <div id="cuadro1">

                        <div class="row">
                            <?php if($user_type == 1 || $permisos['crear_anulaciones'] == 1):?>
                            <div class="col-sm-12 col-lg-6 mb-5">
                                <a href="<?php echo base_url();?>admin/anulacion/<?php if ($code) echo $code; ?>" class="<?php echo $page_name == "anulacion"?"active":""; ?> btn btn-block btn-light btn-hover-primary text-dark-50 text-center py-10 px-5">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path d="M12,22 C6.4771525,22 2,17.5228475 2,12 C2,6.4771525 6.4771525,2 12,2 C17.5228475,2 22,6.4771525 22,12 C22,17.5228475 17.5228475,22 12,22 Z M12,20 C16.418278,20 20,16.418278 20,12 C20,7.581722 16.418278,4 12,4 C7.581722,4 4,7.581722 4,12 C4,16.418278 7.581722,20 12,20 Z M19.0710678,4.92893219 L19.0710678,4.92893219 C19.4615921,5.31945648 19.4615921,5.95262146 19.0710678,6.34314575 L6.34314575,19.0710678 C5.95262146,19.4615921 5.31945648,19.4615921 4.92893219,19.0710678 L4.92893219,19.0710678 C4.5384079,18.6805435 4.5384079,18.0473785 4.92893219,17.6568542 L17.6568542,4.92893219 C18.0473785,4.5384079 18.6805435,4.5384079 19.0710678,4.92893219 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="d-block font-weight-bold font-size-h6 mt-2">Nueva anulación</span>
                                </a>
                            </div>
                            <?php endif; if($user_type == 1 || $permisos['crear_egresos'] == 1):?>
                            <div class="col-sm-12 col-lg-6 mb-5">
                                <a href="javascript:void(0);" class="btn btn-block btn-light btn-hover-primary text-dark-50 text-center py-10 px-5" data-toggle="modal" data-target="#modalEgreso">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <rect fill="#000000" opacity="0.3" x="11.5" y="2" width="2" height="4" rx="1" />
                                                <rect fill="#000000" opacity="0.3" x="11.5" y="16" width="2" height="5" rx="1" />
                                                <path d="M15.493,8.044 C15.2143319,7.68933156 14.8501689,7.40750104 14.4005,7.1985 C13.9508311,6.98949895 13.5170021,6.885 13.099,6.885 C12.8836656,6.885 12.6651678,6.90399981 12.4435,6.942 C12.2218322,6.98000019 12.0223342,7.05283279 11.845,7.1605 C11.6676658,7.2681672 11.5188339,7.40749914 11.3985,7.5785 C11.2781661,7.74950085 11.218,7.96799867 11.218,8.234 C11.218,8.46200114 11.2654995,8.65199924 11.3605,8.804 C11.4555005,8.95600076 11.5948324,9.08899943 11.7785,9.203 C11.9621676,9.31700057 12.1806654,9.42149952 12.434,9.5165 C12.6873346,9.61150047 12.9723317,9.70966616 13.289,9.811 C13.7450023,9.96300076 14.2199975,10.1308324 14.714,10.3145 C15.2080025,10.4981676 15.6576646,10.7419985 16.063,11.046 C16.4683354,11.3500015 16.8039987,11.7268311 17.07,12.1765 C17.3360013,12.6261689 17.469,13.1866633 17.469,13.858 C17.469,14.6306705 17.3265014,15.2988305 17.0415,15.8625 C16.7564986,16.4261695 16.3733357,16.8916648 15.892,17.259 C15.4106643,17.6263352 14.8596698,17.8986658 14.239,18.076 C13.6183302,18.2533342 12.97867,18.342 12.32,18.342 C11.3573285,18.342 10.4263378,18.1741683 9.527,17.8385 C8.62766217,17.5028317 7.88033631,17.0246698 7.285,16.404 L9.413,14.238 C9.74233498,14.6433354 10.176164,14.9821653 10.7145,15.2545 C11.252836,15.5268347 11.7879973,15.663 12.32,15.663 C12.5606679,15.663 12.7949989,15.6376669 13.023,15.587 C13.2510011,15.5363331 13.4504991,15.4540006 13.6215,15.34 C13.7925009,15.2259994 13.9286662,15.0740009 14.03,14.884 C14.1313338,14.693999 14.182,14.4660013 14.182,14.2 C14.182,13.9466654 14.1186673,13.7313342 13.992,13.554 C13.8653327,13.3766658 13.6848345,13.2151674 13.4505,13.0695 C13.2161655,12.9238326 12.9248351,12.7908339 12.5765,12.6705 C12.2281649,12.5501661 11.8323355,12.420334 11.389,12.281 C10.9583312,12.141666 10.5371687,11.9770009 10.1255,11.787 C9.71383127,11.596999 9.34650161,11.3531682 9.0235,11.0555 C8.70049838,10.7578318 8.44083431,10.3968355 8.2445,9.9725 C8.04816568,9.54816454 7.95,9.03200304 7.95,8.424 C7.95,7.67666293 8.10199848,7.03700266 8.406,6.505 C8.71000152,5.97299734 9.10899753,5.53600171 9.603,5.194 C10.0970025,4.85199829 10.6543302,4.60183412 11.275,4.4435 C11.8956698,4.28516587 12.5226635,4.206 13.156,4.206 C13.9160038,4.206 14.6918294,4.34533194 15.4835,4.624 C16.2751706,4.90266806 16.9686637,5.31433061 17.564,5.859 L15.493,8.044 Z"
                                                      fill="#000000" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="d-block font-weight-bold font-size-h6 mt-2">Nuevo
                                        gasto</span>
                                </a>
                            </div>
                            <?php endif; if($user_type == 1 || $permisos['crear_solicitud'] == 1):?>
                            <div class="col-sm-12 col-lg-6 mb-5">
                                <a href="<?php echo base_url();?>admin/nueva_solicitud/" class="btn btn-block btn-light btn-hover-primary text-dark-50 text-center py-10 px-5">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path d="M17,8 C16.4477153,8 16,7.55228475 16,7 C16,6.44771525 16.4477153,6 17,6 L18,6 C20.209139,6 22,7.790861 22,10 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,9.99305689 C2,7.7839179 3.790861,5.99305689 6,5.99305689 L7.00000482,5.99305689 C7.55228957,5.99305689 8.00000482,6.44077214 8.00000482,6.99305689 C8.00000482,7.54534164 7.55228957,7.99305689 7.00000482,7.99305689 L6,7.99305689 C4.8954305,7.99305689 4,8.88848739 4,9.99305689 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,10 C20,8.8954305 19.1045695,8 18,8 L17,8 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 8.000000) scale(1, -1) rotate(-180.000000) translate(-12.000000, -8.000000) " x="11" y="2" width="2" height="12" rx="1" />
                                                <path d="M12,2.58578644 L14.2928932,0.292893219 C14.6834175,-0.0976310729 15.3165825,-0.0976310729 15.7071068,0.292893219 C16.0976311,0.683417511 16.0976311,1.31658249 15.7071068,1.70710678 L12.7071068,4.70710678 C12.3165825,5.09763107 11.6834175,5.09763107 11.2928932,4.70710678 L8.29289322,1.70710678 C7.90236893,1.31658249 7.90236893,0.683417511 8.29289322,0.292893219 C8.68341751,-0.0976310729 9.31658249,-0.0976310729 9.70710678,0.292893219 L12,2.58578644 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 2.500000) scale(1, -1) translate(-12.000000, -2.500000) " />
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="d-block font-weight-bold font-size-h6 mt-2">Solicitud de compra</span>
                                </a>
                            </div>
                            <?php endif; if($user_type == 1 || $permisos['crear_compras'] == 1):?>
                            <div class="col-sm-12 col-lg-6 mb-5">
                                <a href="<?php echo base_url();?>admin/nueva_compra/" class="btn btn-block btn-light btn-hover-primary text-dark-50 text-center py-10 px-5">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <rect fill="#000000" opacity="0.3" transform="translate(12.000000, 7.000000) rotate(-180.000000) translate(-12.000000, -7.000000) " x="11" y="1" width="2" height="12" rx="1" />
                                                <path d="M17,8 C16.4477153,8 16,7.55228475 16,7 C16,6.44771525 16.4477153,6 17,6 L18,6 C20.209139,6 22,7.790861 22,10 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,9.99305689 C2,7.7839179 3.790861,5.99305689 6,5.99305689 L7.00000482,5.99305689 C7.55228957,5.99305689 8.00000482,6.44077214 8.00000482,6.99305689 C8.00000482,7.54534164 7.55228957,7.99305689 7.00000482,7.99305689 L6,7.99305689 C4.8954305,7.99305689 4,8.88848739 4,9.99305689 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,10 C20,8.8954305 19.1045695,8 18,8 L17,8 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                <path d="M14.2928932,10.2928932 C14.6834175,9.90236893 15.3165825,9.90236893 15.7071068,10.2928932 C16.0976311,10.6834175 16.0976311,11.3165825 15.7071068,11.7071068 L12.7071068,14.7071068 C12.3165825,15.0976311 11.6834175,15.0976311 11.2928932,14.7071068 L8.29289322,11.7071068 C7.90236893,11.3165825 7.90236893,10.6834175 8.29289322,10.2928932 C8.68341751,9.90236893 9.31658249,9.90236893 9.70710678,10.2928932 L12,12.5857864 L14.2928932,10.2928932 Z" fill="#000000" fill-rule="nonzero" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="d-block font-weight-bold font-size-h6 mt-2">Nueva
                                        compra</span>
                                </a>
                            </div>
                            <?php endif; if($user_type == 1 || $permisos['crear_cotizaciones'] == 1):?>
                            <div class="col-sm-12 col-lg-6 mb-5">
                                <a href="<?php echo base_url();?>admin/nueva_cotizacion/" class="<?php echo $page_name == "nueva_cotizacion"?"active":""; ?> btn btn-block btn-light btn-hover-primary text-dark-50 text-center py-10 px-5">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <rect x="0" y="0" width="24" height="24" />
                                                <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3" />
                                                <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000" />
                                                <rect fill="#000000" opacity="0.3" x="10" y="9" width="7" height="2" rx="1" />
                                                <rect fill="#000000" opacity="0.3" x="7" y="9" width="2" height="2" rx="1" />
                                                <rect fill="#000000" opacity="0.3" x="7" y="13" width="2" height="2" rx="1" />
                                                <rect fill="#000000" opacity="0.3" x="10" y="13" width="7" height="2" rx="1" />
                                                <rect fill="#000000" opacity="0.3" x="7" y="17" width="2" height="2" rx="1" />
                                                <rect fill="#000000" opacity="0.3" x="10" y="17" width="7" height="2" rx="1" />
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="d-block font-weight-bold font-size-h6 mt-2">Nueva cotización</span>
                                </a>
                            </div>
                            <?php endif; if($user_type == 1 || $permisos['crear_cambios'] == 1):?>
                            <div class="col-sm-12 col-lg-6 mb-5">
                                <a href="<?php echo base_url();?>admin/nuevo_cambio/<?php if ($code) echo $code; ?>" class="<?php echo $page_name == "nuevo_cambio"?"active":""; ?> btn btn-block btn-light btn-hover-primary text-dark-50 text-center py-10 px-5">
                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                <polygon points="0 0 24 0 24 24 0 24" />
                                                <rect fill="#000000" opacity="0.3" transform="translate(13.000000, 6.000000) rotate(-450.000000) translate(-13.000000, -6.000000) " x="12" y="8.8817842e-16" width="2" height="12" rx="1" />
                                                <path d="M9.79289322,3.79289322 C10.1834175,3.40236893 10.8165825,3.40236893 11.2071068,3.79289322 C11.5976311,4.18341751 11.5976311,4.81658249 11.2071068,5.20710678 L8.20710678,8.20710678 C7.81658249,8.59763107 7.18341751,8.59763107 6.79289322,8.20710678 L3.79289322,5.20710678 C3.40236893,4.81658249 3.40236893,4.18341751 3.79289322,3.79289322 C4.18341751,3.40236893 4.81658249,3.40236893 5.20710678,3.79289322 L7.5,6.08578644 L9.79289322,3.79289322 Z" fill="#000000" fill-rule="nonzero" transform="translate(7.500000, 6.000000) rotate(-270.000000) translate(-7.500000, -6.000000) " />
                                                <rect fill="#000000" opacity="0.3" transform="translate(11.000000, 18.000000) scale(1, -1) rotate(90.000000) translate(-11.000000, -18.000000) " x="10" y="12" width="2" height="12" rx="1" />
                                                <path d="M18.7928932,15.7928932 C19.1834175,15.4023689 19.8165825,15.4023689 20.2071068,15.7928932 C20.5976311,16.1834175 20.5976311,16.8165825 20.2071068,17.2071068 L17.2071068,20.2071068 C16.8165825,20.5976311 16.1834175,20.5976311 15.7928932,20.2071068 L12.7928932,17.2071068 C12.4023689,16.8165825 12.4023689,16.1834175 12.7928932,15.7928932 C13.1834175,15.4023689 13.8165825,15.4023689 14.2071068,15.7928932 L16.5,18.0857864 L18.7928932,15.7928932 Z" fill="#000000" fill-rule="nonzero" transform="translate(16.500000, 18.000000) scale(1, -1) rotate(270.000000) translate(-16.500000, -18.000000) " />
                                            </g>
                                        </svg>
                                    </span>
                                    <span class="d-block font-weight-bold font-size-h6 mt-2">Registrar cambio</span>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <?php elseif($page_name == 'anulacion' || $page_name == 'sale_details'):?>
        <div class="d-flex flex-column">
            <div class="flex-grow-1 bg-danger p-8 rounded-xl flex-grow-1 bgi-no-repeat" style="background-position: calc(100% + 0.5rem) bottom; background-size: auto 70%; background-image: url(<?php echo base_url();?>public/assets/media/svg/humans/custom-3.svg)">
                <h4 class="text-inverse-danger mt-2 font-weight-bolder">¡Hola
                    <?php echo $this->crud_model->getName('admin', $this->session->userdata('login_user_id'));?>!</h4>
                <p class="text-inverse-danger my-6">Tu última visita al sistema fue
                    <br><?php echo $this->db->get_where('admin', array('admin_id'=>$this->session->userdata('login_user_id')))->row()->current_info;?>
                </p>
                <a href="<?php echo base_url();?>admin/actividad/" class="btn btn-warning font-weight-bold py-2 px-6">Ver tu
                    actividad</a>
            </div>
        </div>
        <br>
        <div class="row">
            <?php if($sale_id != "" && $sale->credito == 1): ?>
            <div class="col-sm-12"><br>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <div class="form-group">
                                <label>Venta al crédito</label>
                                <span class="switch switch-sm">
                                    <label>
                                        <input type="checkbox" <?php echo $sale->credito == 1 ? "checked":""; ?> name="select" disabled />
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Requiere envío</label>
                            <span class="switch switch-sm">
                                <label>
                                    <input type="checkbox" <?php echo $sale->delivery == 1? "checked":""; ?> name="select" disabled />
                                    <span></span>
                                </label>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label><b>Tipo de cliente:</b></label>
                    <div class="radio-inline">
                        <label class="radio">
                            <input type="radio" name="radios3" <?php echo $sale->my == 2? "checked":""; ?>>
                            <span></span>Mayorista</label>
                        <label class="radio">
                            <input type="radio" name="radios3" <?php echo $sale->my == ""? "checked":""; ?>>
                            <span></span>Minorista</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <br>
                <h4>Pagos realizados:</h4>
                <div class="table-responsive">
                    <table class="table table-bordered" style="border-radius:15px!important">
                        <thead>
                            <tr>
                                <th class="pl-0 font-weight-bold text-muted text-uppercase">&nbsp;Fecha</th>
                                <th class="text-left font-weight-bold text-muted text-uppercase">Monto</th>
                                <th class="text-left font-weight-bold text-muted text-uppercase">Método</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            
                            $pays = $this->db->get_where('credit_details',array('sales_id'=>$sale->sales_id))->result_array();
                            $total_pay = 0;
                            foreach($pays as $pay):
                                $total_pay += $pay['amount'];
                            ?>
                            <tr>
                                <td style="padding:6px!important;" class="pl-0"><?php echo $pay['date'];?></td>
                                <td style="padding:2px!important;" class="text-info font-weight-bold pr-0 text-left">
                                    <?php echo $moneda.' '.number_format( $pay['amount'],2,'.',',');?></td>
                                <td style="padding:2px!important;" class="font-weight-bold"><?php echo $pay['method'];?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                <div class="rounded d-flex  justify-content-between position-relative ml-auto p-7">
                    <div class="font-weight-boldest font-size-h5">TOTAL</div>
                    <div class="text-right d-flex flex-column">
                        <span class="font-weight-boldest font-size-h3 line-height-sm" id='total'><?php echo $moneda.' '.number_format( $total_pay,2,'.',',');?></span>
                        <input type="hidden" name="ttl" id='ttl'>
                    </div>
                </div>
            </div>
            <form action="<?php echo base_url();?>admin/ventas/pay/<?php echo $sale->code;?>" method="post" enctype="multipart/form-data" class="form">
                <div class="row">
                    <div class="col-sm-12">
                        <span><b>Agregar pago:</b></span>
                        <div class="border-bottom"></div><br>
                    </div>
                    <div class="col-lg-12 col-xxl-12">
                        <div class="form-group">
                            <label>Método:</label>
                            <select class="form-control" name="metodo" required>
                                <option value='Efectivo' selected>Efectivo</option>
                                <option value='Tarjeta'>Tarjeta de crédito/débito</option>
                                <option value='Transferencia'>Transferencia</option>
                                <option value='Cheque'>Cheque</option>
                                <option value='Deposito'>Depósito</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-12 col-xxl-12">
                        <div class="form-group">
                            <label>Monto:</label>
                            <input type="number" value="" min='0' step="any" required class="form-control" name='pago' required />
                        </div>
                    </div>
                    <div class="col-lg-12 col-xxl-12 ">
                        <div class="form-group">
                            <label>Total:</label><br>
                            <span class="font-weight-boldest font-size-h5 line-height-sm"><?php echo $moneda.number_format($sale->total,2,'.',',');?></span>
                        </div>
                        <div class="form-group">
                            <button type="submit" style='float: right;' class="btn btn-primary font-weight-bold">Guardar</button>
                        </div>
                    </div>
                </div>
            </form>

            <?php endif;?>
            <?php elseif($page_name == 'nuevo_cambio'):?>
            <div class="d-flex flex-column">
                <div class="flex-grow-1 bg-danger p-8 rounded-xl flex-grow-1 bgi-no-repeat" style="background-position: calc(100% + 0.5rem) bottom; background-size: auto 70%; background-image: url(<?php echo base_url();?>public/assets/media/svg/humans/custom-3.svg)">
                    <h4 class="text-inverse-danger mt-2 font-weight-bolder">¡Hola
                        <?php echo $this->crud_model->getName('admin', $this->session->userdata('login_user_id'));?>!
                    </h4>
                    <p class="text-inverse-danger my-6">Tu última visita al sistema fue
                        <br><?php echo $this->db->get_where('admin', array('admin_id'=>$this->session->userdata('login_user_id')))->row()->current_info;?>
                    </p>
                    <a href="<?php echo base_url();?>admin/actividad/" class="btn btn-warning font-weight-bold py-2 px-6">Ver tu
                        actividad</a>
                </div>
            </div>
            <br>
            <div class="row">
                <?php if ($sale->credito == 1):?>
                <div class="col-sm-12"><br>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label>Venta al crédito</label>
                                    <span class="switch switch-sm">
                                        <label>
                                            <input disabled type="checkbox" <?php echo $sale->delivery == 1? "checked":""; ?> name="select" />
                                            <span></span>
                                        </label>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Requiere envío</label>
                                <span class="switch switch-sm">
                                    <label>
                                        <input disabled type="checkbox" <?php echo $sale->credito == 1? "checked":""; ?> checked name="select" />
                                        <span></span>
                                    </label>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="form-group">
                        <label><b>Tipo de cliente:</b></label>
                        <div class="radio-inline">
                            <label class="radio">
                                <input type="radio" name="radios3">
                                <span></span>Mayorista</label>
                            <label class="radio">
                                <input type="radio" checked="checked" name="radios3">
                                <span></span>Minorista</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <br>
                    <h4>Pagos realizados:</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered" style="border-radius:15px!important">
                            <thead>
                                <tr>
                                    <th class="pl-0 font-weight-bold text-muted text-uppercase">&nbsp;Fecha</th>
                                    <th class="text-left font-weight-bold text-muted text-uppercase">Monto</th>
                                    <th class="text-left font-weight-bold text-muted text-uppercase">Método</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="padding:6px!important;" class="pl-0">12 Dic, 2021</td>
                                    <td style="padding:2px!important;" class="text-info font-weight-bold pr-0 text-left">
                                        Q15,200.00</td>
                                    <td style="padding:2px!important;" class="font-weight-bold">Transferencia</td>
                                </tr>
                                <tr class="border-bottom-0">
                                    <td style="padding:6px!important;" class="pl-0">12 Dic, 2021</td>
                                    <td style="padding:2px!important;" class="text-info font-weight-bold border-top-0 pr-0 py-4 text-left">Q2,500</td>
                                    <td style="padding:2px!important;" class="font-weight-bold" class="border-top-0 py-4">
                                        Transferencia</td>
                                </tr>
                                <tr class="border-bottom-0">
                                    <td style="padding:6px!important;" class="pl-0">12 Dic, 2021</td>
                                    <td style="padding:2px!important;" class="text-info font-weight-bold border-top-0 pr-0 py-4 text-left">Q500</td>
                                    <td style="padding:2px!important;" class="font-weight-bold" class="border-top-0 py-4">
                                        Transferencia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="bg-primary rounded d-flex  justify-content-between text-white position-relative ml-auto p-7">
                        <div class="position-absolute opacity-30 top-0 right-0">
                            <span class="svg-icon svg-icon-2x svg-logo-white svg-icon-flip">
                                <svg xmlns="http://www.w3.org/2000/svg" width="176" height="165" viewBox="0 0 176 165" fill="none">
                                    <g clip-path="url(#clip0)">
                                        <path d="M-10.001 135.168C-10.001 151.643 3.87924 165.001 20.9985 165.001C38.1196 165.001 51.998 151.643 51.998 135.168C51.998 118.691 38.1196 105.335 20.9985 105.335C3.87924 105.335 -10.001 118.691 -10.001 135.168Z" fill="#AD84FF"></path>
                                        <path d="M28.749 64.3117C28.749 78.7296 40.8927 90.4163 55.8745 90.4163C70.8563 90.4163 83 78.7296 83 64.3117C83 49.8954 70.8563 38.207 55.8745 38.207C40.8927 38.207 28.749 49.8954 28.749 64.3117Z" fill="#AD84FF"></path>
                                        <path d="M82.9996 120.249C82.9996 144.964 103.819 165 129.501 165C155.181 165 176 144.964 176 120.249C176 95.5342 155.181 75.5 129.501 75.5C103.819 75.5 82.9996 95.5342 82.9996 120.249Z" fill="#AD84FF"></path>
                                        <path d="M98.4976 23.2928C98.4976 43.8887 115.848 60.5856 137.249 60.5856C158.65 60.5856 176 43.8887 176 23.2928C176 2.69692 158.65 -14 137.249 -14C115.848 -14 98.4976 2.69692 98.4976 23.2928Z" fill="#AD84FF"></path>
                                        <path d="M-10.0011 8.37466C-10.0011 20.7322 0.409554 30.7493 13.2503 30.7493C26.0911 30.7493 36.5 20.7322 36.5 8.37466C36.5 -3.98287 26.0911 -14 13.2503 -14C0.409554 -14 -10.0011 -3.98287 -10.0011 8.37466Z" fill="#AD84FF"></path>
                                        <path d="M-2.24881 82.9565C-2.24881 87.0757 1.22081 90.4147 5.50108 90.4147C9.78135 90.4147 13.251 87.0757 13.251 82.9565C13.251 78.839 9.78135 75.5 5.50108 75.5C1.22081 75.5 -2.24881 78.839 -2.24881 82.9565Z" fill="#AD84FF"></path>
                                        <path d="M55.8744 12.1044C55.8744 18.2841 61.0788 23.2926 67.5001 23.2926C73.9196 23.2926 79.124 18.2841 79.124 12.1044C79.124 5.92653 73.9196 0.917969 67.5001 0.917969C61.0788 0.917969 55.8744 5.92653 55.8744 12.1044Z" fill="#AD84FF"></path>
                                    </g>
                                </svg>
                            </span>
                        </div>
                        <div class="font-weight-boldest font-size-h5">TOTAL PAGOS</div>
                        <div class="text-right d-flex flex-column">
                            <span class="font-weight-boldest font-size-h3 line-height-sm">Q20,600.00</span>
                        </div>
                    </div>
                </div>
                <?php endif;?>
                <?php elseif($page_name == 'nueva_solicitud'):?>
                <div class="row">
                    <div class="col-sm-12">
                        <h2>Detalles de la solicitud</h2>
                        <div class="border-bottom"></div>
                        <br>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><b>Responsable:</b></label>
                            <select class="form-control" name='responsable' required>
                                <?php $admin = $this->crud_model->get_admin(); foreach($admin as $ad): ?>
                                <?php if($ad['type'] == 1 && $ad['status'] ==1): ?>
                                <option value='<?php echo  $ad['admin_id'] ;?>' <?php if($ad['admin_id'] == $this->session->userdata('login_user_id')) echo 'selected';?>>
                                    <?php echo $this->crud_model->getName('admin', $ad['admin_id']) ;?>
                                </option>
                                <?php endif; endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><b>Fecha de entrega:</b></label>
                            <input type="date" class="form-control" value='<?php echo date('Y-m-d');?>' name='date'>
                            <small>Se le indicará al proveedor cuando quieres recibir los productos</small>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="alert alert-warning">
                            <b>NOTA:</b> Se enviará un SMS, WhatsApp o correo electrónico a tu proveedor con los
                            productos y detalles completos de la solicitud.
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Lugar de entrega:</label>
                            <select class="form-control" name='location' required>
                                <?php $branch = $this->crud_model->get_branch(); foreach($branch->result_array() as $br):?>
                                <option value='<?php echo $br['branch_id'];?>'>
                                    <?php echo $br['name'] ;?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Destino</label>
                            <div class="checkbox-inline text-center">
                                <label class="checkbox checkbox-rounded">
                                    <input type="radio" checked name="destiny" value='1'>
                                    <span></span>Inventario
                                </label>
                                <label class="checkbox checkbox-rounded">
                                    <input type="radio" name="destiny" value='0'>
                                    <span></span>Bodega
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Detalles: <small>(Serán visibles para el proveedor)</small></label>
                            <textarea placeholder="Detalles adicionales (Ej: Lugar específico de Entrega)" class="form-control" name='details'></textarea>
                        </div>
                        <input type="hidden" name="ttl" value="0">
                    </div>
                    <div class="col-md-12">
                        <br>
                        <a href="javascript:;" hidden onclick="showModalCodigos('<?php echo base_url();?>modal/popup/modalCodigos/<?php echo $page_name;?>');" class="btn btn-success font-weight-bolder" type='succsess' disabled id='confirm' syle='float: right;'>
                            Confirmar solicitud
                        </a>
                    </div>
                </div>
                </form>


                <?php elseif($page_name == 'nueva_compra_s'):?>

                <div class="row">
                    <div class="col-sm-12">
                        <h2>Detalles de la Compra</h2>
                        <div class="border-bottom"></div>
                        <br>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><b>Fecha de entrega:</b> <?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d-m-Y", strtotime( $data->row()->date ));				
                                        $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); 
                                        echo $Mes_Anyo;?></label>
                            <input type="hidden" name="date" value='<?php echo $data->row()->date;?>'>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Responsable:</label>
                            <input readonly class="form-control" name='responsable' value='<?php echo $this->crud_model->getName('admin',$data->row()->responsable) ;?>'>
                            <input type="hidden" name="responsable" value='<?php echo $data->row()->responsable;?>'>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Lugar de entrega:</label>
                                    <input readonly class="form-control" value='<?php echo $this->db->get_where('branch',array('branch_id'=>$data->row()->location))->row()->name ;?>'>
                                    <input type="hidden" name="location" value='<?php echo $data->row()->location;?>'>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Destino</label>
                                    <input readonly class="form-control" value='<?php echo ($data->row()->destiny == 1) ? ' Inventario ' : 'Bodega' ;?>'>
                                    <input type="hidden" name="destiny" value='<?php echo $data->row()->destiny ;?>'>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Detalles:</label>
                            <textarea readonly class="form-control" name='details'><?php echo ($data->row()->details != '') ? $data->row()->details : "Ferretería La Estrella no agregó notas adicionales a esta solicitud." ;?></textarea>
                        </div>
                        <div class="form-group">
                            <label> Nota del proveedor: </label>
                            <textarea readonly class="form-control" name='note'><?php echo ($data->row()->note != '') ? $data->row()->note : "Sin ninguna observación." ;?></textarea>
                        </div>
                        <div class="form-group">
                            <label><b>Factura:</b></label>
                            <div class="input-group">
                                <input class="uppy-FileInput-input uppy-input-control" type="file" name="factura_img" accept="image/*" id="kt_uppy_5_input_control" style='display:none' onchange="onLoadImage_s(event.target.files)">
                                <label class="uppy-input-label btn btn-light-primary btn-sm btn-bold" for="kt_uppy_5_input_control">Subir factura</label>
                            </div>
                            <label>Archivo seleccionado: <b><span id="imgName_s">Niguno</span></b></label>
                        </div>
                        <div class="form-group">
                            <label><b>Entidad a quien compró o pagó:</b> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Ej: <?php echo $this->crud_model->getNameSistema();?>" aria-label="Text input with checkbox" name="provider" readonly value='<?php echo $this->db->get_where('provider', array('provider_id'=>$data->row()->provider))->row()->name;?>' />
                            </div>
                        </div>
                        <div class="form-group">
                            <label><b>Origen:</b> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-control" name="origin" required="true" onchange="verificar(this.value)">
                                    <option>Seleccionar</option>
                                    <option value="0">(Caja General) Efectivo</option>
                                    <?php $cuentas = $this->crud_model->get_accounts_to_pay();
                                            foreach($cuentas->result_array() as $cts):?>
                                    <option value="<?php echo $cts['account_bank_id'];?>">
                                        <?php if($cts['bank_id'] > 0) echo '('.$this->db->get_where('bank', array('bank_id' => $cts['bank_id']))->row()->name.') - '; echo $cts['name_account'];?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <span class="text-danger" id="msg_error"></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="bg-primary rounded d-flex  justify-content-between text-white position-relative ml-auto p-7">
                            <div class="position-absolute opacity-30 top-0 right-0">
                                <span class="svg-icon svg-icon-2x svg-logo-white svg-icon-flip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="176" height="165" viewBox="0 0 176 165" fill="none">
                                        <g clip-path="url(#clip0)">
                                            <path d="M-10.001 135.168C-10.001 151.643 3.87924 165.001 20.9985 165.001C38.1196 165.001 51.998 151.643 51.998 135.168C51.998 118.691 38.1196 105.335 20.9985 105.335C3.87924 105.335 -10.001 118.691 -10.001 135.168Z" fill="#AD84FF"></path>
                                            <path d="M28.749 64.3117C28.749 78.7296 40.8927 90.4163 55.8745 90.4163C70.8563 90.4163 83 78.7296 83 64.3117C83 49.8954 70.8563 38.207 55.8745 38.207C40.8927 38.207 28.749 49.8954 28.749 64.3117Z" fill="#AD84FF"></path>
                                            <path d="M82.9996 120.249C82.9996 144.964 103.819 165 129.501 165C155.181 165 176 144.964 176 120.249C176 95.5342 155.181 75.5 129.501 75.5C103.819 75.5 82.9996 95.5342 82.9996 120.249Z" fill="#AD84FF"></path>
                                            <path d="M98.4976 23.2928C98.4976 43.8887 115.848 60.5856 137.249 60.5856C158.65 60.5856 176 43.8887 176 23.2928C176 2.69692 158.65 -14 137.249 -14C115.848 -14 98.4976 2.69692 98.4976 23.2928Z" fill="#AD84FF"></path>
                                            <path d="M-10.0011 8.37466C-10.0011 20.7322 0.409554 30.7493 13.2503 30.7493C26.0911 30.7493 36.5 20.7322 36.5 8.37466C36.5 -3.98287 26.0911 -14 13.2503 -14C0.409554 -14 -10.0011 -3.98287 -10.0011 8.37466Z" fill="#AD84FF"></path>
                                            <path d="M-2.24881 82.9565C-2.24881 87.0757 1.22081 90.4147 5.50108 90.4147C9.78135 90.4147 13.251 87.0757 13.251 82.9565C13.251 78.839 9.78135 75.5 5.50108 75.5C1.22081 75.5 -2.24881 78.839 -2.24881 82.9565Z" fill="#AD84FF"></path>
                                            <path d="M55.8744 12.1044C55.8744 18.2841 61.0788 23.2926 67.5001 23.2926C73.9196 23.2926 79.124 18.2841 79.124 12.1044C79.124 5.92653 73.9196 0.917969 67.5001 0.917969C61.0788 0.917969 55.8744 5.92653 55.8744 12.1044Z" fill="#AD84FF"></path>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                            <div class="font-weight-boldest font-size-h5">TOTAL</div>
                            <div class="text-right d-flex flex-column">
                                <span class="font-weight-boldest font-size-h3 line-height-sm" id='total'><?php echo $moneda;?>0.0</span>
                                <input type="hidden" name="ttl" id='ttl'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <br>
                        <button class="btn btn-success font-weight-bolder" type='submit' id='confirmar_solicitud'>
                            Confirmar Compra
                        </button>
                        <a href="<?php echo base_url().'admin/compras/delete/'.$code;?>" class="btn btn-danger font-weight-bolder">
                            Cancelar
                        </a>
                    </div>
                </div>
                </form>
                <?php elseif($page_name == 'nueva_compra'):?>
                <div class="row">
                    <div class="col-sm-12">
                        <h2>Detalles de la compra</h2>
                        <div class="border-bottom"></div>
                        <br>
                    </div>
                    <div class="col-sm-12"><br>
                        <div class="row">
                            <div class="col-sm-4 col-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>Credito</label>
                                        <span class="switch switch-sm">
                                            <label>
                                                <input type="checkbox" name="credito" onchange="" value="1" />
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>Factura</label>
                                        <span class="switch switch-sm">
                                            <label>
                                                <input type="checkbox" name="chk_factura" id="chk_factura" onchange="" value="1" />
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-12">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>Promoción</label>
                                        <span class="switch switch-sm">
                                            <label>
                                                <input type="checkbox" name="promocion" id="promocion" onchange="promotionProducts()" value="1" />
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label><b>Responsable:</b></label>
                            <select class="form-control" name='responsable' required="true">
                                <option value="">Seleccionar</option>
                                <?php $admin = $this->crud_model->get_admin(); 
                                
                                foreach($admin as $ad): ?>
                                <option value='<?php echo  $ad['admin_id'] ;?>' <?php if($ad['admin_id'] == $this->session->userdata('login_user_id')) echo 'selected';?>>
                                    <?php echo $this->crud_model->getName('admin', $ad['admin_id']) ;?>
                                </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><b>Factura:</b></label>
                            <input autocomplete="off" type="text" class='form-control' name="factura"  placeholder='Ingrese el numero o código' autofocus> 
                        </div>
                        
                        <div class="form-group">
                            <label><b>Proveedor:</b></label>
                            <select class="form-control js-example-basic-single-0" name='provider' id="provider_id" required="true">
                                <option value="">Seleccionar</option>
                                <?php $provider = $this->db->order_by('name', 'ASC')->get_where('provider',array('status'=>'1'))->result_array(); 
                                foreach($provider as $prov): ?>
                                <option value='<?php echo $prov['provider_id'];?>' >
                                    <?php echo $prov['name'];?>
                                </option>
                                <?php endforeach;?>
                            </select>
                        </div>
                       
                        <div class="form-group">
                            <label><b>Origen:</b> <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <select class="form-control" name="origin" required="true" onchange="verificar(this.value)">
                                    <option value="0">(Caja General) Efectivo</option>
                                    <?php $cuentas = $this->crud_model->get_accounts_to_pay();
                                        foreach($cuentas->result_array() as $cts):?>
                                    <option value="<?php echo $cts['account_bank_id'];?>">
                                        <?php if($cts['bank_id'] > 0) echo '('.$this->db->get_where('bank', array('bank_id' => $cts['bank_id']))->row()->name.') - '; echo $cts['name_account'];?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <span class="text-danger" id="msg_error"></span>
                        </div>
                        
                         <div class="form-group">
                            <label><b>Factura:</b></label>
                            <div class="input-group">
                                <input class="uppy-FileInput-input uppy-input-control" type="file" name="factura_img" accept="image/*" id="kt_uppy_5_input_control" style='display:none' onchange="onLoadImage_s(event.target.files)">
                                <label class="uppy-input-label btn btn-light-primary btn-sm btn-bold" for="kt_uppy_5_input_control">Subir factura</label>
                            </div>
                            <label>Archivo seleccionado: <b><span id="imgName_s">Niguno</span></b></label>
                        </div>
                        
                        <div class="form-group">
                            <label><b>Fecha de compra:</b></label>
                            <input type="date" class="form-control" name='date' value='<?php echo date('Y-m-d');?>' required="true">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="alert alert-warning">
                            <b>NOTA:</b> Al momento de confirmar la compra se actualizará tu stock
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label>Destino de la mercadería:</label>
                            <select class="form-control" name='location' required="true">
                                <?php $branch = $this->crud_model->get_branch(); foreach($branch->result_array() as $br):?>
                                <option value='<?php echo $br['branch_id'];?>'>
                                    <?php echo $br['name'] ;?>
                                </option>
                                <?php endforeach; ?>
                                <option value='0'>
                                    <?php echo 'Bodega' ;?>
                                </option>
                            </select>
                        </div>
                        <!--<div class="form-group">
                            <label>Destino</label>
                            <div class="checkbox-inline text-center">
                                <label class="checkbox checkbox-rounded">
                                    <input type="radio" checked name="destiny" value='1'>
                                    <span></span>Inventario
                                </label>
                                <label class="checkbox checkbox-rounded">
                                    <input type="radio" name="destiny" value='0'>
                                    <span></span>Bodega
                                </label>
                            </div>
                        </div> -->
                        <div class="form-group">
                            <label>Detalles: <small>(Serán visibles para el proveedor)</small></label>
                            <textarea placeholder="Detalles adicionales" class="form-control" name='details'></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="bg-primary rounded d-flex  justify-content-between text-white position-relative ml-auto p-7">
                            <div class="position-absolute opacity-30 top-0 right-0">
                                <span class="svg-icon svg-icon-2x svg-logo-white svg-icon-flip">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="176" height="165" viewBox="0 0 176 165" fill="none">
                                        <g clip-path="url(#clip0)">
                                            <path d="M-10.001 135.168C-10.001 151.643 3.87924 165.001 20.9985 165.001C38.1196 165.001 51.998 151.643 51.998 135.168C51.998 118.691 38.1196 105.335 20.9985 105.335C3.87924 105.335 -10.001 118.691 -10.001 135.168Z" fill="#AD84FF"></path>
                                            <path d="M28.749 64.3117C28.749 78.7296 40.8927 90.4163 55.8745 90.4163C70.8563 90.4163 83 78.7296 83 64.3117C83 49.8954 70.8563 38.207 55.8745 38.207C40.8927 38.207 28.749 49.8954 28.749 64.3117Z" fill="#AD84FF"></path>
                                            <path d="M82.9996 120.249C82.9996 144.964 103.819 165 129.501 165C155.181 165 176 144.964 176 120.249C176 95.5342 155.181 75.5 129.501 75.5C103.819 75.5 82.9996 95.5342 82.9996 120.249Z" fill="#AD84FF"></path>
                                            <path d="M98.4976 23.2928C98.4976 43.8887 115.848 60.5856 137.249 60.5856C158.65 60.5856 176 43.8887 176 23.2928C176 2.69692 158.65 -14 137.249 -14C115.848 -14 98.4976 2.69692 98.4976 23.2928Z" fill="#AD84FF"></path>
                                            <path d="M-10.0011 8.37466C-10.0011 20.7322 0.409554 30.7493 13.2503 30.7493C26.0911 30.7493 36.5 20.7322 36.5 8.37466C36.5 -3.98287 26.0911 -14 13.2503 -14C0.409554 -14 -10.0011 -3.98287 -10.0011 8.37466Z" fill="#AD84FF"></path>
                                            <path d="M-2.24881 82.9565C-2.24881 87.0757 1.22081 90.4147 5.50108 90.4147C9.78135 90.4147 13.251 87.0757 13.251 82.9565C13.251 78.839 9.78135 75.5 5.50108 75.5C1.22081 75.5 -2.24881 78.839 -2.24881 82.9565Z" fill="#AD84FF"></path>
                                            <path d="M55.8744 12.1044C55.8744 18.2841 61.0788 23.2926 67.5001 23.2926C73.9196 23.2926 79.124 18.2841 79.124 12.1044C79.124 5.92653 73.9196 0.917969 67.5001 0.917969C61.0788 0.917969 55.8744 5.92653 55.8744 12.1044Z" fill="#AD84FF"></path>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                            <div class="font-weight-boldest font-size-h5">TOTAL COMPRA</div>
                            <div class="text-right d-flex flex-column">
                                <span class="font-weight-boldest font-size-h3 line-height-sm" id='total'><?php echo $moneda;?>0.00</span>
                                <input type="hidden" name="ttl" id='ttl' required="true">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 mt-4">
                        <div class="form-group" id='codigoAuth'>
                            <label> Código de autorización para aplicar configuración: <span class="text-danger">*</span></label>
                            <div class=" spinner-success spinner-left" id='spinnerCode'>
                                <input type="password" autocomplete="off" class='form-control' id='code' placeholder='Ingresa el código de autorización' onblur="getCodigo(this.value)">
                            </div>
                            <div id='mensajeError'></div>
                            <small class='text-info'>Para verificar el código utiliza la tecla <b>TAB</b></small>
                        </div>
                    </div>
                    <div class="col-sm-12" style='text-align: end;'>
                        <button class="btn btn-primary font-weight-bolder" type='submit' id='aplicar'>Guardar</button>
                    </div>
                    </form>
                    <?php elseif($page_name == 'nueva_venta'):?>
                     <? $sale = $this->db->get_where('sales', array('branch_id'=> $this->session->userdata('branch_id') , 'code'=>$code))->row(); ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <h2>Detalles de la venta
                            </h2>
                            <div class="border-bottom"></div>
                            <br>
                        </div>
                        <div class="col-sm-12"><br>
                            <div class="row">
                                <div class="col-sm-16">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Información adicional</label>
                                            <span class="switch switch-sm">
                                                <label>
                                                    <input type="checkbox" onchange="addInfo()" value="1" />
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>CUI</label>
                                            <span class="switch switch-sm">
                                                <label>
                                                    <input type="checkbox" name="cui_v" id="cui_v" onchange="cui(this.value)" value="2" />
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><b>Vendedor:</b></label>
                                <input type="text" class="form-control" name='responsable' readonly value=" <?php echo $this->crud_model->getName('admin', $this->session->userdata('login_user_id'));?>" />
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group ">
                                        <label>NIT/Código:</label>
                                        <div class=" spinner-success spinner-left" id='spinnerNit'>
                                            <input type="text" placeholder="Ingrese el nit sin guiones" class="form-control " name="nit" id="nit"  maxlength="13" onblur="clients(this.value)" autocomplete="off" required>
                                        </div>
                                        <div id='errorNit'></div>
                                        <small class="text-info">Presionar la tecla <b>TAB</b> para buscar al contribuyente </small>
                                    </div>
                                </div>

                                <div class="table-responsive" id="client_search">
                                    <table class="table table-padded">
                                        <tbody id="clientes" class="mostly-customized-scrollbars col-sm-12" style="background-color: #fcfcfc; margin-bottom: 0px !important;">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row" id="client_info">
                                <input type="hidden" name="new_client" value="0">
                                <input type="hidden" name="client_id" id='client_id' value="">
                                <div class="col-sm-12" style="display:none;">
                                    <input type="text" id="prueba" onchange="client_type(this.value)" name="type" value="2">
                                </div>
                                <div class="col-sm-12 ">
                                    <div class="form-group ">
                                        <label>Nombre:</label>
                                        <div class="spinner-primary spinner-left" id='spinnerName'>
                                            <input type="text" placeholder="Nombre" class="form-control" name="name" id='nombre_cliente' value="" required="">
                                        </div>
                                        <div id='msClient'></div>
                                    </div>

                                    <div class="form-group add">
                                        <label>Celular:</label>
                                        <input type="number" placeholder="Celular" class="form-control" name="phone" oninput="if(value.length>8)value=value.slice(0,8)" value="" id='phone'>
                                    </div>

                                    <div class="form-group add" id="email" style="display:none">
                                        <label>Correo:</label>
                                        <input type="email" placeholder="Correo" class="form-control" name="email" value="" id='email_new_client' oninput="searchEmail()" onblur="searchEmail()">
                                        <span id="msg_new_client" class="text-danger"></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Dirección de facturación:</label>
                                        <textarea placeholder="Dirección de facturación" class="form-control" name="address" id='address'> Ciudad </textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Detalles:</label>
                                        <textarea placeholder="Detalles adicionales" name="details" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Venta al crédito</label>
                                            <span class="switch switch-sm">
                                                <label>
                                                    <input type="checkbox" name="credito" onchange="new_credito()" value="1" />
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Requiere envío</label>
                                        <span class="switch switch-sm">
                                            <label>
                                                <input type="checkbox" name="shipping" onchange="delivery(this.value)" value="1" />
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-12" id="shipping_cost" style="display:none">
                                    <div class="form-group">
                                        <label>Costo de envío:</label>
                                        <input type="number" class="form-control" name="shipping_cost" step="any" value="0" id="delivery_cost" onchange="sum()" onkeyup="sum()">
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="resumen" style="<?php if($user_type != 1 && $rol != '4') echo "display:none;";?>">
                                <div class="col-sm-12">
                                    <span><b id="resumen_title">Forma de pago:</b> <span class="text-danger">*</span></span>
                                    <div class="border-bottom"></div><br>
                                </div>
                                <div class="col-lg-12 col-xxl-12">
                                    <div class="form-group">
                                        <label>Método:</label>
                                        <select class="form-control" id='metodo' name="metodo" onchange="metodo_pago(this.value)" <?php if($user_type == 1 || $rol == 4) echo "required";?>>
                                            <option value="">Seleccionar</option>
                                            <option value='Efectivo'>Efectivo</option>
                                            <option value='Tarjeta'>Tarjeta de crédito / débito</option>
                                            <option value='Transferencia'>Transferencia / Depósito</option>
                                            <option value='Cheque'>Cheque</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-lg-6 col-xxl-6 efectivo">
                                    <div class="form-group">
                                        <label>Pago con:</label>
                                        <input type="number"  value="" min='0' step="any" class="form-control" name='pago' id='pago' oninput="cambio()" />
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xxl-6 efectivo">
                                    <div class="form-group">
                                        <label>Cambio:</label><br>
                                        <span class="font-weight-boldest font-size-h6 line-height-sm" id='change'><?php echo $moneda;?>0.00</span>
                                        <input type="hidden" value="0" class="form-control" id='changee' name='change' />
                                    </div>
                                </div>


                                <div class="col-lg-12 col-xxl-12 tarjeta">
                                    <div class="form-group">
                                        <label>Voucher</label>
                                        <input type="number" value="" min='0' step="any" class="form-control" placeholder=' Ingrese el voucher' name='voucher' id='voucher' />
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xxl-12 trans">
                                    <div class="form-group">
                                        <label>No. Transferencia</label>
                                        <input type="number" value="" min='0' step="any" class="form-control" placeholder=' Ingrese el # de boleta' name='trans' id='tans' />
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xxl-12 cheque">
                                    <div class="form-group">
                                        <label>No. Cheque</label>
                                        <input type="number" value="" min='0' step="any" class="form-control" placeholder=' Ingrese el # de cheque' name='cheque' id='cheque' />
                                    </div>
                                </div>


                            </div>
                             <div class="col-lg-12 col-xxl-12 ">
                                <div class="form-group">
                                    <label>Total:</label><br>
                                    <span class="font-weight-boldest font-size-h5 line-height-sm" id='total_a'><?php echo $moneda.number_format(0,2,'.',',');?></span>
                                    <input type="hidden" name="total_a" value='0'>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-group" >
                                    <small class="text-danger" id='ms_error'> No ha agregado artículos a la venta o no ha seleccionado un método de pago </small>
                                </div>
                            </div>

                            <input type="hidden" name="FEL" id='FEL' value='0'>
                            <input type="hidden" name="xml_fel" id='xml_fel' value=''>
                            <input type="hidden" name="code_fel" id='code_fel' value=''>
                            <input type="hidden" name="date_fel" id='date_fel' value=''>
                            <input type="hidden" name="serie_fel" id='serie_fel' value=''>
                            <input type="hidden" name="numero_fel" id='numero_fel' value=''>


                            <div class="form-group" id='codigoAuth'>
                                <label> Código de autorización para aplicar descuentos/crédito: <span class="text-danger">*</span></label>
                                <div class=" spinner-success spinner-left" id='spinnerCode'>
                                    <input type="password" autocomplete="off" class='form-control' id='code' placeholder='Ingresa el código de autorización' autofocus onblur="getCodigo(this.value)">
                                </div>
                                <small class='text-info'>Presionar la tecla TAB para verificar tu código</small>
                                <div id='mensajeError'>
                                </div>
                            </div>
                            <div>
                                <input type="submit" style="display:none;" value="Guardar" id="submit4" />
                                <?php if($user_type == 1 || $rol == 4):?>
                                <button onclick="submitform()" style='float: right;' type="button" id='submit2' class="btn btn-text-primary btn-hover-light-primary font-weight-bold mr-2 nueva_venta spinner-dark spinner-left sinFactura">Aplicar</button>
                                <?endif;?>
                                &nbsp;&nbsp;&nbsp;
                                <button onclick="saveform()" style='float: right;' type="button" id='submit3' class="btn btn-text-secondary btn-hover-light-primary font-weight-bold mr-2 nueva_venta spinner-dark spinner-left sinFactura">Guardar</button>
                            </div>
                            <?php echo form_close();?>
                        </div>
                    </div>
                    </form>

                    <?php elseif($page_name == 'nueva_venta_c'):?>
                    <? $sale = $this->db->get_where('sales', array('branch_id'=> $this->session->userdata('branch_id') , 'code'=>$code))->row(); ?>
                    <div class="row">
                        <div class="col-sm-12">
                            <h2>Detalles de la venta
                            </h2>
                            <div class="border-bottom"></div>
                            <br>
                        </div>
                        <div class="col-sm-12"><br>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Información adicional</label>
                                            <span class="switch switch-sm">
                                                <label>
                                                    <input type="checkbox" onchange="addInfo()" value="1" />
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Facturado</label>
                                            <span class="switch switch-sm">
                                                <label>
                                                    <input type="checkbox" name="factura" onchange="" value="1" />
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><b>Vendedor:</b></label>
                                <input type="text" class="form-control" name='name_res' readonly value=" <?php echo $this->crud_model->getName('admin', $data->row()->responsable);?>" />    
                                <input type="hidden" name='responsable_cot' value="<?php echo $data->row()->responsable;?>" />
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group ">
                                        <label>NIT/Código:</label>
                                        <div class=" spinner-success spinner-left" id='spinnerNit'>
                                            <input type="text" placeholder="Ingrese el nit sin guiones" class="form-control " name="nit" id="nit" maxlength="12" onblur="clients(this.value)" autocomplete="off" required >
                                        </div>
                                        <div id='errorNit'></div>
                                        <small class="text-info">Presionar la tecla <b>TAB</b> para buscar al contribuyente </small>
                                    </div>
                                </div>

                                <div class="table-responsive" id="client_search">
                                    <table class="table table-padded">
                                        <tbody id="clientes" class="mostly-customized-scrollbars col-sm-12" style="background-color: #fcfcfc; margin-bottom: 0px !important;">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row" id="client_info">
                                <input type="hidden" name="new_client" value="0">
                                <input type="hidden" name="client_id" value="<?php echo $data->row()->client_id;?>">
                                <input type="hidden" name="ctz" value="1">
                                <div class="col-sm-12" style="display:none;">
                                    <input type="text" id="prueba" onchange="client_type(this.value)" name="type" value="2">
                                </div>

                                <div class="col-sm-12 ">
                                    <div class="form-group ">
                                        <label>Nombre:</label>
                                        <div class="spinner-primary spinner-left" id='spinnerName'>
                                            <input type="text" placeholder="Nombre" class="form-control" name="name" id='nombre_cliente' value="" required="">
                                        </div>
                                        <div id='msClient'></div>
                                    </div>

                                    <div class="form-group add">
                                        <label>Celular:</label>
                                        <input type="number" placeholder="Celular" class="form-control" name="phone" oninput="if(value.length>8)value=value.slice(0,8)" pattern="[0-9]{8}" value="" id='phone'>
                                    </div>

                                    <div class="form-group add" id="email" style="display:none">
                                        <label>Correo:</label>
                                        <input type="email" placeholder="Correo" class="form-control" name="email" value="" id='email_new_client'>
                                        <span id="msg_new_client" class="text-danger"></span>
                                    </div>

                                    <div class="form-group">
                                        <label>Dirección de facturación:</label>
                                        <textarea placeholder="Dirección de facturación" class="form-control" name="address" id='address'> Ciudad </textarea>
                                    </div>

                                    <div class="form-group">
                                        <label>Detalles:</label>
                                        <textarea placeholder="Detalles adicionales" name="details" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Venta al crédito</label>
                                            <span class="switch switch-sm">
                                                <label>
                                                    <input type="checkbox" name="credito" onchange="new_credito()" value="1" />
                                                    <span></span>
                                                </label>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Requiere envío</label>
                                        <span class="switch switch-sm">
                                            <label>
                                                <input type="checkbox" name="shipping" onchange="delivery(this.value)" value="1" />
                                                <span></span>
                                            </label>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-12" id="shipping_cost" style="display:none">
                                    <div class="form-group">
                                        <label>Costo de envío:</label>
                                        <input type="number" class="form-control" name="shipping_cost" step="any" value="0" id="delivery_cost" onchange="sum()" onkeyup="sum()">
                                    </div>
                                </div>
                            </div>


                            <div class="row" id="resumen" style="<?php if($user_type != 1 && $rol != '4') echo "display:none;";?>">
                                <div class="col-sm-12">
                                    <span><b id="resumen_title">Forma de pago:</b> <span class="text-danger">*</span></span>
                                    <div class="border-bottom"></div><br>
                                </div>
                                <div class="col-lg-12 col-xxl-12">
                                    <div class="form-group">
                                        <label>Método:</label>
                                        <select class="form-control" id='metodo' name="metodo" onchange="metodo_pago(this.value)" required>
                                            <option value="">Seleccionar</option>
                                            <option value='Efectivo'>Efectivo</option>
                                            <option value='Tarjeta'>Tarjeta de crédito / débito</option>
                                            <option value='Transferencia'>Transferencia / Depósito</option>
                                            <option value='Cheque'>Cheque</option>
                                        </select>
                                        <small class="text-danger" id='ms_error'> seleccionar una opción</small>
                                    </div>
                                </div>


                                <div class="col-lg-6 col-xxl-6 efectivo">
                                    <div class="form-group">
                                        <label>Pago con:</label>
                                        <input type="number"  value="" min='0' step="any" class="form-control" name='pago' id='pago' oninput="cambio()" />
                                    </div>
                                </div>

                                <div class="col-lg-6 col-xxl-6 efectivo">
                                    <div class="form-group">
                                        <label>Cambio:</label><br>
                                        <span class="font-weight-boldest font-size-h6 line-height-sm" id='change'><?php echo $moneda;?>0.00</span>
                                        <input type="hidden" value="0" class="form-control" id='changee' name='change' />
                                    </div>
                                </div>


                                <div class="col-lg-12 col-xxl-12 tarjeta">
                                    <div class="form-group">
                                        <label>Voucher</label>
                                        <input type="number" value="" min='0' step="any" class="form-control" placeholder=' Ingrese el voucher' name='voucher' id='voucher' />
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xxl-12 trans">
                                    <div class="form-group">
                                        <label>No. Transferencia</label>
                                        <input type="number" value="" min='0' step="any" class="form-control" placeholder=' Ingrese el # de boleta' name='trans' id='tans' />
                                    </div>
                                </div>

                                <div class="col-lg-12 col-xxl-12 cheque">
                                    <div class="form-group">
                                        <label>No. Cheque</label>
                                        <input type="number" value="" min='0' step="any" class="form-control" placeholder=' Ingrese el # de cheque' name='cheque' id='cheque' />
                                    </div>
                                </div>

                            </div>

                            <div class="col-lg-12 col-xxl-12 ">
                                <div class="form-group">
                                    <label>Total:</label><br>
                                    <span class="font-weight-boldest font-size-h5 line-height-sm" id='total_a'><?php echo $moneda.number_format($data->row()->total - $data->row()->total_pagado,2,'.',',');?></span>
                                    <input type="hidden" name="total_a" value='<?php echo $data->row()->total - $data->row()->total_pagado ;?>'>
                                </div>
                            </div>

                            <input type="hidden" name="FEL" id='FEL' value='0'>
                            <input type="hidden" name="xml_fel" id='xml_fel' value=''>
                            <input type="hidden" name="code_fel" id='code_fel' value=''>
                            <input type="hidden" name="date_fel" id='date_fel' value=''>
                            <input type="hidden" name="serie_fel" id='serie_fel' value=''>
                            <input type="hidden" name="numero_fel" id='numero_fel' value=''>

                            <div class="form-group" id='codigoAuth'>
                                <label> Código de autorización para aplicar descuentos/crédito: <span class="text-danger">*</span></label>
                                <div class=" spinner-success spinner-left" id='spinnerCode'>
                                    <input type="password" autocomplete="off" class='form-control' id='code' placeholder='Ingresa el código de autorización' autofocus onkeyup="getCodigo(this.value)" onblur="getCodigo(this.value)">
                                </div>
                                <small class='text-info'>Presionar la tecla TAB para verificar tu código</small>
                                <div id='mensajeError'></div>
                            </div>
                            <div>
                                <button onclick="submitform()" style='float: right;' type="submit" id='submit2' class="btn btn-text-primary btn-hover-light-primary font-weight-bold mr-2 nueva_venta spinner-dark spinner-left sinFactura">Aplicar</button>
                            </div>
                            <?php echo form_close();?>
                        </div>

                        <?php endif;?>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $moneda = $this->crud_model->get_info("moneda");?>

<!-- modal -->
<div class="modal fade" id="modalEgreso" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-dialog  modal-lg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Registrar nuevo gasto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" method="post" action="<?php echo base_url();?>admin/expense_tab" enctype="multipart/form-data">
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
                                <label><b>Fecha:</b> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" required="true" id="kt_datepicker_s" aria-label="Text input with checkbox" name="date" readonly value="<?php echo date('m/d/Y');?>" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><b>Monto:</b> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><?php echo $moneda;?></span>
                                    </div>
                                    <input type="number" step="0.01" class="form-control" min="1" id="amount_side" oninput="verificar_side()" aria-label="Text input with checkbox" name="amount" required="true" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><b>Responsable:</b></label>
                                <div class="input-group">
                                    <select class="form-control" name="responsable" required="true">
                                        <option value="">Seleccionar</option>
                                        <?php $respons = $this->db->get_where('admin', array('status'=>1))->result_array();
                                                            foreach($respons as $res): ?>
                                        <option value="<?php echo $res['admin_id'];?>">
                                            <?php echo $res['name'].' '.$res['last_name'];?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><b>Factura:</b></label>
                                <div class="input-group">
                                    <input class="uppy-FileInput-input uppy-input-control" type="file" name="factura_img" accept="image/*, .pdf" id="kt_uppy_5_input_control_l" style='display:none' onchange="onLoadImage_l(event.target.files)">
                                    <label class="uppy-input-label btn btn-light-primary btn-sm btn-bold" for="kt_uppy_5_input_control_l">Subir Factura</label>
                                </div>
                                <label>Archivo seleccionado: <b><span id="imgName_l">Niguno</span></b></label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><b>Entidad a quien compró o pagó:</b> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Ej: <?php echo $this->crud_model->getNameSistema();?>" aria-label="Text input with checkbox" name="provider" required="true" />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label><b>Origen:</b> <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control" id="origin_side" name="origin" required="true" onchange="verificar_side()">
                                        <option value="">Seleccionar</option>
                                        <option value="0">(Caja General) Efectivo</option>
                                        <?php $cuentas = $this->crud_model->get_accounts_to_pay();
                                            foreach($cuentas->result_array() as $cts):?>
                                        <option value="<?php echo $cts['account_bank_id'];?>">
                                        <?php if($cts['bank_id'] > 0) echo '('.$this->db->get_where('bank', array('bank_id' => $cts['bank_id']))->row()->name.') - '; echo $cts['name_account'];?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <span class="text-danger" id="msg_saldo"></span>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label><b>Descripción</b></label>
                                <div class="input-group">
                                    <textarea class="form-control" aria-label="Text input with checkbox" name="details"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary font-weight-bold" id="submit_expense">Continuar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

function verificar_side() {
    let bank_id = 0;
    var banco = $('#origin_side').val();
    $total = $('#amount_side').val();

    bank_id = banco;


    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/saldo_cuenta/',
        data: {
            bank_id: bank_id,
            total: $total,
        },
        success: function(response) {
            if (response == 1) {
                $('#msg_saldo').html("");
                $('#submit_expense').removeAttr("disabled");
            } else if (response == 2) {
                $('#msg_saldo').html('El pago se realizará, pero la cuenta quedara en cero');
                $('#submit_expense').removeAttr("disabled");
            } else if (response == 3) {
                $('#msg_saldo').html('La cuenta no tiene los fondos suficientes');
                $('#submit_expense').attr("disabled", "true");
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}

var boll = true;

function open_panel() {
    if (boll) {

        boll = false;
        $('#kt_sidebar').removeClass('sidebar-oculto');
    } else {

        boll = true;
        $('#kt_sidebar').addClass('sidebar-oculto');

    }
}



function onLoadImage_l(files) {
    if (files && files[0]) {
        document
            .getElementById('imgName_l')
            .innerHTML = files[0].name;
    } else {
        document
            .getElementById('imgName_l')
            .innerHTML = 'Ninguno';
    }

}

function onLoadImage_s(files) {
    if (files && files[0]) {
        document
            .getElementById('imgName_s')
            .innerHTML = files[0].name;
    } else {
        document
            .getElementById('imgName_s')
            .innerHTML = 'Ninguno';
    }

}

function onLoadImage_c(files) {
    if (files && files[0]) {
        document
            .getElementById('imgName_c')
            .innerHTML = files[0].name;
    } else {
        document
            .getElementById('imgName_c')
            .innerHTML = 'Ninguno';
    }

}

$(document).ready(function() {
    $('.js-example-basic-single-0').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });
    $('.js-example-basic-single-1').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });
    creditos();
});

function find() {
    var name = $('#name').val();
    if (name != '') {
        $('#cuadro1').hide(500);
    } else {
        $('#cuadro1').show(500);
    }
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/search_product',
        data: {
            name: name,
        },
        success: function(response) {
            jQuery('#resultados').html(response);
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}

var address = $('#shipping').hide()

function mostrar(value) {

    if (bol == true) {
        address.show(500);
        bol = false;
    } else {
        address.hide(500);
        bol = true;
    }
}

var pago = $('#forma_pago');
var boll = false;

function creditos(value) {

    if (boll == true) {
        boll = false;
        pago.hide(500);
    } else {
        pago.show(500);
        boll = true;
    }
}

function bancos(metodo) {
    if (metodo == 'bancos') {
        $('#bancos').show(500);
    } else {
        $('#bancos').hide(500);
    }
}
var shipping = false;

function delivery(cost) {
    if (!shipping) {
        $('#shipping_cost').show(500);
        shipping = true;
    } else {
        $('#shipping_cost').hide(500);
        shipping = false;

    }
}

var cui_flag = false;
function cui(value) {

    if (cui_flag == true) {
        $('#cui_v').val('2');
        cui_flag = false;
    } else {
        $('#cui_v').val('1');
        cui_flag = true;
    }
}

$(document).ready(function() {
    $('#selected-0').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true
    });
})
</script>
