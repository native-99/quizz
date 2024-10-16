<?php

namespace App\Http\Controllers\learn;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Question;
use App\Models\Sessionable;
use App\Models\Workout;
use App\Utility\Modules\Tasks\TaskFactory;
use App\Utility\Question\QuestionFactory;
use App\Utility\Workout\WorkoutService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session; // Tambahkan ini
use AppUtility\Question\Traits\WorkoutViewRender;

class WorkoutController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function prepared(Participant $participant, Sessionable $sessionable)
    {

        WorkoutService::WorkOutSyncForThisExcersice($participant, $sessionable, Auth::user());

        return redirect(route('taskLearner', ['participant' => $participant, 'sessionable' => $sessionable]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function task(Participant $participant, Sessionable $sessionable)
    {
        $className = $sessionable->sessionable_type;

        $task = TaskFactory::Build($className);
        $task->set_user(Auth::user());

        // dd($task);
        return $task->Render($participant, $sessionable); // fungsi render dari mana ?
    }



    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */

    public function completedAndNext(Workout $workout)
    {
        if ($workout->is_completed == 0) {
            $workout->workoutScoreUpdate(); // Menghitung skor dan update data workout fungsi workoutScoreUpdate ko ga ada di model
            // dd($workout->workoutScoreUpdate());
        }

        // dd('test');
        $workout->Completed();

        return redirect();
    }

    // public function completedAndNext(Workout $workout)
    // {
    //     // Pastikan workout belum selesai, lalu hitung skor dan tandai sebagai selesai
    //     if (!$workout->is_completed) {
    //         $workout->workoutScoreUpdate(); // Menghitung skor dan update data workout
    //     }

    //     // Redirect ke halaman sebelumnya dengan pesan berhasil
    //     return redirect()->back()->with('success', __('Workout completed successfully.'));
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */


    // ===================================================================



    // public function workout(Request $request)
    // {
    //     $request->validate([
    //         'question_id' => 'required|int',
    //         'workout_id' => 'required|int',
    //     ]);

    //     try {
    //         $question = Question::findOrFail($request->question_id);
    //         $workout = Workout::findOrFail($request->workout_id);

    //         // Assuming answer is being posted as 'answer'
    //         $selectedAnswer = $request->input('answer-' . $question->id);

    //         // Save answer logic here
    //         // UserAnswer::create(...); (save to DB, then set session data)

    //         // Set selected answer in session for persistence
    //         session()->put("workout_answers.{$question->id}", $selectedAnswer);

    //         return redirect()->back()->with('success', __('Jawaban berhasil disimpan'));
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', __('Terjadi kesalahan saat menyimpan jawaban.'));
    //     }
    // }


    public function workout(Request $request)
    {
        $request->validate([
            'question_id' => 'required|integer|exists:questions,id',
            'workout_id' => 'required|integer|exists:workouts,id',
            'answer-' . $request->question_id => 'required',
        ]);

        try {
            $question = Question::findOrFail($request->question_id);
            $workout = Workout::findOrFail($request->workout_id);

            $selectedAnswer = $request->input('answer-' . $question->id);

            // Save answer to the database
            // UserAnswer::updateOrCreate(...);

            // Update session
            session()->put("workout_answers.{$question->id}", $selectedAnswer);

            // Debug: Dump session data
            // dd(session('workout_answers'));

            return redirect()->back()->with('success', __('Jawaban berhasil disimpan'));
        } catch (\Exception $e) {
            \Log::error('Error saving workout answer: ' . $e->getMessage());

            return redirect()->back()->with('error', __('Terjadi kesalahan saat menyimpan jawaban.'));
        }
    }




    // ===================================================================








}
