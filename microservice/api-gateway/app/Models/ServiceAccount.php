<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAccount extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'api_token', 'api_token_expires_at'];
}
