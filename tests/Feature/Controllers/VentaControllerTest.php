<?php

namespace Tests\Feature;

use App\Venta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VentaControllerTest extends TestCase
{
    
    //        TESTING METODO CREAR
    /**
     * Se realiza una prueba en la cual se crea correctamente una venta
     *                              
     * @return void
     */
    /** @test        */
    public function creacionCorrecta(){
        $data = [
            'fecha_resolucion' => "2020-12-10",
            'n_documento' => 5437,
            'medio_pago_id' => 123,
            'cliente_id' => 88,
            'detalle_venta_id' => 151,
        ];
        $response = $this ->post(route('venta.crear'),$data);
        $venta = Venta::firstwhere('n_documento', 5437);
        $datacorrect = [
            'fecha_resolucion' => "2020-12-10",
            'n_documento' => 5437,
            'medio_pago_id' => 123,
            'cliente_id' => 88,
            'detalle_venta_id' => 151,
            'id' => $venta -> id
        ];
        $response -> assertJson(['code' => 200, 'status' => 'success','mensaje'=>'Se ha creado correctamente', 'Venta' => $datacorrect]);
        $response -> assertJsonFragment($datacorrect);
        $response -> assertStatus(200);
        $response -> assertOk();
    }




    /**
     * Se realiza prueba donde no se crea de manera correcta una venta debido a que ya existe
     * 
     *
     * @return void
     */
    /** @test        */
    public function creacionIncorrectaVentaYaCreada(){
        $data = [
            'fecha_resolucion' => "2020-11-24",
            'n_documento' => 78582,
            'medio_pago_id' => 123,
            'cliente_id' => 33,
            'detalle_venta_id' => 144,
        ];
        $response = $this ->post(route('venta.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'Ya existe la venta']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }
    




    /**
     * Se realiza prueba donde no se crea una venta correctamente debido a que faltan atributos
     *
     * @return void
     */
    /** @test     */
    public function creacionIncorrectaVentaFaltanAtributos(){
        $data = [
            'fecha_resolucion' => "2020-12-24",
            'n_documento' => 9375,
            'detalle_venta_id' => 154,
        ];
        $response = $this ->post(route('venta.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'No ha ingresado todos los parametros de la venta']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }
    



    
    /**
     * Se realiza prueba en la cual no se crea correctamente la venta debido a que el request esta vacio
     *
     * @return void
     */
    /** @test       */   
    public function creacionIncorrectaVentaRequestVacio(){
        $data = [
            
        ];
        $response = $this ->post(route('venta.crear'),$data);
        $response -> assertJsonFragment(['code' => 400, 'status' => 'error', 'mensaje' => 'Error al crear la venta']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }




    
    
    //       TESTING METODO EDITAR
    /**
     * Se realiza prueba en la cual se edita correctamente una venta
     *                              
     * @return void
     */
    /** @test     */
    public function edicionCorrecta(){
        $data = [
            'id'=> 788,
            'fecha_resolucion' => "2020-10-05",
            'n_documento' => 87986
        ];
        $response = $this ->put(route('venta.editar'),$data);
        $response -> assertJson(['code' => 200, 'status' => 'success','mensaje' => 'Se ha editado correctamente la venta', 'Venta' => $data]);
        $response -> assertJsonFragment($data);
        $response -> assertStatus(200);
        $response -> assertOk();
    }




    /**
     * Se realiza prueba en la cual no se edita correctamente una venta debido a que no se encuentra
     *                         
     * @return void
     */
    /** @test        */   
    public function edicionIncorrectaNoSeEncuentraVenta(){
        $data = [
            'id'=> 454,
            'fecha_resolucion' => "2020-11-02",
            'n_documento' => 43355
        ];
        $response = $this ->put(route('venta.editar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'No se encuentra la venta']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    

    /**
     * Se realiza prueba en la cual no se edita correctamente una venta debido a que faltan atributos
     *                             
     * @return void
     */
    /** @test       */
    public function edicionIncorrectaFaltanAtributos(){
        $data = [
            'id'=> 790,
            'fecha_resolucion' => "2020-09-04",
        ];
        $response = $this ->put(route('venta.editar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'Error al editar la venta']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }
    



    /**
     * Se realiza prueba en la cual no se crea correctamente una venta ya que el request esta vacio
     *
     * @return void
     */
    /** @test        */
    public function edicionVentaIncorrectaRequestVacio(){
        $data = [
            
        ];
        $response = $this ->put(route('venta.editar'),$data);
        $response -> assertJsonFragment(['code' => 400, 'status' => 'error', 'mensaje' => 'No se han ingresado los datos de la venta correctamente']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }



    

    //         TESTING DEL METODO ELIMINAR
    /**
     * Se realiza prueba donde se elimina correctamente una venta
     *
     * @return void
     */
    /** @test     */
    public function eliminacionCorrecta(){
        $data = [
            'id' => 789
        ];
        $response = $this ->delete(route('venta.eliminar'),$data);
        $response -> assertJson(['code' => 200, 'status' => 'success', 'mensaje' => 'Se ha eliminado correctamente la venta']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }



    /**
     * Se realiza una prueba donde no se elimina de manera correcta una venta debido a que el id estaba vacio
     *
     * @return void
     */
    /** @test          */ 
    public function eliminacionSinId(){
        $data = [
            'id' => ''
        ];
        $response = $this ->delete(route('venta.eliminar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'Debe ingresar un ID de venta para eliminar']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }



    /**
     * Se realiza una prueba donde no se elimina de manera correcta una venta debido a que el id no tiene una venta asociada
     *
     * @return void
     */
    /** @test      */
    public function eliminacionConIdSinUnaVentaAsociada(){
        $data = [
            'id' => '34'
        ];
        $response = $this ->delete(route('venta.eliminar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'No se encontro ID asociado a la venta']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    

    
    
    //      TESTING METODO BUSCAR POR NUMERO DE DOCUMENTO
    /**
     * Se realiza prueba de busqueda exitosa por numero de documento.
     * 
     * @return void
     */
    /** @test      */
    public function busquedaPorNroDeDocumento(){
        $datacorrect = [
            'id' => 786,
            'fecha_resolucion' => "2020-10-13",
            'n_documento' => 87958,
            'medio_pago_id' => 123,
            'cliente_id' => 22,
            'detalle_venta_id' => 143,
        ];

        $response = $this -> call('GET', '/venta/buscarpornumero',['n_documento' =>87958]);
        $response -> assertJson(['code' => 200, 'Venta' => $datacorrect]);
        $response -> assertJsonFragment($datacorrect);
        $response -> assertStatus(200);
        $response -> assertOk();
    }



    /**
     * Se realiza prueba de busqueda fallida mediante el numero de documento el cual se entrega vacio
     *
     *
     * @return void
     */
    /** @test     */
    public function busquedaSinNroDeDocumento(){
        $data = [
            'n_documento' => ''
        ];
        $response = $this -> get(route('venta.buscarnumero'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'Porfavor, ingrese un numero de documento']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }



}