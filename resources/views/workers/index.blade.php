<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tukang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class=" mx-auto sm:px-6 lg:px-8">
            <!-- Card Statistik -->
            <div class="py-6">
                <div class="mx-auto sm:px-4 lg:px-8">
                    <!-- Enhanced Stat Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                        <!-- Total Workers -->
                        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-blue-500">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Tukang</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $totalWorkers }}</p>
                                </div>
                                <div class="bg-blue-100 p-3 rounded-full">
                                    <i class="bi bi-people text-blue-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Active Workers -->
                        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-green-500">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Tukang Aktif</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $activeWorkers }}</p>
                                </div>
                                <div class="bg-green-100 p-3 rounded-full">
                                    <i class="bi bi-check-circle text-green-600 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Daily Salary -->
                        <div class="bg-white rounded-lg shadow-md p-5 border-l-4 border-amber-500">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Total Gaji Harian</p>
                                    <p class="text-2xl font-bold text-gray-800">
                                        Rp{{ number_format($totalDailySalary, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="bg-amber-100 p-3 rounded-full">
                                    <i class="bi bi-wallet2 text-amber-600 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Tabel Tukang -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold">Daftar Tukang HM Company</h3>
                                <a href="{{ route('workers.create') }}"
                                    class="bg-sky-800 hover:bg-sky-700 text-white px-4 py-2 rounded">
                                    Tambah Tukang
                                </a>
                            </div>

                            <div class="overflow-x-auto rounded-lg overflow-hidden border border-gray-200">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-sky-800 text-white">
                                        <tr>
                                            <th class="px-4 py-2 ">NO</th>
                                            <th class="px-4 py-2 ">NAMA</th>
                                            <th class="px-4 py-2 ">KODE</th>
                                            <th class="px-4 py-2 ">GAJI HARIAN (Rp.)</th>
                                            <th class="px-4 py-2 ">NO. TELP</th>
                                            <th class="px-4 py-2 ">USIA</th>
                                            <th class="px-4 py-2 ">AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($workers as $index => $worker)
                                            <tr class="text-center">
                                                <td class="px-4 py-2 ">{{ $workers->firstItem() + $index }}</td>
                                                <td class="px-4 py-2 ">{{ $worker->name }}</td>
                                                <td class="px-4 py-2 ">{{ $worker->code }}</td>
                                                <td class="px-4 py-2 ">
                                                    {{ number_format($worker->daily_salary, 0, ',', '.') }}</td>
                                                <td class="px-4 py-2 ">{{ $worker->phone }}</td>
                                                <td class="px-4 py-2 ">
                                                    @if ($worker->birth_date)
                                                        {{ \Carbon\Carbon::parse($worker->birth_date)->age }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>

                                                <td class="px-4 py-2  flex gap-2 justify-center">
                                                    <a href="{{ route('workers.show', $worker->id) }}"
                                                        class="text-blue-600 hover:text-blue-900"><i
                                                            class="bi bi-eye"></i></a>
                                                    <a href="{{ route('workers.edit', $worker->id) }}"
                                                        class="text-yellow-600 hover:text-yellow-900"><i
                                                            class="bi bi-pencil"></i></a>
                                                    <form action="{{ route('workers.destroy', $worker->id) }}"
                                                        method="POST" onsubmit="return confirm('Hapus data ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900"><i
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
