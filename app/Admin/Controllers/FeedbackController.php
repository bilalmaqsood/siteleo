<?php

namespace App\Admin\Controllers;

use \App\Models\Feedback;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class FeedbackController extends Controller
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

            $content->header('Feedback');
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

            $content->header('Feedback edit');

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
        return Admin::grid(Feedback::class, function (Grid $grid) {
            $grid->disableFilter();
            $grid->disableExport();
            $grid->disableCreation();
            $grid->model()->orderBy('created_at', 'desc');

            $grid->id('ID');

            $states = [
                'on'  => ['value' => 1, 'text' => 'NEW', 'color' => 'primary'],
                'off' => ['value' => 0, 'text' => 'OLD', 'color' => 'default'],
            ];
            $grid->new('Status')->switch($states);

            $grid->name();
            $grid->email()->display(function ($email) {
                return "<a href='mailto:$email'>{$email}</a>";
            });
            $grid->phone()->display(function ($phone) {
                return "<a href='tel:$phone'>{$phone}</a>";
            });
            $grid->comment();

            $grid->created_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Feedback::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('name');
            $form->display('email');
            $form->display('phone');
            $form->display('comment');

            $states = [
                'New'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
                'Old' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
            ];

            $form->switch('new')->states($states);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
