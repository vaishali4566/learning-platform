<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationCustom;

class NotificationController extends Controller
{
    // 🔹 Store new notification
    public function store(Request $request)
    {
        $notification = NotificationCustom::create([
            'user_id'    => $request->user_id,
            'trainer_id' => $request->trainer_id,
            'admin_id'   => $request->admin_id,
            'title'      => $request->title,
            'message'    => $request->message,
            'type'       => $request->type ?? 'info',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notification created successfully',
            'data' => $notification,
        ]);
    }

    // 🔹 Fetch notifications for logged-in person
    public function fetch(Request $request)
    {
        $role = $request->role; // 'user' | 'trainer' | 'admin'

        $notifications = match ($role) {
            'trainer' => NotificationCustom::where('trainer_id', auth('trainer')->id())->latest()->get(),
            'admin'   => NotificationCustom::where('admin_id', auth('web')->id())->latest()->get(),
            default   => NotificationCustom::where('user_id', auth('web')->id())->latest()->get(),
        };

        return response()->json([
            'status' => 'success',
            'notifications' => $notifications,
        ]);
    }

    // 🔹 Mark one as read
    public function markAsRead($id)
    {
        $notification = NotificationCustom::find($id);
        if (!$notification) {
            return response()->json(['status' => 'error', 'message' => 'Not found'], 404);
        }

        $notification->update(['is_read' => true]);

        return response()->json(['status' => 'success', 'message' => 'Marked as read']);
    }
}
