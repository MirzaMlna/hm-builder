<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tukang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <!-- Card Statistik -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Jumlah Tukang</p>
                        <p class="text-2xl font-bold">{{ $totalWorkers }}</p>
                    </div>
                    <i class="bi bi-people text-3xl text-blue-600"></i>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Tukang Aktif</p>
                        <p class="text-2xl font-bold">{{ $activeWorkers }}</p>
                    </div>
                    <i class="bi bi-check-circle text-3xl text-green-600"></i>
                </div>
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Gaji Harian</p>
                        <p class="text-2xl font-bold">Rp{{ number_format($totalDailySalary, 0, ',', '.') }}</p>
                    </div>
                    <i class="bi bi-wallet2 text-3xl text-orange-600"></i>
                </div>
            </div>

            <!-- Tabel Tukang -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Daftar Tukang HM Company</h3>
                        <a href="{{ route('workers.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Tambah Tukang
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border text-sm">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-4 py-2 border">No</th>
                                    <th class="px-4 py-2 border">Nama</th>
                                    <th class="px-4 py-2 border">Kode</th>
                                    <th class="px-4 py-2 border">Gaji (Rp.)</th>
                                    <th class="px-4 py-2 border">No. Telepon</th>
                                    <th class="px-4 py-2 border">Usia</th>
                                    <th class="px-4 py-2 border">ID Card</th>
                                    <th class="px-4 py-2 border">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($workers as $index => $worker)
                                    <tr>
                                        <td class="px-4 py-2 border">{{ $workers->firstItem() + $index }}</td>
                                        <td class="px-4 py-2 border">{{ $worker->name }}</td>
                                        <td class="px-4 py-2 border">{{ $worker->code }}</td>
                                        <td class="px-4 py-2 border">
                                            {{ number_format($worker->daily_salary, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2 border">{{ $worker->phone }}</td>
                                        <td class="px-4 py-2 border">
                                            @if ($worker->birth_date)
                                                {{ \Carbon\Carbon::parse($worker->birth_date)->age }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 border">
                                            <a href="#" class="text-blue-600 hover:underline">Cetak</a>
                                        </td>
                                        <td class="px-4 py-2 border flex gap-2">
                                            <a href="{{ route('workers.show', $worker->id) }}"
                                                class="text-gray-600 hover:text-gray-900"><i class="bi bi-eye"></i></a>
                                            <a href="{{ route('workers.edit', $worker->id) }}"
                                                class="text-blue-600 hover:text-blue-900"><i
                                                    class="bi bi-pencil"></i></a>
                                            <form action="{{ route('workers.destroy', $worker->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus data ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $workers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
