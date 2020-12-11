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

    }

    public function getPedidosByFecha($fecha, $estado, $idRestaurante)
    {
        $pedidos = Pedidos::selectRaw('pedidos.*, usuarios.nombre AS nombreCliente,
            usuarios.apellido AS apellidoCliente, restaurantes.nombre AS nombreRestaurante')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'pedidos.id_usuario')
            ->join('restaurantes', 'restaurantes.id_restaurante', '=', 'pedidos.id_restaurante')
            ->where('pedidos.created_at', 'LIKE', $fecha . '%')
            ->where('pedidos.id_estado', '=', $estado)
            ->where('pedidos.id_restaurante', $idRestaurante)
            ->orderBy('created_at', 'ASC')
            ->get();

        if ($pedidos->count() == 0) {
            return response()->json([
                "error" => "No se encontraron registros",
                "codigo" => "404",
                "data" => null,
            ]);
        } else {
            return response()->json([
                "error" => "",
                "codigo" => "200",
                "data" => $pedidos
            ]);
        }
    }

    public function obtenerByCliente($idCliente){
        $pedidos = Pedidos::selectRaw('pedidos.*, usuarios.nombre AS nombreCliente,
            usuarios.apellido AS apellidoCliente, restaurantes.nombre AS nombreRestaurante')
            ->join('usuarios', 'usuarios.id_usuario', '=', 'pedidos.id_usuario')
            ->join('restaurantes', 'restaurantes.id_restaurante', '=', 'pedidos.id_restaurante')
            ->where('pedidos.id_usuario', $idCliente)
            ->orderBy('created_at', 'DESC')
            ->get();

        if ($pedidos->count() == 0) {
            return response()->json([
                "error" => "No se encontraron registros",
                "codigo" => "404",
                "data" => null,
            ]);
        } else {
            return response()->json([
                "error" => "",
                "codigo" => "200",
                "data" => $pedidos
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

        if ($pedido->id != null) {
            foreach ($request->detalle_pedido as $detalle) {
                $detallePedido = DetallePedidos::create([
                    'id_pedido' => $pedido->id,
                    'cantidad' => $detalle['cantidad'],
                    'sub_total' => $detalle['sub_total'],
                    'id_menu_restaurante' => $detalle['id_menu_restaurante'],
                ]);
            }
        }
        return response()->json([
            "error" => "",
            "codigo" => "200",
            "data" => null
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Pedidos $pedidos
     * @return \Illuminate\Http\Response
     */
    public function show(Pedidos $pedidos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Pedidos $pedidos
     * @return \Illuminate\Http\Response
     */
    public function edit(Pedidos $pedidos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Pedidos $pedidos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id_pedido;
        $actualiza = Pedidos::where('id_pedido', $id)
            ->update([
                'id_estado' => $request->id_estado,
            ]);

        if($actualiza == 1){
            return response()->json([
                'msj' => 'Estado actualizado',
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
     * @param \App\Pedidos $pedidos
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pedidos $pedidos)
    {
        //
    }
}
