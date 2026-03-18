<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportesController extends Controller
{
    public function productosVendidosReporte(Request $request)
{

//  $fechaInicio = Carbon::parse($request->fecha_inicio, 'America/Mexico_City')
//                         ->startOfDay();

//     $fechaFin = Carbon::parse($request->fecha_fin, 'America/Mexico_City')
//                      ->endOfDay();

//     $reporteVentas = DB::table('productos as p')
//         ->leftJoin('cotizacion_producto as cp', 'cp.id_producto', '=', 'p.id_producto')
//         ->leftJoin('cotizaciones as c', function ($join) use ($fechaInicio, $fechaFin) {
//             $join->on('c.id_cotizacion', '=', 'cp.id_cotizacion')
//                  ->where('c.estatus', '>=', 2)
//                  ->whereBetween('c.fecha_venta', [$fechaInicio, $fechaFin]);
//         })
//         ->select(
//             'p.id_producto',
//             'p.nombre as producto',
//             DB::raw('COALESCE(SUM(cp.cantidad), 0) as total_vendido')
//         )
//         ->groupBy('p.id_producto', 'p.nombre')
//         ->orderBy('p.nombre')
//         ->get();

//     return response()->json($reporteVentas);

$fechaInicio = Carbon::parse($request->fecha_inicio, 'America/Mexico_City')
                        ->startOfDay();

    $fechaFin = Carbon::parse($request->fecha_fin, 'America/Mexico_City')
                     ->endOfDay();

    $reporteVentas = DB::table('productos as p')
        ->leftJoin('cotizacion_producto as cp', 'cp.id_producto', '=', 'p.id_producto')
        ->leftJoin('cotizaciones as c', function ($join) use ($fechaInicio, $fechaFin) {
            $join->on('c.id_cotizacion', '=', 'cp.id_cotizacion')
                 ->where('c.estatus', '>=', 2)
                 ->whereBetween('c.fecha_venta', [$fechaInicio, $fechaFin]);
        })
        ->select(
            'p.id_producto',
            'p.nombre as producto',
            DB::raw("
                COALESCE(
                    SUM(
                        CASE 
                            WHEN c.id_cotizacion IS NOT NULL 
                            THEN cp.cantidad 
                            ELSE 0 
                        END
                    ), 0
                ) as total_vendido
            "),
              DB::raw("
            COALESCE(
                SUM(
                    CASE 
                        WHEN c.id_cotizacion IS NOT NULL 
                        THEN cp.total_partida_venta 
                        ELSE 0 
                    END
                ), 0
            ) as total_venta
        ")
        )
        ->groupBy('p.id_producto', 'p.nombre')
        ->orderBy('p.nombre')
        ->get();

    return response()->json($reporteVentas);
}

public function totalVentas(Request $request){
    //    $fechaInicio = Carbon::parse($request->fecha_inicio, 'America/Mexico_City')
    //     ->startOfDay();

    // $fechaFin = Carbon::parse($request->fecha_fin, 'America/Mexico_City')
    //     ->endOfDay();

    // // 🔹 Totales por cotización
    // $cotizaciones = DB::table('cotizaciones as c')
    //     ->join('cotizacion_producto as cp', 'cp.id_cotizacion', '=', 'c.id_cotizacion')
    //     ->join('productos as p', 'p.id_producto', '=', 'cp.id_producto')
    //     ->where('c.estatus', '>=', 2)
    //     ->whereBetween('c.fecha_venta', [$fechaInicio, $fechaFin])
    //     ->groupBy('c.id_cotizacion', 'c.fecha_venta')
    //     ->select(
    //         'c.id_cotizacion',
    //         'c.id_cliente',
    //         'c.domicilio_instalacion',
    //         'c.fecha_venta',
    //         'c.total_venta',
    //         DB::raw('SUM(cp.cantidad * cp.precio) as total_cotizacion')
    //     )
    //     ->orderBy('c.fecha_venta', 'asc')
    //     ->get();

    // // 🔹 Gran total del periodo
    // $granTotal = DB::table('cotizaciones as c')
    //     ->join('cotizacion_producto as cp', 'cp.id_cotizacion', '=', 'c.id_cotizacion')
    //     ->where('c.estatus', '>=', 2)
    //     ->whereBetween('c.fecha_venta', [$fechaInicio, $fechaFin])
    //     ->select(
    //         DB::raw('SUM(cp.cantidad * cp.precio) as gran_total')
    //     )
    //     ->value('gran_total');

    // return response()->json([
    //     'fecha_inicio' => $fechaInicio->toDateString(),
    //     'fecha_fin' => $fechaFin->toDateString(),
    //     'cotizaciones' => $cotizaciones,
    //     'gran_total' => $granTotal ?? 0
    // ]);

     $fechaInicio = Carbon::parse($request->fecha_inicio, 'America/Mexico_City')
        ->startOfDay();

    $fechaFin = Carbon::parse($request->fecha_fin, 'America/Mexico_City')
        ->endOfDay();

    /* 🔹 Totales por cotización con cliente */
    // $cotizaciones = DB::table('cotizaciones as c')
    //     ->join('clientes as cl', 'cl.id_cliente', '=', 'c.id_cliente')
    //     ->join('users as u', 'u.id', '=' , 'c.vendedor')
    //     ->where('c.estatus', '>=', 2)
    //     ->whereBetween('c.fecha_venta', [$fechaInicio, $fechaFin])
    //     ->select(
    //         'c.id_cotizacion',
    //         'c.id_cliente',
    //         'cl.nombre as cliente',
    //         'c.domicilio_instalacion',
    //         'c.fecha_venta',
    //         'u.name',
    //         'c.total_venta',
    //         'c.total_venta as total_cotizacion'
            
    //     )
    //     ->orderBy('c.fecha_venta', 'asc')
    //     ->get();

    // $cotizaciones = DB::table('cotizaciones as c')
    // ->join('clientes as cl', 'cl.id_cliente', '=', 'c.id_cliente')
    // ->join('users as u', 'u.id', '=', 'c.vendedor')
    // ->where('c.estatus', '>=', 2)
    // ->whereBetween('c.fecha_venta', [$fechaInicio, $fechaFin])
    // ->select(
    //     'c.id_cotizacion',
    //     'c.id_cliente',
    //     'cl.nombre as cliente',
    //     'c.domicilio_instalacion',
    //     'c.fecha_venta',
    //     'u.name as vendedor',
    //     'c.total_venta',
    //     'c.total_venta as total_cotizacion'
    // )
    // ->orderBy('c.fecha_venta', 'asc')
    // ->get();

    $cotizaciones = DB::table('cotizaciones as c')
    ->join('clientes as cl', 'cl.id_cliente', '=', 'c.id_cliente')
    ->join('users as u', 'u.id', '=', 'c.vendedor')
    ->leftJoin('cotizacion_producto as cp', 'cp.id_cotizacion', '=', 'c.id_cotizacion')
    ->leftJoin('productos as p', 'p.id_producto', '=', 'cp.id_producto')
    ->where('c.estatus', '>=', 2)
    ->whereBetween('c.fecha_venta', [$fechaInicio, $fechaFin])
     ->where('p.categoria', 1) 
    ->select(
        'c.id_cotizacion',
        'c.id_cliente',
        'cl.nombre as cliente',
        'c.domicilio_instalacion',
        'c.fecha_venta',
        'u.name as vendedor',
        'c.total_venta',
        'c.total_venta as total_cotizacion',
        DB::raw("GROUP_CONCAT(CONCAT(' ( ',cp.cantidad,' ) ', p.nombre) SEPARATOR '\n  ') as productos")
    )
    ->groupBy(
        'c.id_cotizacion',
        'c.id_cliente',
        'cl.nombre',
        'c.domicilio_instalacion',
        'c.fecha_venta',
        'u.name',
        'c.total_venta'
    )
    ->orderBy('c.fecha_venta', 'asc')
    ->get();

    /* 🔹 Gran total del periodo */
    $granTotal = DB::table('cotizaciones as c')
        ->where('c.estatus', '>=', 2)
        ->whereBetween('c.fecha_venta', [$fechaInicio, $fechaFin])
        ->select(DB::raw('SUM(c.total_venta) as gran_total'))
        ->value('gran_total');

    return response()->json([
        'fecha_inicio' => $fechaInicio->toDateString(),
        'fecha_fin' => $fechaFin->toDateString(),
        'cotizaciones' => $cotizaciones,
        'gran_total' => $granTotal ?? 0
    ]);
}

public function totalVentasSinAgrupar(Request $request){
     $fechaInicio = Carbon::parse($request->fecha_inicio, 'America/Mexico_City')
        ->startOfDay();

    $fechaFin = Carbon::parse($request->fecha_fin, 'America/Mexico_City')
        ->endOfDay();

    $cotizaciones = DB::table('cotizaciones as c')
    ->join('clientes as cl', 'cl.id_cliente', '=', 'c.id_cliente')
    ->join('users as u', 'u.id', '=', 'c.vendedor')
    ->leftJoin('cotizacion_producto as cp', 'cp.id_cotizacion', '=', 'c.id_cotizacion')
    ->leftJoin('productos as p', 'p.id_producto', '=', 'cp.id_producto')
    ->where('c.estatus', '>=', 2)
    ->whereBetween('c.fecha_venta', [$fechaInicio, $fechaFin])
     ->where('p.categoria', 1) 
    ->select(
        'c.id_cotizacion',
        'c.id_cliente',
        'cl.nombre as cliente',
        'c.domicilio_instalacion',
        'c.fecha_venta',
        'u.name as vendedor',
        'c.total_venta',
        'c.total_venta as total_cotizacion',
        'cp.cantidad',
        'p.nombre as producto',
        // DB::raw("GROUP_CONCAT(CONCAT(' ( ',cp.cantidad,' ) ', p.nombre) SEPARATOR '\n  ') as productos")
    )
    ->orderBy('c.fecha_venta', 'asc')
    ->get();

    /* 🔹 Gran total del periodo */
    $granTotal = DB::table('cotizaciones as c')
        ->where('c.estatus', '>=', 2)
        ->whereBetween('c.fecha_venta', [$fechaInicio, $fechaFin])
        ->select(DB::raw('SUM(c.total_venta) as gran_total'))
        ->value('gran_total');

    return response()->json([
        'fecha_inicio' => $fechaInicio->toDateString(),
        'fecha_fin' => $fechaFin->toDateString(),
        'cotizaciones' => $cotizaciones,
        'gran_total' => $granTotal ?? 0
    ]);
}

}
