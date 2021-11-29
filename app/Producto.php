<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = "producto"; 
    public $timestamps = false; 

    public function categoria(){
        return $this->belongsTo('App\categoria','categoria_id');
    }
    
    public function proveedor(){
        return $this->belongsTo('App\Proveedor','proveedor_id');
    }

    public function detalle_venta(){
        return $this->hasMany('App\DetalleVenta','detalle_venta_id');
    }
}       
?>