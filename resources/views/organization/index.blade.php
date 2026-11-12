@extends('layouts.app')

@section('content')
<div class="space-y-6" x-data="{
    tab: 'chart',
    modalDept: false,
    modalPos: false,
    editDeptModal: false,
    editPosModal: false,
    editDept: { id: '', code: '', name: '', manager_name: '', parent_id: '', description: '' },
    editPos: { id: '', code: '', title: '', department_id: '', level: '', description: '' },
    openEditDept(data) {
        this.editDept = data;
        this.editDeptModal = true;
    },
    openEditPos(data) {
        this.editPos = data;
        this.editPosModal = true;
    }
}">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Struktur Organisasi & Master Jabatan</h2>
            <p class="text-xs text-slate-500 mt-0.5">Hierarki unit kerja, bagan organisasi interaktif, dan pemetaan posisi jabatan.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <div class="flex items-center bg-slate-100 p-1 rounded-xl text-slate-500">
                <button @click="tab = 'chart'" :class="tab === 'chart' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'hover:text-slate-900'" class="px-3 py-1 text-xs rounded-lg transition">
                    Bagan Organisasi
                </button>
                <button @click="tab = 'departments'" :class="tab === 'departments' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'hover:text-slate-900'" class="px-3 py-1 text-xs rounded-lg transition">
                    Master Unit Kerja ({{ $departments->count() }})
                </button>
                <button @click="tab = 'positions'" :class="tab === 'positions' ? 'bg-white text-slate-900 font-bold shadow-xs' : 'hover:text-slate-900'" class="px-3 py-1 text-xs rounded-lg transition">
                    Master Jabatan ({{ $positions->count() }})
                </button>
            </div>
        </div>
    </div>

    <!-- TAB 1: INTERACTIVE ORGANIZATION CHART -->
    <div x-show="tab === 'chart'" class="space-y-6">
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-xs overflow-x-auto">
            <div class="min-w-[850px] flex flex-col items-center">
                <!-- Root Pimpinan -->
                @php $root = $departments->firstWhere('parent_id', null); @endphp
                @if($root)
                    <div class="flex flex-col items-center">
                        <div class="p-5 rounded-3xl bg-gradient-to-r from-emerald-700 to-teal-800 text-white shadow-lg shadow-emerald-900/10 text-center max-w-sm w-80 relative group hover:scale-[1.02] transition">
                            <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded-full bg-white/20 text-emerald-100">
                                Pimpinan Utama
                            </span>
                            <h4 class="text-base font-extrabold mt-1.5">{{ $root->name }}</h4>
                            <p class="text-xs text-emerald-200 mt-0.5 font-semibold">{{ $root->manager_name }}</p>
                            <div class="mt-3 pt-3 border-t border-white/20 flex items-center justify-between text-xs text-emerald-100">
                                <span>{{ $root->employees_count }} Anggota</span>
                                <a href="{{ route('employees.index', ['department_id' => $root->id]) }}" class="font-bold underline hover:text-white">
                                    Daftar Pegawai &rarr;
                                </a>
                            </div>
                        </div>

                        <!-- Connector Line -->
                        <div class="w-0.5 h-8 bg-emerald-300"></div>
                        <div class="w-3/4 h-0.5 bg-emerald-200"></div>
                        <div class="w-3/4 flex justify-between">
                            @foreach($root->children as $child)
                                <div class="w-0.5 h-6 bg-emerald-200"></div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Level 2 Sub-Units -->
                    <div class="grid grid-cols-3 gap-6 w-full max-w-4xl mt-1">
                        @foreach($root->children as $sub)
                            <div class="p-5 rounded-3xl bg-white border-2 border-emerald-100 hover:border-emerald-500 shadow-xs hover:shadow-md transition text-center flex flex-col justify-between group">
                                <div>
                                    <span class="text-[10px] font-mono font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">
                                        {{ $sub->code }}
                                    </span>
                                    <h4 class="text-sm font-extrabold text-slate-900 mt-2">{{ $sub->name }}</h4>
                                    <p class="text-xs font-semibold text-emerald-700 mt-1">{{ $sub->manager_name }}</p>
                                    <p class="text-[11px] text-slate-500 mt-2 line-clamp-2 leading-relaxed">
                                        {{ $sub->description }}
                                    </p>
                                </div>

                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-700">{{ $sub->employees_count }} Pegawai</span>
                                    <a href="{{ route('employees.index', ['department_id' => $sub->id]) }}" class="font-bold text-emerald-600 hover:text-emerald-800">
                                        Lihat Tim &rarr;
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- TAB 2: MASTER UNIT KERJA (DEPARTMENTS) -->
    <div x-show="tab === 'departments'" x-cloak class="space-y-4">
        <div class="flex justify-end">
            <button @click="modalDept = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Unit Kerja Baru</span>
            </button>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase text-[10px]">
                        <th class="py-3 px-4">Kode</th>
                        <th class="py-3 px-4">Nama Unit Kerja</th>
                        <th class="py-3 px-4">Penanggung Jawab / Pimpinan</th>
                        <th class="py-3 px-4">Unit Induk</th>
                        <th class="py-3 px-4">Jumlah Pegawai</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($departments as $d)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4 font-mono font-bold text-emerald-700">{{ $d->code }}</td>
                            <td class="py-3 px-4">
                                <span class="font-bold text-slate-900 block">{{ $d->name }}</span>
                                <span class="text-[11px] text-slate-400 block max-w-xs truncate">{{ $d->description }}</span>
                            </td>
                            <td class="py-3 px-4 font-medium text-slate-800">{{ $d->manager_name ?? '-' }}</td>
                            <td class="py-3 px-4 text-slate-500">{{ $d->parent?->name ?? 'Puncak Hierarki (Root)' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800">
                                    {{ $d->employees_count }} Orang
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button type="button" @click="openEditDept({{ json_encode(['id' => $d->id, 'code' => $d->code, 'name' => $d->name, 'manager_name' => $d->manager_name ?? '', 'parent_id' => $d->parent_id ?? '', 'description' => $d->description ?? '']) }})" class="p-1.5 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg text-slate-500 transition" title="Edit Unit">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('organization.departments.destroy', $d) }}" method="POST" onsubmit="return confirm('Hapus unit kerja {{ $d->name }}?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 rounded-lg text-slate-400 transition" title="Hapus Unit">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB 3: MASTER JABATAN (JOB POSITIONS) -->
    <div x-show="tab === 'positions'" x-cloak class="space-y-4">
        <div class="flex justify-end">
            <button @click="modalPos = true" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-xs">
                <i data-lucide="plus" class="w-4 h-4"></i>
                <span>Tambah Jabatan Baru</span>
            </button>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-xs overflow-hidden">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase text-[10px]">
                        <th class="py-3 px-4">Kode</th>
                        <th class="py-3 px-4">Nama Jabatan</th>
                        <th class="py-3 px-4">Unit Kerja</th>
                        <th class="py-3 px-4">Tingkat / Level</th>
                        <th class="py-3 px-4">Jumlah Pegawai</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($positions as $pos)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3 px-4 font-mono font-bold text-emerald-700">{{ $pos->code }}</td>
                            <td class="py-3 px-4 font-bold text-slate-900">{{ $pos->title }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $pos->department?->name ?? 'Semua Unit' }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $pos->level === 'Pimpinan' ? 'bg-amber-100 text-amber-800' : ($pos->level === 'Koordinator' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700') }}">
                                    {{ $pos->level }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-bold text-emerald-700">{{ $pos->employees_count }} Orang</td>
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    <button type="button" @click="openEditPos({{ json_encode(['id' => $pos->id, 'code' => $pos->code, 'title' => $pos->title, 'department_id' => $pos->department_id, 'level' => $pos->level, 'description' => $pos->description ?? '']) }})" class="p-1.5 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 rounded-lg text-slate-500 transition" title="Edit Jabatan">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </button>
                                    <form action="{{ route('organization.positions.destroy', $pos) }}" method="POST" onsubmit="return confirm('Hapus jabatan {{ $pos->title }}?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-slate-100 hover:bg-rose-50 hover:text-rose-600 rounded-lg text-slate-400 transition" title="Hapus Jabatan">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL 1: TAMBAH UNIT KERJA -->
    <div x-show="modalDept" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" @click.outside="modalDept = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Tambah Unit Kerja Baru</h3>
                <button @click="modalDept = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form action="{{ route('organization.departments.store') }}" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode Unit Kerja</label>
                    <input type="text" name="code" placeholder="Contoh: LOG-01" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Unit Kerja</label>
                    <input type="text" name="name" placeholder="Nama seksi atau subbag..." required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pimpinan / Koordinator</label>
                    <input type="text" name="manager_name" placeholder="Nama pejabat penanggung jawab..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unit Induk (Parent)</label>
                    <select name="parent_id" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <option value="">Tidak Ada (Puncak Hirarki)</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Uraian Tugas / Deskripsi</label>
                    <textarea name="description" rows="2" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="modalDept = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs">Simpan Unit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: EDIT UNIT KERJA -->
    <div x-show="editDeptModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" @click.outside="editDeptModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Edit Unit Kerja</h3>
                <button @click="editDeptModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form :action="'{{ url('organization/departments') }}/' + editDept.id" method="POST" class="space-y-4 mt-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode Unit Kerja</label>
                    <input type="text" name="code" x-model="editDept.code" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Unit Kerja</label>
                    <input type="text" name="name" x-model="editDept.name" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Pimpinan / Koordinator</label>
                    <input type="text" name="manager_name" x-model="editDept.manager_name" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unit Induk (Parent)</label>
                    <select name="parent_id" x-model="editDept.parent_id" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <option value="">Tidak Ada (Puncak Hierarki)</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Uraian Tugas / Deskripsi</label>
                    <textarea name="description" x-model="editDept.description" rows="2" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="editDeptModal = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 3: TAMBAH JABATAN -->
    <div x-show="modalPos" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" @click.outside="modalPos = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Tambah Master Jabatan</h3>
                <button @click="modalPos = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form action="{{ route('organization.positions.store') }}" method="POST" class="space-y-4 mt-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode Jabatan</label>
                    <input type="text" name="code" placeholder="Contoh: JAB-20" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Jabatan</label>
                    <input type="text" name="title" placeholder="Contoh: Pengelola Logistik Katering" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unit Kerja Terkait</label>
                    <select name="department_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Level Jabatan</label>
                    <select name="level" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <option value="Staff">Staff</option>
                        <option value="Staff Pelaksana">Staff Pelaksana</option>
                        <option value="Koordinator">Koordinator</option>
                        <option value="Teknisi">Teknisi</option>
                        <option value="Pimpinan">Pimpinan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Tugas (Opsional)</label>
                    <textarea name="description" rows="2" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="modalPos = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs">Simpan Jabatan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 4: EDIT JABATAN -->
    <div x-show="editPosModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100" @click.outside="editPosModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900">Edit Master Jabatan</h3>
                <button @click="editPosModal = false" class="text-slate-400 hover:text-slate-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form :action="'{{ url('organization/positions') }}/' + editPos.id" method="POST" class="space-y-4 mt-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kode Jabatan</label>
                    <input type="text" name="code" x-model="editPos.code" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Judul Jabatan</label>
                    <input type="text" name="title" x-model="editPos.title" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Unit Kerja Terkait</label>
                    <select name="department_id" x-model="editPos.department_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Level Jabatan</label>
                    <select name="level" x-model="editPos.level" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs">
                        <option value="Staff">Staff</option>
                        <option value="Staff Pelaksana">Staff Pelaksana</option>
                        <option value="Koordinator">Koordinator</option>
                        <option value="Teknisi">Teknisi</option>
                        <option value="Pimpinan">Pimpinan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Tugas (Opsional)</label>
                    <textarea name="description" x-model="editPos.description" rows="2" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs"></textarea>
                </div>
                <div class="flex items-center gap-2 pt-2">
                    <button type="button" @click="editPosModal = false" class="flex-1 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                    <button type="submit" class="flex-1 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
