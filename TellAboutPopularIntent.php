<?php

namespace birdzilla;

use AlexaPHPSDK\Intent;
use AlexaPHPSDK\Response;

//NO SLOTS

class TellAboutPopularIntent extends TellIntent {
    
    public function ask($params = array()) {
        $birdName = $this->getMostPopularBirdName();
        if(is_null($birdName)) {
            $birdName = 'barn owl';
        }
        $response = $this->generateResponse($birdName, true);
        $lastBird = $this->user['lastBird'];
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