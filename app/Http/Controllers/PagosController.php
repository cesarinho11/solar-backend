<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PagosController extends Controller
{

       public function getPagosByCotizacion(Request $request)
    {
        $pagos = DB::table('pagos')
            ->select('pagos.*')
            ->where('pagos.id_cotizacion', $request->id)
            ->get();
    


             $total = DB::table('pagos')
    ->select(DB::raw('SUM(monto) as total_pagos'))
    ->where('id_cotizacion', $request->id)
    ->first();

        $data = [
             'pagos' => $pagos,
             'total' => $total,
        ];
           
            return response()->json($data);
    }


    public function addPago(Request $request){
        $pago = DB::table("pagos")->insertGetId([
            "id_cotizacion"=> $request->id_cotizacion,
            "fecha_pago"=> $request->fecha_pago,
            "monto"=> $request->monto,
            "forma_pago"=> $request->forma_pago,
            "saldo_anterior"=> 0 ,
            "saldo_actual"=> 0,
            "fecha_proximo_pago"=> $request->fecha_proximo_pago
        ]);

        return response()->json([
                'message' => 'Pago creado correctamente',
                'Pago' => $pago
            ]);
    }

    public function alertaPagos(Request $request){
            $pagos = DB::table('pagos as p')
        ->join(
            DB::raw('(
                SELECT id_cotizacion, MAX(id_pago) AS ultimo_pago
                FROM pagos
                GROUP BY id_cotizacion
            ) as ult'),
            function ($join) {
                $join->on('p.id_cotizacion', '=', 'ult.id_cotizacion')
                       ->on('p.id_pago', '=', 'ult.ultimo_pago'); // ✅ AQUÍ
            }
        )
        ->join('cotizaciones as c', 'p.id_cotizacion', '=', 'c.id_cotizacion')
        ->join('clientes as cl', 'c.id_cliente', '=', 'cl.id_cliente')
       ->whereDate('p.fecha_proximo_pago', '<=', Carbon::today('America/Mexico_City'))
        ->select('p.*', 'cl.nombre', 'cl.id_cliente')
        ->get();

    return response()->json($pagos);
    }

}
