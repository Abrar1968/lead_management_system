<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Check for upcoming meetings and return notification data.
     * Called on page load and via polling every 5 minutes.
     */
    public function checkUpcomingMeetings(Request $request): JsonResponse
    {
        $user = auth()->user();
        $now = Carbon::now();
        $today = $now->format('Y-m-d');

        // Base query for today's pending meetings
        $query = Meeting::with(['lead', 'lead.assignedTo'])
            ->whereDate('meeting_date', $today)
            ->where('outcome', 'Pending')
            ->whereHas('lead');

        // Filter by user if sales person
        if ($user->isSalesPerson()) {
            $query->whereHas('lead', fn ($q) => $q->where('assigned_to', $user->id));
        }

        $meetings = $query->orderBy('meeting_time', 'asc')->get();

        // Check session for login alert
        $isLoginCheck = $request->boolean('login_check', false);
        $loginAlertShown = session('meeting_login_alert_shown', false);

        $notifyMeetings = [];
        $showLoginAlert = false;

        foreach ($meetings as $meeting) {
            // meeting_time is cast as datetime:H:i, so it already has the full datetime
            // We just need the time portion combined with the meeting_date
            $timeString = $meeting->meeting_time ? $meeting->meeting_time->format('H:i') : '00:00';
            $meetingDateTime = Carbon::parse($meeting->meeting_date->format('Y-m-d').' '.$timeString);
            $diffMinutes = $now->diffInMinutes($meetingDateTime, false);

            // Include meeting if:
            // 1. Login check and not yet shown today's alert
            // 2. Meeting is within next 65 minutes (1 hour + 5 min buffer for polling)
            if ($diffMinutes >= -5 && $diffMinutes <= 65) {
                $notifyMeetings[] = [
                    'id' => $meeting->id,
                    'client_name' => $meeting->lead->client_name ?? $meeting->lead->phone_number,
                    'phone' => $meeting->lead->phone_number,
                    'time' => $meeting->meeting_time ? $meeting->meeting_time->format('h:i A') : 'No time set',
                    'type' => $meeting->meeting_type,
                    'location' => $meeting->location,
                    'diff_minutes' => max(0, $diffMinutes),
                    'is_upcoming' => $diffMinutes > 0,
                ];
            }
        }

        // For login check, show all today's meetings if not shown yet
        if ($isLoginCheck && ! $loginAlertShown && $meetings->isNotEmpty()) {
            $showLoginAlert = true;
            session(['meeting_login_alert_shown' => true]);

            // Include all today's meetings for login alert
            $notifyMeetings = $meetings->map(function ($meeting) use ($now) {
                $timeString = $meeting->meeting_time ? $meeting->meeting_time->format('H:i') : '00:00';
                $meetingDateTime = Carbon::parse($meeting->meeting_date->format('Y-m-d').' '.$timeString);
                $diffMinutes = $now->diffInMinutes($meetingDateTime, false);

                return [
                    'id' => $meeting->id,
                    'client_name' => $meeting->lead->client_name ?? $meeting->lead->phone_number,
                    'phone' => $meeting->lead->phone_number,
                    'time' => $meeting->meeting_time ? $meeting->meeting_time->format('h:i A') : 'No time set',
                    'type' => $meeting->meeting_type,
                    'location' => $meeting->location,
                    'diff_minutes' => max(0, $diffMinutes),
                    'is_upcoming' => $diffMinutes > 0,
                ];
            })->toArray();
        }

        return response()->json([
            'alert' => ! empty($notifyMeetings),
            'login_alert' => $showLoginAlert,
            'meetings' => $notifyMeetings,
            'total_today' => $meetings->count(),
        ]);
    }

    /**
     * Mark login alert as shown for today.
     */
    public function dismissLoginAlert(): JsonResponse
    {
        session(['meeting_login_alert_shown' => true]);

        return response()->json(['success' => true]);
    }
}
