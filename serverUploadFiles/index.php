<?php
session_start();
/* Die vom Server zugelassene Größe der uploadbaren
 * Dateimenge geben lassen.
 * */
$displayMaxSize = ini_get('post_max_size');

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
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="bootstrap.min.css">
    <style>
        progress {
            margin:10px 0 10px 0;
        }

        .content {
            margin-top:60px;
        }
        body {
            padding-left: 20px;
        }
        .fill {
            padding-left: 20px;
        }
    </style>
    <title>XML-Bilder-Upload der Runze & Casper GmbH</title>
</head>
<body>

<div class="topbar">
    <div class="fill">
        <div class="container">
            <a class="brand" href="#">Runze & Casper GmbH</a>

            <ul class="nav">
                <li><a href="#">Hilfe/FAQ</a></li>
                <li><a href="#">Kontakt zum Support</a></li>
            </ul>

        </div>
    </div>
</div>
<article class="container">
    <div class="content">
        <header class="page-header">
            <h1>XML-Bilder-Upload</h1>
        </header>

        <div class="row">
            <div class="span8">

                <h2>Upload</h2>
                <p>Select one file to upload (Max total size <?=$displayMaxSize;?>)</p>
                <form action="upload.php" method="POST" enctype="multipart/form-data" id="upload" >
                    <input type="hidden" name="<?php echo ini_get("session.upload_progress.name"); ?>" value="upload" />


                    <div class="clearfix">
                        <label for="files">Bildatei für Ihren Eintrag</label>
                        <div class="input">
                            <input type="file" name="files[]" id="files" multiple style="width: 410px !important;" />
                        </div>
                    </div>
                    <div class="actions">
                        <input type="submit" class="btn primary" value="Upload"/>
                    </div>
                </form>

                <h2>Progress</h2>
                <progress max="1" value="0" id="progress"></progress>
                <p id="error" style="color: red;"></p>
                <p id="progress-txt"></p>
                <p id="uploadCallback"></p>
                <ul id="fileslist"></ul>
            </div>
        </div>
    </div>
</article>




<!-- File containing Jquery and the Jquery form plugin-->
<script src="jquery.js"></script>
<script src="jquery.form.js"></script>
<script>

    // Holds the id from set interval
    var interval_id = 0;

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
                selectedFileList += '<li>' + selectedfiles[f].name + ' ' + extround((selectedfiles[f].size/1024/1024), 100) + ' MB</li>';

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
                 * */
                if(data){
                    for(var i = 0; i < data.files.length; i++){

                        $('#uploadCallback').html(data.files +' data.files');
                    }
                    if(data.uploadError != ''){
                        $('#error').html(data.uploadError+' data.uploadError')
                            .prepend('<em><span style="font-size: 140%;">✘ </span>Folgender Fehler ist aufgetreten:<br></em>');
                        $('#progress').val('0');
                        stopProgress();
                        $('#progress-txt').remove();
                    }
                    if(data.moveError != ''){
                        $('#error').html(data.moveError+' data.moveError')
                            .prepend('<em><span style="font-size: 140%;">✘ </span>Folgender Fehler ist aufgetreten:<br></em>');
                        $('#progress').val('0');
                        stopProgress();
                        $('#progress-txt').remove();
                    }
                }
            }

            //Poll the server for progress
            interval_id = setInterval(function() {
                $.getJSON('progress.php', function(data){

                    //if there is some progress then update the status
                    if(data)
                    {
                        $('#progress').val(data.bytes_processed / data.content_length);
                        $('#progress-txt').html('Uploading '+ Math.round((data.bytes_processed / data.content_length) * 100) + '% ')
                            .append(extround((data.content_length/1024/1024), 100) + ' MB');
                        var filelist = "";
                        for(var i = 0; i < data.files.length; i++) {
                            var done = ' <img class="preloader" src="preloader.gif" style="margin-bottom: -3px" />';
                            if(data.files[i]['done']) {done = '<span class="okay" style="font-size: 140%; color: green;"> ✓</span>'}
                            filelist += '<li>' + data.files[i]['name'] +  done + '</li>';
                        }
                        $('#fileslist').html(filelist);

                    } else {

                        // When there is no data the upload is complete
                        $('#progress').val('1');
                        /* TODO: folgende Meldung sollte von upload.php kommen, 'no data' bedeutet nicht zwingend das alles ok ist. */
                        if($('#error').html() == ''){
                            $('#progress-txt').html('Alle Daten erfolgreich hochgeladen.');
                        }
                        $('.preloader').replaceWith('<span class="okay" style="font-size: 140%; color: green;"> ✓</span>');
                        stopProgress();
                    }
                }).fail(function(jqxhr, textStatus, error){

                    var msg = 'Leider ist folgender Fehler (#progr42) aufgetreten. "';
                    $('#error').html(msg + textStatus + ': ' + error +'"')
                        .prepend('<span style="font-size: 140%;">✘ </span>Es ist ein schwerer Systemfehler aufgetreten.<br>');
                    $('#progress').val('1');
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

