<?php

namespace App\Admin\Controllers;

use App\Models\Category;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Tab;
use App;
use Transliterate;

class CategoryController extends Controller
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

            $content->header('Categories');
            $content->body(Category::tree(function ($tree) {
                $tree->branch(function ($branch) {
                    $src = env('APP_URL') . '/' . $branch['icon'] ;
                    $logo = "<img src='$src' style='max-width:30px;max-height:30px' class='img'/>";
                    $see = !empty($branch['icon']) ? $logo : $branch['id'];
                    return "{$see} - {$branch['title']}";
                });
            }));
            
            //$content->body($this->grid());
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

            $content->header('Categories edit');

            $content->body($this->form($id)->edit($id));
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

            $content->header('Categories create');

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
        return Admin::grid(Category::class, function (Grid $grid) {
            $grid->disableFilter();
            $grid->disableExport();
            $grid->id('ID')->sortable();

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
        return Admin::form(Category::class, function (Form $form) use ($id) {

            $form->tab('Settings', function($form) use ($id){
                $form->display('id', 'ID');

                    $form->switch('home', 'Show in home page?')->states([
                        'on'  => ['value' => 1, 'text' => 'YES', 'color' => 'success'],
                        'off' => ['value' => 0, 'text' => 'NO', 'color' => 'danger'],
                    ])->default(0);

                $form->select('parent_id', trans('admin.parent_id'))->options(Category::selectOptions());
                $form->text('title', 'Title')->rules('required|min:2');
                $form->text('uri', 'URI');
                $form->image('icon');
            })->tab('SEO', function($form){
                $form->textarea('seo_title', 'Title');
                $form->textarea('seo_keywords', 'Keywords');
                $form->textarea('seo_description', 'Description');
            });
            
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
                        
            $form->saving(function (Form $form) {
                //$form->home = (request()->home=='on' ? 1 : 0);
                //dd(request()->home, $form->home);
                if(empty($form->uri)) $form->uri = $form->title;
                $form->uri = str_slug($form->uri, '-'); // Transliterate::make($form->uri, ['type' => 'url', 'lowercase' => true]);
            });
        });
    }
}
