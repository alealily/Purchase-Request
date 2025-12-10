<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory; 

    protected $table = "supplier";
    protected $primaryKey = 'id_supplier';
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
    ];
    protected $guarded = ['id_supplier'];

    public $timestamps = false;

    public function purchaseRequest(): HasMany
    {
        return $this->hasMany(PurchaseRequest::class, 'id_supplier');
    }
}
