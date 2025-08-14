<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use App\Models\PresenceSchedule;
use Carbon\Carbon;
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
            ->whereDate('date', $selectedDate)
            ->get();

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
            'type' => 'required|in:first_check_in,second_check_in,check_out',
        ]);

        // Decode QR
        $hashids = new \Hashids\Hashids('', 40);
        $decoded = $hashids->decode($request->qr);
        if (empty($decoded)) {
            return response()->json(['error' => 'QR tidak valid'], 422);
        }
        $workerId = $decoded[0];

        $worker = \App\Models\Worker::find($workerId);
        if (!$worker) {
            return response()->json(['error' => 'Pekerja tidak ditemukan'], 404);
        }
        if (!$worker->is_active) {
            return response()->json(['error' => 'Pekerja sudah nonaktif, tidak bisa presensi.'], 403);
        }

        $schedule = \App\Models\PresenceSchedule::first();
        if (!$schedule) {
            return response()->json(['error' => 'Jadwal presensi belum diatur'], 422);
        }

        $today = now()->format('Y-m-d');
        $presence = \App\Models\Presence::firstOrCreate([
            'worker_id' => $worker->id,
            'date' => $today,
            'presence_schedule_id' => $schedule->id,
        ]);

        // 🚫 Pengecekan apakah sudah presensi untuk tipe ini
        if ($request->type === 'first_check_in' && $presence->first_check_in) {
            return response()->json(['error' => 'Presensi pertama sudah dilakukan hari ini.'], 422);
        }
        if ($request->type === 'second_check_in' && $presence->second_check_in) {
            return response()->json(['error' => 'Presensi kedua sudah dilakukan hari ini.'], 422);
        }
        if ($request->type === 'check_out' && $presence->check_out) {
            return response()->json(['error' => 'Presensi pulang sudah dilakukan hari ini.'], 422);
        }

        // Simpan presensi
        $now = now()->format('H:i:s');
        if ($request->type === 'first_check_in') {
            $presence->first_check_in = $now;
        } elseif ($request->type === 'second_check_in') {
            $presence->second_check_in = $now;
        } elseif ($request->type === 'check_out') {
            $presence->check_out = $now;
        }
        $presence->save();

        return response()->json([
            'success' => true,
            'worker' => $worker->name,
            'code' => $worker->code ?? null, // kalau ada kode pekerja
            'photo' => $worker->photo_url ?? '/default.jpg', // path foto pekerja
            'type' => $request->type,
            'time' => $now,
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
