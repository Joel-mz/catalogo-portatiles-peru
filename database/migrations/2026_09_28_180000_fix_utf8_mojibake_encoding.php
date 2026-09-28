<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $fixString = function (?string $str): ?string {
            if ($str === null || $str === '') {
                return $str;
            }

            $replacements = [
                'Ã¡' => 'á', 'Ã©' => 'é', 'Ã­' => 'í', 'Ã³' => 'ó', 'Ãº' => 'ú', 'Ã±' => 'ñ',
                'Ã ' => 'Á', 'Ã‰' => 'É', 'Ã ' => 'Í', 'Ã“' => 'Ó', 'Ãš' => 'Ú', 'Ã‘' => 'Ñ',
                'Ã¼' => 'ü', 'Ãœ' => 'Ü',
                'Â¿' => '¿', 'Â¡' => '¡', 'Â°' => '°', 'Âº' => 'º', 'Âª' => 'ª',
                'â€œ' => '“', 'â€ ' => '”', 'â€˜' => '‘', 'â€™' => '’', 'â€“' => '–', 'â€”' => '—',
                'Â ' => ' ',
            ];

            $fixed = strtr($str, $replacements);

            if (preg_match('/[\xC2-\xDF][\x80-\xBF]/', $fixed) && function_exists('mb_convert_encoding')) {
                $converted = @mb_convert_encoding($fixed, 'ISO-8859-1', 'UTF-8');
                if ($converted !== false && mb_check_encoding($converted, 'UTF-8') && !empty($converted)) {
                    $fixed = $converted;
                }
            }

            return $fixed;
        };

        // Fix products
        if (DB::getSchemaBuilder()->hasTable('products')) {
            $products = DB::table('products')->get(['id', 'name', 'description', 'warranty', 'state']);
            foreach ($products as $p) {
                $newName = $fixString($p->name);
                $newDesc = $fixString($p->description);
                $newWarranty = $fixString($p->warranty);
                $newState = $fixString($p->state);

                if ($newName !== $p->name || $newDesc !== $p->description || $newWarranty !== $p->warranty || $newState !== $p->state) {
                    DB::table('products')->where('id', $p->id)->update([
                        'name' => $newName,
                        'description' => $newDesc,
                        'warranty' => $newWarranty,
                        'state' => $newState,
                    ]);
                }
            }
        }

        // Fix categories
        if (DB::getSchemaBuilder()->hasTable('categories')) {
            $categories = DB::table('categories')->get(['id', 'name', 'description']);
            foreach ($categories as $c) {
                $newName = $fixString($c->name);
                $newDesc = $fixString($c->description);
                if ($newName !== $c->name || $newDesc !== $c->description) {
                    DB::table('categories')->where('id', $c->id)->update([
                        'name' => $newName,
                        'description' => $newDesc,
                    ]);
                }
            }
        }

        // Fix brands
        if (DB::getSchemaBuilder()->hasTable('brands')) {
            $brands = DB::table('brands')->get(['id', 'name', 'description']);
            foreach ($brands as $b) {
                $newName = $fixString($b->name);
                $newDesc = $fixString($b->description);
                if ($newName !== $b->name || $newDesc !== $b->description) {
                    DB::table('brands')->where('id', $b->id)->update([
                        'name' => $newName,
                        'description' => $newDesc,
                    ]);
                }
            }
        }

        // Fix subcategories
        if (DB::getSchemaBuilder()->hasTable('subcategories')) {
            $subcategories = DB::table('subcategories')->get(['id', 'name']);
            foreach ($subcategories as $sc) {
                $newName = $fixString($sc->name);
                if ($newName !== $sc->name) {
                    DB::table('subcategories')->where('id', $sc->id)->update([
                        'name' => $newName,
                    ]);
                }
            }
        }

        // Fix device models
        if (DB::getSchemaBuilder()->hasTable('device_models')) {
            $models = DB::table('device_models')->get(['id', 'name']);
            foreach ($models as $dm) {
                $newName = $fixString($dm->name);
                if ($newName !== $dm->name) {
                    DB::table('device_models')->where('id', $dm->id)->update([
                        'name' => $newName,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // irreversible data fix
    }
};
