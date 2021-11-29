<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model{
    protected $table = "proveedor";
    public $timestamps = false;

    public function producto(){
        return $this->hasMany('App\Producto','producto_id');
    }
}

?>