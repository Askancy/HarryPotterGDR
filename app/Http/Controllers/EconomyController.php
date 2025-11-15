<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EconomyController extends Controller
{
    /**
     * Display user's wallet.
     */
    public function wallet()
    {
        $user = Auth::user();
        $wallet = Wallet::getOrCreateForUser($user);

        $recentTransactions = Transaction::getRecentForUser($user, 20);

        $stats = [
            'total_earned' => $wallet->total_earned,
            'total_spent' => $wallet->total_spent,
            'balance' => $wallet->getTotalInGalleons(),
        ];

        return view('economy.wallet', compact('wallet', 'recentTransactions', 'stats'));
    }

    /**
     * Display transaction history.
     */
    public function transactions()
    {
        $user = Auth::user();

        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('economy.transactions', compact('transactions'));
    }

    /**
     * Transfer money to another user.
     */
    public function transfer(Request $request)
    {
        $request->validate([
            'recipient_username' => 'required|exists:users,username',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $sender = Auth::user();
        $recipient = User::where('username', $request->recipient_username)->first();

        if ($recipient->id === $sender->id) {
            return back()->with('error', 'Non puoi trasferire denaro a te stesso');
        }

        $amount = $request->amount;
        $description = $request->description ?? 'Trasferimento denaro';

        // Check balance
        $wallet = Wallet::getOrCreateForUser($sender);
        if (!$wallet->hasEnoughMoney($amount)) {
            return back()->with('error', 'Fondi insufficienti');
        }

        // Process transfer
        $wallet->subtractMoney($amount);
        $recipientWallet = Wallet::getOrCreateForUser($recipient);
        $recipientWallet->addMoney($amount);

        // Log transactions
        Transaction::log(
            $sender,
            'transfer_sent',
            -$amount,
            "Trasferimento a {$recipient->username}: {$description}",
            relatedUser: $recipient
        );

        Transaction::log(
            $recipient,
            'transfer_received',
            $amount,
            "Trasferimento da {$sender->username}: {$description}",
            relatedUser: $sender
        );

        // Notify recipient
        $recipient->notify(
            'money_received',
            'Denaro Ricevuto',
            "{$sender->username} ti ha inviato {$amount} Galleons: {$description}",
            '💰',
            '/economy/wallet'
        );

        return back()->with('success', "Trasferiti {$amount} Galleons a {$recipient->username}");
    }

    /**
     * Leaderboard - richest users.
     */
    public function leaderboard()
    {
        $topUsers = User::select('users.*')
            ->join('wallets', 'users.id', '=', 'wallets.user_id')
            ->orderBy('wallets.galleons', 'desc')
            ->limit(100)
            ->get()
            ->map(function ($user) {
                $user->total_galleons = $user->wallet?->getTotalInGalleons() ?? 0;
                return $user;
            });

        return view('economy.leaderboard', compact('topUsers'));
    }

    /**
     * Economy stats.
     */
    public function stats()
    {
        $totalMoney = Wallet::sum('galleons');
        $totalTransactions = Transaction::count();
        $totalEarned = Wallet::sum('total_earned');
        $totalSpent = Wallet::sum('total_spent');

        $topEarners = Transaction::selectRaw('user_id, SUM(amount) as total')
            ->earnings()
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(10)
            ->with('user')
            ->get();

        $topSpenders = Transaction::selectRaw('user_id, SUM(amount) as total')
            ->expenses()
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(10)
            ->with('user')
            ->get();

        return view('economy.stats', compact(
            'totalMoney',
            'totalTransactions',
            'totalEarned',
            'totalSpent',
            'topEarners',
            'topSpenders'
        ));
    }
}
