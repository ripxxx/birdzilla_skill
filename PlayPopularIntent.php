<?php

namespace birdzilla;

use AlexaPHPSDK\Intent;
use AlexaPHPSDK\Response;

//NO SLOTS

class PlayPopularIntent extends PlayIntent {
    
    public function ask($params = array()) {
        $lastBird = $this->user['lastBird'];
        $birdName = $this->getMostPopularBirdName();
        if(is_null($birdName)) {
            $birdName = 'barn owl';
        }
        $response = $this->generateResponse($birdName, true);
        if(!is_null($lastBird) && ($this->simplifyBirdName($birdName) == $this->simplifyBirdName($lastBird))) {
            $response->removeCard();
        }
        return $response;
    }
    
    public function run($params = array()) {
        $lastBird = $this->user['lastBird'];
        $birdName = $this->getMostPopularBirdName();
        if(is_null($birdName)) {
            $birdName = 'barn owl';
        }
        $response = $this->generateResponse($birdName, false);
        if(!is_null($lastBird) && ($this->simplifyBirdName($birdName) == $this->simplifyBirdName($lastBird))) {
            $response->removeCard();
        }
        return $response;
    }
    
}