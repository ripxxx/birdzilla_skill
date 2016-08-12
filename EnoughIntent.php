<?php

namespace birdzilla;

use AlexaPHPSDK\Intent;
use AlexaPHPSDK\Response;

//NO SLOTS

class EnoughIntent extends Intent {
    
    public function ask($params = array()) {
        return $this->endSessionResponse();
    }
    
    public function run($params = array()) {
        $response = new Response(false);
        $response->addText('What would you like to hear next?');
        return $response;
    }
    
}