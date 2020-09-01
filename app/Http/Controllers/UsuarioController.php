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
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = Usuario::all();
        if ($usuarios == null) {
            return response()->json([
                "error" => "No se encontraron registros",
                "codigo" => "404"
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
     * @param \App\Usuario $usuario
     * @return \Illuminate\Http\Response
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
        //
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
     * @return \Illuminate\Http\Response
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
                        'error' => '',
                        'codigo' => 200
                    ], 200);
                } else {
                    return response()->json([
                        'msj' => '',
                        'error' => 'Contraseña inválida',
                        'codigo' => 403
                    ], 403);
                }
            } else {
                return response()->json([
                    'msj' => '',
                    'error' => 'Usuario inactivo',
                    'codigo' => 403
                ], 403);
            }
        } else {
            return response()->json([
                'msj' => '',
                'error' => 'Usuario no encontrado',
                'codigo' => 404
            ], 404);
        }
    }
}
