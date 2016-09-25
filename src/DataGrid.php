<?php

namespace Justin\DataGrid;

class DataGrid {

    protected $headers = array();

    protected $sortable = array();

    protected $data = array();

    protected $callable = array();

    protected $attributes = array();

    public function getSortAble() {
        return $this->sortable;
    }

    public function setSortAble(array $sortable)
    {
        $this->sortable = $sortable;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function setHeaders(array $headers)
    {
        foreach($headers as $key => $value) {
            if(is_array($value) && array_key_exists(1, $value)) {
                $this->attributes[$key]['width'] = $value[1];
            }
        }

        $this->headers = $headers;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getData() {
        return $this->data;
    }

    public function showHeader()
    {
        $headers = $this->getHeaders();
        $html = '';
        foreach($headers as $field => $title) {
            if( $this->hasSortAble($field) ) {
                $html .= '<th data-field="'. $field .'"><a href="">'. $this->getHeaderTitle($headers, $field) .'</a></th>';
            } else {
                $html .= '<th data-field="'. $field .'">'. $title .'</th>';
            }
        }

        return $html;
    }


    public function showBody()
    {
        $data = $this->getData();
        $headers = $this->getHeaders();

        $html = '';
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


    public function setColumn($key, $callable = null)
    {
        $this->callable[$key] = $callable;
    }


    public function isCallable($key)
    {
        return isset($this->callable[$key]) && is_callable($this->callable[$key]);
    }

    public function haveAttribute($key)
    {
        return isset($this->attributes[$key]);
    }


    public function getValue($data, $key)
    {
        if(is_object($data) && property_exists($data, $key)) {
            return $data->$key;
        }

        if(is_array($data) && array_key_exists($key, $data)) {
            return $data[$key];
        }
    }


    public function getHeaderTitle($headers, $key)
    {
        if( array_key_exists($key, $headers) ) {
            if( is_array($headers[$key]) ) {
                return array_shift($headers[$key]);
            } else{
                return $headers[$key];
            }
        }
    }

    public function output() {
        return '<table class="table">
            <thead>'. $this->showHeader() .'</thead>
            <tbody>'. $this->showBody() .'</tbody>
        </table>';
    }


    private function hasSortAble($field) {
        if(array_key_exists($field, $this->getSortAble())) {
            return true;
        }

        return false;
    }

}