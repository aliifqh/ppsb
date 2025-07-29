<div class="bg-white p-6 rounded-lg border-2 border-teal-100 mb-6">
    <h3 class="text-lg font-medium text-teal-700 mb-4">Progress Pembayaran</h3>
    <div class="space-y-4">
        <div class="flex items-center">
            <span>Biaya Administrasi</span>
            <div class="flex-grow mx-4 h-1 bg-gray-200 rounded">
                @if($santri->pembayaran && $santri->pembayaran->status_administrasi === 'diverifikasi')
                <div class="h-1 bg-green-500 rounded" style="width: 100%"></div>
                @elseif($santri->pembayaran && $santri->pembayaran->status_administrasi === 'sudah_bayar')
                <div class="h-1 bg-yellow-500 rounded" style="width: 50%"></div>
                @else
                <div class="h-1 bg-red-500 rounded" style="width: 0%"></div>
                @endif
            </div>
            <span class="text-sm">
                @if($santri->pembayaran && $santri->pembayaran->status_administrasi === 'diverifikasi')
                <span class="text-green-600">Selesai</span>
                @elseif($santri->pembayaran && $santri->pembayaran->status_administrasi === 'sudah_bayar')
                <span class="text-yellow-600">Proses</span>
                @else
                <span class="text-red-600">Belum</span>
                @endif
            </span>
        </div>
        <div class="flex items-center">
            <span>Daftar Ulang</span>
            <div class="flex-grow mx-4 h-1 bg-gray-200 rounded">
                @if($santri->pembayaran && $santri->pembayaran->status_daftar_ulang === 'diverifikasi')
                <div class="h-1 bg-green-500 rounded" style="width: 100%"></div>
                @elseif($santri->pembayaran && $santri->pembayaran->status_daftar_ulang === 'sudah_bayar')
                <div class="h-1 bg-yellow-500 rounded" style="width: 50%"></div>
                @else
                <div class="h-1 bg-red-500 rounded" style="width: 0%"></div>
                @endif
            </div>
            <span class="text-sm">
                @if($santri->pembayaran && $santri->pembayaran->status_daftar_ulang === 'diverifikasi')
                <span class="text-green-600">Selesai</span>
                @elseif($santri->pembayaran && $santri->pembayaran->status_daftar_ulang === 'sudah_bayar')
                <span class="text-yellow-600">Proses</span>
                @else
                <span class="text-red-600">Belum</span>
                @endif
            </span>
        </div>
    </div>
</div> 