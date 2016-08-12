<?php

namespace birdzilla;

use AlexaPHPSDK\Intent;
use AlexaPHPSDK\Response;

//NO SLOTS

class PlayLastIntent extends PlayIntent {
    
    protected function generateNoBirdResponse($shouldEndSession) {
        $response = new Response($shouldEndSession);
        $response->setRepromptMessage('Just name a bird, and I will play it.');
        $response->addText('You did not select any bird previously. ');
        if(!$shouldEndSession) {
            $response->addText('Just name a bird, and I will play it.');
        }
        return $response;
    }
    
    public function ask($params = array()) {
        $lastBird = $this->user['lastBird'];
        if(is_null($lastBird)) {
            return $this->generateNoBirdResponse(true);
        }
        $response = $this->generateResponse($lastBird, true);
        $response->removeCard();
        return $response;
    }
    
    public function run($params = array()) {
        $lastBird = $this->user['lastBird'];
        if(is_null($lastBird)) {
            return $this->generateNoBirdResponse(false);
        }
        $response = $this->generateResponse($lastBird, false);
        $response->removeCard();
        return $response;
    }
    
}