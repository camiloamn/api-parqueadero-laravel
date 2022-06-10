<?php
namespace App\helpers;//lo primero que se debe agregar manualmente

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;//me permite hacer consultas a la Bd
use App\Models\User;
use App\Models\vehiculos;
use App\Models\tipoVehiculos;

class JwtAuth{ //lo agrego manualmente 
    
    public $key;
    
    public function __construct() {
        $this->key = 'esto_es_una_clave_super_secreta-99887766';
    }
    //metodo de login
    public function signup($id, $password, $getToken = null) {
        
    //buscar si existe el usuario con las credenciales
    $user = User::where([
        'id' => $id,
        'password' =>$password
    ])->first();
    
    //comprombar si son correctas
    $signup = false;
    if(is_object($user)){
        $signup = true;
    }
    //generar el token con los datos del usuario identificado
    if($signup){
      $token = array(
          'id'          => $user->id, 
          'nombre'      => $user->nombre,
          'apellidos'   => $user->apellidos,
          'email'       => $user->email,      
          'iat'         => time(),
          'exp'         => time() + (7 * 24 * 60 * 60)//el token caduca en 7 dias           
      ); 
      //HS256 el algoritmo de decodificacion
      $jwt = JWT::encode($token, $this->key, 'HS256');
      $decoded = JWT::decode($jwt, $this->key, ['HS256']);
     //devolver los datos decodificados o el token en funcion de un parametro
    
     if(is_null($getToken)){//si getToken es null que m edevuleva el token
        $data = $jwt;

    } else{//sino que me devuelva la decodificacion de ese token
        $data = $decoded;
    } 
    }else{
        $data = array(//realizo el else en caso de que no se halla identificado correctamente
            'status' => 'Error',
            'message' => 'Login incorrecto.'
        );
    }    
    return $data;
        
    }
    public function checkToken($jwt, $getIdentity = false){
        $auth = false;
        
        try {
            $jwt = str_replace('"', '', $jwt);//verifica con comillas o sin comillas el token 
            $decoded = JWT::decode($jwt, $this->key, ['HS256']);//decodificacion del token           
        } catch (\UnexpectedValueException $ex) { 
             $auth = false;         
        } catch (\DomainException $ex){
            $auth = false;                     
        }
        //si no esta vacio, si es objeto, si tenemos el id del usuario dentro del token
        //comprueba si el token es correcto o incorrecto
        if(!empty($decoded) && is_object($decoded) && isset($decoded->id)){
            $auth = true;
        }else{
            $auth = false;
        }
         if($getIdentity){
            return $decoded;        
    }
        return $auth;
        }
        //LO COPIE DE tipoVehiculoController
        public function getAllVehiculo(){
            $getToken = true;
            $tiposVehiculos = vehiculos::all(); //select * from traigo todo lo de vehiculos
            $signup = false;//signup variable que me ayuda a validar l epuedo poner culauqier nombre y por defecto viene false para convertirla en true 
            if(is_object($tiposVehiculos)){
                $signup = true;
            }
            if($signup){
                $nuevoVehiculo = array();//preparo un array
                foreach($tiposVehiculos as $recorrer){//for each m epermite realizar el recorrido
                 $tv = array( //traigo los datos que quiero 
                     'id-vehiculos' => $recorrer->id,
                     'claseVehiculo' => $recorrer->claseVehiculo
     
                 );
                 array_push($nuevoVehiculo, $tv); //aray de arrays
                 
                }
                $jwt = JWT::encode($nuevoVehiculo, $this->key, ['HS256']); 
                $decoded = JWT::decode($jwt, $this->key, ['HS256']);
                if(is_null($getToken)){
                    $data = $jwt;
                }else{
                    $data = $decoded;
                }
             }else{
                 $data = array(
                     'status' => 'error',
                     'message' => 'Datos incorrectos'
                 );
             }
             return $data;
         }     
   }



