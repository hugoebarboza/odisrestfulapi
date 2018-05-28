<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\JwtAuth;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

use App\User;

class UserController extends Controller
{
    public function register(Request $request){
    	//return 'Mostrando Formulario para crear Usuarios '; die();
    	$json = $request -> input('json',null);
    	$params = json_decode($json);

    	$email = (!is_null($json) && isset($params->email)) ? $params->email : null;
    	$name = (!is_null($json) && isset($params->name)) ? $params->name : null;
  		$surname = (!is_null($json) && isset($params->surname)) ? $params->surname : null;
  		$role = 'ROLE_USER';
  		$status = 0;
  		$password = (!is_null($json) && isset($params->password)) ? $params->password : null;

        if(!is_null($email) && !is_null($password) && !is_null($name))
        {
        	//Insert User
        	$user = new User();
        	$user->email = $email;        	
        	$user->name = $name;
        	$user->surname = $surname;
        	$user->role = $role;
        	$user->status = $status;
        	$pwd = hash('sha256', $password);
			$user->password = $pwd;

			//Validate User Duplicated
			$isset_user = DB::table('users')->where('email', $email)->first();			
			//$isset_user = User::where('email', $email)->first();
			//var_dump($isset_user);
			if($isset_user == null)
			{
			//Save User
			$user->save();
        	$data = array(
        		'status' => 'success',
        		'message'=> 'Transaccion procesada, Usuario Almacenado', 
        		'code' => 200
        		);
        	//return Redirect::to('/users');  
			}else{
			//No Save, User Duplicated
        	$data = array(
        		'status' => 'error',
        		'message'=> 'No se puede procesar transaccion, Usuario Duplicado', 
        		'code' => 401
        		);
			}

        }else{
        	$data = array(
        		'status' => 'error',
        		'message'=> 'No se procesaron los valores para crear Usuario', 
        		'code' => 401
        	);        
        }
	return response()->json($data, 200);        	
    }

    public function login(Request $request){
    	//return 'Mostrando Formulario para Loguear usuarios '; die();

    	$jwtAuth = new JwtAuth();
    	//Post Data
    	$json = $request -> input('json',null);
    	$params = json_decode($json);
    	$email = (!is_null($json) && isset($params->email)) ? $params->email : null;
  		$password = (!is_null($json) && isset($params->password)) ? $params->password : null;
    	$getToken = (!is_null($json) && isset($params->getToken)) ? $params->getToken : null;  		
    	//HASH PASSWORD
    	$pwd = hash('sha256', $password);

    	if(!is_null($email) && !is_null($password) && ($getToken==null || $getToken=='False')){
    		$signup = $jwtAuth->signup($email,$pwd);
    		//return response()->json($signup,200);
    	}elseif($getToken != null){
    		$signup = $jwtAuth->signup($email, $pwd, $getToken);
    		//return response()->json($signup,200);
    	}else{
        	$signup = array(
        		'status' => 'error',
        		'message'=> 'No se puede procesar transaccion, Usuario Sin Token, envia tus datos por post', 
        		'code' => 401
        		);    		
    	}
    	return response()->json($signup, 200);
    }

}
