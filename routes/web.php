<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\UserController;
use App\Models\TipoJornada;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    // return Departamento::find(1);
    return phpinfo();
    return $router->app->version();
});

// ! ---- PRUEBAS ------

/**
 * Rutas con las que gestionar los distintos tipos de jornadas (Sistemas)
 */
$router->group(['prefix' => 'prueba', 'middleware' => 'auth'], function () use ($router) {

    $router->get('/', ['uses' => 'CalendarioGeneralController@index', 'as' => 'calendariog_l']);
    $router->post('/', ['uses' => 'CalendarioGeneralController@store', 'as' => 'calendariog_c']);
    $router->get('/{id}', ['uses' => 'CalendarioGeneralController@show', 'as' => 'calendariog_s']);
    $router->post('/{id}',  ['uses' => 'CalendarioGeneralController@update', 'as' => 'calendariog_u']);
    $router->delete('/{id}',  ['uses' => 'CalendarioGeneralController@destroy', 'as' => 'calendariog_d']);
});
$router->get('/try',  ['uses' => 'CalendarioGeneralController@new_year']);
$router->get('/meses',  ['uses' => 'CalendarioCentroController@meses']);

// ! ---- PRUEBAS ------

/**
 *  Rutas con las que el usuario maneja su propio usuario
 *  ? Restablecer contraseña via email (Buscar como)
 */
$router->group(['prefix' => 'auth'], function () use ($router) {

    $router->post('/signup', ['uses' => 'AuthController@signup', 'as' => 'signup']);
    $router->post('/login', ['uses' => 'AuthController@login', 'as' => 'login']);
    $router->get('/',  ['uses' => 'AuthController@me', 'as' => 'me']);
    $router->delete('/',  ['uses' => 'AuthController@logout', 'as' => 'logout']);
});

/**
 * Rutas para que los usuarios de administracion o Jefes de Departamento Gestionen sus usuarios
 */
$router->group(['prefix' => 'usuarios', 'middleware' => 'auth'], function () use ($router) {

    $router->get('/', ['uses' => 'UsuarioController@index', 'as' => 'user_l']);
    $router->get('/{id}', ['uses' => 'UsuarioController@show', 'as' => 'user_s']);
    $router->post('/{id}',  ['uses' => 'UsuarioController@update', 'as' => 'user_u']);
    $router->delete('/{id}',  ['uses' => 'UsuarioController@destroy', 'as' => 'user_d']);
});

/**
 * Rutas con las que gestionar los centros de trabajo (Administracion o Sistemas)
 */
$router->group(['prefix' => 'centros', 'middleware' => 'auth'], function () use ($router) {

    $router->get('/', ['uses' => 'CentroController@index', 'as' => 'centro_l']);
    // $router->post('/', ['uses' => 'CentroController@store', 'as' => 'centro_c']); // ? Quien crea y gestiona los usuarios
    // $router->get('/{id}', ['uses' => 'CentroController@show', 'as' => 'centro_s']);
    // $router->post('/{id}/departamento',  ['uses' => 'CentroController@addDepartamento', 'as' => 'centro_ad']); // ? El usuario edita solo su contraseña y su calendario o ni eso?
    // $router->post('/{id}',  ['uses' => 'CentroController@update', 'as' => 'centro_u']); // ? El usuario edita solo su contraseña y su calendario o ni eso?
    // $router->delete('/{id}',  ['uses' => 'CentroController@destroy', 'as' => 'centro_d']);
});

/**
 * Rutas con las que gestionar los distintos departamentos (Admin o Sistemas)
 */
$router->group(['prefix' => 'departamentos', 'middleware' => 'auth'], function () use ($router) {

    $router->get('/', ['uses' => 'DepartamentoController@index', 'as' => 'departamento_l']);
    // $router->post('/', ['uses' => 'DepartamentoController@store', 'as' => 'departamento_c']);
    // $router->get('/{id}', ['uses' => 'DepartamentoController@show', 'as' => 'departamento_s']);
    // $router->post('/{id}',  ['uses' => 'DepartamentoController@update', 'as' => 'departamento_u']);
    // $router->delete('/{id}',  ['uses' => 'DepartamentoController@destroy', 'as' => 'departamento_d']);
});

