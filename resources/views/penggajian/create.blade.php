@extends('layouts.app')
@section('title', 'Hitung Gaji Karyawan')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-calculator text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Hitung Penggajian Karyawan</h2>
                <p class="text-xs text-gray-500">Proses perhitungan gaji pokok, tunjangan, dan potongan periode</p>
            </div>
        </div>
        <x-button variant="secondary" href="{{ route('penggajian.index') }}">
            <i class="bi bi-arrow-left"></i> Kembali
        </x-button>
    </div>

    <div class="card p-6 md:p-8">
        <form action="{{ route('penggajian.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="label">Pilih Karyawan</label>
                    <select name="karyawan_id" required class="input">
                        <option value="">- Pilih Karyawan -</option>
                        @foreach($karyawan as $k)
                            <option value="{{ $k->id }}" {{ old('karyawan_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nip }} &mdash; {{ $k->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="label">Periode Gaji (Bulan - Tahun)</label>
                    <x-input type="month" name="periode" value="{{ old('periode', date('Y-m')) }}" required />
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <x-button variant="ghost" href="{{ route('penggajian.index') }}">Batal</x-button>
                <x-button variant="default" type="submit">
                    <i class="bi bi-calculator"></i> Hitung & Simpan Gaji
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection

