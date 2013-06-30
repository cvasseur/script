#!/usr/bin/php
<?php

$dir = ".";
$strFrom = " ";
$strTo = "_";

$files = scandir($dir);
foreach ($files as $file) {
  if (strpos($file, $strFrom) !== false) {
        $path = $dir.DIRECTORY_SEPARATOR.$file;
        $newPath = $dir.DIRECTORY_SEPARATOR.str_replace($strFrom, $strTo, $file);

        if (is_dir($newPath) || is_file($newPath)) {
            echo "Unable to rename $path, $newpath already in use\n";
          continue;
        }

        if (rename($path, $newPath)) {
            echo "Move $path to $newPath\n";
        } else {
            echo "Unable to move $path to $newPath\n";
        }

    }
}
