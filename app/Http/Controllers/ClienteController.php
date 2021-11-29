<?php

namespace App\Http\Controllers;


use App\Cliente;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function listar()
    {
        $cliente =  DB::table('cliente')->select('*')->get();
        $data=[
            'code'=>200,
            'Cliente'=>$cliente];
        return response() -> json($cliente);
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
                $cliente = Cliente::firstwhere('rut', $request ->rut);
                if (empty($cliente)) {
                    $cliente = new Cliente();
                    $cliente ->rut =$request-> rut;
                    $cliente ->nombre =$request -> nombre;
                    $cliente ->apellido= $request-> apellido;
                    $cliente ->email = $request ->email;
                    $cliente ->telefono =$request-> telefono;
                    $cliente ->direccion =$request-> direccion;
                    $cliente ->save();
                     
                    $data =[
                    'code' =>200,
                    'status' => 'success',
                    'mensaje' => 'Se ha creado correctamente',
                    'Cliente' => $cliente,
                ];
                } else {
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'Ya existe el cliente',
                    ];
                }
            }
        } else {
            $data = [
                'code' =>400,
                'status' => 'error',
                'mensaje' => 'Error al crear el cliente'
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
                $cliente = Cliente::find($request ->id);
                if ($cliente !=null) {
                    $cliente ->rut = $request -> rut;
                    $cliente ->nombre = $request -> nombre;
                    $cliente ->apellido = $request -> apellido;
                    $cliente ->email = $request -> email;
                    $cliente ->telefono = $request -> telefono;
                    $cliente ->direccion = $request -> direccion;
                    $cliente ->save();
                    $data = [
                    'code' => 200,
                    'status' => 'success',
                    'mensaje' => 'Se ha editado correctamente',
                    'Cliente' => $cliente
                ];
                } else {
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'No se encuentra el cliente'];
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
        if ($request ->id == '') {
            $data = [
                'code' => 400,
                'status' => 'error',
                'mensaje' => 'Debe ingresar un ID del cliente para eliminar'
            ];
            return response() -> json($data);
        }
        $cliente = Cliente::find($request ->id);
        if ($cliente != null) {
            $cliente -> delete();
            $data = [
                'code' => 200,
                'status' => 'success',
                'mensaje' => 'Se ha eliminado correctamente'
            ];
        } else {
            $data = [
                    'code' => 200,
                    'status' => 'success',
                    'mensaje' => 'No se encontro el ID'
                ];
        }
        return response() -> json($data);
    }
    public function buscarClientePorRut(Request $request)
    {
        if ($request ->rut == '') {
            $data =[
                'code' =>400,
                'status' => 'error',
                'mensaje' => 'Ingrese un rut porfavor'
            ];
        } else {
            $cliente = Cliente::firstwhere('rut', $request -> rut);
            if (empty($cliente)) {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'mensaje' => 'No existe cliente asociado a ese rut'
                ];
            } else {
                $data = [
                    'code' => 200,
                    'Cliente' => $cliente
                ];
            }
        }
        return response() -> json($data);
    }
    public function ventasDeUnCliente(Request $request)
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
                $cliente = Cliente::firstwhere('rut', $request ->rut);
                if (empty($cliente)) {
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'No existe cliente asociado a ese rut'
                    ];
                } else {
                    $ventas = DB::table('venta') -> where('cliente_id', $cliente ->id) -> get();
                    if (empty($ventas ->all())) {
                        $data = [
                            'code' =>200,
                            'status' => 'success',
                            'mensaje' => 'No existen ventas asociados al cliente'
                        ];
                    } else {
                        $data = [
                        'code' =>200,
                        'status' => 'success',
                        'Ventas' => $ventas
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

    public function cantidadDeVentasDeUnCliente(){
        $clientes = DB::table('cliente')->select(array('cliente.rut','cliente.nombre','cliente.apellido',DB::raw('COUNT(venta.cliente_id) as CantidadVentas')))->groupBy('cliente.rut','cliente.nombre','cliente.apellido') ->join('venta','cliente.id','=','venta.cliente_id')->get();
        $data = [
            'code' =>200,
            'Ventas' => $clientes
        ];

        return response()->json($data);

    }
    public function OrdenarClientePorOrdenAlfabetico(){
        $cliente = DB::table('cliente')->select('*')->orderBy('apellido')->get();
        $data=[
            'code'=>200,
            'cliente'=>$cliente
        ];
        return response()->json($data);
    }

}

    
