<?php

namespace App\Admin\Controllers;

use App\Models\Ads;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use App\Models\Category;

    class AdsController extends Controller
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

            $content->header('Ads');
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Ad edit');

            $content->body($this->form()->edit($id));
        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Ads::class, function (Grid $grid) {
            $grid->disableFilter();
            $grid->disableExport();
            $grid->disableCreation();
            $grid->model()->orderBy('created_at', 'desc');
            $grid->id('ID')->sortable();

            //$grid->column('name');


            $grid->name()->display(function ($name) use ($grid) {
                return "<a href='/advertise/{$this->uri}' target='_blank'>$name <i class=\"fa fa-external-link\"></i></a>";
            });

            $grid->column('user.name');
            $grid->column('user.surname');

            $grid->disableExport();

            $states = [
                'on'  => ['value' => 1, 'text' => 'YES', 'color' => 'primary'],
                'off' => ['value' => 0, 'text' => 'NO', 'color' => 'default'],
            ];
            $grid->activ('Active?')->switch($states);

            $grid->created_at();
            $grid->updated_at();

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Ads::class, function (Form $form) {

            $form->tab('Settings', function($form){
                $form->display('id', 'ID');

                $form->display('payable', 'Payable');
                $form->display('like', 'Likes');
                $form->display('views', 'Views');

                $form->text('uri', 'Uri');
                $form->text('name', 'Name');

                $form->multipleSelect('category', 'Categories')->options(\App\Models\Category::all()->pluck('title', 'id'));

                $form->ckeditor('terms_service', 'Terms Service');
                $form->ckeditor('description', 'Description');

                $form->switch('activ', 'Active?');
                $form->display('created_at', 'Created At');
                $form->display('updated_at', 'Updated At');
            })->tab('SEO', function($form){
                $form->textarea('seo_title', 'SEO Title');
                $form->textarea('seo_keywords', 'SEO Keywords');
                $form->textarea('seo_description', 'SEO Description');
            });
        });
    }
}
