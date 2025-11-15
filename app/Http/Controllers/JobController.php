<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobCompletion;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Display available jobs.
     */
    public function index()
    {
        $user = Auth::user();

        $jobs = Job::where('is_active', true)
            ->get()
            ->map(function ($job) use ($user) {
                $job->can_perform = $job->canUserPerform($user);
                $job->is_available = $job->isAvailableForUser($user);

                if (!$job->is_available) {
                    $lastCompletion = JobCompletion::where('user_id', $user->id)
                        ->where('job_id', $job->id)
                        ->orderBy('completed_at', 'desc')
                        ->first();

                    $job->next_available = $lastCompletion?->next_available_at;
                }

                return $job;
            });

        return view('jobs.index', compact('jobs'));
    }

    /**
     * Show job details.
     */
    public function show(Job $job)
    {
        $user = Auth::user();

        $canPerform = $job->canUserPerform($user);
        $isAvailable = $job->isAvailableForUser($user);

        $lastCompletion = JobCompletion::where('user_id', $user->id)
            ->where('job_id', $job->id)
            ->orderBy('completed_at', 'desc')
            ->first();

        $stats = [
            'total_completions' => JobCompletion::where('job_id', $job->id)->count(),
            'total_earned' => JobCompletion::where('job_id', $job->id)->sum('payment_received'),
            'avg_quality' => JobCompletion::where('job_id', $job->id)->avg('quality_score'),
        ];

        return view('jobs.show', compact('job', 'canPerform', 'isAvailable', 'lastCompletion', 'stats'));
    }

    /**
     * Start a job.
     */
    public function start(Request $request, Job $job)
    {
        $user = Auth::user();

        if (!$job->canUserPerform($user)) {
            return back()->with('error', 'Non puoi svolgere questo lavoro');
        }

        $completion = JobCompletion::start($user, $job);

        if (!$completion) {
            return back()->with('error', 'Impossibile iniziare il lavoro');
        }

        return redirect()->route('jobs.work', $completion->id)
            ->with('success', 'Lavoro iniziato!');
    }

    /**
     * Work on a job (minigame/activity).
     */
    public function work(JobCompletion $completion)
    {
        if ($completion->user_id !== Auth::id()) {
            abort(403);
        }

        if ($completion->completed_at) {
            return redirect()->route('jobs.show', $completion->job_id)
                ->with('info', 'Hai già completato questo lavoro');
        }

        return view('jobs.work', compact('completion'));
    }

    /**
     * Complete a job.
     */
    public function complete(Request $request, JobCompletion $completion)
    {
        if ($completion->user_id !== Auth::id()) {
            abort(403);
        }

        if ($completion->completed_at) {
            return redirect()->route('jobs.show', $completion->job_id)
                ->with('error', 'Lavoro già completato');
        }

        // Calculate quality based on user actions (placeholder)
        $qualityScore = $request->input('quality_score', rand(60, 100));

        $completion->complete($qualityScore);

        $user = Auth::user();
        $user->notify(
            'job_completed',
            'Lavoro Completato!',
            "Hai completato {$completion->job->name} e guadagnato {$completion->payment_received} Galleons!",
            '💰',
            '/economy/wallet'
        );

        return redirect()->route('jobs.index')
            ->with('success', "Lavoro completato! Hai guadagnato {$completion->payment_received} Galleons!");
    }

    /**
     * User's job history.
     */
    public function history()
    {
        $user = Auth::user();

        $completions = JobCompletion::where('user_id', $user->id)
            ->with('job')
            ->orderBy('completed_at', 'desc')
            ->paginate(20);

        $stats = JobCompletion::getUserStats($user);

        return view('jobs.history', compact('completions', 'stats'));
    }
}
