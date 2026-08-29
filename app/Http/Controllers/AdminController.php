<?php

namespace App\Http\Controllers;

use App\Models\PemesananJasa;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $pemesanan = PemesananJasa::with('user')->latest()->get();
        $stats = [
            'total' => $pemesanan->count(),
            'menunggu' => $pemesanan->where('status_persetujuan', 'menunggu')->count(),
            'berjalan' => $pemesanan->whereIn('status_proses', ['pengerjaan', 'perbaikan'])->count(),
            'selesai' => $pemesanan->where('status_proses', 'selesai')->count(),
        ];

        return view('admin.dashboard', compact('pemesanan', 'stats'));
    }
}
