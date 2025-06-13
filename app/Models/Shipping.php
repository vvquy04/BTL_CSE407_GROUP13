<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'shipping_name',
        'shipping_email',
        'shipping_phone',
        'shipping_street',
        'shipping_ward',
        'shipping_district',
        'shipping_city',
        'shipping_note',
        'shipping_method',
        'shipping_fee',
        'estimated_delivery'
    ];
    protected $primaryKey = 'shipping_id';
    protected $table = 'tbl_shipping';
    
}
