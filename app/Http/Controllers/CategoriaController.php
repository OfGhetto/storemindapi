<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\categoria;

use Dotenv\Result\Success;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class CategoriaController extends Controller
{
    //
    public function listar (){
        $categoria = DB::table('categoria')->select('*')->get(); //
        $data = [
            'code' => 200,
            'categoria' => $categoria
        ];
        return response() -> json($data); //
    }

    public function index(){
        return \csrf_token();
    }

    public function crear(Request $request){
        if (!empty($request -> all())) {
            $validate = Validator::make($request -> all(), [
             'nombre' => 'required',
             'descripcion' => 'required'
            ]);
            if ($validate ->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'mensaje' => 'No ha ingresado todos los parametros',
                    'errores' => $validate -> errors()
                ];
            } else{
                $categoria = categoria::where('nombre', $request ->nombre)->first();
                if (empty($categoria)) {
                    $categoria = new categoria();
                    $categoria ->nombre =$request->nombre;
                    $categoria ->descripcion =$request->descripcion;
                    $categoria ->save();

                    $data =[
                        'code' =>200,
                        'status' => 'success',
                        'mensaje' => 'Se ha creado correctamente',
                        'categoria' => $categoria
                    ];
                }else{
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'Ya existe la categoria'
                    ];

                }
            }
        } else {
            $data = [
                'code' =>400,
                'status' => 'error',
                'mensaje' => 'Error al crear la categoria'
            ];
        }
        return response() ->json($data);
    }

    public function editar(Request $request){
        if (!empty($request -> all())) {
            $validate = Validator::make($request ->all(), [
                'id' => 'required',
                'nombre' => 'required',
                'descripcion' => 'required'
            ]);
            if ($validate->fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'mensaje' => 'No ha ingresado todos los parametros',
                    'errores' => $validate -> errors()
                ];
            }else{
                $categoria = categoria::find($request->id);
                if (empty($categoria)) {
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'No se encuentra la categoria'];

                }else{
                    $categoria ->nombre = $request -> nombre;
                    $categoria ->descripcion = $request -> descripcion;
                    $categoria ->save();
                    $data = [
                        'code' =>200,
                        'status' => 'success',
                        'mensaje' => 'Se ha editado correctamente',
                        'categoria' => $categoria
                    ];
                    }
                }
            }else{
                $data = [
                    'code' =>400,
                    'status' => 'error',
                    'mensaje' => 'Error al editar'
                ];
            }
            return response()->json($data);
        }


    public function eliminar (Request $request){
        if ($request->id == ''){
            $data = [
                'code' =>400,
                'status' => 'error',
                'mensaje' => 'Debe ingresar un ID de una categoria'
            ];
        }else{
            $categoria = categoria::find($request->id);
            if(empty($categoria)){
                $data = [
                    'code' =>400,
                    'status' => 'error',
                    'mensaje' => 'No se encontro la categoria asociada al ID'
                ];
            }else{
                $categoria ->delete();
                $data = [
                    'code' =>200,
                    'status' => 'success',
                    'mensaje' => 'Se ha eliminado correctamente'
                ];
            }
        }
        return response() -> json ($data);
    }

    public function productosDeUnaCategoria(Request $request)
    {
        if (!empty($request -> all())) {
            $validate = Validator::make($request ->all(), [
                'id' => 'required'
            ]);
            if ($validate -> fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'mensaje' => 'No ha ingresado un ID valido',
                    'errores' => $validate -> errors()
                ];
            } else {
                $categoria = categoria::firstwhere('id', $request ->id);
                if (empty($categoria)) {
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'No existen categorias asociadas a ese ID'
                    ];
                } else {
                    $productos = DB::table('producto') -> where('categoria_id', $categoria ->id) -> get();
                    if (empty($productos ->all())) {
                        $data = [
                            'code' =>200,
                            'status' => 'success',
                            'mensaje' => 'No existen productos asociados a la categoria'
                        ];
                    } else {
                        $data = [
                        'code' =>200,
                        'status' => 'success',
                        'producto' => $productos
                    ];
                    }
                }
            }
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensaje' => 'Error al buscar'
            ];
        }
    return response() ->json($data);
    }
public function listarAlfabeticamente(){
    $categoria = DB::table('categoria') -> select('*') -> orderBy('nombre') -> get();
    $data = [
        'code' => 200,
        'categoria' => $categoria
    ];
    return response() -> json($data);
}

}
