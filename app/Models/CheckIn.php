<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CheckIn extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id', 'user_id',
        'clock_in', 'clock_out'
    ];

    protected $hidden = [

    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'check_ins', 'length' => 5, 'prefix' => 'C', 'reset_on_prefix_change'=>true]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
