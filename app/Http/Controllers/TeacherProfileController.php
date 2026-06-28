<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TeacherProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class TeacherProfileController extends Controller
{
    /**
     * INDEX (Admin & Guru)
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            // Menggunakan Spatie hasRole() / Deteksi rute Guru
            if ($user->hasRole('guru') || $request->is('guru*')) {
                $user->load('teacherProfile');
                $profile = $user->teacherProfile ?? new TeacherProfile();
                $skills = $profile->skills_tags ? json_decode($profile->skills_tags, true) : [];

                return view('guru.profile', compact('user', 'profile', 'skills'));
            }

            // JIKA YANG AKSES ADALAH ADMIN
            $query = TeacherProfile::with('user');

            if ($request->has('status') && $request->status != '') {
                $query->where('verification_status', $request->status);
            }

            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('skills_tags', 'like', "%{$search}%")
                        ->orWhere('title', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            }

            $profiles = $query->latest()->paginate(10)->withQueryString();

            // Memastikan hanya mengambil user yang belum punya profil guru
            $users = User::whereDoesntHave('teacherProfile')->get();

            return view('admin.teacher', compact('profiles', 'users'));
        } catch (Exception $e) {
            Log::error('Gagal memuat halaman manajemen profil guru: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat memuat data pengajar.');
        }
    }

    /**
     * STORE (Khusus Admin menambahkan guru manual)
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'             => 'required|exists:users,id|unique:teacher_profiles,user_id',
            'title'               => 'required|string|max:255',
            'bio'                 => 'nullable|string',
            'skills_tags'         => 'required|string',
            'verification_status' => 'required|in:pending,approved,rejected',
            'cv_file'             => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'bank_name'           => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name'   => 'required|string|max:255',
        ], [
            'user_id.required'             => 'Pengguna wajib dipilih.',
            'user_id.unique'               => 'Pengguna ini sudah memiliki profil pengajar terdaftar.',
            'title.required'               => 'Gelar profesi atau keahlian utama wajib diisi.',
            'skills_tags.required'         => 'Label kompetensi (skills tags) wajib ditentukan.',
            'verification_status.required' => 'Status verifikasi akun wajib ditentukan.',
            'cv_file.mimes'                => 'Format dokumen CV harus berupa berkas PDF, DOC, atau DOCX.',
            'cv_file.max'                  => 'Ukuran dokumen CV maksimal adalah 2 MB.',
            'bank_name.required'           => 'Nama bank wajib diisi.',
            'bank_account_number.required' => 'Nomor rekening bank wajib diisi.',
            'bank_account_name.required'   => 'Nama pemilik rekening wajib diisi.',
        ]);

        $uploadedCv = null;
        DB::beginTransaction();

        try {
            // Mengubah string tags menjadi JSON array sebelum disimpan
            $tagsArray = array_map('trim', explode(',', $request->skills_tags));

            $data = $request->except('cv_file');
            $data['skills_tags'] = json_encode($tagsArray);
            $data['average_rating'] = 0;

            if ($request->hasFile('cv_file')) {
                $uploadedCv = $request->file('cv_file')->store('cv_teachers', 'public');
                $data['cv_file'] = $uploadedCv;
            }

            $profile = TeacherProfile::create($data);

            // Spatie: Mengubah role user jika status disetujui
            if ($request->verification_status === 'approved') {
                $profile->user->syncRoles(['guru']);
            }

            DB::commit();
            return redirect('/teachers')->with('success', 'Profil pengajar baru berhasil ditambahkan.');
        } catch (Exception $e) {
            DB::rollBack();
            if ($uploadedCv && Storage::disk('public')->exists($uploadedCv)) {
                Storage::disk('public')->delete($uploadedCv);
            }
            Log::error('Gagal menambahkan profil guru secara manual: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data karena kendala internal basis data.');
        }
    }

    /**
     * SHOW (Khusus Admin)
     */
    public function show(TeacherProfile $profile)
    {
        try {
            $profile->load('user');
            return view('admin.teacher-show', compact('profile'));
        } catch (Exception $e) {
            Log::error('Gagal memuat detail profil guru: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data detail pengajar tidak dapat ditemukan.');
        }
    }

    /**
     * UPDATE (Admin & Guru)
     */
    public function update(Request $request, TeacherProfile $profile = null)
    {
        $user = Auth::user();

        if ($user->hasRole('guru') || $request->is('guru*') || is_null($profile) || !$profile->exists) {
            $request->validate([
                'name'                => 'required|string|max:255',
                'phone_number'        => 'nullable|string|max:20',
                'avatar'              => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'title'               => 'required|string|max:100',
                'bio'                 => 'nullable|string|max:1000',
                'skills_tags'         => 'nullable|string',
                'cv_file'             => 'nullable|mimes:pdf|max:3072',
                'bank_name'           => 'required|string|max:50',
                'bank_account_number' => 'required|string|max:50',
                'bank_account_name'   => 'required|string|max:255',
            ], [
                'name.required'                => 'Nama lengkap wajib diisi.',
                'avatar.image'                 => 'Berkas avatar harus berupa gambar.',
                'avatar.mimes'                 => 'Format gambar harus berupa JPEG, PNG, atau JPG.',
                'avatar.max'                   => 'Ukuran foto avatar maksimal adalah 2 MB.',
                'title.required'               => 'Gelar kompetensi pengajar wajib diisi.',
                'bio.max'                      => 'Deskripsi bio terlalu panjang, maksimal 1000 karakter.',
                'cv_file.mimes'                => 'Format dokumen riwayat hidup harus berupa file PDF.',
                'cv_file.max'                  => 'Ukuran file berkas CV maksimal 3 MB.',
                'bank_name.required'           => 'Nama instansi perbankan wajib diisi.',
                'bank_account_number.required' => 'Nomor rekening bank wajib diisi.',
                'bank_account_name.required'   => 'Nama pemilik rekening sesuai buku tabungan wajib diisi.',
            ]);

            $uploadedAvatar = null;
            $uploadedCv = null;
            DB::beginTransaction();

            try {
                // Update data personal dasar user
                $userData = ['name' => $request->name, 'phone_number' => $request->phone_number];
                if ($request->hasFile('avatar')) {
                    $uploadedAvatar = $request->file('avatar')->store('avatars', 'public');
                    $userData['avatar'] = $uploadedAvatar;
                }

                $oldAvatar = $user->avatar;
                $user->update($userData);

                // Mempersiapkan pemecahan teks tag ke format JSON
                $tagsArray = $request->skills_tags ? array_map('trim', explode(',', $request->skills_tags)) : [];

                $profileData = [
                    'title'               => $request->title,
                    'bio'                 => $request->bio,
                    'skills_tags'         => json_encode($tagsArray),
                    'bank_name'           => $request->bank_name,
                    'bank_account_number' => $request->bank_account_number,
                    'bank_account_name'   => $request->bank_account_name,
                ];

                if ($request->hasFile('cv_file')) {
                    $uploadedCv = $request->file('cv_file')->store('cv_teachers', 'public');
                    $profileData['cv_file'] = $uploadedCv;
                }

                // Ambil data dokumen CV lama jika ada (untuk pelindung memori hosting)
                $oldCv = $user->teacherProfile ? $user->teacherProfile->cv_file : null;

                // FITUR UTAMA: Jika data belum ada, dia jalankan Create. Jika sudah ada, langsung melakukan Update.
                $user->teacherProfile()->updateOrCreate(
                    ['user_id' => $user->id], // Kolom pencari identitas unik pemilik profil
                    array_merge($profileData, [
                        'verification_status' => $user->teacherProfile->verification_status ?? 'pending',
                        'average_rating'      => $user->teacherProfile->average_rating ?? 0
                    ])
                );

                DB::commit();

                // Bersihkan storage dari berkas usang yang tertimpa file baru
                if ($uploadedAvatar && $oldAvatar && Storage::disk('public')->exists($oldAvatar)) {
                    Storage::disk('public')->delete($oldAvatar);
                }
                if ($uploadedCv && $oldCv && Storage::disk('public')->exists($oldCv)) {
                    Storage::disk('public')->delete($oldCv);
                }

                return redirect()->back()->with('success', 'Informasi profil pengajar berhasil disinkronkan!');
            } catch (Exception $e) {
                DB::rollBack();
                if ($uploadedAvatar && Storage::disk('public')->exists($uploadedAvatar)) {
                    Storage::disk('public')->delete($uploadedAvatar);
                }
                if ($uploadedCv && Storage::disk('public')->exists($uploadedCv)) {
                    Storage::disk('public')->delete($uploadedCv);
                }
                Log::error('Sistem gagal melakukan sinkronisasi profil otomatis: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Gagal memproses data karena gangguan pangkalan data.');
            }
        }

        if (!$user->hasRole('admin')) {
            abort(403, 'Anda tidak memiliki hak akses untuk memperbarui profil pengajar ini.');
        }

        if (!$profile || !$profile->exists) {
            return redirect()->back()->with('error', 'Profil pengajar tidak ditemukan.');
        }

        $request->validate([
            'title'               => 'required|string|max:255',
            'bio'                 => 'nullable|string',
            'skills_tags'         => 'required|string',
            'verification_status' => 'required|in:pending,approved,rejected',
            'cv_file'             => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'bank_name'           => 'required|string|max:100',
            'bank_account_number' => 'required|string|max:50',
            'bank_account_name'   => 'required|string|max:255',
        ]);

        $uploadedCv = null;
        DB::beginTransaction();

        try {
            $tagsArray = array_map('trim', explode(',', $request->skills_tags));

            $data = [
                'title'               => $request->title,
                'bio'                 => $request->bio,
                'skills_tags'         => json_encode($tagsArray),
                'verification_status' => $request->verification_status,
                'bank_name'           => $request->bank_name,
                'bank_account_number' => $request->bank_account_number,
                'bank_account_name'   => $request->bank_account_name,
            ];

            $oldCv = $profile->cv_file;

            if ($request->hasFile('cv_file')) {
                $uploadedCv = $request->file('cv_file')->store('cv_teachers', 'public');
                $data['cv_file'] = $uploadedCv;
            }

            $profile->update($data);

            $profileUser = $profile->user;
            if ($request->verification_status === 'approved' && $profileUser) {
                $profileUser->assignRole('guru');
            } elseif ($request->verification_status === 'rejected' && $profileUser && $profileUser->hasRole('guru')) {
                $profileUser->removeRole('guru');
            }

            DB::commit();

            if ($uploadedCv && $oldCv && Storage::disk('public')->exists($oldCv)) {
                Storage::disk('public')->delete($oldCv);
            }

            return redirect()->back()->with('success', 'Profil pengajar berhasil diperbarui oleh admin.');
        } catch (Exception $e) {
            DB::rollBack();
            if ($uploadedCv && Storage::disk('public')->exists($uploadedCv)) {
                Storage::disk('public')->delete($uploadedCv);
            }
            Log::error('Gagal memperbarui profil guru oleh admin ID ' . $profile->id . ': ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil pengajar karena kendala sistem.');
        }
    }

    /**
     * DESTROY (Khusus Admin)
     */
    public function destroy(TeacherProfile $profile)
    {
        DB::beginTransaction();
        try {
            // 1. Ambil data yang diperlukan sebelum dihapus dari database
            $userName = $profile->user->name ?? 'Pengajar';
            $cvPath = $profile->cv_file;
            $user = $profile->user;

            // Backup path avatar jika ingin menghapus foto profilnya juga dari storage
            $avatarPath = $user ? $user->avatar : null;

            // 2. Hapus data Profil Pengajar terlebih dahulu (menghindari lock foreign key)
            $profile->delete();

            // 3. Hapus data Akun User utamanya jika data user tersebut ada
            if ($user) {
                // Spatie: Cabut semua roles yang melekat pada user ini agar tabel permission bersih
                $user->roles()->detach();

                // Hapus baris data user dari tabel users
                $user->delete();
            }

            DB::commit();

            // 4. Bersihkan Berkas Fisik dari Storage setelah DB sukses commit
            // Hapus file CV
            if ($cvPath && Storage::disk('public')->exists($cvPath)) {
                Storage::disk('public')->delete($cvPath);
            }

            // Hapus file Avatar (Foto Profil) jika bukan avatar default sistem
            if ($avatarPath && $avatarPath !== 'default-avatar.png' && Storage::disk('public')->exists($avatarPath)) {
                Storage::disk('public')->delete($avatarPath);
            }

            return redirect('/teachers')->with('success', "Akun dan profil pengajar atas nama {$userName} berhasil dihapus permanen dari sistem.");
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Gagal menghapus total akun & profil guru ID ' . $profile->id . '. Pesan Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal menghapus data karena kendala relasi basis data: ' . $e->getMessage());
        }
    }

    /**
     * VERIFY (Khusus Admin - Tombol Aksi Cepat)
     */
    public function verify(Request $request, TeacherProfile $profile)
    {
        $request->validate([
            'verification_status' => 'required|in:approved,rejected',
        ], [
            'verification_status.required' => 'Status konfirmasi peninjauan wajib dilampirkan.',
            'verification_status.in'       => 'Pilihan status peninjauan tidak sah.'
        ]);

        DB::beginTransaction();
        try {
            $profile->update([
                'verification_status' => $request->verification_status
            ]);

            $user = $profile->user;

            if ($request->verification_status === 'approved') {
                $user->assignRole('guru');
            } else {
                if ($user->hasRole('guru')) {
                    $user->removeRole('guru');
                }
                $user->update([
                    'is_active' => 0
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', "Status akun pengajar {$user->name} berhasil diperbarui menjadi " . strtoupper($request->verification_status));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Gagal mengubah status verifikasi profil guru ID ' . $profile->id . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengubah status verifikasi karena kendala internal server: ' . $e->getMessage());
        }
    }
}
