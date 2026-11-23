<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Registration never sets `country`, so give it a default to stop inserts failing.
    public function up()
    {
        DB::statement('ALTER TABLE users MODIFY country INT NOT NULL DEFAULT 0');
    }

    public function down()
    {
        DB::statement('ALTER TABLE users MODIFY country INT NOT NULL');
    }
};
