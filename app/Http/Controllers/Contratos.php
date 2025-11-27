<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Contratos extends Controller
{

    public function getContratos(Request $request)
    {
        $per_page = $request->get('page', 10);
        $search = $request->get('search');

        $query = DB::table('contratos')
            ->select('contratos.*');
        if ($search) {

            $query->where('domicilio', 'like', "%$search%")
                ->orWhere('nombre', 'like', "%$search%")
                ->orWhere('telefono', 'like', "%$search%");

        }

        $contratos = $query->paginate(10);

        return response()->json($contratos);
    }

    public function addContrato(Request $request)
    {
        //return $request;
        if ($request->clienteNuevo == true) {

            //inserto cliente
            $cliente_nuevo = DB::table('clientes')->insertGetId([
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'domicilio' => $request->domicilio
            ]);

            $id_client = $cliente_nuevo;

            //insertar contrato
            $contrato = DB::table('contratos')->insertGetId([
                'n_solicitud' => $request->n_solicitud,
                'fecha' => $request->fecha,
                'fecha_operacion' => $request->fecha_operacion,
                'dias' => $request->dias,
                'mes' => $request->mes,
                'year' => $request->year,
                'cliente_id' => $id_client,
                'nombre' => $request->nombre,
                'cargo' => $request->cargo,
                'ine' => $request->ine,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'ciudad' => $request->ciudad,
                'calle' => $request->calle,
                'n_exterior' => $request->n_exterior,
                'n_interior' => $request->n_interior,
                'estado' => $request->estado,
                'municipio' => $request->municipio,
                'cp' => $request->cp,
                'colonia' => $request->colonia,
                'domicilio' => $request->domicilio,
                'x' => $request->x,
                'y' => $request->y,
                'rpu' => $request->rpu,
                'rmu' => $request->rmu,
                'n_cuenta' => $request->n_cuenta,
                'tension' => $request->tension,
                'capacidad' => $request->capacidad,
                'capacidad_incrementar' => $request->capacidad_incrementar,
                'tension_interconexion' => $request->tension_interconexion,
                'tecnologia' => $request->tecnologia,
                'tecnologia_secundaria' => $request->tecnologia_secundaria,
                'regimen_contraprestacion' => $request->regimen_contraprestacion,
                'tarifa' => $request->tarifa,
                'voltaje' => $request->voltaje,
                'n_fases' => $request->n_fases,
                'n_hilos' => $request->n_hilos,
                'n_medidor' => $request->n_medidor,
                'n_unidades' => $request->n_unidades,
                'tipo_medidor' => $request->tipo_medidor,
                'carga' => $request->carga,
                'potencia' => $request->potencia,
                'central_electrica' => $request->central_electrica,
                'baja_tencion' => $request->baja_tencion,
                'media_tension' => $request->media_tension,
                'consumo_centros' => $request->consumo_centros,
                'consumo_centros_ventas' => $request->consumo_centros_ventas,
                'venta_total' => $request->venta_total,
                'especificar' => $request->especificar,
                'solar' => $request->solar,
                'biomasa' => $request->biomasa,
                'otro' => $request->otro,
                'cogeneracion' => $request->cogeneracion,
                'eolico' => $request->eolico
            ]);

            $id_contrato = $contrato;


            return response()->json([
                'message' => 'Contrato creado correctamente',
                'id' => $id_contrato
            ]);

        } else {
            //insertar servicio
            $contrato = DB::table('contratos')->insertGetId([
                'n_solicitud' => $request->n_solicitud,
                'fecha' => $request->fecha,
                'fecha_operacion' => $request->fecha_operacion,
                'dias' => $request->dias,
                'mes' => $request->mes,
                'year' => $request->year,
                'cliente_id' => $request->id_client,
                'nombre' => $request->nombre,
                'cargo' => $request->cargo,
                'ine' => $request->ine,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'ciudad' => $request->ciudad,
                'calle' => $request->calle,
                'n_exterior' => $request->n_exterior,
                'n_interior' => $request->n_interior,
                'estado' => $request->estado,
                'municipio' => $request->municipio,
                'cp' => $request->cp,
                'colonia' => $request->colonia,
                'domicilio' => $request->domicilio,
                'x' => $request->x,
                'y' => $request->y,
                'rpu' => $request->rpu,
                'rmu' => $request->rmu,
                'n_cuenta' => $request->n_cuenta,
                'tension' => $request->tension,
                'capacidad' => $request->capacidad,
                'capacidad_incrementar' => $request->capacidad_incrementar,
                'tension_interconexion' => $request->tension_interconexion,
                'tecnologia' => $request->tecnologia,
                'tecnologia_secundaria' => $request->tecnologia_secundaria,
                'regimen_contraprestacion' => $request->regimen_contraprestacion,
                'tarifa' => $request->tarifa,
                'voltaje' => $request->voltaje,
                'n_fases' => $request->n_fases,
                'n_hilos' => $request->n_hilos,
                'n_medidor' => $request->n_medidor,
                'n_unidades' => $request->n_unidades,
                'tipo_medidor' => $request->tipo_medidor,
                'carga' => $request->carga,
                'potencia' => $request->potencia,
                'central_electrica' => $request->central_electrica,
                'baja_tencion' => $request->baja_tencion,
                'media_tension' => $request->media_tension,
                'consumo_centros' => $request->consumo_centros,
                'consumo_centros_ventas' => $request->consumo_centros_ventas,
                'venta_total' => $request->venta_total,
                'especificar' => $request->especificar,
                'solar' => $request->solar,
                'biomasa' => $request->biomasa,
                'otro' => $request->otro,
                'cogeneracion' => $request->cogeneracion,
                'eolico' => $request->eolico
            ]);

            $id_contrato = $contrato;

            return response()->json([
                'message' => 'Contrato creado correctamente',
                'id' => $id_contrato
            ]);
        }


    }

    public function editContrato(Request $request)
    {


        $updateContrato = DB::table('contratos')
            ->where('id_contrato', $request->id_contrato) // o la columna que uses como llave primaria
            ->update([
                'n_solicitud' => $request->n_solicitud,
                'fecha' => $request->fecha,
                'fecha_operacion' => $request->fecha_operacion,
                'dias' => $request->dias,
                'mes' => $request->mes,
                'year' => $request->year,
                'cliente_id' => $request->cliente_id,
                'nombre' => $request->nombre,
                'cargo' => $request->cargo,
                'ine' => $request->ine,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'ciudad' => $request->ciudad,
                'calle' => $request->calle,
                'n_exterior' => $request->n_exterior,
                'n_interior' => $request->n_interior,
                'estado' => $request->estado,
                'municipio' => $request->municipio,
                'cp' => $request->cp,
                'colonia' => $request->colonia,
                'domicilio' => $request->domicilio,
                'x' => $request->x,
                'y' => $request->y,
                'rpu' => $request->rpu,
                'rmu' => $request->rmu,
                'n_cuenta' => $request->n_cuenta,
                'tension' => $request->tension,
                'capacidad' => $request->capacidad,
                'capacidad_incrementar' => $request->capacidad_incrementar,
                'tension_interconexion' => $request->tension_interconexion,
                'tecnologia' => $request->tecnologia,
                'tecnologia_secundaria' => $request->tecnologia_secundaria,
                'regimen_contraprestacion' => $request->regimen_contraprestacion,
                'tarifa' => $request->tarifa,
                'voltaje' => $request->voltaje,
                'n_fases' => $request->n_fases,
                'n_hilos' => $request->n_hilos,
                'n_medidor' => $request->n_medidor,
                'n_unidades' => $request->n_unidades,
                'tipo_medidor' => $request->tipo_medidor,
                'carga' => $request->carga,
                'potencia' => $request->potencia,
                'central_electrica' => $request->central_electrica,
                'baja_tencion' => $request->baja_tencion,
                'media_tension' => $request->media_tension,
                'consumo_centros' => $request->consumo_centros,
                'consumo_centros_ventas' => $request->consumo_centros_ventas,
                'venta_total' => $request->venta_total,
                'especificar' => $request->especificar,
                'solar' => $request->solar,
                'biomasa' => $request->biomasa,
                'otro' => $request->otro,
                'eolico' => $request->eolico,
                'cogeneracion' => $request->cogeneracion
            ]);


        return response()->json([
            'message' => 'Contrato actualizado correctamente',
            'orden' => $updateContrato
        ]);

    }

    public function getContratoById(Request $request)
    {
        $contrato = DB::table('contratos')
            ->select('contratos.*')
            ->where('contratos.id_contrato', $request->id)
            ->first();
        return response()->json($contrato);
    }
}
