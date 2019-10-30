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
    </head>
    <body class="<?php echo $global['bodyClass']; ?>">
        <?php
        include $global['systemRootPath'] . 'view/include/navbar.php';
        ?>
        <div class="container">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div id="select-input">
                        <div class="input-group col-md-12">
                          <!-- Select -->
                        </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-sm-6">
                          <button class="btn btn-success btn-block" id="getSelected"><?php echo __('Embed Selected'); ?></button>
                      </div>
                      <div class="col-sm-6">
                          <button class="btn btn-info btn-block" id="getAll"><?php echo __('Embed All'); ?></button>
                      </div>
                    </div>
                </div>
                <div class="panel-body">
                    <ul id="results" class="list-group"></ul>
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

            var params = {};
            s3.listBuckets(params, function(err, data) {
             if (err) console.log(err, err.stack);
             else {

               var buckets = [];

               data.Buckets.forEach(function (element) {
                   buckets.push({
                       bucket: element.Name
                   });
               });

               var sel = $('<select>').appendTo('body');
                sel.append($("<option>").text("Select a bucket").prop('disabled', true).prop('selected', true));
               buckets.forEach(function(element) {
                sel.append($("<option>").attr('value',element.bucket).text(element.bucket));
               });
               $(sel).attr('id', "buckets");
               $(sel).addClass('form-control');
               $('#select-input').append(sel);
             }
            });

            $(document).ready(function () {
              $('#buckets').on('change', function(){
                search($('#buckets option:selected').val());
              });
            });

            function search(bucket){

              var params = {
                  Bucket: bucket
              };

              var files = [];

              s3.listObjectsV2(params, function (err, data) {
                  if (!err) {
                      data.Contents.forEach(function (element) {
                          files.push({
                              filename: element.Key
                          });
                      });

                      $('#results').html('');
                      $.each(files, function (i, file) {
                          // Get Output
                          var output = getOutput(bucket, file);
                          // display results
                          $('#results').append(output);
                      });

                  } else {
                      console.log(err);  // an error ocurred
                  }
              });

            }

            function getOutput(b, f) {

              var title = f.filename;
              // Build output string
              var output = '<li class="list-group-item">' +
                      '<div class="checkbox">' +
                      '<label><input class="checkbox-inline" type="checkbox" value="' + title + '" name="videoCheckbox">' + title + '<a target="_blank" href="https://s3.' + '<?php echo $obj->REGION; ?>' + '.wasabisys.com/' + b + '/' + title + '?rel=0"> watch</a></label>' +
                      '</div>' +
                      '</li>' +
                      '';
              return output;

            }

        </script>
    </body>
</html>
