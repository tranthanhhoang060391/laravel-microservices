<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ServiceAccount extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name', 'service_id', 'service_secret', 'api_token', 'api_token_expires_at'];
}
