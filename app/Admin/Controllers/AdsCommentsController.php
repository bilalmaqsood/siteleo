<?php

namespace App\Admin\Controllers;

use App\Models\AdsComments;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class AdsCommentsController extends Controller
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

            $content->header('Ads Comments');
            //$content->description('description');

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

            $content->header('Show comment');

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
        return Admin::grid(AdsComments::class, function (Grid $grid) {

            $grid->disableCreation();
            $grid->disableExport();
            $grid->disableFilter();

            $grid->id('ID')->sortable();

            $grid->ads_id('Ad')->display(function ($adId){
                $ad = \App\Models\Ads::find($adId);
                if($ad)
                    return "<a href='/advertise/{$ad->uri}' target='_blank'>{$ad->name} <i class=\"fa fa-external-link\"></i></a>";
                else
                    return "Deleted Ad";
            });

            $grid->user_id('User')->display(function ($Id){
                $user = \App\User::find($Id);
                return $user->name.' '.$user->surname;
            });

            //$grid->message();

            $states = [
                'on'  => ['value' => 1, 'text' => 'YES', 'color' => 'primary'],
                'off' => ['value' => 0, 'text' => 'NO', 'color' => 'default'],
            ];

            $grid->active('Active?')->switch([
                'on'  => ['value' => '1', 'text' => 'YES', 'color' => 'primary'],
                'off' => ['value' => '0', 'text' => 'NO', 'color' => 'default'],
            ]);

            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form($id=false)
    {
        return Admin::form(AdsComments::class, function (Form $form) use ($id) {
            $message = \App\Models\AdsComments::find($id);

            $form->display('id', 'ID');
            if($id) $form->display('', 'Message')->default($message->message);

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');

            $form->switch('active')->states([
                'on'  => ['value' => '1', 'text' => 'YES', 'color' => 'primary'],
                'off' => ['value' => '0', 'text' => 'NO', 'color' => 'default'],
            ]);

            $form->saving(function (Form $form) {

            });
        });
    }
}
