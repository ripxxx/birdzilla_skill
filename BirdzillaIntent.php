<?php
/**
 * Created by Aleksandr Berdnikov.
 * Copyright 2016 Onix-Systems.
*/

namespace birdzilla;

use AlexaPHPSDK\Intent as BaseIntent;
use AlexaPHPSDK\Response;
use AlexaPHPSDK\Skill;

define('BIRDS_LIST_FILE_NAME', 'birds.lst');

class BirdzillaIntent extends BaseIntent {
    
    protected function simplifyBirdName($name) {
        $name = preg_replace("/[^a-z]/", '', strtolower($name));
        return $name;
    }
    
    protected function getContentDirectoryPath() {
        $skill = Skill::getInstance();
        $contentDirectoryPath = $skill['directories']['content'];
        if(file_exists($contentDirectoryPath) && is_dir($contentDirectoryPath) && is_writable($contentDirectoryPath)) {
            return $contentDirectoryPath;
        }
        return false;
    }

    protected function getBirdsListRemoteData() {
        $birdsList = file_get_contents('http://www.birdzilla.com/birds/names-aliases-json.html?output_format=json');
        if(strlen($birdsList) > 0) {
            $birdsList = json_decode($birdsList);
            if(count($birdsList) > 0) {
                $_birdsList = array();
                foreach ($birdsList as $bird) {
                    $name = $this->simplifyBirdName($bird[1]);
                    $_birdsList[$name] = $bird;
                }
                $birdsList = $_birdsList;
                $contentDirectoryPath = $this->getContentDirectoryPath();
                if($contentDirectoryPath !== false) {
                    $fileName = $contentDirectoryPath.'/'.BIRDS_LIST_FILE_NAME;
                    file_put_contents($fileName, serialize($birdsList));
                }
                return $birdsList;
            }
        }
        return array();
    }
    
    protected function getBirdsListLocalData() {
        $birdsList = array();
        $contentDirectoryPath = $this->getContentDirectoryPath();
        if($contentDirectoryPath !== false) {
            $fileName = $contentDirectoryPath.'/'.BIRDS_LIST_FILE_NAME;
            if(file_exists($fileName)) {
                $_birdsList = file_get_contents($fileName);
                if(strlen($_birdsList) > 0) {
                    $_birdsList = unserialize($_birdsList);
                    (count($_birdsList) > 0) && $birdsList = $_birdsList;
                }
            }
        }
        return $birdsList;
    }

    protected function getBirdsList() {
        $birdsList = $this->getBirdsListLocalData();
        if(count($birdsList) == 0) {
           $birdsList = $this->getBirdsListRemoteData(); 
        }
        return $birdsList;
    }
    
    protected function getBirdLocalData($id) {
        $contentDirectoryPath = $this->getContentDirectoryPath();
        if($contentDirectoryPath !== false) {
            $fileName = $contentDirectoryPath.'/'.$id.'.dt';
            if(file_exists($fileName)) {
                $data = file_get_contents($fileName);
                if(strlen($data) > 0) {
                   $data = unserialize($data);
                   if(count($data) > 0) {
                       return $data;
                   }
                }
            }
        }
        return false;
    }
    
