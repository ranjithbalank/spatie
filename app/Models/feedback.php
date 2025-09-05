<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'feedback_text',
        'areas_of_improvement',
    ];

    /**
     * Get the user that provided the feedback.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
