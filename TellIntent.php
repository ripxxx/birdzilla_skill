<?php
/**
 * Created by Aleksandr Berdnikov.
 * Copyright 2016 Onix-Systems.
*/

namespace Birdzilla;

use AlexaPHPSDK\Response;
use AlexaPHPSDK\Skill;
use AlexaPHPSDK\User;

require_once(__DIR__.'/BirdzillaIntent.php');

class TellIntent extends BirdzillaIntent {
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
                    $response->setTitle($bird[1]);
                    $response->setImage($data['imageHttpsUrl']);
                    $description = preg_replace("/<\/p>[^<]*?<p>/", ' ', $data['description']);
                    $description = preg_replace("/<[^>]+>/", '', $description);
                    $description = preg_replace("/\t/", '', $description);
                    $description = str_replace("’", "'", $description);
                    $description = str_replace('"', "\\\"", $description);
                    $description = trim($description);

                    $response->setDescription($description);
                    $response->addText($data['description']);
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