/**
 * Rutas con las que gestionar el calendario laboral real (Usuario, auto)
 */
$router->group(['prefix' => 'registros', 'middleware' => 'auth'], function () use ($router) {

    $router->get('/prueba', ['uses' => 'RegistroController@getCalendar', 'as' => 'diauser_l']);

    $router->get('/today',  ['uses' => 'RegistroController@getToday', 'as' => 'today']);

    $router->post('/start',  ['uses' => 'RegistroController@startDay', 'as' => 'reg_start']);
    $router->post('/end',  ['uses' => 'RegistroController@endDay', 'as' => 'reg_end']);
    $router->post('/extra',  ['uses' => 'RegistroController@startExtra', 'as' => 'reg_sx']);
    $router->post('/endExtra',  ['uses' => 'RegistroController@endExtra', 'as' => 'reg_ex']);
    $router->post('/break',  ['uses' => 'RegistroController@startBreak', 'as' => 'reg_sb']);
    $router->post('/back',  ['uses' => 'RegistroController@back', 'as' => 'diauser_eb']);

    $router->post('/complete',  ['uses' => 'RegistroController@complete', 'as' => 'diauser_eb']);

    $router->post('/update',  ['uses' => 'RegistroController@update', 'as' => 'diauser_u']); //TODO check y cliente

    $router->delete('/delete/{id}',  ['uses' => 'RegistroController@destroy', 'as' => 'diauser_d']); //TODO check y cliente

    $router->get('/historial[/{mes:[0-9]+}]', ['uses' => 'RegistroController@index', 'as' => 'diauser_l']);

    // $router->post('')

//     $router->post('/show/{id}', ['uses' => 'DiaUserController@show', 'as' => 'diauser_s']);
//     $router->get('/showById/{id}', ['uses' => 'DiaUserController@showById', 'as' => 'diauser_sID']);
//     $router->post('/delete[/{id}]',  ['uses' => 'DiaUserController@destroy', 'as' => 'diauser_d']);
//     // $router->post('/',  ['uses' => 'DiaUserController@update', 'as' => 'diauser_u']);
});

/**
 * Rutas con las que gestionar el calendario laboral real (Usuario, auto)
 */
$router->group(['prefix' => 'calendario', 'middleware' => 'auth'], function () use ($router) {

    $router->get('/prueba', ['uses' => 'CalendarioCentroController@getCalendar', 'as' => 'diauser_l']);

    $router->post('/',  ['uses' => 'CalendarioCentroController@generar', 'as' => 'cg_g']);

//     $router->post('/show/{id}', ['uses' => 'DiaUserController@show', 'as' => 'diauser_s']);
//     $router->get('/showById/{id}', ['uses' => 'DiaUserController@showById', 'as' => 'diauser_sID']);
//     $router->post('/delete[/{id}]',  ['uses' => 'DiaUserController@destroy', 'as' => 'diauser_d']);
//     // $router->post('/',  ['uses' => 'DiaUserController@update', 'as' => 'diauser_u']);
});

// /**
//  * Rutas con las que gestionar los distintos tipos de jornadas (Sistemas)
//  */
$router->group(['prefix' => 'tipo-jornada', 'middleware' => 'auth'], function () use ($router) {

    $router->get('/', ['uses' => 'TipoJornadaController@index', 'as' => 'tipojor_l']);
//     $router->post('/', ['uses' => 'TipoJornadaController@store', 'as' => 'tipojor_c']);
//     $router->get('/{id}', ['uses' => 'TipoJornadaController@show', 'as' => 'tipojor_s']);
//     $router->post('/{id}',  ['uses' => 'TipoJornadaController@update', 'as' => 'tipojor_u']);
//     $router->delete('/{id}',  ['uses' => 'TipoJornadaController@destroy', 'as' => 'tipojor_d']);
});

