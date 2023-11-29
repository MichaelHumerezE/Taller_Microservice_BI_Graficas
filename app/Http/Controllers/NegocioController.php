<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NegocioController extends Controller
{
    public function reparaciones()
    {
        return view("negocio.reparaciones");
    }

    public function beneficios()
    {
        return view("negocio.beneficios");
    }

    public function tecnicos()
    {
        return view("negocio.tecnicos");
    }

    public function index()
    {
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}
