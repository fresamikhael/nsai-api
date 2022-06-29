<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Outlet extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id', 'name', 'phone',
        'photo', 'lat', 'long', 'address'
    ];

    protected $hidden = [

    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'outlets', 'length' => 5, 'prefix' => 'O', 'reset_on_prefix_change'=>true]);
        });
    }
}
