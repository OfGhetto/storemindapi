<?php

namespace Tests\Feature;

use App\DetalleVenta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DetalleVentaControllerTest extends TestCase
{
    //                  TESTING METODO CREAR

    /**
     * Se realiza prueba donde se crea correctamente un DetalleVenta
     *
     * @return void
     */
    /** @test */
    public function creacionCorrectaDetalleVenta(){
        $data = [
            'iva' => 4176,
            'total' => 21980,
            'cantidad' => 2,
            'producto_id' => 666,
        ];
        $response = $this ->post(route('DetalleVenta.crear'),$data);
        $detalleventa = DetalleVenta::firstwhere('iva','4176');
        $datacorrect = [
            'iva' => 4176,
            'total' => 21980,
            'cantidad' => 2,
            'producto_id' => 666,
            'id' => $detalleventa ->id
        ];
        $response -> assertJson(['code' => 200, 'status' => 'success','mensaje' => 'Se ha creado correctamente', 'detalleventa' => $datacorrect]);
        $response -> assertJsonFragment($datacorrect);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se crea correctamente un DetalleVenta debido a que ya existe
     *
     * @return void
     */
    /** @test */
    public function creacionIncorrectaProductoNoAsociado(){
        $data = [
            'id' => 152,
            'iva' => 8888,
            'total' => 10000,
            'cantidad' => 3,
            'producto_id' => 100
        ];
        $response = $this ->post(route('DetalleVenta.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'No existe producto asociado a ese ID']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se crea correctamente un DetalleVenta debido a que no se ingresan todos los
     * atributos
     *
     * @return void
     */
    /** @test */
    public function creacionIncorrectaDetalleVentaFaltanAtributos(){
        $data = [
            'id' => 152,
            'total' => 10000,
            'producto_id' => 666
        ];
        $response = $this ->post(route('DetalleVenta.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'No ha ingresado todos los parametros']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }



    /**
     * Se realiza prueba donde no se crea correctamente un DetalleVenta debido a el request esta vacio
     *
     * @return void
     */
    /** @test */
    public function creacionIncorrectaDetalleVentaRequestVacio(){
        $data = [

        ];
        $response = $this ->post(route('DetalleVenta.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'Error al crear']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }



//                              EDITAR

    /**
     * Se realiza prueba donde se edita correctamente un DetalleVenta
     *
     * @return void
     */
    /** @test */

    public function edicionCorrecta(){
        $data = [
            'id' => 152,
            'total' => 32970,
            'cantidad' => 3,
            'producto_id' => 666
        ];
        $response = $this->put(route('DetalleVenta.editar'),$data);
        $detalleventa=DetalleVenta::firstwhere('total','32970');
        $datacorrect=[
                'id' => 152,
                'iva' => 6264,
                'total' => 32970,
                'cantidad' => 3,
                'producto_id' => $detalleventa->producto_id      
        ];

        $response ->assertJson(['code' => 200,'status' => 'success','mensaje' => 'Se ha editado correctamente','detalleventa' =>$datacorrect]);
        $response ->assertJsonFragment($datacorrect);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se edita correctamente un DetalleVenta debido a que no se encuentra
     *
     * @return void
     */
    /** @test */

    public function edicionIncorrectaNoSeEncuentraDetalleVenta(){
        $data = [
            'id'=> 10100,
            'iva' => 8888,
            'total' => 10000,
            'cantidad' => 3,
        ];
        $response = $this->put(route('DetalleVenta.editar'),$data);
        $response ->assertJson(['code' => 400,'status' => 'error','mensaje' => 'No se encuentra el DetalleVenta']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se edita correctamente un DetalleVenta debido a faltan atributos
     *
     * @return void
     */
    /** @test */

    public function edicionIncorrectaFaltanAtributosDetalleVenta(){
        $data = [
            'id'=> 152,
            'iva' => 8888,
            'cantidad' => 3,
        ];
        $response = $this->put(route('DetalleVenta.editar'),$data);
        $response ->assertJson(['code' => 400,'status' => 'error','mensaje' => 'No ha ingresado todos los parametros']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se edita correctamente un DetalleVenta debido a
     * que el request esta vacio
     *
     * @return void
     */
    /** @test */

    public function edicionIncorrectaRequestVacio(){
        $data = [

        ];
        $response = $this->put(route('DetalleVenta.editar'),$data);
        $response ->assertJson(['code' => 400,'status' => 'error','mensaje' => 'Error al editar']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


//                      ELIMINAR

    /**
     * Se realiza prueba donde se elimina correctamente un DetalleVenta
     *
     * @return void
     */
    /** @test     */
    public function eliminacionCorrectaDetalleVenta(){
        $data = [
            'id'=>152
        ];

        $response  =  $this->delete(route('DetalleVenta.eliminar'),$data);
        $response -> assertJson(['code' => 200, 'status' => 'success', 'mensaje' => 'Se ha eliminado correctamente']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


        /**
     * Se realiza prueba donde no se elimina correctamente un DetalleVenta
     * debido a que no se ingreso el id
     *
     * @return void
     */
    /** @test    */
    public function eliminacionIncorrectaFaltaID(){
        $data = [
            'id'=>''
        ];

        $response  =  $this->delete(route('DetalleVenta.eliminar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'Debe ingresar un ID de un DetalleVenta']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se elimina correctamente un DetalleVenta
     * debido a que el id no tiene DetalleVenta asociado
     *
     * @return void
     */
    /** @test   */
    public function eliminacionIncorrectaIDSinDetalleVentaAsociado(){
        $data = [
            'id'=>'1001'
        ];

        $response  =  $this->delete(route('DetalleVenta.eliminar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'No se encontro el DetalleVenta asociado al ID']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


}


