<?php
/**
 * Script used to guarantee that the `Plugins.txt` entries are
 * sorted according to the reference file `Plugins-regular-stable.txt`.
 */

declare(strict_types=1);

use AppUtils\FileHelper;
use AppUtils\FileHelper\FileInfo;

require_once __DIR__.'/vendor/autoload.php';

$referenceFile = FileInfo::factory(__DIR__ . '/../Plugins.reference.txt');
$targetFile = FileInfo::factory(__DIR__.'/../Plugins.txt');

echo str_repeat('-', 64).PHP_EOL;
echo 'SAFEGUARDING PREVIOUS LOAD ORDER'.PHP_EOL;
echo str_repeat('-', 64).PHP_EOL;
echo PHP_EOL;
echo '- Detecting plugin files...';

$knownPlugins = FileHelper::createFileFinder('C:\Steam\steamapps\common\Starfield\Data')
    ->includeExtensions(array('esm', 'esp'))
    ->makeRecursive()
    ->setPathmodeStrip()
    ->getMatches();

$lookup = array();
foreach($knownPlugins as $fileName) {
    $lookup[strtolower($fileName)] = $fileName;
    echo '.';
}

echo 'OK'.PHP_EOL;
echo '- Found ['.count($knownPlugins).'] plugins.'.PHP_EOL;

$referenceLines = filterLines($referenceFile);
$targetLines = filterLines($targetFile);

echo '- Sorting ['.$targetFile->getName()."] according to [".$referenceFile->getName()."]".PHP_EOL;

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
    echo '- Appending ['.count($targetLines)."] new lines at the end of the file.\n";
    array_push($sortedTargetLines, ...$targetLines);
}

echo '- Adjusting file name case...';

foreach($sortedTargetLines as $idx => $targetLine) {
    $search = ltrim($targetLine, '*');
    if(isset($lookup[$search])) {
        $sortedTargetLines[$idx] = '*'.$lookup[$search];
        echo '.';
    }
}

echo 'OK'.PHP_EOL;

$targetFile->putContents(implode("\r\n", $sortedTargetLines)."\r\n");

echo PHP_EOL.'ALL DONE.'.PHP_EOL;

function filterLines(FileInfo $file) : array
{
    $lines = array_map('trim', FileHelper::readLines($file));
    $lines = array_map('strtolower', $lines);

    return array_filter($lines, function ($line) {
        return !str_starts_with(trim($line), '#') && trim($line) !== '';
    });
}

