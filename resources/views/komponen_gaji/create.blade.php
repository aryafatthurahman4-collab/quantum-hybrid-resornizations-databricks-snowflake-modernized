@extends('layouts.app')
@section('title', 'Tambah Komponen Gaji')

@section('content')
<div class="space-y-6 max-w-5xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="p-2.5 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                <i class="bi bi-wallet2 text-xl"></i>
            </span>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-900">Tambah Komponen Gaji</h2>
                <p class="text-xs text-gray-500">Tambahkan komponen penghasilan atau potongan baru</p>
            </div>
        </div>
        <x-button variant="secondary" href="{{ route('komponen-gaji.index') }}">
            <i class="bi bi-arrow-left"></i> Kembali
        </x-button>
    </div>

    <div class="card p-6 md:p-8">
        <form action="{{ route('komponen-gaji.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label class="label">Kode Komponen</label>
                    <x-input type="text" name="kode" value="{{ old('kode') }}" maxlength="30" required placeholder="Contoh: TJ-JBT" />
                </div>

                <div>
                    <label class="label">Nama Komponen</label>
                    <x-input type="text" name="nama" value="{{ old('nama') }}" maxlength="100" required placeholder="Contoh: Tunjangan Jabatan" />
                </div>

                <div>
                    <label class="label">Tipe</label>
                    <select name="tipe" required class="input">
                        <option value="penghasilan" {{ old('tipe') == 'penghasilan' ? 'selected' : '' }}>Penghasilan (Earning)</option>
                        <option value="potongan" {{ old('tipe') == 'potongan' ? 'selected' : '' }}>Potongan (Deduction)</option>
                    </select>
                </div>

                <div>
                    <label class="label">Sifat</label>
                    <select name="sifat" required class="input">
                        <option value="tetap" {{ old('sifat') == 'tetap' ? 'selected' : '' }}>Tetap (Fixed)</option>
                        <option value="variable" {{ old('sifat') == 'variable' ? 'selected' : '' }}>Variabel (Variable)</option>
                    </select>
                </div>

                <div>
                    <label class="label">Nilai (Rp)</label>
                    <x-input type="number" name="nilai" value="{{ old('nilai') }}" min="0" required placeholder="0" />
                </div>

                <div class="md:col-span-2 lg:col-span-3">
                    <label class="label">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="3" placeholder="Deskripsi atau rincian komponen..." class="input">{{ old('keterangan') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-200 flex items-center justify-end gap-3">
                <x-button variant="ghost" href="{{ route('komponen-gaji.index') }}">Batal</x-button>
                <x-button variant="default" type="submit">
                    <i class="bi bi-check2-circle"></i> Simpan Data
                </x-button>
            </div>
        </form>
    </div>
</div>
@endsection

