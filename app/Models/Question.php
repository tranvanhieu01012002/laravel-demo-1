<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        "content",
        "set_question_id",
        "image"
    ];

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function setQuestion(): BelongsTo
    {
        return $this->belongsTo(SetQuestion::class);
    }

    public function replicateRow(int $setQuestionId)
    {
        $clone = $this->replicate();
        $clone->set_question_id = $setQuestionId;
        $clone->push();

        foreach ($this->answers as $answer) {
            $clone->answers()->create($answer->toArray());
        }

        $clone->save();
    }
}
