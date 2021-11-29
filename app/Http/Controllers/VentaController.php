<?php

namespace App\Http\Controllers;

use App\Venta;
use  Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VentaController extends Controller
{
    public function listar()
    {
        $venta  = Venta::all();
        $data = [
            'code' => 200,
            'Venta' => $venta
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
                'fecha_resolucion' => 'required',
                'n_documento' => 'required',
                'medio_pago_id' => 'required',
                'cliente_id' => 'required',
                'detalle_venta_id' => 'required',
            ]);
            if ($validate -> fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'mensaje' => 'No ha ingresado todos los parametros de la venta',
                    'errores' => $validate -> errors()
                ];
            }else{
                $venta = Venta::firstwhere('n_documento', $request -> n_documento);
                if (empty($venta)) {
                    $venta = new Venta();
                    $venta -> fecha_resolucion = $request -> fecha_resolucion;
                    $venta -> n_documento = $request -> n_documento;
                    $venta -> medio_pago_id = $request -> medio_pago_id;
                    $venta -> cliente_id = $request -> cliente_id;
                    $venta -> detalle_venta_id = $request -> detalle_venta_id;
                    $venta -> save();

                    $data =[
                        'code' =>200,
                        'status' => 'success',
                        'mensaje' =>'Se ha creado correctamente',
                        'Venta' => $venta,
                    ];
                }else{
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'Ya existe la venta'
                    ];
                }
            }
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensaje' => 'Error al crear la venta'
            ];
        }
        return response() ->json($data);
    }

    public function editar(Request $request)
    {
        if (!empty($request -> all())) {
            $validate = Validator::make($request -> all(), [
                'id' => 'required',
                'fecha_resolucion' => 'required',
                'n_documento' => 'required',
            ]);
            if ($validate -> fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'mensaje' => 'Error al editar la venta',
                    'errores' => $validate -> errors()
                ];
            }else{
                $venta = Venta::find($request -> id);
                if ($venta != null) {
                    $venta -> fecha_resolucion = $request -> fecha_resolucion;
                    $venta -> n_documento = $request -> n_documento;
                    $venta -> save();
                    $data = [
                        'code' => 200,
                        'status' => 'success',
                        'mensaje' => 'Se ha editado correctamente la venta',
                        'Venta' => $venta
                    ];
                }else{
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'No se encuentra la venta'
                    ];
                }
            }
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensaje' => 'No se han ingresado los datos de la venta correctamente'
            ];
        }
        return response() -> json($data);
    }

    public function eliminar(Request $request)
    {
        if ($request -> id == '') {
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensaje' => 'Debe ingresar un ID de venta para eliminar'
            ];
            return response() -> json($data);
        }
        $venta = Venta::find($request -> id);
        if ($venta != null) {
            $venta -> delete();
            $data = [
                'code' => 200,
                'status' => 'success',
                'mensaje' => 'Se ha eliminado correctamente la venta'
            ];
        }else{
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensaje' => 'No se encontro ID asociado a la venta'
            ];
        }
        return response() -> json($data);       
    }

    public function buscarPorNroDeDocumento(Request $request)
    {
        if ($request -> n_documento == '') {
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensaje' => 'Porfavor, ingrese un numero de documento'
            ];
        }else{
            $venta = Venta::firstwhere('n_documento', $request -> n_documento);
            if (empty($venta)) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'mensaje' => 'No existe una venta asociada a este numero de documento'
                ];
            }else{
                $data = [
                    'code' => 200,
                    'Venta' => $venta
                ];
            }
        }
        return response() -> json($data);
    }
    
}