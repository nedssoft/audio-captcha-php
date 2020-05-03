<?php
session_start();
$status = '';

if (isset($_POST['captcha']) && ($_POST['captcha'] != "")) {
    // Validation: Checking entered captcha code with the generated captcha code

    // trim the captcha input
    $captcha = trim($_POST['captcha']);
    // Sanitize and strip accent
    $captcha = iconv('UTF-8', 'ASCII//TRANSLIT', $captcha);
    
    if (strcmp($_SESSION['captcha'], $captcha) != 0) {
        // Note: the captcha code is compared case insensitively.
        // if you want case sensitive match, update the check above to strcmp()
        $status = "<p class='status' style='color:#FFFFFF; font-size:20px'><span style='background-color:#FF0000;'>Wrong captcha!!, try again</span></p>";
    } else {
        $status = "<p class='status' style='color:#FFFFFF; font-size:20px'><span style='background-color:#46ab4a;'>Correct captcha!!</span></p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio Captcha</title>
    <style>
        * {
            padding: 0;
            margin: 0;
        }
        html {
            font-size: 62.5%;
        }
        body,
        html {
            width: 100vw;
            height: 100%;

        }

        .container {
             width: 100%;
            height: 100%;
            
        }  

        section {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            width: 400px;
            margin: 50px auto;
        }

        audio {
            height: 30px;
            margin: 20px auto;
            outline: none;
        }

        @media (max-width:500px) {
            section {
                width: 100%;
            }
        }

        .header {
            width: 100%;
            height: 100px;
            background: black;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .header h1 {
            text-align: center;
            margin: auto;
        }
        section form {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            font-size: 1.5rem;
        }

        form input {
            outline: none;
            width: 60%;
            padding:0.5rem;
            border-radius: 6px;
            font-size:1.2rem;
            border: 1px solid #15202b;
        }

        form button {
            width: 40%;
            padding: 0.5rem;
            border-radius:6px;
            cursor: pointer;
            font-size:1.5rem;
            border: 1px solid #15202b;
            outline: none;
        }
        form button:hover {
            background:  #15202b;
            transition: 0.2s ease-in-out;
            color: #ffffff;
        }
        label {
            margin-top: 0.5rem;
        }
   
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <h1>A Captcha Implementation With Audio</h1>
        </header>
        <section class="">
            <?= $status; ?>
            <form name="form" method="post" action="">
                <label><strong>Enter Captcha:</strong></label>
                    <input type="text" name="captcha" />
                    <p><br /><img src="captcha.php?rand=<?= rand(); ?>" id='captcha_image'></p>
                    <p>Can't read the image? <a href='javascript: refreshCaptcha();'>click here</a> to refresh</p>
                    <p> <audio src="audio.mp3?rand=<?php echo rand(); ?>" id="audio" width="100px" controls></audio></p>
                    <button name="submit">Verify</button>
            </form>
        </section>
    </div>

    <script>
        //Refresh Captcha
        function refreshCaptcha() {
            var img = document.images['captcha_image'];
            img.src = img.src.substring(0, img.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
            var audio = document.getElementById('audio');
            audio.src = audio.src.substring(0, audio.src.lastIndexOf("?")) + "?rand=" + Math.random() * 1000;
            audio.load();
        }
    </script>
</body>

</html>