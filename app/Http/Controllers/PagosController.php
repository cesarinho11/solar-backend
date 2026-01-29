<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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
            "saldo_actual"=> 0
        ]);

        return response()->json([
                'message' => 'Pago creado correctamente',
                'Pago' => $pago
            ]);
    }

}
