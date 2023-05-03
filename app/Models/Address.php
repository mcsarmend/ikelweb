<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $table = 'address';

	protected $primary_key = 'idaddress';

	public $timestamps = false;

	protected $fillable = [
		'idaddress',
        'iduser',
        'idmunicipio',
        'idestado',
        'cp',
        'colonia',
        'calle',
        'numero',
	];

	protected $guarded =[];
}
