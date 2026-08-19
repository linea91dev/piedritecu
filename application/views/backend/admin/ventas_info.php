<?php $moneda = $this->crud_model->get_info("moneda");  
$regimen=$this->db->get_where('settings',array('type'=>'regimen'))->row()->description / 100; if($param2 == 1 ):?>

<div class="onboarding-content with-gradient">
        <div class="modal-body">
            <div class="row">
                <div class="col-xl-6">
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                            Ventas del día </div>
                </div>
                <div class="col-xl-6" style='text-align: end;'>
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                            Total </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php echo $moneda.$this->crud_model->total_vendido_sinFEL();?></div>
                    
                </div>
                <div class="col-xl-4">
                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                            Ventas del día  </div>
                            <small class='text-info'> (Facturadas)</small>
                    </div>
                <div class="col-xl-2" style='text-align: center;'>
                    
                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                                Monto </div>
                            <div class="font-weight-bold text-inverse-white font-size-h6">
                                <?php $tot_fel = $this->crud_model->total_vendido_FEL(); 
                                //echo $tot_fel;
                                $montoGravable = ($tot_fel /($regimen + 1));  
                                echo $moneda.number_format($montoGravable,2,'.',',');?></div>
                </div>    
                <div class="col-xl-2" style='text-align: center;'>
                    
                <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                            IVA </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php $montoImpuesto = $montoGravable*$regimen;  echo $moneda.number_format($montoImpuesto,2,'.',',');?></div>
                </div>
                
                <div class="col-xl-4" style='text-align: end;'>
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                            Total </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php echo $moneda.$this->crud_model->total_vendido_FEL()?></div>
                    
                </div>

                <div class="col-xl-12 mt-2">
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
                            <span class="font-weight-boldest font-size-h3 line-height-sm" id="total"><?php echo $moneda.$this->crud_model->total_vendido();?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary  font-weight-bold" data-dismiss="modal">Cerrar</button>
        </div>
</div>

<?php  elseif($param2 == 2 ):?>

<div class="onboarding-content with-gradient">
        <div class="modal-body">
            <div class="row">
                <div class="col-xl-6">
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                            Ventas de la semana </div>
                </div>
                <div class="col-xl-6" style='text-align: end;'>
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                            Total </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php echo $moneda.$this->crud_model->total_vendido_sinFEL_s();?></div>
                    
                </div>
                <div class="col-xl-4">
                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                            Ventas de la semana  </div>
                            <small class='text-warning'> (Facturadas)</small>
                    </div>
                <div class="col-xl-2" style='text-align: center;'>
                    
                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                                Precio </div>
                            <div class="font-weight-bold text-inverse-white font-size-h6">
                                <?php $montoGravable = number_format($this->crud_model->total_vendido_FEL_s()/($regimen + 1),6,".",",");   echo $moneda.number_format($montoGravable,2,'.',',');?></div>
                </div>    
                <div class="col-xl-2" style='text-align: center;'>
                    
                <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                            IVA </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php $montoImpuesto = number_format($montoGravable*$regimen,6,".",",");  echo $moneda.number_format($montoImpuesto,2,'.',',');?></div>
                </div>
                
                <div class="col-xl-4" style='text-align: end;'>
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                            Total </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php echo $moneda.$this->crud_model->total_vendido_FEL()?></div>
                    
                </div>

                <div class="col-xl-12 mt-2">
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
                            <span class="font-weight-boldest font-size-h3 line-height-sm" id="total"><?php echo $moneda.$this->crud_model->total_vendido_semana();?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary  font-weight-bold" data-dismiss="modal">Cerrar</button>
        </div>
</div>

<?php  elseif($param2 == 3 ):?>

<div class="onboarding-content with-gradient">
        <div class="modal-body">
            <div class="row">
                <div class="col-xl-6">
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                            Ventas del día </div>
                </div>
                <div class="col-xl-6" style='text-align: end;'>
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                            Total </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php echo $moneda.$this->crud_model->total_vendido_sinFEL_m();?></div>
                    
                </div>
                <div class="col-xl-4">
                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                            Ventas del mes  </div>
                            <small class='text-success'> (Facturadas)</small>
                    </div>
                <div class="col-xl-2" style='text-align: center;'>
                    
                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                                Precio </div>
                            <div class="font-weight-bold text-inverse-white font-size-h6">
                                <?php $montoGravable = number_format($this->crud_model->total_vendido_FEL_m()/($regimen + 1),6,".",",");   echo $moneda.number_format($montoGravable,2,'.',',');?></div>
                </div>    
                <div class="col-xl-2" style='text-align: center;'>
                    
                <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                            IVA </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php $montoImpuesto = number_format($montoGravable*$regimen,6,".",",");  echo $moneda.number_format($montoImpuesto,2,'.',',');?></div>
                </div>
                
                <div class="col-xl-4" style='text-align: end;'>
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                            Total </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php echo $moneda.$this->crud_model->total_vendido_FEL()?></div>
                    
                </div>

                <div class="col-xl-12 mt-2">
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
                            <span class="font-weight-boldest font-size-h3 line-height-sm" id="total"><?php echo $moneda.$this->crud_model->total_vendido_mes();?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary  font-weight-bold" data-dismiss="modal">Cerrar</button>
        </div>
</div>

<?php  elseif($param2 == 4 ):?>

