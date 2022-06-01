<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;//agrego manualmente estos modelos
use App\Models\docVehiculos;

class DocVehiculoController extends Controller
{
    public function pruebas(Request $request){
        return "accion de pruebas docvehiculo controller";
    }    
    public function __construct() {
    //utiliza el api.auth en todos los metodos excepto en los metodos index, show    
        $this->middleware('api.auth', ['except' => ['index','show']]);
    }
        //metodos para sacar informacion de los vehiculos
    public function index() {//metodo index para sacar las categorias de nuestra BD
        $docVehiculos = docVehiculos::all(); //saca todas las categories de vehiculo o tipos 
        //pruebas ;
        return response()->json([
           'docVehiculos' => $docVehiculos
        ]);
    }
    //metodo que me devuelve una sola categoria o tipo de vehiculo
    public function show($id) {
        $docVehiculos = docVehiculos::where("id", "=", $id)->first();//where me ayuda con datos diferntes a id como codigo

        if (is_object($docVehiculos)) {
            $data = [
                'code' => 200,
                'status' => 'success',
                'documentos' => $docVehiculos
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'El tipo de documento no existe'
            ];
        }
        return response()->json($data, $data['code']);
        //var_dump($codigo);die();
    }
    //guardar un tipo de vehiculo utilizando la api
    public function store(Request $request){
       //recoger los datos por post
       $json = $request->input('json', null);
       $params_array = json_decode($json, true);//me devuelve un dato json y lo convierte en array
       
       if(!empty($params_array)){
       //validar los datos
       $validate = \Validator::make($params_array, [  
           //'codigo' => 'required', 
           //'nombre' => 'required',
           //'placa' => 'required',                      
           'id_vehiculos' => 'required', 
           'codigoTipoVehiculo' => 'required', //llaves foraneas
       ]);
       //guardar la categoria
       if($validate->fails()){//si la validacion es fallida
           $data = [
             'code' => 400,
             'status' => 'error',
             'message' => 'No se guardo la categoria'  
           ];
       }else{
       $docVehiculos = new docVehiculos();
       //$tipo->codigo = $params_array['codigo'];
       //$tipo->nombre = $params_array['nombre'];
       //$tipo->placa = $params_array['placa'];
       //$tipo->claseVehiculo = $params_array['claseVehiculo'];     
       $docVehiculos->id_vehiculos = $params_array['id_vehiculos'];//llave foranea que la traigo con un params_ array
       $docVehiculos->codigoTipoVehiculo = $params_array['codigoTipoVehiculos'];//llave foranea que la traigo con un params_ array
       $docVehiculos->save();
       
       $data = [
             'code' => 200,
             'status' => 'success',
             'tipo' => $tipo  
           ];
         }
         
       }else{
           $data = [
             'code' => 400,
             'status' => 'error',
             'message' => 'No se ha enviado ningun tipo de documento'  
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
           //'codigo' => 'required',
           //'nombre' => 'required',
           //'placa' => 'required',                       
           'id_vehiculos' => 'required',  
           'codigoTipoVehiculo' => 'required',  
        ]);
        
        if($validate->fails()){
            $data = [
             'code' => 400,
             'status' => 'error',
             'message' =>  $validate->errors()
           ];
            
        }else{
            $$docVehiculos = docVehiculos::where('id', $params_array['id'])//pide el id para llamar la categoria
            ->update(['docTipoVehiculo'=>$params_array['docTipoVehiculo']]);//actualiza solo le parametro de calseVEhiculo
             //revizar la actualizacion           
            $data = [
             'code' => 200,
             'status' => 'success',
             'tipo' => $params_array  
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
            'codigo' => 'required',           
        ]);
        
        if($validate->fails()){
            $data = [
             'code' => 400,
             'status' => 'error',
             'message' =>  $validate->errors()
           ];
            
        }else{
            $eliminarDoc = docVehiculos::where('id', $params_array['id'])//pide el codigo para llamar la categoria
            ->delete (['id'=> $params_array['id']]);
            
            
            if($eliminarDoc){
                  $data = [
             'code' => 200,
             'status' => 'success',
             'message' => 'documento eliminado'
             //'eliminarVehiculo' => $params_array  
           ];     
            }else{
                $data = [
             'code' => 200,
             'status' => 'success',
             'message' => 'codigo de documento no encontrado '
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
