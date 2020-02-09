<?php

include 'db/db.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

if (isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {

    $arr_file = explode('.', $_FILES['file']['name']);
    $extension = end($arr_file);

    if ('csv' == $extension) {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    } else {
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    }

    $spreadsheet = $reader->load($_FILES['file']['tmp_name']);

    $sheetData = $spreadsheet->getActiveSheet()->toArray();

    foreach ($sheetData as $key => $data) {
        if ($key == 0) {
            continue;
        }
//        print "<pre>";
//        print_r($data);
        
        $company = $data[0];
        $street = $data[1];
        $city = $data[2];
        $lat = $data[3];
        $lng = $data[4];
        $state = $data[5];
        $postalcode = $data[6];
        $country = $data[7];
        $phone = $data[8];
        $website = $data[9];
        

        $sql = "INSERT INTO retailers (company, street, city, lat, lng, state, postalcode, country, phone, website)
                VALUES ('$company', '$street', '$city',  '$lat', '$lng','$state', '$postalcode', '$country', '$phone', '$website')";

        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
