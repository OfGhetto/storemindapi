<?php

namespace App\Http\Controllers;

use App\Producto;
use App\Proveedor;
use Illuminate\Support\Facades\Validator;
use  Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class ProveedorController extends Controller
{
    public function listar()
    {
        $proveedor = DB::table('proveedor') -> select('*') ->get();
        $data = [
            'code' => 200,
            'Proveedor' => $proveedor
        ];
        return response() -> json($data);
    }

    public function index()
    {
        return \csrf_token();
    }

    public function crear(Request $request)
    {
        if (!empty($request ->all())) {
            $validate = Validator::make($request -> all(), [
                'rut' => 'required',
                'nombre' => 'required',
                'apellido' => 'required',
                'email' => 'required',
                'telefono' => 'required',
                'direccion' => 'required',
            ]);
            if ($validate ->fails()) {
                $data = [
                    'code' =>400,
                    'status' => 'error',
                    'mensaje' => 'No ha ingresado todos los parametros',
                    'errores' => $validate -> errors()
                ];
            } else {
                $proveedor = Proveedor::firstwhere('rut', $request ->rut);
                if (empty($proveedor)) {
                    $proveedor = new Proveedor();
                    $proveedor ->rut =$request-> rut;
                    $proveedor ->nombre =$request -> nombre;
                    $proveedor ->apellido= $request-> apellido;
                    $proveedor ->email = $request ->email;
                    $proveedor ->telefono =$request-> telefono;
                    $proveedor ->direccion =$request-> direccion;
                    $proveedor ->save();
                     
                    $data =[
                    'code' =>200,
                    'status' => 'success',
                    'mensaje' => 'Se ha creado correctamente',
                    'Proveedor' => $proveedor,
                ];
                } else {
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'Ya existe el proveedor',
                    ];
                }
            }
        } else {
            $data = [
                'code' =>400,
                'status' => 'error',
                'mensaje' => 'Error al crear el proveedor'
            ];
        }
        return response() ->json($data);
    }

    public function editar(Request $request)
    {
        if (!empty($request -> all())) {
            $validate = Validator::make($request ->all(), [
                'id' => 'required',
                'rut' => 'required',
                'nombre' => 'required',
                'apellido' => 'required',
                'email' => 'required',
                'telefono' => 'required',
                'direccion' => 'required',
            ]);
            if ($validate -> fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'mensaje' => 'No ha ingresado todos los parametros',
                    'errores' => $validate -> errors()
                ];
            } else {
                $proveedor = Proveedor::find($request ->id);
                if (empty($proveedor)) {
                    $data = [
                    'code' => 400,
                    'status' => 'error',
                    'mensaje' => 'No se encuentra el proveedor'];
                } else {
                    $proveedor ->rut = $request -> rut;
                    $proveedor ->nombre = $request -> nombre;
                    $proveedor ->apellido = $request -> apellido;
                    $proveedor ->email = $request -> email;
                    $proveedor ->telefono = $request -> telefono;
                    $proveedor ->direccion = $request -> direccion;
                    $proveedor ->save();
                    $data = [
                    'code' => 200,
                    'status' => 'success',
                    'mensaje' => 'Se ha editado correctamente',
                    'Proveedor' => $proveedor
                ];
                }
            }
        } else {
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensaje' => 'Error al editar'];
        }
        return response()->json($data);
    }

    public function eliminar(Request $request)
    {
        if ($request ->rut == '') {
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensaje' => 'Debe ingresar el rut del proveedor'
            ];
        } else {
            $proveedor = Proveedor::firstwhere('rut', $request -> rut);
            if (empty($proveedor)) {
                $data = [
                'code' => 200,
                'status' => 'success',
                'mensaje' => 'No se encontro el proveedor'
            ];
            } else {
                $proveedor -> delete();
                $data = [
                'code' => 200,
                'status' => 'success',
                'mensaje' => 'Se ha eliminado correctamente'
            ];
            }
        }
        return response() -> json($data);
    }

    public function buscarPorRut(Request $request)
    {
        if ($request ->rut == '') {
            $data =[
                'code' =>400,
                'status' => 'error',
                'mensaje' => 'Ingrese un rut porfavor'
            ];
        } else {
            $proveedor = Proveedor::firstwhere('rut', $request -> rut);
            if (empty($proveedor)) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'mensaje' => 'No existe proveedor asociado a ese rut'
                ];
            } else {
                $data = [
                    'code' => 200,
                    'Proveedor' => $proveedor
                ];
            }
        }
        return response() -> json($data);
    }

    public function productosDeUnProveedor(Request $request)
    {
        if (!empty($request -> all())) {
            $validate = Validator::make($request ->all(), [
                'rut' => 'required'
            ]);
            if ($validate -> fails()) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'mensaje' => 'No ha ingresado un rut',
                    'errores' => $validate -> errors()
                ];
            } else {
                $proveedor = Proveedor::firstwhere('rut', $request ->rut);
                if (empty($proveedor)) {
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'No existe proveedor asociado a ese rut'
                    ];
                } else {
                    $productos = DB::table('producto') -> where('proveedor_id', $proveedor ->id) -> get();
                    if (empty($productos ->all())) {
                        $data = [
                            'code' =>200,
                            'status' => 'success',
                            'mensaje' => 'No existen productos asociados al proveedor'
                        ];
                    } else {
                        $data = [
                        'code' =>200,
                        'status' => 'success',
                        'Productos' => $productos
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

    public function listarAlfabeticamente()
    {
        $proveedor = DB::table('proveedor') -> select('*') ->orderBy('apellido') -> get();
        $data = [
            'code' => 200,
            'Proveedor' => $proveedor
        ];
        return response() -> json($data);
    }
}
