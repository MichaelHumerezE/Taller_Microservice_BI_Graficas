<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Cliente;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; 
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class LoginApiController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string'
        ]);

        /* if($request->name = '' || $request->password == '')
            return response()->json(['message' => 'These credentials do not match our records.'], 404); */

        if(!DB::table('users')->where('name', $request->name)->exists()){
            return response()->json(['message' => 'TTTThese credentials do not match our records.'], 404);
        }

        $user = User::where('name', $request->name)->firstOrFail();
        if(Hash::check($request->password, $user->password)){
            return $user;
        }else{
            return response()->json(['message' => 'These credentials do not match our records.'], 404);
        }
    }

    public function registerCliente(Request $request){
        //return $request;
        $request->validate([
            'name' => 'required|min:4',
            'password' => 'required|min:4'
        ]);
        
        
        if(DB::table('users')->where('name', $request->name)->exists()){
            return response()->json(['message' => 'Ya existe un usuario con ese nombre'], 404);
        }
        if(DB::table('clientes')->where('ci', $request->ci)->exists()){
            return response()->json(['message' => 'Ya existe un usuario con ese número de carnet'], 404);
        }
        if(DB::table('clientes')->where('email', $request->email)->exists()){
            return response()->json(['message' => 'Ya existe un usuario con ese email'], 404);
        }
        
        $user = new User();
        $user->name = $request->name;
        $user->password = bcrypt($request->password);
        
        
        $cliente = new Cliente();
        $cliente->nombre = $request->nombre;
        $cliente->celular = $request->telefono;
        $cliente->ci = $request->ci;
        $cliente->email =$request->email;
        $cliente->genero = $request->genero;
        $cliente->fecha = $request->fechaNac;
        
        $user->save();
        $role = Role::find(3);
        $user->assignRole($role);
        
        $cliente->user_id = $user->id;
        $cliente->save();
        return $user;
        return $cliente;
        return $request;
    }

    public function getRole($id){
        $user = User::find($id);
        $rol = $user->getRoleNames();
        return response()->json(['rol' => $rol[0]]);
    }
}
