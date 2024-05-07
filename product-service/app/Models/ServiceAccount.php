<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ServiceAccount extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['name', 'api_token', 'api_token_expires_at'];
}
