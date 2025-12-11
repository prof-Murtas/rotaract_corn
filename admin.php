<?php
    require_once "PHP/constants.php";
    require_once "PHP/functions.php";

    /*INPUT KEYS*/
    $IN_HOME_IMG = "homeImg";
    $IN_INDEX = "inputIndex";
    $IN_TEXT = "inputTxt";
    $IN_CAROUSEL_IMG = "carouselImg";
    $IN_SELECTED_PDF = "selectedPdf"; 
    $IN_RENAME_PDF = "renamePdf";
    $IN_DELETE_PDF = "deletePdf";
    $IN_ADD_PDF = "newPdf";
    $IN_ADD_COLLABORATION = "newCollaboration";
    $IN_NEW_COLLAB_LINK = "newCollabLink";
    $IN_NEW_COLLAB_NAME = "newCollabName";
    $IN_RENAME_COLLAB = "renameCollab";
    $IN_RELINK_COLLAB = "relinkCollab";
    $IN_DELETE_COLLAB = "deleteCollab";
    $IN_EDIT_PAGE_TEXT = "editPageText";
    $IN_EDIT_PAGE_KEY = "key";
    $IN_EDIT_PAGE_LANG = "lang";
    $IN_ADD_PRESIDENT = "newPresident";
    $IN_NEW_PRES_NAME = "newPresName";
    $IN_NEW_PRES_START = "newPresStart";
    $IN_NEW_PRES_END = "newPresEnd";
    $IN_ADD_EX_PRES = "newExPres";
    $IN_DELETE_EX_PRES = "deleteExPres";
    $IN_RENAME_DIRECTOR = "renameDirector";

    $INPUT_TYPE = "action";
    /*=====*/

    session_set_cookie_params(0); //distruggi la sessione all'uscita dal browser
    session_start();
    loadJsonInSession();

    if (!isset($_SESSION[$IS_LOGGED]) || !$_SESSION[$IS_LOGGED]){
        header("Location: PHP/logout.php"); //Redirigo al logout in maniera da ripulire la sessione
        exit;
    }

    require_once "PHP/edit.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST[$INPUT_TYPE])){
            switch ($_POST[$INPUT_TYPE]) {
                case $IN_HOME_IMG:
                    editHomeImg($_FILES[$IN_HOME_IMG]);
                    break;
                case $IN_CAROUSEL_IMG:
                    editCarouselImg($_FILES, $IN_CAROUSEL_IMG);
                    break;
                case $IN_RENAME_PDF:
                    editNamePdf($_POST[$IN_INDEX], $_POST[$IN_TEXT]);
                    break;
                case $IN_DELETE_PDF:
                    editDeletePdf($_POST[$IN_INDEX]);
                    break;
                case $IN_ADD_PDF:
                    editAddPdf($_FILES[$IN_ADD_PDF]);
                    break;
                case $IN_SELECTED_PDF:
                    editSelectedPdf((int)$_POST[$IN_INDEX] - 1);
                    break;
                case $IN_ADD_COLLABORATION:
                    editAddCollaboration($_POST[$IN_NEW_COLLAB_NAME], $_POST[$IN_NEW_COLLAB_LINK]);
                    break;
                case $IN_RENAME_COLLAB:
                    editRenameCollab($_POST[$IN_INDEX], $_POST[$IN_TEXT]);
                    break;
                case $IN_RELINK_COLLAB:
                    editRelinkCollab($_POST[$IN_INDEX], $_POST[$IN_TEXT]);
                    break;
                case $IN_DELETE_COLLAB:
                    editDeleteCollab($_POST[$IN_INDEX]);
                    break;
                case $IN_EDIT_PAGE_TEXT:
                    editPageText($_POST[$IN_EDIT_PAGE_KEY], $_POST[$IN_EDIT_PAGE_LANG], $_POST[$IN_TEXT]);
                    break;
                case $IN_ADD_PRESIDENT:
                    editAddPresident($_POST[$IN_NEW_PRES_NAME], $_POST[$IN_NEW_PRES_START], $_POST[$IN_NEW_PRES_END]);
                    break;
                case $IN_ADD_EX_PRES:
                    editAddExPres($_POST[$IN_NEW_PRES_NAME], $_POST[$IN_NEW_PRES_START], $_POST[$IN_NEW_PRES_END]);
                    break;
                case $IN_DELETE_EX_PRES:
                    editDeleteExPresident($_POST[$IN_INDEX]);
                    break;
                case $IN_RENAME_DIRECTOR:
                    editRenameDirector($_POST[$IN_INDEX], $_POST[$IN_TEXT]);
                    break;
            }
        }
        header("Refresh:0");
        exit;
    }

    $configJson = json_decode($_SESSION[$CONFIG_JSON], true);

    //Img
    $homeImg = $configJson[$HOME_IMAGE];

    $carouselImages = $configJson[$CAROUSEL_IMAGES];
    $carouselLength = sizeof($carouselImages);

    //Presidents
    $presidentsJsonContent = json_decode($_SESSION[$PRESIDENTS_JSON], true);
    $presidents = $presidentsJsonContent[$EX_PRESIDENTS];
    $directorNames = $presidentsJsonContent[$DIRECTORS];
    $numPresidents = sizeof($presidents);

    $DIR_ROLE = "role";
    $DIR_NAME = "name";

    $DIR_PRESIDENT_ROLE = "Presidente";
    $directors = [
        [
            $DIR_ROLE => $DIR_PRESIDENT_ROLE,
            $DIR_NAME => $directorNames[$ROLE_PRESIDENT]
        ],
        [
            $DIR_ROLE => "Vicepresidente",
            $DIR_NAME => $directorNames[$ROLE_VICE_PRESIDENT]
        ],
        [
            $DIR_ROLE => "Segretario",
            $DIR_NAME => $directorNames[$ROLE_SECRETARY]
        ],
        [
            $DIR_ROLE => "Tesoriere",
            $DIR_NAME => $directorNames[$ROLE_TREASURE]
        ],
        [
            $DIR_ROLE => "Prefetto",
            $DIR_NAME => $directorNames[$ROLE_PREFECT]
        ]
    ];

    //PDF
    $bulletinPdfs = filterPdf(scandir($PDF_BULLETIN_FOLDER));

    $selectedBulletin = $configJson[$CURRENT_BULLETIN];
    $validBulletin = true;
    if (!is_file("Media/" . $selectedBulletin)){
        $selectedBulletin = "ERRORE: bollettino non selezionato o non esistente";
        $validBulletin = false;
    }

    //Collaborations
    $collaborations = json_decode($_SESSION[$COLLAB_JSON], true)[$COLLABORATIONS];

    //Texts
    $texts = [
        "it" => json_decode($_SESSION[$TXT_JSON][$IT_TEXTS_FILE], true),
        "en" => json_decode($_SESSION[$TXT_JSON][$EN_TEXTS_FILE], true),
        "de" => json_decode($_SESSION[$TXT_JSON][$DE_TEXTS_FILE], true)
    ];

    $italianTexts = $texts["it"];
    $commonKeys = [
        $TXT_HOME,
        $TXT_WHO_WE_ARE,
        $TXT_SERVICE,
        $TXT_EVENTS,
        $TXT_COLLAB,
        $TXT_CONTACTS,
        $TXT_LEGAL_RES,
        $TXT_ADDRESS,
        $TXT_DISTRICT,
        $TXT_ROTARY_TRENTO
    ];

    $PAGE_KEYS = "keys";
    $PAGE_NAME = "name";
    $pages = [
        [
            $PAGE_NAME => "In comune (Header e Footer)",
            $PAGE_KEYS => $commonKeys
        ],
        [
            $PAGE_NAME => $italianTexts[$TXT_HOME],
            $PAGE_KEYS => getTextKeysFromPage("index.php", $commonKeys)
        ],
        [
            $PAGE_NAME => $italianTexts[$TXT_WHO_WE_ARE],
            $PAGE_KEYS => getTextKeysFromPage("Pages/whoWeAre.php", $commonKeys)
        ],
        [
            $PAGE_NAME => $italianTexts[$TXT_SERVICE],
            $PAGE_KEYS => getTextKeysFromPage("Pages/service.php", $commonKeys)
        ],
        [
            $PAGE_NAME => $italianTexts[$TXT_COLLAB],
            $PAGE_KEYS => getTextKeysFromPage("Pages/collaborations.php", $commonKeys)
        ],
        [
            $PAGE_NAME => $italianTexts[$TXT_CONTACTS],
            $PAGE_KEYS => getTextKeysFromPage("Pages/contacts.php", $commonKeys)
        ],
        [
            $PAGE_NAME => $italianTexts[$TXT_EX_TITLE],
            $PAGE_KEYS => getTextKeysFromPage("Pages/exreport.php", $commonKeys)
        ]
    ];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>

    <link rel="stylesheet" href="CSS/adminStyle.css">
    <link rel="stylesheet" href="CSS/commonStyle.css">
