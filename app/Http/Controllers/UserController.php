<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;//lo importo de manera
use App\Models\User;



class UserController extends Controller {

    //METODO DE PRUEBAS PARA LOS CONTROLADORES
    public function pruebas(Request $request) {
        return "Accion de pruebas de USER- CONTROLLER";
    }

    //METODO REGISTRO DE USUARIO
    public function register(Request $request) {        
        //RECOGER LOS DATOS DEL USUARIO POR POST
        $json = $request->input('json', null);
        $params = json_decode($json); //obtengo un objeto
        $params_array = json_decode($json, true); //obtengo un array

        if (!empty($params) && !empty($params_array)) {//si no esta vacio      
            //LIMPIAR DATOS 
            $params_array = array_map('trim', $params_array);
            
            //VALIDAR DATOS
            $validate = \Validator::make($params_array, [
                        'id' => 'required|unique:usuarios', //comprobar si el usuario existe (duplicado)
                        'nombre' => 'required|alpha',
                        'apellidos' => 'required|alpha',
                        'email' => 'required|email',
                        'password' => 'required',
            ]);
            if ($validate->fails()) {
                // la validacion ha fallado
                $data = array(
                    'status' => 'error',
                    'code' => 404, //numeracion de codigo http 
                    'message' => 'El usuario no se ha creado',
                    'errors' => $validate->errors()
                );
            } else {
                //validacion pasada correctamente
                //cifrar la contraseña
                $pwd = hash('sha256', $params->password);
                //$pwd = password_hash($params->password, PASSWORD_BCRYPT, ['cost => 4']);//la cifra 4 veces 
                // crear el usuario
                $user = new User;
                $user->id = $params_array['id'];
                $user->nombre = $params_array['nombre'];
                $user->apellidos = $params_array['apellidos'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                
                //guardar el usuario en base de datos 
                $user->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200, //numeracion de codigo http 
                    'message' => 'El usuario se ha creado correctamente ',
                    'user' => $user
                );
            }
        } else {
            $data = array(
                'status' => 'error',
                'code' => 404, //numeracion de codigo http 
                'message' => 'Los datos enviados no son correctos',
            );
        }
        //CONVIERTE EL ARRAY EN DATOS JSON
        return response()->json($data, $data['code']);        
    }
    //METODO REGISTRO DE LOGIN
    public function login(Request $request) {

        $jwtAuth = new \JwtAuth();//añado manualmente para instanciar

        //recibir datos por post
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        //validar esos datos

        $validate = \Validator::make($params_array, [
                    'id' => 'required',
                    'password' => 'required',
        ]);
        if ($validate->fails()) {
            // la validacion ha fallado
            $signup = array(
                'status' => 'error',
                'code' => 404, //numeracion de codigo http 
                'message' => 'El usuario no se ha podido logear',
                'errors' => $validate->errors()
            );
        } else {
            //cifrar el password
            $pwd = hash('sha256', $params->password);
            //devolver token o datos
            $signup = $jwtAuth->signup($params->id, $pwd);

            if (!empty($params->gettoken)) {
                $signup = $jwtAuth->signup($params->id, $pwd, true);
            }
        }          
        //$pwd= password_hash($password, PASSWORD_BCRYPT, ['cost => 4']);//cifrar la contraseña 
        return response()->json($signup, 200);        
        
        // fin METODO REGISTRO DE LOGIN
    }
    
    //metodo para actualizar los datos del usuario
    public function update(Request $request) {
        
        //comprobar si el usuario esta identificado
        $token = $request->header('Authorization'); //recoger el token desde una cebecera  
        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token); //creamos la variable checkToken y le pasamos el token
        
        //recoger los datos por post
        $json = $request->input('json', null); //recibo los datos que m ellegan desde la peticion json
        $params_array = json_decode($json, true); //decodficicar el json a objeto php
        $params = json_decode($json);

        if ($checkToken && !empty($params_array)) {//si checkToken es true imprime 
            //actualizar usuario            
            //sacar usuario identificado
            $user = $jwtAuth->checkToken($token, true);

            //validar datos
            $validate = \Validator::make($params_array, [
                        'id' => 'required|unique:usuarios,', //.$user->id, //comprobar si el usuario existe (duplicado)
                        'nombre' => 'required|alpha',
                        'apellidos' => 'required|alpha',
                        'email' => 'required|email',
                        'password' => 'required'
            ]);
            //quitar los campos que no quiero actualizar
            /*unset($params_array['id']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']); */

            //actualizar usuario en BD
             
            $user = User::where('id','=',$params->id)//busca el usuario 
                        ->first();
            //$user_update = User::where('id', $user->sub)->update($params_array);

            if(is_object($user)){
                $user->id=$params->id;
                $user->nombre=$params->nombre;
                $user->apellidos=$params->apellidos;
                $user->email=$params->email;
                $user->password=hash('sha256', $params->password);//cifrar el password
                $user->save();

                $data = array(  //devolver array con resultado
                    'code' => 200,
                    'status' => 'succes',
                    'user' => $user,
                    'changes' => $params_array //me permite ver los datos antiguos , para ver los cambios
                );
            }else{
                $data = array(
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'El usuario no esta identificado.'

                );
            }   
        

        }else {
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'El usuario no esta identificado.'
            );
        }

        return response()->json($data, $data['code']);
    }


    //metodo para subir un avatar
    public function upload(Request $request) {
        //recoger datos de la peticion
        $image = $request->file('file0'); //recoger los datos que llegan
        //validacion de imagen 
        $validate = \Validator::make($request->all(), [
                    'file0' => 'required|mimes:docx,pdf,xml,xlsx,xls'//formatos que va a permitir
        ]);
        //guardar imgen
        if (!$image || $validate->fails()) {
            //devolver el resultado        
            $data = array(
                'code' => 400,
                'status' => 'error',
                'message' => 'Error al subir imagen.'
            );
        } else {
            $image_name = time() . $image->getClientoriginalName();
            \Storage::disk('users')->put($image_name, \File::get($image));

            $data = array(
                'code' => 200,
                'status' => 'success',
                'image' => $image_name
            );
        }
        return response()->json($data, $data['code']);
    }

    //obtener una imagen de una url
    public function getImage($filename) {
        $isset = \Storage::disk('users')->exists($filename);//saber si existe la imagen
        if ($isset) {
            $file = \storage::disk('users')->get($filename);
            return new Response($file, 200);
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'La imagen no existe'
            );
            return response()->json($data, $data['code']);
        }
    }

    //metodo que saque la informacion de un usuario en concreto
    public function detail($id) {
        $user = User::find($id);

        if (is_object($user)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user
            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'message' => 'El usuario no existe'
            );
        }
        return response()->json($data, $data['code']);
    }

}