/**
 * Rutas con las que gestionar los distintos tipos de jornadas (Sistemas)
 */
$router->group(['prefix' => 'jornada', 'middleware' => 'auth'], function () use ($router) {

    $router->get('/', ['uses' => 'JornadaController@index', 'as' => 'jornada_l']);
    // $router->post('/', ['uses' => 'JornadaController@store', 'as' => 'jornada_c']);
    // $router->get('/{id}', ['uses' => 'JornadaController@show', 'as' => 'jornada_s']);
    // $router->post('/{id}',  ['uses' => 'JornadaController@update', 'as' => 'jornada_u']);
    // $router->delete('/{id}',  ['uses' => 'JornadaController@destroy', 'as' => 'jornada_d']);
});

/**
 * Rutas con las que gestionar los distintos motivos de pausa y extras (Sistemas)
 */
$router->group(['prefix' => 'motivo', 'middleware' => 'auth'], function () use ($router) {

    $router->get('/', ['uses' => 'MotivoController@index', 'as' => 'motivo_l']);
    // $router->post('/', ['uses' => 'JornadaController@store', 'as' => 'jornada_c']);
    // $router->get('/{id}', ['uses' => 'JornadaController@show', 'as' => 'jornada_s']);
    // $router->post('/{id}',  ['uses' => 'JornadaController@update', 'as' => 'jornada_u']);
    // $router->delete('/{id}',  ['uses' => 'JornadaController@destroy', 'as' => 'jornada_d']);
});


/**
 *  Rutas de manejo de archivos
 */
$router->group(['prefix' => 'archivo', 'middleware' => 'auth'], function () use ($router) {

    // $router->get('/', ['uses' => 'ExcelController@index']);
    $router->get('/[{mes:[0-9]+}]', ['uses' => 'ExcelController@bajar']);
});


    // /**
    //  * Rutas con las que gestionar los dintintos tipos de jornadas disponibles (Admin o Sistemas)
    //  */
    // $router->group(['prefix' => 'v1'], function () use ($router) {

    //     $router->group(['prefix' => 'centros'], function () use ($router) {

    //         $router->get('/', ['uses' => 'CentroController@index', 'as' => 'centro_l']);
    //         $router->post('/', ['uses' => 'CentroController@store', 'as' => 'centro_c']);
    //         $router->group(['prefix' => '/{centro}'], function () use ($router) {
    //             $router->get('/', ['uses' => 'CentroController@show', 'as' => 'centro_s']);
    //             $router->post('/',  ['uses' => 'CentroController@update', 'as' => 'centro_u']);
    //             $router->delete('/',  ['uses' => 'CentroController@destroy', 'as' => 'centro_d']);

    //             $router->group(['prefix' => '/departamentos'], function () use ($router) {
    //                 $router->get('/', ['uses' => 'DepartamentoController@index', 'as' => 'departamento_l']);
    //                 $router->post('/', ['uses' => 'DepartamentoController@store', 'as' => 'departamento_c']);

    //                 $router->group(['prefix' => '/{departamento}'], function () use ($router) {
    //                     $router->get('/', ['uses' => 'DepartamentoController@show', 'as' => 'departamento_s']);
    //                     $router->post('/',  ['uses' => 'DepartamentoController@update', 'as' => 'departamento_u']);
    //                     $router->delete('/',  ['uses' => 'DepartamentoController@destroy', 'as' => 'departamento_d']);
    //                     $router->group(['prefix' => '/usuarios'], function () use ($router) {
    //                         $router->get('/', ['uses' => 'UserController@index', 'as' => 'user_l']);
    //                     });
    //                 });
    //             });
    //         });
    //     });
    // });
