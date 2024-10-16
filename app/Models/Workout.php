<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Awobaz\Compoships\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workout extends Model
{
    use HasFactory, Compoships;

    protected $guarded = [];


    public function scopeCompleted()
    {
        return $this->update([
            'is_completed' => 1,
            'score' => 100,
            'date_get_score' => Carbon::now()->format("Y-m-d")
        ]);
    }


    public function showWorkoutResults(Workout $workout)
    {
        $workout->load('WorkOutQuiz'); // Memastikan semua relasi juga dimuat untuk akses lebih lanjut
        return view('livewire.activity.result', compact('workout'));
    }


    public function Sessionable(): BelongsTo
    {
        return $this->belongsTo(Sessionable::class);
    }

    public function Session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }

    public function WorkOutQuiz(): HasMany
    {
        return $this->hasMany(WorkoutQuizLog::class);
    }

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function Participant(): HasOne
    {
        return $this->hasOne(Participant::class);
    }

    //     public function calculateScore()
    // {
    //     // Ambil semua log kuis untuk workout ini
    //     $logs = $this->WorkOutQuiz;

    //     // Variabel untuk menghitung jumlah jawaban yang benar
    //     $correctCount = 0;
    //     $totalQuestions = $logs->count();

    //     // Iterasi setiap log dan hitung jawaban benar
    //     foreach ($logs as $log) {
    //         if ($log->score > 0) {
    //             $correctCount++;
    //         }
    //     }

    //     // Hitung persentase skor
    //     $scorePercentage = $totalQuestions > 0 ? ($correctCount / $totalQuestions) * 100 : 0;

    //     // Update skor di workout
    //     $this->update([
    //         'score' => round($scorePercentage),
    //         'is_completed' => 1,
    //         'date_get_score' => Carbon::now(),
    //     ]);



}
