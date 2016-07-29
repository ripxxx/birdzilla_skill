<?php

class NothingIntent extends Intent {
    
    public function ask($params = array()) {
        return $this->endSessionResponse('Ok, goodbye.');
    }
    
    public function run($params = array()) {
        return $this->ask($params);
    }
    
}

