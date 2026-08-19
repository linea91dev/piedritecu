<div class="container-fluid">
    <div class="row">
        <div class="col-xl-12">
            <div class="card-body">
                <div class="card card-custom">
                    <div class="card-header flex-wrap border-0 pt-6 pb-0">
                        <h3 class="card-title align-items-start flex-column">
                                <span class="card-label font-weight-bolder text-dark">Calendario</span>
                            
                        </h3>
                        <?php if($user_type == 1 || $permisos['crear_eventos'] == 1):?>
                        <a href="javascript:;" onclick=" showModal('<?php echo base_url().'/modal/popup/calendario_add/';?>')"
                            class="btn btn-danger font-weight-bold py-3 px-6">
                            <i class="ki ki-plus icon-1x mr-2"></i>Agregar evento
                        </a>
                        <?php endif;?>
                        <div class="card-toolbar">
                            <div class="alert alert-warning" style="width: auto !important;">
                                <span class="d-block pt-2 font-size-sm">Aquí podrás registrar tus actividades diarias, tanto personales como laborales. Para agregar una solamente haz clic en la fecha deseada o bien en el botón <b>+ Agregar evento</b>.
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-10">
                        <div id="kt_calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
let base_url = '<?php echo base_url();?>';
let data = [
    <?php foreach($data->result_array() as $event): 
        $start =  $event['date_start'].'T'.$event['time_start'];
        $end   =  $event['date_end'].'T'.$event['time_end'];
        ?> {
        id: '<?php echo $event['events_id']; ?>',
        title: '<?php echo $event['title']; ?>',
        start: '<?php echo $start;?>',
        end: '<?php echo  $end;?>',
        className: 'cks',
        color: '<?php echo $event['color']; ?>',
        description: '<?php echo preg_replace("/[\r\n|\n|\r]+/", " ",$event['description']) ;?>',
    },
    <?php endforeach; ?>
];
</script>