<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Signature extends Model
{
    use HasFactory;
    protected $table = "signature";
    protected $primaryKey = "id_signature";
    protected $fillable = [
        'id_user',
        'file_path',
    ];
    protected $guarded = ['id_signature'];

    public $timestamps = false;

    /**
     * Get the user that owns this signature
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
