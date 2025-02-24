@extends('layouts.app')

@section('title', 'Home App')

@section('section-title')
    <h1>Inicio</h1>
    <nav>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    </nav>
@endsection

@section('content')
<div class="row">
    <!-- Left side columns -->
    <div class="col-lg-12">
        <div class="row">

                <table style="width: 100%;">
                    <tr><!-- kk -->
                        <td style="width: 100%; text-align: center;">
                            <img src="assets/img/vasperoficial.jpg"
                                style="max-width: 50%; max-height: 275px;">

                             <h6>Bienvenido: {{ ucwords(strtolower(Auth::user()->nombre)) }}</h6> 
                        </td>
                 </tr>
                </table>
        </div>
    </div><!-- End Left side columns -->

</div>

@endsection

@push('js')
    
@endpush