<?php

namespace App\Models\buttons;

use App\Models\buttons\extend\AbstractButtonsViber;

class ButtonsViber extends AbstractButtonsViber {

    public function subscribed($count): array
    {
        return [
            $this->button(6, 1, 'subscribed__'.$count, '{subscribed}')
        ];
    }
}
