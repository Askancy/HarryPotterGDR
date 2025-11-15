<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HouseApiController extends Controller
{
    /**
     * Get all messages for a house
     */
    public function getMessages(Request $request)
    {
        $houseId = $request->input('house_id');

        if (!$houseId || Auth::user()->team != $houseId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = DB::table('house_messages')
            ->join('users', 'house_messages.user_id', '=', 'users.id')
            ->where('house_messages.house_id', $houseId)
            ->select(
                'house_messages.*',
                'users.name as user_name',
                'users.avatar as user_avatar'
            )
            ->orderBy('house_messages.created_at', 'asc')
            ->limit(100)
            ->get();

        return response()->json([
            'messages' => $messages->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'user_id' => $msg->user_id,
                    'user_name' => $msg->user_name,
                    'user_avatar' => $msg->user_avatar ?: '/images/default-avatar.png',
                    'message' => $msg->message,
                    'message_type' => $msg->message_type,
                    'is_pinned' => $msg->is_pinned,
                    'created_at' => Carbon::parse($msg->created_at)->diffForHumans()
                ];
            })
        ]);
    }

    /**
     * Get new messages after a specific message ID
     */
    public function getNewMessages(Request $request)
    {
        $houseId = $request->input('house_id');
        $afterId = $request->input('after_id', 0);

        if (!$houseId || Auth::user()->team != $houseId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = DB::table('house_messages')
            ->join('users', 'house_messages.user_id', '=', 'users.id')
            ->where('house_messages.house_id', $houseId)
            ->where('house_messages.id', '>', $afterId)
            ->select(
                'house_messages.*',
                'users.name as user_name',
                'users.avatar as user_avatar'
            )
            ->orderBy('house_messages.created_at', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages->map(function($msg) {
                return [
                    'id' => $msg->id,
                    'user_id' => $msg->user_id,
                    'user_name' => $msg->user_name,
                    'user_avatar' => $msg->user_avatar ?: '/images/default-avatar.png',
                    'message' => $msg->message,
                    'message_type' => $msg->message_type,
                    'is_pinned' => $msg->is_pinned,
                    'created_at' => Carbon::parse($msg->created_at)->diffForHumans()
                ];
            })
        ]);
    }

    /**
     * Send a new message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'house_id' => 'required|integer',
            'message' => 'required|string|max:500'
        ]);

        $houseId = $request->input('house_id');

        if (Auth::user()->team != $houseId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messageId = DB::table('house_messages')->insertGetId([
            'user_id' => Auth::id(),
            'house_id' => $houseId,
            'message' => $request->input('message'),
            'message_type' => 'text',
            'is_pinned' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $message = DB::table('house_messages')
            ->join('users', 'house_messages.user_id', '=', 'users.id')
            ->where('house_messages.id', $messageId)
            ->select(
                'house_messages.*',
                'users.name as user_name',
                'users.avatar as user_avatar'
            )
            ->first();

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'user_id' => $message->user_id,
                'user_name' => $message->user_name,
                'user_avatar' => $message->user_avatar ?: '/images/default-avatar.png',
                'message' => $message->message,
                'message_type' => $message->message_type,
                'is_pinned' => $message->is_pinned,
                'created_at' => Carbon::parse($message->created_at)->diffForHumans()
            ]
        ]);
    }

    /**
     * Get online members of a house
     */
    public function getMembers(Request $request)
    {
        $houseId = $request->input('house_id');

        if (!$houseId || Auth::user()->team != $houseId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Consider users online if they've been active in the last 5 minutes
        $onlineThreshold = Carbon::now()->subMinutes(5);

        $members = DB::table('users')
            ->where('team', $houseId)
            ->select(
                'id',
                'name',
                'avatar',
                'level',
                'last_activity',
                DB::raw("CASE WHEN last_activity >= '{$onlineThreshold}' THEN 1 ELSE 0 END as is_online")
            )
            ->orderBy('is_online', 'desc')
            ->orderBy('level', 'desc')
            ->get();

        return response()->json([
            'members' => $members->map(function($member) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'avatar' => $member->avatar ?: '/images/default-avatar.png',
                    'level' => $member->level ?? 1,
                    'is_online' => (bool)$member->is_online
                ];
            })
        ]);
    }

    /**
     * Get announcements for a house
     */
    public function getAnnouncements(Request $request)
    {
        $houseId = $request->input('house_id');

        if (!$houseId || Auth::user()->team != $houseId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $announcements = DB::table('house_announcements')
            ->where('house_id', $houseId)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'announcements' => $announcements->map(function($ann) {
                return [
                    'id' => $ann->id,
                    'title' => $ann->title,
                    'content' => $ann->content,
                    'priority' => $ann->priority,
                    'created_at' => Carbon::parse($ann->created_at)->diffForHumans()
                ];
            })
        ]);
    }

    /**
     * Get events for a house
     */
    public function getEvents(Request $request)
    {
        $houseId = $request->input('house_id');

        if (!$houseId || Auth::user()->team != $houseId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $events = DB::table('house_events')
            ->where('house_id', $houseId)
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->get();

        $userId = Auth::id();

        return response()->json([
            'events' => $events->map(function($event) use ($userId) {
                $participantCount = DB::table('house_event_participants')
                    ->where('event_id', $event->id)
                    ->where('status', 'going')
                    ->count();

                $userRsvp = DB::table('house_event_participants')
                    ->where('event_id', $event->id)
                    ->where('user_id', $userId)
                    ->first();

                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'type' => $event->type,
                    'event_date' => Carbon::parse($event->event_date)->format('d M Y H:i'),
                    'event_date_human' => Carbon::parse($event->event_date)->diffForHumans(),
                    'max_participants' => $event->max_participants,
                    'participants_count' => $participantCount,
                    'user_rsvp' => $userRsvp ? $userRsvp->status : null
                ];
            })
        ]);
    }

    /**
     * Join an event (RSVP)
     */
    public function joinEvent(Request $request, $eventId)
    {
        $event = DB::table('house_events')->where('id', $eventId)->first();

        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        if (Auth::user()->team != $event->house_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if user already RSVP'd
        $existing = DB::table('house_event_participants')
            ->where('event_id', $eventId)
            ->where('user_id', Auth::id())
            ->first();

        if ($existing) {
            // Update status
            DB::table('house_event_participants')
                ->where('id', $existing->id)
                ->update([
                    'status' => 'going',
                    'updated_at' => now()
                ]);
        } else {
            // Create new RSVP
            DB::table('house_event_participants')->insert([
                'event_id' => $eventId,
                'user_id' => Auth::id(),
                'status' => 'going',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Get house statistics
     */
    public function getHouseStats(Request $request)
    {
        $houseId = $request->input('house_id');

        if (!$houseId || Auth::user()->team != $houseId) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $house = DB::table('houses')->where('id', $houseId)->first();

        if (!$house) {
            return response()->json(['error' => 'House not found'], 404);
        }

        // Get house ranking
        $houses = DB::table('houses')
            ->orderBy('points', 'desc')
            ->get();

        $rank = 1;
        foreach ($houses as $index => $h) {
            if ($h->id == $houseId) {
                $rank = $index + 1;
                break;
            }
        }

        // Count members
        $memberCount = DB::table('users')->where('team', $houseId)->count();

        // Count completed quests (if table exists)
        $completedQuests = 0;
        if (DB::getSchemaBuilder()->hasTable('user_quest')) {
            $completedQuests = DB::table('user_quest')
                ->join('users', 'user_quest.user_id', '=', 'users.id')
                ->where('users.team', $houseId)
                ->where('user_quest.completed', true)
                ->count();
        }

        return response()->json([
            'rank' => $rank,
            'total_points' => $house->points ?? 0,
            'members' => $memberCount,
            'quests_completed' => $completedQuests
        ]);
    }
}
