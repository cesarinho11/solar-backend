<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComprasController extends Controller
{
    public function getCompras(Request $request)
    {
        $per_page = $request->get('page', 10);
        $search = $request->get('search');

        $query = DB::table('compras')
            ->select('compras.*');
        if ($search) {

            $query->where('nombre', 'like', "%$search%")
                ->orWhere('categoria', 'like', "%$search%");

        }

        $contratos = $query->paginate(10);

        return response()->json($contratos);
    }

    public function addCompra(Request $request)
    {

        //insertar compra
        $compra = DB::table('compras')->insertGetId([
            'id_proveedor' => $request->id_proveedor,
            'fecha' => $request->fecha,
            'subtotal' => $request->total,
            'total' => $request->total_venta,
            'sucursal' => 1
        ]);

        $id_compra = $compra;

        $arr = $request->productos_compra;
        $data = $arr;

        for ($i = 0; $i < count($data); $i++) {
            DB::table('compra_producto')->insert([
                'id_compra' => $id_compra,
                'id_producto' => $data[$i]['id_producto'],
                'cantidad' => $data[$i]['cantidad'],
                'costo' => $data[$i]['costo'],
                'precio' => $data[$i]['precio'],
                'lote' => $data[$i]['lote'],
                'estatus' => 1
            ]);
        }


        return response()->json([
            'message' => 'Compra creada correctamente',
            'id' => $id_compra
        ]);
    }


    public function updateCompra(Request $request)
    {
        $compra = DB::table('compras')
            ->where('id_compra', $request->id_compra)
            ->update([
                "id_proveedor" => $request->id_proveedor,
                'fecha' => $request->fecha,
                'subtotal' => $request->total,
                'total' => $request->total_venta
            ]);

        DB::table('compra_producto')->where('id_compra', $request->id_compra)->delete();

        $arr = $request->productos_cotizacion;
        $data = $arr;

        for ($i = 0; $i < count($data); $i++) {
            DB::table('cotizacion_producto')->insert([
                'id_compra' => $request->id_compra,
                'id_producto' => $data[$i]['id_producto'],
                'cantidad' => $data[$i]['cantidad'],
                'costo' => $data[$i]['costo'],
                'precio' => $data[$i]['precio'],
                'lote' => $data[$i]['lote']
            ]);
        }


        return response()->json([
            "message" => "Compra actualizado correctamente",
            "Compra" => $compra
        ]);
    }

    public function confirmarCompra(Request $request)
    {

        // 1. Obtener productos asociados a la cotización
        $productos = DB::table('compra_producto')
            ->where('id_compra', $request->id)
            ->get();

        // 2. Restar stock producto por producto
        foreach ($productos as $prod) {

            // Obtener stock actual del producto
            $productoDB = DB::table('productos')
                ->where('id_producto', $prod->id_producto)
                ->first();

            if (!$productoDB) {
                continue; // o lanzar excepción si prefieres
            }

            // Calcular nuevo stock
            $nuevoStock = $productoDB->stock + $prod->cantidad;

            // Actualizar stock
            DB::table('productos')
                ->where('id_producto', $prod->id_producto)
                ->update([
                    'stock' => $nuevoStock

                ]);

        }


        $Compra = DB::table('compras')
            ->where('id_compra', $request->id)
            ->update([
                "estatus" => 2
            ]);

        return response()->json([
            "message" => "Compra actualizada correctamente",
            "Compra" => $Compra
        ]);
    }



}
