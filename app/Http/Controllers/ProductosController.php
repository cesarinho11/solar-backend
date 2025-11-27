<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductosController extends Controller
{

    public function getProductos(Request $request)
    {
        $per_page = $request->get('page', 10);
        $search = $request->get('search');

        $query = DB::table('productos')
            ->select('productos.*');
              $query->where('estatus','=', 1);
        if ($search) {

            $query->where('nombre', 'like', "%$search%")
                ->orWhere('categoria', 'like', "%$search%");

        }

        $contratos = $query->paginate(10);

        return response()->json($contratos);
    }

    public function getProductosList(Request $request)
    {
        $search = $request->get('search');
        $query = DB::table('productos')
            ->select('productos.*');
        if ($search) {

            $query->where('nombre', 'like', "%$search%");

        }

        $contratos = $query->get();

        return response()->json($contratos);
    }

    public function addProducto(Request $request){
        $producto = DB::table("productos")->insertGetId([
            "nombre"=> $request->nombre,
            "categoria"=> $request->categoria,
            "descripcion"=> $request->descripcion,
            "stock_min"=> $request->stock_min,
            "stock_max"=> $request->stock_max,
            "stock"=> $request->stock,
            "costo"=> $request->costo,
            "precio"=> $request->precio,
            "tipo"=> $request->tipo,
            "estatus"=> 1,
        ]);

        return response()->json([
                'message' => 'Producto creado correctamente',
                'producto' => $producto
            ]);
    }

    public function updateProducto(Request $request){
        $producto = DB::table('productos')
        ->where('id_producto',$request->id_producto)
        ->update([
            "nombre"=> $request->nombre,
            "descripcion"=> $request->descripcion,
            "categoria"=> $request->categoria,
            "stock_min"=> $request->stock_min,
            "stock_max"=> $request->stock_max,
            "stock"=> $request->stock,
            "costo"=> $request->costo,
            "precio"=> $request->precio,
            "tipo"=> $request->tipo,
        ]);

        return response()->json([
            "message"=> "Producto actualizado correctamente",
            "producto"=> $producto
        ]);
    }

    public function deleteProducto(Request $request){
        $producto = DB::table('productos')
        ->where('id_producto',$request->id)
        ->update([
            "estatus"=> 0
        ]);

        return response()->json([
            "message"=> "Producto eliminado correctamente",
            "producto"=> $producto
        ]);
    }

}
