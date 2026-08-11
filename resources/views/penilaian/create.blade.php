@extends('layouts.app')
@section('title', 'Buat Penilaian Kinerja')

@section('content')
<div class="space-y-6 max-w-6xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-star-fill text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Input Penilaian Kinerja KPI</h2>
                <p class="text-xs text-gray-500">Evaluasi dan skor kinerja bulanan karyawan</p>
            </div>
        </div>
        <x-button variant="secondary" href="{{ route('penilaian.index') }}">
            <i class="bi bi-arrow-left"></i> Kembali
        </x-button>
    </div>

    <div class="card p-6 md:p-8">
        <form action="{{ route('penilaian.store') }}" method="POST" class="space-y-8">
            @csrf
            
            <!-- Section 1: Informasi Karyawan & Periode -->
            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <i class="bi bi-person-check text-indigo-500"></i> Informasi Karyawan & Periode Evaluasi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
                        <label class="label">Periode Evaluasi</label>
                        <x-input type="month" name="periode" value="{{ old('periode', date('Y-m')) }}" required />
                    </div>

                    <div>
                        <label class="label">Tanggal Penilaian</label>
                        <x-input type="date" name="tanggal_penilaian" value="{{ old('tanggal_penilaian', date('Y-m-d')) }}" required />
                    </div>
                </div>
            </div>

            <!-- Section 2: Kriteria Penilaian (0-100) -->
            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200 flex items-center gap-2">
                    <i class="bi bi-sliders text-indigo-500"></i> Kriteria Penilaian KPI (Skala 0 - 100)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @php 
                        $kriteria = [
                            'nilai_disiplin' => 'Kedisiplinan',
                            'nilai_kualitas' => 'Kualitas Pekerjaan',
                            'nilai_kuantitas' => 'Kuantitas Pekerjaan',
                            'nilai_tanggung_jawab' => 'Tanggung Jawab',
                            'nilai_kerjasama' => 'Kerja Sama Team',
                            'nilai_inisiatif' => 'Inisiatif & Kreativitas',
                            'nilai_ketepatan_waktu' => 'Ketepatan Waktu',
                            'nilai_target' => 'Pencapaian Target'
                        ]; 
                    @endphp
                    @foreach($kriteria as $field => $label)
                        <div>
                            <label class="label">{{ $label }}</label>
                            <x-input type="number" name="{{ $field }}" value="{{ old($field, 80) }}" min="0" max="100" step="0.01" required placeholder="0-100" />
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 3: Catatan evaluasi -->
            <div>
                <label class="label">Catatan Evaluasi Manager / Evaluator</label>
                <textarea name="catatan" rows="3" placeholder="Masukan dan evaluasi untuk karyawan..." class="input">{{ old('catatan') }}</textarea>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <x-button variant="ghost" href="{{ route('penilaian.index') }}">Batal</x-button>
                <x-button variant="default" type="submit">
                    <i class="bi bi-check2-circle"></i> Simpan Penilaian
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection

