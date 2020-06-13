<?php

namespace App\Http\Controllers;

use App\Specialty;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $specialties = Specialty::all();
        return view('specialties.index', compact('specialties'));
    }

    public function create()
    {
        return view('specialties.create');
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $this->performValidation($request);

        $specialty = new Specialty();
        $specialty->name = $request->input('name');
        $specialty->descripcion = $request->input('descripcion');
        $specialty->save(); // INSERT

        $notification = 'la especialidad se ha registrado correctamente.';
        return redirect('/specialties')->with(compact('notification'));
    }

    public function edit(Specialty $specialty)
    {
        return view('specialties.edit', compact('specialty'));
    }

    public function update(Request $request, Specialty $specialty)
    {
        $this->performValidation($request);

        $specialty->name = $request->input('name');
        $specialty->descripcion = $request->input('descripcion');
        $specialty->save(); // UPDATE

        $notification = 'la especialidad se ha actualizado correctamente.';
        return redirect('/specialties')->with(compact('notification'));
    }

    public function destroy(Specialty $specialty)
    {
        $deletedSpeciality = $specialty->name;
        $specialty->delete();
        $notification = 'la especialidad '.$deletedSpeciality.' se ha eliminado correctamente.';
        return redirect('/specialties')->with(compact('notification'));
    }

    private function performValidation(Request $request)
    {
        $rules = [
            'name' => 'required|min:3'
        ];
        $messages = [
            'name.required' => 'Es necesario ingresar un nombre',
            'name.min' => 'Como minimo el nombre debe tener 3 caracteres.'
        ];
         $this->validate($request, $rules, $messages);
    }
}
