<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'question_id',
        'answer',
        'answer_id',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order')->orderBy('id');
    }

    public function getQuestionForLocale(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        if ($locale === 'id' && ! empty($this->question_id)) {
            return $this->question_id;
        }

        return $this->question;
    }

    public function getAnswerForLocale(?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();

        if ($locale === 'id' && ! empty($this->answer_id)) {
            return $this->answer_id;
        }

        return $this->answer;
    }
}
