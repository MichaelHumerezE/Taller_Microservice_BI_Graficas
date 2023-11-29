<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\TipoController;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\CotizacionController;
use App\Http\Controllers\DetalleOrdenController;
use App\Http\Controllers\DetalleServicioTipoController;
use App\Http\Controllers\DomicilioController;
use App\Http\Controllers\MecanicoController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\PDFOrdenTrabajoController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\SalidaController;



use App\Http\Controllers\TipoServicioController;
use App\http\Controllers\MaterialController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\TipoMaterialController;

use App\Models\Factura;
use App\Models\User;

use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\NegocioController;
use App\Http\Controllers\OrdenRepuestoController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});
Auth::routes();
