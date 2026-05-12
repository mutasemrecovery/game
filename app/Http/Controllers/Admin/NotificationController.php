<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $admin         = Auth::guard('admin')->user();
        $notifications = $admin->notifications()->latest()->take(20)->get();

        return response()->json([
            'notifications' => $notifications->map(fn($n) => [
                'id'         => $n->id,
                'data'       => $n->data,
                'read_at'    => $n->read_at,
                'created_at' => $n->created_at->diffForHumans(),
            ]),
            'unread_count' => $admin->unreadNotifications()->count(),
        ]);
    }

    public function markAllRead()
    {
        Auth::guard('admin')->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markRead($id)
    {
        $n = Auth::guard('admin')->user()->notifications()->where('id', $id)->first();
        if ($n) {
            $n->markAsRead();
        }
        return response()->json(['success' => true]);
    }
}
