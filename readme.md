# Data grid table

## Methods

    /**
    $field : Field name
    $title: Title
    $width: Column width
    $sort: Sort this column?
    $search: Search this column?
    */
    setHeader($field, $title, $width = null, $sort = false, $search = false);



    /**
    $data: Array or Collection
    */
    setData($data);


    /**
    $field: Field name
    $callable: Callback function to custom display of the field
     */
    setColumn($field, $callable = null);


    /**
     * Output the html
     */
    output();


## Example code

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