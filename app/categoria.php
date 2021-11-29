<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class categoria extends Model
{
    //
    protected $table = "categoria"; //definicion de nombre de base de datos
    public $timestamps = false; // para que no se cree de forma automatica delete at y create at

    public function producto(){
        return $this->hasMany('App\Producto','producto_id');
    }
}
