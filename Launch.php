<?php
/**
 * Created by Aleksandr Berdnikov.
 * Copyright 2016 Onix-Systems.
*/

namespace birdzilla;

use AlexaPHPSDK\LaunchRequest;
use AlexaPHPSDK\Response;

class Launch extends LaunchRequest {
    
    public function run($params = array()) {
        $response = new Response();
        $response->addText('Hello, I know how different birds sound. Which one do you want to hear?');
        $response->setRepromptMessage('Just name a bird, and I will play it.');
        return $response;
    }
    
}

