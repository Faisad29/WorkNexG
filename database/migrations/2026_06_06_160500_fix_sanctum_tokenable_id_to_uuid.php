<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('personal_access_tokens')) {
            return;
        }

        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        $dataType = DB::table('information_schema.columns')
            ->where('table_schema', 'public')
            ->where('table_name', 'personal_access_tokens')
            ->where('column_name', 'tokenable_id')
            ->value('data_type');

        if ($dataType === 'uuid') {
            return;
        }

        DB::statement('DROP INDEX IF EXISTS personal_access_tokens_tokenable_type_tokenable_id_index');
        DB::statement('ALTER TABLE personal_access_tokens DROP COLUMN tokenable_id');
        DB::statement('ALTER TABLE personal_access_tokens ADD COLUMN tokenable_id uuid NOT NULL');
        DB::statement('CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON personal_access_tokens (tokenable_type, tokenable_id)');
    }

    public function down(): void
    {
        if (! Schema::hasTable('personal_access_tokens')) {
            return;
        }

        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        $dataType = DB::table('information_schema.columns')
            ->where('table_schema', 'public')
            ->where('table_name', 'personal_access_tokens')
            ->where('column_name', 'tokenable_id')
            ->value('data_type');

        if ($dataType !== 'uuid') {
            return;
        }

        DB::statement('DROP INDEX IF EXISTS personal_access_tokens_tokenable_type_tokenable_id_index');
        DB::statement('ALTER TABLE personal_access_tokens DROP COLUMN tokenable_id');
        DB::statement('ALTER TABLE personal_access_tokens ADD COLUMN tokenable_id bigint NOT NULL');
        DB::statement('CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON personal_access_tokens (tokenable_type, tokenable_id)');
    }
};
