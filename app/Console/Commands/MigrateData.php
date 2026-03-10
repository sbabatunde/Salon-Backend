<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MigrateData extends Command
{
    protected $signature = 'migrate:data';
    protected $description = 'Migrate data from SQLite to PostgreSQL';

    public function handle()
    {
        $this->info('Starting data migration from SQLite to PostgreSQL...');

        if (!$this->testConnections()) {
            return 1;
        }

        $tables = [
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
        ];

        // Disable triggers temporarily
        DB::connection('pgsql')->statement('SET session_replication_role = replica;');

        foreach ($tables as $table) {
            $this->migrateTable($table);
        }

        DB::connection('pgsql')->statement('SET session_replication_role = DEFAULT;');

        $this->info('✅ Migration completed successfully!');
        return 0;
    }

    protected function testConnections()
    {
        $this->info('Testing connections...');

        try {
            $sqlitePath = DB::connection('sqlite')->getDatabaseName();
            $this->info("✅ SQLite connected: {$sqlitePath}");
        } catch (\Exception $e) {
            $this->error("❌ SQLite connection failed: " . $e->getMessage());
            return false;
        }

        try {
            DB::connection('pgsql')->getPdo();
            $this->info("✅ PostgreSQL connected");
        } catch (\Exception $e) {
            $this->error("❌ PostgreSQL connection failed: " . $e->getMessage());
            return false;
        }

        return true;
    }

    protected function migrateTable(string $table)
    {
        $this->newLine();
        $this->info("Migrating table: {$table}");

        try {
            $rows = DB::connection('sqlite')->table($table)->get();
            $count = $rows->count();

            if ($count === 0) {
                $this->warn("  No data in {$table}, skipping");
                return;
            }

            $this->line("  Found {$count} records");

            // Clear existing data
            DB::connection('pgsql')->table($table)->truncate();

            $inserted = 0;
            $errors = 0;

            foreach ($rows as $row) {
                $data = (array) $row;

                // Handle table-specific data type conversions
                $data = $this->convertDataTypes($table, $data);

                // Handle JSON fields
                $data = $this->prepareJsonFields($table, $data);

                // Handle timestamps
                $data = $this->prepareTimestamps($data);

                // Remove generated columns for accounts table
                if ($table === 'accounts') {
                    unset($data['total_cost'], $data['profit']);
                }

                try {
                    DB::connection('pgsql')->table($table)->insert($data);
                    $inserted++;
                } catch (\Exception $e) {
                    $errors++;
                    $this->error("    Error inserting ID {$data['id']}: " . $e->getMessage());
                }
            }

            $this->info("  ✓ Inserted {$inserted} records into {$table}" . ($errors ? " ({$errors} errors)" : ""));
        } catch (\Exception $e) {
            $this->error("  ✗ Failed: " . $e->getMessage());
        }
    }

    protected function convertDataTypes(string $table, array $data): array
    {
        switch ($table) {
            case 'inventories':
                // Convert integer status to boolean
                if (isset($data['status'])) {
                    $data['status'] = (bool) $data['status'];
                }
                break;

            case 'testimonials':
                // Convert integer submitted to boolean
                if (isset($data['submitted'])) {
                    $data['submitted'] = (bool) $data['submitted'];
                }
                break;

            case 'accounts':
                // Convert decimal fields to proper format
                $decimalFields = ['amount_paid', 'service_cost', 'material_cost', 'other_cost'];
                foreach ($decimalFields as $field) {
                    if (isset($data[$field])) {
                        $data[$field] = (float) $data[$field];
                    }
                }
                break;
        }

        return $data;
    }

    protected function prepareJsonFields(string $table, array $data): array
    {
        if ($table === 'stylists') {
            foreach (['awards', 'specializations'] as $field) {
                if (isset($data[$field])) {
                    if (is_string($data[$field])) {
                        json_decode($data[$field]);
                        if (json_last_error() !== JSON_ERROR_NONE) {
                            if (str_contains($data[$field], ',')) {
                                $array = array_map('trim', explode(',', $data[$field]));
                                $data[$field] = json_encode($array);
                            } else {
                                $data[$field] = json_encode([$data[$field]]);
                            }
                        }
                    } elseif (is_array($data[$field])) {
                        $data[$field] = json_encode($data[$field]);
                    }
                }
            }
        }

        return $data;
    }

    protected function prepareTimestamps(array $data): array
    {
        $timestampFields = ['created_at', 'updated_at', 'deleted_at', 'email_verified_at', 'token_created_at'];

        foreach ($timestampFields as $field) {
            if (isset($data[$field]) && !empty($data[$field])) {
                try {
                    $data[$field] = Carbon::parse($data[$field])->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    $data[$field] = null;
                }
            }
        }

        return $data;
    }
}
