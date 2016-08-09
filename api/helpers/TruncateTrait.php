<?php
namespace ParkillerDemo\helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

trait TruncateTrait
{

    public function truncateTables(array $tables = [ ])
    {
        $tables = $this->selectTruncateTables($tables);
        $this->checkForeignKeys();
        foreach ($tables as $table) {
            if ($table === 'migrations') {
                continue;
            }
            DB::table($table)->truncate();
        }
        $this->checkForeignKeys(false);
    }


    /**
     * @param array $tables
     *
     * @return array
     */
    public function selectTruncateTables(array $tables)
    {
        if (empty( $tables )) {
            $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();

            return $tables;
        }

        return $tables;
    }


    public function checkForeignKeys($check = true)
    {
        $check = $check ? '0' : '1';
        DB::statement("SET FOREIGN_KEY_CHECKS = $check;");
    }
}