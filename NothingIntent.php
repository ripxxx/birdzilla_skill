<?php
/**
 * Created by Aleksandr Berdnikov.
 * Copyright 2016 Onix-Systems.
*/

namespace Birdzilla;

use AlexaPHPSDK\Intent;
use AlexaPHPSDK\Response;

class NothingIntent extends Intent {
    
    public function ask($params = array()) {
        return $this->endSessionResponse('Ok, goodbye.');
    }
    
    public function run($params = array()) {
        return $this->ask($params);
    }
    
}

