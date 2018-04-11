<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
use Encore\Admin\Facades\Admin;
use App\Admin\Extensions\Form\CKEditor;

Encore\Admin\Form::extend('ckeditor', CKEditor::class);

Encore\Admin\Form::forget(['map', 'editor']);

Admin::navbar(function (\Encore\Admin\Widgets\Navbar $navbar) {
    
    $locale = App::getLocale();
    
    $admin_langs = ['EN','ES','HE','PL','RU'];
    
    foreach ($admin_langs as $lang){
        $select = strtolower($lang)==$locale ? ' class="active"' : '';
//        $navbar->right('<li'.$select.'>
//                   <a href="'.route('lang', ['locale' => strtolower($lang)]).'" data-toggle="control-sidebar">'.$lang.'</a>
//                 </li>');
    }
    $navbar->right(new \App\Admin\Extensions\Nav\Links());
    //$navbar->right('');
});
if(isset(Admin::user()->avatar)) Admin::user()->avatar = 'uploads/images/'.last(explode('/', Admin::user()->avatar));