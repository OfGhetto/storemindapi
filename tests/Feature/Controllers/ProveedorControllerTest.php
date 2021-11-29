<?php

namespace Tests\Feature\Controllers;

use App\Proveedor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \Illuminate\Support\Facades\DB;

class ProveedorControllerTest extends TestCase
{

    //                          TESTING METODO BUSQUEDA POR RUT
    //  
    /**
     * Se realiza prueba de busqueda exitosa mediante el rut
     *  
     * @return void
     */
    /** @test  */       
    public function busquedaPorRutCorrecta(){
        $datacorrect= [
            'id' => 2,
            'rut' => "975646141",
            'nombre' => "Dario",
            'apellido' => "Gonzales",
            'email' => "dgonzales@gmail.com",
            'telefono' => "89887887",
            'direccion' => "el roble, 44"
        ];

        $response = $this -> call('GET', '/proveedor/buscarporrut',['rut' => 975646141] );
        $response -> assertJson(['code' => 200, 'Proveedor' => $datacorrect]);
        $response -> assertJsonFragment($datacorrect);
        $response -> assertStatus(200);
        $response -> assertOk();
    }
 


    /**
     * Se realiza prueba de busqueda fallida mediante el rut
     * el cual se entrega vacio.
     *
     * @return void
     */
    /** @test        */
    public function busquedaSinRut(){
        $data = [
            'rut' => ''
        ];
        $response = $this -> get(route('proveedor.buscarporrut'),$data);
        $response -> assertJson(['code' =>400, 'status' => 'error', 'mensaje' => 'Ingrese un rut porfavor']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }
 

        /**
     * Se realiza prueba de busqueda fallida mediante el rut
     * el cual no esta asociado a ningun proveedor
     *
     * @return void
     */
    /** @test        */ 
    public function busquedaporRutSinProveedorAsociado()
        {
     
            $datacorrect= [
                'code' => 400,
                'status' => 'error',
                'mensaje' => 'No existe proveedor asociado a ese rut'
            ];
    
            $response = $this -> call('GET', '/proveedor/buscarporrut',['rut' => 4775588589] );
            $response -> assertJson(['code' => 400,'status' => 'error','mensaje' => 'No existe proveedor asociado a ese rut']);
            $response -> assertJsonFragment($datacorrect);
            $response -> assertStatus(200);
            $response -> assertOk();
        }

    //            TESTING METODO CREAR

    
    /**
     * Se realiza prueba donde se crea correctamente un proveedor
     *                              
     * @return void
     */
    /** @test */
    public function creacionCorrecta(){
        $data = [
            'rut' => 123,
            'nombre' => 'Prueba',
            'apellido' => 'test',
            'email' => 'prueba@gmail.com',
            'telefono' => '567',
            'direccion' => 'chile'
        ];
        $response = $this ->post(route('proveedor.crear'),$data);
        $proveedor = Proveedor::firstwhere('rut', 123);
        $datacorrect = [
            'rut' => 123,
            'nombre' => 'Prueba',
            'apellido' => 'test',
            'email' => 'prueba@gmail.com',
            'telefono' => '567',
            'direccion' => 'chile',
            'id' => $proveedor -> id
        ];
        $response -> assertJson(['code' => 200, 'status' => 'success','mensaje' => 'Se ha creado correctamente', 'Proveedor' => $datacorrect]);
        $response -> assertJsonFragment($datacorrect);
        $response -> assertStatus(200);
        $response -> assertOk();
    }



        /**
     * Se realiza prueba donde no se crea correctamente un proveedor debido a que ya existe
     *
     * @return void
     */
    /** @test     */
    public function creacionIncorrectaProveedorYaCreado(){
        $data = [
            'rut' => 194888744,
            'nombre' => 'Juan',
            'apellido' => 'Martinez',
            'email' => 'jmart@gmail.com',
            'telefono' => '61515112',
            'direccion' => 'chacabuco, 1132'
        ];
        $response = $this ->post(route('proveedor.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'Ya existe el proveedor']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }

    

    /**
     * Se realiza prueba donde no se crea correctamente un proveedor debido a que no se poseen
     * todos los atributos
     *
     * @return void
     */
    /** @test     */
    public function creacionIncorrectaProveedorFaltanAtributos(){
        $data = [
            'rut' => 123,
            'apellido' => 'test',
            'email' => 'prueba@gmail.com',
            'telefono' => '567',
            'direccion' => 'chile'
        ];
        $response = $this ->post(route('proveedor.crear'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'No ha ingresado todos los parametros']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se crea correctamente un proveedor debido a que 
     * el request esta vacio
     *
     * @return void
     */
    /** @test      */
    public function creacionIncorrectaProveedoRequestVacio(){
        $data = [
            
        ];
        $response = $this ->post(route('proveedor.crear'),$data);
        $response -> assertJsonFragment(['code' => 400, 'status' => 'error', 'mensaje' => 'Error al crear el proveedor']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }




     //             TESTING METODO EDITAR

         /**
     * Se realiza prueba donde se edita correctamente un proveedor
     *                              
     * @return void
     */
    /** @test     */
    public function edicionCorrecta(){
        $data = [
            'id' => 2,
            'rut' => 975646141,
            'nombre' => 'Dario',
            'apellido' => 'Gonzales',
            'email' => 'dgonzales@gmail.com',
            'telefono' => '89887887',
            'direccion' => 'el roble, 44'
        ];
        $response = $this ->put(route('proveedor.editar'),$data);
        $response -> assertJson(['code' => 200, 'status' => 'success','mensaje' => 'Se ha editado correctamente', 'Proveedor' => $data]);
        $response -> assertJsonFragment($data);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se edita correctamente un proveedor, debido a que no se encuentra
     *                              
     * @return void
     */
    /** @test     */    
    public function edicionIncorrectaNoSeEncuentraProveedor(){
        $data = [
            'id' => 15,
            'rut' => 975646141,
            'nombre' => 'Dario',
            'apellido' => 'Gonzales',
            'email' => 'dgonzales@gmail.com',
            'telefono' => '89887887',
            'direccion' => 'el roble, 44'
        ];
        $response = $this ->put(route('proveedor.editar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'No se encuentra el proveedor']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se edita correctamente un proveedor, debido a que no se enviaron
     * todos los atributos
     *                             
     * @return void
     */
    /** @test     */
    public function edicionIncorrectaFaltanAtributos(){
        $data = [
            'id' => 15,
            'nombre' => 'Dario',
            'apellido' => 'Gonzales',
            'email' => 'dgonzales@gmail.com',
            'telefono' => '89887887',
            'direccion' => 'el roble, 44'
        ];
        $response = $this ->put(route('proveedor.editar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'No ha ingresado todos los parametros']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }

    /**
     * Se realiza prueba donde no se crea correctamente un proveedor debido a que 
     * el request esta vacio
     *
     * @return void
     */
    /** @test    */
    public function edicionIncorrectaProveedorRequestVacio(){
        $data = [
            
        ];
        $response = $this ->put(route('proveedor.editar'),$data);
        $response -> assertJsonFragment(['code' => 400, 'status' => 'error', 'mensaje' => 'Error al editar']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }
  

    //                      TESTING DEL METODO ELIMINAR

    /**
     * Se realiza prueba donde se elimina correctamente un proveedor
     *
     * @return void
     */
    /** @test     */
    public function eliminacionCorrecta(){
        $data = [
            'rut' => 123
        ];
        $response = $this ->delete(route('proveedor.eliminar'),$data);
        $response -> assertJson(['code' => 200, 'status' => 'success', 'mensaje' => 'Se ha eliminado correctamente']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }



    /**
     * Se realiza prueba donde no se elimina correctamente un proveedor
     * debido a que el id era vacio
     *
     * @return void
     */
    /** @test      */   
    public function eliminacionSinRut(){
        $data = [
            'rut' => ''
        ];
        $response = $this ->delete(route('proveedor.eliminar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'Debe ingresar el rut del proveedor']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se elimina correctamente un proveedor
     * debido a que el id no tiene proveedor asociado
     *
     * @return void
     */
    /** @test      */
    public function eliminacionConIdSinProveedorAsociado(){
        $data = [
            'rut' => '15'
        ];
        $response = $this ->delete(route('proveedor.eliminar'),$data);
        $response -> assertJson(['code' => 200, 'status' => 'success', 'mensaje' => 'No se encontro el proveedor']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }

//                  TESTING METODO PRODUCTOS DE UN PROVEEDOR
    /**
     * Se realiza prueba donde se buscan productos asociados a un proveedor, el cual no tiene productos
     * asociados
     *                              
     * @return void
     */
    /** @test  */
    public function busquedaDeProductosDeUnProveedorCorrectaProveedorSinProductosAsociado(){
        $datacorrect = [
            'code' => 200,
            'status' => 'success',
            'mensaje' => 'No existen productos asociados al proveedor'
        ];
        $response = $this -> call('GET', '/proveedor/productosproveedor', ['rut' => 787777222] );
        $response -> assertJson(['code' => 200, 'status' => 'success', 'mensaje' => 'No existen productos asociados al proveedor']);
        $response -> assertJsonFragment($datacorrect);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se obtienen los productos asociados a un proveedor
     * debido a que no se ingreso el rut
     *                              
     * @return void
     */
    /** @test  */
    public function busquedaDeProductosDeUnProveedorIncorrectaRutVacio(){
        $data = [
            'rut' => ''
        ];
        $response = $this -> call('GET', '/proveedor/productosproveedor',$data );
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'No ha ingresado un rut']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }


    /**
     * Se realiza prueba donde no se obtienen los productos asociados a un proveedor
     * debido a que no se encuentra proveedor asociado al rut
     *                              
     * @return void
     */
    /** @test  */
    public function busquedaDeProductosDeUnProveedorIncorrectaRutSinProveedorAsociado(){
        $datacorrect = [
            'code' => 400,
            'status' => 'error',
            'mensaje' => 'No existe proveedor asociado a ese rut'
        ];
        $response = $this -> call('GET', '/proveedor/productosproveedor', ['rut' => 11] );
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'No existe proveedor asociado a ese rut']);
        $response -> assertJsonFragment($datacorrect);
        $response -> assertStatus(200);
        $response -> assertOk();
    }

}
