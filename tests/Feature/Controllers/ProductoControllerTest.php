<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \Illuminate\Support\Facades\DB;
use App\Producto;

class ProductoControllerTest extends TestCase
{
//                  TESTING METODO CREAR

    /**
     * Se realiza prueba donde se crea correctamente un producto
     *                              
     * @return void
     */
    /** @test */
    public function creacionCorrectaProducto(){
        $data = [
            'nombre' => 'Tableros',
            'marca' => 'Construmark',
            'cantidad' => '100',
            'valor' => '15000',
            'unidadMedida' => 'Uni',
            'categoria_id'=> '110',
            'proveedor_id' => '2'
        ];
        $response = $this ->post(route('producto.crear'),$data);
        $producto = Producto::where('nombre', 'Tableros') -> where('marca', 'Construmark')-> first();
        $datacorrect = [
            'valor' => '15000',
            'cantidad' => '100',
            'nombre' => 'Tableros',
            'marca' => 'Construmark',
            'unidadMedida' => 'Uni',
            'categoria_id'=> '110',
            'proveedor_id' => '2',
            'id' => $producto -> id
        ];
        $response -> assertJson(['code' => 200, 'status' => 'success','mensaje' => 'Se ha creado correctamente', 'Producto' => $datacorrect]);
        $response -> assertJsonFragment($datacorrect);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se crea correctamente un producto debido a que ya existe
     *                              
     * @return void
     */
    /** @test */
    public function creacionIncorrectaProductoYaCreado(){
        $data = [
            'nombre' => 'Tableros',
            'marca' => 'Construmark',
            'cantidad' => '100',
            'valor' => '15000',
            'unidadMedida' => 'Uni',
            'categoria_id'=> '3',
            'proveedor_id' => '1'
        ];
        $response = $this ->post(route('producto.crear'),$data);
        $response -> assertJson(['code' => 200, 'status' => 'error','mensaje' => 'Ya existe producto con marca y nombre identico']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se crea correctamente un producto debido a que no se ingresan todos los
     * atributos
     *                              
     * @return void
     */
    /** @test */
    public function creacionIncorrectaProductoFaltanAtributos(){
        $data = [
            'marca' => 'Construmark',
            'cantidad' => '100',
            'valor' => '15000',
            'unidadMedida' => 'Uni',
            'categoria_id'=> '3',
            'proveedor_id' => '1'
        ];
        $response = $this ->post(route('producto.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'No ha ingresado todos los parametros']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }




    /**
     * Se realiza prueba donde no se crea correctamente un producto debido a el request esta vacio
     *                              
     * @return void
     */
    /** @test */
    public function creacionIncorrectaProductoRequestVacio(){
        $data = [
            
        ];
        $response = $this ->post(route('producto.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'Error al crear']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }



//                              EDITAR

    /**
     * Se realiza prueba donde se edita correctamente un producto
     *                              
     * @return void
     */
    /** @test */

    public function edicionCorrecta(){
        $data = [
            'id'=> 1000,
            'nombre'=> 'galletera',
            'marca' =>'bauker',
            'cantidad'=> '5',
            'valor' =>'25000',
            'unidadMedida'=> 'Uni',
        ];
        $response = $this->put(route('producto.editar'),$data);
        $producto = Producto::find(1000);
        $datacorrect = [
            'id'=> 1000,
            'nombre'=> 'galletera',
            'marca' =>'bauker',
            'cantidad'=> '5',
            'valor' =>'25000',
            'unidadMedida'=> 'Uni',
            'categoria_id' =>$producto ->categoria_id,
            'proveedor_id'=> $producto ->proveedor_id
        ];
        $response ->assertJson(['code' => 200,'status' => 'success','mensaje' => 'Se ha editado correctamente','Producto' =>$datacorrect]);
        $response ->assertJsonFragment($datacorrect);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se edita correctamente un producto debido a que no se encuentra
     *                              
     * @return void
     */
    /** @test */

    public function edicionIncorrectaNoSeEncuentraProducto(){
        $data = [
            'id'=> 10100,
            'nombre'=> 'galletera',
            'marca' =>'bauker',
            'cantidad'=> '5',
            'valor' =>'25000',
            'unidadMedida'=> 'Uni',
            'categoria_id' =>110,
            'proveedor_id'=> 6
        ];
        $response = $this->put(route('producto.editar'),$data);
        $response ->assertJson(['code' => 400,'status' => 'error','mensaje' => 'No se encuentra el producto']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se edita correctamente un producto debido a faltan atributos
     *                              
     * @return void
     */
    /** @test */

    public function edicionIncorrectaFaltanAtributosProducto(){
        $data = [
            'id'=> 1001,
            'cantidad'=> '5',
            'valor' =>'25000',
            'unidadMedida'=> 'Uni',
            'categoria_id' =>110,
            'proveedor_id'=> 6
        ];
        $response = $this->put(route('producto.editar'),$data);
        $response ->assertJson(['code' => 400,'status' => 'error','mensaje' => 'No ha ingresado todos los parametros']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se edita correctamente un producto debido a 
     * que el request esta vacio
     *                              
     * @return void
     */
    /** @test */

    public function edicionIncorrectaRequestVacio(){
        $data = [

        ];
        $response = $this->put(route('producto.editar'),$data);
        $response ->assertJson(['code' => 400,'status' => 'error','mensaje' => 'Error al editar']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


//                      ELIMINAR

    /**
     * Se realiza prueba donde se elimina correctamente un producto
     *                              
     * @return void
     */
    /** @test     */
    public function eliminacionCorrectaProducto(){
        $data = [
            'id'=>1000
        ];

        $response  =  $this->delete(route('producto.eliminar'),$data);
        $response -> assertJson(['code' => 200, 'status' => 'success', 'mensaje' => 'Se ha eliminado correctamente']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


        /**
     * Se realiza prueba donde no se elimina correctamente un producto
     * debido a que no se ingreso el id
     *                              
     * @return void
     */
    /** @test    */ 
    public function eliminacionIncorrectaFaltaID(){
        $data = [
            'id'=>''
        ];

        $response  =  $this->delete(route('producto.eliminar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'Debe ingresar un ID de un producto']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se elimina correctamente un producto
     * debido a que el id no tiene producto asociado
     *                              
     * @return void
     */
    /** @test   */  
    public function eliminacionIncorrectaIDSinProductoAsociado(){
        $data = [
            'id'=>'10011'
        ];

        $response  =  $this->delete(route('producto.eliminar'),$data);
        $response -> assertJson(['code' => 200, 'status' => 'success', 'mensaje' => 'No se encontro el producto asociado al ID']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


}
