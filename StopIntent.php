<?php

class StopIntent extends Intent {
    
    public function ask($params = array()) {
        
        return $this->endSessionResponse();
    }
    
    public function run($params = array()) {
        $response = new Response(flase);
        $response->addText('What would you like to hear next?');
        return $response;
    }
    
}

