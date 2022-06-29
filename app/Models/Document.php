<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $fillable = [
        'id', 'user_id', 'name', 'file'
    ];

    protected $hidden = [

    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->id = IdGenerator::generate(['table' => 'documents', 'length' => 6, 'prefix' => 'DOC', 'reset_on_prefix_change'=>true]);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
