<?php

namespace App\Http\Controllers;

use App\MenuRestaurante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'titulo' => 'required',
            'tipo_img' => 'required',
            'descripcion' => 'required',
            'precio' => 'required',
            'url_img' => 'required',
            'id_restaurante' => 'required|exists:restaurantes,id_restaurante',
            'id_tipo_menu' => 'required|exists:tipo_menu,id_tipo_menu',
            'id_estado' => 'required|exists:estados,id_estado'
        ]);

        $image = $request->url_img;  // your base64 encoded
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = 'menu-' . $request->id_restaurante . '-' . time() . '.' . $request->tipo_img;

        $storage = \Storage::disk('menus_img');
        $carpeta = "menus_img";
        if (!$storage->exists($carpeta)) {
            $storage->makeDirectory($carpeta);
        }
        $path = storage_path() . '/app/public/menus_img/' . $imageName;
        $ruta = \File::put($path, base64_decode($image));
        $url = $request->getHttpHost().'/storage/menus_img/' . $imageName;

        // $request->url_img = "prueba";
        $menu = MenuRestaurante::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'descuento' => $request->descuento,
            'id_estado' => $request->id_estado,
            'url_img' => 'http://' . $url . '?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940',
            'id_restaurante' => $request->id_restaurante,
            'id_tipo_menu' => $request->id_tipo_menu,
            'promocion' =>  $request->promocion,
        ]);

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
        //$menuByRestaurante = MenuRestaurante::where('id_restaurante', $idRestaurante)->get();
        $menuByRestaurante = MenuRestaurante::selectRaw('menu_restaurantes.*, tipo_menu.descripcion as tipo_menu_descripcion,
               restaurantes.nombre as restaurantes_nombre')
            ->join('tipo_menu', 'tipo_menu.id_tipo_menu', '=', 'menu_restaurantes.id_tipo_menu')
            ->join('restaurantes', 'restaurantes.id_restaurante', '=', 'menu_restaurantes.id_restaurante')
            ->where('restaurantes.id_restaurante', $idRestaurante)
            ->get();
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
        //$menuByTipo = MenuRestaurante::where('id_tipo_menu', '=', $idTipo)
          //  ->where('id_restaurante', $idRestaurante)->get();
        $menuByTipo = MenuRestaurante::selectRaw('menu_restaurantes.*, tipo_menu.descripcion as tipo_menu_descripcion,
               restaurantes.nombre as restaurantes_nombre')
            ->join('tipo_menu', 'tipo_menu.id_tipo_menu', '=', 'menu_restaurantes.id_tipo_menu')
            ->join('restaurantes', 'restaurantes.id_restaurante', '=', 'menu_restaurantes.id_restaurante')
            ->where('menu_restaurantes.id_tipo_menu', $idTipo)
            ->where('restaurantes.id_restaurante', $idRestaurante)
            ->get();
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

    public function menuByScanner(Request $request)
    {
        $valida = $request->validate([
            'correo_usuario' => 'required',
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

    public function getB64Image($base64_image)
    {
        // Obtener el String base-64 de los datos
        $image_service_str = substr($base64_image, strpos($base64_image, ",") + 1);
        // Decodificar ese string y devolver los datos de la imagen
        $image = base64_decode($image_service_str);
        // Retornamos el string decodificado
        return $image;
    }

    public function getB64Extension($base64_image, $full = null)
    {
        // Obtener mediante una expresión regular la extensión imagen y guardarla
        // en la variable "img_extension"
        //preg_match("/^data:image\/(.*);base64/i", $base64_image, $img_extension);
        // Dependiendo si se pide la extensión completa o no retornar el arreglo con
        // los datos de la extensión en la posición 0 - 1
        //return ($full) ? $img_extension[0] : $img_extension[1];
        return explode('/', mime_content_type($base64_image))[1];

    }
}
