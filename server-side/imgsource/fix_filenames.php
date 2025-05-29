<?php
$folder = 'server-side/imgsource/';
$files = scandir($folder);

foreach ($files as $file) {
    $newName = trim($file);

    if ($file !== '.' && $file !== '..' && $file !== $newName) {
        rename($folder . $file, $folder . $newName);
        echo "Renamed: '$file' → '$newName'<br>";
    }
}
?>
