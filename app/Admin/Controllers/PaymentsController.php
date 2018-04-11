<?php

namespace App\Admin\Controllers;

use App\Models\Payments;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class PaymentsController extends Controller
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

            $content->header('Payments');

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

            $content->header('Payment view\edit');

            $content->body($this->form($id)->edit($id));
        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Payments::class, function (Grid $grid) {
            $grid->disableFilter();
            $grid->disableExport();
            $grid->disableCreation();
            $grid->id('ID')->sortable();

            $grid->column('order');
            $grid->column('user.name');
            $grid->column('user.surname');

            $grid->column('cost.cost')->display(function ($cost) {
                return "$cost EUR";
            });

            $grid->column('cost.cost_bonus')->display(function ($cost) {
                return "$cost EUR";
            });

            $grid->column('status')->display(function ($status) {
                if(!$status) return "<span class=\"label bg-blue\">Created</span>";
                elseif($status==1) return "<span class=\"label bg-orange\">Wait</span>";
                elseif($status==2) return "<span class=\"label bg-green\">Complete</span>";
                elseif($status==3) return "<span class=\"label bg-red\">Error {$this->error_message}</span>";
            });

            $grid->created_at();

            $grid->actions(function ($actions) {
                $actions->disableDelete();
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id)
    {
        return Admin::form(Payments::class, function (Form $form) use($id) {

            $form->display('id', 'ID');
            $form->display('order', 'Order ID');
            $form->display('user.name');
            $form->display('user.surname');
            $form->select('status')->options([0 => 'Created', 1 => 'Wait', 2 => 'Complete', 3 => 'Error']);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $data = $form->model()->find($id);
            $payment_system_response_data = [];
            if(count($data->payment_system_response_data)) foreach ($data->payment_system_response_data as $key=>$val){ $payment_system_response_data[$key] = urldecode($data->payment_system_response_data[$key]); }

            $form->html("<h2>Response data</h2><br><pre>".print_r($data->payment_system_response, 1)."</pre>");
            $form->html("<pre>".print_r($payment_system_response_data, 1)."</pre>");
            $form->display('error_message');
        });
    }
}
