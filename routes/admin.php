<?php
use App\Http\Controllers\Admin\CodeEditorController;
use App\Http\Controllers\Admin\PersonalInfoController;
//site configuration
Route::get('site_config/edit', ['as' => 'site_config.edit', 'uses' => 'SiteConfigController@edit']);
Route::post('site_config', ['as' => 'site_config.update', 'uses' => 'SiteConfigController@update']);
Route::post('send_test_mail', ['as' => 'site_config.sendTestMail', 'uses' => 'SiteConfigController@sendTestMail']);
Route::post('send_test_sms', ['as' => 'site_config.sendTestSMS', 'uses' => 'SiteConfigController@sendTestSMS']);

Route::resource('blogs', 'BlogsController');
Route::post('blogs/bulk-action', ['as' => 'blogs.bulk-action', 'uses' => 'BlogsController@bulkAction']);
Route::post('blogs/{id}', ['as' => 'blogs.restore', 'uses' => 'BlogsController@restore']);
Route::post('blogs/delete/{id}', ['as' => 'blogs.forcedelete', 'uses' => 'BlogsController@forceDelete']);
Route::post('blogs/generate/slug', ['as' => 'blogs.generate.slug', 'uses' => 'BlogsController@generateSlug']);
Route::delete('blogsFileDelete/{id}', 'BlogsController@deleteFile')->name('blogs.deleteFile');
Route::post('blogs-load-selectbox', ['as' => 'blogs.load-selectbox', 'uses' => 'BlogsController@getSelectHtml']);


Route::resource('tag', 'TagController');
Route::post('tag/generate/slug', ['as' => 'tag.generate.slug', 'uses' => 'TagController@generateSlug']);


Route::resource('pages', 'PagesController');
Route::post('pages/bulk-action', ['as' => 'pages.bulk-action', 'uses' => 'PagesController@bulkAction']);
Route::post('pages/{id}', ['as' => 'pages.restore', 'uses' => 'PagesController@restore']);
Route::post('pages/delete/{id}', ['as' => 'pages.forcedelete', 'uses' => 'PagesController@forceDelete']);
Route::get('pages/file/delete/{record_id}/{record_type}/{filename}', ['as' => 'pages.deleteFile', 'uses' => 'PagesController@deleteFile']);

Route::resource('videos', 'VideosController');
Route::post('videos/bulk-action', ['as' => 'videos.bulk-action', 'uses' => 'VideosController@bulkAction']);
Route::post('videos/{id}', ['as' => 'videos.restore', 'uses' => 'VideosController@restore']);
Route::post('videos/delete/{id}', ['as' => 'videos.forcedelete', 'uses' => 'VideosController@forceDelete']);
Route::post('videos/generate/slug', ['as' => 'videos.generate.slug', 'uses' => 'VideosController@generateSlug']);


Route::resource('start_up_notice', 'StartUpNoticeController');
Route::post('start_up_notice/bulk-action', ['as' => 'start_up_notice.bulk-action', 'uses' => 'StartUpNoticeController@bulkAction']);
Route::post('start_up_notice/{id}', ['as' => 'start_up_notice.restore', 'uses' => 'StartUpNoticeController@restore']);
Route::post('start_up_notice/delete/{id}', ['as' => 'start_up_notice.forcedelete', 'uses' => 'StartUpNoticeController@forceDelete']);


Route::resource('media', 'MediaController');
Route::post('media/bulk-action', ['as' => 'media.bulk-action', 'uses' => 'MediaController@bulkAction']);
Route::post('media/{id}', ['as' => 'media.restore', 'uses' => 'MediaController@restore']);
Route::post('media/delete/{id}', ['as' => 'media.forcedelete', 'uses' => 'MediaController@forceDelete']);
Route::get('get-media','MediaController@getMedias')->name('get-media');

Route::resource('roles', 'RoleController');
Route::post('roles/bulk-action', ['as' => 'roles.bulk-action', 'uses' => 'RoleController@bulkAction']);

Route::resource('users', 'UserController');
Route::post('users/bulk-action', ['as' => 'users.bulk-action', 'uses' => 'UserController@bulkAction']);

Route::get('backups', 'BackupController@index')->name('backups.index');
Route::post('backups/create', 'BackupController@create')->name('backups.store');
Route::get('backups/download/', 'BackupController@download')->name('backups.download');
Route::delete('backups/delete/{file_name?}', 'BackupController@delete')->where('file_name', '(.*)')->name('backups.destroy');

Route::resource('menu', 'MenuController');
Route::post('menu/rank/update', ['as' => 'menu.update-rank','uses' => 'MenuController@updateRank']);
Route::post('menu/getItems', ['as' => 'menu.getItems', 'uses' => 'MenuController@getItems']);

Route::resource('feedback', 'FeedbackController');
Route::post('feedback/bulk-action', ['as' => 'feedback.bulk-action', 'uses' => 'FeedbackController@bulkAction']);
Route::post('feedback/{id}', ['as' => 'feedback.restore', 'uses' => 'FeedbackController@restore']);
Route::post('feedback/delete/{id}', ['as' => 'feedback.forcedelete', 'uses' => 'FeedbackController@forceDelete']);

Route::post('feedback_reply/submit', ['as' => 'feedbackreply.submit', 'uses' => 'FeedbackController@replySubmit']);

Route::resource('blog-category', 'BlogCategoryController');
Route::post('blog-category/rank/update', ['as' => 'blog-category.update-rank','uses' => 'BlogCategoryController@updateRank']);
Route::post('blog-category/use/bulk-action', ['as' => 'blog-category.bulk-action', 'uses' => 'BlogCategoryController@bulkAction']);
Route::post('blog-category/generate/slug', ['as' => 'blog-category.generate.slug', 'uses' => 'BlogCategoryController@generateSlug']);

Route::get('design_config/edit', ['as' => 'design_config.edit', 'uses' => 'SiteConfigController@designEdit']);
Route::post('design_config', ['as' => 'design_config.update', 'uses' => 'SiteConfigController@designUpdate']);

// Main editor page
    Route::get('/code-editor', [CodeEditorController::class, 'index'])->name('code-editor.index');

    // Open file (AJAX)
    Route::get('/code-editor/open', [CodeEditorController::class, 'open'])->name('code-editor.open');

    // Save file (AJAX)
    Route::post('/code-editor/save', [CodeEditorController::class, 'save'])->name('code-editor.save');

    // Upload file
    Route::post('/code-editor/upload', [CodeEditorController::class, 'upload'])->name('code-editor.upload');

    // Create directory
    Route::post('/code-editor/mkdir', [CodeEditorController::class, 'mkdir'])->name('code-editor.mkdir');

    // Delete file/folder
    Route::post('/code-editor/delete', [CodeEditorController::class, 'delete'])->name('code-editor.delete');

    // Rename file/folder
    Route::post('/code-editor/rename', [CodeEditorController::class, 'rename'])->name('code-editor.rename');



    Route::resource('person', PersonController::class)
    ->only(['index', 'show', 'edit', 'update']);



