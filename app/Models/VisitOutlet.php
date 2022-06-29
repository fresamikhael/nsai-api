<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VisitOutlet extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id', 'user_id', 'outlet_id',
        'clock_in', 'clock_out', 'address',
        'item_photo', 'outlet_photo', 'other_photo'
    ];

    protected $hidden = [

    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'visit_outlets', 'length' => 6, 'prefix' => 'VO', 'reset_on_prefix_change'=>true]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class);
    }
}
