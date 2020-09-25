<?php

namespace App\Http\Controllers;

use App\MenuRestaurante;
use Illuminate\Http\Request;

class MenuRestauranteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $menus = MenuRestaurante::selectRaw('menu_restaurantes.*, tipo_menu.descripcion as tipo_menu_descripcion,
               restaurantes.nombre as restaurantes_nombre')
            ->join('tipo_menu', 'tipo_menu.id_tipo_menu', '=', 'menu_restaurantes.id_tipo_menu')
            ->join('restaurantes', 'restaurantes.id_restaurante', '=', 'menu_restaurantes.id_restaurante')
            ->get();
        if ($menus == null) {
            return response()->json([
                "error" => "No se encontraron registros",
                "codigo" => "404",
                "data" => null
            ]);
        } else {
            return response()->json([
                "error" => "",
                "codigo" => "200",
                "data" => $menus
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'titulo' => 'required',
            'descripcion' => 'required',
            'precio' => 'required',
            'url_img' => 'required',
            'id_restaurante' => 'required|exists:restaurantes,id_restaurante',
            'id_tipo_menu' => 'required|exists:tipo_menu,id_tipo_menu',
            'id_estado' => 'required|exists:estados,id_estado'
        ]);
        $menu = MenuRestaurante::create($request->all());
        return response()->json([
            "error" => "",
            "codigo" => "200",
            "data" => $menu
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\MenuRestaurante $menuRestaurante
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menuIndividual = MenuRestaurante::where('id_menu', $id)->first();
        if ($menuIndividual == null) {
            return response()->json([
                "error" => "No se encontraron registros",
                "codigo" => "404",
                "data" => null
            ]);
        } else {
            return response()->json([
                "error" => "",
                "codigo" => "200",
                "data" => $menuIndividual
            ]);
        }
    }

    public function menuByRestaurante($idRestaurante)
    { //Trae menús ligados al restaurante
        $menuByRestaurante = MenuRestaurante::where('id_restaurante', $idRestaurante)->get();
        if ($menuByRestaurante->count() == 0) {
            return response()->json([
                "error" => "No se encontraron registros",
                "codigo" => "404",
                "data" => null,
            ]);
        } else {
            return response()->json([
                "error" => "",
                "codigo" => "200",
                "data" => $menuByRestaurante
            ]);
        }
    }

    public function menuByTipo($idTipo)
    { //Trae menús por tipo
        $menuByTipo = MenuRestaurante::where('id_tipo_menu', $idTipo)->get();
        if ($menuByTipo->count() == 0) {
            return response()->json([
                "error" => "No se encontraron registros",
                "codigo" => "404",
                "data" => null,
            ]);
        } else {
            return response()->json([
                "error" => "",
                "codigo" => "200",
                "data" => $menuByTipo
            ]);
        }
    }

    public function menuByTipoByRest($idRestaurante, $idTipo)
    { //Trae menús por tipo
        $menuByTipo = MenuRestaurante::where('id_tipo_menu', '=', $idTipo)
            ->where('id_restaurante', $idRestaurante)->get();

        if ($menuByTipo->count() == 0) {
            return response()->json([
                "error" => "No se encontraron registros",
                "codigo" => "404",
                "data" => null,
            ]);
        } else {
            return response()->json([
                "error" => "",
                "codigo" => "200",
                "data" => $menuByTipo
            ]);
        }
    }

    public function menuByScanner(Request $request){
        $valida = $request->validate([
            'id_usuario' => 'required',
            'num_mesa' => 'required',
            'id_restaurante' => 'required',
            'id_tipo_menu' => 'required'
        ]);
        return $this->menuByTipoByRest($request->id_restaurante, $request->id_tipo_menu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\MenuRestaurante $menuRestaurante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'id_menu' => 'required',
            'titulo' => 'required',
            'descripcion' => 'required',
            'precio' => 'required',
            'url_img' => 'required',
            'id_restaurante' => 'required|exists:restaurantes,id_restaurante',
            'id_tipo_menu' => 'required|exists:tipo_menu,id_tipo_menu',
            'id_estado' => 'required|exists:estados,id_estado'
        ]);

        $idMenu = $request->id_menu;

        $actualizado = MenuRestaurante::where('id_menu', $idMenu)
            ->update($request->all());
        if ($actualizado == 1) {
            return response()->json([
                'msj' => 'Menú actualizado',
                'codigo' => 200,
            ]);
        } else {
            return response()->json([
                'msj' => 'Eror al actualizar',
                'codigo' => 400,
            ]);
        }
    }

    public function delete($idMenu)
    {
        if (!empty($idMenu)) {
            if (MenuRestaurante::where('id_menu', $idMenu)->exists()) {
                $respuesta = MenuRestaurante::where('id_menu', $idMenu)->delete();
                if ($respuesta == 1) {
                    return response()->json([
                        'msj' => 'Menú eliminado',
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
                    'msj' => 'No existe el Menú',
                    'codigo' => 404,
                ]);
            }
        } else {
            return response()->json([
                'msj' => 'El código de menú es obligatorio',
                'codigo' => 403,
            ]);
        }

    }
}
