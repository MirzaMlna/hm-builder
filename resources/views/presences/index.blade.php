<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan QR Presensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
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
                        <div class="bg-blue-100 p-3 rounded-full">
                            <i class="bi bi-people text-blue-600 text-xl"></i>
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
                        <div class="bg-green-100 p-3 rounded-full">
                            <i class="bi bi-check-circle text-green-600 text-xl"></i>
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
                        <div class="bg-amber-100 p-3 rounded-full">
                            <i class="bi bi-wallet2 text-amber-600 text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row md:space-x-6 space-y-6 md:space-y-0">
                {{-- Kamera (Kiri) --}}
                <div class="bg-white shadow-sm rounded-lg p-6 basis-1/2 flex flex-col">
                    <div
                        class="border border-gray-200 rounded-lg w-full aspect-square mx-auto relative overflow-hidden flex items-center justify-center text-gray-500">
                        <div id="qr-reader" style="width:100%;"></div>
                    </div>
                    <div class="mt-4 flex space-x-2">
                        <select id="presensiType" class="border rounded px-2 py-1">
                            <option value="first_check_in">Presensi Pertama</option>
                            <option value="second_check_in">Presensi Kedua</option>
                            <option value="check_out">Presensi Pulang</option>
                        </select>
                    </div>
                </div>

                {{-- Hasil Scan (Kanan) --}}
                <div class="bg-white shadow-sm rounded-lg p-6 basis-1/2">
                    <h3 class="font-semibold mb-4 flex items-center">
                        <i class="bi bi-qr-code mr-2"></i> Hasil Scan
                    </h3>
                    <div id="scanResult" class="flex items-center space-x-3">
                        {{-- <img src="/pekerja.jpg" alt="Foto" class="h-12 w-12 rounded-full object-cover">
                        <div class="flex-1">
                            <p class="font-bold">TKG010</p>
                            <p class="text-gray-600">Muhammad Palui</p>
                            <span class="text-green-600 text-sm">Tepat Waktu</span>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg">08.10</p>
                            <p class="text-gray-500 text-sm">WITA</p>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- HTML5-Qrcode CDN (WAJIB sebelum script scanner) -->
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const presensiType = document.getElementById('presensiType');
        const scanResult = document.getElementById('scanResult');

        let scanned = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (scanned) return; // kalau sudah scan, hentikan

            scanned = true; // tandai sudah scan

            fetch("{{ route('presences.scan') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        qr: decodedText,
                        type: presensiType.value
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: "Presensi Berhasil",
                            html: `
                    <p><b>Jenis Presensi:</b> ${presensiType.options[presensiType.selectedIndex].text}</p>
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
                    setTimeout(() => {
                        scanned = false; // aktifkan lagi setelah 3 detik
                    }, 3000);
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
                },
                false
            );
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });
    </script>


</x-app-layout>
