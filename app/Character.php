<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
	protected $fillable = ['name', 'descriptions', 'power'];
	
	public function users() {
		return $this->belongsToMany('App\User');
	}
}
