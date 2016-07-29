<?php

class StopIntent extends Intent {
    
    public function ask($params = array()) {
        return $this->endSessionResponse();
    }
    
    public function run($params = array()) {
        return $this->ask($params);
    }
    
}

