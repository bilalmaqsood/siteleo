<?php

namespace App\Admin\Controllers\Language;

use App\Models\Translation;
use App;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

use App\Models\Language;

use Stichoza\GoogleTranslate\TranslateClient;

use Illuminate\Support\MessageBag;

use Transliterate;

class TranslationController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        
        
        //dump($lang->__('now_lang_name'));
        
        return Admin::content(function (Content $content) {

            $content->header('Translations');
            $content->description('');

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

            $content->header('Edit translation');
            $content->description('');

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

            $content->header('Create translation');
            $content->description('');

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
        return Admin::grid(Translation::class, function (Grid $grid) {
            $grid->disableExport();
            $grid->id('ID')->sortable();          
            
            //dd($grid->model()->value);
            
            $grid->column('name');
            //$grid->column('value');
            $grid->column('Value')->display(function($id) use ($grid){
                $locale = App::getLocale();          
                $langm = Translation::find($this->id);
                return $langm->value[$locale];
            });
            
            
            $grid->updated_at();

            $grid->filter(function($filter){

                $filter->disableIdFilter();

                $filter->like('name');

                $filter->like('value');
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Translation::class, function (Form $form) {
            $form->display('id', 'ID');
            
            $form->text('name', 'Translate Name')->rules('required|min:2');
            
            foreach (Language::where('activ', 1)->get() as $lang){
                $form->textarea('value_'.$lang->code, 'Translate ('.$lang->name.')')->default(function ($form) use ($lang) {
                    if(isset($form->model()->value[$lang['code']]))
                        return $form->model()->value[$lang['code']];
                    else
                        return '';
                });
                $states = [
                    'on'  => ['value' => 1, 'text' => 'Yes', 'color' => 'success'],
                    'off' => ['value' => 0, 'text' => 'no', 'color' => 'danger'],
                ];

                $form->switch('translate.'.$lang->code, 'Translate')->states($states)->default(0);
            }
            $form->ignore(['translate']);
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
            
            $form->saving(function (Form $form) {
                $langs = [];
                $iteration = 0;
                $tr = new TranslateClient();
                foreach (Language::where('activ', 1)->get() as $lang){
                    
                    $val = !is_null($form->{'value_'.$lang->code}) ? $form->{'value_'.$lang->code} : $form->name;

                    if(isset(request()->translate) && request()->translate[$lang->code]=='on') $langs[$lang['code']] = $tr->setSource(null)->setTarget($lang->code)->translate($val);
                    else $langs[$lang['code']] = $val;
                    //if($form->model()->id) $langs[$lang['code']] = $val;
                    //else $langs[$lang['code']] = $tr->setSource(null)->setTarget($lang->code)->translate($val);
                    //else $langs[$lang['code']] = $val;
                    $iteration++;
                }
                $form->name = preg_replace('/[^A-Za-z0-9_]/', ' ', $form->name);
                $form->name = str_replace('__', '_', $form->name);
                $name = $this->strlen_simb(substr(Transliterate::make($form->name, ['type' => 'filename', 'lowercase' => true]), 0, 30),'_');
                                
                $test = Translation::where('name','=',$name)->first();                                
                if($form->model()->id) $langm = Translation::find($form->model()->id);
                elseif(!is_null($test)) $langm = Translation::find($test->id);
                else $langm = new Translation;
                
                $langm->name = $name;
                $langm->value = $langs;
                $langm->save();
                
                $success = new MessageBag([
                    'title'   => 'Success',
                    'message' => "Variable (@_{$name}) - ({$langs[app()->getLocale()]}) done!",
                ]);

                return redirect('/admin/lang/translations')->with(compact('success'));
                
                dd($form);
            });
        });
    }
        
    public function strlen_simb($str, $simb){
        if ($str{strlen($str)-1} == $simb) {
            return substr($str,0,-1);
        }
        return $str;
    }
}
