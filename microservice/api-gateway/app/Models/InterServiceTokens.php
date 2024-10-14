<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterServiceTokens extends Model
{
    use HasFactory;

    protected $fillable = ['issuer_service_id', 'token', 'api_token_expires_at'];

    protected $casts = [
        'api_token_expires_at' => 'datetime',
    ];

    public function issuerServiceAccount()
    {
        return $this->belongsTo(ServiceAccount::class, 'issuer_service_id', 'service_id');
    }
}
