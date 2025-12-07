<?php

declare(strict_types=1);

use AppUtils\FileHelper;
use AppUtils\FileHelper\FileInfo;

require_once __DIR__.'/vendor/autoload.php';

$referenceFile = FileInfo::factory(__DIR__.'/../Plugins-regular-stable.txt');
$targetFile = FileInfo::factory(__DIR__.'/../Plugins.txt');

$referenceLines = filterLines($referenceFile);
$targetLines = filterLines($targetFile);

// sort target lines in order to keep all lines from the reference file at the top
usort($targetLines, function($a, $b) use ($referenceLines) {
    $indexA = array_search($a, $referenceLines, true);
    $indexB = array_search($b, $referenceLines, true);
    $indexA = $indexA === false ? PHP_INT_MAX : $indexA;
    $indexB = $indexB === false ? PHP_INT_MAX : $indexB;
    return $indexA <=> $indexB;
});

$targetFile->putContents(implode("\r\n", $targetLines)."\r\n");

function filterLines(FileInfo $file) : array
{
    $lines = array_map('trim', FileHelper::readLines($file));
    $lines = array_map('strtolower', $lines);

    return array_filter($lines, function ($line) {
        return !str_starts_with(trim($line), '#') && trim($line) !== '';
    });
}

