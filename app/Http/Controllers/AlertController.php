<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    public function index()
    {
        $alerts = Alert::with('device')->latest()->paginate(10);
        return view('alerts.index', compact('alerts'));
    }

    public function resolve(Alert $alert)
    {
        $alert->status = 'resolved';
        $alert->save();

        return back()->with('success', 'Alert berhasil diselesaikan.');
    }

    public function resolveAll()
    {
        $count = Alert::where('status', 'unresolved')->count();
        Alert::where('status', 'unresolved')->update(['status' => 'resolved']);

        return back()->with('success', "{$count} alert berhasil diselesaikan semua.");
    }
}

