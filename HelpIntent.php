<?php

namespace birdzilla;

use AlexaPHPSDK\Intent;
use AlexaPHPSDK\Response;

//NO SLOTS

class HelpIntent extends Intent {
    protected function generateResponse($shouldEndSession) {
        $response = new Response($shouldEndSession);
        
        $response->addText('<p>I can play songs of birds of North America and tell stories about them. You can also ask me to play a bird song chosen at random or play song of most popular birds, which were requested the most.</p>');
        $response->addText('<p>You can use following voice commands to interact with me:</p>');
        $response->addText('To play song, say - play song of and then <p>bird name</p>.');
        $response->addText('To listen the story, ask - tell me about and then <p>bird name</p>.');
        $response->addText('Bird song is lasting about 30 seconds, you can say - <p>Alexa, enough</p>, to stop it or you can say - <p>Alexa, stop</p> to exit the skill.');
        $response->addText('<p>To play song of last bird, say - play song of last bird.</p>');
        $response->addText('<p>To listen the story about the most popural bird, ask - tell me about most popular bird.</p>');
        $response->addText('<p>To play song of random bird, say - play song of random bird.</p>');
        
        return $response;
    }
    
    public function ask($params = array()) {
        return $this->generateResponse(true);
    }
    
    public function run($params = array()) {
        return $this->generateResponse(false);
    }
    
}