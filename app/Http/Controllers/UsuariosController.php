<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsuariosController extends Controller
{
    public function getUsuarios(Request $request)
    {
        $per_page = $request->get('page', 10);
        $search = $request->get('search');

        $query = DB::table('users')
            ->select('users.*');
        if ($search) {

            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");

        }

        $contratos = $query->paginate(10);

        return response()->json($contratos);
    }
}
