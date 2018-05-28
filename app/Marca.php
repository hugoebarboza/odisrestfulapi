<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $table = 'marcas';

    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        //'user_id', 'cliente_id', 'title', 'description', 'status',
        'title', 'description',
    ];


    public function user(){
    	return $this -> belongsTo('App\User', 'user_id');
    }
}
