<?php
require 'vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;

$templates = [
    'individu' => 'storage/app/public/templates/1784279655_template_individu.docx',
    'kelompok' => 'storage/app/public/templates/1784279702_template_kelompok.docx'
];

foreach ($templates as $name => $path) {
    echo "--- Variables in {$name} ({$path}) ---" . PHP_EOL;
    if (file_exists($path)) {
        $processor = new TemplateProcessor($path);
        print_r($processor->getVariables());
    } else {
        echo "File does not exist!" . PHP_EOL;
    }
}
