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
                        <video id="previewVideo" class="w-full h-full object-cover hidden" autoplay muted
                            playsinline></video>
                        <div id="placeholder" class="flex flex-col items-center justify-center absolute inset-0">
                            <i class="bi bi-camera text-4xl mb-2"></i>
                            <p class="font-medium">Pratinjau Kamera</p>
                            <span class="text-sm">Tekan "Mulai Memindai" untuk mulai</span>
                        </div>
                    </div>
                    <div class="mt-4 flex space-x-2">
                        <button id="startScanBtn" class="bg-blue-900 hover:bg-blue-800 text-white px-6 py-2 rounded">
                            Mulai Memindai
                        </button>
                        <button id="stopScanBtn"
                            class="bg-red-600 hover:bg-red-500 text-white px-6 py-2 rounded hidden">
                            Tutup Kamera
                        </button>
                    </div>
                </div>

                {{-- Hasil Scan (Kanan) --}}
                <div class="bg-white shadow-sm rounded-lg p-6 basis-1/2">
                    <h3 class="font-semibold mb-4 flex items-center">
                        <i class="bi bi-qr-code mr-2"></i> Hasil Scan
                    </h3>
                    <div id="scanResult" class="flex items-center space-x-3">
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
    </div>

    {{-- Script Kamera --}}
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
    </script>
</x-app-layout>
