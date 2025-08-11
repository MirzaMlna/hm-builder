<?php

namespace App\Http\Controllers;

use App\Models\PresenceSchedule;
use Illuminate\Http\Request;

class PresenceScheduleController extends Controller
{
    public function index()
    {
        $schedule = PresenceSchedule::first();
        return view('presence_schedules.index', compact('schedule'));
    }

    public function storeOrUpdate(Request $request)
    {
        $this->validateTimes($request);

        $schedule = PresenceSchedule::first();

        if ($schedule) {
            $schedule->update($request->only([
                'first_check_in_start',
                'first_check_in_end',
                'second_check_in_start',
                'second_check_in_end',
                'check_out_start',
                'check_out_end'
            ]));
            $message = 'Jadwal absensi berhasil diperbarui.';
        } else {
            PresenceSchedule::create($request->only([
                'first_check_in_start',
                'first_check_in_end',
                'second_check_in_start',
                'second_check_in_end',
                'check_out_start',
                'check_out_end'
            ]));
            $message = 'Jadwal absensi berhasil disimpan.';
        }

        return redirect()->route('presence-schedules.index')->with('success', $message);
    }

    private function validateTimes(Request $request)
    {
        $rules = [
            'first_check_in_start'  => 'required|date_format:H:i',
            'first_check_in_end'    => 'required|date_format:H:i|after:first_check_in_start',
            'second_check_in_start' => 'required|date_format:H:i|after_or_equal:first_check_in_end',
            'second_check_in_end'   => 'required|date_format:H:i|after:second_check_in_start',
            'check_out_start'       => 'required|date_format:H:i|after_or_equal:second_check_in_end',
            'check_out_end'         => 'required|date_format:H:i|after:check_out_start',
        ];

        $messages = [
            'after' => ':attribute harus lebih besar dari waktu sebelumnya.',
            'after_or_equal' => ':attribute harus sama atau lebih besar dari waktu sebelumnya.',
        ];

        $request->validate($rules, $messages);
    }
}