    protected function loadBirdData($id, $url) {
        $data = $this->getBirdLocalData($id);
        if($data === false) {
            $skill = Skill::getInstance();
            $siteUrl = $skill['birdzillaSiteUrl'];
            $httpsUrl = $skill['skillHttpsUrl'];

            if(!is_null($siteUrl) && (strlen($siteUrl) > 0)) {
                /*$fn = $this->getContentDirectoryPath().'/'.$id.'.html';
                $html = file_get_contents($fn);//*/
                $html = file_get_contents($siteUrl.$url);
                $html = preg_replace("/[\n\r]/", ' ', $html);
                preg_match_all('/<div class="description page-item">(.+?)<\/div>/i', $html, $matches);
                $description = $matches[1][0];
                $description = preg_replace("/<.?em>/", '', $description);
                preg_match_all('/<a href="([^"]*?)">/i', $description, $matches);
                $description = preg_replace("/<.?a[^>]*?>/", '', $description);
                $descriptionUrl= $matches[1][0];
                $data = array(
                    'description' => $description,
                    'descriptionUrl' => $descriptionUrl,
                );
                
                preg_match_all('/<div class="images">[^<]*?<img.*?src="([^"]*?)"/i', $html, $matches);
                
                $imageUrl = $matches[1][0];
                $data['imageUrl'] = $imageUrl;
                $pathParts = pathinfo($imageUrl);
                $imageFileName = $id.'.'.$pathParts['extension'];
                $contentDirectoryPath = $this->getContentDirectoryPath();
                if($contentDirectoryPath !== false) {
                    file_put_contents($contentDirectoryPath.'/'.$imageFileName, file_get_contents($imageUrl));
                    $data['imageFileName'] = $imageFileName;
                    $data['imagePath'] = $contentDirectoryPath.'/'.$imageFileName;
                    $data['imageHttpsUrl'] = $httpsUrl.'/content/'.$imageFileName;
                }//*/
                
                preg_match_all('/<li class="play-sound">[^<]*?<a.*?href="([^"]*?)"/i', $html, $matches);
                
                $soundUrl = $matches[1][0];
                $data['soundUrl'] = $soundUrl;
                $html = file_get_contents($siteUrl.$soundUrl);
                $html = preg_replace("/[\n\r]/", ' ', $html);
                preg_match_all('/<audio.*?src="([^"]*?)"/i', $html, $matches);
                $audioUrl = $matches[1][0];
                $data['audioUrl'] = $audioUrl;
                $pathParts = pathinfo($audioUrl);
                $audioFileName = $id.'.original.'.$pathParts['extension'];
                $_audioFileName = $id.'.'.$pathParts['extension'];
                if($contentDirectoryPath !== false) {
                    file_put_contents($contentDirectoryPath.'/'.$audioFileName, file_get_contents($audioUrl));
                    $data['originalAudioFileName'] = $audioFileName;
                    $data['originalAudioPath'] = $contentDirectoryPath.'/'.$audioFileName;
                    $command = 'ffmpeg -y -i "'.$contentDirectoryPath.'/'.$audioFileName.'" -ar 16000 -ab 48k -ac 1 "'.$contentDirectoryPath.'/'.$_audioFileName.'"';
                    exec($command);
                    $data['audioFileName'] = $_audioFileName;
                    $data['audioPath'] = $contentDirectoryPath.'/'.$_audioFileName;
                    $data['audioHttpsUrl'] = $httpsUrl.'/content/'.$_audioFileName;
                }//*/
                
                $data['fanFacts'] = '';
                file_put_contents($contentDirectoryPath.'/'.$id.'.dt', serialize($data));
                return $data;
            }
        }
        return $data;
    }
    
    protected function getMostPopularBirdName() {
        $fileName = __DIR__.'/private/popular.json';
        if(file_exists($fileName)) {
            $contents = NULL;
            $fh = fopen($fileName, "r");
            if(flock($fh, LOCK_SH)) {
                $contents = fread($fh, filesize($fileName));
                flock($fh, LOCK_UN);
            }
            fclose($fh);
            if(strlen($contents) > 1) {
                $data = json_decode($contents, true);
                if(is_array($data) && (count($data) > 0)) {
                    if(asort($data)) {
                        $names = array_keys($data);
                        return array_pop($names);
                    }
                }
            }
        }
        return NULL;
    }
    
    protected function voteForBirdName($birdName) {
        $fileName = __DIR__.'/private/popular.json';
        $contents = NULL;
        $fh = NULL;
        $fe = false;
        if(file_exists($fileName)) {
            $fh = fopen($fileName, "r+");
            $fe = true;
        }
        else {
            $fh = fopen($fileName, "w");
        }
        if(flock($fh, LOCK_EX)) {
            $data = array();
            if($fe) {
                $contents = fread($fh, filesize($fileName));
                if(strlen($contents) > 1) {
                    $data = json_decode($contents, true);
                    if(is_array($data) && (count($data) > 0)) {
                        !isset($data[$birdName]) && $data[$birdName] = 0;
                        ++$data[$birdName];
                    }
                }
            }
            
            if(count($data) == 0) {
                $data[$birdName] = 1;
            }
            ftruncate($fh, 0);
            fseek($fh, 0);
            fwrite($fh, json_encode($data));
            flock($fh, LOCK_UN);
        }
        fclose($fh);
    }
}