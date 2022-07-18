<?php

//mettere questo file php in web root
$UrlDash='./';

$pag=filter_input(INPUT_GET,'pag',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
function testata(){
    echo '<!DOCTYPE html>';
    echo '<html>';
    echo '<head>';
    echo '<title>Page Title</title>';
    echo '<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">';
    echo '</head>';
    echo '<body>';
    echo '<div class="w3-container w3-teal">';
    echo '<a href="dashboard-filesgest.php"><h1>PHP FILE MANAGER TOOLS</h1></a>';
    echo '</div>';

}
function closer(){

    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js" integrity="sha512-GZ1RIgZaSc8rnco/8CXfRdCpDxRCphenIiZ2ztLy3XQfCbQUSCuk8IudvNHxkRA3oUg6q0qejgN/qqyG1duv5Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var editor = ace.edit("editor");

        editor.setTheme("ace/theme/dracula");
        editor.session.setMode("ace/mode/php");
        editor.setReadOnly(true);
        editor.session.setUseSoftTabs(true);
        editor.setOptions({
            autoScrollEditorIntoView: true,
            maxLines: 50
        });

    </script>
    <?php
    echo '</body>';
    echo '</html>';
}


//**********************************************************************************************************************************************************************************************************
//HOME PAGE
//**********************************************************************************************************************************************************************************************************

if($pag==''){
    testata();
    echo '<div class="w3-container">';
    echo '<h5>Scegli un tool... attenzione app in beta</h5>';
    echo '<p></p>';

    echo '</div>';

    echo '<hr>';

    echo '<div class="w3-container">';
    echo '<p>Scompattare file</p>';
    echo '<p><a href="dashboard-filesgest.php?pag=zipextract1" class="w3-button w3-orange w3-round">FIle .ZIP</a> <a href="dashboard-filesgest.php?pag=zipextract" class="w3-button w3-orange w3-round">FIle .RAR</a></p>';
    echo '</div>';

    echo '<hr>';

    echo '<div class="w3-container">';
    echo '<p>Visualizza file in Storage</p>';
    echo '<p><a href="dashboard-filesgest.php?pag=readfile" class="w3-button w3-orange w3-round">Cerca File da leggere</a></p>';
    echo '</div>';

    echo '<hr>';

    echo '<div class="w3-container">';
    echo '<p>Visualizza Phpinfo()</p>';
    echo '<p><a href="dashboard-filesgest.php?pag=phpinforead" class="w3-button w3-blue w3-round">php_info();</a></p>';
    echo '</div>';

    echo '<hr>';

    echo '<div class="w3-container">';
    echo '<p>Web root</p>';
    echo '<p><a href="dashboard-filesgest.php?pag=" class="w3-button w3-blue w3-round">Vedi tree del web storage</a>
<a href="dashboard-filesgest.php?pag=" class="w3-button w3-blue w3-round">Vedi tree del web storage</a>
<a href="dashboard-filesgest.php?pag=" class="w3-button w3-blue w3-round">Vedi tree del web storage</a></p>';
    echo '</div>';



    closer();
}




//**********************************************************************************************************************************************************************************************************
if($pag=='phpinforead') {
    echo '<div class="w3-container w3-teal">';
    echo '<a href="dashboard-filesgest.php"><h1>PHP FILE MANAGER TOOLS</h1></a>';
    echo '</div>';

    phpinfo();
}

if($pag=='readfile') {
    testata();

    echo '<br>';

    echo '<div class="w3-card-4">';
    echo '<div class="w3-container w3-orange">';
    echo '<h2>Leggi file testuali</h2>';
    echo '</div>';
    echo '<form class="w3-container" action="dashboard-filesgest.php">';
    echo '<p>';
    echo '<label class="w3-text-orange"><b>Posizione File:</b></label>';
    echo '<input class="w3-input w3-border w3-sand" name="position" type="text"  placeholder="Posizione File" required></p>';
    echo '<p>';
    echo '<label class="w3-text-orange"><b>Nome File:</b></label>';
    echo '<input class="w3-input w3-border w3-sand" name="file" type="text"  placeholder="Scegli File da visualizzare" required></p>';

    echo '<input type="hidden" name="pag" value="readfile2">';
    echo '<button class="w3-btn w3-orange" type="submit">Esegui Script</button></p>';
    echo '</form>';
    closer();
}

if($pag=='readfile2') {
    $file=filter_input(INPUT_GET,'file', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $position=filter_input(INPUT_GET,'position', FILTER_SANITIZE_FULL_SPECIAL_CHARS);



    //creazione path intera dell'archivio
    if($position=='/'){

        $path=$file;
    }
    else{
        //togliere possibile / all'inizio del percorso archivio
        if(substr($position, 0,1)=='/') {
            $position=substr($position, 1);

        }

        //togliere possibile / alla fine del percorso archivio
        if(substr($position, -1)=='/')
        {
            $position=substr($position, -1);
        }
        $path=$position."/".$file;
    }

    testata();
    /*echo $position;
    echo "\n";
    echo $file;
        echo "\n";
    echo $path;*/
    /*
        $lines=file($path);
        foreach($lines as $line){
            $testo.=$line;
            $testo.="\n";

    }
    */
    $testo= parse_file($path);

    /*
        $myfile = fopen($path, "r") or die("Unable to open file!");
    // Output one line until end-of-file
        while(!feof($myfile)) {
            echo "<span>".fgets($myfile)."</span>"."<br>";
        }
        fclose($myfile);
    */
    echo '<div id="editor">';
    echo $testo;


    echo '</div>';
    closer();
}



//funzione a caso
function parse_file($path)
{
    try {
        $file = new SplFileObject($path);
    } catch (LogicException $exception) {
        die('SplFileObject : '.$exception->getMessage());
    }
    while ($file->valid()) {
        $line = $file->fgets();
        //echo htmlspecialchars($line);
        $testo=$testo.htmlspecialchars($line)."\n";
    }

    //don't forget to free the file handle.
    $file = null;
    return $testo;
}


if($pag=='zipextract1'){

    //web page
    testata();


    echo '<br>';

    echo '<div class="w3-card-4">';
    echo '<div class="w3-container w3-orange">';
    echo '<h2>Scompatta file .zip</h2>';
    echo '</div>';
    echo '<form class="w3-container" action="dashboard-filesgest.php">';
    echo '<p>';
    echo '<label class="w3-text-orange"><b>Posizione Archivio:</b></label>';
    echo '<input class="w3-input w3-border w3-sand" name="positionzip" type="text"  placeholder="Posizione Archivio"></p>';
    echo '<p>';
    echo '<label class="w3-text-orange"><b>Archivio:</b></label>';
    echo '<input class="w3-input w3-border w3-sand" name="filezip" type="text"  placeholder="Scegli archivio .ZIP da scompattare"></p>';
    echo '<p>';
    echo '<label class="w3-text-orange"><b>Destinazione:</b></label>';
    echo '<input class="w3-input w3-border w3-sand" type="text"  name="destination" placeholder="Cartella di destinazione"></p>';
    echo '<p>';
    echo '<input type="hidden" name="pag" value="zipextract2">';
    echo '<button class="w3-btn w3-orange" type="submit">Esegui Script</button></p>';
    echo '</form>';


    closer();
}

if($pag=='zipextract2') {
    $filezip=filter_input(INPUT_GET,'filezip', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $positionzip=filter_input(INPUT_GET,'positionzip', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $destination=filter_input(INPUT_GET,'destination', FILTER_SANITIZE_FULL_SPECIAL_CHARS);





    //creazione path intera dell'archivio
    if($positionzip=='/'){

        $path=$filezip;
    }
    else{
        //togliere possibile / all'inizio del percorso archivio
        if(substr($positionzip, 0,1)=='/') {
            $positionzip=substr($position, 1);

        }

        //togliere possibile / alla fine del percorso archivio
        if(substr($positionzip, -1)=='/')
        {
            $positionzip=substr($positionzip, -1);
        }
        $path=$positionzip."/".$filezip;
    }





    //togliere possibile / all'inizio del percorso destinazione
    if(substr($destination, 0,1)=='/') {
        $destination=substr($destination, 1);

    }

//togliere possibile / alla fine del percorso destinazione
    if(substr($destination, -1)=='/')
    {
        $destination=substr($destination, -1);
    }

    testata();

    $zip = new ZipArchive;
    if ($zip->open($path) === TRUE) {
        $zip->extractTo($destination);
        $zip->close();

        echo '<div class="w3-container w3-orange">';
        echo '<h2>Archivio ZIP Scompattato</h2>';
        echo '<p>Controllare nella Cartella <b>'.$destination.'</b></p>';
        echo '<hr>';
        echo '<p>Path-> '.$path.'</p>';
        echo '<p>Nome Archivio-> '.$filezip.'</p>';
        echo '<p>Cartella Destinazione-> '.$destination.'</p>';
        echo '<p>Dashboard Dove si trova-> '.$UrlDash.'</p>';
        echo '</div>';
    } else {
        echo '<div class="w3-container w3-orange">';
        echo '<h2>Archivio ZIP NON Scompattato</h2>';
        echo '<p>Controllare i vari parametri passati</p>';
        echo '<p>Nome Archivio-> '.$filezip.'</p>';
        echo '<p>Cartella Destinazione-> '.$destination.'</p>';
        echo '<p>Dashboard Dove si trova-> '.$UrlDash.'</p>';
        echo '</div>';
    }

    closer();


}


?>
