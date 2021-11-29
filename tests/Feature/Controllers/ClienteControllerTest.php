<?php

namespace Tests\Feature\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use \Illuminate\Support\Facades\DB;
use App\Cliente;

class ExampleTest extends TestCase
{
    
                                /**Test de metodo Crear un Cliente */
    /** @test*/
    public function ElClienteSeCreaCorrectamente()
    {
        $data = [
            'rut' => 133546681,
            'nombre' => 'rosa',
            'apellido' => 'Lex',
            'email' => 'prueba232@gmail.com',
            'telefono' => '567098098',
            'direccion' => 'nardos'
        ];
        $response = $this ->post(route('cliente.crear'),$data);
        $cliente=Cliente::firstwhere('rut',133546681);
        
        $datacorrect = [
            'rut' => 133546681,
            'nombre' => 'rosa',
            'apellido' => 'Lex',
            'email' => 'prueba232@gmail.com',
            'telefono' => '567098098',
            'direccion' => 'nardos',
            'id' => $cliente -> id
        ];
        $response -> assertJson(['code' => 200, 'status' => 'success','mensaje' => 'Se ha creado correctamente', 'Cliente' => $datacorrect]);
        $response -> assertJsonFragment($datacorrect);
        $response -> assertStatus(200);
        $response -> assertOk();

    }
     
    /** @test*/
    public function NoSeIngresanTodosLosParametrosParaCrearElCliente()
    {
        $data = [
            'rut' => 152345431,
            'nombre'=>'Juan',
            'email' => 'jverita@email.cl',
            'telefono' => '987766554',
            'direccion' => 'rio viejo,34'
        ];
        $response = $this ->post(route('cliente.crear'),$data);
        $response -> assertJsonFragment(['code' => 400, 'status' => 'error', 'mensaje' => 'No ha ingresado todos los parametros']);
        $response -> assertStatus(200);
        $response -> assertOk();
    
    }
    
    /** @test*/
    public function ElClienteNoSePuedeCrearPorqueYaExiste()
    {
        $data = [
            'rut' => 222222222,
            'nombre' => 'Jorge',
            'apellido' => 'Diaz',
            'email' => 'jorged@gmail.com',
            'telefono' => '54542345',
            'direccion' => 'gamero, 445'
        ];
        $response = $this ->post(route('cliente.crear'),$data);
        $response -> assertJsonFragment(['code' => 400, 'status' => 'error', 'mensaje' => 'Ya existe el cliente']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }
    
    /*el testing ElClienteNoSePuedeCrearPorqueYaExiste se crea para 
     * probar si se crea un cliente cuando este ya esta en la base de datos lo cual 
     * nos devuelve que ya existe en la base de datos */
    
    /** @test*/
    public function ErrorAlCrearElClienteRequestSinDatos()
    {
        $data = [  

         ];
        $response = $this ->post(route('cliente.crear'),$data);
        $response -> assertJsonFragment(['code' => 400, 'status' => 'error', 'mensaje' => 'Error al crear el cliente']);
        $response -> assertStatus(200);
        $response -> assertOk();
    
    }
     
    
    
                        //Test de Metodo Editar Clientes 
    /** @test */
    public function CuandoNoSeIngresanTodosLosParametrosParaEditarCliente()
    {
        $data = [
            'id' => 4,
            'nombre' => 'Juan jose',
            'apellido' => 'Ortiz Herrera',
            'email' => 'usuario@gmail.com',
            'telefono' => '923232524',
            'direccion' => 'Collin/0909'
        ];
        $response = $this ->put(route('cliente.editar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'No ha ingresado todos los parametros']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }
    
    /** @test*/ 
    public function NoSeEncuentraElClienteAEditar()
    {
        $data = [
            'id' => 17,
            'rut' => 133820043,
            'nombre' => 'edita',
            'apellido' => 'mariela',
            'email' => 'editamariela@gmail.com',
            'telefono' => '8150731',
            'direccion' => 'el Piedral/12'
        ];
        $response = $this ->put(route('cliente.editar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'No se encuentra el cliente']);
        $response -> assertStatus(200);
        $response -> assertOk();
    }
    
        
  /** @test*/
    public function ElClienteSeHaEditadoCorrectamente()
    {
        $data = [
            'id' => 55,
            'rut' => 555555555,
            'nombre' => 'Lucas',
            'apellido' => 'Soto',
            'email' => 'luquitass@gmail.com',
            'telefono' => '68745465',
            'direccion' => 'arturo prat, 223'
        ];
        $response = $this ->put(route('cliente.editar'),$data);
        $response -> assertJson(['code' => 200, 'status' => 'success','mensaje' => 'Se ha editado correctamente', 'Cliente' => $data]);
        $response -> assertJsonFragment($data);
        $response -> assertStatus(200);
        $response -> assertOk();
    }
     
                         /*Test de metodo Eliminar clientes*/
    /** @test*/ 
     public function SeNecesitaUnIdParaEliminarUnCliente()
     {
        $data = [
            'id' => '98'
        ];
        $response = $this ->delete(route('cliente.eliminar'),$data);
        $response -> assertJson(['code' => 200, 'status' => 'success', 'mensaje' => 'No se encontro el ID']);
        $response -> assertStatus(200);
        $response -> assertOk();
     }
     
    /** @test*/
     public function ElClienteSeEliminaCorrectamente()
     {
        $data = [
            'id' => 88
        ];
        $response = $this ->delete(route('cliente.eliminar'),$data);
        $response -> assertJson(['code' => 200, 'status' => 'success', 'mensaje' => 'Se ha eliminado correctamente']);
        $response -> assertStatus(200);
        $response -> assertOk();
     }
      
    /** @test*/ 
     public function NoSeEncontroElIdParaEliminarElCliente()
     {
        $data = [
            'id' => ''
        ];
        $response = $this ->delete(route('cliente.eliminar'),$data);
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'Debe ingresar un ID del cliente para eliminar']);
        $response -> assertStatus(200);
        $response -> assertOk();
     }
     
                    /*Metodo BuscarClientePorRut*/
                    
    /** @test*/
     public function SeDebeIngresarUnRutParaBuscarClientePorRut()
     {
        $data = [
            'rut' => ''
        ];
        $response = $this -> get(route('cliente.buscarclienteporrut'),$data);
        $response -> assertJson([ 'code' =>400,'status' => 'error','mensaje' => 'Ingrese un rut porfavor' ]);
        $response -> assertStatus(200);
        $response -> assertOk();  
     }
      
                        
     /** @test*/ 
     public function ErrorNoSeEncuentraRutAsociadoParaBuscarClientePorRut()
     {
        
        $response = $this -> call('GET', '/Cliente/buscarClientePorRut',['rut' => 133820049]);
        $response -> assertJson(['code' => 400, 'status' => 'error','mensaje' => 'No existe cliente asociado a ese rut']);
        $response -> assertStatus(200);
        $response -> assertOk();
     }
    

     
                        /*Metodo ventasDeUnCliente*/
     /** @test*/
     public function ErrorNoExisteClienteAsociadoAEseRut()
     {
        $datacorrect = [
            'code' => 400,
            'status' => 'error',
            'mensaje' => 'No existe cliente asociado a ese rut'
        ];
        $response = $this -> call('GET', '/Cliente/ventasDeUnCliente', ['rut' => 111233456] );
        $response -> assertJson(['code' => 400, 'status' => 'error', 'mensaje' => 'No existe cliente asociado a ese rut']);
        $response -> assertJsonFragment($datacorrect);
        $response -> assertStatus(200);
        $response -> assertOk();

    }
     
    
   
}
