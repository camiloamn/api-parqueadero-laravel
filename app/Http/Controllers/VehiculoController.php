<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;//agrego manualmente estos modelos
use App\Models\vehiculos;//agrego manualmente estos modelos

class VehiculoController extends Controller
{
    public function pruebas(Request $request){
        return "accion de pruebas vehiculo controller";
    }
    public function __construct() {
    //utiliza el api.auth en todos los metodos excepto en los metodos index, show    
        $this->middleware('api.auth', ['except' => ['index','show']]);
    }
        //metodos para sacar informacion de los vehiculos
    public function index() {//metodo index para sacar las categorias de nuestra BD
        $categoriesVehiculos = vehiculos::all(); //saca todas las categories de vehiculo o tipos 
        //pruebas ;
        return response()->json([
           'categories' => $categoriesVehiculos
        ]);
    }
    //metodo que me devuelve una sola categoria o tipo de vehiculo
    public function show($id) {
        $category = vehiculos::where("id", "=", $id)->first();//where me ayuda con datos diferntes a id como codigo

        if (is_object($category)) {
            $data = [
                'code' => 200,
                'status' => 'success',
                'category' => $category
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'La categoria no existe'
            ];
        }
        return response()->json($data, $data['code']);
        //var_dump($codigo);die();       
    }
    //guardar una categoria o tipo de vehiculo utilizando la api
    public function store(Request $request){
       //recoger los datos por post
       $json = $request->input('json', null);
       $params_array = json_decode($json, true);//me devuelve un dato json y lo convierte en array
       
       if(!empty($params_array)){
       //validar los datos
       $validate = \Validator::make($params_array, [          
           'claseVehiculo' => 'required',            
           'id_usuarios' => 'required', 
           
       ]);
       //guardar la categoria
       if($validate->fails()){//si la validacion es fallida
           $data = [
             'code' => 400,
             'status' => 'error',
             'message' => 'No se guardo la categoria'  
           ];
       }else{
       $category = new vehiculos();       
       $category->claseVehiculo = $params_array['claseVehiculo'];     
       $category->id_usuarios = $params_array['id_usuarios'];//llave forane que la traigo con un params_ array
       $category->save();
       
       $data = [
             'code' => 200,
             'status' => 'success',
             'category' => $category  
           ];
         }
         
       }else{
           $data = [
             'code' => 400,
             'status' => 'error',
             'message' => 'No se ha enviado ninguna categoria'  
           ];
       }
       //devolver resultado
       return response()->json($data, $data['code']); 
    }
    //actualizacion de categoria
    public function update(Request $request){
        //Recoger datos por post
        $json = $request->input('json', null);//recibo los datos
        $params_array = json_decode($json, true);//decodificar la informacion a un array de php
        
        if(!empty($params_array)){//si no esta nulo continua con la validacion
        //Validar los datos
        $validate = \Validator::make($params_array,[
            'id' => 'required',
            'claseVehiculo' => 'required',
        ]);
        
        if($validate->fails()){
            $data = [
             'code' => 400,
             'status' => 'error',
             'message' =>  $validate->errors()
           ];
            
        }else{
            $category = Vehiculos::where('id', $params_array['id'])//pide el id para llamar la categoria
            ->update(['claseVehiculo'=>$params_array['claseVehiculo']]);//actualixa solo le parametro de calseVEhiculo
                       
            $data = [
             'code' => 200,
             'status' => 'success',
             'category' => $params_array  
           ];   
        }        
    }else{
        $data = [
             'code' => 400,
             'status' => 'error',
             'message' => 'No se ha enviado ninguna categoria'  
           ];
        }
        //Devolver respuesta
        return response()->json($data);
    }
    //eliminar un registro
    public function destroy(Request $request){        
        //Recoger datos por post
        $json = $request->input('json', null);//recibo los datos
        $params_array = json_decode($json, true);//decodificar la informacion a un array de php
        
        if(!empty($params_array)){//si no esta nulo continua con la validacion
        //Validar los datos
        $validate = \Validator::make($params_array,[
            'id' => 'required',           
        ]);
        
        if($validate->fails()){
            $data = [
             'code' => 400,
             'status' => 'error',
             'message' =>  $validate->errors()
           ];
            
        }else{
            $eliminarVehiculo = Vehiculos::where('id', $params_array['id'])//pide el id para llamar la categoria
            ->delete (['id'=> $params_array['id']]);
            
            
            if($eliminarVehiculo){
                  $data = [
             'code' => 200,
             'status' => 'success',
             'message' => 'Vehiculo eliminado'
             //'eliminarVehiculo' => $params_array  
           ];     
            }else{
                $data = [
             'code' => 200,
             'status' => 'success',
             'message' => 'id de Vehiculo no encontrado '
             //'eliminarVehiculo' => $params_array  
           ];     
            } 
          }        
    }else{
        $data = [
             'code' => 400,
             'status' => 'error',
             'message' => 'No se ha eliminado ningun registro'  
           ];
        }
        //Devolver respuesta
        return response()->json($data);  
    }
}