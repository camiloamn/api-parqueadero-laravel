<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class AccionController extends Controller
{
    //
    public function delete(Request $request){
        $json = $request->input('json', null);
        
        $params_array = json_decode($json, true);
        
        $validate = Validator::make($params_array, [
            'nombre'=>'required'
        ]);
    if($validate->fails()){
        $data = array(
          'status'=>'error',
          'code'=>404,
          'message'=>'Nooooo!',
          'errors'=>$validate->errors()  
            );
    }else{
        $data = array(
          'status'=>'success',
          'code'=>200,
          'message'=>'Siiii!'
        );
    
        
    }
    return response()->json($data, 200);
    }
}
