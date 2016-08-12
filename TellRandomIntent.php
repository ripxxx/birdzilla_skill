<?php

namespace birdzilla;

use AlexaPHPSDK\Response;
use AlexaPHPSDK\Skill;
use AlexaPHPSDK\User;

//NO SLOTS

class TellRandomIntent extends BirdzillaIntent {
    
    protected function generateResponse($shouldEndSession) {
        $response = new Response($shouldEndSession);
        $skill = Skill::getInstance();
        $response->setRepromptMessage('Just name a bird, and I will tell about it.');
        $birdsList = $this->getBirdsList();
        if(count($birdsList) > 0) {
            $_birdName = array_rand($birdsList);
            $birdName = $birdsList[$_birdName][1];
            $this->user['lastBird'] = $birdName;
            
            $bird = $birdsList[$_birdName];
            $data = $this->loadBirdData($bird[0], $bird[2]);

            if($data !== false) {
                $response->setTitle($bird[1]);
                $response->setImage($data['imageHttpsUrl']);
                $description = preg_replace("/<\/p>[^<]*?<p>/", ' ', $data['description']);
                $description = preg_replace("/<[^>]+>/", '', $description);
                $description = preg_replace("/\t/", '', $description);
                $description = str_replace("’", "'", $description);
                $description = str_replace('"', "\\\"", $description);
                $description = trim($description);

                $response->setDescription($description);
                $response->addText('<p>'.$birdName.'</p>');
                $response->addText($data['description']);
                if(!$shouldEndSession) {
                    $response->addText('What would you like to hear next?');
                }
            }
            else {
                $response->addText('Sorry, the service is not available right now, please try again later.');
            }
        }
        else {
            $response->addText('Sorry, the service is not available right now, please try again later.');
            $response->forceSessionEnd();
        }
        
        return $response;
    }
    
    public function ask($params = array()) {
        return $this->generateResponse(true);
    }
    
    public function run($params = array()) {
        return $this->generateResponse(false);
    }
    
}