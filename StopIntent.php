<?php
/**
 * Created by Aleksandr Berdnikov.
 * Copyright 2016 Onix-Systems.
*/

namespace Birdzilla;

use AlexaPHPSDK\Intent;
use AlexaPHPSDK\Response;

class StopIntent extends Intent {
    
    public function ask($params = array()) {
        
        return $this->endSessionResponse();
    }
    
    public function run($params = array()) {
        $response = new Response(false);
        $response->addText('What would you like to hear next?');
        return $response;
    }
    
}

