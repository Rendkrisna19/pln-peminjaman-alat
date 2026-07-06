@php
    $sortField = $sortField ?? 'kode_barcode';
    $sortDirection = $sortDirection ?? 'asc';
@endphp

<div class="overflow-x-auto">
    <table class="w-full text-left border-collapse">
        <thead class="bg-pln-cyan text-white text-xs uppercase font-extrabold tracking-wider border-b-2 border-blue-600">
            <tr>
                <th class="px-6 py-4">
                    <a href="{{ request()->fullUrlWithQuery(['sort_field' => 'kode_barcode', 'sort_direction' => ($sortField == 'kode_barcode' && $sortDirection == 'asc') ? 'desc' : 'asc']) }}" class="ajax-sort flex items-center hover:text-blue-200 transition-colors group w-max">
                        KODE BARANG
                        @if($sortField == 'kode_barcode')
                            {!! $sortDirection == 'asc' ? '<i class="fa-solid fa-sort-up ml-2 text-white"></i>' : '<i class="fa-solid fa-sort-down ml-2 text-white"></i>' !!}
                        @else
                            <i class="fa-solid fa-sort text-blue-300/50 ml-2 group-hover:text-blue-200"></i>
                        @endif
                    </a>
                </th>
                <th class="px-6 py-4">INFORMASI ALAT</th>
                <th class="px-6 py-4">LOKASI RAK</th>
                <th class="px-6 py-4 text-center">KONDISI FISIK</th>
                <th class="px-6 py-4 text-center">
                    <a href="{{ request()->fullUrlWithQuery(['sort_field' => 'status_ketersediaan', 'sort_direction' => ($sortField == 'status_ketersediaan' && $sortDirection == 'asc') ? 'desc' : 'asc']) }}" class="ajax-sort flex items-center justify-center hover:text-blue-200 transition-colors group w-max mx-auto">
                        STATUS KETERSEDIAAN
                        @if($sortField == 'status_ketersediaan')
                            {!! $sortDirection == 'asc' ? '<i class="fa-solid fa-sort-up ml-2 text-white"></i>' : '<i class="fa-solid fa-sort-down ml-2 text-white"></i>' !!}
                        @else
                            <i class="fa-solid fa-sort text-blue-300/50 ml-2 group-hover:text-blue-200"></i>
                        @endif
                    </a>
                </th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 text-sm bg-white">
            @forelse($monitoring as $item)
            <tr class="hover:bg-blue-50/40 transition-colors group">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-hashtag text-gray-400 text-xs"></i>
                        <span class="inline-block px-2.5 py-1 bg-blue-50 border border-blue-100 text-pln-cyan rounded text-xs font-mono font-bold shadow-sm">
                            {{ $item->kode_barcode }}
                        </span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="font-extrabold text-gray-800 text-sm group-hover:text-pln-cyan transition-colors">{{ $item->peralatan->nama_alat ?? 'Data Induk Dihapus' }}</p>
                    <p class="text-[11px] text-gray-500 font-medium line-clamp-1 mt-0.5">{{ $item->peralatan->spesifikasi ?? '-' }}</p>
                </td>
                <td class="px-6 py-4">
                    @if($item->peralatan && $item->peralatan->rak)
                        <span class="text-xs font-bold text-gray-700 bg-gray-50 px-3 py-1.5 rounded-xl border border-gray-200 inline-flex items-center gap-1.5 shadow-sm group-hover:bg-white transition-colors">
                            <i class="fa-solid fa-layer-group text-pln-cyan text-[10px]"></i> {{ $item->peralatan->rak->nama_rak }}
                        </span>
                    @else
                        <span class="text-xs font-bold text-gray-400 italic flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> Tanpa Rak</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center">
                    @php
                        // Logika Pewarnaan Kondisi Fisik Alat
                        $kondisi = $item->kondisi;
                        if ($kondisi == 'Baik') {
                            $kClass = 'bg-green-50 text-green-700 border-green-200';
                            $kIcon = 'fa-check';
                        } elseif ($kondisi == 'Rusak Ringan') {
                            $kClass = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                            $kIcon = 'fa-triangle-exclamation';
                        } else {
                            // Mencakup "Rusak" dan "Rusak Berat"
                            $kClass = 'bg-red-50 text-red-700 border-red-200';
                            $kIcon = 'fa-xmark';
                        }
                    @endphp
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 {{ $kClass }} font-extrabold rounded-lg border text-[11px] uppercase tracking-wider w-max mx-auto shadow-sm">
                        <i class="fa-solid {{ $kIcon }}"></i> {{ $kondisi }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($item->status_ketersediaan == 'Tersedia')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-600 font-extrabold rounded-full border border-blue-200 text-[11px] uppercase tracking-wider w-max mx-auto shadow-sm">
                            <i class="fa-solid fa-box text-[10px]"></i> Tersedia
                        </span>
                    @elseif($item->status_ketersediaan == 'Dipinjam')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-50 text-yellow-700 font-extrabold rounded-full border border-yellow-200 text-[11px] uppercase tracking-wider w-max mx-auto shadow-sm">
                            <i class="fa-solid fa-handshake text-[10px]"></i> Dipinjam
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 text-gray-600 font-extrabold rounded-full border border-gray-200 text-[11px] uppercase tracking-wider w-max mx-auto shadow-sm">
                            <i class="fa-solid fa-wrench text-[10px]"></i> Diperbaiki
                        </span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-20 h-20 bg-gray-50 border-2 border-dashed border-gray-200 rounded-full flex items-center justify-center mb-4">
                            <i class="fa-solid fa-satellite-dish text-3xl text-gray-300"></i>
                        </div>
                        <h3 class="text-lg font-extrabold text-gray-700 mb-1">Tidak Ada Data</h3>
                        <p class="text-sm text-gray-500 font-medium">Pergerakan alat yang sesuai dengan filter pencarian tidak ditemukan.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Navigasi Halaman -->
<div class="p-5 border-t border-gray-100 bg-gray-50/50" id="pagination-links">
    {{ $monitoring->links() }}
</div>