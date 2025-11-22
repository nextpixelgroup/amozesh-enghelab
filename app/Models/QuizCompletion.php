<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizCompletion extends Model
{
    protected $fillable = ['user_id', 'course_id', 'quiz_id', 'question_id', 'question_option_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(QuestionOption::class);
    }
}
