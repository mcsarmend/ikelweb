<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';

	protected $primary_key = 'id';

	public $timestamps = false;

	protected $fillable = [
		'id',
        'internal_id',
        'client_name',
        'client_number',
        'order_description',
        'cost',
        'lat_destiny',
        'lon_destiny',
	];

	protected $guarded =[];
}
