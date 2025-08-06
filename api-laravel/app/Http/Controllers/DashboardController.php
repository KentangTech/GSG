<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $notifications = Notification::latest()->paginate(10);
        return view('Dashboard', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = true;
        $notification->save();

        return redirect()->back();
    }

    public function markAllAsRead()
    {
        \App\Models\Notification::where('is_read', false)->update(['is_read' => true]);
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }
}
