<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2ch-like Board</title>
    <style>
        body {
            font-family: 'ＭＳ Ｐゴシック', 'MS PGothic', sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
			background-image: url('https://cdn.discordapp.com/attachments/1164733443570159669/1184647790492663919/image.png?ex=658cbc3a&is=657a473a&hm=0cf56b72d01eaa3d470366267184713b62ee549ad1f6ce6a2c706ef33f8dbed7&');
            background-repeat: repeat; /* Repetir la imagen */
            background-size: auto; /* No ampliar la imagen */
            color: #000;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            background: ;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 0px;
        }

        h1 {
            color: #117743;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
            padding: 10px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 0px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input,
        textarea {
            width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
        }

        #threads {
            background: #fff;
            border-radius: 0px;
            overflow: hidden;
            margin-top: 20px;
        }

        .thread-list {
            border: 1px solid #000;
            border-radius: 0px;
            overflow: hidden;
            background: #CCFFCC;
            margin-bottom: 20px;
        }

        .thread-list p {
            padding: 10px;
            margin: 0;
            font-weight: bold;
            border-bottom: 1px solid #000;
        }

        .thread-list a {
            display: block;
            padding: 10px;
            color: #000;
            text-decoration: none;
            border-bottom: 1px solid #000;
        }

        .thread-list a:last-child {
            border-bottom: none;
        }

        .thread {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            background: #f9f9f9;
            border-radius: 0; /* Bordes completamente cuadrados */
        }

        .thread p {
            margin: 0;
            padding: 10px 0;
        }

        .thread form {
            margin-top: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .thread a {
            text-decoration: none;
            color: #117743;
            font-weight: bold;
            margin-right: 10px;
        }

        .thread:first-child {
            background-color: #e8e8e8;
        }

        .reply {
            margin-left: 20px;
        }

        .thread-number {
            color: #777;
            font-size: 14px;
        }

        hr {
            border: none;
            height: 1px;
            color: #ddd;
            background-color: #ddd;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
		<center><iframe src="https://torotan.foroelectricos.com.es/banners.php" width="300" height="250" scrolling="no" border="0" marginwidth="0" style="border:none;max-width:800px;max-height:600px;"
						frameborder="0"></iframe></center>
        <h1>Torotan</h1>

        <!-- Formulario para enviar un nuevo hilo -->
        <form action="index.php" method="post">
            <label for="thread_title">Título del Hilo:</label>
            <input type="text" name="thread_title" id="thread_title" placeholder="Título del hilo opcional">
            <br>
            <label for="name">Nombre:</label>
            <input type="text" name="name" id="name" placeholder="Nombre opcional">
            <br>
            <label for="email">Correo electrónico:</label>
            <input type="text" name="email" id="email" placeholder="Correo electrónico opcional">
            <br>
            <label for="message">Mensaje:</label>
            <textarea name="message" id="message" rows="4" cols="50" required></textarea>
            <br>
            <input type="submit" value="Enviar">
        </form>

        <!-- Mostrar hilos y mensajes -->
        <?php
        // Manejo de la creación de hilos
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $thread_title = $_POST['thread_title'] ?? '';
            $name = $_POST['name'] ?? 'Anonymous';
            $email = $_POST['email'] ?? '';
            $message = $_POST['message'] ?? '';

            // Crear un nuevo hilo
            $thread_id = time(); // Se utiliza la marca de tiempo como ID del hilo
            $thread_filename = 'thread_' . $thread_id . '.txt';
            file_put_contents($thread_filename, "[title]{$thread_title}[/title]\n{$name}|{$email}|{$message}\n");
        }

        // Obtener hilos existentes
        $existing_threads = glob('thread_*.txt');

        // Mostrar enlaces a los hilos recientes
        echo '<div class="thread-list">';
        echo '<p><strong>Hilos Recientes:</strong></p>';
        foreach ($existing_threads as $filename) {
            $thread_id = str_replace(['thread_', '.txt'], '', basename($filename));

            // Obtener el título del hilo desde el archivo
            $title = '';
            $file_content = file_get_contents($filename);
            if (preg_match('/\[title\](.*?)\[\/title\]/s', $file_content, $matches)) {
                $title = htmlspecialchars($matches[1]);
            }

            echo '<a href="thread.php?id=' . $thread_id . '">' . ($title ? $title : 'No.' . $thread_id) . '</a>';
        }
        echo '</div>';

        // Mostrar hilos y mensajes
        foreach ($existing_threads as $filename) {
            $thread_id = str_replace(['thread_', '.txt'], '', basename($filename));
            echo '<div class="thread">';
            echo '<p class="thread-number"><a href="thread.php?id=' . $thread_id . '">No.' . $thread_id . '</a></p>';

            // Mostrar mensajes del hilo
            $messages = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            // Mostrar título del hilo
            if (!empty($messages) && preg_match('/\[title\](.*?)\[\/title\]/s', $messages[0], $matches)) {
                echo '<p class="thread-title">' . htmlspecialchars($matches[1]) . '</p>';
                unset($messages[0]); // Eliminar la línea del título para que no aparezca como mensaje
            }

            foreach ($messages as $message) {
                list($name, $email, $text) = explode('|', $message);
                echo '<p><strong>' . htmlspecialchars($name) . '</strong>';
                if ($email) {
                    echo ' (' . htmlspecialchars($email) . ')';
                }
                echo ': ' . htmlspecialchars($text) . '</p>';
            }

            // Formulario para responder al hilo
            echo '<form action="index.php" method="post">';
            echo '<input type="hidden" name="thread_id" value="' . $thread_id . '">';
            echo '<label for="name">Nombre:</label>';
            echo '<input type="text" name="name" id="name" placeholder="Nombre opcional">';
            echo '<br>';
            echo '<label for="email">Correo electrónico:</label>';
            echo '<input type="text" name="email" id="email" placeholder="Correo electrónico opcional">';
            echo '<br>';
            echo '<label for="reply_message">Responder:</label>';
            echo '<textarea name="reply_message" id="reply_message" rows="2" cols="50" required></textarea>';
            echo '<br>';
            echo '<input type="submit" value="Responder">';
            echo '</form>';

            echo '</div>';
            echo '<hr>';
        }
        ?>
    </div>
	<center>&copy; 2023 Torochan. All rights reserved.</center>
</body>
</html>