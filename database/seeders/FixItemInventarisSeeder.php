<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peralatan;
use App\Models\ItemInventaris;

class FixItemInventarisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $peralatans = Peralatan::all();

        foreach ($peralatans as $peralatan) {
            $existingCount = ItemInventaris::where('peralatan_id', $peralatan->id)->count();
            $needed = $peralatan->total_stok - $existingCount;

            if ($needed > 0) {
                for ($i = 1; $i <= $needed; $i++) {
                    // Start numbering from existing count + 1
                    $number = $existingCount + $i;
                    $barcode = 'PAND-' . str_pad($peralatan->id, 3, '0', STR_PAD_LEFT) . '-' . str_pad($number, 3, '0', STR_PAD_LEFT) . strtoupper(substr(uniqid(), -2));

                    ItemInventaris::create([
                        'peralatan_id' => $peralatan->id,
                        'barcode_alat' => $barcode,
                        'status_ketersediaan' => 'Tersedia',
                        'kondisi' => 'Baik',
                    ]);
                }
                $this->command->info("Added {$needed} missing items for Peralatan ID: {$peralatan->id} ({$peralatan->nama_alat})");
            } elseif ($needed < 0) {
                $this->command->info("Peralatan ID: {$peralatan->id} ({$peralatan->nama_alat}) has more items than total_stok. Please review manually.");
            }
        }

        $this->command->info('FixItemInventarisSeeder completed!');
    }
}
