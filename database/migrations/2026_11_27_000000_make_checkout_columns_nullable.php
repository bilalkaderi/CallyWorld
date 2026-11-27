<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Checkout never sets these: the delivery details/hour are filled in later by the admin,
    // and orders.name is unused. As NOT NULL columns they made every checkout insert fail.
    public function up()
    {
        DB::statement('ALTER TABLE cart MODIFY delivery_details VARCHAR(255) NULL');
        DB::statement('ALTER TABLE cart MODIFY delivery_hour TIME NULL');
        DB::statement('ALTER TABLE orders MODIFY name VARCHAR(255) NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE cart MODIFY delivery_details VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE cart MODIFY delivery_hour TIME NOT NULL');
        DB::statement('ALTER TABLE orders MODIFY name VARCHAR(255) NOT NULL');
    }
};
