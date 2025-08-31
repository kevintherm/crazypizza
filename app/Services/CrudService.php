<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;

class CrudService
{
    public static function delete(string|stdClass|Model $modelClass, int $id, string $column = null): Model
    {
        DB::beginTransaction();
        $item = $modelClass::findOrFail($id);

        if ($column) {
            $item->update([$column => null]);
        } else {
            $item->delete();
        }

        DB::commit();
        return $item;
    }

    public static function bulkDelete(string|stdClass|Model $modelClass, array $ids): void
    {
        DB::beginTransaction();
        $modelClass::destroy($ids);
        DB::commit();
    }
}
