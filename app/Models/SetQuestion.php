<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    public function replicateRow()
    {
        $clone = $this->replicate();
        $clone->user_id = Auth::id();
        $clone->push();

        foreach ($this->questions as $question) {
            $question->replicateRow($clone->id);
        }

        $clone->save();
    }
}
