<?php

namespace App\Admin\Controllers;

use App\Models\ListCost;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ListCostController extends Controller
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

            $content->header('CostList');
            $content->description('List of packages to replenish balance');

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

            $content->header('CostList edit');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('CostList create');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(ListCost::class, function (Grid $grid) {
            $grid->disableFilter();
            $grid->disableExport();
            $grid->id('ID')->sortable();
            
            $grid->column('cost')->editable();
            $grid->column('cost_bonus')->editable();

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
        return Admin::form(ListCost::class, function (Form $form) {

            $form->display('id', 'ID');
            
            $form->slider('cost', 'Cost')->options(['max' => 500, 'min' => 1, 'step' => 1, 'postfix' => '€']);
            $form->slider('cost_bonus', 'Cost bonus')->options(['max' => 500, 'min' => 0, 'step' => 1, 'postfix' => '€']);
            
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
