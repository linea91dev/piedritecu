<?php $moneda = $this->crud_model->get_info("moneda"); ?>    
	<div class="container-fluid">
        <div class="row">
			<div class="col-xl-12">
				<div class="card card-custom">
					<div class="card-header flex-wrap border-0 pt-6 pb-0">
						<div class="card-title">
							<h3 class="card-label">Registro de servicios de transporte
							<span class="d-block text-muted pt-2 font-size-sm"><?php echo $this->db->get_where('transport', array('transport_id' => $id_transporte))->row()->name;?></span></h3>
						</div>
						<div class="card-toolbar">
							<?php
					        $transports = $this->crud_model->get_services_transports($id_transporte);
					        if($transports->num_rows() > 0  && ($user_type == 1 || $permisos['reportes_servicios'] == 1)): ?>
							
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
										<li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">Exportar como:</li>
										<li class="navi-item">
											<a href="<?php echo base_url(); ?>admin/export_excel/transportservices" class="navi-link">
												<span class="navi-icon">
													<i class="la la-file-excel-o"></i>
												</span>
												<span class="navi-text">Excel</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="<?php echo base_url(); ?>admin/export_pdf/transportservices" class="navi-link">
												<span class="navi-icon">
													<i class="la la-file-pdf-o"></i>
												</span>
												<span class="navi-text">PDF</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
							<?php endif; if($user_type == 1 || $permisos['registrar_servicios'] == 1):?>
							<a href="javascript:void(0);" class="btn btn-primary font-weight-bolder" onclick="showModal('<?php echo base_url();?>modal/popup/servicio_transporte/<?php echo $id_transporte;?>');">
							    <span class="svg-icon svg-icon-md">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<circle fill="#000000" cx="9" cy="15" r="6" />
											<path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
										</g>
									</svg>
								</span> Nuevo Servicio
							</a>
							<?php endif;?>
							&nbsp;&nbsp;
							<a href="<?php echo base_url().'admin/transporte/' ;?>"
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
					</div>
					<div class="card-body">
					    <?php
					    if($transports->num_rows() > 0 ): ?>
						<div class="mb-7">
							<div class="row align-items-center">
								<div class="col-lg-9 col-xl-8">
									<div class="row align-items-center">
										<div class="col-md-4 my-2 my-md-0">
											<label>Fecha:</label>
                                			<input type="text" class="form-control datatable-input" id="kt_datepicker"
                                    			placeholder="<?php echo date("m/d/Y");?>" data-col-index="2" readonly>
										</div>
									</div>
								</div>
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
						<div class="table-responsive">
    						<table class="table table-bordered dataTable no-footer dtr-inline" id="kt_datatable">
    							<thead>
    								<tr>
    									<th title="Field #1">ID</th>
    									<th title="Field #2">Lugar servicio</th>
    									<th title="Field #3">Fecha servicio</th>
    									<th title="Field #4">Responsable</th>
    									<th title="Field #5">Forma de Pago</th>
    									<th title="Field #6">Precio</th>
    									<th title="Field #7">Próximo servicio</th>
    									<th title="Field #8">Detalles</th>
    								</tr>
    							</thead>
    							<tbody>
    							    <?php foreach($transports->result_array() as $rrow):?>
    								<tr>
    									<td><?php echo $rrow['service_transport_id'];?></td>
    									<td><?php echo $rrow['place_service'];?></td>
    									<td>
											<span class="badge badge-primary"><?php setlocale(LC_TIME, "spanish"); 
												$Nueva_Fecha = date("d-m-Y", strtotime( $rrow['date'] ));
												$Mes_Anyo = strftime("%m/%d/%Y", strtotime($Nueva_Fecha)); 
												echo $Mes_Anyo;?>
											</span>
										</td>
    									<td><?php echo $this->crud_model->getName('admin',$rrow['responsable']);?></td>
										<td>
    									<?php if($rrow['payment_method'] == 0) echo 'Efectivo';
											if($rrow['payment_method'] == 1) echo 'Cheque';
											if($rrow['payment_method'] == 2) echo 'Tarjeta Débito';
											if($rrow['payment_method'] == 3) echo 'Transferencia';
										?>
										</td>
    									<td><span class="label label-lg font-weight-bolder label-light-success label-inline"><?php echo $moneda.number_format($rrow['price'], '2', '.', ',');?></span></td>
    									<td>
											<span class="badge badge-danger">
												<?php if($rrow['next_service'] != "") echo date('d M, Y', strtotime($rrow['next_service']));
													else echo "No definido";?>
											</span>
										</td>
    									<td><?php echo $rrow['details'];?></td>
    								</tr>
    								<?php endforeach;?>
    							</tbody>
    						</table>
						</div>
						<?php else: ?>
						<center>
						    <h3>Sin datos</h3><br>
						    <img src="<?php echo base_url();?>uploads/empty.jpg" style="max-width:35%">
						</center>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

    <script type="text/javascript">
let timerInterval

function executeExample(admin_id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Se eliminará el transporte",
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
            location.href = "<?php echo base_url();?>admin/transporte/delete/" + admin_id;
        }
    })
}
</script>