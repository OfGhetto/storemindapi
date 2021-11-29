<?php

namespace Tests\Feature\Controllers;


use App\MedioDePago;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use \Illuminate\Support\Facades\DB;
use Tests\TestCase;

class MedioDePagoControllerTest extends TestCase
{

    //                   TESTING DEL METODO CREAR

    /**
     * Se realiza prueba donde se crea correctamente una MedioDePago
     *
     *@return void
     */
    /** @test */
    public function creacionCorrectaMedioDePago (){
        $data = [
            'forma_pago' => 'Cheque',
            'descripcion' => 'pago con cheque al dia'
        ];
        $response = $this ->post(route('MedioDePago.crear'),$data);
        $mediodepago = MedioDePago::where('forma_pago', 'Cheque')-> first();
        $datacorrect = [
            'forma_pago' => 'Cheque',
            'descripcion' => 'pago con cheque al dia',
            'id' => $mediodepago -> id
        ];
        $response -> assertJson(['code' => 200, 'status' => 'success','mensaje' => 'Se ha creado correctamente', 'mediodepago' => $datacorrect]);
        $response -> assertJsonFragment($datacorrect);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


     /**
     * Se realiza prueba donde no se crea correctamente una MedioDePago debido a que ya existe
     *
     * @return void
     */
    /** @Test */
    public function creacionIncorrectaMedioDePagoYaCreada(){
        $data = [
            'forma_pago' => 'Cheque',
            'descripcion' => 'pago con Cheque al dia'
        ];
        $response = $this ->post(route('MedioDePago.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'Ya existe el Medio De Pago']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


     /**
     * Se realiza prueba donde no se crea correctamente una MedioDePago debido a que no se ingresan todos los
     * atributos
     *
     * @return void
     */
    /** @test */
    public function creacionIncorrectaMedioDePagoFaltanAtributos(){
        $data = [
            'forma_pago' => 'Cheque'
        ];
        $response = $this ->post(route('MedioDePago.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'No ha ingresado todos los parametros']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se crea correctamente una MedioDePago debido a que el request esta vacio
     *
     * @return void
     */
    /** @test */
    public function creacionIncorrectaMedioDePagoRequestVacio(){
        $data = [

        ];
        $response = $this ->post(route('MedioDePago.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'Error al crear el Medio De Pago']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }





//                              TESTING DEL METODO EDITAR

    /**
     * Se realiza prueba donde se edita correctamente un MedioDePago
     *
     * @return void
     */
    /** @test */

    public function edicionCorrecta(){
        $data = [
            'id'=> 125,
            'forma_pago' => 'Efectivo',
            'descripcion' => 'pago con efectivo'
        ];
        $response = $this->put(route('MedioDePago.editar'),$data);
        $response ->assertJson(['code' => 200,'status' => 'success','mensaje' => 'Se ha editado correctamente','mediodepago' =>$data]);
        $response ->assertJsonFragment($data);
        $response -> assertStatus(200);
        $response -> assertOk();
    }

    /**
     * Se realiza prueba donde no se edita correctamente una MedioDePago debido a que no se encuentra
     *
     * @return void
     */
    /** @test */

    public function edicionIncorrectaNoSeEncuentraMedioDePago(){
        $data = [
            'id'=> 1001,
            'forma_pago' => 'Cheque',
            'descripcion' => 'pago con cheque al dia'
        ];
        $response = $this->put(route('MedioDePago.editar'),$data);
        $response ->assertJson(['code' => 400,'status' => 'error','mensaje' => 'No se encuentra el Medio De Pago']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se edita correctamente una MedioDePago debido a que faltan atributos
     *
     * @return void
     */
    /** @test */

    public function edicionIncorrectaFaltanAtributosMedioDePago(){
        $data = [
            'id'=>100,
            'forma_pago' => 'Efectivo'
        ];
        $response = $this->put(route('MedioDePago.editar'),$data);
        $response ->assertJson(['code' => 400,'status' => 'error','mensaje' => 'No ha ingresado todos los parametros']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se edita correctamente una MedioDePago debido a
     * que el request esta vacio
     *
     * @return void
     */
    /** @test */

    public function edicionIncorrectaRequestVacio(){
        $data = [

        ];
        $response = $this->put(route('MedioDePago.editar'),$data);
        $response ->assertJson(['code' => 400,'status' => 'error','mensaje' => 'Error al editar']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }





   //                    TESTING DEL METODO ELIMINAR

    /**
     *        se realiza la prueba donde se elimina correctamente un MedioDePago
     *
     * @return void
     */
    /** @test */
    public function EliminacionCorrectaMedioDePago () {
        $data = [
            'id' =>125
        ];
        $response =$this ->delete(route('MedioDePago.eliminar'),$data);
        $response ->assertJson(['code' =>200, 'status' => 'success', 'mensaje' => 'Se ha eliminado correctamente']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


        /**
     * Se realiza prueba donde no se elimina correctamente un MedioDePago
     * debido a que el id no tiene MedioDePago asociado
     *
     * @return void
     */
    /** @test    */
    public function eliminacionIncorrectaIDSinMedioDePagoAsociada(){
        $data = [
            'id'=>'1001'
        ];

        $response  =  $this->delete(route('MedioDePago.eliminar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'No se encontro el Medio De Pago asociada al ID']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }




    /**
     * Se realiza prueba donde no se elimina correctamente un MedioDePago
     * debido a que no se ingreso el id
     *
     * @return void
     */
    /** @test  */
    public function eliminacionIncorrectaDeMedioDePagoFaltaID(){
        $data = [
            'id'=>''
        ];

        $response  =  $this->delete(route('MedioDePago.eliminar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'Debe ingresar un ID de un Medio De Pago']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }
}







