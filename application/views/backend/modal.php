    <script type="text/javascript">
function showAjaxModal(url) {
    jQuery('#exampleModal .modal-body').html(
        '<div style="text-align: center;"><img src="<?php echo base_url();?>uploads/Spinner-5.gif" /></div>'
    );
    jQuery('#exampleModal').modal('show', {
        backdrop: 'true'
    });
    $.ajax({
        url: url,
        success: function(response) {
            console.log(url);
            jQuery('#exampleModal .modal-body').html(response);
        }
    });
}

function showModal(url) {
    jQuery('#exmpMdl .modal-body').html(
        '<div style="text-align: center;"><img src="<?php echo base_url();?>uploads/Spinner-5.gif" /></div>'
    );
    jQuery('#exmpMdl').modal('show', {
        backdrop: 'true'
    });
    $.ajax({
        url: url,
        success: function(response) {
            console.log(url);
            jQuery('#exmpMdl .modal-body').html(response);
        }
    });
}

function showModalAn(url) {
    jQuery('#anulaciones .modal-body').html(
        '<div style="text-align: center;"><img src="<?php echo base_url();?>uploads/Spinner-5.gif" /></div>'
    );
    jQuery('#anulaciones').modal('show', {
        backdrop: 'true'
    });
    $.ajax({
        url: url,
        success: function(response) {
            console.log(url);
            jQuery('#anulaciones .modal-body').html(response);
        }
    });
}

function showModalCodigos(url) {
    jQuery('#codigos .modal-body').html(
        '<div style="text-align: center;"><img src="<?php echo base_url();?>uploads/Spinner-5.gif" /></div>'
    );
    jQuery('#codigos').modal('show', {
        backdrop: 'true'
    });
    $.ajax({
        url: url,
        success: function(response) {
            console.log(url);
            jQuery('#codigos .modal-body').html(response);
        }
    });
}

function showModalTras(url) {
    jQuery('#traslados .modal-body').html(
        '<div style="text-align: center;"><img src="<?php echo base_url();?>uploads/Spinner-5.gif" /></div>'
    );
    jQuery('#traslados').modal('show', {
        backdrop: 'true'
    });
    $.ajax({
        url: url,
        success: function(response) {
            console.log(url);
            jQuery('#traslados .modal-body').html(response);
        }
    });
}

function showModalReportes(url) {
    jQuery('#reportes .modal-body').html(
        '<div style="text-align: center;"><img src="<?php echo base_url();?>uploads/Spinner-5.gif" /></div>'
    );
    jQuery('#reportes').modal('show', {
        backdrop: 'true'
    });
    $.ajax({
        url: url,
        success: function(response) {
            console.log(url);
            jQuery('#reportes .modal-body').html(response);
        }
    });
}

function showModalProductos(url) {
    jQuery('#productos .modal-body').html(
        '<div style="text-align: center;"><img src="<?php echo base_url();?>uploads/Spinner-5.gif" /></div>'
    );
    jQuery('#productos').modal('show', {
        backdrop: 'true'
    });
    $.ajax({
        url: url,
        success: function(response) {
            console.log(url);
            jQuery('#productos .modal-body').html(response);
        }
    });
}
    </script>

    <div aria-hidden="true" class="onboarding-modal modal fade animated" role="dialog" tabindex="-1" id="exampleModal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exmpMdlLabel"> Actualizar datos </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>


    <div aria-hidden="true" class="onboarding-modal modal fade animated" role="dialog" tabindex="-1" id="exmpMdl">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exmpMdlLabel"> Agregar datos </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    
    <div aria-hidden="true" class="onboarding-modal modal fade animated" role="dialog" tabindex="-1" id="anulaciones">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exmpMdlLabel"> Anulación </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    
    <div aria-hidden="true" class="onboarding-modal modal fade animated" role="dialog" tabindex="-1" id="codigos">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exmpMdlLabel"> Código de autorización </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

    <div aria-hidden="true" class="onboarding-modal modal fade animated" role="dialog" tabindex="-1" id="traslados">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exmpMdlLabel"> Motivo del traslado </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

    <div aria-hidden="true" class="onboarding-modal modal fade animated" role="dialog" tabindex="-1" id="reportes">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exmpMdlLabel"> Reporte de venta </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

    <div aria-hidden="true" class="onboarding-modal modal fade animated" role="dialog" tabindex="-1" id="productos">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exmpMdlLabel"> Productos </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i aria-hidden="true" class="ki ki-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>

    <div aria-hidden="true" class="onboarding-modal modal fade animated" role="dialog" tabindex="-1" id="ValidandoFEL">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h1 class='text-center'> <b> Obteniendo autorización FEL.  </b></h1> 
                    <div style="text-align: center;"><img src="<?php echo base_url();?>uploads/Spinner-5.gif" /></div>
                </div>
            </div>
        </div>
    </div>