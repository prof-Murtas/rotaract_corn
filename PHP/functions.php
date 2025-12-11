<?php
    function filterPDF($fileArray) {
        return filterFiles($fileArray, ["pdf"]);
    }

    function loadJsonInSession($mainFolder = ""){
        require "constants.php";

        if (session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }

        if (!isset($_SESSION[$CONFIG_JSON])){
            $_SESSION[$CONFIG_JSON] = file_get_contents($mainFolder . $CONFIG_FILE);
        }
        if (!isset($_SESSION[$TXT_JSON])){
            $_SESSION[$TXT_JSON] = [];

            foreach ($TEXT_FILES as $txtFile) {
                $_SESSION[$TXT_JSON][$txtFile] = file_get_contents($mainFolder . $txtFile);
            }
        }
        if (!isset($_SESSION[$COLLAB_JSON])){
            $_SESSION[$COLLAB_JSON] = file_get_contents($mainFolder . $COLLABORATIONS_FILE);
        }
        if (!isset($_SESSION[$PRESIDENTS_JSON])){
            $_SESSION[$PRESIDENTS_JSON] = file_get_contents($mainFolder . $PRESIDENTS_FILE);
        }
    }

    function filterFiles($fileArray, $extensions){
        $fileArray = array_diff($fileArray, array('.', '..'));

        $newArray = array();
        foreach ($fileArray as $file) {
            foreach ($extensions as $extension) {
                if (strtolower(pathinfo($file, PATHINFO_EXTENSION)) === $extension){
                    $newArray[] = $file;
                }
            }
        }

        return $newArray;
    }

    function setLanguage($lang = null) {
        require "constants.php";

        if (!isset($_COOKIE[$LANGUAGE_COOKIE])){
            $textFileName = $IT_TEXTS_FILE;
        } else if(is_null($lang)){
            $textFileName = $_COOKIE[$LANGUAGE_COOKIE]; //Estendo la durata del cookie
        } else {
            switch ($lang) {
                case $ITALIAN:
                    $textFileName = $IT_TEXTS_FILE;
                    break;
                case $GERMAN:
                    $textFileName = $DE_TEXTS_FILE;
                    break;
                case $ENGLISH:
                    $textFileName = $EN_TEXTS_FILE;
                    break;
                default:
                    $textFileName = $IT_TEXTS_FILE;
                    break;
            }
        }

        setcookie($LANGUAGE_COOKIE, $textFileName, time() + 60*60*24*365, "/","",true, true);
        return $textFileName;
    }

    function getLanguageImage($textName){
        require "constants.php";

        $imageName = "it";
        switch ($textName) {
            case $IT_TEXTS_FILE:
                $imageName = "it";
                break;
            case $DE_TEXTS_FILE:
                $imageName = "de";
                break;
            case $EN_TEXTS_FILE:
                $imageName = "en";
                break;
        }

        return "Media/$imageName.png";
    }

    function getTextKeysFromPage($page, $removeArr) {
        require "constants.php";
        $content = file_get_contents($page);

        //Regex
        $pattern = '/\$texts\[\s*(\$[a-zA-Z0-9_]+)\s*\]/';
        $keys = [];
        //$matches[1] contiene i nomi delle variabili tra parentesi quadre
        if (preg_match_all($pattern, $content, $matches)) {
            $foundVars = array_unique($matches[1]);

            foreach ($foundVars as $varNameWithDollar) {
                $varName = substr($varNameWithDollar, 1);

                // $$VAR legge il valore della variabile corrispondente al valore della variabile
                //Esempio: $CIAO = 5, $VAR = "CIAO", $$VAR => 5
                global $$varName;
                $keys[] = $$varName;
            }
        }

        $keys = array_diff($keys, $removeArr);

        return $keys;
    }

    function saveJsonInFile($json, $filePath) {
        $file = fopen($filePath, "w");
        fwrite($file,$json);

        fclose($file);
    }