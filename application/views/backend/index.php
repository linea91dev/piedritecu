<?php
	$account_type =	$this->session->userdata('login_type'); 
    $system_title = $this->db->get_where('settings',array('type'=>'name'))->row()->description;
    $user_type    = $this->session->userdata('login_user_type');
    $permisos     = unserialize($this->session->userdata('permissions'));
    $branch_id    = $this->session->userdata('branch_id');
    $job_id       = $this->session->userdata('job_id');
    log_message("error", "Job: $job_id");
?>
<!DOCTYPE html>
<html lang="es">

    <head>
        <title><?php echo $page_title;?> | <?php echo $system_title;?></title>
        <meta charset="utf-8">
        <meta name="description" content="Updates and statistics" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta http-equiv='cache-control' content='no-cache'>
        <meta http-equiv='expires' content='0'>
        <meta http-equiv='pragma' content='no-cache'>
        <?php include 'topcss.php';?>
    </head>

    <body id="kt_body" class="header-fixed header-mobile-fixed sidebar-enabled page-loading">
        <?php include 'mobile.php';?>
        <div class="d-flex flex-column flex-root">
            <div class="d-flex flex-row flex-column-fluid page">
                <?php include 'aside.php';?>
                <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                    <?php include $account_type.'/navigation.php';?>
                    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                        <div class="d-flex flex-column-fluid">
                            <?php include $account_type.'/'.$page_name.'.php';?>
                        </div>
                    </div>
                    <?php include 'footer.php';?>
                </div>
                <?php include 'sidebar-left.php';?>
            </div>
        </div>
        <?php include 'notifications.php';?>
        <div id="kt_scrolltop" class="scrolltop">
            <span class="svg-icon">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
                        <path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
                    </g>
                </svg>
            </span>
        </div>
        <?php include 'modal.php';?>
        <?php include 'scripts.php';?>
    </body>

</html>
