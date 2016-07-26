<?php

require_once(__DIR__.'/BirdzillaIntent.php');

class TellIntent extends BirdzillaIntent {
    protected function generateResponse($birdName, $shouldEndSession) {
        $response = new Response($shouldEndSession);
        $skill = Skill::getInstance();
        
        $birdsList = $this->getBirdsList();
        if(count($birdsList) > 0) {
            
        }
        else {
            $response->addText('Sorry, the service is not available right now, please try again later.');
            $response->forceSessionEnd();
        }
        
        return $response;
    }
    
    public function ask($params = array()) {
        return $this->generateResponse($params['bird'], true);
    }
    
    public function run($params = array()) {
        return $this->generateResponse($params['bird'], false);
    }
}

