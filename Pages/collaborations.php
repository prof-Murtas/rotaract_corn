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
    $collaborations = json_decode($_SESSION[$COLLAB_JSON], true)[$COLLABORATIONS];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texts[$TXT_COLLAB]; ?></title>
    <link rel="stylesheet" href="../CSS/commonStyle.css">
    <link rel="stylesheet" href="../CSS/collaborationsStyle.css">
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
        <a class="logoContainer" href="../index.php"><img class="logo" src="../Media/logo.png"></a>

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
        <div id="mainContainer">
            <h1 id="title"><?php echo $texts[$TXT_ROT_COLLAB]; ?></h1>
            <p><?php echo $texts[$TXT_DESC_COLLAB]; ?></p>
            <h2 id="subtitle"><?php echo $texts[$TXT_ACT_COLLAB]; ?></h2>
        
            <div id="collaborationContainer">
                <?php foreach ($collaborations as $collaboration) {
                    ?>
                        <a href="<?php echo $collaboration[$COLLABORATION_LINK];?>" target="_blank">
                        <div class="collaboration">
                            <h3><?php echo $collaboration[$COLLABORATION_NAME];?>:</h3>
                            <p><?php echo $texts[$TXT_SITE]; ?></p>
                        </div></a>
                    <?php
                    }
                ?>
            </div>
        </div>
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
            <a class="logoContainer" href="../index.php"><img class="logo" src="../Media/logo.png"></a>
        </div>
    </div>

    <script src="../JS/lateralSelection.js"></script>
</body>
</html>