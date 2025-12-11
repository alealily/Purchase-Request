<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    use HasFactory;
    
    protected $table = "approval";
    protected $primaryKey = "id_approval";
    protected $fillable = [
        'id_pr',
        'id_user',
        'level',
        'approval_status',
        'approval_date',
        'remarks',
    ];
    protected $guarded = ['id_approval'];
    public $timestamps = true; // Enable for created_at and updated_at

    public function purchaseRequest():BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class, 'id_pr');
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class,'id_user');
    }    
}
