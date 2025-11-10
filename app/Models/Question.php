<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{

    protected $fillable = [
        'quiz_id', 'question_text', 'type', 'order'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function correctOptions()
    {
        return $this->options()->where('is_correct', true);
    }
}
