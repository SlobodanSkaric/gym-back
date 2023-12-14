<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingProgram extends Model
{
    use HasFactory;

    protected $fillable = ["program_name", "trening_weight"];

    public function users(){
        return $this->belongsTo(User::class);
    }
}
