<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Presence;
use App\Models\PresenceSchedule;
use Carbon\Carbon;
use Hashids\Hashids;
use Illuminate\Http\Request;

class PresenceController extends Controller
{


    public function index()
    {
        $presence_schedules = PresenceSchedule::first();

        // Ambil tanggal dari query string, default = hari ini
        $selectedDate = request('date', Carbon::today()->toDateString());

        // Ambil semua presensi sesuai tanggal yang dipilih
        $presences = Presence::with('worker')
            ->whereDate('created_at', request('date', now()))
            ->paginate(10);
        return view('presences.index', compact('presence_schedules', 'presences', 'selectedDate'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Proses scan QR untuk presensi
     */
    public function scanQr(Request $request)
    {
        $request->validate([
            'qr' => 'required|string',
        ]);

        // Decode QR
        $hashids = new Hashids('', 40);
        $decoded = $hashids->decode($request->qr);
        if (empty($decoded)) {
            return response()->json(['error' => 'QR tidak valid'], 422);
        }

        $workerId = $decoded[0];
        $worker = Worker::find($workerId);
        if (!$worker) {
            return response()->json(['error' => 'Pekerja tidak ditemukan'], 404);
        }
        if (!$worker->is_active) {
            return response()->json(['error' => 'Pekerja sudah nonaktif, tidak bisa presensi.'], 403);
        }

        $schedule = PresenceSchedule::first();
        if (!$schedule) {
            return response()->json(['error' => 'Jadwal presensi belum diatur'], 422);
        }

        $today = now()->toDateString();
        $presence = Presence::firstOrCreate([
            'worker_id' => $worker->id,
            'date' => $today,
            'presence_schedule_id' => $schedule->id,
        ]);

        // Ambil waktu sekarang
        $now = Carbon::now();
        $timeNow = $now->format('H:i:s');

        // Konversi jadwal ke Carbon
        $fc_start = Carbon::parse($schedule->first_check_in_start);
        $fc_end   = Carbon::parse($schedule->first_check_in_end);
        $sc_start = Carbon::parse($schedule->second_check_in_start);
        $sc_end   = Carbon::parse($schedule->second_check_in_end);
        $co_start = Carbon::parse($schedule->check_out_start);
        $co_end   = Carbon::parse($schedule->check_out_end);

        $type = null; // biar bisa ditampilkan di response

        // ===============================
        // 🔹 FIRST CHECK IN
        // ===============================
        if ($now->between($fc_start->copy()->subHours(1), $fc_start->copy()->subSecond())) {
            if ($presence->first_check_in) {
                return response()->json(['error' => 'Presensi pertama sudah dilakukan hari ini.'], 422);
            }
            $presence->first_check_in = $timeNow;
            $presence->first_check_in_type = 'Datang Lebih Awal';
            $type = 'first_check_in';
        } elseif ($now->between($fc_start, $fc_end)) {
            if ($presence->first_check_in) {
                return response()->json(['error' => 'Presensi pertama sudah dilakukan hari ini.'], 422);
            }
            $presence->first_check_in = $timeNow;
            $presence->first_check_in_type = 'Tepat Waktu';
            $type = 'first_check_in';
        }

        // ===============================
        // 🔹 SECOND CHECK IN
        // ===============================
        elseif ($now->between($sc_start, $sc_end)) {
            if ($presence->second_check_in) {
                return response()->json(['error' => 'Presensi kedua sudah dilakukan hari ini.'], 422);
            }
            $presence->second_check_in = $timeNow;
            $presence->second_check_in_type = 'Tepat Waktu';
            $type = 'second_check_in';
        }

        // ===============================
        // 🔹 CHECK OUT
        // ===============================
        elseif ($now->between($co_start, $co_end)) {
            if ($presence->check_out) {
                return response()->json(['error' => 'Presensi pulang sudah dilakukan hari ini.'], 422);
            }
            $presence->check_out = $timeNow;
            $presence->check_out_type = 'Tepat Waktu';
            $type = 'check_out';
        } elseif ($now->greaterThan($co_end) && $now->lessThanOrEqualTo($co_end->copy()->addHours(4))) {
            if ($presence->check_out) {
                return response()->json(['error' => 'Presensi pulang sudah dilakukan hari ini.'], 422);
            }
            $presence->check_out = $timeNow;
            $presence->check_out_type = 'Pulang Lebih Lambat';
            $type = 'check_out';
        } elseif ($now->greaterThan($co_end->copy()->addHours(4)) && $now->lessThanOrEqualTo(Carbon::parse('23:59:59'))) {
            if ($presence->check_out) {
                return response()->json(['error' => 'Presensi pulang sudah dilakukan hari ini.'], 422);
            }
            $presence->check_out = $timeNow;
            $presence->check_out_type = 'Lembur Malam';
            $type = 'check_out';
        }

        // ===============================
        // 🔹 DILUAR JADWAL
        // ===============================
        else {
            return response()->json(['error' => 'Scan QR hanya bisa dilakukan dalam rentang waktu presensi. Jika anda lupa, laporkan ke petugas presensi.'], 422);
        }

        $presence->save();

        return response()->json([
            'success' => true,
            'worker' => $worker->name,
            'code' => $worker->code ?? null,
            'photo' => $worker->photo ?? '/default.jpg',
            'type' => $type,
            'time' => $timeNow,
        ]);
    }




    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
