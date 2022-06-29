<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absent extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id', 'user_id', 'distributor_id',
        'clock_in', 'clock_out', 'address',
        'item_photo', 'distributor_photo'
    ];

    protected $hidden = [

    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'absents', 'length' => 5, 'prefix' => 'A', 'reset_on_prefix_change'=>true]);
        });
    }

    public function item()
    {
        return $this->hasMany(ItemTaken::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }
}
