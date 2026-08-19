<?php  
    if(!defined('BASEPATH')) exit('No direct script access allowed');

$route['default_controller']          = 'panel';
$route['404_override']                = '';
$route['translate_uri_dashes']        = FALSE; 
$route['admin/cuentas/bancarias']     = 'admin/cuentas_bancarias';
$route['admin/reportes/ventas']       = 'admin/reportes_ventas';
$route['admin/reportes/compras']      = 'admin/reportes_compras';
$route['admin/reportes/anulaciones']  = 'admin/reportes_anulaciones';
$route['admin/reportes/actividad']    = 'admin/reportes_actividad';
$route['admin/reportes/generales']    = 'admin/reportes_generales';
$route['admin/reportes/cambios']      = 'admin/reportes_cambios';
