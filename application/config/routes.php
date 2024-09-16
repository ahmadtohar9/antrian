<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'AntrianController';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['antrian/tambah_antrian/(:any)'] = 'AntrianController/tambah_antrian/$1';
$route['antrian/panggil_antrian/(:any)/(:any)'] = 'AntrianController/panggil_antrian/$1/$2';
$route['antrian/selesai_antrian/(:num)'] = 'AntrianController/selesai_antrian/$1';
$route['antrian/get_antrian_display'] = 'AntrianController/get_antrian_display';
$route['antrian/panggil_antrian_pendaftaran'] = 'AntrianController/panggil_antrian_pendaftaran_view';
$route['antrian/display_antrian'] = 'AntrianController/display_antrian';

// $route['antrian/ambil_antrian'] = 'AntrianController/view_ambil_antrian';
$route['antrian/panggil_antrian'] = 'AntrianController/view_panggil_antrian';
$route['antrian/display_antrian'] = 'AntrianController/view_display_antrian';
$route['antrian/panggil_antrian'] = 'AntrianController/panggil_antrian';
$route['get_antrian_terpanggil'] = 'AntrianController/get_antrian_terpanggil';




