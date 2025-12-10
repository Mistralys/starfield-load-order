<?php

declare(strict_types=1);

use AppUtils\FileHelper;
use AppUtils\FileHelper\FileInfo;

require_once __DIR__.'/vendor/autoload.php';

$referenceFile = FileInfo::factory(__DIR__.'/../Plugins-regular-stable.txt');
$targetFile = FileInfo::factory(__DIR__.'/../Plugins.txt');

$referenceLines = filterLines($referenceFile);
$targetLines = filterLines($targetFile);

echo 'Sorting '.$targetFile->getName()." according to ".$referenceFile->getName()."\n";

// sort target lines in order to keep the reference lines in the exact same order,
// appending all new lines at the end of the file.
$sortedTargetLines = [];
foreach ($referenceLines as $refLine) {
    foreach ($targetLines as $key => $targetLine) {
        if ($refLine === $targetLine) {
            $sortedTargetLines[] = $targetLine;
            unset($targetLines[$key]);
            break;
        }
    }
}

// append all remaining target lines that were not in the reference file
if (count($targetLines) > 0) {
    echo 'Appending '.count($targetLines)." new lines at the end of the file.\n";
    array_push($sortedTargetLines, ...$targetLines);
}

$targetFile->putContents(implode("\r\n", $sortedTargetLines)."\r\n");

echo 'Done.'."\n";

function filterLines(FileInfo $file) : array
{
    $lines = array_map('trim', FileHelper::readLines($file));
    $lines = array_map('strtolower', $lines);

    return array_filter($lines, function ($line) {
        return !str_starts_with(trim($line), '#') && trim($line) !== '';
    });
}

