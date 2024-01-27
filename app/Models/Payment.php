<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Payment extends Model
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = "payment";

    protected $fillable = ["count", "user_id","payment_at"];
}
