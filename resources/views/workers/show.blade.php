<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Tukang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                <p><strong>Kode:</strong> {{ $worker->code }}</p>
                <p><strong>Nama:</strong> {{ $worker->name }}</p>
                <p><strong>No. Telepon:</strong> {{ $worker->phone }}</p>
                <p><strong>Tanggal Lahir:</strong> {{ $worker->birth_date }}</p>
                <p><strong>Usia:</strong>
                    {{ $worker->birth_date ? \Carbon\Carbon::parse($worker->birth_date)->age : '-' }}</p>
                <p><strong>Gaji Harian:</strong> Rp{{ number_format($worker->daily_salary, 0, ',', '.') }}</p>
                <p><strong>Status:</strong> {{ $worker->is_active ? 'Aktif' : 'Tidak Aktif' }}</p>
                <p><strong>Alamat:</strong> {{ $worker->address }}</p>
                <p><strong>Catatan:</strong> {{ $worker->note }}</p>

                <div class="flex gap-2 mt-4">
                    <a href="{{ route('workers.edit', $worker->id) }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>
                    <a href="{{ route('workers.index') }}" class="bg-gray-300 px-4 py-2 rounded">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
