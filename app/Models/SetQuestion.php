<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetQuestion extends Model
{
    use HasFactory;

    protected $hidden = [
        "created_at",
        // "updated_at"
    ];

    protected $fillable = [
        "user_id",
        "name",
        "status"
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function favorite()
    {
        return $this->hasOne(Favorite::class);
    }

    public function username()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
