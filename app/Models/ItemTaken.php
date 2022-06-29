<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemTaken extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id', 'absent_id', 'product_id',
        'item_taken', 'total_item', 'sales_result'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'item_takens', 'length' => 5, 'prefix' => 'A', 'reset_on_prefix_change'=>true]);
        });
    }

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function absent()
    {
        return $this->belongsTo(Absent::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
