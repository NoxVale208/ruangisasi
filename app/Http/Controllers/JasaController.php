<?php

namespace App\Http\Controllers;

use App\Models\Jasa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JasaController extends Controller
{
    public function index(): View
    {
        return view('jasa.index', ['jasa' => Jasa::latest()->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:jasa,nama'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
        ]);
        Jasa::create($data + ['status' => 'aktif']);
        return back()->with('success', 'Jenis jasa berhasil ditambahkan.');
    }

    public function update(Request $request, Jasa $jasa): RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:jasa,nama,' . $jasa->id],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);
        $jasa->update($data);
        return back()->with('success', 'Jenis jasa berhasil diperbarui.');
    }
}
