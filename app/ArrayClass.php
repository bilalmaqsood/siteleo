<?php
namespace App;

class ArrayClass
{
    public static function convert($model, $field='id', $fieldName=false, $emptyClear=false){
        $return = [];
        if(is_object($model)) {
            foreach ($model as $collect) $return[$collect->{$field}] = ($fieldName ? (isset($collect->{$fieldName}) ? $collect->{$fieldName} : '') : $collect);
        }else{
            foreach ($model as $collect) $return[$collect[$field]] = ($fieldName ? (isset($collect[$fieldName]) ? $collect[$fieldName] : '') : $collect);
        }
        if($emptyClear){
            foreach ($return as $key => $val) {
                if(empty($val)) unset($return[$key]);
            }
        }
        return $return;
    }

    public static function group($model, $field='id', $fieldName=false, $emptyClear=false){
        $return = [];
        if(is_object($model)) {
            foreach ($model as $collect) $return[$collect->{$field}][] = ($fieldName ? (isset($collect->{$fieldName}) ? $collect->{$fieldName} : '') : $collect);
        }else{
            foreach ($model as $collect) $return[$collect[$field]][] = ($fieldName ? (isset($collect[$fieldName]) ? $collect[$fieldName] : '') : $collect);
        }
        if($emptyClear){
            foreach ($return as $key => $val) {
                if(empty($val)) unset($return[$key]);
            }
        }
        return $return;
    }
}