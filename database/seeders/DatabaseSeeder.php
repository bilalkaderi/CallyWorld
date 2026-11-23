<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Demo data so the app can be explored right after `php artisan migrate --seed`.
 *
 * Every demo account uses the password "password".
 * Safe to run more than once: existing demo rows are reused, not duplicated.
 */
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $now = now();
        $password = Hash::make('password');

        // Suppliers / admin live in `users` (login page); role 1 = admin.
        $adminId = $this->findOrInsert('users', 'email', [
            'name' => 'Demo Admin',
            'email' => 'admin@callyworld.test',
            'password' => $password,
            'phone' => '+10000000001',
            'role' => 1,
            'status' => 'verified',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $artistId = $this->findOrInsert('users', 'email', [
            'name' => 'Demo Artist',
            'email' => 'artist@callyworld.test',
            'password' => $password,
            'phone' => '+10000000002',
            'aboutme' => 'Calligrapher working in Thuluth and Diwani scripts.',
            'role' => 0,
            'status' => 'verified',
            'address' => 'Beirut, Lebanon',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // Buyers live in `client` (sign-up form on the homepage).
        $buyerId = $this->findOrInsert('client', 'email', [
            'name' => 'Demo Buyer',
            'email' => 'buyer@callyworld.test',
            'password' => $password,
            'phone' => '+10000000003',
            'status' => 'verified',
            'address' => 'Istanbul, Turkey',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $categories = [];
        foreach (['Arabic', 'Modern', 'Framed'] as $name) {
            $categories[$name] = $this->findOrInsert('categories', 'name', [
                'name' => $name,
                'totalproducts' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $products = [
            ['Treble Flourish', 'Red ink composition on a textured green background.', 'demo/calligraphy-1.jpg', 'Modern', 45, 30, 40, 5, 0],
            ['Hilal Sentence', 'Hand-written Arabic sentence with a reed pen.', 'demo/calligraphy-2.jpg', 'Arabic', 60, 30, 30, 3, 20],
            ['Qard Hasan', 'Thuluth piece in blue and red, gold frame included.', 'demo/calligraphy-3.jpg', 'Framed', 120, 50, 40, 2, 0],
            ['Lastu Mahzuman', 'Arabic calligraphy on paper, ready to frame.', 'demo/calligraphy-4.jpg', 'Arabic', 75, 40, 30, 4, 10],
        ];

        $productIds = [];
        foreach ($products as [$name, $description, $photo, $category, $price, $width, $height, $stock, $promotion]) {
            $exists = DB::table('product')->where('photo', $photo)->exists();
            $productIds[] = $this->findOrInsert('product', 'photo', [
                'name' => $name,
                'userId' => $artistId,
                'price' => $price,
                'photo' => $photo,
                'description' => $description,
                'width' => $width,
                'height' => $height,
                'categoryId' => $categories[$category],
                'stock' => $stock,
                'expecting_delivery_time' => '7',
                'delivery_time_type' => 'days',
                'promotion' => $promotion,
                'validated' => 'yes',
                'our_stock' => 0,
                'sales_after_promotion' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            if (! $exists) {
                DB::table('categories')->where('id', $categories[$category])->increment('totalproducts');
            }
        }

        foreach ([[$productIds[1], 5], [$productIds[2], 4]] as [$productId, $rate]) {
            DB::table('productsrate')->updateOrInsert(
                ['productId' => $productId, 'clientId' => $buyerId],
                ['rate' => $rate, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        DB::table('comment')->updateOrInsert([
            'productId' => $productIds[1],
            'clientId' => $buyerId,
            'comment' => 'Beautiful line work, arrived well packaged.',
        ], [
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('rate')->updateOrInsert([
            'userId' => $artistId,
            'clientId' => $buyerId,
        ], [
            'rate' => 5,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    /**
     * Return the id of the row whose $key matches, inserting it first if needed,
     * so the seeder can be re-run without creating duplicates.
     */
    private function findOrInsert(string $table, string $key, array $row): int
    {
        $id = DB::table($table)->where($key, $row[$key])->value('id');

        return $id ?? DB::table($table)->insertGetId($row);
    }
}
