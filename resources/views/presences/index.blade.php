<x-app-layout>
    <style>
        /* Mirror kamera agar tampak seperti selfie */
        #qr-reader video {
            transform: scaleX(-1);
            -webkit-transform: scaleX(-1);
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan QR Presensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Kartu Jadwal Presensi -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-blue-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Presensi Pertama</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($presence_schedules->first_check_in_start)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($presence_schedules->first_check_in_end)->format('H:i') }}
                            </p>
                        </div>

                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-green-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Presensi Kedua</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($presence_schedules->second_check_in_start)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($presence_schedules->second_check_in_end)->format('H:i') }}
                            </p>
                        </div>

                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-amber-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Presensi Pulang</p>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($presence_schedules->check_out_start)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($presence_schedules->check_out_end)->format('H:i') }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Konten Kiri (Kamera) & Kanan (Hasil Scan) -->
            <div class="flex flex-col md:flex-row md:space-x-6 space-y-6 md:space-y-0">

                <!-- KIRI: Kamera -->
                <div class="bg-white shadow-sm rounded-lg p-6 basis-1/3 flex flex-col">
                    <!-- Waktu Presensi Otomatis -->
                    <div class="mt-4 mb-5">
                        <div class="bg-gray-100 rounded-lg p-4 text-center">
                            <span class="font-semibold">Waktu & Tanggal Saat Ini</span><br>
                            <span id="currentDateTime" class="text-lg"></span>
                        </div>
                    </div>
                    <script>
                        function updateDateTime() {
                            const now = new Date();
                            const options = {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            };
                            const dateStr = now.toLocaleDateString('id-ID', options);
                            const timeStr = now.toLocaleTimeString('id-ID', {
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit'
                            });
                            document.getElementById('currentDateTime').textContent = `${dateStr} - ${timeStr}`;
                        }
                        updateDateTime();
                        setInterval(updateDateTime, 1000);
                    </script>

                    <div
                        class="border border-gray-200 rounded-lg w-full aspect-square mx-auto relative overflow-hidden flex items-center justify-center text-gray-500">
                        <div id="qr-reader" style="width:100%;"></div>
                    </div>
                </div>

                <!-- KANAN: Hasil Scan -->
                <div class="bg-white shadow-sm rounded-lg p-6 basis-2/3">
                    <h3 class="font-semibold mb-4 flex items-center">
                        <i class="bi bi-qr-code mr-2"></i> Hasil Scan
                    </h3>

                    <!-- Filter Tanggal -->
                    <form method="GET" action="{{ route('presences.index') }}"
                        class="mb-4 flex items-center space-x-2">
                        <input type="date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}"
                            class="border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400">
                        <button type="submit" class="bg-sky-800 text-white px-4 py-2 rounded-lg hover:bg-sky-700">
                            Pilih Tanggal
                        </button>
                    </form>

                    <div class="mt-4">
                        <h3 class="mb-2">
                            Hasil Scan - <span class="font-bold">
                                {{ \Carbon\Carbon::parse(request('date', now()))->isoFormat('dddd, D MMMM Y') }}
                            </span>
                        </h3>
                        <div class="rounded-lg overflow-hidden border border-gray-300">
                            <table class="w-full text-sm">
                                <thead class="bg-sky-800">
                                    <tr class="text-white">
                                        <th class="p-2 text-center">No</th>
                                        <th class="p-2">Nama</th>
                                        <th class="p-2 text-center">Kode</th>
                                        <th class="p-2 text-center">Presensi 1</th>
                                        <th class="p-2 text-center">Presensi 2</th>
                                        <th class="p-2 text-center">Presensi Pulang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($presences as $index => $presence)
                                        <tr class="odd:bg-white even:bg-gray-50">
                                            <td class="p-2 text-center">
                                                {{ ($presences->currentPage() - 1) * $presences->perPage() + $loop->iteration }}
                                            </td>
                                            <td class="p-2">{{ $presence->worker->name }}</td>
                                            <td class="p-2 text-center">{{ $presence->worker->code }}</td>
                                            <td class="p-2 text-center">{{ $presence->first_check_in ?? '-' }}</td>
                                            <td class="p-2 text-center">{{ $presence->second_check_in ?? '-' }}</td>
                                            <td class="p-2 text-center">{{ $presence->check_out ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="p-2 text-center">
                                                Belum ada data presensi pada tanggal ini
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $presences->appends(['date' => request('date')])->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HTML5-Qrcode CDN -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const presensiType = document.getElementById('presensiType');
        let scanned = false;

        function getAttendanceType(schedule) {
            const now = new Date();
            const today = now.toISOString().slice(0, 10);
            // Helper to parse time string to Date object for today
            function parseTime(timeStr) {
                const [h, m, s] = timeStr.split(":");
                return new Date(today + "T" + h.padStart(2, "0") + ":" + m.padStart(2, "0") + ":" + (s ? s.padStart(2,
                    "0") : "00"));
            }
            // Compare now with schedule
            if (now >= parseTime(schedule.first_check_in_start) && now <= parseTime(schedule.first_check_in_end)) {
                return {
                    type: "first_check_in",
                    label: "Presensi Pertama"
                };
            } else if (now >= parseTime(schedule.second_check_in_start) && now <= parseTime(schedule.second_check_in_end)) {
                return {
                    type: "second_check_in",
                    label: "Presensi Kedua"
                };
            } else if (now >= parseTime(schedule.check_out_start) && now <= parseTime(schedule.check_out_end)) {
                return {
                    type: "check_out",
                    label: "Presensi Pulang"
                };
            } else {
                return {
                    type: null,
                    label: "Di luar rentang presensi"
                };
            }
        }

        // Jadwal presensi dari backend
        const schedule = {
            first_check_in_start: "{{ $presence_schedules->first_check_in_start }}",
            first_check_in_end: "{{ $presence_schedules->first_check_in_end }}",
            second_check_in_start: "{{ $presence_schedules->second_check_in_start }}",
            second_check_in_end: "{{ $presence_schedules->second_check_in_end }}",
            check_out_start: "{{ $presence_schedules->check_out_start }}",
            check_out_end: "{{ $presence_schedules->check_out_end }}"
        };

        function onScanSuccess(decodedText) {
            if (scanned) return;
            scanned = true;
            const attendance = getAttendanceType(schedule);
            if (!attendance.type) {
                Swal.fire("Gagal", "Scan QR hanya bisa dilakukan dalam rentang waktu presensi.", "error");
                setTimeout(() => scanned = false, 3000);
                return;
            }
            fetch("{{ route('presences.scan') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        qr: decodedText,
                        type: attendance.type
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: "Presensi Berhasil",
                            html: `
                            <p><b>Jenis Presensi:</b> ${attendance.label}</p>
                            <img src="${data.photo}" alt="Foto" style="width:80px;height:80px;border-radius:50%;margin-top:10px;">
                            <p><b>Kode:</b> ${data.code}</p>
                            <p><b>Nama:</b> ${data.worker}</p>
                            <p><b>Jam:</b> ${data.time}</p>
                        `,
                            icon: "success"
                        });
                    } else {
                        Swal.fire("Gagal", data.error || "Presensi gagal", "error");
                    }
                })
                .catch(() => {
                    Swal.fire("Error", "Presensi gagal", "error");
                })
                .finally(() => {
                    setTimeout(() => scanned = false, 3000);
                });
        }

        function onScanFailure(error) {
            // Tidak perlu aksi
        }

        document.addEventListener("DOMContentLoaded", () => {
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", {
                    fps: 10,
                    qrbox: 250
                }, false
            );
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });
    </script>
</x-app-layout>
