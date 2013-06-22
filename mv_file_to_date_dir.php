#!/usr/bin/php
<?php

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
  if(rename($path, $newPath)) {
		echo "Move $path to $newPath\n";
	} else {
		echo "Unable to move $path to $newPath\n";
  }
}

