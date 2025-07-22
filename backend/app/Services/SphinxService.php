<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class SphinxService
{
    public function search(string $query): array
    {
        $data = DB::connection('sphinx')->select("
            SELECT * FROM posts WHERE MATCH(:query)
        ", ['query' => $query]);

        return $data;
    }
}