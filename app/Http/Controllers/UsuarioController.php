<?php

namespace App\Http\Controllers;

use App\Usuario;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
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
            ], 404);
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
        return Usuario::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param \App\ $usuario
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $usuario = Usuario::where('id_usuario', $id)->first();

        if ($usuario == null) {
            return response()->json([
                "error" => "usuario no encontrado",
                "codigo" => "404"
            ], 404);
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
    public function update(Request $request, Usuario $usuario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Usuario $usuario
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Usuario $usuario)
    {
        //
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
            if ($usuarioFila->estado != 0) {
                $passb64 = base64_encode($password);
                if (!strcmp($passb64, $usuarioFila->password)) {
                    return response()->json([
                        'msj' => 'Login correcto',
                        'codigo' => 200,
                        'nombre_usuario' => $usuarioFila->nombre,
                        'apellido_usuario' => $usuarioFila->apellido,
                        'id_tipo_usuario' => $usuarioFila->id_tipo_usuario,
                    ]);
                } else {
                    return response()->json([
                        'msj' => 'Contraseña inválida',
                        'codigo' => 405,
                        'nombre_usuario' => null,
                        'apellido_usuario' => null,
                        'id_tipo_usuario' => null,
                    ]);
                }
            } else {
                return response()->json([
                    'msj' => 'Usuario inactivo',
                    'codigo' => 403,
                    'nombre_usuario' => null,
                    'apellido_usuario' => null,
                    'id_tipo_usuario' => null,
                ]);
            }
        } else {
            return response()->json([
                'msj' => 'Usuario no encontrado',
                'codigo' => 404,
                'nombre_usuario' => null,
                'apellido_usuario' => null,
                'id_tipo_usuario' => null,
            ]);
        }
    }
}
