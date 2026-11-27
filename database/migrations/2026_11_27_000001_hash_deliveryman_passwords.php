<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    // Courier passwords used to be stored and compared as plain text.
    // Hash any row that isn't hashed yet so existing couriers can still log in.
    public function up()
    {
        DB::table('deliveryman')->select('id', 'password')->orderBy('id')->each(function ($row) {
            if ($row->password !== null && Hash::info($row->password)['algo'] === null) {
                DB::table('deliveryman')->where('id', $row->id)->update([
                    'password' => Hash::make($row->password),
                ]);
            }
        });
    }

    // Hashing can't be undone.
    public function down()
    {
    }
};
