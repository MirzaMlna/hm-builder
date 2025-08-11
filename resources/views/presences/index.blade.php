<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan QR Presensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Baris Pertama: Pengaturan + Kamera --}}
            <div class="flex flex-col md:flex-row md:space-x-6 space-y-6 md:space-y-0">
                {{-- Pengaturan Rentang Waktu (Kiri) --}}
                <div class="bg-white shadow-sm rounded-lg p-6 basis-1/3">
                    <h3 class="font-semibold mb-2 flex items-center">
                        <i class="bi bi-clock-history mr-2"></i> Pengaturan Rentang Waktu Presensi
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </p>
                    <form id="timeSettingsForm" class="space-y-4">
                        {{-- Check In 1 --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="font-medium">Check-In 1</label>
                            <input type="time" name="check_in1_start" class="border rounded p-2 w-full">
                            <input type="time" name="check_in1_end" class="border rounded p-2 w-full">
                        </div>
                        {{-- Check In 2 --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="font-medium">Check-In 2</label>
                            <input type="time" name="check_in2_start" class="border rounded p-2 w-full">
                            <input type="time" name="check_in2_end" class="border rounded p-2 w-full">
                        </div>
                        {{-- Check Out --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                            <label class="font-medium">Check-Out</label>
                            <input type="time" name="check_out_start" class="border rounded p-2 w-full">
                            <input type="time" name="check_out_end" class="border rounded p-2 w-full">
                        </div>
                        <hr class="my-4">
                        {{-- Konfirmasi teks --}}
                        <div>
                            <label class="font-medium block mb-2">Konfirmasi</label>
                            <input type="text" id="confirmationText"
                                placeholder='Ketik "HM BUILDERS" untuk menyimpan' class="border rounded p-2 w-full">
                            <p class="text-xs text-gray-500 mt-1">* Wajib ketik persis "HM BUILDERS" (huruf besar semua)
                            </p>
                        </div>
                        {{-- Tombol Submit --}}
                        <button type="submit" id="saveBtn"
                            class="bg-blue-900 hover:bg-blue-800 text-white px-6 py-2 rounded mt-4 disabled:opacity-50 disabled:cursor-not-allowed"
                            disabled>
                            Simpan Pengaturan
                        </button>
                    </form>
                </div>

                {{-- Kamera (Kanan) --}}
                <div class="bg-white shadow-sm rounded-lg p-6 basis-2/3 flex flex-col">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Kotak preview kamera --}}
                        <div
                            class="border border-gray-200 rounded-lg w-full aspect-square mx-auto relative overflow-hidden flex items-center justify-center text-gray-500">
                            <video id="previewVideo" class="w-full h-full object-cover hidden" autoplay muted
                                playsinline></video>
                            <div id="placeholder" class="flex flex-col items-center justify-center absolute inset-0">
                                <i class="bi bi-camera text-4xl mb-2"></i>
                                <p class="font-medium">Pratinjau Kamera</p>
                                <span class="text-sm">Tekan "Mulai Memindai" untuk mulai</span>
                            </div>
                        </div>

                        {{-- Box panduan --}}
                        <div class="bg-blue-50 p-3 rounded text-sm text-gray-700">
                            <p class="font-semibold mb-1">Cara Penggunaan:</p>
                            <ul class="list-disc ml-5 space-y-1">
                                <li>Posisikan kode QR di dalam bingkai pemindaian</li>
                                <li>Pastikan pencahayaan yang baik untuk pemindaian optimal</li>
                                <li>Jaga kamera tetap stabil hingga selesai</li>
                                <li>Jika berhasil, data absensi akan muncul di bawah</li>
                            </ul>
                            <div class="mt-4 flex space-x-2">
                                <button id="startScanBtn"
                                    class="bg-blue-900 hover:bg-blue-800 text-white px-6 py-2 rounded">
                                    Mulai Memindai
                                </button>
                                <button id="stopScanBtn"
                                    class="bg-red-600 hover:bg-red-500 text-white px-6 py-2 rounded hidden">
                                    Tutup Kamera
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Baris Kedua: Hasil Scan --}}
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="font-semibold mb-4 flex items-center">
                    <i class="bi bi-qr-code mr-2"></i> Hasil Scan
                </h3>
                <div class="flex items-center space-x-3">
                    <img src="/pekerja.jpg" alt="Foto" class="h-12 w-12 rounded-full object-cover">
                    <div class="flex-1">
                        <p class="font-bold">TKG010</p>
                        <p class="text-gray-600">Muhammad Palui</p>
                        <span class="text-green-600 text-sm">Tepat Waktu</span>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg">08.10</p>
                        <p class="text-gray-500 text-sm">WITA</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Kamera & Form --}}
    <script>
        const startScanBtn = document.getElementById('startScanBtn');
        const stopScanBtn = document.getElementById('stopScanBtn');
        const video = document.getElementById('previewVideo');
        const placeholder = document.getElementById('placeholder');
        let cameraStream = null;

        startScanBtn.addEventListener('click', async () => {
            try {
                cameraStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "environment"
                    },
                    audio: false
                });
                video.srcObject = cameraStream;
                video.classList.remove('hidden');
                placeholder.style.display = 'none';
                startScanBtn.disabled = true;
                startScanBtn.textContent = "Memindai...";
                stopScanBtn.classList.remove('hidden');
            } catch (err) {
                alert('Gagal mengakses kamera: ' + err.message);
            }
        });

        stopScanBtn.addEventListener('click', () => {
            if (cameraStream) {
                cameraStream.getTracks().forEach(track => track.stop());
                cameraStream = null;
            }
            video.srcObject = null;
            video.classList.add('hidden');
            placeholder.style.display = 'flex';
            startScanBtn.disabled = false;
            startScanBtn.textContent = "Mulai Memindai";
            stopScanBtn.classList.add('hidden');
        });

        const confirmationText = document.getElementById('confirmationText');
        const saveBtn = document.getElementById('saveBtn');

        confirmationText.addEventListener('input', () => {
            saveBtn.disabled = confirmationText.value.trim() !== "HM BUILDERS";
        });

        document.getElementById('timeSettingsForm').addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Pengaturan berhasil disimpan!');
        });
    </script>
</x-app-layout>
