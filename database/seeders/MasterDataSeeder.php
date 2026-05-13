<?php

namespace Database\Seeders;

use App\Models\UnitLokasi;
use App\Models\RakPenyimpanan;
use App\Models\Peralatan;
use App\Models\ItemInventaris;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------------
        // 1. SEEDER DATA UNIT PEMAKAI (LOKASI OPERASIONAL)
        // ---------------------------------------------------------
        $unitLokasi = [
            ['nama_unit' => 'PLTA ASAHAN 3', 'jenis_unit' => 'PLTA'],
            ['nama_unit' => 'PLTA RENUN', 'jenis_unit' => 'PLTA'],
            ['nama_unit' => 'PLTA SIPANSIHAPORAS', 'jenis_unit' => 'PLTA'],
            ['nama_unit' => 'PLTMH BATANG GADIS', 'jenis_unit' => 'PLTMH'],
            ['nama_unit' => 'PLTMH AEK RAISAN 1', 'jenis_unit' => 'PLTMH'],
            ['nama_unit' => 'PLTMH AEK RAISAN 2', 'jenis_unit' => 'PLTMH'],
            ['nama_unit' => 'PLTMH AEK SIBUNDONG', 'jenis_unit' => 'PLTMH'],
            ['nama_unit' => 'PLTMH AEK SILANG', 'jenis_unit' => 'PLTMH'],
            ['nama_unit' => 'PLTMH BOHO', 'jenis_unit' => 'PLTMH'],
            ['nama_unit' => 'PLTMH TONDUHAN', 'jenis_unit' => 'PLTMH'],
            ['nama_unit' => 'PLTMH KOMBIH 1', 'jenis_unit' => 'PLTMH'],
            ['nama_unit' => 'PLTMH KOMBIH 2', 'jenis_unit' => 'PLTMH'],
        ];

        foreach ($unitLokasi as $unit) {
            UnitLokasi::create($unit);
        }

        // ---------------------------------------------------------
        // 2. SEEDER DATA RAK PENYIMPANAN
        // ---------------------------------------------------------
        $rakList = ['RAK C2', 'RAK C3', 'RAK C4', 'RAK C5', 'RAK C6', 'RAK C7', 'RAK C8'];
        $rakIds = [];
        
        foreach ($rakList as $namaRak) {
            $rak = RakPenyimpanan::create(['nama_rak' => $namaRak]);
            $rakIds[$namaRak] = $rak->id; // Menyimpan ID untuk relasi ke peralatan
        }

        // ---------------------------------------------------------
        // 3. SEEDER KATALOG PERALATAN & GENERATE ITEM INVENTARIS
        // ---------------------------------------------------------
        // Data diekstrak dari format "TOOLS WORKSHOP UP PANDAN"
        $peralatanList = [
            [
                'nama_alat' => 'TABUNG GAS OKSIGEN',
                'spesifikasi' => '2 UKURAN SEDANG, 3 UKURAN BESAR',
                'rak_id' => null, // Sesuai Excel tidak spesifik di Rak C
                'total_stok' => 5
            ],
            [
                'nama_alat' => 'MESIN BUBUT CHINHUNS',
                'spesifikasi' => 'CHD-560 X 1500',
                'rak_id' => null,
                'total_stok' => 1
            ],
            [
                'nama_alat' => 'MESIN GERINDA DUDUK',
                'spesifikasi' => 'Standard',
                'rak_id' => null,
                'total_stok' => 1
            ],
            [
                'nama_alat' => 'MESIN COMPRESSOR KRISBOW',
                'spesifikasi' => 'POWER 3 HP, PRESS 12 Bar, WARNA ABU2',
                'rak_id' => null,
                'total_stok' => 1
            ],
            [
                'nama_alat' => 'HIDRAULIC CRIMPING TOOLS',
                'spesifikasi' => 'MAX. PRESS. 20 Ton, MAX. STROKE 16 mm',
                'rak_id' => $rakIds['RAK C4'],
                'total_stok' => 1
            ],
            [
                'nama_alat' => 'RACHET PULLER SLING 2 Ton',
                'spesifikasi' => 'Kapasitas 2 Ton',
                'rak_id' => $rakIds['RAK C4'],
                'total_stok' => 4
            ],
            [
                'nama_alat' => 'FLUKE INSULATION TESTER',
                'spesifikasi' => 'Tipe 1503/1507',
                'rak_id' => $rakIds['RAK C7'],
                'total_stok' => 1
            ],
            [
                'nama_alat' => 'FLUKE INFRARED THERMOMETER',
                'spesifikasi' => '-30 C° hingga 500 C°',
                'rak_id' => $rakIds['RAK C8'],
                'total_stok' => 1
            ],
            [
                'nama_alat' => 'FEELER GAUGE 100 mm (TEKIRO)',
                'spesifikasi' => '0,05 - 1 mm',
                'rak_id' => $rakIds['RAK C8'],
                'total_stok' => 1
            ],
            [
                'nama_alat' => 'SNAP RING PLIER SET 4 Pcs 7 inch',
                'spesifikasi' => 'Ukuran 7 inch, Set 4 Pcs',
                'rak_id' => $rakIds['RAK C8'],
                'total_stok' => 1
            ]
        ];

        foreach ($peralatanList as $index => $dataAlat) {
            $peralatan = Peralatan::create($dataAlat);

            // Generate Barcode Fisik berdasarkan total stok
            // Jika stok 4, maka akan dibuatkan 4 baris data di tabel tbl_item_inventaris
            for ($i = 1; $i <= $peralatan->total_stok; $i++) {
                // Format Barcode: PLNU-00X-00Y (Contoh: PLNU-005-001)
                $kodeBarcode = 'PLNU-' . str_pad($peralatan->id, 3, '0', STR_PAD_LEFT) . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                
                ItemInventaris::create([
                    'peralatan_id' => $peralatan->id,
                    'kode_barcode' => $kodeBarcode,
                    'kondisi' => 'Baik',
                    'status_ketersediaan' => 'Tersedia'
                ]);
            }
        }
    }
}