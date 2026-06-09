<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$data = DB::table('harga_harians')->where('nama_barang', 'Beras SPHP 5 Kg')->get();
echo json_encode($data->toArray());
