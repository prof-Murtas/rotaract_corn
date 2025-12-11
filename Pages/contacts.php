<?php 
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '../PHPMailer/src/Exception.php';
    require '../PHPMailer/src/PHPMailer.php';
    require '../PHPMailer/src/SMTP.php';
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $texts[$TXT_CONTACTS]; ?></title>
    <link rel="stylesheet" href="../CSS/commonStyle.css">
    <link rel="stylesheet" href="../CSS/contactsStyle.css">
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
        <main class="container">

            <div class="contactInfo">
                <h3><?php echo $texts[$TXT_CONTACT_TITLE]; ?></h3>

                <div class="infoCenter">
                    <div class="infoItem">
                        <svg class="icon" viewBox="0 0 24 24"><path d="M12 2C8.1 2 5 5.1 5 9c0 5.3 7 13 7 13s7-7.7 7-13c0-3.9-3.1-7-7-7zm0 9.5c-1.4 0-2.5-1.1-2.5-2.5S10.6 6.5 12 6.5s2.5 1.1 2.5 2.5S13.4 11.5 12 11.5z"/></svg>
                        <p><?php echo $texts[$TXT_ADDRESS]; ?></p>
                    </div>

                    <div class="socialLinks">
                        <a href="https://www.facebook.com/RotaractClubTrento" target="_blank" class="socialBtn facebook">
                            <svg viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7H8v-3h2.5V9.5a3.5 3.5 0 0 1 3.8-3.8h2.7v3h-2c-.8 0-1.2.4-1.2 1.2V12H17l-.5 3h-2.2v7A10 10 0 0 0 22 12z"/></svg>
                            Facebook
                        </a>

                        <a href="https://www.instagram.com/rotaractclubtrento" target="_blank" class="socialBtn instagram">
                            <svg viewBox="0 0 24 24"><path d="M7 2C4.2 2 2 4.2 2 7v10c0 2.8 2.2 5 5 5h10c2.8 0 5-2.2 5-5V7c0-2.8-2.2-5-5-5H7zm10 2c1.6 0 3 1.4 3 3v10c0 1.6-1.4 3-3 3H7c-1.6 0-3-1.4-3-3V7c0-1.6 1.4-3 3-3h10zm-5 3.5A5.5 5.5 0 1 0 17.5 13 5.5 5.5 0 0 0 12 7.5zm0 9A3.5 3.5 0 1 1 15.5 13 3.5 3.5 0 0 1 12 16.5zm5.8-10.8a1.1 1.1 0 1 0 1.1 1.1 1.1 1.1 0 0 0-1.1-1.1z"/></svg>
                            Instagram
                        </a>
                    </div>
                </div>

                <div class="mapContainer">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2768.0005054992876!2d11.119404976728042!3d46.07103109274534!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x478271359a342359%3A0x9ecd447b4e46808!2sRotary%20Club%20Trento!5e0!3m2!1sit!2sit!4v1762416004887!5m2!1sit!2sit"
                        width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

            <div class="contactForm">
                <h2><?php echo $texts[$TXT_WRITE_FORM]; ?></h2>

                <?php if (isset($_SESSION['errore'])): ?>
                    <div class="messageBox error">
                        <p><strong>❌ <?php echo $texts[$TXT_ERROR_MSG]; ?>:</strong> <?= $_SESSION['errore'] ?></p>
                    </div>
                    <?php unset($_SESSION['errore']); ?>
                <?php elseif (isset($_SESSION['successo'])): ?>
                    <div class="messageBox success">
                        <p><strong>✅ <?php echo $texts[$TXT_SUCCESS_MSG]; ?></strong></p>
                    </div>
                    <?php unset($_SESSION['successo']); ?>
                <?php endif; ?>

                <form method="POST" action="">
                    <label for="nome"><?php echo $texts[$TXT_NAME_LABEL]; ?></label>
                    <input type="text" id="nome" name="nome" required>

                    <label for="email"><?php echo $texts[$TXT_EMAIL_LABEL]; ?></label>
                    <input type="email" id="email" name="email" required>

                    <label for="messaggio"><?php echo $texts[$TXT_MESSAGE_LABEL]; ?></label>
                    <textarea id="messaggio" name="messaggio" required></textarea>

                    <label class="privacyBox">
                        <input type="checkbox" name="privacy" required>
                        <span>
                            <?php echo $texts[$TXT_PRIVACY_ACCEPT]; ?><a href="../Documenti/Informativa_Privacy.pdf" target="_blank"><?php echo $texts[$TXT_PRIVACY_LINK]; ?></a>
                        </span>
                    </label>

                    <button type="submit" id="invia"><?php echo $texts[$TXT_SUBMIT_BTN]; ?></button>
                </form>
            </div>

        </main>
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