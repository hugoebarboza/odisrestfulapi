<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Helpers\JwtAuth;
use App\Marca;



class MarcaController extends Controller
{
    
    public function __construct(){
        $this->middleware('auth.basic.once', ['only'=> ['index', 'store', 'show']]);
    }
    
    public function index(Request $request)
    {
        //return 'Mostrando Marcas index'; die();
        $marca = Marca::all()->load('user');
        return response()->json(array(
            'marca' => $marca,
            'status' => 'success'
        ),200);
    }

    public function store(Request $request)
    {   
        $hash = $request-> header('Authorization', null);
        $JwtAuth = new JwtAuth();
        $checkToken = $JwtAuth -> checkToken($hash);
        
        if($checkToken){            
            //USER AUTHENTICATE
            $user = $JwtAuth->checkToken($hash, true);

            //GET POST PARAMS TO MARCA
            $json = $request -> input('json',null);
            $params = json_decode($json);
            $params_array = json_decode($json, true);

            //VALIDATE
            $validate = \Validator::make($params_array, [
                'title' => 'required|min:2',
                'description' => 'required'
            ]);

            if($validate->fails())
            {
            $data = array(
                'status' => 'error',
                'message'=> $validate->errors(), 
                'code' => 422
                );          
            }else{
                //SAVE MARCA                        
                $marca = new Marca;            
                $marca->user_id = $user->sub;
                $marca->title = $params->title;
                $marca->description = $params->description;
                $marca->status = 1; 
                $marca->cliente_id = 1; //SYSTEM
                $marca->save();
                $data = array(
                    'status' => 'success',
                    'message'=> 'Transaccion procesada, Marca Almacenada', 
                    'code' => 200
                    );                

            }

        }else{
            $data = array(
                'status' => 'error',
                'message'=> 'No se puede procesar transaccion, Usuario Sin Token', 
                'code' => 401
                );          
        }    
        return response()->json($data, 200);            
        //Marca::create($request->all());
        //return response()->json(['mensaje' => 'Marca insertada'],201);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return view('marca.create');
       return 'Mostrando Formulario para crear Fabricantes';
    }

    public function show($id)
    {
        $marca = Marca::find($id);
        if(!$marca)
        {
            return response()->json(['mensaje'=> 'no se encuentra Marca', 'codigo'=>404],404);
        }

        return response()->json(['datos'=> $marca],200);
        //return 'Mostrando Fabricante con id'.$id;
    }



}
