<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\lista;
use App\Models\tipoVehiculos;
use APP\Models\tDocumentos;


class listasController extends Controller
{
  
     //metodos para sacar informacion de todos los vehiculos
     public function index() {//metodo index para sacar las categorias de nuestra BD
        $lista = lista::all(); //saca todas las categories de vehiculo o tipos 
        //pruebas ;
        return response()->json([
        'listas$listas' => $lista
        
            ]);
        } 
    
        //metodo que me devuelve una sola categoria o tipo de vehiculo
        public function show($id) {
            $lista = lista::where("id", "=", $id)->first();//where me ayuda con datos diferntes a id como codigo
    
            if (is_object($lista)) {
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'tipos' => $lista
                ];
            } else {
                $data = [
                    'code' => 404,
                    'status' => 'error',
                    'message' => 'La lista no existe'
                ];
            }
            return response()->json($data, $data['code']);
            //var_dump($codigo);die();
        }
        //guardar un tipo de vehiculo utilizando la api
        public function store(Request $request){
           //recoger los datos por post
           $json = $request->input('json', null);//cero la cariable json
           $params_array = json_decode($json, true);//lo convierto en un array de php con el parametro true
           
           if(!empty($params_array)){
           //validar los datos
           $validate = \Validator::make($params_array, [  
               //'codigo' => 'required', 
               //'tipoDocumento' => 'required',
               //'id_vehiculos' => 'required',
               'codigoTipoVehiculo' => 'required',
               'id_tDocumentos' => 'required',
               'fechainicio' => 'required',
               'fechafin' => 'required',
               'url' => 'required',
               
               //'placa' => 'required',                      
               //'id_vehiculos' => 'required', //verificar que necesito realmente
           ]);
           //guardar la categoria
           if($validate->fails()){//si la validacion es fallida
               $data = [
                 'code' => 400,
                 'status' => 'error',
                 'message' => 'NO SE GUARDO LA LISTA'  
               ];
           }else{
           $lista = new lista();
           //$lista->id_vehiculos = $params_array['id_vehiculos'];
           $lista->codigoTipoVehiculo = $params_array['codigoTipoVehiculo'];
           $lista->id_tDocumentos = $params_array['id_tDocumentos'];      
           $lista->fechainicio = $params_array['fechainicio'];
           $lista->fechafin = $params_array['fechafin'];
           $lista->url = $params_array['url'];
           //$tVehiculos->placa = $params_array['placa'];
           //$tVehiculos->claseVehiculo = $params_array['claseVehiculo'];     
           //$lista->id_usuarios = $params_array['id_usuarios'];//llave foranea que la traigo con un params_ array
           $lista->save();
           
           $data = [
                 'code' => 200,
                 'status' => 'success',
                 'tipo' => $lista  
               ];
             }
             
           }else{
               $data = [
                 'code' => 400,
                 'status' => 'error',
                 'message' => 'No se ha enviado ninguna lista'  
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
               'placa' => 'required',  
               'fechainicio' => 'required',
               'fechafin' => 'required',
               'url' => 'required',               
               //'id_vehiculos' => 'required', //verificar que necesito realmente 
            ]);
            
            if($validate->fails()){
                $data = [
                 'code' => 400,
                 'status' => 'error',
                 'message' =>  $validate->errors()
               ];
                
            }else{
            
                $lista = lista::where('placa', $params_array['placa'])//pide el id para llamar la categoria estaba con id le cambie a codigo 
                ->update(['fechainicio'=>$params_array['fechainicio']])                
                ->update(['fechafin' =>$params_array['fechafin']])
                ->update(['url' =>$params_array['url']]);           
                $data = [
                 'code' => 200,
                 'status' => 'success'
                 //'tipo' => $params_array  
               ];   
            }        
        }else{
            $data = [
                 'code' => 400,
                 'status' => 'error',
                 'message' => 'No se ha actualizado la lista'  
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
    
       public function pruebas(Request $request){
            return "accion de pruebas tipovehiculo controller";
        }
        public function getAllDocumentos(Request $request){
            //$checkToken = $jwtAuth->checkToken($token);
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
                $signup = $jwtAuth->getAllDocumentos();
    
                
            }
            return response()->json($signup,200);
        }  
        
        public function buscador(Request $request){
            $res = '';
    
            $query = tipoVehiculos::query();
            $data = $request->input('search');
    
            if($data != ''){
                $query->whereRaw("nombre LIKE '%".$data."%'")
                ->orWhereRaw("placa LIKE '%".$data."%'");
                /* ->Where("cedtra", "!=",$separada[1]) */
                
                $res=$query->get();
            }else{
                $query='';
                $res = $query;
            }
            return $res;
        }
    
            
    }
  