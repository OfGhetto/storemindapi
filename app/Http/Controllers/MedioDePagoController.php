<?php

namespace App\Http\Controllers;

use  Illuminate\Http\Request;
use App\MedioDePago;

use Dotenv\Result\Success;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class MedioDePagoController extends Controller
{
     //
     public function listar (){
        $mediodepago = DB::table('medio_pago')->select('*')->get(); //
        $data = [
            'code' => 200,
            'mediodepago' => $mediodepago
        ];
        return response() -> json($data); //
    }

    public function index(){
        return \csrf_token();
    }

    public function crear(Request $request){
        if (!empty($request -> all())) {
            $validate = Validator::make($request -> all(), [
             'forma_pago' => 'required',
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
                $mediodepago = MedioDePago::where('forma_pago', $request ->forma_pago)->first();
                if (empty($mediodepago)) {
                    $mediodepago = new MedioDePago();
                    $mediodepago ->forma_pago =$request->forma_pago;
                    $mediodepago ->descripcion =$request->descripcion;
                    $mediodepago ->save();

                    $data =[
                        'code' =>200,
                        'status' => 'success',
                        'mensaje' => 'Se ha creado correctamente',
                        'mediodepago' => $mediodepago
                    ];
                }else{
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'Ya existe el Medio De Pago'
                    ];

                }
            }
        } else {
            $data = [
                'code' =>400,
                'status' => 'error',
                'mensaje' => 'Error al crear el Medio De Pago'
            ];
        }
        return response() ->json($data);
    }

    public function editar(Request $request){
        if (!empty($request -> all())) {
            $validate = Validator::make($request ->all(), [
                'id' => 'required',
                'forma_pago' => 'required',
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
                $mediodepago = MedioDePago::find($request->id);
                if (empty($mediodepago)) {
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'No se encuentra el Medio De Pago'];

                }else{
                    $mediodepago ->forma_pago = $request -> forma_pago;
                    $mediodepago ->descripcion = $request -> descripcion;
                    $mediodepago ->save();
                    $data = [
                        'code' =>200,
                        'status' => 'success',
                        'mensaje' => 'Se ha editado correctamente',
                        'mediodepago' => $mediodepago
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
                'mensaje' => 'Debe ingresar un ID de un Medio De Pago'
            ];
        }else{
            $mediodepago = MedioDePago::find($request->id);
            if(empty($mediodepago)){
                $data = [
                    'code' =>400,
                    'status' => 'error',
                    'mensaje' => 'No se encontro el Medio De Pago asociada al ID'
                ];
            }else{
                $mediodepago ->delete();
                $data = [
                    'code' =>200,
                    'status' => 'success',
                    'mensaje' => 'Se ha eliminado correctamente'
                ];
            }
        }
        return response() -> json ($data);
    }

}
