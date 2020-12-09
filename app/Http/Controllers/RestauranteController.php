<?php

namespace App\Http\Controllers;

use App\Restaurante;
use App\Usuario;
use Illuminate\Http\Request;

class RestauranteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $restaurantes = Restaurante::selectRaw('restaurantes.*, usuarios.nombre as nombre_encargado,
               usuarios.apellido as apellido_encargado,
               usuarios.correo as correo_encargado')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'restaurantes.id_usuario')
            ->get();

        if ($restaurantes == null) {
            return response()->json([
                "error" => "No se encontraron registros",
                "codigo" => "404",
            ]);
        } else {
            return $restaurantes;
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
            'nombre' => 'required',
            'num_mesas' => 'required',
            'telefono' => 'required',
            'direccion' => 'required',
            'correo' => 'required|email',
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_estado' => 'required|exists:estados,id_estado'
        ]);

        $idUsuario = $request->id_usuario;

        $actualizaRest = Usuario::where('id_usuario', $idUsuario)
            ->update([
                'restaurante_asociado' => $request->id_restaurante,
            ]);

        $restaurante = Restaurante::create($request->all());

        return response()->json([
            "error" => "",
            "codigo" => "200",
            "data" => $restaurante
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Restaurante $restaurante
     * @return \Illuminate\Http\Response
     */
    public function show($idRestaurante)
    {
        $restaurantes = Restaurante::selectRaw('restaurantes.*, usuarios.nombre as nombre_encargado,
               usuarios.apellido as apellido_encargado,
               usuarios.correo as correo_encargado')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'restaurantes.id_usuario')
            ->where('restaurantes.id_restaurante', $idRestaurante)
            ->get();

        if ($restaurantes == null) {
            return response()->json([
                "error" => "No se encontraron registros",
                "codigo" => "404",
            ]);
        } else {
            return $restaurantes;
        }
    }

    public function getRestauranteByEncargado($idOrMail)
    {
        $restaurantes = Restaurante::selectRaw('restaurantes.*, usuarios.nombre as nombre_encargado,
               usuarios.apellido as apellido_encargado,
               usuarios.correo as correo_encargado')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'restaurantes.id_usuario')
            ->where('restaurantes.id_usuario', $idOrMail)
            ->orWhere('usuarios.correo', $idOrMail)
            ->get();

        if ($restaurantes == null) {
            return response()->json([
                "error" => "No se encontraron registros",
                "codigo" => "404",
            ]);
        } else {
            return $restaurantes;
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Restaurante $restaurante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id_restaurante' => 'required|exists:restaurantes,id_restaurante',
            'nombre' => 'required',
            'num_mesas' => 'required',
            'telefono' => 'required',
            'direccion' => 'required',
            'correo' => 'required|email',
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_estado' => 'required|exists:estados,id_estado'
        ]);
        $idRestaurante = $request->id_restaurante;

        $actualizado = Restaurante::where('id_restaurante', $idRestaurante)
            ->update($request->all());

        if ($actualizado == 1) {
            return response()->json([
                'msj' => 'Restaurante actualizado',
                'codigo' => 200,
            ]);
        } else {
            return response()->json([
                'msj' => 'Eror al actualizar',
                'codigo' => 400,
            ]);
        }
    }


    public function delete($idRestaurante)
    {
        if (!empty($idRestaurante)) {
            if (Restaurante::where('id_restaurante', $idRestaurante)->exists()) {
                $respuesta = Restaurante::where('id_restaurante', $idRestaurante)->delete();
                if ($respuesta == 1) {
                    return response()->json([
                        'msj' => 'Restaurante eliminado',
                        'codigo' => 200,
                    ]);
                } else {
                    return response()->json([
                        'msj' => 'Error al eliminar',
                        'codigo' => 500,
                    ]);
                }
            } else {
                return response()->json([
                    'msj' => 'No existe el restaurante',
                    'codigo' => 404,
                ]);
            }
        } else {
            return response()->json([
                'msj' => 'El código de restaurante es obligatorio',
                'codigo' => 403,
            ]);
        }
    }
}
