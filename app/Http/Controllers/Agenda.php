<?php

namespace App\Http\Controllers;

use App\Http\Services\Lucipher;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Agenda extends Controller{

    public function index(){
        $empresa_id = Auth::user()->empresa_id;
        $sucursales = Sucursal::where('empresa_id',$empresa_id)->get();
        return view('agenda.index',compact('sucursales'));
    }


}
