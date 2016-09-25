<?php

if (! function_exists('mergeAttributes') ) {
    /**
    * Merge Attributes
    *
    * @return array attributes.
    */
    function mergeAttributes() {

        $args = func_get_args();

        $temp_attributes = array();

        $attributes = array();

        foreach($args as $key => $array_attr) {
            foreach($array_attr as $name_attr => $value_attr) {
                if($name_attr == 'class') {
                    $temp_attributes[$name_attr][] = $value_attr;
                }else{
                    $temp_attributes[$name_attr] = $value_attr;
                }
            }
        }

        foreach($temp_attributes as $name_attr => $value_attr) {
            if($name_attr == 'class') {
                $attributes[$name_attr] = implode(' ', $value_attr);
            }else{
                $attributes[$name_attr] = $value_attr;
            }
        }

        return $attributes;
    }
}

if (! function_exists('makeAttributes') ) {
    /**
     * Generate string attributes
     * @param  array $attributes
     * @return string
     */
    function makeAttributes($attributes) {

        $stringAttribute = '';

        if(is_array($attributes)) {
            foreach($attributes as $key => $value) {
                $stringAttribute .= "$key=\"$value\" ";
            }
        }else{
            $stringAttribute = @strval($attributes);
        }

        return trim($stringAttribute, ' ');
    }
}