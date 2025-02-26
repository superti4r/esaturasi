<?php

namespace App\Http\Controllers\Administrator;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class DashboardAdministrator extends Controller
{
    public function index()
    {
        $totalUser = User::count();
        $totalSiswa = Siswa::count();
        $totalMapel = MataPelajaran::count();
        $totalKelas = Kelas::count();
        $totalJurusan = Jurusan::count();

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $pingServer = exec("ping -n 1 8.8.8.8 | findstr /R /C:\"Average =\"") ?: "Timeout";
            $uptimeRaw = exec("wmic OS get LastBootUpTime /value");
            preg_match('/\d{8}\d{6}/', $uptimeRaw, $matches);

            if (isset($matches[0])) {
                $lastBoot = Carbon::createFromFormat('YmdHis', $matches[0]);
                $now = Carbon::now();
                $uptime = $now->diff($lastBoot)->format('%d Hari, %h Jam, %i Menit');
            } else {
                $uptime = "Tidak tersedia";
            }
        } else {
            $pingServer = shell_exec("ping -c 1 8.8.8.8 | grep 'time=' | awk -F'time=' '{print $2}' | awk '{print $1}'");
            $pingServer = $pingServer ? trim($pingServer) . " ms" : "Timeout";
            $uptimeRaw = shell_exec("cat /proc/uptime");
            if ($uptimeRaw) {
                $uptimeSeconds = (int)explode(" ", $uptimeRaw)[0];
                $days = floor($uptimeSeconds / 86400);
                $hours = floor(($uptimeSeconds % 86400) / 3600);
                $minutes = floor(($uptimeSeconds % 3600) / 60);

                $uptime = ($days ? $days . " Hari, " : "") . ($hours ? $hours . " Jam, " : "") . $minutes . " Menit";
            } else {
                $uptime = "Tidak tersedia";
            }
        }

        $storageTotal = round(disk_total_space("/") / 1073741824, 2);
        $storageAvailable = round(disk_free_space("/") / 1073741824, 2);
        $serverOS = php_uname('s') . " " . php_uname('r');
        $cpuInfo = shell_exec("grep 'model name' /proc/cpuinfo | head -1 | cut -d ':' -f2") ?: "Tidak tersedia";
        $ramTotal = shell_exec("grep MemTotal /proc/meminfo | awk '{print $2}'");
        $ramTotal = $ramTotal ? round($ramTotal / 1048576, 2) . " GB" : "Tidak tersedia";

        return view('administrator.dashboard', compact(
            'totalUser',
            'totalSiswa',
            'totalMapel',
            'totalKelas',
            'totalJurusan',
            'pingServer',
            'uptime',
            'storageTotal',
            'storageAvailable',
            'serverOS',
            'cpuInfo',
            'ramTotal'
        ));
    }

    public function updateRegisterToken(Request $request)
    {
        $request->validate([
            'register_token' => 'required|string|max:255',
        ]);

        $newToken = $request->input('register_token');
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            return redirect()->back()->with('error', 'File .env tidak ditemukan.');
        }

        $envContent = File::get($envPath);

        if (preg_match('/^REGISTER_TOKEN=.*$/m', $envContent)) {
            $envContent = preg_replace('/^REGISTER_TOKEN=.*$/m', "REGISTER_TOKEN={$newToken}", $envContent);
        } else {
            $envContent .= "\nREGISTER_TOKEN={$newToken}\n";
        }

        File::put($envPath, $envContent);
        Artisan::call('config:clear');
        Artisan::call('config:cache');

        return redirect()->back()->with('success', 'REGISTER_TOKEN berhasil diperbarui.');
    }

    public function updateGeminiApiKey(Request $request)
    {
        $request->validate([
            'gemini_api_key' => 'required|string|max:255',
        ]);

        $newApiKey = $request->input('gemini_api_key');
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            return redirect()->back()->with('error', 'File .env tidak ditemukan.');
        }

        $envContent = File::get($envPath);

        if (preg_match('/^GEMINI_API_KEY=.*$/m', $envContent)) {
            $envContent = preg_replace('/^GEMINI_API_KEY=.*$/m', "GEMINI_API_KEY={$newApiKey}", $envContent);
        } else {
            $envContent .= "\nGEMINI_API_KEY={$newApiKey}\n";
        }

        File::put($envPath, $envContent);
        Artisan::call('config:clear');
        Artisan::call('config:cache');

        return redirect()->back()->with('success', 'GEMINI_API_KEY berhasil diperbarui.');
    }

}
