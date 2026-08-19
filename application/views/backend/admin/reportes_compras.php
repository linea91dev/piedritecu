<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">Reporte de compras de productos
                            <span class="d-block text-muted pt-2 font-size-sm">Genera y administra la información de tus
                                reportes de compras a proveedores.</span>
                        </h3>
                    </div>
                </div>
                <div class="card-body">
                    <form class="mb-15" method="POST" action="<?php echo base_url().'admin/reportes/compras/'?>">
                        <div class="row mb-6">
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Proveedor:</label>
                                <select class=" form-control datatable-input" data-col-index="2" name="provider_id" id="selected-1">
                                    <option value="0">Todos</option>
                                    <?php $providers = $this->crud_model->get_provider()->result_array(); log_message("error", "Provider id: $provider_id"); 
                                        foreach($providers as $prov):?>
                                    <option value='<?php echo $prov['provider_id'];?>' <?php if($provider_id == $prov['provider_id']) echo "selected";?>><?php echo $prov['name'];?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Fecha inicial:</label>
                                <div class="input-daterange input-group">
                                    <input type="date" class="form-control datatable-input" name="initial"
                                        autocomplete="off" value="<?php echo $initial;?>">
                                </div>
                            </div>
                            <div class="col-lg-3 mb-lg-0 mb-6">
                                <label>Fecha final:</label>
                                <div class="input-daterange input-group">
                                    <input type="date" class="form-control datatable-input" name="final"
                                        autocomplete="off" value="<?php echo $final;?>">
                                </div>
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
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered table-hover table-checkable dataTable dtr-inline"
                                    id="kt_datatable" role="grid" aria-describedby="kt_datatable_info"
                                    style="width: 1235px;">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting sorting_asc" tabindex="0" aria-controls="kt_datatable"
                                                rowspan="1" colspan="1" style="width: 72px;" aria-sort="ascending"
                                                aria-label="ID: activate to sort column descending">ID
                                            </th>

                                            <th class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1"
                                                colspan="1" style="width: 126px;"
                                                aria-label="Responsable: activate to sort column ascending">Responsable
                                            </th>

                                            <th class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1"
                                                colspan="1" style="width: 126px;"
                                                aria-label="Responsable: activate to sort column ascending">Proveedor
                                            </th>

                                            <th class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1"
                                                colspan="1" style="width: 121px;"
                                                aria-label="Código: activate to sort column ascending">Código</th>

                                            <th class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1"
                                                colspan="1" style="width: 72px;"
                                                aria-label="Fecha: activate to sort column ascending">Fecha</th>

                                            <th class="sorting" tabindex="0" aria-controls="kt_datatable" rowspan="1"
                                                colspan="1" style="width: 66px;"
                                                aria-label="Cantidad: activate to sort column ascending">Cantidad</th>

                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 116px;"
                                                aria-label="Total">Total</th>

                                            <th class="sorting_disabled" rowspan="1" colspan="1" style="width: 116px;"
                                                aria-label="">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $total = 0;
                                        $this->db->select('*');
                                            $this->db->where("DATE(date) >= DATE('$initial')", NULL, FALSE);
                                            $this->db->where("DATE(date) <= DATE('$final')", NULL, FALSE);
                                            if($provider_id > 0) $this->db->where("provider", $provider_id);
                                            $this->db->where("status", 1);
                                            $data = $this->db->get("shopping"); 
                                            $n=1; $moneda = $this->db->get_where('settings', array('type'=>'moneda'))->row()->description; 
                                        foreach ($data->result_array() as $row): 
                                        $total=$total+$row['total'];?>
                                        <tr class="odd">
                                            <td class="sorting_1 dtr-control"><?php echo $n++;?></td>
                                            <td><?php echo $this->crud_model->getName('admin', $row['responsable']);?></td>
                                            <td><?php echo $this->crud_model->getSingleName('provider', $row['provider']);?></td>
                                            <td><?php echo $row['code'];?>
                                            </td>
                                            <td><?php setlocale(LC_TIME, "spanish");
                                        $Nueva_Fecha = date("d-m-Y", strtotime( $row['date'] ));				
                                        $Mes_Anyo = strftime("%m/%d/%Y", strtotime($Nueva_Fecha)); 
                                        echo $Mes_Anyo;?></td>
                                            <td style="text-align: left;">
                                                    <span
                                                        class="text-warning"><b><?php echo $row['num_products'] ;?></b></span>
                                            </td>
                                            <td style="text-align: left;">
                                                    <span
                                                        class="label label-lg font-weight-bolder label-light-success label-inline"><b><?php echo $moneda.number_format($row['total'],2,'.',',') ;?></b></span>
                                                
                                            </td>
                                            <td style="">
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
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                        <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td><b>Total:</b></td>
                                        <td><b><?php echo $moneda.number_format($total,2,'.',',') ;?></b></td>
                                        </tr>                        
                                    </tbody>
                                </table>
                                <div id="kt_datatable_processing" class="dataTables_processing card"
                                    style="display: none;">Buscando...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('#selected-1').select2({
        language: "es",
        placeholder: 'Seleccionar',
        allowClear: true,
        tags: true
    });
});
</script>