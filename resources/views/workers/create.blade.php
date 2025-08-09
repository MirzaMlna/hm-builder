<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Tukang
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('workers.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label>Kode</label>
                            <input type="text" name="code" value="{{ old('code') }}"
                                class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label>Nama</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label>No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label>Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                                class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label>Gaji Harian</label>
                            <input type="number" name="daily_salary" value="{{ old('daily_salary') }}"
                                class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label>Status Aktif</label>
                            <select name="is_active" class="w-full border rounded p-2">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label>Alamat</label>
                            <textarea name="address" class="w-full border rounded p-2">{{ old('address') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label>Catatan</label>
                            <textarea name="note" class="w-full border rounded p-2">{{ old('note') }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end gap-2">
                        <a href="{{ route('workers.index') }}" class="bg-gray-300 px-4 py-2 rounded">Batal</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
