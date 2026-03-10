<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;

class ImportFromSQLiteSeeder extends Seeder
{
    protected array $tables = [
        'users',
        'appointments',
        'services',
        'settings',
        'inventories',
        'styles',
        'blogs',
        'site_videos',
        'signature_looks',
        'testimonials',
        'accounts',
        'stylists',
        'jobs',
        'personal_access_tokens',
    ];

    public function run(): void
    {
        $this->command->info('Starting data migration from SQLite to PostgreSQL...');
        $this->command->warn('Make sure your .env is pointing to SQLite before running this!');

        if (!$this->confirmProceed()) {
            return;
        }

        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Clear existing data
        $this->clearExistingData();

        // Migrate each table
        foreach ($this->tables as $table) {
            $this->migrateTable($table);
        }

        // Reset sequences for PostgreSQL
        $this->resetSequences();

        Schema::enableForeignKeyConstraints();

        $this->command->info('✅ Data migration completed successfully!');
        $this->command->warn('Now switch your .env back to PostgreSQL and run: php artisan migrate');
    }

    protected function confirmProceed(): bool
    {
        return $this->command->confirm(
            'Have you switched your .env to SQLite? (yes/no)',
            false
        );
    }

    protected function clearExistingData(): void
    {
        $this->command->info('Clearing existing data from PostgreSQL...');

        // Truncate in reverse order to handle foreign keys
        $reverseTables = array_reverse($this->tables);
        foreach ($reverseTables as $table) {
            try {
                DB::connection('pgsql')->table($table)->truncate();
                $this->command->line("  ✓ Truncated: {$table}");
            } catch (\Exception $e) {
                $this->command->warn("  ⚠ Could not truncate {$table}: " . $e->getMessage());
            }
        }
    }

    protected function migrateTable(string $table): void
    {
        $this->command->info("Migrating table: {$table}");

        try {
            // Check if table exists in SQLite
            $hasTable = DB::connection('sqlite')->select(
                "SELECT name FROM sqlite_master WHERE type='table' AND name=?",
                [$table]
            );

            if (empty($hasTable)) {
                $this->command->warn("  ⚠ Table {$table} not found in SQLite, skipping...");
                return;
            }

            $count = 0;
            $chunkSize = 100;

            DB::connection('sqlite')->table($table)->orderBy('id')->chunk($chunkSize, function ($rows) use ($table, &$count) {
                foreach ($rows as $row) {
                    $data = (array) $row;

                    // Handle JSON fields
                    $data = $this->prepareJsonFields($table, $data);

                    // Handle timestamps
                    $data = $this->prepareTimestamps($data);

                    try {
                        DB::connection('pgsql')->table($table)->insert($data);
                        $count++;
                    } catch (\Exception $e) {
                        $this->command->error("    Error inserting row ID {$data['id']}: " . $e->getMessage());
                    }
                }
                $this->command->line("    Migrated {$count} records...");
            });

            $this->command->info("  ✓ Completed table: {$table} ({$count} records)");
        } catch (\Exception $e) {
            $this->command->error("  ✗ Failed to migrate {$table}: " . $e->getMessage());
        }
    }

    protected function prepareJsonFields(string $table, array $data): array
    {
        // Handle JSON fields for specific tables
        $jsonFields = [
            'stylists' => ['awards', 'specializations'],
            'services' => ['features'], // if you have this
            'settings' => ['value'], // if you store JSON in settings
        ];

        if (isset($jsonFields[$table])) {
            foreach ($jsonFields[$table] as $field) {
                if (isset($data[$field])) {
                    // Check if it's already JSON, if not, encode it
                    $value = $data[$field];
                    if (is_string($value)) {
                        // Try to decode to see if it's already JSON
                        $decoded = json_decode($value, true);
                        if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
                            // Not JSON, treat as regular string
                            $data[$field] = $value;
                        } else {
                            // Already JSON, keep as is
                            $data[$field] = $value;
                        }
                    } elseif (is_array($value)) {
                        // Convert array to JSON
                        $data[$field] = json_encode($value);
                    }
                }
            }
        }

        return $data;
    }

    protected function prepareTimestamps(array $data): array
    {
        $timestampFields = ['created_at', 'updated_at', 'deleted_at', 'email_verified_at'];

        foreach ($timestampFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                try {
                    // Convert SQLite datetime to proper format
                    $data[$field] = Carbon::parse($data[$field])->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    // If invalid date, set to null
                    $data[$field] = null;
                }
            }
        }

        return $data;
    }

    protected function resetSequences(): void
    {
        $this->command->info('Resetting sequences...');

        foreach ($this->tables as $table) {
            try {
                DB::connection('pgsql')->statement("
                    SELECT setval(pg_get_serial_sequence('{$table}', 'id'), 
                    (SELECT COALESCE(MAX(id), 0) FROM {$table}) + 1, false)
                ");
                $this->command->line("  ✓ Reset sequence for: {$table}");
            } catch (\Exception $e) {
                $this->command->warn("  ⚠ Could not reset sequence for {$table}");
            }
        }
    }
}
