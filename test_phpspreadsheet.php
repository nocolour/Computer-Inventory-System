<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Create a new spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello, World!');

// Save the spreadsheet as an Excel file
$writer = new Xlsx($spreadsheet);
$writer->save('test.xlsx');

echo "Spreadsheet created successfully! Check 'test.xlsx' in your project folder.";
?>
