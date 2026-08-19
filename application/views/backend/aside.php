<div class="aside aside-left d-flex flex-column" id="kt_aside">
    <div class="aside-brand d-flex flex-column align-items-center flex-column-auto pt-5 pt-lg-18 pb-10">
        <div class="p-0 symbol symbol-60" href="<?php echo base_url();?>admin/tablero/" id="kt_quick_user_toggle">
            <div class="symbol-label bg-white">
                <a href="<?php echo base_url();?>admin/tablero/"><img alt="Logo"
                        src="<?php echo ($this->db->get_where('settings', array('type'=>'logo'))->row()->description != '')?  base_url().'uploads/img/'.$this->db->get_where('settings', array('type'=>'logo'))->row()->description : base_url().'public/assets/media/users/blank.png' ;?>"
                        style="max-width: 80px!important; height: auto; border-radius: 10%;" /></a>
            </div>
        </div>
        <br>
        <div>
            <?php echo $this->crud_model->getBranch( $this->session->userdata('branch_id'));?>
        </div>
    </div>
    <div class="aside-nav d-flex flex-column align-items-center flex-column-fluid pb-10">
        <ul class="nav flex-column">

            <li class="nav-item mb-2 menu-icon-sidebar" data-toggle="tooltip" data-placement="right"
                data-container="body" data-boundary="window" title="Mi cuenta">
                <a href="<?php echo base_url();?>admin/perfil/"
                    class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path
                                    d="M4,4 L11.6314229,2.5691082 C11.8750185,2.52343403 12.1249815,2.52343403 12.3685771,2.5691082 L20,4 L20,13.2830094 C20,16.2173861 18.4883464,18.9447835 16,20.5 L12.5299989,22.6687507 C12.2057287,22.8714196 11.7942713,22.8714196 11.4700011,22.6687507 L8,20.5 C5.51165358,18.9447835 4,16.2173861 4,13.2830094 L4,4 Z"
                                    fill="#000000" opacity="0.3" />
                                <path
                                    d="M12,11 C10.8954305,11 10,10.1045695 10,9 C10,7.8954305 10.8954305,7 12,7 C13.1045695,7 14,7.8954305 14,9 C14,10.1045695 13.1045695,11 12,11 Z"
                                    fill="#000000" opacity="0.3" />
                                <path
                                    d="M7.00036205,16.4995035 C7.21569918,13.5165724 9.36772908,12 11.9907452,12 C14.6506758,12 16.8360465,13.4332455 16.9988413,16.5 C17.0053266,16.6221713 16.9988413,17 16.5815,17 C14.5228466,17 11.463736,17 7.4041679,17 C7.26484009,17 6.98863236,16.6619875 7.00036205,16.4995035 Z"
                                    fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                    </span>
                </a>
            </li>
            <?php if($user_type == 1 || $permisos['calendario'] == 1):?>
            <li class="nav-item mb-2 menu-icon-sidebar" data-toggle="tooltip" data-placement="right"
                data-container="body" data-boundary="window" title="Calendario">
                <a href="<?php echo base_url();?>admin/calendario/"
                    class="nav-link btn btn-icon btn-hover-text-primary btn-lg active">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <rect fill="#000000" x="2" y="5" width="19" height="4" rx="1" />
                                <rect fill="#000000" opacity="0.3" x="2" y="11" width="19" height="10" rx="1" />
                            </g>
                        </svg>
                    </span>
                </a>
            </li>
            <?php endif; if($user_type == 1 || $permisos['alertas_productos'] == 1):?>
            <li class="nav-item mb-2 menu-icon-sidebar" data-toggle="tooltip" data-placement="right"
                data-container="body" data-boundary="window" title="Productos en alerta">
                <a href="<?php echo base_url();?>admin/alertas/"
                    class="nav-link btn btn-icon btn-hover-text-primary btn-lg active">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path
                                    d="M11.1669899,4.49941818 L2.82535718,19.5143571 C2.557144,19.9971408 2.7310878,20.6059441 3.21387153,20.8741573 C3.36242953,20.9566895 3.52957021,21 3.69951446,21 L21.2169432,21 C21.7692279,21 22.2169432,20.5522847 22.2169432,20 C22.2169432,19.8159952 22.1661743,19.6355579 22.070225,19.47855 L12.894429,4.4636111 C12.6064401,3.99235656 11.9909517,3.84379039 11.5196972,4.13177928 C11.3723594,4.22181902 11.2508468,4.34847583 11.1669899,4.49941818 Z"
                                    fill="#000000" opacity="0.3" />
                                <rect fill="#000000" x="11" y="9" width="2" height="7" rx="1" />
                                <rect fill="#000000" x="11" y="17" width="2" height="2" rx="1" />
                            </g>
                        </svg>
                    </span>
                </a>
            </li>
            <?php endif; if($user_type == 1 || $permisos['sucursales'] == 1):?>
            <li class="nav-item mb-2 menu-icon-sidebar" data-toggle="tooltip" data-placement="right"
                data-container="body" data-boundary="window" title="Sucursales">
                <a href="<?php echo base_url();?>admin/sucursales/"
                    class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <path
                                    d="M3.5,3 L5,3 L5,19.5 C5,20.3284271 4.32842712,21 3.5,21 L3.5,21 C2.67157288,21 2,20.3284271 2,19.5 L2,4.5 C2,3.67157288 2.67157288,3 3.5,3 Z"
                                    fill="#000000" />
                                <path
                                    d="M6.99987583,2.99995344 L19.754647,2.99999303 C20.3069317,2.99999474 20.7546456,3.44771138 20.7546439,3.99999613 C20.7546431,4.24703684 20.6631995,4.48533385 20.497938,4.66895776 L17.5,8 L20.4979317,11.3310353 C20.8673908,11.7415453 20.8341123,12.3738351 20.4236023,12.7432941 C20.2399776,12.9085564 20.0016794,13 19.7546376,13 L6.99987583,13 L6.99987583,2.99995344 Z"
                                    fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                    </span>
                </a>
            </li>
            <?php endif; if($user_type == 1 || $permisos['configuracion'] == 1):?>
            <li class="nav-item mb-2 menu-icon-sidebar" data-toggle="tooltip" data-placement="right"
                data-container="body" data-boundary="window" title="Configuración">
                <a href="<?php echo base_url();?>admin/configuracion/"
                    class="nav-link btn btn-icon btn-hover-text-primary btn-lg">
                    <span class="svg-icon svg-icon-primary svg-icon-2x">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                            height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect opacity="0.200000003" x="0" y="0" width="24" height="24" />
                                <path
                                    d="M4.5,7 L9.5,7 C10.3284271,7 11,7.67157288 11,8.5 C11,9.32842712 10.3284271,10 9.5,10 L4.5,10 C3.67157288,10 3,9.32842712 3,8.5 C3,7.67157288 3.67157288,7 4.5,7 Z M13.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L13.5,18 C12.6715729,18 12,17.3284271 12,16.5 C12,15.6715729 12.6715729,15 13.5,15 Z"
                                    fill="#000000" opacity="0.3" />
                                <path
                                    d="M17,11 C15.3431458,11 14,9.65685425 14,8 C14,6.34314575 15.3431458,5 17,5 C18.6568542,5 20,6.34314575 20,8 C20,9.65685425 18.6568542,11 17,11 Z M6,19 C4.34314575,19 3,17.6568542 3,16 C3,14.3431458 4.34314575,13 6,13 C7.65685425,13 9,14.3431458 9,16 C9,17.6568542 7.65685425,19 6,19 Z"
                                    fill="#000000" />
                            </g>
                        </svg>
                    </span>
                </a>
            </li>
            <?php endif;?>
        </ul>
    </div>

    <div class="aside-footer d-flex flex-column align-items-center flex-column-auto py-8">
        <a href="javascript:void(0);" class="btn btn-icon btn-hover-text-primary btn-lg mb-1 position-relative"
            id="kt_quick_notifications_toggle" data-toggle="tooltip" data-placement="right" data-container="body"
            data-boundary="window" title="Notificaciones">
            <span class="svg-icon svg-icon-primary svg-icon-2x">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24" />
                        <path
                            d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z"
                            fill="#000000" />
                        <circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5" />
                    </g>
                </svg>
            </span>
            <?php $nots = $this->crud_model->get_nots_mes();
                $rows = $nots->num_rows(); $pends = ''; if($rows >= 100) $pends = '+99'; else $pends = $rows; ?>
            <?php if($rows > 0): ?>
            <span
                class="label label-sm label-light-danger label-rounded font-weight-bolder position-absolute top-0 right-0 mt-1 mr-1">
                <?php echo $pends; ?>
            </span>
            <?php endif; ?>
        </a>

        <div class="dropdown" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window"
            title="" data-original-title="Sucursales" aria-describedby="tooltip494747">

            <a href="#" class="btn btn-icon btn-hover-text-primary btn-lg" data-toggle="dropdown" data-offset="0px,0px"
                aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                    height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24"></rect>
                        <path
                            d="M3.5,3 L5,3 L5,19.5 C5,20.3284271 4.32842712,21 3.5,21 L3.5,21 C2.67157288,21 2,20.3284271 2,19.5 L2,4.5 C2,3.67157288 2.67157288,3 3.5,3 Z"
                            fill="#8950fc"></path>
                        <path
                            d="M6.99987583,2.99995344 L19.754647,2.99999303 C20.3069317,2.99999474 20.7546456,3.44771138 20.7546439,3.99999613 C20.7546431,4.24703684 20.6631995,4.48533385 20.497938,4.66895776 L17.5,8 L20.4979317,11.3310353 C20.8673908,11.7415453 20.8341123,12.3738351 20.4236023,12.7432941 C20.2399776,12.9085564 20.0016794,13 19.7546376,13 L6.99987583,13 L6.99987583,2.99995344 Z"
                            fill="#8950fc" opacity="0.3"></path>
                    </g>
                </svg>
            </a>

            <div class="dropdown-menu p-0 m-0 dropdown-menu-anim-up dropdown-menu-sm dropdown-menu-left" style="">
                <ul class="navi navi-hover py-4">
                    <?php $sucursales = $this->db->get_Where('admin', array('status'=>1,'admin_id'=>$this->session->userdata('login_user_id')))->row()->sucursal; 
                    $sucursales = unserialize($sucursales); 
                    if(!in_array($this->session->userdata('branch_id'), $sucursales))
                    {
                        $this->session->set_userdata('branch_id', $sucursales[0]);
                    }
                    foreach ( $sucursales as $row):
                    ?>
                    <li class="navi-item">
                        <a href="<?php echo base_url().'admin/change_branch/'.$row;?>" class="navi-link">
                            <span class="symbol symbol-20 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"></rect>
                                        <path
                                            d="M3.5,3 L5,3 L5,19.5 C5,20.3284271 4.32842712,21 3.5,21 L3.5,21 C2.67157288,21 2,20.3284271 2,19.5 L2,4.5 C2,3.67157288 2.67157288,3 3.5,3 Z"
                                            fill="<?php echo $this->session->userdata('branch_id')==$row ? 'red':'#8950fc'; ?>">
                                        </path>
                                        <path
                                            d="M6.99987583,2.99995344 L19.754647,2.99999303 C20.3069317,2.99999474 20.7546456,3.44771138 20.7546439,3.99999613 C20.7546431,4.24703684 20.6631995,4.48533385 20.497938,4.66895776 L17.5,8 L20.4979317,11.3310353 C20.8673908,11.7415453 20.8341123,12.3738351 20.4236023,12.7432941 C20.2399776,12.9085564 20.0016794,13 19.7546376,13 L6.99987583,13 L6.99987583,2.99995344 Z"
                                            fill="<?php echo $this->session->userdata('branch_id')==$row ? 'red':'#8950fc'; ?>"
                                            opacity="<?php echo $this->session->userdata('branch_id')==$row ? '1':'0.3'; ?>">
                                        </path>
                                    </g>
                                </svg>
                            </span>
                            <span class="navi-text"
                                <?php echo $this->session->userdata('branch_id')==$row ? 'style="color:red"':''; ?>><?php echo $this->db->get_where('branch',array('branch_id'=>$row))->row()->name;?>
                            </span>
                        </a>
                    </li>
                    <?php endforeach ;?>
                </ul>
            </div>
        </div>

        <div class="dropdown" data-toggle="tooltip" data-placement="right" data-container="body" data-boundary="window"
            title="Salir del sistema">
            <a href="javascript:void(0)" onclick="logout()" class="btn btn-icon btn-hover-text-primary btn-lg">
                <span class="svg-icon svg-icon-primary svg-icon-2x">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                        height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24" />
                            <path
                                d="M14.0069431,7.00607258 C13.4546584,7.00607258 13.0069431,6.55855153 13.0069431,6.00650634 C13.0069431,5.45446114 13.4546584,5.00694009 14.0069431,5.00694009 L15.0069431,5.00694009 C17.2160821,5.00694009 19.0069431,6.7970243 19.0069431,9.00520507 L19.0069431,15.001735 C19.0069431,17.2099158 17.2160821,19 15.0069431,19 L3.00694311,19 C0.797804106,19 -0.993056895,17.2099158 -0.993056895,15.001735 L-0.993056895,8.99826498 C-0.993056895,6.7900842 0.797804106,5 3.00694311,5 L4.00694793,5 C4.55923268,5 5.00694793,5.44752105 5.00694793,5.99956624 C5.00694793,6.55161144 4.55923268,6.99913249 4.00694793,6.99913249 L3.00694311,6.99913249 C1.90237361,6.99913249 1.00694311,7.89417459 1.00694311,8.99826498 L1.00694311,15.001735 C1.00694311,16.1058254 1.90237361,17.0008675 3.00694311,17.0008675 L15.0069431,17.0008675 C16.1115126,17.0008675 17.0069431,16.1058254 17.0069431,15.001735 L17.0069431,9.00520507 C17.0069431,7.90111468 16.1115126,7.00607258 15.0069431,7.00607258 L14.0069431,7.00607258 Z"
                                fill="#000000" fill-rule="nonzero" opacity="0.3"
                                transform="translate(9.006943, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-9.006943, -12.000000) " />
                            <rect fill="#000000" opacity="0.3"
                                transform="translate(14.000000, 12.000000) rotate(-270.000000) translate(-14.000000, -12.000000) "
                                x="13" y="6" width="2" height="12" rx="1" />
                            <path
                                d="M21.7928932,9.79289322 C22.1834175,9.40236893 22.8165825,9.40236893 23.2071068,9.79289322 C23.5976311,10.1834175 23.5976311,10.8165825 23.2071068,11.2071068 L20.2071068,14.2071068 C19.8165825,14.5976311 19.1834175,14.5976311 18.7928932,14.2071068 L15.7928932,11.2071068 C15.4023689,10.8165825 15.4023689,10.1834175 15.7928932,9.79289322 C16.1834175,9.40236893 16.8165825,9.40236893 17.2071068,9.79289322 L19.5,12.0857864 L21.7928932,9.79289322 Z"
                                fill="#000000" fill-rule="nonzero"
                                transform="translate(19.500000, 12.000000) rotate(-90.000000) translate(-19.500000, -12.000000) " />
                        </g>
                    </svg>
                </span>
            </a>
        </div>

    </div>
</div>
<script type="text/javascript">
function logout() {
    Swal.fire({
        title: '¿Cerrar sesión?',
        text: "Deberá iniciar sesión nuevamente para poder acceder al sistema.",
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#9fd13b',
        cancelButtonColor: '#fd4f57',
        confirmButtonText: 'Sí, cerrar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.value) {
            location.href = "<?php echo base_url();?>login/logout/";
        }
    })
}
</script>