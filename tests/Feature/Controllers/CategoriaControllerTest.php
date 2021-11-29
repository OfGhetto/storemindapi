<?php

namespace Tests\Feature\Controllers;

use App\categoria;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \Illuminate\Support\Facades\DB;

class CategoriaControllerTest extends TestCase
{


    //                   TESTING DEL METODO CREAR

    /**
     * Se realiza prueba donde se crea correctamente una categoria
     *
     *@return void
     */
    /** @test */
    public function creacionCorrectaCategoria (){
        $data = [
            'nombre' => 'Cemento',
            'descripcion' => 'diferentes tipos y marcas de cemento'
        ];
        $response = $this ->post(route('categoria.crear'),$data);
        $categoria = categoria::where('nombre', 'Cemento')-> first();
        $datacorrect = [
            'nombre' => 'Cemento',
            'descripcion' => 'diferentes tipos y marcas de cemento',
            'id' => $categoria -> id
        ];
        $response -> assertJson(['code' => 200, 'status' => 'success','mensaje' => 'Se ha creado correctamente', 'categoria' => $datacorrect]);
        $response -> assertJsonFragment($datacorrect);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


     /**
     * Se realiza prueba donde no se crea correctamente una categoria debido a que ya existe
     *
     * @return void
     */
    /** @Test */
    public function creacionIncorrectaCategoriaYaCreada(){
        $data = [
            'nombre' => 'Cemento',
            'descripcion' => 'diferentes tipos y marcas de cemento'
        ];
        $response = $this ->post(route('categoria.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'Ya existe la categoria']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


     /**
     * Se realiza prueba donde no se crea correctamente una categoria debido a que no se ingresan todos los
     * atributos
     *
     * @return void
     */
    /** @test */
    public function creacionIncorrectaCategoriaFaltanAtributos(){
        $data = [
            'nombre' => 'Ceramicas'
        ];
        $response = $this ->post(route('categoria.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'No ha ingresado todos los parametros']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se crea correctamente una categoria debido a que el request esta vacio
     *
     * @return void
     */
    /** @test */
    public function creacionIncorrectaCategoriaRequestVacio(){
        $data = [

        ];
        $response = $this ->post(route('categoria.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'Error al crear la categoria']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }





//                              TESTING DEL METODO EDITAR

    /**
     * Se realiza prueba donde se edita correctamente un categoria
     *
     * @return void
     */
    /** @test */

    public function edicionCorrecta(){
        $data = [
            'id'=> 70,
            'nombre' => 'Tuberia',
            'descripcion' => 'Tuberias de todos los tamanios'
        ];
        $response = $this->put(route('categoria.editar'),$data);
        $response ->assertJson(['code' => 200,'status' => 'success','mensaje' => 'Se ha editado correctamente','categoria' =>$data]);
        $response ->assertJsonFragment($data);
        $response -> assertStatus(200);
        $response -> assertOk();
    }

    /**
     * Se realiza prueba donde no se edita correctamente una categoria debido a que no se encuentra
     *
     * @return void
     */
    /** @test */

    public function edicionIncorrectaNoSeEncuentraCategoria(){
        $data = [
            'id'=> 1001,
            'nombre' => 'Candados',
            'descripcion' => 'Candados de todos los tamanios'
        ];
        $response = $this->put(route('categoria.editar'),$data);
        $response ->assertJson(['code' => 400,'status' => 'error','mensaje' => 'No se encuentra la categoria']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se edita correctamente una categoria debido a que faltan atributos
     *
     * @return void
     */
    /** @test */

    public function edicionIncorrectaFaltanAtributosCategoria(){
        $data = [
            'id'=>100,
            'nombre' => 'Tuberias'
        ];
        $response = $this->put(route('categoria.editar'),$data);
        $response ->assertJson(['code' => 400,'status' => 'error','mensaje' => 'No ha ingresado todos los parametros']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se edita correctamente una categoria debido a
     * que el request esta vacio
     *
     * @return void
     */
    /** @test */

    public function edicionIncorrectaRequestVacio(){
        $data = [

        ];
        $response = $this->put(route('categoria.editar'),$data);
        $response ->assertJson(['code' => 400,'status' => 'error','mensaje' => 'Error al editar']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }





   //                    TESTING DEL METODO ELIMINAR

    /**
     *        se realiza la prueba donde se elimina correctamente una categoria
     *
     * @return void
     */
    /** @test */
    public function EliminacionCorrectaCategoria () {
        $data = [
            'id' =>121
        ];
        $response =$this ->delete(route('categoria.eliminar'),$data);
        $response ->assertJson(['code' =>200, 'status' => 'success', 'mensaje' => 'Se ha eliminado correctamente']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


        /**
     * Se realiza prueba donde no se elimina correctamente un producto
     * debido a que el id no tiene producto asociado
     *
     * @return void
     */
    /** @test    */
    public function eliminacionIncorrectaIDSinCategoriaAsociada(){
        $data = [
            'id'=>'1001'
        ];

        $response  =  $this->delete(route('categoria.eliminar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'No se encontro la categoria asociada al ID']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }




    /**
     * Se realiza prueba donde no se elimina correctamente un producto
     * debido a que no se ingreso el id
     *
     * @return void
     */
    /** @test  */
    public function eliminacionIncorrectaFaltaID(){
        $data = [
            'id'=>''
        ];

        $response  =  $this->delete(route('categoria.eliminar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'Debe ingresar un ID de una categoria']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


}




