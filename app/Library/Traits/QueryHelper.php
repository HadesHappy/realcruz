<?php

namespace Acelle\Library\Traits;

use DB;

trait QueryHelper
{
    public function scopePerPage($query, $size, $callback)
    {
        $count = $query->count();
        $pages = (int)ceil($query->count()/$size);
        for ($i = 0; $i < $pages; $i += 1) {
            $offset = $size * $i;
            $callback($query->limit($size)->offset($offset));
        }
    }

    public function createTemporaryTableFromArray($tableName, $data, $fields, $callback = null)
    {
        // Note: data must be an array of hash
        // Note: fields format looks like this:

        try {
            DB::beginTransaction();

            $table = table($tableName); // with prefix
            $fieldsSql = implode(',', $fields);

            DB::statement("DROP TABLE IF EXISTS {$table};");
            DB::statement("CREATE TABLE {$table}({$fieldsSql});");

            // Actually insert data
            DB::table($tableName)->insert($data);


            // Pass to the controller for handling
            if (!is_null($callback)) {
                $callback($tableName); // Note: it is without prefix
            }

            // Cleanup
            DB::statement("DROP TABLE IF EXISTS {$table};");

            // It is all done
            DB::commit();
        } catch (\Exception $e) {
            // finish the transaction
            DB::rollBack();
            throw $e;
        }
    }
}
