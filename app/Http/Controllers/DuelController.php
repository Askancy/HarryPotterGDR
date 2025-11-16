<?php

namespace App\Http\Controllers;

use App\Models\Duel;
use App\Models\DuelStatistic;
use App\Models\Spell;
use App\Models\User;
use App\Services\DuelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DuelController extends Controller
{
    protected $duelService;

    public function __construct(DuelService $duelService)
    {
        $this->middleware('auth');
        $this->duelService = $duelService;
    }

    /**
     * Display duels index page.
     */
    public function index()
    {
        $user = Auth::user();

        // Get active duel
        $activeDuel = $user->activeDuel();

        // Get pending invitations
        $pendingInvitations = $user->pendingDuelInvitations()->with(['challenger', 'location'])->get();

        // Get recent duels
        $recentDuels = Duel::forUser($user->id)
            ->whereIn('status', ['completed', 'fled'])
            ->with(['challenger', 'opponent', 'winner', 'location'])
            ->orderBy('ended_at', 'desc')
            ->limit(10)
            ->get();

        // Get user statistics
        $statistics = $user->duelStatistic;

        // Get top ranked players
        $topRanked = DuelStatistic::topRanked(10)->with('user')->get();

        return view('duels.index', compact(
            'activeDuel',
            'pendingInvitations',
            'recentDuels',
            'statistics',
            'topRanked'
        ));
    }

    /**
     * Show duel creation form.
     */
    public function create()
    {
        $user = Auth::user();

        // Check if user can duel
        if (!$user->canDuel()) {
            return redirect()->route('duels.index')
                ->with('error', 'Devi avere almeno il 50% di salute e mana per duellare!');
        }

        // Check if user is already in a duel
        if ($user->isInDuel()) {
            return redirect()->route('duels.index')
                ->with('error', 'Sei già in un duello attivo!');
        }

        // Get potential opponents (users at current location, excluding self)
        $opponents = User::where('current_location_id', $user->current_location_id)
            ->where('id', '!=', $user->id)
            ->where('group', 0) // Only regular users, not admins
            ->get();

        return view('duels.create', compact('opponents'));
    }

    /**
     * Store a new duel challenge.
     */
    public function store(Request $request)
    {
        $request->validate([
            'opponent_id' => 'required|exists:users,id',
            'is_practice' => 'boolean',
        ]);

        $user = Auth::user();
        $opponent = User::findOrFail($request->opponent_id);

        // Validation
        if ($user->id === $opponent->id) {
            return redirect()->back()->with('error', 'Non puoi sfidare te stesso!');
        }

        if (!$user->canDuel()) {
            return redirect()->back()->with('error', 'Devi avere almeno il 50% di salute e mana per duellare!');
        }

        if ($user->isInDuel()) {
            return redirect()->back()->with('error', 'Sei già in un duello attivo!');
        }

        if ($opponent->isInDuel()) {
            return redirect()->back()->with('error', 'Il giocatore è già in un duello!');
        }

        // Check level difference (max 5 levels for ranked duels)
        $isPractice = $request->boolean('is_practice');
        if (!$isPractice && abs($user->level - $opponent->level) > 5) {
            return redirect()->back()
                ->with('error', 'Puoi sfidare solo giocatori con massimo 5 livelli di differenza! Usa un duello di allenamento invece.');
        }

        // Create duel
        $duel = $this->duelService->createDuel($user, $opponent, $isPractice);

        return redirect()->route('duels.show', $duel->id)
            ->with('success', 'Sfida inviata! In attesa che ' . $opponent->name . ' accetti.');
    }

    /**
     * Show a specific duel.
     */
    public function show($id)
    {
        $duel = Duel::with(['challenger', 'opponent', 'winner', 'location', 'turns.player', 'turns.spell'])
            ->findOrFail($id);

        $user = Auth::user();

        // Check if user is part of this duel
        if ($duel->challenger_id !== $user->id && $duel->opponent_id !== $user->id) {
            return redirect()->route('duels.index')
                ->with('error', 'Non sei autorizzato a visualizzare questo duello.');
        }

        // Get user's available spells
        $userSpells = $user->userSkills()
            ->join('skills', 'user_skills.skill_id', '=', 'skills.id')
            ->where('skills.category', 'spell')
            ->with('skill')
            ->get();

        // Get spell list for combat
        $spells = Spell::whereIn('type', ['attack', 'defense', 'healing'])
            ->where('required_level', '<=', $user->level)
            ->get();

        return view('duels.show', compact('duel', 'user', 'spells'));
    }

    /**
     * Accept a duel challenge.
     */
    public function accept($id)
    {
        $duel = Duel::findOrFail($id);
        $user = Auth::user();

        // Validate user is the opponent
        if ($duel->opponent_id !== $user->id) {
            return redirect()->back()->with('error', 'Non sei autorizzato ad accettare questo duello.');
        }

        // Check if user can duel
        if (!$user->canDuel()) {
            return redirect()->back()->with('error', 'Devi avere almeno il 50% di salute e mana per duellare!');
        }

        // Accept duel
        if ($this->duelService->acceptDuel($duel)) {
            return redirect()->route('duels.show', $duel->id)
                ->with('success', 'Duello accettato! Che il migliore vinca!');
        }

        return redirect()->back()->with('error', 'Impossibile accettare il duello.');
    }

    /**
     * Decline a duel challenge.
     */
    public function decline($id)
    {
        $duel = Duel::findOrFail($id);
        $user = Auth::user();

        // Validate user is the opponent
        if ($duel->opponent_id !== $user->id) {
            return redirect()->back()->with('error', 'Non sei autorizzato a rifiutare questo duello.');
        }

        // Decline duel
        if ($this->duelService->declineDuel($duel)) {
            return redirect()->route('duels.index')
                ->with('success', 'Duello rifiutato.');
        }

        return redirect()->back()->with('error', 'Impossibile rifiutare il duello.');
    }

    /**
     * Cast a spell in a duel.
     */
    public function castSpell(Request $request, $id)
    {
        $request->validate([
            'spell_id' => 'required|exists:spells,id',
        ]);

        $duel = Duel::findOrFail($id);
        $user = Auth::user();
        $spell = Spell::findOrFail($request->spell_id);

        // Validate
        if (!$duel->isPlayerTurn($user)) {
            return redirect()->back()->with('error', 'Non è il tuo turno!');
        }

        if ($duel->status !== 'active') {
            return redirect()->back()->with('error', 'Il duello non è attivo!');
        }

        // Cast spell
        $result = $this->duelService->castSpell($duel, $user, $spell);

        if ($result['success']) {
            $message = 'Hai lanciato ' . $spell->name . '!';
            if ($result['is_critical']) {
                $message .= ' COLPO CRITICO!';
            }
            if ($result['is_dodged']) {
                $message .= ' Ma l\'avversario ha schivato!';
            }
            if ($result['is_blocked']) {
                $message .= ' Ma l\'avversario ha bloccato parte del danno!';
            }

            return redirect()->route('duels.show', $duel->id)
                ->with('success', $message);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    /**
     * Defend in a duel.
     */
    public function defend($id)
    {
        $duel = Duel::findOrFail($id);
        $user = Auth::user();

        // Validate
        if (!$duel->isPlayerTurn($user)) {
            return redirect()->back()->with('error', 'Non è il tuo turno!');
        }

        if ($duel->status !== 'active') {
            return redirect()->back()->with('error', 'Il duello non è attivo!');
        }

        // Defend
        $result = $this->duelService->defend($duel, $user);

        if ($result['success']) {
            return redirect()->route('duels.show', $duel->id)
                ->with('success', 'Ti sei messo in difesa e hai recuperato ' . $result['mana_restored'] . ' mana!');
        }

        return redirect()->back()->with('error', 'Impossibile difendersi.');
    }

    /**
     * Flee from a duel.
     */
    public function flee($id)
    {
        $duel = Duel::findOrFail($id);
        $user = Auth::user();

        // Validate
        if ($duel->challenger_id !== $user->id && $duel->opponent_id !== $user->id) {
            return redirect()->back()->with('error', 'Non sei parte di questo duello!');
        }

        if ($duel->status !== 'active') {
            return redirect()->back()->with('error', 'Il duello non è attivo!');
        }

        // Flee
        if ($this->duelService->flee($duel, $user)) {
            return redirect()->route('duels.index')
                ->with('warning', 'Sei fuggito dal duello. Hai perso 20 punti ranking.');
        }

        return redirect()->back()->with('error', 'Impossibile fuggire.');
    }

    /**
     * Show leaderboard.
     */
    public function leaderboard()
    {
        $topPlayers = DuelStatistic::with('user')
            ->orderByDesc('ranking_points')
            ->limit(50)
            ->get();

        return view('duels.leaderboard', compact('topPlayers'));
    }

    /**
     * Show user duel statistics.
     */
    public function statistics()
    {
        $user = Auth::user();
        $statistics = $user->getOrCreateDuelStatistics();

        // Get duel history
        $duelHistory = Duel::forUser($user->id)
            ->whereIn('status', ['completed', 'fled'])
            ->with(['challenger', 'opponent', 'winner'])
            ->orderBy('ended_at', 'desc')
            ->paginate(20);

        return view('duels.statistics', compact('statistics', 'duelHistory'));
    }
}
