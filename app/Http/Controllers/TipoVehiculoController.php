<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;//agrego manualmente estos modelos
use App\Models\vehiculos;//agrego manualmente estos modelos
use App\Models\tipoVehiculos;//agrego manualmente estos modelos

class TipoVehiculoController extends Controller
{
    public function pruebas(Request $request){
        return "accion de pruebas tipovehiculo controller";
    }
    public function getAllVehiculos(Request $request){
        $jwtAuth = new \JwtAuth();

        $json = $request->input('json',null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        $validate = \Validator::make($params_array,[
            
        ]);
        if($validate->fails()){
            $signup = array(
                'status'=> 'error',
                'code'=>404,
                'message'=>'No validos',
                'errors'=>$validate->errors()
            );
        }else{
            $signup = $jwtAuth->getAllVehiculo();

            
        }
        return response()->json($signup,200);
    }

    public function __construct() {
    //utiliza el api.auth en todos los metodos excepto en los metodos index, show    
        //$this->middleware('api.auth', ['except' => ['index','show']]);
    }
        //metodos para sacar informacion de los vehiculos
    public function index() {//metodo index para sacar las categorias de nuestra BD
        $tiposVehiculos = tipoVehiculos::all(); //saca todas las categories de vehiculo o tipos 
        //pruebas ;
        return response()->json([
           'tipos' => $tiposVehiculos
        ]);
    }
    //metodo que me devuelve una sola categoria o tipo de vehiculo
    public function show($codigo) {
        $tVehiculos = tipoVehiculos::where("codigo", "=", $codigo)->first();//where me ayuda con datos diferntes a id como codigo

        if (is_object($tVehiculos)) {
            $data = [
                'code' => 200,
                'status' => 'success',
                'tipos' => $tVehiculos
            ];
        } else {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message' => 'El tipo de vehiculo no existe'
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
           'nombre' => 'required',
           'placa' => 'required',                      
           'id_vehiculos' => 'required', //verificar que necesito realmente
       ]);
       //guardar la categoria
       if($validate->fails()){//si la validacion es fallida
           $data = [
             'code' => 400,
             'status' => 'error',
             'message' => 'No se guardo la categoria'  
           ];
       }else{
       $tVehiculos = new tipoVehiculos();
       //$tipo->codigo = $params_array['codigo'];
       $tVehiculos->nombre = $params_array['nombre'];
       $tVehiculos->placa = $params_array['placa'];
       //$tipo->claseVehiculo = $params_array['claseVehiculo'];     
       $tVehiculos->id_vehiculos = $params_array['id_vehiculos'];//llave foranea que la traigo con un params_ array
       $tVehiculos->save();
       
       $data = [
             'code' => 200,
             'status' => 'success',
             'tipo' => $tVehiculos  
           ];
         }
         
       }else{
           $data = [
             'code' => 400,
             'status' => 'error',
             'message' => 'No se ha enviado ningun tipo de vehiculo'  
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
           'codigo' => 'required',
           //'nombre' => 'required',
           'placa' => 'required',                       
           'id_vehiculos' => 'required', //verificar que necesito realmente 
        ]);
        
        if($validate->fails()){
            $data = [
             'code' => 400,
             'status' => 'error',
             'message' =>  $validate->errors()
           ];
            
        }else{
            $tVehiculos = tipoVehiculos::where('codigo', $params_array['codigo'])//pide el id para llamar la categoria estaba con id le cambie a codigo 
            ->update(['tipoVehiculo'=>$params_array['tipoVehiculo']]);//actualiza solo le parametro de calseVEhiculo
                       
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
            $eliminarTipoVehiculo = tipoVehiculos::where('codigo', $params_array['codigo'])//pide el codigo para llamar la categoria
            ->delete (['codigo'=> $params_array['codigo']]);
            
            
            if($eliminarTipoVehiculo){
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
             'message' => 'codigo de Vehiculo no encontrado '
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
