<?php

require_once $global['systemRootPath'] . 'plugin/Plugin.abstract.php';

class Wasabi extends PluginAbstract {

    public function getDescription() {
        return "Manage Wasabi Videos Informations";
    }

    public function getName() {
        return "Wasabi API";
    }

    public function getUUID() {
        return "0fdbbf98-fa4f-11e9-8f0b-362b9e155667";
    }

    public function getPluginVersion() {
        return "1.0";
    }

    public function getTags() {
        return array('free', 'wasabi', 'video player');
    }

    public function getEmptyDataObject() {
        return "";
    }

    public function getHeadCode() {
        return "";
    }
}
