<?php

namespace App\Http\Controllers;

use App\DetallePedidos;
use Illuminate\Http\Request;

class DetallePedidosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\DetallePedidos $detallePedidos
     * @return \Illuminate\Http\Response
     */
    public function show($idPedido)
    {
        $detallePedido = DetallePedidos::selectRaw('detalle_pedidos.*, menu_restaurantes.titulo AS nombreMenu,
            menu_restaurantes.descripcion AS descripcionMenu, menu_restaurantes.url_img')
            ->join('menu_restaurantes', 'menu_restaurantes.id_menu', '=', 'detalle_pedidos.id_menu_restaurante')
            ->where('detalle_pedidos.id_pedido', '=', $idPedido)
            ->get();
        if ($detallePedido->count() == 0) {
            return response()->json([
                "error" => "No se encontraron registros",
                "codigo" => "404",
                "data" => null,
            ]);
        } else {
            return response()->json([
                "error" => "",
                "codigo" => "200",
                "data" => $detallePedido
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\DetallePedidos $detallePedidos
     * @return \Illuminate\Http\Response
     */
    public function edit(DetallePedidos $detallePedidos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\DetallePedidos $detallePedidos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetallePedidos $detallePedidos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\DetallePedidos $detallePedidos
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetallePedidos $detallePedidos)
    {
        //
    }
}
