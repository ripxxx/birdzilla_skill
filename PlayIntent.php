<?php
require_once(__DIR__.'/BirdzillaIntent.php');

class PlayIntent extends BirdzillaIntent {
    
   protected function generateResponse($birdName, $shouldEndSession) {
        $response = new Response($shouldEndSession);
        $skill = Skill::getInstance();
        $response->setRepromprtMessage('Just name a bird, and I will play it.');
        
        $birdsList = $this->getBirdsList();
        if(count($birdsList) > 0) {
            $_birdName = $this->simplifyBirdName($birdName);
            if(isset($birdsList[$_birdName])) {
                $bird = $birdsList[$_birdName];
                $data = $this->loadBirdData($bird[0], $bird[2]);
                
                if($data !== false) {
                    $response->setImage($data['imageHttpsUrl']);
                    $response->setDescription($data['description']);
                    $response->addText('Here is how '.$birdName.'  sounds like.');
                    $response->addAudio($data['audioHttpsUrl']);
                    if(!$shouldEndSession) {
                        $response->addText('What would you like to hear next?');
                    }
                }
                else {
                    $response->addText('Sorry, I cannot find such bird in my library. Please try another.');
                }
            }
            else {
                $response->addText('Sorry, I cannot find such bird in my library. Please try another.');
            }
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

