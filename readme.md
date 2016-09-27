# Data grid table

## Methods

**Create an object**

    $grid = new Justin\DataGrid\DataGrid();

**Set header**

    setHeader($field, $title, $width = null, $sort = false, $search = false);

    $grid->setHeader('id', 'ID', 50, true, true);


`$field` : Field name

`$title`: Title

`$width`: Column width

`$sort`: Sort this column?

`$search`: Search this column?


**Set data**

    setData($data);

    $grid->setData($data);

**Custom column**

    setColumn($field, $callable = null);

    $grid->setColumn('name', function($item) {
        return '<b>'. $item->name .'</b>';
    });

**Custom search column**

    $grid->setSearchColumn('quantity', function() {
        return '
            <div><input type="text" class="form-control input-sm" name="q_min" placeholder="Min:" /></div>
            <div><input type="text" class="form-control input-sm" name="q_max" placeholder="Max:" /></div>
        ';
    });

**Bulk action**

    $grid->showBulkAction();

**Output**

    output();


## Example code

        $productGrid = new Justin\DataGrid\DataGrid();

        $productGrid->showBulkAction();
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

        $productGrid->setSearchColumn('quantity', function() {
            return '
                <div><input type="text" class="form-control input-sm" name="q_min" placeholder="Min:" /></div>
                <div><input type="text" class="form-control input-sm" name="q_max" placeholder="Max:" /></div>
            ';
        });

        echo $productGrid->output();