<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProveedoresController extends Controller
{
        public function getProvedores(Request $request)
    {
        $per_page = $request->get('page', 10);
        $search = $request->get('search');

        $query = DB::table('proveedores')
            ->select('proveedores.*');
             $query->where('estatus','=', 1);
         if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('nombre', 'like', "%{$search}%")
              ->orWhere('domicilio', 'like', "%{$search}%");
        });
    }

        $contratos = $query->paginate(10);

        return response()->json($contratos);
    }

       public function obtenerProveedores(Request $request)
    {
        $clientes = DB::table('proveedores')->get(); // SELECT * FROM users
        return response()->json($clientes);
    }

    public function addProveedor(Request $request){
        $Proveedor = DB::table("proveedores")->insertGetId([
            "nombre"=> $request->nombre,
            "domicilio"=> $request->domicilio,
            "correo"=> $request->correo,
            "telefono"=> $request->telefono,
            "estatus"=> 1,
        ]);

        return response()->json([
                'message' => 'Proveedor creado correctamente',
                'Proveedor' => $Proveedor
            ]);
    }

    public function updateProvedores(Request $request){
        $Proveedor = DB::table('proveedores')
        ->where('id_proveedor',$request->id_proveedor)
        ->update([
            "nombre"=> $request->nombre,
            "domicilio"=> $request->domicilio,
            "correo"=> $request->correo,
            "telefono"=> $request->telefono,
            "estatus"=> $request->estatus
        ]);

        return response()->json([
            "message"=> "Proveedor actualizado correctamente",
            "Proveedor"=> $Proveedor
        ]);
    }

    public function deleteProveedor(Request $request){
        $proveedor = DB::table('proveedores')
        ->where('id_proveedor',$request->id)
        ->update([
            "estatus"=> 0
        ]);

        return response()->json([
            "message"=> "Proveedor eliminado correctamente",
            "proveedor"=> $proveedor
        ]);
    }
}
