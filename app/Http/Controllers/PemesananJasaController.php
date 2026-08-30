<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use App\Models\PemesananJasa;
use App\Models\StatusPemesanan;
use App\Models\Tim;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PemesananJasaController extends Controller
{
    public function index(): View
    {
        $pemesanan = PemesananJasa::with([
            'jasa',
            'tim',
            'diputuskanOleh',
            'riwayatStatus.pengguna',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $jasa = Jasa::where('status', 'aktif')
            ->orderBy('nama')
            ->get();

        return view('user.dashboard', compact('pemesanan', 'jasa'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'jasa_id' => ['required', 'exists:jasa,id'],
            'alamat' => ['required', 'string', 'max:1000'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:today'],
            'budget' => ['required', 'integer', 'min:0'],
        ]);

        $jasa = Jasa::where('id', $validated['jasa_id'])
            ->where('status', 'aktif')
            ->first();

        if (!$jasa) {
            return back()
                ->withInput()
                ->with('error', 'Jasa yang dipilih sedang tidak tersedia.');
        }

        $pemesanan = PemesananJasa::create([
            'user_id' => Auth::id(),
            'jasa_id' => $jasa->id,
            'nama_jasa' => $jasa->nama,
            'alamat' => $validated['alamat'],
            'tanggal_mulai' => today(),
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'budget' => $validated['budget'],
            'status_persetujuan' => 'menunggu',
            'status_proses' => 'menunggu',
            'keputusan' => null,
            'diputuskan_oleh' => null,
            'diputuskan_pada' => null,
            'tim_id' => null,
            'ditugaskan_pada' => null,
            'catatan_admin' => null,
        ]);

        $this->buatRiwayat(
            $pemesanan,
            'menunggu',
            'Pemesanan dibuat dan menunggu persetujuan Admin atau Super Admin.'
        );

        return back()->with(
            'success',
            'Pemesanan berhasil dikirim. Menunggu persetujuan Admin/Super Admin.'
        );
    }

    public function adminIndex(): View
    {
        $pemesanan = PemesananJasa::with([
            'user',
            'jasa',
            'tim',
            'diputuskanOleh',
            'riwayatStatus.pengguna',
        ])
            ->latest()
            ->get();

        $tim = Tim::where('status', 'aktif')
            ->orderBy('nama_tim')
            ->get();

        $stats = [
            'total' => $pemesanan->count(),
            'menunggu' => $pemesanan->where('status_persetujuan', 'menunggu')->count(),
            'berjalan' => $pemesanan->whereIn('status_proses', ['pengerjaan', 'perbaikan'])->count(),
            'selesai' => $pemesanan->where('status_proses', 'selesai')->count(),
        ];

        return view('admin.dashboard', compact('pemesanan', 'tim', 'stats'));
    }

    public function approve(Request $request, PemesananJasa $pemesananJasa): RedirectResponse
    {
        $validated = $request->validate([
            'keputusan' => ['required', 'in:setuju,tidak_setuju'],
            'catatan_admin' => [
                'nullable',
                'string',
                'max:2000',
                'required_if:keputusan,tidak_setuju',
            ],
        ], [
            'catatan_admin.required_if' => 'Alasan penolakan wajib diisi.',
        ]);

        if ($pemesananJasa->status_persetujuan !== 'menunggu') {
            return back()->with('error', 'Pemesanan ini sudah memiliki keputusan.');
        }

        $approved = $validated['keputusan'] === 'setuju';
        $catatan = trim($validated['catatan_admin'] ?? '');

        $pemesananJasa->update([
            'keputusan' => $validated['keputusan'],
            'status_persetujuan' => $validated['keputusan'],
            'status_proses' => $approved ? 'menunggu_tim' : 'ditolak',
            'diputuskan_oleh' => Auth::id(),
            'diputuskan_pada' => now(),
            'catatan_admin' => $catatan !== '' ? $catatan : null,
        ]);

        $this->buatRiwayat(
            $pemesananJasa,
            $approved ? 'menunggu_tim' : 'ditolak',
            $approved
                ? ($catatan !== '' ? $catatan : 'Pemesanan disetujui dan menunggu penugasan tim.')
                : 'Pemesanan ditolak. Alasan: ' . $catatan
        );

        return back()->with(
            'success',
            $approved
                ? 'Pemesanan disetujui. Silakan tentukan tim pengerjaannya.'
                : 'Pemesanan ditolak dan alasan sudah disimpan.'
        );
    }

    public function assignTeam(Request $request, PemesananJasa $pemesananJasa): RedirectResponse
    {
        $validated = $request->validate([
            'tim_id' => ['required', 'exists:tim,id'],
        ]);

        if ($pemesananJasa->status_persetujuan !== 'setuju') {
            return back()->with('error', 'Tim hanya dapat dipilih untuk pesanan yang sudah disetujui.');
        }

        $tim = Tim::where('id', $validated['tim_id'])
            ->where('status', 'aktif')
            ->first();

        if (!$tim) {
            return back()->with('error', 'Tim yang dipilih sedang tidak aktif.');
        }

        $pemesananJasa->update([
            'tim_id' => $tim->id,
            'ditugaskan_pada' => now(),
            'status_proses' => 'pengerjaan',
        ]);

        $this->buatRiwayat(
            $pemesananJasa,
            'pengerjaan',
            'Tim ' . $tim->nama_tim . ' ditugaskan untuk mengerjakan pesanan.'
        );

        return back()->with('success', 'Tim ' . $tim->nama_tim . ' berhasil ditugaskan.');
    }

    public function updateProcess(Request $request, PemesananJasa $pemesananJasa): RedirectResponse
    {
        $validated = $request->validate([
            'status_proses' => ['required', 'in:pengerjaan,perbaikan,selesai'],
            'catatan_admin' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($pemesananJasa->status_persetujuan !== 'setuju') {
            return back()->with('error', 'Pesanan harus disetujui terlebih dahulu.');
        }

        if (!$pemesananJasa->tim_id) {
            return back()->with('error', 'Pilih tim pengerja terlebih dahulu.');
        }

        $status = $validated['status_proses'];
        $catatan = trim($validated['catatan_admin'] ?? '');

        $pemesananJasa->update([
            'status_proses' => $status,
            'catatan_admin' => $catatan !== '' ? $catatan : $pemesananJasa->catatan_admin,
        ]);

        $keteranganDefault = match ($status) {
            'pengerjaan' => 'Pekerjaan sedang dikerjakan.',
            'perbaikan' => 'Pemesanan membutuhkan perbaikan.',
            'selesai' => 'Pekerjaan telah selesai.',
            default => 'Status pengerjaan diperbarui.',
        };

        $this->buatRiwayat(
            $pemesananJasa,
            $status,
            $catatan !== '' ? $catatan : $keteranganDefault
        );

        return back()->with('success', 'Status pengerjaan berhasil diperbarui.');
    }

    public function superAdminIndex(): View
    {
        $pemesanan = PemesananJasa::with([
            'user',
            'jasa',
            'tim',
            'diputuskanOleh',
            'riwayatStatus.pengguna',
        ])
            ->latest()
            ->get();

        return view('superadmin.dashboard', compact('pemesanan'));
    }

    private function buatRiwayat(
        PemesananJasa $pemesanan,
        string $status,
        ?string $catatan = null
    ): void {
        StatusPemesanan::create([
            'pemesanan_jasa_id' => $pemesanan->id,
            'status' => $status,
            'catatan' => $catatan,
            'diubah_oleh' => Auth::id(),
            'diubah_pada' => now(),
        ]);
    }
}