</head>
<body>
    <div id="header">
        <a class="logoContainer" href="index.php"><img class="logo" src="Media/logo.png"></a>

        <h1 id="title">GESTIONE PAGINE</h1>

        <div id="buttons">
            <form action="PHP/logout.php" method="get">
                <button id="logoutBtn" type="submit">Logout</button>
            </form>
        </div>
    </div>

    <div id="mainContent">
        <!--DIREZIONE E PRESIDENTI-->
        <div class="editableElementContainer">
            <div class="sectionContainer">
                <div class="arrow right"></div>
                <h2>Consiglio Direttivo</h2>
                <div class="descriptionContainer">
                    <p>Modifica il consigio direttivo o lo storico dei presidenti</p>
                </div>
            </div>

            <div class="modificationContainer">
                <div class="verticalLineContainer">
                    <div class="linePoint"></div>
                    <div class="lineBody"></div>
                </div>
                <div class="editableContent">
                    <br>
                    <p><span>Attuale Direzione</span></p>
                    <br>
                    <br>
                    <?php foreach ($directors as $director) {
                        ?>
                            <form class="role" action="" method="POST">
                                <p><span><?php echo $director[$DIR_ROLE].": "; ?></span><?php echo $director[$DIR_NAME]?></p>
                                <?php if ($director[$DIR_ROLE] != $DIR_PRESIDENT_ROLE){
                                    ?>
                                    <div class="directorEditInputs">
                                        <input type="hidden" name="<?php echo $IN_INDEX;?>" value="<?php echo $director[$DIR_ROLE];?>">
                                        <input type="text" name="<?php echo $IN_TEXT;?>" placeholder="<?php echo "Nome " . $director[$DIR_ROLE];?>" required>         
                                        <button type="submit" name="<?php echo $INPUT_TYPE;?>" value="<?php echo $IN_RENAME_DIRECTOR;?>">Modifica</button>
                                    </div>
                                    <?php
                                    }
                                ?>
                            </form>
                        <?php
                        }
                    ?>

                    <br>
                    <form class="addForm" action="" method="post">
                        <button type="submit" name="<?php echo $INPUT_TYPE;?>" value="<?php echo $IN_ADD_PRESIDENT;?>" class="addBtn">Cambia Presidente</button>
                        <div>
                            <label for="inputNewPresName">Nome nuovo presidente</label>
                            <input type="text" name="<?php echo $IN_NEW_PRES_NAME;?>" id="inputNewPresName" required>
                        </div>
                        <div>
                            <label for="inputNewPresStartDate">Inizio mandato presidente corrente</label>
                            <input type="date" name="<?php echo $IN_NEW_PRES_START;?>" id="inputNewPresStartDate" required>
                        </div>
                        <div>
                            <label for="inputNewPresEndDate">Fine mandato presidente corrente</label>
                            <input type="date" name="<?php echo $IN_NEW_PRES_END;?>" id="inputNewPresEndDate" required>
                        </div>
                    </form>
                    
                    <br>
                    <p><span>Ex-Presidenti</span></p>
                    <br>
                    <br>

                    <div class="grid" id="presidentsGrid">
                        <?php
                        for ($i = $numPresidents - 1; $i >= 0; $i--){
                            $currentPresident = $presidents[$i];
                            ?>
                            <div>
                                <div>
                                    <p><?php echo "<span>".($numPresidents - $i) . ".</span> " . $currentPresident[$PRESIDENT_NAME] ?></p>
                                    <p class="presDate"><?php echo $currentPresident[$PRESIDENT_DATE] ?></p>
                                </div>
                                
                                <form action="" method="post">
                                    <input type="hidden" name="<?php echo $IN_INDEX;?>" value="<?php echo $i;?>">
                                    <button type="submit" name="<?php echo $INPUT_TYPE;?>" value="<?php echo $IN_DELETE_EX_PRES;?>">Rimuovi</button>
                                </form>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <br>
                    <form class="addForm" action="" method="post">
                        <button type="submit" name="<?php echo $INPUT_TYPE;?>" value="<?php echo $IN_ADD_EX_PRES;?>" class="addBtn">Aggiungi</button>
                        <div>
                            <label for="inputExPresName">Nome nuovo presidente</label>
                            <input type="text" name="<?php echo $IN_NEW_PRES_NAME;?>" id="inputExPresName" required>
                        </div>
                        <div>
                            <label for="inputExPresStartDate">Inizio mandato</label>
                            <input type="date" name="<?php echo $IN_NEW_PRES_START;?>" id="inputExPresStartDate" required>
                        </div>
                        <div>
                            <label for="inputExPresEndDate">Fine mandato</label>
                            <input type="date" name="<?php echo $IN_NEW_PRES_END;?>" id="inputExPresEndDate" required>
                        </div>
                    </form>

                    <br>
                </div>
            </div>
        </div>

        <!--BOLLETTINI-->
        <div class="editableElementContainer">
            <div class="sectionContainer">
                <div class="arrow right"></div>
                <h2>Bollettino</h2>
                <div class="descriptionContainer">
                    <p>Aggiungi/rimuovi il PDF di un bollettino</p>
                </div>
            </div>

            <div class="modificationContainer">
                <div class="verticalLineContainer">
                    <div class="linePoint"></div>
                    <div class="lineBody"></div>
                </div>
                <div class="editableContent">
                    <br>
                    <p><span>Lista bollettini pubblicati</span></p>
                    <br>
                    <br>

                    <form action="" method="post" enctype="multipart/form-data" id="currentBulletinContainer">
                        <p><span>Bollettino attualmente selezionato: </span><?php if ($validBulletin){
                            ?><a href="<?php echo "Media/" . $selectedBulletin;?>" target="_blank"><?php echo $selectedBulletin;?></a><?php
                            } else {
                                echo $selectedBulletin;
                            }
                        ?></p>

                        <div>
                            <label for="pdfNumberSelection">N. Bollettino</label>
                            <input type="number" name="<?php echo $IN_INDEX;?>" id="pdfNumberSelection" required>
                            <button type="submin" name="<?php echo $INPUT_TYPE;?>" value="<?php echo $IN_SELECTED_PDF;?>" >Cambia</button>
                        </div>
                    </form>

                    <br>

                    <div id="pdfGrid" class="grid">
                        <?php
                        for ($i=0; $i < sizeof($bulletinPdfs); $i++) { 
                            ?>
                            <div>
                                <p><span><?php echo $i + 1 . ". ";?></span><a href="<?php echo "$PDF_BULLETIN_FOLDER/" . $bulletinPdfs[$i];?>" target="_blank">
                                    <?php echo $bulletinPdfs[$i];?>
                                </a></p>
                                <form action="" method="post">
                                    <div>
                                        <input type="text" name="<?php echo $IN_TEXT;?>" id="<?php echo "inputPdfText". $i + 1;?>" placeholder="Nuovo nome" required>
                                    </div>

                                    <input type="hidden" name="<?php echo $IN_INDEX;?>" value="<?php echo $i;?>">
                                    <button type="submit" name="<?php echo $INPUT_TYPE;?>" value="<?php echo $IN_RENAME_PDF;?>">Rinomina</button>
                                    <button type="submit" name="<?php echo $INPUT_TYPE;?>" value="<?php echo $IN_DELETE_PDF;?>" formnovalidate>Rimuovi</button>
                                </form>
                            </div>
                            <?php
                        }
                        ?>
                    </div>

                    <br>
                    <form class="addForm" action="" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="<?php echo $INPUT_TYPE;?>" value="<?php echo $IN_ADD_PDF;?>">
                        <button type="button" onclick="<?php echo "document.getElementById('inputAddPdf').click()"; ?>" class="addBtn">Aggiungi</button>
                        <input onchange="this.form.submit()" type="file" name="<?php echo $IN_ADD_PDF;?>" id="<?php echo "inputAddPdf";?>">
                    </form>
                    <br>
                </div>
            </div>
        </div>

        <!--COLLABORAZIONI-->
        <div class="editableElementContainer">
            <div class="sectionContainer">
                <div class="arrow right"></div>
                <h2>Collaborazioni</h2>
                <div class="descriptionContainer">
                    <p>Modifica le collaborazioni nella pagina "collaborazioni"</p>
                </div>
            </div>

            <div class="modificationContainer">
                <div class="verticalLineContainer">
                    <div class="linePoint"></div>
                    <div class="lineBody"></div>
                </div>

                <div class="editableContent">
                    <br>
                    <p><span>Collaborazioni attuali</span></p>
                    <br>
                    <br>

                    <div id="collaborationsContainer">
                        <?php for ($i=0; $i < sizeof($collaborations); $i++) { 
                            ?>
                            <div>
                                <p><span><?php echo $i + 1 . ". ";?></span><a href="<?php echo $collaborations[$i][$COLLABORATION_LINK];?>" target="_blank"><?php echo $collaborations[$i][$COLLABORATION_NAME];?></a></p>
                                <form action="" method="post">
                                    <div>
                                        <label for="<?php echo "inputCollabText". $i + 1;?>">Modifica: </label>
                                        <input type="text" name="<?php echo $IN_TEXT;?>" id="<?php echo "inputCollabText". $i + 1;?>" placeholder="Inserire la modifica da effettuare" required>
                                    </div>
                                    <input type="hidden" name="<?php echo $IN_INDEX;?>" value="<?php echo $i;?>">
                                    <button type="submit" name="<?php echo $INPUT_TYPE;?>" value="<?php echo $IN_RENAME_COLLAB;?>">Modifica Nome</button>
                                    <button type="submit" name="<?php echo $INPUT_TYPE;?>" value="<?php echo $IN_RELINK_COLLAB;?>">Modifica Link</button>
                                    <button type="submit" name="<?php echo $INPUT_TYPE;?>" value="<?php echo $IN_DELETE_COLLAB;?>" formnovalidate>Rimuovi</button>
                                </form>
                            </div>
                            <?php
                            }
                        ?>
                    </div>

                    <form class="addForm" action="" method="post">
                        <button type="submit" name="<?php echo $INPUT_TYPE;?>" value="<?php echo $IN_ADD_COLLABORATION;?>" class="addBtn">Aggiungi</button>
                        <div>
                            <label for="inputCollabName">Nome</label>
                            <input type="text" name="<?php echo $IN_NEW_COLLAB_NAME;?>" id="inputCollabName" required>
                        </div>
                        <div>
                            <label for="inputCollabLink">Link</label>
                            <input type="text" name="<?php echo $IN_NEW_COLLAB_LINK;?>" id="inputCollabLink" required>
                        </div>
                    </form>
                    <br>
                </div>
            </div>
        </div>

        <!--TESTI E IMMAGINI-->
        <div class="editableElementContainer">
            <div class="sectionContainer">
                <div class="arrow right"></div>
                <h2>Testi e Immagini</h2>
                <div class="descriptionContainer">
                    <p>Modifica i testi o cambia le immagini di tutte le pagine</p>
                </div>
            </div>

            <div class="modificationContainer">
                <div class="verticalLineContainer">
                    <div class="linePoint"></div>
                    <div class="lineBody"></div>
                </div>
                <div id="editablePages" class="editableContent">
                    <br>
                    <p><span>Testi</span></p>
                    <br>
                    <br>

                    <?php foreach ($pages as $page) {
                        ?>
                            <div class="editableElementContainer">
                                <div class="sectionContainer">
                                    <div class="arrow right"></div>
                                    <p><?php echo $page[$PAGE_NAME];?></p>
                                </div>

                                <div class="modificationContainer">
                                    <div class="verticalLineContainer">
                                        <div class="linePoint"></div>
                                        <div class="lineBody"></div>
                                    </div>
                                    <div class="editableContent">
                                        <?php 
                                            $textKeys = $page[$PAGE_KEYS];

                                            foreach ($textKeys as $key) {
                                            ?>
                                                <div class="editableElementContainer ">
                                                    <div class="sectionContainer">
                                                        <div class="arrow right"></div>
                                                        <p><?php echo $italianTexts[$key];?></p>
                                                    </div>

                                                    <div class="modificationContainer">
                                                        <div class="verticalLineContainer">
                                                            <div class="linePoint"></div>
                                                            <div class="lineBody"></div>
                                                        </div>
                                                        <form action="" method="post" class="editableContent">
                                                            <input type="hidden" name="<?php echo $IN_EDIT_PAGE_KEY;?>" value="<?php echo $key;?>">
                                                            <input type="hidden" name="<?php echo $IN_EDIT_PAGE_LANG;?>" id="<?php echo $key."EditLang";?>">
                                                            <input type="hidden" name="<?php echo $INPUT_TYPE;?>", value="<?php echo $IN_EDIT_PAGE_TEXT;?>">

                                                            <?php foreach ($texts as $language => $languageTexts) {
                                                                ?>
                                                                <div>
                                                                    <p><span><?php echo "$language: ";?></span><?php echo $languageTexts[$key];?></p>
                                                                    <button type="button" onclick="setupLangInputValue('<?php echo $key;?>' , '<?php echo $language;?>' , this)">Modifica</button>
                                                                </div>
                                                                <?php
                                                            }
                                                            ?>

                                                            <textarea class="pageTextInput" name="<?php echo $IN_TEXT;?>" rows="1" placeholder="Inserire il nuovo testo" required></textarea>
                                                        </form>
                                                    </div>
                                                </div>
                                            <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                    }
                    ?>

                    <br>
                    <p><span>Immagini Carosello/Home</span></p>
                    <br>
                    <br>

                    <div>
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="<?php echo $INPUT_TYPE;?>" value="<?php echo $IN_HOME_IMG;?>">
                            <div id="editableHomeImg">
                                <a href="<?php echo "Media/$homeImg";?>" target="_blank"><p>Immagine Home</p></a>
                                <button type="button" onclick="document.getElementById('inputHomeImg').click()">Cambia</button>
                                <input onchange="this.form.submit()" type="file" name="<?php echo $IN_HOME_IMG;?>" id="inputHomeImg">
                            </div><br><br>
                        </form>
                        <form action="" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="<?php echo $INPUT_TYPE;?>" value="<?php echo $IN_CAROUSEL_IMG;?>">
                            <div id="imgGrid" class="grid">
                                <?php for ($i = 0; $i < $carouselLength; $i++) {
                                    ?>
                                    <div>
                                        <a href="<?php echo "Media/".$carouselImages[$i];?>" target="_blank"><p><?php echo "Carosello " . $i + 1;?></p></a>
                                        <button type="button" onclick="<?php echo "document.getElementById('inputCarouselImg" . $i + 1 . "').click()"; ?>">Cambia</button>
                                        <input onchange="this.form.submit()" type="file" name="<?php echo $IN_CAROUSEL_IMG . $i + 1;?>" id="<?php echo "inputCarouselImg" . $i + 1;?>">
                                    </div>
                                    <?php
                                    }
                                ?>
                            </div>
                        </form>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </div>

    <div id="footer">
        <form action="Pages/changePassword.php" method="get">
            <button id="passwordBtn" type="submit">Cambia Password</button>
        </form>

        <form action="PHP/saveConfiguration.php" method="get">
            <button id="saveBtn" type="submit">Salva</button>
        </form>
    </div>

    <script src="JS/adminScript.js"></script>
</body>
</html>