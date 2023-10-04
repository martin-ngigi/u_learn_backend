<?php


namespace App\Admin\Controllers;

use App\Models\Course;
use App\Models\CourseType;
use App\Models\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Tree;

class CourseController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Course';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Course());

        $grid->column('id', __('Id'));
        $grid->column('user_token', __('Teacher'))->display( function ($token) {
            /// for further processing data you can create any method inside it or do operations
            return User::where('token', $token)->value('name');
        });        $grid->column('name', __('Name'));
        $grid->column('thumbnail', __('Thumbnail Photo'))->image('',60,60);
        $grid->column('description', __('Description'));
        $grid->column('type_id', __('Type id'));
        $grid->column('price', __('Price'));
        $grid->column('lesson_num', __('Lesson num'));
        $grid->column('video_length', __('Video length'));
        $grid->column('downloadable_res', __('Downloadable Resource')); 
        $grid->column('follow', __('Follow'));
        $grid->column('created_at', __('Created at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Course::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_token', __('Teacher'))->display( function ($token) {
            /// for further processing data you can create any method inside it or do operations
            return User::where('token' ,'=', $token)->value('name');
        });
        $show->field('name', __('Name'));
        $show->field('thumbnail', __('Thumbnail'));
        $show->field('video', __('Video'));
        $show->field('description', __('Description'));
        $show->field('type_id', __('Type id'));
        $show->field('price', __('Price'));
        $show->field('lesson_num', __('Lesson num'));
        $show->field('video_length', __('Video length'));
        $show->field('follow', __('Follow'));
        $show->field('score', __('Score'));
        $show->field('downloadable_res', __('Downloadable Resources'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /// create or edit a row
    protected function form()
    {
        $form = new Form(new Course());
        $form->text('name', __('Course Name'),);
        $result = CourseType::pluck('title', 'id'); // get the categories... {value, key}
        $form->select('type_id','Category')->options($result); // select one of the options that comes from result variable.
        $form->image('thumbnail', 'Thumbnail')->uniqueName(); 
        $form->file('video', 'Video')->uniqueName(); // for video and other formats like pdf
        $form->textarea('description', 'Description');
        $form->decimal('price', 'Price');  /// decimal helps in retrieving the decimal value from DB
        $form->number('lesson_num', 'Lesson Number');
        $form->number('video_length', 'Video Length');
        $form->number('downloadable_res', __('Downloadable Resources'));

        $user_result = User::pluck('name', 'token');/// who is posting
        $form->select('user_token','Teacher')->options($user_result);

        $form->display('created_at', 'Created At');
        $form->display('updated_at', 'Updated At');

        return $form;
    }
}
