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
        return $task->Render($participant, $sessionable);
    }


    
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */

    public function completedAndNext(Workout $workout)
    {
        if ($workout->is_completed == 0)
            $workout->workoutScoreUpdate(); // Menghitung skor dan update data workout

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

    public function workout(Request $request)
    {

        $request->validate([
            'question_id' => 'required|int',
            'workout_id' => 'required|int'
        ]);


        $question = Question::findorfail($request->question_id);
        $workout = Workout::findorfail($request->workout_id);

        $result =  QuestionFactory::Build($question->questionType)
            ->workoutChecker($question, $workout, $request);

        return response()->json($result);
    }
    

    // ===================================================================








}
