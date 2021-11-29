<?php

namespace App\Http\Controllers;

use App\DetalleVenta;
use App\Producto;
use  Illuminate\Http\Request;

use Dotenv\Result\Success;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
class DetalleVentaController extends Controller
{
    public function listar()
    {
        $DetalleVenta =DetalleVenta::all();
        $data = [
            'code' => 200,
            'detalleventa' => $DetalleVenta
        ];
        return response() -> json($data);
    }

    public function index()
    {
        return \csrf_token();
    }

    public function crear(Request $request)
    {
        if (!empty($request -> all())) {
            $validate = Validator::make($request -> all(), [
                'total' => 'required',
                'cantidad' => 'required',
                'producto_id' => 'required',
            ]);
            if ($validate -> fails()) {
                $data = [
                    'code' =>400,
                    'status' => 'error',
                    'mensaje' => 'No ha ingresado todos los parametros',
                    'errores' => $validate -> errors()
                ];
            } else {
                $Producto =Producto::where('id', $request->producto_id) ->first();
                if (!empty($Producto)) {
                    $DetalleVenta = new DetalleVenta();
                    $DetalleVenta ->iva = round(($request->total*0.19));
                    $DetalleVenta ->total = $request ->total;
                    $DetalleVenta ->cantidad= $request ->cantidad;
                    $DetalleVenta ->producto_id = $request ->producto_id;
                    $DetalleVenta -> save();
                    $data = [
                        'code' => 200,
                        'status' => 'success',
                        'mensaje' => 'Se ha creado correctamente',
                        'detalleventa' => $DetalleVenta
                    ];
                } else {
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'No existe producto asociado a ese ID'
                    ];
                }
            }
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensaje' => 'Error al crear'
            ];
        }
        return response() -> json($data);
    }

    public function editar(Request $request)
    {
        if (!empty($request -> all())) {
            $validate = Validator::make($request -> all(), [
                'id' => 'required',
                'total' => 'required',
                'cantidad' => 'required',
            ]);
            if ($validate ->fails()) {
                $data = [
                    'code' =>400,
                    'status' => 'error',
                    'mensaje' => 'No ha ingresado todos los parametros',
                    'errores' => $validate -> errors()
                ];
            } else {
                $DetalleVenta =DetalleVenta::find($request->id);
                if (empty($DetalleVenta)) {
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'No se encuentra el DetalleVenta'];
                } else {
                    $DetalleVenta ->id = $request ->id;
                    $DetalleVenta ->iva = round(($request->total*0.19));
                    $DetalleVenta ->total = $request ->total;
                    $DetalleVenta ->cantidad = $request ->cantidad;
                    $DetalleVenta -> save();
                    $data =[
                        'code' => 200,
                        'status' => 'success',
                        'mensaje' => 'Se ha editado correctamente',
                        'detalleventa' =>$DetalleVenta
                    ];

                }
            }
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensaje' => 'Error al editar'];
        }
        return response() ->json($data);
    }

    public function eliminar(Request $request){
        if ($request ->id =='') {
            $data =[
            'code' => 400,
            'status' => 'error',
            'mensaje' => 'Debe ingresar un ID de un DetalleVenta'
        ];
        }else{
            $DetalleVenta =DetalleVenta::find($request ->id);
            if(empty($DetalleVenta)){
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'mensaje' => 'No se encontro el DetalleVenta asociado al ID'
                ];
            }else{
                $DetalleVenta -> delete();
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'mensaje' => 'Se ha eliminado correctamente'
                ];
            }
        }
        return response() -> json($data);
    }
    public function listarDeMenorAMayorTotalDeVenta(){
        $detalle_venta = DB::table('detalle_venta') -> select('*') -> orderBy('total') -> get();
        $data = [
            'code' => 200,
            'Detalle_venta' => $detalle_venta
        ];
        return response() -> json($data);
    }

}
