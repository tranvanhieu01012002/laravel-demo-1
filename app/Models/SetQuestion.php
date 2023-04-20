<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetQuestion extends Model
{
    use HasFactory;

    protected $hidden = [
        "created_at",
        "updated_at"
    ];

    protected $fillable = [
        "user_id",
        "name"
    ];

    public function questions(){
        return $this->hasMany(Question::class);
    }
    
}