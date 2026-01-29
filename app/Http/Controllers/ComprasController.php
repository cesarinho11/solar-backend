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
            ->select('compras.*', 'proveedores.nombre', 'proveedores.domicilio', 'proveedores.correo', 'proveedores.telefono')
             ->join('proveedores', 'proveedores.id_proveedor', '=', 'compras.id_proveedor');
        if ($search) {

            $query->where('nombre', 'like', "%$search%")
                ->orWhere('categoria', 'like', "%$search%");

        }

        $contratos = $query->paginate(10);

        return response()->json($contratos);
    }

    public function addCompra(Request $request)
    {

       

         if ($request->clienteNuevo == true) {

             //inserto cliente
            $proveedor_nuevo = DB::table('proveedores')->insertGetId([
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'domicilio' => $request->domicilio
            ]);

            $id_proveedor = $proveedor_nuevo;
//insertar compra
        $compra = DB::table('compras')->insertGetId([
            'id_proveedor' =>  $id_proveedor,
            'fecha' => $request->fecha,
            'subtotal' => $request->total_compra,
            'total' => $request->total_compra,
            'sucursal' => 1,
            'estatus' => 1
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
                'costo_compra' => $data[$i]['costo_compra'],
                'total' => $data[$i]['total'],
                'total_compra' => $data[$i]['total_compra'],
                'lote' => $data[$i]['lote']
                // 'estatus' => 1
            ]);
        }


        return response()->json([
            'message' => 'Compra creada correctamente',
            'id' => $id_compra
        ]);


         }else{

            //insertar compra
        $compra = DB::table('compras')->insertGetId([
            'id_proveedor' => $request->id_proveedor,
            'fecha' => $request->fecha,
            'subtotal' => $request->total_compra,
            'total' => $request->total_compra,
            'sucursal' => 1,
            'estatus' => 1
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
                'costo_compra' => $data[$i]['costo_compra'],
                'total' => $data[$i]['total'],
                'total_compra' => $data[$i]['total_compra'],
                'lote' => $data[$i]['lote']
                // 'estatus' => 1
            ]);
        }


        return response()->json([
            'message' => 'Compra creada correctamente',
            'id' => $id_compra
        ]);
         }

        
    }


    public function updateCompra(Request $request)
    {
        $compra = DB::table('compras')
            ->where('id_compra', $request->id_compra)
            ->update([
                'id_proveedor' => $request->id_proveedor,
            'fecha' => $request->fecha,
            'subtotal' => $request->total_compra,
            'total' => $request->total_compra,
            'sucursal' => 1,
            'estatus' => 1
            ]);

        DB::table('compra_producto')->where('id_compra', $request->id_compra)->delete();

        $arr = $request->productos_compra;
        $data = $arr;

        for ($i = 0; $i < count($data); $i++) {
            DB::table('compra_producto')->insert([
                'id_compra' => $request->id_compra,
                'id_producto' => $data[$i]['id_producto'],
                'cantidad' => $data[$i]['cantidad'],
                'costo' => $data[$i]['costo'],
                'costo_compra' => $data[$i]['costo_compra'],
                'total' => $data[$i]['total'],
                'total_compra' => $data[$i]['total_compra'],
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
                "estatus" => 3
            ]);

        return response()->json([
            "message" => "Compra actualizada correctamente",
            "Compra" => $Compra
        ]);
    }


    public function productosCompra(Request $request)
    {
        $productosCompra = DB::table('compra_producto')
            ->select('compra_producto.*', 'productos.nombre','productos.codigo', 'productos.descripcion', 'productos.categoria','categoria_productos.nombre_categoria')
            ->join('productos','productos.id_producto','=','compra_producto.id_producto')
            ->join('categoria_productos','categoria_productos.id_categoria','=','productos.categoria')
            ->where('compra_producto.id_compra', $request->id)
            ->get();
        return response()->json($productosCompra);
    }

}
