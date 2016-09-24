<?php error_reporting( E_ALL );ini_set('display_errors', 1);
?><!DOCTYPE html>
<html>
<head>
    <title>Data table</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>
<?php

    require '../src/DataGrid.php';
    require_once '../vendor/fzaninotto/faker/src/autoload.php';

    class ProductGrid extends Justin\DataGrid\AbstractDataGrid {

        public function getHeaders()
        {
            return [
                'id'       => 'ID',
                'name'     => 'Name',
                'quantity' => 'Quantity'
            ];
        }

        public function getSortAbleColumns()
        {
            return [
                'id' => array('id', true),
                'name' => array('name', true)
            ];
        }

        public function getData()
        {
            $faker = Faker\Factory::create();
            $faker->addProvider(new Faker\Provider\Barcode($faker));
            $data = [];
            for($i = 0; $i < 100; $i ++) {
                $data[] = (object) [
                    'id' => $faker->ean13,
                    'name' => $faker->name,
                    'quantity' => $faker->randomDigit
                ];
            }

            return $data;
        }
    }

    $productGrid = new ProductGrid();
    echo $productGrid->output();

?>
</body>
</html>