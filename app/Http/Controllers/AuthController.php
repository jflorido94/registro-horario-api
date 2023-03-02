<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Http\Resources\UsuarioResource;

class AuthController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'signup',
            'login',
        ]]);
    }
    public function signup(Request $request)
    {
        $rules = array(
            'nombre' => 'required',
            'apellidos' => 'required',
            'email' => 'required|email|unique:users',
            //'departamento' => 'required|exists:departamentos,id',
            'password' => 'required|confirmed', //pasword_confirmation
        );

        $messages = array(
            'nombre.required' => 'Por favor introduzca el nombre del usuario',
            'apellidos.required' => 'Por favor introduzca el apellido del usuario',
            'email.required' => 'Por favor introduzca el email del usuario',
            'email.unique' => 'El email introducido ya existe',
            'email.email' => 'El email introducido no es valido',
            // 'departamento.required' => 'Por favor introduzca el departamento del usuario',
            // 'departamento.exists' => 'El departamento introducido no existe',
            'password.required' => 'Por favor introduzca la contraseña del usuario',
            'password.confirmed' => 'Las contraseñas no coinciden',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $mensaje = $validator->messages();
            return response()->json(['mensaje' => $mensaje], 406);
        }

        // $departamento = Departamento::find($request->input('departamento'));

        $user = new Usuario();

        // $user->departamento()->associate($departamento);


        $user->nombre = $request->input('nombre');
        $user->apellidos = $request->input('apellidos');
        $user->email = $request->input('email');
        $user->password = app('hash')->make($request->input('password'));

        if ($user->save()) {
            return response()->json(['mensaje' => 'Usuario creado correctamente'], 201);
        } else {
            return response()->json(['mensaje' => 'Ocurrió un error durante la creacion del usuario'], 500);
        }
    }

    /**
     * Login a user in the app.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request  $request)
    {
        $rules = array(
            'email' => 'required|email',
            'password' => 'required',
            'remember_me' => 'boolean'
        );

        $messages = array(
            'email.required' => 'Por favor introduzca el email del usuario',
            'email.email' => 'El email introducido no es valido',
            'password.required' => 'Por favor introduzca la contraseña del usuario',
            'remember_me.boolean' => 'Solo puede ser 1 o 0',
        );

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $mensaje = $validator->messages();
            return response()->json(['mensaje' => $mensaje], 406);
        }
        $credentials = request(['email', 'password']);
        $claims = ['exp' => Carbon::now()->addDays(7)->timestamp];

        if (!$token = auth()->attempt($credentials, $claims)) {
            return response()->json(['message' => 'Email y/o contraseña incorrectos'], 401);
        }

        $auth = Usuario::where('email',$request->email)->first();

        return response()->json([
            'id' => $auth->id,
            'username' => $auth->email,
            'role' => $auth->roles[0]->nombre,
            'token' => $token,
            'message' => 'OK',
            'firstLogin' => $auth->first_login
        ]);
        return $this->respondWithToken($token, request()->rememberMe);
    }

    /**
     * Get user details.
     *
     * @param  Request  $request
     * @return Response
     */
    public function me()
    {
        return response()->json(new UsuarioResource(auth()->user()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuario  $user
     * @return \Illuminate\Http\Response
     */
    public function logout(Usuario $user)
    {
        auth()->logout();

        return response()->json(['message' => 'Sesion cerrada correctamente'], 204);
    }
}
