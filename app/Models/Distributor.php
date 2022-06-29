<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Distributor extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id', 'name',
        'lat', 'long', 'address'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'distributors', 'length' => 5, 'prefix' => 'D', 'reset_on_prefix_change'=>true]);
        });
    }
}
