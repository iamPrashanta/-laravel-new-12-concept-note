<?php

namespace App\CustomTasks;

use Illuminate\Support\Facades\DB;

class DeleteUsers
{
    public function __invoke()
    {
        \Log::info("this is an auto invoke for DeleteUser task");
        // DB::table('recent_users')->delete();
    }

    public function customFunctionABCD()
    {
        \Log::info("this is a custom function in DeleteUser task");
    }
}
