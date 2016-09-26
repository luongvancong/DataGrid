<?php

namespace Justin\DataGrid;

class DataGrid {

    protected $headers = array();

    protected $sortable = array();

    protected $data = array();

    protected $callable = array();

    protected $searchCallable = array();

    protected $attributes = array();

    protected $args = array();


    /**
     * Set header each column
     * @param string  $field
     * @param string  $title
     * @param int|null  $width
     * @param boolean $sort
     * @param boolean $search
     */
    public function setHeader($field, $title, $width = null, $sort = false, $search = false)
    {
        $this->headers[$field] = $title;
        $this->attributes[$field]['width'] = $width;
        $this->sortable[$field] = $sort;
        $this->search[$field] = $search;

    }


    /**
     * Set data
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }


    /**
     * Get data
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }


    /**
     * Show header
     * @return html
     */
    public function showHeader()
    {
        $headers = $this->getHeaders();
        $html = '';
        foreach($headers as $field => $title) {
            if( $this->haveSortAble($field) ) {
                $html .= '<th data-field="'. $field .'"><div><a href="'. $this->getUrlWithSortQueryString($field) .'">'. $this->getHeaderTitle($field) .'</a></div></th>';
            } else {
                $html .= '<th data-field="'. $field .'"><div>'. $title .'</div></th>';
            }
        }

        return $html;
    }


    /**
     * Show body
     * @return html
     */
    public function showBody()
    {
        $data = $this->getData();
        $headers = $this->getHeaders();

        $html = '';

        // Show search control
        foreach($data as $key => $info) {
            $html .= '<tr>';
            foreach($headers as $field => $label) {
                if($this->haveSearchCallable($field)) {
                    $html .= '<td>' . call_user_func_array($this->searchCallable[$field], array()) . '</td>';
                } else {
                    $html .= '<td>'. $this->getSearchControl($field) .'</td>';
                }

            }
            $html .= '</tr>';
            break;
        }

        // Show data
        foreach($data as $key => $info) {
            $html .= '<tr>';
            foreach($headers as $field => $label) {
                $style = '';
                if( $this->haveAttribute($field) ) {
                    $style = makeAttributes($this->attributes[$field]);
                }

                if( $this->isCallable($field)  ) {
                    $html .= '<td '. $style .'>'. call_user_func_array($this->callable[$field], array($this->data[$key])) .'</td>';
                } else {
                    $html .= '<td '. $style .'>'. $this->getValue($info, $field) .'</td>';
                }
            }
            $html .= '</tr>';
        }

        return $html;
    }


    /**
     * Custom display column what you want
     * @param string $key
     * @param callable $callable
     */
    public function setColumn($key, $callable = null)
    {
        $this->callable[$key] = $callable;
    }


    /**
     * Set custom search column
     * @param string $key
     * @param callable $callable
     */
    public function setSearchColumn($key, $callable = null)
    {
        $this->searchCallable[$key] = $callable;
    }


    public function haveSearchCallable($key)
    {
        return array_key_exists($key, $this->searchCallable) && is_callable($this->searchCallable[$key]);
    }


    /**
     * Output html
     * @return html
     */
    public function output() {
        return '
            <form method="GET" action="">
                <button type="submit" style="display:none">Search</button>
                <table class="table">
                    <thead>'. $this->showHeader() .'</thead>
                    <tbody>'. $this->showBody() .'</tbody>
                </table>
            </form>';
    }


     /**
     * Get sortable array
     * @return array
     */
    public function getSortAble() {
        return $this->sortable;
    }


    /**
     * Get headers array
     * @return array
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * Have column callable
     * @param  string  $key
     * @return boolean
     */
    public function isCallable($key)
    {
        return isset($this->callable[$key]) && is_callable($this->callable[$key]);
    }


    /**
     * Have column attribute?
     * @param  string $key
     * @return boolean
     */
    public function haveAttribute($key)
    {
        return isset($this->attributes[$key]) && !is_null($this->attributes[$key]);
    }

    /**
     * Get value of field
     * @param  mixed $data
     * @param  string $key
     * @return mixed
     */
    public function getValue($data, $key)
    {
        $value = null;
        if(is_object($data) && property_exists($data, $key)) {
            $value = $data->$key;
        }

        if(is_object($data) && method_exists($data, '__get')) {
            $value = $data->__get($key);
        }

        if(is_array($data) && array_key_exists($key, $data)) {
            $value = $data[$key];
        }

        return $value;
    }


    /**
     * Get header title
     * @param  string $key
     * @return mixed
     */
    public function getHeaderTitle($key)
    {
        if( array_key_exists($key, $this->getHeaders()) ) {
            return $this->headers[$key];
        }
    }


    /**
     * Have sortable
     * @param  string $field
     * @return bool
     */
    private function haveSortAble($field) {
        return array_key_exists($field, $this->getSortAble()) && $this->sortable[$field] === true ? true : false;
    }


    /**
     * Have search
     * @param  string $field
     * @return bool
     */
    private function haveSearch($field) {
        return array_key_exists($field, $this->search) && $this->search[$field] === true ? true : false;
    }


    /**
     * Get search control
     * @param  string $field
     * @return html
     */
    private function getSearchControl($field) {
        if($this->haveSearch($field)) {
            $value = isset($_GET[$field]) ? $_GET[$field] : '';
            return '<div><input type="text" name="'. $field .'" value="'. $value .'" class="form-control" placeholder="'. $this->getHeaderTitle($field) .'" /></div>';
        }
    }


    /**
     * Get full url with sort params
     * @param  string $field
     * @return url
     */
    public function getUrlWithSortQueryString($field)
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $sortKey = isset($_GET['sort_key']) ? $_GET['sort_key'] : '';
        $sortValue = isset($_GET['sort_value']) ? $_GET['sort_value'] : 'desc';

        $query = ['sort_key' => $field, 'sort_value' => $sortValue];
        $query = array_merge($_GET, $query);

        if($sortKey == $field) {
            if($sortValue == 'desc') {
                $sortValue = 'asc';
            }
            else {
                $sortValue = 'desc';
            }

            $query = ['sort_key' => $sortKey, 'sort_value' => $sortValue];
            $query = array_merge($_GET, $query);
        }

        $parseUrl = parse_url($url);

        return $parseUrl['path'] . '?' . http_build_query($query);

    }


    /**
     * Set custom argument
     * @param mixed $key
     * @param mixed $value
     */
    public function setArgs($key, $value)
    {
        $this->args[$key] = $value;
    }


    /**
     * Get custom argument
     * @param  mixed $key
     * @return mixed
     */
    public function getArg($key)
    {
        return array_key_exists($key, $this->args) ? $this->args[$key] : null;
    }


    /**
     * Column serial number
     * @param string $field
     * @param int $perPage
     */
    public function setSerialNumber($field, $perPage)
    {
        $self = $this;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $serial = ($page - 1) * $perPage;
        $this->setArgs($field, $serial);
        $this->setColumn($field, function($item) use($self) {
            $no = $self->getArg('no');
            $no ++;
            $self->setArgs('no', $no);
            return $no;
        });
    }


}
