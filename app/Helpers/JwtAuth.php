<?php
namespace App\helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;//me permite hacer consultas a la DB
use App\Models\User;

class JwtAuth{
    
    public $key;
    
    public function __construct() {
        $this->key = 'esto_es_una_clave_super_secreta-99887766';
    }
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
          'exp'         => time() + (7*24*60*60),//el token caduca en 7 dias           
      ); 
      //HS256 el algoritmo de decodificacion
      $jwt = JWT::encode($token, $this->key, 'HS256');
      $decoded = JWT::decode($jwt, $this->key, ['HS256']);
     //devolver los datos decodificados o el token en funcion de un parametro
    if(is_null($getToken)){
        $data = $jwt;
    } else{
        $data = $decoded;
    } 
    }else{
        $data = array(
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
   }



