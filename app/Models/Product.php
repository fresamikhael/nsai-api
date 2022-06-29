<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id', 'distributor_id', 'name', 'unit', 'price'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'products', 'length' => 5, 'prefix' => 'P', 'reset_on_prefix_change'=>true]);
        });
    }
}
