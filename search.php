<?php
require_once '../../videos/configuration.php';
if (!User::isLogged()) {
    header("Location: {$global['webSiteRootURL']}?error=" . __("You can not do this"));
    exit;
}
$obj = YouPHPTubePlugin::getObjectData("Wasaaa");
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['language']; ?>">
    <head>
        <script src="https://sdk.amazonaws.com/js/aws-sdk-2.558.0.min.js"></script>
        <title><?php echo $config->getWebSiteTitle(); ?>  :: Wasabi Embed</title>
        <?php
        include $global['systemRootPath'] . 'view/include/head.php';
        ?>
        <style>
            #custom-search-input{
                padding: 3px;
                border: solid 1px #E4E4E4;
                border-radius: 6px;
                background-color: #fff;
            }

            #custom-search-input input{
                border: 0;
                box-shadow: none;
            }

            #custom-search-input button{
                margin: 2px 0 0 0;
                background: none;
                box-shadow: none;
                border: 0;
                color: #666666;
                padding: 0 8px 0 10px;
                border-left: solid 1px #ccc;
            }

            #custom-search-input button:hover{
                border: 0;
                box-shadow: none;
                border-left: solid 1px #ccc;
            }

            #custom-search-input .glyphicon-search{
                font-size: 23px;
            }
            #results li {
                padding: 10px 0;
                border-bottom: 1px dotted #ccc;
                list-style: none;
                overflow: auto;
            }
            .list-left {
                float: left;
                width: 20%;
            }
            .list-left img {
                width: 100%;
                padding: 3px;
                border: 1px solid #ccc;
            }
            .list-right {
                float: right;
                width: 78%;
            }
            .list-right h3 {
                margin: 0;
            }
            .list-right p {
                margin: 0;
            }

            .cTitle {
                color: #dd2826;
            }

            .button-container {
                margin-top: 25px;

            }

            .paging-button {
                background: #f4f4f4;
                padding: 0 13px;
                border: #ccc 1px solid;
                border-radius: 5px;
                color: #333;
                margin: 10px;
                cursor: pointer;
            }
        </style>
    </head>
    <body class="<?php echo $global['bodyClass']; ?>">
        <?php
        include $global['systemRootPath'] . 'view/include/navbar.php';
        ?>
        <div class="container">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <form id="search-form" name="search-form" onsubmit="return search()">
                        <div id="custom-search-input">
                            <div class="input-group col-md-12">
                                <input type="search" id="query" class="form-control input-lg" placeholder="Search YouTube / PlayList URL" />
                                <span class="input-group-btn">
                                    <button class="btn btn-info btn-lg" type="submit">
                                        <i class="glyphicon glyphicon-search"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                    <br>
                    <div class="row">
                        <div class="col-sm-6">
                            <button class="btn btn-info btn-block" id="getAll"><?php echo __('Embed All'); ?></button>
                        </div>
                        <div class="col-sm-6">
                            <button class="btn btn-success btn-block" id="getSelected"><?php echo __('Embed Selected'); ?></button>
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <ul id="results"></ul>
                    <div id="buttons"></div>
                </div>
            </div>
        </div>
        <?php
        include $global['systemRootPath'] . 'view/include/footer.php';
        ?>
        <script>

            var accessKeyId = '<?php echo $obj->API_KEY; ?>';
            var secretAccessKey = '<?php echo $obj->API_SECRET; ?>';

            var wasabiEndpoint = new AWS.Endpoint('s3.wasabisys.com');
            var s3 = new AWS.S3({
                signatureVersion: 'v2',
                endpoint: wasabiEndpoint,
                accessKeyId: accessKeyId,
                secretAccessKey: secretAccessKey
            });

            var params = {
                Bucket: 'bledtube'
            };

            s3.listObjectsV2(params, function (err, data) {
                if (!err) {
                    var files = []
                    data.Contents.forEach(function (element) {
                        files.push({
                            filename: element.Key
                        });
                    });
                    console.log(files)
                } else {
                    console.log(err);  // an error ocurred
                }
            });

        </script>
    </body>
</html>
