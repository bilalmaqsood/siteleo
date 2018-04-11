<?php

namespace App\Admin\Controllers;

use App\User;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class UserController extends Controller
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

            $content->header('Users');

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

            $content->header('User edit');

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

            $content->header('User create');

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
        return Admin::grid(User::class, function (Grid $grid) {
            $grid->disableFilter();
            $grid->disableExport();
            $grid->id('ID')->sortable();
            
            $grid->column('name');
            $grid->column('email');
            
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
        return Admin::form(User::class, function (Form $form) {

            $form->tab('Settings', function($form){

                $form->display('id', 'ID');

                $form->select('role', 'Role')->options(['visitor' => 'Visitor', 'worker' => 'worker']);

                $form->text('name', 'User Name');

                $form->text('surname', 'User Surnsme')->rules('required|min:2');

                $form->password('password', trans('admin.password'))->rules('|confirmed')
                    ->default(function ($form) {
                        return $form->model()->password;
                    });


                $form->password('password_confirmation', trans('admin.password_confirmation'))->rules('required')
                    ->default(function ($form) {
                        return $form->model()->password;
                    });

                $form->ignore(['password_confirmation']);

                $form->image('photo');

                $form->text('phone', 'Phone')->rules('required|min:10');

                $form->text('email', 'E-Mail')->rules('required|min:10');

                $form->select('sex', 'Sex')->options(['0' => 'Unknow', '1' => 'Man', '2' => 'Female']);

                $form->date('birthday', 'Birthday')->format('DD.MM.YYYY');

                //$form->timeRange('worck_time_from', 'worck_time_to', 'Time Range');


                $states = [
                    'on'  => ['value' => 1, 'text' => 'enable', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => 'disable', 'color' => 'danger'],
                ];

                $form->switch('ban', 'Ban')->states($states);
                $form->switch('activ', 'Activ')->states($states);

                $form->display('created_at', 'Created At');
                $form->display('updated_at', 'Updated At');

            })->tab('Location', function($form){
                $form->text('location.city', 'City')->rules('required');
                $form->text('location.provincia', 'Provincia')->rules('required');
                $form->text('location.postcode', 'Post Code')->rules('required');
            })->tab('Galery', function($form){

                $form->hasMany('gallery', function (Form\NestedForm $form) {
                    $form->image('photo');
                });

            })->tab('Graphic', function($form){
                $form->text('graphics.begining_work_day', 'Begining work day');
                $form->text('graphics.end_working_day', 'End working day');

                $form->multipleSelect('graphics.working_days', 'Working days')->options([
                    1 => 'Monday',
                    2 => 'Tuesday',
                    3 => 'Wednesday',
                    4 => 'Thursday',
                    5 => 'Friday',
                    6 => 'Saturday',
                    7 => 'Sunday',
                ]);

            })->tab('Services', function($form){
                $form->hasMany('services', function (Form\NestedForm $form) {
                    $form->text('title');
                });
            })->tab('Certificates', function($form){
                $form->hasMany('certificates', function (Form\NestedForm $form) {
                    $form->text('title');
                });
            });

                        

            
            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });
        });
    }
}
