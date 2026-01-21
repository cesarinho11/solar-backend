<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientesController extends Controller
{
    public function getClientes(Request $request)
    {
        $per_page = $request->get('page', 10);
        $search = $request->get('search');

        $query = DB::table('clientes')
            ->select('clientes.*');
        $query->where('estatus','=', 1);
        if ($search) {
            
           $query->where(function ($q) use ($search) {
            $q->where('domicilio', 'like', "%{$search}%")
              ->orWhere('nombre', 'like', "%{$search}%")
              ->orWhere('telefono', 'like', "%{$search}%");
        });

        }
        
        $clientes = $query->paginate(10);

        return response()->json($clientes);
    }

    public function obtenerClientes(Request $request)
    {
        $clientes = DB::table('clientes')->get(); // SELECT * FROM users
        return response()->json($clientes);
    }

    public function addCliente(Request $request)
    {
        $clientes = DB::table('clientes')
            ->insert([
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'domicilio' => $request->domicilio
            ]);

        return response()->json([
            "message" => "Cliente actualizado correctamente",
            "cliente" => $clientes
        ]);
    }

    public function updateCliente(Request $request)
    {
        $clientes = DB::table('clientes')
            ->where('id_cliente', $request->id_cliente)
            ->update([
                'nombre' => $request->nombre,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'domicilio' => $request->domicilio
            ]);

        return response()->json([
            "message" => "Cliente actualizado correctamente",
            "cliente" => $clientes
        ]);
    }

    public function deleteCliente(Request $request)
    {
        $clientes = DB::table('clientes')
            ->where('id_cliente', $request->id)
            ->update([
                "estatus" => 0
            ]);

        return response()->json([
            "message" => "Cliente eliminado correctamente",
            "client" => $clientes
        ]);
    }

}
