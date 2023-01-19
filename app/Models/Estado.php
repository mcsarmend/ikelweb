<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'cat_estados';

	protected $primary_key = 'id';

	public $timestamps = false;

	protected $fillable = [
		'id',
		'estado',
	];

	protected $guarded =[];
}
