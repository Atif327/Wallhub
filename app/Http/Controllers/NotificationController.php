<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->notifications()
            ->where('read', false)
            ->update(['read' => true]);
        
        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotificationsCount();
        
        return response()->json(['count' => $count]);
    }
}
