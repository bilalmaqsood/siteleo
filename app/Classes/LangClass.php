<?php

namespace App\Classes;

use App;
use App\Models\Translation;

class LangClass
{
    public $vars = [];
    
    public function __construct() {
        $vars = Translation::all();
        $locale = App::getLocale();

        foreach ($vars as $var){
            $this->vars['main.'.$var->name] = isset($var->value[$locale]) && !empty($var->value[$locale])? $var->value[$locale] : $var->name;
        }

        //dd($locale, $this->vars);

    }
    
    public function __($name){
        return isset($this->vars[$name]) ? $this->vars[$name] : $name;
    }
}