<div class="onboarding-content with-gradient">
        <div class="modal-body">
            <div class="row">
                <div class="col-xl-6">
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                            Ganancia del día </div>
                </div>
                <div class="col-xl-6" style='text-align: end;'>
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                            Total </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php echo $moneda.$this->crud_model->total_vendido_por_dia_sinFEL();?></div>
                    
                </div>
                <div class="col-xl-4">
                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                            Ganancia del día  </div>
                            <small class='text-info'> (Facturadas)</small>
                    </div>
                <div class="col-xl-2" style='text-align: center;'>
                    
                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                                Precio </div>
                            <div class="font-weight-bold text-inverse-white font-size-h6">
                                <?php $montoGravable = number_format($this->crud_model->total_vendido_por_dia_FEL()/($regimen + 1),6,".",",");   echo $moneda.number_format($montoGravable,2,'.',',');?></div>
                </div>    
                <div class="col-xl-2" style='text-align: center;'>
                    
                <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                            IVA </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php $montoImpuesto = number_format($montoGravable*$regimen,6,".",",");  echo $moneda.number_format($montoImpuesto,2,'.',',');?></div>
                </div>
                
                <div class="col-xl-4" style='text-align: end;'>
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-info">
                            Total </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php echo $moneda.$this->crud_model->total_vendido_por_dia_FEL();?></div>
                    
                </div>

                <div class="col-xl-12 mt-2">
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
                            <span class="font-weight-boldest font-size-h3 line-height-sm" id="total"><?php echo $moneda.$this->crud_model->total_vendido_por_dia();?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary  font-weight-bold" data-dismiss="modal">Cerrar</button>
        </div>
</div>

<?php  elseif($param2 == 5 ):?>

<div class="onboarding-content with-gradient">
        <div class="modal-body">
            <div class="row">
                <div class="col-xl-6">
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                            Ganancia por semana </div>
                </div>
                <div class="col-xl-6" style='text-align: end;'>
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                            Total </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php echo $moneda.$this->crud_model-> total_vendido_por_semana_sinFEL();?></div>
                    
                </div>
                <div class="col-xl-4">
                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                            Ganancia de la semana  </div>
                            <small class='text-warning'> (Facturadas)</small>
                    </div>
                <div class="col-xl-2" style='text-align: center;'>
                    
                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                                Precio </div>
                            <div class="font-weight-bold text-inverse-white font-size-h6">
                                <?php $montoGravable = number_format($this->crud_model->total_vendido_por_semana_FEL()/($regimen + 1),6,".",",");   echo $moneda.number_format($montoGravable,2,'.',',');?></div>
                </div>    
                <div class="col-xl-2" style='text-align: center;'>
                    
                <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                            IVA </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php $montoImpuesto = number_format($montoGravable*$regimen,6,".",",");  echo $moneda.number_format($montoImpuesto,2,'.',',');?></div>
                </div>
                
                <div class="col-xl-4" style='text-align: end;'>
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-warning">
                            Total </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php echo $moneda.$this->crud_model->total_vendido_por_semana_FEL();?></div>
                    
                </div>

                <div class="col-xl-12 mt-2">
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
                            <span class="font-weight-boldest font-size-h3 line-height-sm" id="total"><?php echo $moneda.$this->crud_model->total_vendido_mes();?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary  font-weight-bold" data-dismiss="modal">Cerrar</button>
        </div>
</div>

<?php  elseif($param2 == 6 ):?>

<div class="onboarding-content with-gradient">
        <div class="modal-body">
            <div class="row">
                <div class="col-xl-6">
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                            Ganancia por mes </div>
                </div>
                <div class="col-xl-6" style='text-align: end;'>
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                            Total </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php echo $moneda.$this->crud_model->total_vendido_por_mes_sinFEL();?></div>
                    
                </div>
                <div class="col-xl-4">
                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                            Ganancia de la semana  </div>
                            <small class='text-success'> (Facturadas)</small>
                    </div>
                <div class="col-xl-2" style='text-align: center;'>
                    
                    <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                                Precio </div>
                            <div class="font-weight-bold text-inverse-white font-size-h6">
                                <?php $montoGravable = number_format($this->crud_model->total_vendido_por_semana_FEL()/($regimen + 1),6,".",",");   echo $moneda.number_format($montoGravable,2,'.',',');?></div>
                </div>    
                <div class="col-xl-2" style='text-align: center;'>
                    
                <div class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                            IVA </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php $montoImpuesto = number_format($montoGravable*$regimen,6,".",",");  echo $moneda.number_format($montoImpuesto,2,'.',',');?></div>
                </div>
                
                <div class="col-xl-4" style='text-align: end;'>
                    
                        <div
                            class="text-inverse-white font-weight-bolder font-size-h5 mb-2 mt-5 text-success">
                            Total </div>
                        <div class="font-weight-bold text-inverse-white font-size-h6">
                            <?php echo $moneda.$this->crud_model->total_vendido_por_semana_FEL();?></div>
                    
                </div>

                <div class="col-xl-12 mt-2">
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
                            <span class="font-weight-boldest font-size-h3 line-height-sm" id="total"><?php echo $moneda.$this->crud_model->total_vendido_por_mes_FEL();?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-light-primary  font-weight-bold" data-dismiss="modal">Cerrar</button>
        </div>
</div>
<?php endif;?>

