<?php

namespace App\Http\Controllers;

use App\Models\CompanyAccount;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log as FacadesLog;

class CompanyAccountController extends Controller
{
    public function index()
    {
        $accounts = CompanyAccount::latest()->paginate(10);
        return view('admin.company-accounts', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name'      => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_name'   => 'required|string|max:255',
            'branch'         => 'nullable|string|max:255',
        ]);

        try {
            CompanyAccount::create($validated);
            return redirect()->back()->with('success', 'Nomor rekening perusahaan berhasil ditambahkan!');
        } catch (Exception $e) {
            FacadesLog::error('Gagal menambah rekening: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambah data karena kendala sistem.');
        }
    }

    public function update(Request $request, CompanyAccount $account)
    {
        $validated = $request->validate([
            'bank_name'      => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_name'   => 'required|string|max:255',
            'branch'         => 'nullable|string|max:255',
            'is_active'      => 'required|boolean'
        ]);

        try {
            $account->update($validated);
            return redirect()->back()->with('success', 'Data rekening perusahaan berhasil diperbarui!');
        } catch (Exception $e) {
            FacadesLog::error('Gagal memperbarui rekening ID ' . $account->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data karena kendala sistem.');
        }
    }

    public function destroy(CompanyAccount $account)
    {
        try {
            $account->delete();
            return redirect()->back()->with('success', 'Data rekening berhasil dihapus dari sistem!');
        } catch (Exception $e) {
            FacadesLog::error('Gagal menghapus rekening ID ' . $account->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus data dari sistem.');
        }
    }
}