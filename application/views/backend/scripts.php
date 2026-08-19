    <script>
var KTAppSettings = {
    "breakpoints": {
        "sm": 576,
        "md": 768,
        "lg": 992,
        "xl": 1200,
        "xxl": 1200
    },
    "colors": {
        "theme": {
            "base": {
                "white": "#ffffff",
                "primary": "#8950FC",
                "secondary": "#E5EAEE",
                "success": "#1BC5BD",
                "info": "#8950FC",
                "warning": "#FFA800",
                "danger": "#F64E60",
                "light": "#F3F6F9",
                "dark": "#212121"
            },
            "light": {
                "white": "#ffffff",
                "primary": "#E1E9FF",
                "secondary": "#ECF0F3",
                "success": "#C9F7F5",
                "info": "#EEE5FF",
                "warning": "#FFF4DE",
                "danger": "#FFE2E5",
                "light": "#F3F6F9",
                "dark": "#D6D6E0"
            },
            "inverse": {
                "white": "#ffffff",
                "primary": "#ffffff",
                "secondary": "#212121",
                "success": "#ffffff",
                "info": "#ffffff",
                "warning": "#ffffff",
                "danger": "#ffffff",
                "light": "#464E5F",
                "dark": "#ffffff"
            }
        },
        "gray": {
            "gray-100": "#F3F6F9",
            "gray-200": "#ECF0F3",
            "gray-300": "#E5EAEE",
            "gray-400": "#D6D6E0",
            "gray-500": "#B5B5C3",
            "gray-600": "#80808F",
            "gray-700": "#464E5F",
            "gray-800": "#1B283F",
            "gray-900": "#212121"
        }
    },
    "font-family": "Poppins"
};
    </script>
    <script src="<?php echo base_url();?>public/assets/plugins/global/plugins.bundle.js"></script>
    <script src="<?php echo base_url();?>public/assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
    <script src="<?php echo base_url();?>public/assets/js/scripts.bundle.js"></script>
    <script src="<?php echo base_url();?>public/assets/plugins/custom/datatables/datatables.bundle.js"></script>
    <script src="<?php echo base_url();?>public/assets/js/pages/crud/datatables/basic/basic.js?v=7.2.9"></script>
    <?php if (in_array($page_name, array('agregar_empleado', 'perfil_empleado'), true)): ?>
    <script>
    jQuery(document).ready(function($) {
        var dateOpts = {
            language: 'es',
            format: 'dd/mm/yyyy',
            todayHighlight: true,
            autoclose: true,
            templates: {
                leftArrow: '<i class="la la-angle-left"></i>',
                rightArrow: '<i class="la la-angle-right"></i>'
            }
        };

        if ($('#kt_datepicker').length) {
            $('#kt_datepicker').datepicker('destroy').datepicker(dateOpts);
        }
        if ($('#kt_datepicker_1').length) {
            $('#kt_datepicker_1').datepicker('destroy').datepicker(dateOpts);
        }
    });
    </script>
    <?php endif; ?>
    <?php if($page_name == 'reportes_ventas' ):?>
    <script src="<?php echo base_url();?>public/assets/js/pages/widgets.js"></script>
    <?php endif;?>
    <script src="<?php echo base_url();?>public/assets/js/pages/custom/profile/profile.js?v=7.2.9"></script>
    <?php if($page_name == 'calendario'):?>
    <script src="<?php echo base_url();?>public/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
    <script src="<?php echo base_url();?>public/assets/js/pages/custom/education/school/calendar.js?v=7.2.9"></script>
    <?php endif;?>
    <?php if ($this->session->flashdata('flash_message') != ""):?>
    <script>
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

Toast.fire({
    icon: 'success',
    title: '<?php echo $this->session->flashdata("flash_message");?>'
})
    </script>
    <?php endif;?>

    <?php if ($this->session->flashdata('flash_error') != ""):?>
    <script>
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 4000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

Toast.fire({
    icon: 'error',
    title: '<?php echo $this->session->flashdata("flash_error");?>'
})
    </script>
    <?php endif;?>
    
       <script>
$(".form").bind("submit", function() {
    $(this).find(':button[type=submit]').prop('disabled', true);
});
    </script>
    
    
    
    
    <script>
        enviando = false; 
        function checkSubmit() 
        {
            if(!enviando) 
            {
                enviando = true;
                return true;
            }
            else 
            {
                alert("Ya presionó el botón una vez, por favor espere");
                return false;
            }
        }
    </script>