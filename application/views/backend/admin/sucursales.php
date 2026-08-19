<?php $data = $this->db->get_where('branch', array('status'=>1)); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Gestionar sucursales
                            <span class="d-block text-muted pt-2 font-size-sm">Aquí podrás gestionar todas las sucursales con las que disponga tu negocio.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="javascript:;" class="btn btn-warning font-weight-bolder" data-toggle="modal"
                            data-target="#exampleModalSizeLg">
                            <span class="svg-icon svg-icon-md">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <circle fill="#000000" cx="9" cy="15" r="6" />
                                        <path
                                            d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z"
                                            fill="#000000" opacity="0.3" />
                                    </g>
                                </svg>
                            </span> Nueva sucursal
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($data->num_rows() > 0): ?>

                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="table-responsive">
                            <table class="table table-bordered dataTable no-footer dtr-inline" id="kt_datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Sucursal</th>
                                        <th>Correo</th>
                                        <th>Teléfono</th>
                                        <th>Dirección</th>
                                        <th>Encargado</th>
                                        <th>Acciones</th>

                                    </tr>
                                </thead>
                                <tbody id='table'>
                                    <?php $n = 1; foreach ($data->result_array() as $row): ?>
                                    <tr>
                                        <td><?php echo $n++;?></td>
                                        <td><?php echo $row['name'];?></td>
                                        <td>
                                            <?php if(!$row['email']):?>
                                                <span class="label label-lg font-weight-bold label-primary label-inline">N/A</span>
                                            <?php else:?>
                                            <a style='color:#3F4254;' href="mailto:<?php echo $row['email'];?>">
                                                <?php echo $row['email'];?></a>
                                            <?php endif;?>
                                        </td>
                                        <td> <a style='color:#3F4254;' <?php if($row['tel']):?> href="tel:+502<?php echo $row['tel'];?>" <?php else: ?> href="javascript:;" <?php endif;?>>
                                                <?php echo ($row['tel']=='') ? 'Sin datos' : '(+502)'.$row['tel'];?></a> </td>
                                        <td><?php echo ($row['address']=='') ? 'Sin datos' : $row['address'];?>
                                        </td>
                                        <td><?php echo ($row['manager']=='') ? 'Sin datos' : $this->crud_model->getName('admin', $row['manager']);?>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-shrink-0">
                                                <a href="javascript:;" data-toggle="tooltip"
                                                    data-original-title="Editar sucursal"
                                                    onclick="showAjaxModal('<?php echo base_url().'modal/popup/editar_sucursal/'.$row['branch_id'];?>');"
                                                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1">
                                                    <span class="svg-icon svg-icon-3">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                            viewBox="0 0 24 24" fill="none">
                                                            <path opacity="0.3"
                                                                d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                                fill="#8950FC" />
                                                            <path
                                                                d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                                fill="#8950FC" />
                                                        </svg>
                                                    </span>
                                                </a>
                                                &nbsp;
                                                <a href="javascript:;" data-toggle="tooltip"
                                                    data-original-title="Eliminar sucursal"
                                                    onclick="executeExample('<?php echo $row['branch_id'];?>')"
                                                    class="btn btn-icon btn-bg-light btn-active-color-info btn-sm">
                                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                                            height="24px" viewBox="0 0 24 24" version="1.1">
                                                            <g stroke="none" stroke-width="1" fill="none"
                                                                fill-rule="evenodd">
                                                                <rect x="0" y="0" width="24" height="24" />
                                                                <path
                                                                    d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z"
                                                                    fill="#000000" fill-rule="nonzero" />
                                                                <path
                                                                    d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z"
                                                                    fill="#000000" opacity="0.3" />
                                                            </g>
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


<form class="form" action="<?php echo base_url();?>admin/sucursales/create" method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="exampleModalSizeLg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-dialog  modal-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar nueva sucursal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
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
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Sucursal <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control" aria-label="Text input with checkbox"
                                        name='name' required />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Correo <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="email" class="form-control" aria-label="Text input with checkbox"
                                        name='email' required />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Celular</label>
                                <div class="input-group">
                                    <input type="number" min='0' oninput="if(value.length>8)value=value.slice(0,8)" class='form-control' aria-label="Text input with checkbox"
                                        name='phone'  />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Teléfono <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" min='0' oninput="if(value.length>8)value=value.slice(0,8)" class="form-control" aria-label="Text input with checkbox"
                                        name='tel' required />
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Encargado <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <select class="form-control" name="manager" required>
                                        <option value=''>Seleccionar</option>
                                        <?php $manager = $this->db->get_where('admin', array('username !='=>'admin', 'status'=>1)); foreach ($manager->result_array() as $row):?>
                                        <?php if($manager->num_rows() > 0 ):?>
                                        <option value="<?php echo $row['admin_id'];?>"><?php echo $row['name'];?>
                                        </option>
                                        <?php else:?>
                                        <option value="">Sin Datos
                                        </option>
                                        <?php endif;?>
                                        <?php endforeach  ;?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Dirección</label>
                                <div class="input-group">
                                    <textarea class="form-control" aria-label="Text input with checkbox"
                                        name='address'></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger font-weight-bold"
                        data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</form>



<script type="text/javascript">
let timerInterval

function executeExample(sucursal_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará toda la información de la sucursal.",
        type: 'info',
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
            location.href = "<?php echo base_url();?>admin/sucursales/delete/" + sucursal_id;
        }
    })
}
</script>