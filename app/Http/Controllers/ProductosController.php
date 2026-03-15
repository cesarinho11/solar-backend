<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductosController extends Controller
{

    public function getProductos(Request $request)
    {
        $per_page = $request->get('per_page', 10);
        $search = $request->get('search');

        $query = DB::table('productos')
            ->select('productos.*','categoria_productos.nombre_categoria')
            ->join('categoria_productos','categoria_productos.id_categoria','=','productos.categoria');
              $query->where('estatus','=', 1);
              $query->where('productos.categoria','!=', 4);
        if ($search) {

            $query->where('nombre', 'like', "%$search%")
                ->orWhere('categoria', 'like', "%$search%")
                ->orWhere('descripcion', 'like', "%$search%")
                ->orWhere('categoria2', 'like', "%$search%");

        }

        // $contratos = $query->paginate($per_page);
        $contratos =  $query->get();

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

     // Validar si ya existe un producto con el mismo nombre
    $existe = DB::table('productos')
        ->where('nombre', $request->nombre)
        ->exists();

    if ($existe) {
        return response()->json([
            'message' => 'Ya existe un producto con ese nombre'
        ], 400);
    }

    
        $producto = DB::table("productos")->insertGetId([
            "nombre"=> $request->nombre,
           
            "categoria"=> $request->categoria,
            "categoria2"=> $request->categoria2,
            "descripcion"=> $request->descripcion,
            "stock_min"=> $request->stock_min,
            "stock_max"=> $request->stock_max,
            "stock"=> $request->stock,
            "costo"=> $request->costo,
            "precio"=> $request->precio,
            "tipo"=> $request->tipo,
            "potencia"=> $request->potencia,
            "marca"=> $request->marca,
            "modelo"=> $request->modelo,
            "desc_mini"=> $request->desc_mini,
            "alerta"=> $request->alerta,
            "estatus"=> 1,
            "ultima_compra"=> $request->ultima_compra,
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
            "categoria2"=> $request->categoria2,
            "stock_min"=> $request->stock_min,
            "stock_max"=> $request->stock_max,
            "stock"=> $request->stock,
            "costo"=> $request->costo,
            "precio"=> $request->precio,
            "tipo"=> $request->tipo,
            "potencia"=> $request->potencia,
            "marca"=> $request->marca,
            "modelo"=> $request->modelo,
            "desc_mini"=> $request->desc_mini,
            "alerta"=> $request->alerta,
            "ultima_compra"=> $request->ultima_compra,
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

    public function categoriasProducto(Request $request){
            $categorias = DB::table('categoria_productos')
            ->select('categoria_productos.*')
            ->get();
        return response()->json($categorias);
    }
    public function alertaStock(Request $request){
            $stocks = DB::table('productos')
              ->whereColumn('stock', '<=', 'stock_min')
              ->where('estatus', '=', 1)
              ->where('alerta', '=', 1)
            ->get();
        return response()->json($stocks);
    }

    //herramientas
        public function getHerramientas(Request $request)
    {
        $per_page = $request->get('page', 10);
        $search = $request->get('search');

        $query = DB::table('productos')
            ->select('productos.*','categoria_productos.nombre_categoria')
            ->join('categoria_productos','categoria_productos.id_categoria','=','productos.categoria');
              $query->where('estatus','=', 1);
              $query->where('productos.categoria','=', 4);
        if ($search) {

            $query->where('nombre', 'like', "%$search%")
                ->orWhere('categoria', 'like', "%$search%")
                ->orWhere('descripcion', 'like', "%$search%")
                ->orWhere('categoria2', 'like', "%$search%");

        }

        $contratos = $query->get();

        return response()->json($contratos);
    }
}
