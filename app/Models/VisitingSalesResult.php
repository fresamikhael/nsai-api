<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitingSalesResult extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id', 'product_id', 'visit_id',
        'item_sold', 'total_sales'
    ];

    protected $hidden = [

    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function visit()
    {
        return $this->belongsTo(VisitOutlet::class);
    }
}
