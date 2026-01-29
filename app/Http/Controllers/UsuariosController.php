<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuariosController extends Controller
{
    public function getUsuarios(Request $request)
    {
        $per_page = $request->get('page', 10);
        $search = $request->get('search');

        $query = DB::table('users')
            ->select('users.*');
        $query->where('estatus', '=', 1);
        if ($search) {

            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");

        }

        $contratos = $query->paginate(10);

        return response()->json($contratos);
    }

    public function addUsuario(Request $request)
    {
        // $user = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'tipo' => $request->tipo,
        //     'password' => Hash::make($request->password),
        // ]);

        $user = DB::table("users")->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'tipo' => $request->tipo,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'Usuario creado correctamente',
            'User' => $user
        ]);
    }

    public function updateUsuario(Request $request)
    {


        $nueva_pass = Hash::make($request->password);
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'tipo' => $request->tipo,
            'password' => $nueva_pass
        ];

        DB::table('users')
            ->where('id', $request->id)
            ->update($data);

        return response()->json([
            "message" => "Usuario actualizado correctamente"
        ]);
    }


    public function deleteUsuario(Request $request)
    {

        $user = User::where('id', $request->id)->first();
        //  Usuario inactivo
        if ($user->tipo == 1) {
            return response()->json(['error' => 'No se puede eliminar un  administrador'], 403);
        }


        $proveedor = DB::table('users')
            ->where('id', $request->id)
            ->update([
                "estatus" => 0
            ]);

        return response()->json([
            "message" => "Usuario eliminado correctamente",
            "proveedor" => $proveedor
        ]);
    }

}
