<?php $data = $this->crud_model->get_transfer(); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Traslados recientes 
                            <span class="d-block text-muted pt-2 font-size-sm">Traslado de productos a diferentes
                                sucursales o bodega.</span>
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <?php if($data->num_rows() > 0  && ($user_type == 1 || $permisos['reportes_clientes'] == 1)):?>
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
                                        <a href="<?php echo base_url().'admin/export_excel/transfers'?>"
                                            class="navi-link">
                                            <span class="navi-icon">
                                                <i class="la la-file-excel-o"></i>
                                            </span>
                                            <span class="navi-text">Excel</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="<?php echo base_url().'admin/export_pdf/transfers'?>"
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
                        <?php endif;?>
                        <?php if($user_type == 1 || $permisos['crear_traslados'] == 1):?>
                        <a href="<?php echo base_url();?>admin/producto_traslado"
                            class="btn btn-primary font-weight-bolder">
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
                            </span>
                            Nuevo traslado
                        </a>
                        <?php endif;?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($data->num_rows() >0):?>
                    <form class="mb-15">
                        <div class="row mb-6">
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Fecha:</label>
                                <div class="input-daterange input-group" id="kt_datepicker">
                                    <input type="text" class="form-control datatable-input" name="start"
                                        autocomplete="off" placeholder="mm/dd/aaaa" readonly data-col-index="1">
                                </div>
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Sucursal:</label>
                                <select class=" form-control datatable-input" data-col-index="3">
                                    <option value="">Seleccionar</option>
                                    <option value="Bodega">Bodega</option>
                                    <?php $sucursales = $this->db->get_where('branch',array('status'=>1))->result_array(); foreach ($sucursales as $sc):?>
                                    <option value="<?php echo $sc['name'];?>"><?php echo $sc['name'];?></option>
                                    <?php endforeach?>
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
                        <table class="table table-bordered dataTable no-footer dtr-inline" id="kt_datatable_1">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fecha</th>
                                    <th>Desde</th>
                                    <th>Hacia</th>
                                    <th>Cantidad</th>
                                    <th class='text-center'>Producto</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id='table'>
                                <?php $n = 1; foreach ($data->result_array() as $row):
                                    $product_id = $row['products_id'];
                                    if($row['products_id_2'] > 0) $product_id = $row['products_id_2'];
                                    $pro = $this->db->get_where('products',array('products_id'=>$product_id))->row_array();
                                    $amount = $row['amount'];
                                    if ($pro['presentation'] == 'Caja' && $pro['cnt_prod_matriz'] > 0) $amount = $row['amount'] / $pro['cnt_prod_matriz'];?>
                                <tr>
                                    <td><?php echo $pro['code'];?></td>
                                    <td> <span class="text-info"><b><?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));				
                                        $Mes_Anyo = strftime("%m/%d/%Y", strtotime($Nueva_Fecha)); 
                                        echo date("Y/m/d", strtotime($row['date']));?></b></span></td>
                                    <td>
                                        <?php /* if($row['type']  == 1):?>
                                        <span class="label label-lg font-weight-bold label-light-success label-inline">
                                            Se recibio de
                                        </span>
                                        <?php else: ?>
                                        <span class="label label-lg font-weight-bold label-light-danger label-inline">
                                            Se traslado a
                                        </span>
                                        <?php endif; */?>
                                        
                                        <?php  if($row['type']== 0 ):?>
                                        <span class="label label-lg font-weight-bold label-light-danger label-inline">
                                            
                                            Se envio de: <?php echo $row['branch_id'] == 0 ? "Bodega": $this->db->get_where('branch',array('branch_id'=>$row['branch_id']))->row()->name;?>
                                        </span>
                                        <?php else: ?>
                                                <span class="label label-lg font-weight-bold label-light-success label-inline">
                                            
                                            Se recibio de: <?php echo $row['branch_id2'] == 0 ? "Bodega": $this->db->get_where('branch',array('branch_id'=>$row['branch_id2']))->row()->name;?>
                                        </span> 
                                        <?php endif; ?>
                                        
                                        
                                    </td>
                                    <td>
                                         <?php  if($row['type']== 0 ):?>
                                        <span class="label label-lg font-weight-bold label-light-danger label-inline">
                                            
                                            A: <?php echo $row['branch_id2'] == 0 ? "Bodega": $this->db->get_where('branch',array('branch_id'=>$row['branch_id2']))->row()->name;?>
                                        </span>
                                        <?php else: ?>
                                        <span class="label label-lg font-weight-bold label-light-success label-inline">
                                            A: <?php echo $row['branch_id'] == 0 ? "Bodega": $this->db->get_where('branch',array('branch_id'=>$row['branch_id']))->row()->name;?>
                                        </span> 
                                        <?php endif; ?>
                                        <?php // echo $row['branch_id2'] == 0 ? "Bodega": $this->db->get_where('branch',array('branch_id'=>$row['branch_id2']))->row()->name;?>
                                    </td>
                                    <td><span
                                            class="text-<?php echo $row['type'] == 1 ? "success":"danger";?>"><b><?php echo $amount;?></b></span>
                                    </td>
                                    <td class='text-center'>
                                        <span class="label label-lg font-weight-bold label-light-success label-inline"><?php echo $pro['name'];?></span>
                                    </td>
                                    <td>
                                        <?php if($user_type == 1 || $permisos['eliminar_traslados'] == 1):?>
                                        <div class="d-flex flex-shrink-0">
                                            &nbsp;
                                            <a href="javascript:void(0);"
                                                onclick="executeExample('<?php echo $row['product_details_id'];?>')"
                                                class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                                                data-toggle="tooltip" title="" data-original-title="Revertir traslado">
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
                                            &nbsp;
                                            <a href="<?php echo base_url().'admin/export_pdf/traslado/'.$row['id_traslado'];?>"
                                                class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                                                data-toggle="tooltip" title="" data-original-title="Imprimir">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                        <g stroke="none" stroke-width="1" fill="none"
                                                            fill-rule="evenodd">
                                                            <rect x="0" y="0" width="24" height="24" />
                                                            <path d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z" fill="#000000" />
                                                            <rect fill="#000000" opacity="0.3" x="8" y="2" width="8" height="2" rx="1" />
                                                        </g>
                                                    </svg>
                                                </span>
                                            </a>
                                            &nbsp;
                                            <a href="javascript:;"
                                                onclick="showModalTras('<?php echo base_url();?>modal/popup/traslados_info/<?php echo $row['product_details_id'];?>');"
                                                class="btn btn-icon btn-bg-light btn-active-color-info btn-sm"
                                                data-toggle="tooltip" title="" data-original-title="Motivo del traslado">
                                                <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                                                    <svg
                                                            xmlns="http://www.w3.org/2000/svg"
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
                                                </span>
                                            </a>
                                        </div>
                                        
                                        
                                        <?php endif;?>
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
$(document).ready(function () {
    $('#kt_datatable_1').DataTable({
		responsive: true,
        order: [[1, 'desc']] ,
		// Pagination settings
		dom: `<'row'<'col-sm-12'tr>>
		<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7 dataTables_pager'lp>>`,
		// read more: https://datatables.net/examples/basic_init/dom.html

		lengthMenu: [5, 10, 25, 50],

		pageLength: 10,

		language: {
			lengthMenu: "Mostrar _MENU_",
			infoFiltered: "(filtrado de _MAX_ entradas totales)",
			emptyTable: "No hay datos disponibles en la tabla",
			zeroRecords: "No se encontraron coincidencias",
			loadingRecords: "Cargando...",
			processing: "Procesando...",
		},

		searchDelay: 500,
		processing: true,
    });
});

function search() {
    $name = $('#name').val();
    $status = $('#status').val();


    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/inventario',
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

function executeExample(product_details_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará este traslado!",
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
                title: 'Eliminando traslado',
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
            location.href = "<?php echo base_url();?>admin/traslados/delete/" + product_details_id;
        }
    })
}
</script>