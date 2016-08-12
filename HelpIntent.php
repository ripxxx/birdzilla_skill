<?php

namespace birdzilla;

use AlexaPHPSDK\Intent;
use AlexaPHPSDK\Response;

//NO SLOTS

class HelpIntent extends Intent {
    
    public function ask($params = array()) {
        return $this->endSessionResponse('Goodbye.');
    }
    
    public function run($params = array()) {
        return $this->endSessionResponse('Goodbye.');
    }
    
}