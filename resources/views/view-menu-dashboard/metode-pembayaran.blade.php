@extends('admin.main')
@section('judul', 'Kelola Metode Pembayaran | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-6 bg-gray-100 min-h-screen mt-6">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-credit-card text-blue-600 mr-3"></i> Metode Pembayaran
            </h1>
            <button onclick="openCreateModal()"
                class="inline-flex items-center px-5 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
                <i class="fas fa-plus mr-2"></i> Tambah Metode
            </button>
        </div>

        <div class="border-t border-gray-300 my-6"></div>

        {{-- Tabel --}}
        <div class="bg-white shadow-lg rounded-lg overflow-hidden border border-gray-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold">#</th>
                            <th class="px-6 py-3 text-left font-semibold">Nama</th>
                            <th class="px-6 py-3 text-left font-semibold">Kode/Nomor</th>
                            <th class="px-6 py-3 text-left font-semibold">Deskripsi</th>
                            <th class="px-6 py-3 text-left font-semibold">QRIS</th>
                            <th class="px-6 py-3 text-left font-semibold">Status</th>
                            <th class="px-6 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($methods as $index => $m)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-medium">{{ $m->name }}</td>
                                <td class="px-6 py-4 text-gray-500">{{ $m->code }}</td>
                                <td class="px-6 py-4">{{ Str::limit($m->description, 50) }}</td>
                                <td class="px-6 py-4">
                                    @if ($m->qris_image_path)
                                        <img src="{{ asset('storage/' . $m->qris_image_path) }}" class="w-12 h-12 rounded">
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($m->is_active)
                                        <span
                                            class="px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">
                                            <i class="fas fa-check-circle"></i> Aktif
                                        </span>
                                    @else
                                        <span
                                            class="px-2 py-1 rounded-full bg-gray-200 text-gray-600 text-xs font-semibold">
                                            <i class="fas fa-times-circle"></i> Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex space-x-2">
                                        <button
                                            onclick="openEditModal({{ $m->id }}, '{{ $m->name }}', '{{ $m->description }}', {{ $m->is_active }})"
                                            class="text-blue-600 hover:text-blue-900"><i class="fas fa-edit"></i></button>
                                        <button onclick="openDeleteModal({{ $m->id }}, '{{ $m->name }}')"
                                            class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada metode pembayaran.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Form --}}
    <div id="methodModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-8">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-800">Tambah Metode</h2>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i
                        class="fas fa-times"></i></button>
            </div>
            <form id="methodForm" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">

                <div class="mb-4">
                    <label class="block text-sm">Nama Metode</label>
                    <input type="text" name="name" id="methodName" oninput="generateCode()"
                        class="w-full rounded-md border-gray-300 shadow-sm" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm">Kode/Nomor</label>
                    <input type="text" name="code" id="methodCode" class="w-full rounded-md border-gray-300 shadow-sm"
                        required>
                    <p class="text-xs text-gray-500">Untuk QRIS otomatis jadi <b>qris</b></p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm">Deskripsi</label>
                    <textarea name="description" id="methodDescription" rows="3" class="w-full rounded-md border-gray-300 shadow-sm"></textarea>
                </div>

                {{-- Khusus QRIS --}}
                <div id="qrisFields" class="hidden">
                    <div class="mb-4">
                        <label class="block text-sm">Upload QRIS (Opsional)</label>
                        <input type="file" name="qris_image" id="qrisImage" accept="image/*" class="w-full text-sm">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm">Status</label>
                    <select name="is_active" id="methodStatus" class="w-full rounded-md border-gray-300 shadow-sm">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-md">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md"><i
                            class="fas fa-save mr-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6">
            <h2 class="text-xl font-bold mb-4">Hapus Metode</h2>
            <p id="deleteText" class="mb-6">Yakin ingin menghapus metode?</p>
            <form id="deleteForm" method="POST">
                @csrf @method('DELETE')
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-md">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md">Hapus</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const methodModal = document.getElementById('methodModal');
        const deleteModal = document.getElementById('deleteModal');
        const methodForm = document.getElementById('methodForm');
        const formMethod = document.getElementById('formMethod');
        const methodName = document.getElementById('methodName');
        const methodCode = document.getElementById('methodCode');
        const methodDescription = document.getElementById('methodDescription');
        const methodStatus = document.getElementById('methodStatus');
        const qrisFields = document.getElementById('qrisFields');
        const deleteText = document.getElementById('deleteText');
        const deleteForm = document.getElementById('deleteForm');

        function generateCode() {
            let name = methodName.value.toLowerCase().trim();

            if (name === 'qris') {
                methodCode.value = 'qris';
                methodCode.readOnly = true;
                qrisFields.classList.remove('hidden');
            } else {
                methodCode.value = name.replace(/\s+/g, '-');
                methodCode.readOnly = false;
                qrisFields.classList.add('hidden');
            }
        }

        function openCreateModal() {
            methodForm.action = "{{ route('metode-pembayaran.store') }}";
            formMethod.value = 'POST';
            methodName.value = '';
            methodDescription.value = '';
            methodStatus.value = '1';
            methodCode.value = '';
            methodCode.readOnly = false;
            qrisFields.classList.add('hidden');
            document.getElementById('modalTitle').textContent = 'Tambah Metode';
            methodModal.classList.remove('hidden');
        }

        function openEditModal(id, name, description, is_active) {
            methodForm.action = `/metode-pembayaran/${id}`;
            formMethod.value = 'PUT';
            methodName.value = name;
            methodDescription.value = description;
            methodStatus.value = is_active;

            if (name.toLowerCase().trim() === 'qris') {
                methodCode.value = 'qris';
                methodCode.readOnly = true;
                qrisFields.classList.remove('hidden');
            } else {
                methodCode.value = name.toLowerCase().replace(/\s+/g, '-');
                methodCode.readOnly = false;
                qrisFields.classList.add('hidden');
            }

            document.getElementById('modalTitle').textContent = 'Edit Metode';
            methodModal.classList.remove('hidden');
        }

        function openDeleteModal(id, name) {
            deleteText.textContent = `Yakin ingin menghapus metode "${name}"?`;
            deleteForm.action = `/metode-pembayaran/${id}`;
            deleteModal.classList.remove('hidden');
        }

        function closeModal() {
            methodModal.classList.add('hidden');
            deleteModal.classList.add('hidden');
        }
    </script>
@endsection
