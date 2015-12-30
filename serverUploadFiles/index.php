<?php
session_start();
/* Die vom Server zugelassene Größe der uploadbaren
 * Dateimenge geben lassen.
 * Und die Menge der zugelassenen Dateien für einen
 * Upload geben lassen (max_file_uploads).
 * */
$displayMaxSize = ini_get('post_max_size');
$displayMaxFileUploads = ini_get('max_file_uploads');

/* Ersetzung durch eine übliche Einheitsangabe. */
switch(substr($displayMaxSize,-1))
{
    case 'G':
        $displayMaxSize = substr($displayMaxSize, 0, -1) . ' Gigabyte';
        break;
    case 'M':
        $displayMaxSize = substr($displayMaxSize, 0, -1) . ' Megabyte';
        break;
    case 'K':
        $displayMaxSize = substr($displayMaxSize, 0, -1) . ' Kilobyte';
        break;
}
?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="css/bootstrap-theme.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Bootstrap E N D E -->
    <style>
        progress {
            margin:10px 0 10px 0;
        }
        body {
            /* Abstand oben wegen der festen Navbar */
            padding-top: 40px;
        }
    </style>
    <title>XML-Bilder-Upload der Runze & Casper GmbH</title>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Menü ein/aus</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Runze & Casper GmbH</a>
        </div>
        <!-- Die Nav-Links, Formulare und andere Inhalte für das Umschalten zu sammeln -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="#">Hilfe/FAQ</a></li>
                <li><a href="#">Kontakt zum Support</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-fluid">
    <header class="page-header">
        <h1>XML-Bilder-Upload</h1>
    </header>

    <div class="row">
        <div class="col-xs-12 col-md-8">

            <h2>Upload</h2>
            <p>Select one file to upload (Max total size <?=$displayMaxSize;?>)</p>
            <p>Bitte nur Maximal <?=$displayMaxFileUploads;?> Dateien auswählen.</p>
            <form action="upload.php" method="POST" enctype="multipart/form-data" id="upload" class="form-horizontal">
                <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="upload" />

                <div class="form-group">
                    <label for="files" class="col-sm-3 control-label">Bildatei für Ihren Eintrag</label>

                    <div class="col-sm-9">
                        <div class="input-group">
                            <span class="input-group-btn">
                                <input id="submit" type="submit" class="btn btn-primary" value="Upload"/>
                            </span>
                            <input class="form-control" type="file" name="files[]" id="files" multiple/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="progress" class="col-sm-3 control-label">Fortschritt</label>

                    <div class="col-sm-9">

                            <div class="progress">
                                <div class="progress-bar" id="progress" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>

                    </div>
                </div>

            </form>

            <p id="error" style="color: red;"></p>
            <p id="progress-txt"></p>
            <p id="uploadCallback" class="hide"></p>
            <ul id="fileslist"></ul>
        </div>
    </div>
</div>




