<?php

namespace App\Http\Controllers;

use App\Mail\PasswordSend;
use App\Usuario;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Symfony\Component\Console\Input\Input;

class UsuarioController extends Controller
{
    public function __construct()
    {
        // $this->middleware('client_credentials')->except('store', 'login', 'index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $usuarios = Usuario::all();
        if ($usuarios == null) {
            return response()->json([
                "error" => "No se encontraron registros",
                "codigo" => "404",
            ]);
        } else {
            return $usuarios;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_tipo_usuario' => 'exists:tipo_usuarios,id_tipo_usuario',
            'id_estado' => 'required|exists:estados,id_estado',
            'nombre' => 'required',
            'apellido' => 'required',
            'password' => 'required',
            'correo' => 'required|email|unique:usuarios',
            'tipo_creacion' => 'required'
        ]);
        if($request->tipo_creacion == 2){
            Mail::to($request->correo)->send(new PasswordSend($validatedData));
        }
        $usuario =  Usuario::create([
            'id_tipo_usuario' => $request->id_tipo_usuario,
            'restaurante_asociado' => $request->id_restaurante_asociado,
            'id_estado' => $request->id_estado,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'password' => base64_encode($request->password),
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'tipo_creacion' => $request->tipo_creacion, //1- normal | 2-google
        ]);
        return response()->json([
            "error" => "",
            "codigo" => "200",
            "data" => $usuario,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\ $usuario
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $usuario = Usuario::where('id_usuario', $id)->orWhere('correo', $id)->first();

        if ($usuario == null) {
            return response()->json([
                "error" => "usuario no encontrado",
                "codigo" => "404"
            ]);
        } else {
            return $usuario;
        }
    }
    public function getUserByRestaurante($idRestaurante)
    {

        $usuario = Usuario::where('restaurante_asociado', $idRestaurante)->get();

        if ($usuario == null) {
            return response()->json([
                "error" => "usuario no encontrado",
                "codigo" => "404"
            ]);
        } else {
            return $usuario;
        }
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Usuario $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Usuario $usuario)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Usuario $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id_tipo_usuario' => 'required|exists:tipo_usuarios,id_tipo_usuario',
            'id_estado' => 'required|exists:estados,id_estado',
            'nombre' => 'required',
            'apellido' => 'required',
            'password' => 'required',
            'correo' => 'required|email',
            'telefono' => [
                'required',
                //Se utiliza para validar campos unicos al hacer update
                Rule::unique('usuarios')->ignore($request->correo, 'correo'),
            ],
        ]);
        $email = $request->correo;
        $valor = Usuario::where('correo', $email)
            ->update([
                'id_tipo_usuario' => $request->id_tipo_usuario,
                'restaurante_asociado' => $request->id_restaurante_asociado,
                'id_estado' => $request->id_estado,
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'password' => base64_encode($request->password),
                'telefono' => $request->telefono,
                'direccion' => $request->direccion
            ]);

        if($valor == 1){
            return response()->json([
                'msj' => 'Usuario actualizado',
                'codigo' => 200,
            ]);
        }else{
            return response()->json([
                'msj' => 'Error al actualizar',
                'codigo' => 400,
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Usuario $usuario
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($email)
    {
        if (Usuario::where('correo',$email)->exists()) {
            $respuesta = Usuario::where('correo',$email)->delete();
            if($respuesta == 1){
                return response()->json([
                    'error' => '',
                    'codigo' => 200,
                ]);
            }else{
                return response()->json([
                    'error' => 'No se pudo eliminar',
                    'codigo' => 500,
                ]);
            }
        }else{
            return response()->json([
                'error' => 'Usuario no encontrado
                }
                ',
                'codigo' => 404,
            ]);
        }
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'usuario' => 'required',
            'password' => 'required',
        ]);

        $usuario = \request('usuario');
        $password = \request('password');

        $usuarioFila = Usuario::where('correo', $usuario)->first();

        if ($usuarioFila != null) {
            if ($usuarioFila->estado != 2) {
                $passb64 = base64_encode($password);
                if (!strcmp($passb64, $usuarioFila->password)) {
                    return response()->json([
                        'msj' => 'Login correcto',
                        'codigo' => 200,
                        'nombre_usuario' => $usuarioFila->nombre,
                        'apellido_usuario' => $usuarioFila->apellido,
                        'id_tipo_usuario' => $usuarioFila->id_tipo_usuario,
                        'restaurante_asociado' => $usuarioFila->restaurante_asociado,
                    ]);
                } else {
                    return response()->json([
                        'msj' => 'Contraseña inválida',
                        'codigo' => 405,
                        'nombre_usuario' => null,
                        'apellido_usuario' => null,
                        'id_tipo_usuario' => null,
                        'restaurante_asociado' => null,
                    ]);
                }
            } else {
                return response()->json([
                    'msj' => 'Usuario inactivo',
                    'codigo' => 403,
                    'nombre_usuario' => null,
                    'apellido_usuario' => null,
                    'id_tipo_usuario' => null,
                    'restaurante_asociado' => null,
                ]);
            }
        } else {
            return response()->json([
                'msj' => 'Usuario no encontrado',
                'codigo' => 404,
                'nombre_usuario' => null,
                'apellido_usuario' => null,
                'id_tipo_usuario' => null,
                'restaurante_asociado' => null,
            ]);
        }
    }
}
