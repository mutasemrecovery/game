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

    /**
     * Server-Sent Events stream.
     * Runs for ~25 s then closes; EventSource auto-reconnects seamlessly.
     */
    public function stream(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        // Release session file lock so other browser tabs are not blocked
        session()->save();

        return response()->stream(function () use ($admin) {
            // Kill any PHP output buffering layers
            while (ob_get_level() > 0) {
                ob_end_clean();
            }

            $lastCheck = now()->subSeconds(1);
            $start     = microtime(true);
            $maxRun    = 25; // seconds before graceful close + reconnect

            echo ": connected\n\n";
            flush();

            while (microtime(true) - $start < $maxRun) {
                if (connection_aborted()) {
                    break;
                }

                sleep(2);

                if (connection_aborted()) {
                    break;
                }

                $fresh = $admin->unreadNotifications()
                    ->where('created_at', '>=', $lastCheck)
                    ->orderBy('created_at')
                    ->get();

                if ($fresh->isNotEmpty()) {
                    $lastCheck = $fresh->last()->created_at->addSecond();

                    foreach ($fresh as $n) {
                        echo 'data: ' . json_encode([
                            'id'         => $n->id,
                            'data'       => $n->data,
                            'created_at' => $n->created_at->diffForHumans(),
                        ]) . "\n\n";
                        flush();
                    }
                } else {
                    // Keep-alive so proxy/browser doesn't close the connection
                    echo ": ping\n\n";
                    flush();
                }
            }

            // Signal client to immediately reconnect
            echo 'data: ' . json_encode(['reconnect' => true]) . "\n\n";
            flush();
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache',
            'X-Accel-Buffering' => 'no',   // nginx / LiteSpeed: don't buffer
            'Connection'        => 'keep-alive',
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
