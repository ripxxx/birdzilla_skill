<?php

class Launch extends LaunchRequest {
    
    public function run($params = array()) {
        $response = new Response();
        $response->addText('Hello, I know how different birds sound. Which one do you want to hear?');
        $response->setRepromprtMessage('Just name a bird, and I will play it.');
        return $response;
    }
    
}

