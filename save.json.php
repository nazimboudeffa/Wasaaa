<?php

//error_reporting(0);
header('Content-Type: application/json');
if (!isset($global['systemRootPath'])) {
    require_once '../../videos/configuration.php';
}

$obj = new stdClass();
$obj->error = true;
$obj->msg = array();

$objo = YouPHPTubePlugin::getObjectDataIfEnabled('Wasaaa');

if (empty($objo) || ($objo->onlyAdminCanWasabiEmbed && !User::isAdmin())) {
    $obj->msg[] = __("Permission denied");
    $obj->msg[] = "Plugin disabled";
} else if (!User::canUpload()) {
    $obj->msg[] = __("Permission denied");
    $obj->msg[] = "User can not upload videos";
} else if (!empty($_POST['objectsToSave'])) {

    foreach ($_POST['objectsToSave'] as $value) {

        foreach ($value as $key => $value2) {
            $value[$key] = xss_esc($value2);
        }

        $filename = uniqid("_YPTuniqid_", true);
        $videos = new Video();
        $videos->setFilename($filename);
        $videos->setTitle($value['title']);
        $videos->setDescription("");
        $videos->setClean_title($value['title']);
        $videos->setDuration(secondsToVideoTime($value['duration']));
        file_put_contents($global['systemRootPath'] . "videos/{$filename}.jpg", url_get_contents($global['webSiteRootURL']."plugin/Wasaaa/Wasaaa.jpg"));
        $videos->setVideoLink($value['link']);
        $videos->setType('linkVideo');

        $videos->setStatus('a');
        try {
            $resp = $videos->save(true);
        } catch (Exception $exc) {
            try {
                $resp = $videos->save(true);
            } catch (Exception $exc) {
                continue;
            }
        }

        YouPHPTubePlugin::afterNewVideo($resp);

        YouPHPTubePlugin::saveVideosAddNew($_POST, $resp);

        $obj->msg[] = Video::getVideoLight($resp);
    }

    $obj->error = false;
}
echo json_encode($obj);
