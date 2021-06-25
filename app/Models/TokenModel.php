<?php

namespace App\Models;

use Laravel\Sanctum\PersonalAccessToken;

class TokenModel extends PersonalAccessToken
{
    protected $table;

    public function __construct() {

        $this->table = config('app.db_table_prefix') . 'personal_access_tokens';

    }
}