<?php
// Leer mensajes desde el archivo de texto
$filename = 'messages.txt';
if (file_exists($filename)) {
    $messages = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Mostrar los mensajes más recientes primero
    $messages = array_reverse($messages);

    // Mostrar cada mensaje en el tablón
    foreach ($messages as $message) {
        echo '<p>' . htmlspecialchars($message) . '</p>';
    }
}
?>
