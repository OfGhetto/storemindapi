<?php

namespace App\Http\Controllers;

use App\Producto;
use  Illuminate\Http\Request;
use App\Proveedor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function listar()
    {
        $producto = producto::all();
        $data = [
            'code' => 200,
            'Producto' => $producto
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
                'valor' => 'required',
                'cantidad' => 'required',
                'nombre' => 'required',
                'marca' => 'required',
                'unidadMedida' => 'required',
                'categoria_id' => 'required',
                'proveedor_id' => 'required'
            ]);
            if ($validate -> fails()) {
                $data = [
                    'code' =>400,
                    'status' => 'error',
                    'mensaje' => 'No ha ingresado todos los parametros',
                    'errores' => $validate -> errors()
                ];
            } else {
                $producto = Producto::where('nombre', $request->nombre) -> where('marca', $request ->marca)-> first();
                if (empty($producto)) {
                    $producto = new Producto();
                    $producto ->valor = $request ->valor;
                    $producto ->cantidad = $request ->cantidad;
                    $producto ->nombre= $request ->nombre;
                    $producto ->marca = $request ->marca;
                    $producto ->unidadMedida = $request ->unidadMedida;
                    $producto ->categoria_id = $request ->categoria_id;
                    $producto ->proveedor_id = $request ->proveedor_id;
                    $producto -> save();
                    $data = [
                        'code' => 200,
                        'status' => 'success',
                        'mensaje' => 'Se ha creado correctamente',
                        'Producto' => $producto
                    ];
                } else {
                    $data = [
                        'code' => 200,
                        'status' => 'error',
                        'mensaje' => 'Ya existe producto con marca y nombre identico'
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
                'valor' => 'required',
                'cantidad' => 'required',
                'nombre' => 'required',
                'marca' => 'required',
                'unidadMedida' => 'required'
            ]);
            if ($validate ->fails()) {
                $data = [
                    'code' =>400,
                    'status' => 'error',
                    'mensaje' => 'No ha ingresado todos los parametros',
                    'errores' => $validate -> errors()
                ];
            } else {
                $producto = Producto::find($request->id);
                if (empty($producto)) {
                    $data = [
                        'code' => 400,
                        'status' => 'error',
                        'mensaje' => 'No se encuentra el producto'];
                } else {
                    $producto -> valor = $request -> valor;
                    $producto -> cantidad = $request -> cantidad;
                    $producto -> nombre = $request -> nombre;
                    $producto -> marca = $request -> marca;
                    $producto -> unidadMedida = $request -> unidadMedida;
                    $producto -> save();
                    $data =[
                        'code' => 200,
                        'status' => 'success',
                        'mensaje' => 'Se ha editado correctamente',
                        'Producto' =>$producto
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
            'mensaje' => 'Debe ingresar un ID de un producto'
        ];
        }else{
            $producto = Producto::find($request ->id);
            if(empty($producto)){
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'mensaje' => 'No se encontro el producto asociado al ID'
                ];
            }else{
                $producto -> delete();
                $data = [
                    'code' => 200,
                    'status' => 'success',
                    'mensaje' => 'Se ha eliminado correctamente'
                ];
            }
        }
        return response() -> json($data);
    }

    public function listarAlfabeticamente(){
        $producto = DB::table('producto') -> select('*') -> orderBy('nombre') -> get();
        $data = [
            'code' => 200,
            'Producto' => $producto
        ];
        return response() -> json($data);
    }

    public function listarDeMenorAMayorCantidad(){
        $producto = DB::table('producto') -> select('*') -> orderBy('cantidad') -> get();
        $data = [
            'code' => 200,
            'Producto' => $producto
        ];
        return response() -> json($data);
    }

    public function reporteProductosVendidos(){
        $productos = DB::table('producto')->select(array('producto.nombre','marca',DB::raw('SUM(producto.cantidad) as CantidadVendida'),DB::raw('(SUM(producto.cantidad)*producto.valor) + detalle_venta.iva as TotalVendido'))) ->groupBy('producto.nombre','marca','producto.valor','detalle_venta.iva')->join('detalle_venta', 'producto.id','=','producto_id')->get();
        $data = [
            'code' =>200,
            'Reporte' => $productos
        ];
        return response() ->json($data);
    }

}
