#!/usr/bin/php
<?php

define('OPT_HELP', 'help');
define('OPT_ON_DUPLICATE', 'on-duplicate');
$onDuplicateValues = array('ignore', 'info', 'clean');

$options = getopt("", array(OPT_HELP, OPT_ON_DUPLICATE.':'));

if (isset($options[OPT_HELP])) {
    echo "Options :\n";
    echo "--".OPT_HELP."\n";
    echo "--".OPT_ON_DUPLICATE."={".implode($onDuplicateValues)."}\n";

    return;
}

$onDuplicate = 'info';
if (isset($options[OPT_ON_DUPLICATE])) {
    if (!in_array($options[OPT_ON_DUPLICATE], $onDuplicateValues)) {
        echo sprintf("Unexpected value %s for option %s", $options[OPT_ON_DUPLICATE], OPT_ON_DUPLICATE)."\n";

        return;
    }
    $onDuplicate = $options['on-duplicate'];
}

$dir = ".";

$files = scandir($dir);
foreach ($files as $file) {
    $path = $dir.DIRECTORY_SEPARATOR.$file;
    if (is_dir($path)) {
        continue;
    }

    $info = filemtime($path);
    $dateDir = $dir.DIRECTORY_SEPARATOR.date('Y-m-d', $info);
    if (!is_dir($dateDir)) {
        mkdir($dateDir, 0755, true);
    }

    $newPath = $dateDir.DIRECTORY_SEPARATOR.str_replace(" ", "_", $file);

    if (is_file($newPath)) {
        if ($onDuplicate === 'ignore') {
            continue;
        }

        echo "Destination file already exists: ";
        if (md5_file($path) !== md5_file($newPath)) {
            echo "files are not identical\n";
            continue;
        }

        if ($onDuplicate === 'info') {
            echo "Files are identical\n";
        } elseif ($onDuplicate === 'clean') {
            unlink($path);
            echo "Duplicate cleaned\n";
        }
    } else {
        if (rename($path, $newPath)) {
            echo "Move $path to $newPath\n";
        } else {
            echo "Unable to move $path to $newPath\n";
        }
    }
}