<!-- File containing Jquery and the Jquery form plugin-->
<script src="js/jquery-1.11.3.min.js"></script>
<script src="js/jquery.form.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>
<script>

    // Holds the id from set interval
    var interval_id = 0;
    var uploadCallback = '';

    $(document).ready(function(){

        $('#files').change(function(){
            if($(this).val() == ''){
                /* Prüfen ob Daten vom Benutzer ausgewählt worden sind. */
                $('#error').html('<span style="font-size: 140%;">✘ </span>Bitte wählen Sie Daten von Ihrem Rechner aus die hochgeladen werden sollen.')
            } else {
                /* Wenn dann die Daten vom Benutzer ausgewählt wurden
                 * Fehlermeldung wieder leeren. Und eine Liste der Auswahl
                 * ausgeben.
                 * */
                $('#error').html('');
            }

            var selectedFileList = '';
            var fileListSize = 0;
            var selectedfiles = $('#files')[0].files;
            for(var f = 0; f < selectedfiles.length; f++) {
                selectedFileList += '<li id="' + f + '">' + selectedfiles[f].name + ' '
                                 + '<span class="preloader">' + extround((selectedfiles[f].size/1024/1024), 100) + ' MB</span></li>';

                fileListSize += selectedfiles[f].size;
            }
            $('#fileslist').html(selectedFileList);
            $('#progress-txt').html('Die von Ihnen agewählten Dateien: ' + extround((fileListSize/1024/1024), 100) + ' MB');
        });

        // Add the submit handler to the form
        $('#upload').submit(function(){

            $('#upload').ajaxSubmit({
                // Optionen für den jQuery-Ajax-Commit:
                beforeSubmit:   beforeSubmit,
                dataType:       'json',
                success:        uploadResponse,
                resetForm:      true,
                error:          $('#uploadCallback').load('upload.php', function(response, status, xhr) {
                    if(status == 'error') {
                        var msg = 'Leider ist folgender Fehler (#upl42) aufgetreten. "';
                        $('#error').html(msg + xhr.status + ' ' + xhr.statusText)
                            .prepend('<span style="font-size: 140%;">✘ </span>Es ist ein schwerer Systemfehler aufgetreten.<br>');
                        $('#fileslist').remove();
                        $('#progress-txt').remove();
                    }
                })
            });

            function beforeSubmit() {
                /* Prüfen ob Daten vom Benutzer ausgewählt worden sind. */
                if ($('#files').val() == '') {
                    $('#error').html('<span style="font-size: 140%;">✘ </span>Bitte wählen Sie Daten von Ihrem Rechner aus die hochgeladen werden sollen.');

                    return false;
                }
            }

            function uploadResponse(data) {
                /* Response der upload.php (JSON) auswerten
                 * und prüfen ob alles hochgeladen wurde.
                 * ggf. entsprechende Fehlermeldung ausgeben.
                 * ****************************************** */
                if(data){
                    for(var i = 0; i < data.files.length; i++){

                        $('#uploadCallback').html(data.files +' data.files');
                    }
                    uploadCallback = 'okay';
                    if(data.uploadError != ''){
                        $('#error').html(data.uploadError+' data.uploadError')
                            .prepend('<em><span style="font-size: 140%;">✘ </span>Folgender Fehler ist aufgetreten:<br></em>');
                        $('#progress').width('0%');
                        $('#progress').html('');
                        stopProgress();
                        $('#progress-txt').remove();
                    }
                    if(data.moveError != ''){
                        $('#error').html(data.moveError+' data.moveError')
                            .prepend('<em><span style="font-size: 140%;">✘ </span>Folgender Fehler ist aufgetreten:<br></em>');
                        $('#progress').width('0%');
                        $('#progress').html('');
                        stopProgress();
                        $('#progress-txt').remove();
                    }
                }
            }

            //Poll the server for progress
            interval_id = setInterval(function() {
                $.getJSON('progress.php', function(data){

                    //if there is some progress then update the status
                    if(!data.noProgress)
                    {
                        $('#submit').attr('disabled', true);
                        $('#progress').width((data.bytes_processed / data.content_length) * 100 + '%');
                        $('#progress').html(Math.round((data.bytes_processed / data.content_length) * 100) + '% ');
                        $('#progress-txt').html(extround((data.bytes_processed/1024/1024), 100) + ' MB bereits hochgeladen');

                        var done = "";
                        for(var i = 0; i < data.files.length; i++) {

                            done = ' <img class="preloader" src="preloader.gif" style="margin-bottom: -3px" />';
                            /* Wenn data.files[i]['done'] true ist der Upload in das temporäre Verzeichnis
                             * des Webservers fertig und kann als okay markiert werden. */
                            if(data.files[i]['done']) {
                                done = '<span class="okay" style="font-size: 140%; color: green;"> ✓</span>'
                            }
                            $('#'+i).html(data.files[i]['name'] +  done);
                        }

                    }

                    //if noProgress then this part
                    if(data.noProgress) {
                        /* Wenn von der upload.php kein moveError, uploadError in #error
                         * ausgegeben wird und von upload.php der uploadCallback okay ist
                         * wird eine Erfolgsmeldung ausgegeben.
                         * ************************************ */
                        if($('#error').html() == '' && uploadCallback == 'okay'){
                            $('#progress').width('100%');
                            $('#progress').html('100%');
                            $('#progress-txt').html('Alle Daten erfolgreich hochgeladen.');
                            $('.preloader').replaceWith('<span class="okay" style="font-size: 140%; color: green;"> ✓</span>');
                        }
                        $('#submit').attr('disabled', false);
                        stopProgress();
                    }
                }).fail(function(jqxhr, textStatus, error){

                    var msg = 'Leider ist folgender Fehler (#progr42) aufgetreten. "';
                    $('#error').html(msg + textStatus + ': ' + error +'"')
                        .prepend('<span style="font-size: 140%;">✘ </span>Es ist ein schwerer Systemfehler aufgetreten.<br>');
                    $('#progress').width('0%');
                    $('.okay').replaceWith('<span style="font-size: 140%; color: red;"> ✘</span>');
                    stopProgress();
                });

            }, 200);

            return false;

        });
    });

    function stopProgress()
    {
        clearInterval(interval_id);
    }

    function extround(zahl,n_stelle) {
        zahl = (Math.round(zahl * n_stelle) / n_stelle);
        return zahl;
//                10 = 1 Nachkommastelle
//                100 = 2 Nackommastellen
//                1000 = 3 Nachkommastellen
//                usw.
    }
</script>

</body>
</html>

