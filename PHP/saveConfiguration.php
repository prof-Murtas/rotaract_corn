<?php
    require "constants.php";
    require "functions.php";

    session_start();

    //Per questioni di sicurezza
    if (!isset($_SESSION[$IS_LOGGED]) || !$_SESSION[$IS_LOGGED]){
        header("Location: logout.php"); //Redirigo al logout in maniera da ripulire la sessione
        exit;
    }

    $configJsonArray = json_decode($_SESSION[$CONFIG_JSON], true);

    //Trasferisco i file dalle cartelle TMP alle rispettive cartelle
    $validFiles = [];
    foreach ($configJsonArray as $key => $value) {
        if (is_string($value)){
            $file = $value;
            $configJsonArray[$key] = str_replace($TMP_FOLDER, "", $value);
            rename("../Media/" . $file, "../Media/" . $configJsonArray[$key]);

            $validFiles[] = "../Media/" . $configJsonArray[$key];
        } else {
            for ($i = 0; $i < sizeof($value); $i++) {
                $file = $value[$i];
                $configJsonArray[$key][$i] = str_replace($TMP_FOLDER, "", $value[$i]);
                rename("../Media/" . $file, "../Media/" . $configJsonArray[$key][$i]);

                $validFiles[] = "../Media/" . $configJsonArray[$key][$i];
            }
        }
    }

    //Pulisco le cartelle dai file che non sono inclusi nel JSON
    $mediaFolders = [
        "../Media/HomeImg", 
        "../Media/carousel"
    ];

    foreach ($mediaFolders as $folder) {
        if (is_dir($folder)) {
            $filesInFolder = array_diff(scandir($folder), ["./","../",$TMP_FOLDER]);

            foreach ($filesInFolder as $f) {
                $fullPathToCheck = $folder . "/" . $f;

                if (is_file($fullPathToCheck) && !in_array($fullPathToCheck, $validFiles)) {
                    unlink($fullPathToCheck);
                }
            }
        }
    }

    $mediaFolders[] = "../Media/PDF";

    //Pulisco le cartelle TMP
    foreach ($mediaFolders as $folder) {
        $tmpPath = $folder . "/" .  $TMP_FOLDER;

        if (is_dir($tmpPath)) {
            // glob prende tutti i file dentro la cartella
            $filesInTmp = glob($tmpPath . "/*"); 
            
            foreach($filesInTmp as $trashFile){
                if(is_file($trashFile)){
                    unlink($trashFile);
                }
            }
        }
    }

    $_SESSION[$CONFIG_JSON] = json_encode($configJsonArray , JSON_PRETTY_PRINT);

    $saveParameters = [
        [
            "json" => $_SESSION[$CONFIG_JSON],
            "file" => $CONFIG_FILE
        ],
        [
            "json" => $_SESSION[$COLLAB_JSON],
            "file" => $COLLABORATIONS_FILE
        ],
        [
            "json" => $_SESSION[$PRESIDENTS_JSON],
            "file" => $PRESIDENTS_FILE
        ]
    ];

    foreach ($_SESSION[$TXT_JSON] as $langFile => $content) {
        $saveParameters[] = [
            "json" => $content,
            "file" => $langFile
        ];
    }

    foreach ($saveParameters as $parameters) {
        saveJsonInFile($parameters["json"], "../" . $parameters["file"]);
    }
    header("Location: ../index.php");
    
    
    
    