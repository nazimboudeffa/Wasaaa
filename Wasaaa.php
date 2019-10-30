<?php

require_once $global['systemRootPath'] . 'plugin/Plugin.abstract.php';

class Wasaaa extends PluginAbstract {

    public function getDescription() {
        return "Manage Wasabi Videos Informations";
    }

    public function getName() {
        return "Wasaaa";
    }

    public function getUUID() {
        return "0fdbbf98-fa4f-11e9-8f0b-362b9e155667";
    }

    public function getPluginVersion() {
        return "1.0";
    }

    public function getTags() {
        return array('free', 'wasabi', 'video');
    }

    public function getEmptyDataObject() {
        $obj = new stdClass();
        $obj->onlyAdminCanWasabiEmbed = true;
        $obj->API_KEY = 'Your Wasabi Generated Key';
        $obj->API_SECRET = 'Your Wasabi Secret Key';
        $obj->REGION = 'us-west-1';
        return $obj;
    }

    public function getUploadMenuButton(){
        global $global;
        $obj = $this->getDataObject();
        if($obj->onlyAdminCanWasabiEmbed && !User::isAdmin()){
            return '';
        }
        return '<li><a  href="'.$global['webSiteRootURL'].'plugin/Wasaaa/search.php" ><span class="fa fa-link"></span> '.__("Wasabi Embed").'</a></li>';
    }
}
