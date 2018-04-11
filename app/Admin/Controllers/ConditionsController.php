<?php

namespace App\Admin\Controllers;

use App\Models\Conditions;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Box;
use Illuminate\Support\MessageBag;


class ConditionsController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Conditions');


            $form = new \Encore\Admin\Widgets\Form(Conditions::find(1));

            $form->ckeditor('for_professionals');
            $form->ckeditor('for_clients');
            $form->ckeditor('consultas_frecuentes', 'Most frequent consultations');


            $body = (new Box('Conditions update', $form))->style('success');

            $content->body($body);
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Conditions::class, function (Form $form) {

            $form->saving(function (Form $form) {

                $find = Conditions::find(1);

                $find->for_professionals = $form->for_professionals;
                $find->for_clients = $form->for_clients;
                $find->consultas_frecuentes = $form->consultas_frecuentes;

                $find->save();

                $success = new MessageBag([
                    'title'   => 'Success',
                    'message' => "Saved!",
                ]);

                return redirect('/admin/conditions')->with(compact('success'));
            });

        });
    }
}
