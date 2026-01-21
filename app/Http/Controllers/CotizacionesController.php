<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CotizacionesController extends Controller
{
     public function getCotizaciones(Request $request)
    {
        $per_page = $request->get('page', 10);
        $search = $request->get('search');

        $query = DB::table('cotizaciones')
            ->select('cotizaciones.*', 'clientes.*')
             ->join('clientes', 'clientes.id_cliente', '=', 'cotizaciones.id_cliente');
             $query->where('cotizaciones.estatus', '=', 1);
      if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('clientes.nombre', 'like', "%{$search}%")
              ->orWhere('cotizaciones.domicilio_instalacion', 'like', "%{$search}%");
        });
    }


        $contratos = $query->paginate(10);

        return response()->json($contratos);
    }

    public function addCotizacion (Request $request){

         if ($request->clienteNuevo == true) {
            $cliente_nuevo = DB::table('clientes')->insertGetId([
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'domicilio' => $request->domicilio
            ]);

            $id_client = $cliente_nuevo;

            //insertar cotizacion
            $cotizacion = DB::table('cotizaciones')->insertGetId([
                'id_cliente' =>  $id_client,
                'domicilio_instalacion' => $request->domicilio,
                'total' => $request->total,
                'total_venta' => $request->total_venta,
                'telefono' => $request->telefono,
                'tipo_pago' => $request->tipo_pago,

                'inversor' => $request->inversor,
                'n_mod' => $request->n_mod,
                'modulo_fv' => $request->modulo_fv,
                'mat_montaje' => $request->mat_montaje,
                's_fotovoltaico' => $request->s_fotovoltaico,
                'tension' => $request->tension,
                'demanda_kw' => $request->demanda_kw,
                'inst_electrica' => $request->inst_electrica

            ]);

            $id_cotizacion = $cotizacion;

            $arr = $request->productos_cotizacion;
            $data = $arr;

            for ($i = 0; $i < count($data); $i++) {
                DB::table('cotizacion_producto')->insert([
                    'id_cotizacion' =>  $id_cotizacion,
                    'id_producto' => $data[$i]['id_producto'],
                    'cantidad' => $data[$i]['cantidad'],
                    'precio' => $data[$i]['precio'],
                    'precio_venta' => $data[$i]['precio_venta'],
                    'total_partida' => $data[$i]['total'],
                    'total_partida_venta' => $data[$i]['total_venta']
                ]);
            }


            return response()->json([
                'message' => 'Cotizacion creado correctamente',
                'id' => $id_cotizacion
            ]);


         }else{

            //insertar cotizacion
            $cotizacion = DB::table('cotizaciones')->insertGetId([
                'id_cliente' =>  $request->id_cliente,
                'domicilio_instalacion' => $request->domicilio,
                'total' => $request->total,
                'total_venta' => $request->total_venta,
                'telefono' => $request->telefono,
                'tipo_pago' => $request->tipo_pago,

                'inversor' => $request->inversor,
                'n_mod' => $request->n_mod,
                'modulo_fv' => $request->modulo_fv,
                'mat_montaje' => $request->mat_montaje,
                's_fotovoltaico' => $request->s_fotovoltaico,
                'tension' => $request->tension,
                'demanda_kw' => $request->demanda_kw,
                'inst_electrica' => $request->inst_electrica
            ]);

            $id_cotizacion = $cotizacion;

            $arr = $request->productos_cotizacion;
            $data = $arr;

            for ($i = 0; $i < count($data); $i++) {
                DB::table('cotizacion_producto')->insert([
                    'id_cotizacion' =>  $id_cotizacion,
                    'id_producto' => $data[$i]['id_producto'],
                    'cantidad' => $data[$i]['cantidad'],
                    'precio' => $data[$i]['precio'],
                    'precio_venta' => $data[$i]['precio_venta'],
                    'total_partida' => $data[$i]['total'],
                    'total_partida_venta' => $data[$i]['total_venta']
                ]);
            }


            return response()->json([
                'message' => 'Cotizacion creado correctamente',
                'id' => $id_cotizacion
            ]);



         }
    }

     public function getVentas(Request $request)
    {
        $per_page = $request->get('page', 10);
        $search = $request->get('search');
        $estatus = $request->get('estatus');

        $query = DB::table('cotizaciones')
            ->select('cotizaciones.*', 'clientes.nombre', 'clientes.telefono', 'clientes.correo', 'clientes.domicilio')
             ->join('clientes', 'clientes.id_cliente', '=', 'cotizaciones.id_cliente');
             $query->where('cotizaciones.estatus', '!=', 1);
        if($estatus != 0){
                $query->where('cotizaciones.estatus', '=', $estatus);
        }

        if ($search) {

            $query->orwhere('nombre', 'like', "%$search%")
                ->orWhere('categoria', 'like', "%$search%");

        }

        $contratos = $query->paginate(10);

        return response()->json($contratos);
    }

    public function productosCotizacion(Request $request)
    {
        $productosCotizacion = DB::table('cotizacion_producto')
            ->select('cotizacion_producto.*', 'productos.nombre','productos.codigo', 'productos.descripcion', 'productos.categoria','categoria_productos.nombre_categoria')
            ->join('productos','productos.id_producto','=','cotizacion_producto.id_producto')
            ->join('categoria_productos','categoria_productos.id_categoria','=','productos.categoria')
            ->where('cotizacion_producto.id_cotizacion', $request->id)
            ->get();
        return response()->json($productosCotizacion);
    }

    public function updateCotizacion(Request $request)
    {
        $Cotizacion = DB::table('cotizaciones')
        ->where('id_cotizacion',$request->id_cotizacion)
        ->update([
            "id_cliente"=> $request->id_cliente,
            "domicilio_instalacion"=> $request->domicilio,
            "total"=> $request->total,
            "total_venta"=> $request->total_venta,
            "telefono"=> $request->telefono,
            "tipo_pago"=> $request->tipo_pago,

            'inversor' => $request->inversor,
                'n_mod' => $request->n_mod,
                'modulo_fv' => $request->modulo_fv,
                'mat_montaje' => $request->mat_montaje,
                's_fotovoltaico' => $request->s_fotovoltaico,
                'tension' => $request->tension,
                'demanda_kw' => $request->demanda_kw,
                'inst_electrica' => $request->inst_electrica
        ]);

        DB::table('cotizacion_producto')->where('id_cotizacion', $request->id_cotizacion)->delete();

        $arr = $request->productos_cotizacion;
            $data = $arr;

            for ($i = 0; $i < count($data); $i++) {
                DB::table('cotizacion_producto')->insert([
                    'id_cotizacion' =>  $request->id_cotizacion,
                    'id_producto' => $data[$i]['id_producto'],
                    'cantidad' => $data[$i]['cantidad'],
                    'precio' => $data[$i]['precio'],
                    'precio_venta' => $data[$i]['precio_venta'],
                    'total_partida' => $data[$i]['total'],
                    'total_partida_venta' => $data[$i]['total_venta']
                ]);
            }


        return response()->json([
            "message"=> "Proveedor actualizado correctamente",
            "Proveedor"=> $Cotizacion
        ]);
    }

    public function confirmarCotizacion(Request $request) 
    {

        // 1. Obtener productos asociados a la cotización
        $productos = DB::table('cotizacion_producto')
            ->where('id_cotizacion', $request->id)
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
            $nuevoStock =  $productoDB->stock - $prod->cantidad; 

            // Actualizar stock
            DB::table('productos')
                ->where('id_producto', $prod->id_producto)
                ->update([
                    'stock' => $nuevoStock
         
                    ]);

        }


        $Cotizacion = DB::table('cotizaciones')
        ->where('id_cotizacion',$request->id)
        ->update([
            "estatus"=> 2
        ]);

        return response()->json([
            "message"=> "Cotizacion actualizado correctamente",
            "Cotizacion"=> $Cotizacion
        ]);
    }

    public function marcarPagada(Request $request) 
    {
        $Cotizacion = DB::table('cotizaciones')
        ->where('id_cotizacion',$request->id)
        ->update([
            "estatus"=> 3
        ]);

        return response()->json([
            "message"=> "Cotizacion actualizado correctamente",
            "Cotizacion"=> $Cotizacion
        ]);
    }

     public function cancelarVenta(Request $request) 
    {
        $Cotizacion = DB::table('cotizaciones')
        ->where('id_cotizacion',$request->id)
        ->update([
            "estatus"=> 1
        ]);

        return response()->json([
            "message"=> "Cotizacion actualizado correctamente",
            "Cotizacion"=> $Cotizacion
        ]);
    }
}
