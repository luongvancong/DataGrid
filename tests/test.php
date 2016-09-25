<?php error_reporting( E_ALL );ini_set('display_errors', 1);
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
            'quantity' => $faker->randomDigit
        ];
    }

    $productGrid = new Justin\DataGrid\DataGrid();
    $productGrid->setHeaders([
        'id'       => array('ID', 30),
        'name'     => array('Name'),
        'quantity' => 'Quantity'
    ]);

    $productGrid->setSortAble([
        'id' => array('id', true),
        'name' => array('name', true)
    ]);

    $productGrid->setData($data);

    $productGrid->setColumn('name', function($item) {
        return $item['name'] . ' SHIT';
    });

    $productGrid->setColumn('quantity', 30);

    echo $productGrid->output();

?>
</body>
</html>