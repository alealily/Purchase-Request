<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrDetail extends Model
{
    use HasFactory;
    
    protected $table = "pr_detail";
    protected $primaryKey = "id_pr_detail";
    protected $fillable = [
        'id_pr',
        'quotation_file',
        'quantity',
        'unit_price',
        'total_cost',
        'material_description',
        'uom',
        'currency_code',
    ];
    protected $guarded = ["id_pr_detail"];
    public $timestamps = false;

    public function purchaseRequest():BelongsTo
    {
        return $this->belongsTo(PurchaseRequest::class, 'id_pr');
    }

    public function supplier():BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }
}
