<?php

namespace Justin\DataGrid;

abstract class AbstractDataGrid {
    protected $headers = array();

    protected $sorts = array();

    protected $data = array();

    abstract function getSortAbleColumns();

    abstract function getData();

    abstract function getHeaders();


    public function showHeader()
    {
        $headers = $this->getHeaders();
        $html = '';
        foreach($headers as $field => $title) {
            if( $this->hasSortAble($field) ) {
                $html .= '<th data-field="'. $field .'"><a href="">'. $title .'</a></th>';
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
        // print_r($data);die;
        $html = '';
        foreach($data as $info) {
            $html .= '<tr>';
            foreach($headers as $field => $label) {
                if(property_exists($info, $field)) {
                    $html .= '<td>'. $info->$field .'</td>';
                } else {
                    $html .= '<td></td>';
                }
            }
            $html .= '</tr>';
        }

        return $html;
    }

    public function output() {
        return '<table class="table">
            <thead>'. $this->showHeader() .'</thead>
            <tbody>'. $this->showBody() .'</tbody>
        </table>';
    }


    private function hasSortAble($field) {
        if(array_key_exists($field, $this->getSortAbleColumns())) {
            return true;
        }

        return false;
    }
}