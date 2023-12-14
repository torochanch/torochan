<?php
// Verificar si se ha enviado un mensaje
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["message"])) {
    $message = $_POST["message"];

    // Guardar el mensaje en un archivo de texto
    $filename = 'messages.txt';
    $file = fopen($filename, 'a');
    fwrite($file, $message . PHP_EOL);
    fclose($file);
}

// Redirigir a la página principal después de enviar el mensaje
header("Location: index.html");
exit();
?>
