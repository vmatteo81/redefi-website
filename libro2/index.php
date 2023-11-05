

<!DOCTYPE html>
<html>
<title>Le stagioni in tutti i sensi</title>
<head>
<link rel="stylesheet" type="text/css" href="styles.css">
    <title>Invia Email</title>
    <script type="text/javascript">
        function validateEmail(email) {
            var re = /\S+@\S+\.\S+/;
            return re.test(email);
        }

        function validateForm() {
            var email = document.getElementById("recipient_email").value;
            if (!validateEmail(email)) {
                alert("Inserisci un indirizzo email valido.");
                return false;
            }
            return true;
        }

        function sendEmail() {
            if (validateForm()) {
                var email = document.getElementById("recipient_email").value;
                var message = "Email inviata con successo a " + email + ".\r\nControlla anche la casella spam..non si sa mai!";
                alert(message); // Mostra un messaggio di conferma
                /*setTimeout(function () {
                    window.location.href = "index.php"; // Reindirizza alla pagina index dopo 2 secondi
                }, 2000);*/
            }
        }

    </script>
</head>
<body>
    <?php
    require '../assets/vendor/PHPMailer/src/Exception.php';
    require '../assets/vendor/PHPMailer/src/PHPMailer.php';
    require '../assets/vendor/PHPMailer/src/SMTP.php';
    
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    // Verifica se il modulo è stato inviato
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Recupera l'indirizzo email inserito nel modulo
        $recipient_email = $_POST["recipient_email"];

        // Invia l'email
        $subject = "INFO (LE STAGIONI IN TUTTI I SENSI)";
        $message = "<p>Ciao, sono Bianchi Veronica.</p>
        <p>Grazie per aver chiesto informazioni.</p>
        <p>Il libro contiene 80 attivit&agrave; didattiche pratiche che stimoleranno lo sviluppo del movimento, della comunicazione e del linguaggio, la creativit&agrave;, l'immaginazione, la coordinazione oculo-manuale, i sensi. Attivit&agrave; utili per lo sviluppo della percezione e della logica, la fantasia, la concentrazione e molto altro.</p>
        <p>Se fossi poi interessata all'acquisto, &egrave; possibile il pagamento via PayPal (la mail collegata al conto &egrave; veronicabianchi1984@virgilio.it) oppure attraverso un bonifico bancario, l'IBAN: IT84Y0760113900001009497718 (intestato a Bianchi Veronica) una volta effettuato il pagamento rispondi a questa email allegando la distinta di pagamento per ricevere il libro subito.</p>
        <p>Se i dati di chi pagher&agrave; non coincidono con i tuoi, inviami i dettagli per associare il pagamento a te rispondendo a questa email.</p>
        <p>L'importo base &egrave; di 7,99 euro (Sconto di 1 euro per ogni libro precedente acquistato LA CARICA DELLE 101 ATTIVITA e 31 ATTIVITA DI HALLOWEEN).</p>
        <p>In allegato ti invio l'indice.</p>
        <p>Grazie mille.</p>
        <p>Bianchi Veronica</p>";

        $mail = new PHPMailer(true);
    
        try {
    
            $mail->isSMTP();
            $mail->Host = "smtps.aruba.it";
            $mail->SMTPAuth = true;
            $mail->Username = "veronicabianchi1984@redefi.eu";
            $mail->Password = "Giotto35*";
            $mail->SMTPSecure = "ssl";
            $mail->Port = 465;

            $mail->addReplyTo('veronicabianchi1984@virgilio.it', 'Bianchi Veronica');
            $mail->addBCC("veronicabianchi1984@redefi.eu", "BCC");
            $mail->setFrom($mail->Username, "Bianchi Veronica");
            $mail->addAddress($recipient_email,$recipient_email);
           
            $mail->addAttachment( 'indice.pdf' , 'indice.pdf');
    
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;
    
            $mail->send();
        } 
        catch (Exception $e) {
            echo "Si è verificato un errore nell'invio dell'email. $e";
        }
    }
    ?>
    <div>
        <h1><strong style="color:red;">Nuovo</strong> libro disponibile in PDF (via e-mail)</br>con 80 attività per bambini dai 6 mesi.</br>💥IL PREZZO E' DI SOLI 7,99€ (Sconto di 1 € per ogni libro precedente acquistato LA CARICA DELLE 101 ATTIVITA e 31 ATTIVITA DI HALLOWEEN)</h1></br>
        <div class="intro-panel">
            <img src="intro.jpg" alt="Cover page" width="500" height="600">
        </div>
        <h2>Ecco alcune pagine del libro</h2>
        <div class="image-panel">
            <img src="immagine1.jpg" alt="Immagine 1" width="150">
            <img src="immagine2.jpg" alt="Immagine 2" width="150">
            <img src="immagine3.jpg" alt="Immagine 3" width="150">
        </div>
        <h2>Inserisci la tua email nel box sottostante, premendo "Invia" riceverai indice e istruzioni su come acquistare.</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="email" name="recipient_email" id="recipient_email"  style="max-width: 500px; font-size: 40px;" placeholder="lamiaemail@gmail.com" required>
            <input type="submit" value="Invia" onclick="sendEmail();" style="font-size: 40px;">
        </form>
    </div>
</body>
</html>
