<?php
/**
 * Created by Aleksandr Berdnikov.
 * Copyright 2016 Onix-Systems.
*/

namespace birdzilla;

use AlexaPHPSDK\EndSessionRequest;
use AlexaPHPSDK\Response;

class EndSession extends EndSessionRequest {
    
    public function run($params = array()) {
        $response = $this->endSessionResponse();
        return $response;
    }
    
}

