@extends('admin.main')
@section('judul', 'Ulasan & Komentar | AGA IT COMPUTER | Toko Komputer & Service Laptop Singosari Malang')

@section('content')
    <div class="p-6 bg-gray-100 ">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Ulasan & Komentar</h1>
        </div>

        {{-- Pencarian --}}
        <div class="bg-white p-4 rounded-lg shadow-sm mb-6 flex items-center justify-between gap-4 ">
            <form action="{{ route('ulasan.index') }}" method="GET" class="flex items-center gap-2 w-full">
                <input type="text" name="search" placeholder="Cari nama produk atau user..."
                    value="{{ request('search') }}"
                    class="w-full sm:w-80 px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 mt-4" />
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-md font-medium hover:bg-blue-700 transition duration-200 mt-4">
                    Cari
                </button>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">#</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produk
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">User
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rating
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Komentar</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($reviews as $review)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $review->product->nama_produk ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $review->user->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 flex items-center">
                                {{ str_repeat('⭐', $review->rating) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $review->komentar ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if ($review->status == 'disetujui')
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 font-medium">Disetujui</span>
                                @elseif($review->status == 'menunggu')
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800 font-medium">Menunggu</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800 font-medium">Ditolak</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $review->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex items-center gap-2">
                                {{-- Tombol Detail --}}
                                <button onclick="openDetailModal({{ json_encode($review) }})" title="Detail"
                                    class="text-blue-600 hover:text-blue-900 transition-colors duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                {{-- Form Status --}}
                                <form action="{{ route('reviews.updateStatus', $review->id) }}" method="POST">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()"
                                        class="text-xs border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 py-1">
                                        <option value="menunggu" {{ $review->status == 'menunggu' ? 'selected' : '' }}>
                                            Pending</option>
                                        <option value="disetujui" {{ $review->status == 'disetujui' ? 'selected' : '' }}>
                                            Setujui</option>
                                        <option value="ditolak" {{ $review->status == 'ditolak' ? 'selected' : '' }}>Tolak
                                        </option>
                                    </select>
                                </form>

                                {{-- Tombol Hapus --}}
                                <button onclick="openDeleteModal({{ $review->id }})" title="Hapus"
                                    class="text-red-600 hover:text-red-900 transition-colors duration-200">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.035 21H7.965a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-9H7" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada ulasan yang
                                ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginasi --}}
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    </div>

    {{-- Modal Detail --}}
    <div id="detailModal"
        class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-lg p-6 transform scale-95 transition-transform duration-300">
            <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Detail Ulasan</h2>
                <button type="button" onclick="closeModal('detailModal')"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-4 space-y-3">
                <div>
                    <p class="font-semibold text-gray-700">Produk:</p>
                    <p id="detailProduct" class="text-gray-900 font-medium"></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-700">User:</p>
                    <p id="detailUser" class="text-gray-900"></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-700">Rating:</p>
                    <p id="detailRating" class="text-yellow-500 text-xl"></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-700">Komentar:</p>
                    <p id="detailKomentar" class="p-3 bg-gray-50 rounded-lg text-gray-700 border border-gray-200"></p>
                </div>
                <div>
                    <p class="font-semibold text-gray-700">Tanggal:</p>
                    <p id="detailTanggal" class="text-gray-700"></p>
                </div>
            </div>
            <div class="pt-4 flex justify-end">
                <button type="button" onclick="closeModal('detailModal')"
                    class="px-4 py-2 rounded-md bg-blue-600 text-white font-medium hover:bg-blue-700 transition duration-200">Tutup</button>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div id="deleteModal"
        class="fixed inset-0 bg-black bg-opacity-70 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden transition-opacity duration-300">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6 transform scale-95 transition-transform duration-300">
            <div class="flex flex-col items-center text-center">
                <div class="bg-red-100 p-3 rounded-full mb-4">
                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900 mb-2">Hapus Ulasan?</h2>
                <p class="text-sm text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus ulasan ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex gap-3 w-full">
                    <button type="button" onclick="closeModal('deleteModal')"
                        class="flex-1 px-4 py-2 rounded-md border border-gray-300 text-gray-700 hover:bg-gray-100 transition duration-200">
                        Batal
                    </button>
                    <form id="deleteForm" method="POST" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2 rounded-md bg-red-600 text-white font-medium hover:bg-red-700 transition duration-200">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                modal.querySelector('div').classList.remove('scale-95');
                modal.querySelector('div').classList.add('scale-100');
            }, 10);
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.querySelector('div').classList.remove('scale-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }

        function openDetailModal(review) {
            document.getElementById('detailProduct').textContent = review.product ? review.product.nama_produk : '-';
            document.getElementById('detailUser').textContent = review.user ? review.user.name : '-';
            document.getElementById('detailRating').textContent = '⭐'.repeat(review.rating);
            document.getElementById('detailKomentar').textContent = review.komentar;
            document.getElementById('detailTanggal').textContent = new Date(review.created_at).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            openModal('detailModal');
        }

        function openDeleteModal(id) {
            let url = `{{ route('ulasan.destroy', ':id') }}`;
            url = url.replace(':id', id);
            document.getElementById('deleteForm').action = url;
            openModal('deleteModal');
        }

        // Close modals when clicking outside
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.fixed.inset-0').forEach(modal => {
                modal.addEventListener('click', (event) => {
                    if (event.target.id === modal.id) {
                        closeModal(modal.id);
                    }
                });
            });
        });
    </script>
@endsection
