<?php
    //Per questioni di sicurezza
    if (!isset($_SESSION[$IS_LOGGED]) || !$_SESSION[$IS_LOGGED]){
        header("Location: logout.php"); //Redirigo al logout in maniera da ripulire la sessione
        exit;
    }

    if (session_status() != PHP_SESSION_ACTIVE){
            session_start();
    }

    function editHomeImg($image){
        require 'constants.php';

        $fileName = basename($image["name"]); // Nome originale del file
        $targetFilePath = "$HOME_IMAGE_FOLDER/$TMP_FOLDER" . $fileName; // Percorso completo finale
        $fileTmpPath = $image["tmp_name"]; // Dove si trova ora (temp)
        $fileError = $image["error"]; // Eventuali errori

        if ($fileError === 0) {
            move_uploaded_file($fileTmpPath, $targetFilePath);

            $oldJson = json_decode($_SESSION[$CONFIG_JSON], true);
            $oldJson[$HOME_IMAGE] = substr($targetFilePath,strlen("Media/"));
            $newJson = json_encode($oldJson, JSON_PRETTY_PRINT);

            $_SESSION[$CONFIG_JSON] = $newJson;
        } else {
            echo "Si è verificato un errore!!";
        }
    }

    function editCarouselImg($images, $fileKey){
        require 'constants.php';

        $imageIndex = 0;
        for ($i=0; $i < sizeof($images); $i++) { 
            if ($images[$fileKey . $i + 1]["size"] > 0){
                $imageKey = $i;
            }
        }
        
        $image = $images[$fileKey . $imageIndex + 1];

        $fileName = basename($image["name"]); // Nome originale del file
        $targetFilePath = "$CAROUSEL_IMAGES_FOLDER/$TMP_FOLDER" . $fileName; // Percorso completo finale
        $fileTmpPath = $image["tmp_name"]; // Dove si trova ora (temp)
        $fileError = $image["error"]; // Eventuali errori

        if ($fileError === 0) {
            move_uploaded_file($fileTmpPath, $targetFilePath);

            $oldJson = json_decode($_SESSION[$CONFIG_JSON], true);
            $oldJson[$CAROUSEL_IMAGES][$imageIndex] = substr($targetFilePath,strlen("Media/"));
            $newJson = json_encode($oldJson, JSON_PRETTY_PRINT);

            $_SESSION[$CONFIG_JSON] = $newJson;
        } else {
            echo "Si è verificato un errore!!";
        }
    }

    function editNamePdf($index, $newName){
        require 'constants.php';
        require_once 'functions.php';

        $bulletinPdfs = filterPdf(scandir($PDF_BULLETIN_FOLDER));
        
        rename("$PDF_BULLETIN_FOLDER/" . $bulletinPdfs[$index], "$PDF_BULLETIN_FOLDER/" . $newName . ".pdf");
    }

    function editDeletePdf($index){
        require 'constants.php';
        require_once 'functions.php';

        $bulletinPdfs = filterPdf(scandir($PDF_BULLETIN_FOLDER));

        unlink("$PDF_BULLETIN_FOLDER/" . $bulletinPdfs[$index]);
    }

    function editAddPdf($file){
        require 'constants.php';

        if (strtolower(pathinfo($file["full_path"], PATHINFO_EXTENSION)) == "pdf"){
            $fileName = basename($file["name"]); // Nome originale del file
            $targetFilePath = "$PDF_BULLETIN_FOLDER/" . $fileName; // Percorso completo finale
            $fileTmpPath = $file["tmp_name"]; // Dove si trova ora (temp)
            $fileError = $file["error"]; // Eventuali errori

            if ($fileError === 0) {
                move_uploaded_file($fileTmpPath, $targetFilePath);
            } else {
                echo "Si è verificato un errore!!";
            }
        }
    }

    function editSelectedPdf($index){
        require 'constants.php';
        require_once 'functions.php';

        $bulletinPdfs = filterPdf(scandir($PDF_BULLETIN_FOLDER));
        $file = $bulletinPdfs[$index];

        if ($index >= 0 && $index < sizeof($bulletinPdfs)){
            $oldJson = json_decode($_SESSION[$CONFIG_JSON], true);
            $oldJson[$CURRENT_BULLETIN] = substr("$PDF_BULLETIN_FOLDER/" . $file,strlen("Media/"));
            $newJson = json_encode($oldJson, JSON_PRETTY_PRINT);

            $_SESSION[$CONFIG_JSON] = $newJson;
        }
    }
    
    function editAddCollaboration($name, $link){
        require 'constants.php';
        $collab = json_decode($_SESSION[$COLLAB_JSON], true);
        $newCollab = array(
            "name" => $name,
            "link" => $link
        );
        $collab['collaborations'][] = $newCollab;
        $_SESSION[$COLLAB_JSON] = json_encode($collab);
    }

    function editRenameCollab($index, $name){
        require 'constants.php';
        $collab = json_decode($_SESSION[$COLLAB_JSON], true);
        $collab['collaborations'][$index]['name'] = $name;
        $_SESSION[$COLLAB_JSON] = json_encode($collab);
    }
    
    function editRelinkCollab($index, $link){
        require 'constants.php';
        $collab = json_decode($_SESSION[$COLLAB_JSON], true);
        $collab['collaborations'][$index]['link'] = $link;
        $_SESSION[$COLLAB_JSON] = json_encode($collab);
    }
    
    function editDeleteCollab($index){
        require 'constants.php';
        $collab = json_decode($_SESSION[$COLLAB_JSON], true);
        array_splice($collab['collaborations'], (int)$index, 1);
        $_SESSION[$COLLAB_JSON] = json_encode($collab);
    }
    
    function editPageText($id, $lang, $txt){
        require 'constants.php';
        switch($lang){
            case "it":
                $lang = $IT_TEXTS_FILE;
                break;
            case "en":
                $lang = $EN_TEXTS_FILE;
                break;
            case "de":
                $lang = $DE_TEXTS_FILE;
                break;
        }
        $texts = json_decode($_SESSION[$TXT_JSON][$lang], true);

        $texts[$id] = $txt;

        $_SESSION[$TXT_JSON][$lang] = json_encode($texts);
    }
    
    function editAddPresident($name, $start, $finish){
        require 'constants.php';
        $yStart = intval(explode("-", $start)[0]);
        $yFinish = intval(explode("-", $finish)[0]);

        $period = "(".$yStart."-".$yFinish.")";
        $exPres = json_decode($_SESSION[$PRESIDENTS_JSON], true);

        $exPres['directors']['expres'] = $exPres['directors']['pres'];

        $exPres['exPresidents'][] = array(
            "name" => $exPres['directors']['expres'],
            "date" => $period
        );

        $exPres['directors']['pres'] = $name;
        $_SESSION[$PRESIDENTS_JSON] = json_encode($exPres);
    }
    
    function editAddExPres($name, $start, $finish){
        require 'constants.php';
        $yStart = intval(explode("-", $start)[0]);
        $yFinish = intval(explode("-", $finish)[0]);

        $period = "(".$yStart."-".$yFinish.")";
        $exPres = json_decode($_SESSION[$PRESIDENTS_JSON], true);

        $ctr = 0;
        $found = false;
        $remChar = array("(", ")");
        $index = count($exPres['exPresidents']) -1;

        while($ctr < count($exPres['exPresidents']) && !$found){
            $tmp = str_replace($remChar, "", $exPres['exPresidents'][$ctr]['date']);
            $tmp = explode("-",$tmp)[0];
            $tmp = intval($tmp);
            if($yStart < $tmp){
                $index = $ctr;
                $found = true;
            }
            $ctr = $ctr +1;
        }

        $presArray = array(
            "name" => $name, 
            "date" => $period
        );

        array_splice($exPres['exPresidents'], $index, 0, [$presArray]);

        $_SESSION[$PRESIDENTS_JSON] = json_encode($exPres);
    }
    
    function editDeleteExPresident($index){
        require 'constants.php';
        $roles = json_decode($_SESSION[$PRESIDENTS_JSON], true);
        
        $ctr = $index;
        while($ctr < count($roles['exPresidents'])-1){
            $roles['exPresidents'][$ctr] = $roles['exPresidents'][$ctr +1];
            $ctr = $ctr +1;
        }

        unset($roles['exPresidents'][count($roles['exPresidents'])-1]);

        $_SESSION[$PRESIDENTS_JSON] = json_encode($roles);
    }
    
    function editRenameDirector($position, $name){
        require 'constants.php';
        switch($position){
            case 'Vicepresidente':
                $position = "vpres";
                break;
            case 'Segretario':
                $position = "segr";
                break;
            case 'Tesoriere':
                $position = "tes";
                break;
            case 'Prefetto':
                $position = "pref";
                break;
        }

        $roles = json_decode($_SESSION[$PRESIDENTS_JSON], true);

        $roles["directors"][$position] = $name;

        $_SESSION[$PRESIDENTS_JSON] = json_encode($roles);
    }