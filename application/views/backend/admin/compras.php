<?php $moneda = $this->crud_model->get_info("moneda");?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar compras
                            <span class="d-block text-muted pt-2 font-size-sm">Administra las compras realizadas a tus
                                proveedores.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <?php if($data->num_rows() > 0  && ($user_type == 1 || $permisos['reportes_compras'] == 1)) :?>
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
                                        <a href="<?php echo base_url().'admin/export_excel/compras/'.$initial.'/'.$final.'/';?>"
                                            class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-excel-o"></i>
                                            </span>
                                            <span class="navi-text">Excel</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_pdf/compras/'.$initial.'/'.$final.'/';?>" class="navi-link">
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
                    <form class="mb-15" method="POST" action="<?php echo base_url().'admin/compras/'?>">
                        <div class="row mb-6">
                            
                            <div class="col-lg-4 mb-lg-2 mb-6">
                                <label>Código</label>
                                <div class="input-daterange input-group">
                                    <input type="text" class="form-control datatable-input" name="code"
                                        autocomplete="off" placeholder="VC1234" data-col-index="1" value="<?php echo $code;?>">
                                </div>
                            </div>

                            <div class="col-lg-4 mb-lg-2 mb-6">
                                <label>Fecha inicial:</label>
                                <div class="input-daterange input-group" id="">
                                    <input type="date" class="form-control datatable-input" name="initial"
                                        autocomplete="off" placeholder="dd/mm/aaaa" data-col-index="2" value="<?php echo $initial;?>">
                                </div>
                            </div>

                            <div class="col-lg-4 mb-lg-2 mb-6">
                                <label>Fecha final:</label>
                                <div class="input-daterange input-group" id="">
                                    <input type="date" class="form-control datatable-input" name="final"
                                        autocomplete="off" placeholder="dd/mm/aaaa" data-col-index="2" value="<?php echo $final;?>">
                                </div>
                            </div>

                            <div class="col-lg-4 mb-lg-0 mb-6">
                                <label>Estado:</label>
                                <select class=" form-control datatable-input" name="type" data-col-index="4">
                                    <option value="">Todos</option>
                                    <option value="1" <?php if($type == '1') echo "selected";?>>Completado</option>
                                    <option value="3" <?php if($type == '3') echo "selected";?>>Solicitud de compra	</option>
                                    <option value="2" <?php if($type == '2') echo "selected";?>>Orden de compra</option>
                                </select>
                            </div>

                            <div class="col-lg-4 mb-lg-0 mb-6">
                                <label>Proveedor:</label>
                                <select  class=" form-control datatable-input" name="admin_id" data-col-index="7">
                                    <option value="">Todos</option>
                                    <?php $this->db->order_by('name','ASC');
                                    $admins = $this->db->get_where('provider', array('status'=>1))->result_array();
                                        foreach($admins as $ad):?>
                                    <option value="<?php echo $ad['provider_id'];?>" ><?php echo trim($ad['name']);?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-8">
                            <div class="col-lg-12">
                                <button class="btn btn-primary btn-primary--icon" type="submit">
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
                    <?php if($data->num_rows() > 0):?>
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable no-footer dtr-inline" id="kt_datatable">
                            <thead>
                                <tr>
                                    <th title="Field #1">ID</th>
                                    <th title="Field #2">Código</th>
                                    <th title="Field #3">Fecha</th>
                                    <th title="Field #4">Total</th>
                                    <th title="Field #5">Estado</th>
                                    <th title="Field #6">Credito</th>
                                    <th title="Field #7">Encargado</th>
                                    <th title="Field #8">Proveedor</th>
                                    <th title="Field #9">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id='table'>
                                <?php $n=1 ;foreach ($data->result_array() as $row) :?>
                                <tr>
                                    <td><?php echo $n++;?></td>
                                    <td><?php echo $row['code'];?></td>
                                    <td><span class="text-info"><b><?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d/m/Y", strtotime( $row['date'] ));				
                                        $Mes_Anyo = strftime("%m/%d/%Y", strtotime($Nueva_Fecha)); 
                                        echo $Nueva_Fecha;?></b></span></td>
                                    <td><span
                                            class="label label-lg font-weight-bolder label-light-info label-inline"><?php echo $moneda.number_format($row['total'],2,'.',',')?></span>
                                    </td>
                                    <td><span
                                            class="badge badge-<?php if($row['type']==3){echo 'warning';}elseif($row['type']==2){echo 'info';}elseif($row['type']==1){echo 'success';} ?>"><?php if($row['type']==3){echo 'Solicitud de compra ';}elseif($row['type']==2){echo 'Orden de compra';}elseif($row['type']==1){echo 'Completado';} ?></span>
                                    </td>
                                    <td><span
                                            class="badge badge-<?php if($row['credito']==1){echo 'warning';}elseif($row['credito']==0){echo 'success';} ?>"><?php if($row['credito']==1){echo 'Activo';}elseif($row['credito']==0){echo 'Completado';} ?></span>
                                    </td>
                                    <td><?php echo $this->crud_model->getName('admin', $row['responsable']);?></td>
                                    <td><?php echo $this->crud_model->getSingleName('provider', $row['provider']);?></td>
                                    <td>
                                        <div class="d-flex flex-shrink-0">
                                            <?php if($row['type']==2 && ($user_type == 1 || $permisos['completar_solicitud'] == 1)):?>
                                            &nbsp;
                                            <a href="<?php echo base_url().'admin/nueva_compra_s/'.$row['code'];?>"
                                                data-toggle="tooltip" data-original-title="Registrar compra"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path
                                                                d="M3,13.5 L19,12 L3,10.5 L3,3.7732928 C3,3.70255344 3.01501031,3.63261921 3.04403925,3.56811047 C3.15735832,3.3162903 3.45336217,3.20401298 3.70518234,3.31733205 L21.9867539,11.5440392 C22.098181,11.5941815 22.1873901,11.6833905 22.2375323,11.7948177 C22.3508514,12.0466378 22.2385741,12.3426417 21.9867539,12.4559608 L3.70518234,20.6826679 C3.64067359,20.7116969 3.57073936,20.7267072 3.5,20.7267072 C3.22385763,20.7267072 3,20.5028496 3,20.2267072 L3,13.5 Z"
                                                                fill="#000000" />
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            <?php endif;?>
                                            <?php if($row['type']==3 && ($user_type == 1 || $permisos['ver_solicitud'] == 1)):?>
                                            &nbsp;
                                            <a href="<?php echo base_url().'provider/detalles_compra/'.$row['code'];?>" target=”_blank”
                                                data-toggle="tooltip" data-original-title="Compartir con proveedor"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path
                                                                d="M10.9,2 C11.4522847,2 11.9,2.44771525 11.9,3 C11.9,3.55228475 11.4522847,4 10.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,16 C20,15.4477153 20.4477153,15 21,15 C21.5522847,15 22,15.4477153 22,16 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L10.9,2 Z"
                                                                fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                            <path
                                                                d="M24.0690576,13.8973499 C24.0690576,13.1346331 24.2324969,10.1246259 21.8580869,7.73659596 C20.2600137,6.12944276 17.8683518,5.85068794 15.0081639,5.72356847 L15.0081639,1.83791555 C15.0081639,1.42370199 14.6723775,1.08791555 14.2581639,1.08791555 C14.0718537,1.08791555 13.892213,1.15726043 13.7542266,1.28244533 L7.24606818,7.18681951 C6.93929045,7.46513642 6.9162184,7.93944934 7.1945353,8.24622707 C7.20914339,8.26232899 7.22444472,8.27778811 7.24039592,8.29256062 L13.7485543,14.3198102 C14.0524605,14.6012598 14.5269852,14.5830551 14.8084348,14.2791489 C14.9368329,14.140506 15.0081639,13.9585047 15.0081639,13.7695393 L15.0081639,9.90761477 C16.8241562,9.95755456 18.1177196,10.0730665 19.2929978,10.4469645 C20.9778605,10.9829796 22.2816185,12.4994368 23.2042718,14.996336 L23.2043032,14.9963244 C23.313119,15.2908036 23.5938372,15.4863432 23.9077781,15.4863432 L24.0735976,15.4863432 C24.0735976,15.0278051 24.0690576,14.3014082 24.0690576,13.8973499 Z"
                                                                fill="#000000" fill-rule="nonzero"
                                                                transform="translate(15.536799, 8.287129) scale(-1, 1) translate(-15.536799, -8.287129) " />
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            <?php endif;?>
                                            <?php if($row['credito']==1 ):?>
                                            &nbsp;
                                            <a href="<?php echo base_url().'admin/detalles_creditos_prov/'.$row['code'];?>" target=”_blank”
                                                data-toggle="tooltip" data-original-title="Ver credito con proveedor"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path
                                                                d="M10.9,2 C11.4522847,2 11.9,2.44771525 11.9,3 C11.9,3.55228475 11.4522847,4 10.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,16 C20,15.4477153 20.4477153,15 21,15 C21.5522847,15 22,15.4477153 22,16 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L10.9,2 Z"
                                                                fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                                            <path
                                                                d="M24.0690576,13.8973499 C24.0690576,13.1346331 24.2324969,10.1246259 21.8580869,7.73659596 C20.2600137,6.12944276 17.8683518,5.85068794 15.0081639,5.72356847 L15.0081639,1.83791555 C15.0081639,1.42370199 14.6723775,1.08791555 14.2581639,1.08791555 C14.0718537,1.08791555 13.892213,1.15726043 13.7542266,1.28244533 L7.24606818,7.18681951 C6.93929045,7.46513642 6.9162184,7.93944934 7.1945353,8.24622707 C7.20914339,8.26232899 7.22444472,8.27778811 7.24039592,8.29256062 L13.7485543,14.3198102 C14.0524605,14.6012598 14.5269852,14.5830551 14.8084348,14.2791489 C14.9368329,14.140506 15.0081639,13.9585047 15.0081639,13.7695393 L15.0081639,9.90761477 C16.8241562,9.95755456 18.1177196,10.0730665 19.2929978,10.4469645 C20.9778605,10.9829796 22.2816185,12.4994368 23.2042718,14.996336 L23.2043032,14.9963244 C23.313119,15.2908036 23.5938372,15.4863432 23.9077781,15.4863432 L24.0735976,15.4863432 C24.0735976,15.0278051 24.0690576,14.3014082 24.0690576,13.8973499 Z"
                                                                fill="#000000" fill-rule="nonzero"
                                                                transform="translate(15.536799, 8.287129) scale(-1, 1) translate(-15.536799, -8.287129) " />
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            <?php endif;?>
                                            &nbsp;
                                            <a href="<?php echo base_url().'admin/detalles_compra/'.$row['code'];?>"
                                                data-toggle="tooltip" data-original-title="Detalles"
                                                class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                        height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <circle fill="#000000" opacity="0.3" cx="12" cy="12"
                                                                r="10" />
                                                            <rect fill="#000000" x="11" y="10" width="2" height="7"
                                                                rx="1" />
                                                            <rect fill="#000000" x="11" y="7" width="2" height="2"
                                                                rx="1" />
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            &nbsp;
                                            <?php if($user_type == 1 || $permisos['reportes_compras'] == 1):?>
                                            <a href="<?php echo base_url().'admin/export_pdf/compra/'.$row['code'];?>"
                                                data-toggle="tooltip" data-original-title="Imprimir"
                                                class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
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
                                            &nbsp;
                                            <?php endif;?>
                                            <a href="<?php echo base_url().'admin/export_pdf/voucher_shop/'.$row['code'];?>"
                                                        class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                                                        data-toggle="tooltip" title="" data-original-title="Imprimir" target="_blank">
                                                <span class="svg-icon svg-icon-primary svg-icon-2hx">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <path opacity="0.3" d="M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM12.5 18C12.5 17.4 12.6 17.5 12 17.5H8.5C7.9 17.5 8 17.4 8 18C8 18.6 7.9 18.5 8.5 18.5L12 18C12.6 18 12.5 18.6 12.5 18ZM16.5 13C16.5 12.4 16.6 12.5 16 12.5H8.5C7.9 12.5 8 12.4 8 13C8 13.6 7.9 13.5 8.5 13.5H15.5C16.1 13.5 16.5 13.6 16.5 13ZM12.5 8C12.5 7.4 12.6 7.5 12 7.5H8C7.4 7.5 7.5 7.4 7.5 8C7.5 8.6 7.4 8.5 8 8.5H12C12.6 8.5 12.5 8.6 12.5 8Z" fill="#000000"/>
                                                        <rect x="7" y="17" width="6" height="2" rx="1" fill="#000000"/>
                                                        <rect x="7" y="12" width="10" height="2" rx="1" fill="#000000"/>
                                                        <rect x="7" y="7" width="6" height="2" rx="1" fill="#000000"/>
                                                        <path d="M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z" fill="#000000"/>
                                                    </svg>
                                                </span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach;?>

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



<script type="text/javascript">
function search() {
    $name = $('#name').val();
    $status = $('#status').val();

    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/compras',
        data: {
            name: $name,
            status: $status,
        },
        success: function(response) {
            jQuery('#table').html(response);
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}




let timerInterval

function executeExample(_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará toda la información de la venta!.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#9fd13b',
        cancelButtonColor: '#fd4f57',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            Swal.fire({
                title: 'Eliminando información',
                type: 'success',
                icon: 'success',
                titleTextColor: '#000',
                html: 'Esta ventana se cerrará en <strong></strong>.',
                timer: 2000,
                onBeforeOpen: () => {
                    Swal.showLoading()
                    timerInterval = setInterval(() => {
                        Swal.getContent().querySelector('strong').textContent = Swal
                            .getTimerLeft()
                    }, 100)
                },
                onClose: () => {
                    clearInterval(timerInterval)
                }
            })
            location.href = "<?php echo base_url();?>admin/ventas/delete/" + _id + '/1';
        }
    })
}
</script>