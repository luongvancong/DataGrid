<?php session_start();
error_reporting( E_ALL );ini_set('display_errors', 1);
?><!DOCTYPE html>
<html>
<head>
    <title>Data table</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<?php

    require '../src/helper.php';
    require '../src/DataGrid.php';
    require_once '../vendor/fzaninotto/faker/src/autoload.php';

    $faker = Faker\Factory::create();
    $faker->addProvider(new Faker\Provider\Barcode($faker));
    $data = [];
    for($i = 0; $i < 100; $i ++) {
        $data[] = [
            'id' => $faker->ean13,
            'name' => $faker->name,
            'address' => $faker->address,
            'company' => $faker->company,
            'quantity' => $faker->randomDigit,
        ];
    }

    $productGrid = new Justin\DataGrid\DataGrid();

    $productGrid->setHeader('stt', "No");
    $productGrid->setHeader('id', 'ID', null, true, true);
    $productGrid->setHeader('name', 'Name', null, true, true);
    $productGrid->setHeader('address', 'Addr', null, true, true);
    $productGrid->setHeader('company', 'Company', null, true, true);
    $productGrid->setHeader('quantity', 'Quantity');
    $productGrid->setData($data);

    $productGrid->setSerialNumber('stt', 10);

    $productGrid->setColumn('name', function($item) {
        return $item['name'] . ' SHIT';
    });

    echo $productGrid->output();

?>
</body>
</html>