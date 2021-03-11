<?php

namespace App\Http\Controllers;

use App\Models\Mailing;
use App\Models\Reminder;

ini_set('max_execution_time', 0);

class Send extends Controller {

    public function mailing(): string
    {
       return (new Mailing)->send();
    }

    public function reminder(): array
    {
        return (new Reminder)->send();
    }
}
