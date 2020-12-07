<?php

namespace App\Http\Controllers;

use App\DetallePedidos;
use App\Pedidos;
use Illuminate\Http\Request;

class PedidosController extends Controller
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = $request->validate([
            'total' => 'required',
            'id_mesa' => 'required',
            'codigo_pedido' => 'required',
            'detalle_pedido' => 'required',
            'id_restaurante' => 'required|exists:restaurantes,id_restaurante',
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_estado' => 'required|exists:estados,id_estado'
        ]);

        $pedido = Pedidos::create([
            'total' => $request->total,
            'id_restaurante' => $request->id_restaurante,
            'id_usuario' => $request->id_usuario,
            'id_estado' => $request->id_estado,
            'id_mesa' => $request->id_mesa,
            'codigo_pedido' => $request->codigo_pedido,
        ]);

        if($pedido->id != null){
            foreach($request->detalle_pedido as $detalle){
                $detallePedido = DetallePedidos::create([
                    'id_pedido' => $pedido->id,
                    'cantidad' => $detalle['cantidad'],
                    'sub_total' => $detalle['sub_total'],
                    'id_menu_restaurante' => $detalle['id_menu_restaurante'],
                ]);
            }
        }
        return response()->json([
            "msj" => "Pedido insertado correctamente",
            "codigo" => "200",
            "data" => null
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Pedidos  $pedidos
     * @return \Illuminate\Http\Response
     */
    public function show(Pedidos $pedidos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Pedidos  $pedidos
     * @return \Illuminate\Http\Response
     */
    public function edit(Pedidos $pedidos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Pedidos  $pedidos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pedidos $pedidos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Pedidos  $pedidos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pedidos $pedidos)
    {
        //
    }
}
