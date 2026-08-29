<?php

namespace App\Http\Controllers;

use App\Models\Tim;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TimController extends Controller
{
    public function index(): View
    {
        return view('tim.index', ['tim' => Tim::latest()->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nama_tim' => ['required', 'string', 'max:255', 'unique:tim,nama_tim'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
        ]);
        Tim::create($data + ['status' => 'aktif']);
        return back()->with('success', 'Tim berhasil ditambahkan.');
    }

    public function update(Request $request, Tim $tim): RedirectResponse
    {
        $data = $request->validate([
            'nama_tim' => ['required', 'string', 'max:255', 'unique:tim,nama_tim,' . $tim->id],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ]);
        $tim->update($data);
        return back()->with('success', 'Tim berhasil diperbarui.');
    }
}
