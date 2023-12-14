<?php
// thread.php

// Obtener el ID del hilo desde la URL
$thread_id = isset($_GET['id']) ? $_GET['id'] : die('ID del hilo no especificado.');

// Leer mensajes desde el archivo de texto del hilo
$filename = 'thread_' . $thread_id . '.txt';
$messages = [];
if (file_exists($filename)) {
    $messages = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

// Verificar si se ha enviado una respuesta al hilo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["reply_message"])) {
        $reply_message = $_POST["reply_message"];

        // Guardar la respuesta en el archivo correspondiente al hilo
        $file = fopen($filename, 'a');
        fwrite($file, $reply_message . PHP_EOL);
        fclose($file);

        // Redirigir a esta página después de procesar la respuesta
        header("Refresh: 0");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hilo <?php echo $thread_id; ?></title>
        <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-image: url('https://cdn.discordapp.com/attachments/1164733443570159669/1184647790492663919/image.png?ex=658cbc3a&is=657a473a&hm=0cf56b72d01eaa3d470366267184713b62ee549ad1f6ce6a2c706ef33f8dbed7&');
            background-repeat: repeat; /* Repetir la imagen */
            background-size: auto; /* No ampliar la imagen */
        }

        h1 {
            color: #1E90FF;
        }

        form {
            margin-bottom: 20px;
        }

        textarea {
            width: 100%;
        }

        #thread-messages {
            border: 1px solid #ccc;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.7); /* Fondo semi-transparente para mejorar la legibilidad */
        }

        .thread {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #ccc;
        }

        .thread p {
            margin: 0;
            padding: 10px 0;
        }

        .thread form {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Hilo <?php echo $thread_id; ?></h1>
    
    <!-- Formulario para responder al hilo -->
    <form action="thread.php?id=<?php echo $thread_id; ?>" method="post">
        <label for="reply_message">Responder:</label>
        <textarea name="reply_message" id="reply_message" rows="2" cols="50" required></textarea>
        <br>
        <input type="submit" value="Responder">
    </form>

    <!-- Mostrar mensajes del hilo -->
    <div id="thread-messages">
        <?php
        foreach ($messages as $message) {
            echo '<p>' . htmlspecialchars($message) . '</p>';
        }
        ?>
    </div>
	<center>&copy; 2023 Torochan. All rights reserved.</center>
</body>
</html>
