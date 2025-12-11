<?php
    require "../PHP/constants.php";
    require "../PHP/functions.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        if (isset($_POST["lang"])){
            setLanguage($_POST["lang"]);
        }
        header("Refresh:0");
        exit;
    }

    session_set_cookie_params(0); //distruggi la sessione all'uscita dal browser
    session_start();
    loadJsonInSession("../");

    $textFile = setLanguage();
    $texts = json_decode($_SESSION[$TXT_JSON][$textFile], true);
    $langImg = "../" . getLanguageImage($textFile);

    $configJson = json_decode($_SESSION[$CONFIG_JSON], true);
    $selectedBulletin = "../Media/" . $configJson[$CURRENT_BULLETIN];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texts[$TXT_SERVICE]; ?></title>

    <link rel="stylesheet" href="../CSS/commonStyle.css">
    <link rel="stylesheet" href="../CSS/serviceStyle.css">
</head>

<body>
    <div id="obscurer"></div>

    <div id="lateralSelection">
        <button id="exitBtn" onclick="hideLateralSelection()">X</button>

        <div id="lateralBtns">
            <a href="../index.php"><button><?php echo $texts[$TXT_HOME]; ?></button></a>
            <a href="whoWeAre.php"><button><?php echo $texts[$TXT_WHO_WE_ARE]; ?></button></a>
            <a href="service.php"><button><?php echo $texts[$TXT_SERVICE]; ?></button></a>
            <a href="calendar.php"><button><?php echo $texts[$TXT_EVENTS]; ?></button></a>
            <a href="collaborations.php"><button><?php echo $texts[$TXT_COLLAB]; ?></button></a>
            <a href="contacts.php"><button><?php echo $texts[$TXT_CONTACTS]; ?></button></a>
        </div>
    </div>

    
    <div id="header">
        <a class="logoContainer" href="../index.php">
            <img class="logo" src="../Media/logo.png">
        </a>

        <div class="dropdownBox">
            <div class="hoverDropdownBox">
                <img id="langImg" class="dropdownImg" src="<?php echo $langImg;?>">
                <div class="dropdownContent">
                    <form action="" method="POST">
                        <input type="submit" name="lang" value="<?php echo $ITALIAN;?>" class="btn"></input>
                        <input type="submit" name="lang" value="<?php echo $ENGLISH;?>" class="btn"></input>
                        <input type="submit" name="lang" value="<?php echo $GERMAN;?>" class="btn"></input>
                    </form>
                </div>
            </div>
        </div>

        <div id="buttons">
            <a href="../index.php"><button><?php echo $texts[$TXT_HOME]; ?></button></a>
            <a href="whoWeAre.php"><button><?php echo $texts[$TXT_WHO_WE_ARE]; ?></button></a>
            <a href="service.php"><button><?php echo $texts[$TXT_SERVICE]; ?></button></a>
            <a href="calendar.php"><button><?php echo $texts[$TXT_EVENTS]; ?></button></a>
            <a href="collaborations.php"><button><?php echo $texts[$TXT_COLLAB]; ?></button></a>
            <a href="contacts.php"><button><?php echo $texts[$TXT_CONTACTS]; ?></button></a>
        </div>

        <div id="menuHamburger" onclick="showLateralSelection()">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
    </div>


    <div id="content">

        <h1><?php echo $texts[$TXT_SERVICE]; ?></h1>
        <div id="textContainer">

            <div id="servicesList">
                <div class="serviceItem">
                    <p><?php echo $texts[$TXT_SERVICE_DESC]; ?></p>
                    <p><?php echo $texts[$TXT_FIVE_WAYS]; ?></p>
                </div>
                <div class="serviceItem">
                    <h2><?php echo $texts[$TXT_WAY1]; ?></h2>
                    <p><?php echo $texts[$TXT_WAY1_DESC]; ?></p>
                </div>

                <div class="serviceItem">
                    <h2><?php echo $texts[$TXT_WAY2]; ?></h2>
                    <p><?php echo $texts[$TXT_WAY2_DESC]; ?></p>
                </div>

                <div class="serviceItem">
                    <h2><?php echo $texts[$TXT_WAY3]; ?></h2>
                    <p><?php echo $texts[$TXT_WAY3_DESC]; ?></p>
                </div>

                <div class="serviceItem">
                    <h2><?php echo $texts[$TXT_WAY4]; ?></h2>
                    <p><?php echo $texts[$TXT_WAY4_DESC]; ?></p>
                </div>

                <div class="serviceItem">
                    <h2><?php echo $texts[$TXT_WAY5]; ?></h2>
                    <p><?php echo $texts[$TXT_WAY5_DESC]; ?></p>
                </div>

            </div> 
        </div>

        <div id="pdf">
            <h1><?php echo $texts[$TXT_PDF_TEXT]; ?></h1>
            <?php if (is_file($selectedBulletin)){
                ?>
                    <iframe src="<?php echo $selectedBulletin;?>" width="100%" height="600px"></iframe>
                <?php
                }
            ?>
            <form action="exReport.php" method="get">
                <button id="btnReports"><?php echo $texts[$TXT_VIEW_ALL_BULLETINS]; ?></button>
            </form>
        </div>
        <br>
        <br>
    </div> 

    <div id="footer">
        <div id="footerContent">

            <div id="registeredOffice">
                <h4><?php echo $texts[$TXT_LEGAL_RES]; ?></h4>
                <p><?php echo $texts[$TXT_ADDRESS]; ?></p>
            </div>

            <div id="externalWebsites">
                <div>
                    <h4><?php echo $texts[$TXT_DISTRICT]; ?></h4>
                    <p><a href="https://www.rotaract2060.it/">https://www.rotaract2060.it/</a></p>
                </div>

                <div>
                    <h4><?php echo $texts[$TXT_ROTARY_TRENTO]; ?></h4>
                    <p><a href="https://trento.rotary2060.org/">https://trento.rotary2060.org/</a></p>
                </div>
            </div>

            <a class="logoContainer" href="../index.php">
                <img class="logo" src="../Media/logo.png">
            </a>

        </div>
    </div>

    <script src="../JS/lateralSelection.js"></script>
</body>

</html>