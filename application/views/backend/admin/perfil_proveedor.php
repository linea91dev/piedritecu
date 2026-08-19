<?php $data = $this->db->get_where('provider' , array('provider_id' => $ID)); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <div class="row">
                            <div class="col-sm-10">
                                <h3 class="card-label">Historial de Compras</h3>
                            </div>
                            <div class="col-sm-2">
                                <a href="<?php echo  base_url().'admin/proveedores/' ;?>"
                                    class="btn btn-light-primary font-weight-bolder">
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
                                    </span> Regresar
                                </a>
                            </div>
                            <div class="col-sm-12">
                                <br>
                                <span class="alert alert-blue d-block pt-2 font-size-sm">En esta sección podrás visualizar toda la actividad comercial que haz tenido con tu proveedor, como historial de compras y transacciones.
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($historial->num_rows() > 0):?>
                    <div class="table-responsive">
                        <table class="table table-bordered dataTable no-footer dtr-inline" id="kt_datatable">
                            <thead>
                                <tr>
                                    <th title="Field #1">ID</th>
                                    <th title="Field #2">Fecha</th>
                                    <th title="Field #3">Total</th>
                                    <th title="Field #4">Estado</th>
                                    <th title="Field #5">Encargado</th>
                                    <th title="Field #6">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id='table'>
                                <?php $n=1;foreach ($historial->result_array() as $com) :?>
                                <tr>
                                    <td><?php echo $n++;?></td>
                                    <td><span class="text-info"><b><?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d-m-Y", strtotime( $com['date'] ));				
                                        $Mes_Anyo = strftime("%d de %B de %Y", strtotime($Nueva_Fecha)); 
                                        echo $Mes_Anyo;?></b></span></td>
                                    <td><span
                                            class="label label-lg font-weight-bolder label-light-info label-inline"><?php echo $moneda.number_format($com['total'],2,'.',',')?></span>
                                    </td>
                                    <td><span
                                            class="badge badge-<?php if($com['type']==3){echo 'warning';}elseif($com['type']==2){echo 'info';}elseif($com['type']==1){echo 'success';} ?>"><?php if($com['type']==3){echo 'Solicitud de compra ';}elseif($com['type']==2){echo 'Orden de compra';}elseif($com['type']==1){echo 'Completado';} ?></span>
                                    </td>
                                    <td><?php echo $this->crud_model->getName('admin', $com['responsable']);?></td>
                                    <td>
                                        <div class="d-flex flex-shrink-0">
                                            <?php if($com['type']==2 && ($user_type == 1 || $permisos['completar_solicitud'] == 1)):?>
                                            &nbsp;
                                            <a href="<?php echo base_url().'admin/nueva_compra_s/'.$com['code'];?>"
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
                                            <?php if($com['type']==3 && ($user_type == 1 || $permisos['ver_solicitud'] == 1)):?>
                                            &nbsp;
                                            <a href="<?php echo base_url().'provider/purchase_details/'.$com['code'];?>"
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
                                            &nbsp;
                                            <?php if($user_type == 1 || $permisos['ver_compras'] == 1):?>
                                            <a href="<?php echo base_url().'admin/compra_details/'.$com['code'];?>"
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
                                            <?php endif;?>
                                            &nbsp;
                                            <?php if($user_type == 1 || $permisos['reportes_compras'] == 1):?>
                                            <a href="<?php echo base_url().'admin/export_pdf/compra/'.$com['code'];?>"
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
                                            <?php endif;?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <center>
                        <h3>Sin datos</h3><br>
                        <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width: 200px; margin-top: 100px; margin-bottom: 100px; margin-top: 75px; margin-botton: 125px;">
                    </center>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var avatar4 = new KTImageInput('kt_image_4');

avatar4.on('cancel', function(imageInput) {
    swal.fire({
        title: 'Image successfully canceled !',
        type: 'success',
        buttonsStyling: false,
        confirmButtonText: 'Awesome!',
        confirmButtonClass: 'btn btn-primary font-weight-bold'
    });
});

avatar4.on('change', function(imageInput) {
    swal.fire({
        title: 'Image successfully changed !',
        type: 'success',
        buttonsStyling: false,
        confirmButtonText: 'Awesome!',
        confirmButtonClass: 'btn btn-primary font-weight-bold'
    });
});

avatar4.on('remove', function(imageInput) {
    swal.fire({
        title: 'Image successfully removed !',
        type: 'error',
        buttonsStyling: false,
        confirmButtonText: 'Got it!',
        confirmButtonClass: 'btn btn-primary font-weight-bold'
    });
});

function searchEmail() {
    var email = $('#email_prov_profile').val();
    var ID = '<?php echo $ID?>';
    $.ajax({
        type: "POST",
        url: '<?php echo base_url();?>admin/search/search_email/provider',
        data: {
            email: email,
            ID: ID,
        },
        success: function(response) {
            if (response == 1) {
                $('#msg_email_prov_profile').html(" ");
                $('#profile_prov_submit').removeAttr('disabled');
            } else if (response == 0) {
                $('#msg_email_prov_profile').html("Correo eléctronico no disponible");
                $('#profile_prov_submit').attr('disabled', 'disabled');
            } else if (response == 2) {
                $('#msg_email_prov_profile').html(" ");
                $('#profile_prov_submit').removeAttr('disabled');
            }
        },
        error: function(e) {
            console.log("ERROR : ", e);
        }
    });
}
</script>