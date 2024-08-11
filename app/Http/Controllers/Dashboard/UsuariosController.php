<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Models\Parametro;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UsuariosController extends Controller
{

    public function index()
    {
        $parametros = Parametro::where('tabla_id', -1)->get();
        return view('dashboard.usuarios.index')
            ->with('smListarRoles', $parametros);
    }

    public function export($keyword = null)
    {
        return Excel::download(new UsersExport($keyword), "Usuarios_Registrados_".date('d-m-Y').".xlsx");
    }

    public function perfil()
    {
        return view('dashboard.perfil.index');
    }
}
