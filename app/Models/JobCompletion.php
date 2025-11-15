<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JobCompletion extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_id',
        'payment_received',
        'quality_score',
        'started_at',
        'completed_at',
        'next_available_at',
    ];

    protected $casts = [
        'payment_received' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'next_available_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relazione con l'utente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relazione con il lavoro
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Inizia un lavoro
     */
    public static function start(User $user, Job $job): ?self
    {
        if (!$job->canUserPerform($user)) {
            return null;
        }

        return static::create([
            'user_id' => $user->id,
            'job_id' => $job->id,
            'started_at' => now(),
        ]);
    }

    /**
     * Completa un lavoro
     */
    public function complete(?int $qualityScore = null): void
    {
        $qualityScore = $qualityScore ?? rand(60, 100);

        $payment = $this->job->calculatePayment($qualityScore);

        // Aggiungi denaro all'utente
        $wallet = Wallet::getOrCreateForUser($this->user);
        $wallet->addMoney($payment);

        // Registra transazione
        Transaction::log(
            $this->user,
            'job_payment',
            $payment,
            "Pagamento per: {$this->job->name}",
            reference: $this->job
        );

        // Calcola prossima disponibilità
        $nextAvailable = now()->addHours($this->job->cooldown_hours);

        $this->update([
            'payment_received' => $payment,
            'quality_score' => $qualityScore,
            'completed_at' => now(),
            'next_available_at' => $nextAvailable,
        ]);

        // Possibile reward exp/skill
        $this->user->addExperience($payment * 2); // 2 exp per galleon
    }

    /**
     * Ottieni statistiche lavori dell'utente
     */
    public static function getUserStats(User $user): array
    {
        $completions = static::where('user_id', $user->id)->get();

        return [
            'total_jobs' => $completions->count(),
            'total_earned' => $completions->sum('payment_received'),
            'average_quality' => $completions->avg('quality_score'),
            'favorite_job' => static::where('user_id', $user->id)
                ->selectRaw('job_id, COUNT(*) as count')
                ->groupBy('job_id')
                ->orderByDesc('count')
                ->first()?->job,
        ];
    }
}
