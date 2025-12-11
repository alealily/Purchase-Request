<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PurchaseRequest extends Model
{
    use HasFactory;
    protected $table = "pr";
    protected $primaryKey = "id_pr";
    protected $fillable = [
        'id_user',
        'id_supplier',
        'pr_number',
        'status',
    ];
    protected $guarded = ['id_pr'];
    
    // Disable updated_at since table doesn't have this column
    const UPDATED_AT = null;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class,'id_supplier');
    }

    public function prDetails(): HasOne
    {
        return $this->hasOne(PrDetail::class,'id_pr');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class,'id_pr');
    }
}
