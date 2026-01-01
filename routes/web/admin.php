<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PathController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\RestrictionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SettingMenuController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::name('admin.')->prefix('admin')->group(function () {

    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');

    Route::middleware(['auth', 'admin'])->group(function () {

        /********* Books *********/
        Route::get('/', fn() => redirect()->route('admin.courses.index'));
        Route::get('/books', [BookController::class, 'index'])->name('books.index');
        Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
        Route::post('/books/store', [BookController::class, 'store'])->name('books.store');
        Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
        Route::put('/books/{book}/update', [BookController::class, 'update'])->name('books.update');

        Route::get('/books/categories', [BookCategoryController::class, 'index'])->name('books.categories.index');
        Route::post('/books/categories/store', [BookCategoryController::class, 'store'])->name('books.categories.store');
        Route::put('/books/categories/{category}/update', [BookCategoryController::class, 'update'])->name('books.categories.update');
        Route::delete('/books/categories/{category}/destroy', [BookCategoryController::class, 'destroy'])->name('books.categories.destroy');

        /********* Courses *********/
        Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses/store', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}/update', [CourseController::class, 'update'])->name('courses.update');

        Route::get('/courses/categories', [CourseCategoryController::class, 'index'])->name('courses.categories.index');
        Route::post('/courses/categories/store', [CourseCategoryController::class, 'store'])->name('courses.categories.store');
        Route::put('/courses/categories/{category}/update', [CourseCategoryController::class, 'update'])->name('courses.categories.update');
        Route::delete('/courses/categories/{category}/destroy', [CourseCategoryController::class, 'destroy'])->name('courses.categories.destroy');

        /********* Paths *********/
        Route::get('/paths', [PathController::class, 'index'])->name('paths.index');
        Route::post('/paths/store', [PathController::class, 'store'])->name('paths.store');
        Route::delete('/paths/{path}/destroy', [PathController::class, 'destroy'])->name('paths.destroy');

        /********* Pages *********/
        Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('/pages/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}/update', [PageController::class, 'update'])->name('pages.update');

        /********* Orders *********/
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
        Route::put('/orders/{order}/update', [OrderController::class, 'update'])->name('orders.update');

        /********* quizzes *********/
        Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes.index');
        Route::get('/quizzes/{video}/edit', [QuizController::class, 'edit'])->name('quizzes.edit');
        Route::get('/video/url/{video}', [QuizController::class, 'url'])->name('video.url');
        Route::get('/video/poster/{video}', [QuizController::class, 'poster'])->name('video.poster');
        Route::put('/quizzes/{video}', [QuizController::class, 'update'])->name('quizzes.update');

        /********* comments *********/
        Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
        Route::post('/comments/{comment}/approve', [CommentController::class, 'approve'])->name('comments.approve');
        Route::post('/comments/{comment}/reply', [CommentController::class, 'reply'])->name('comments.reply');
        Route::delete('/comments/{comment}/destroy', [CommentController::class, 'destroy'])->name('comments.destroy');

        /********* Users *********/
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}/update', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/restrictions/', [RestrictionController::class, 'store'])->name('users.restrictions.store');
        Route::put('/users/restrictions/{restriction}', [RestrictionController::class, 'update'])->name('users.restrictions.update');

        /********* supports *********/
        Route::get('/supports', [TicketController::class, 'index'])->name('tickets.index');
        Route::put('/supports/{ticket}/archive', [TicketController::class, 'archive'])->name('tickets.archive');

        /********* Contact *********/
        Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');
        Route::put('/contacts/{contact}/archive', [ContactController::class, 'archive'])->name('contacts.archive');

        /********* Setting *********/
        Route::get('/settings/general', [SettingController::class, 'general'])->name('settings.general');
        Route::get('/settings/menus', [SettingMenuController::class, 'index'])->name('settings.menus');
        Route::post('/settings/menus', [SettingMenuController::class, 'store'])->name('settings.menus.store');
        Route::put('/settings/{menu}/menus', [SettingMenuController::class, 'update'])->name('settings.menus.update');
        Route::put('/settings/menus/order', [SettingMenuController::class, 'order'])->name('settings.menus.order');
        Route::delete('/settings/{menu}/destroy', [SettingMenuController::class, 'destroy'])->name('settings.menus.destroy');
        Route::put('/settings/slides/update', [SettingController::class, 'slideUpdate'])->name('settings.slides.update');
        Route::delete('/settings/slides/{id}/destroy', [SettingController::class, 'slideDelete'])->name('settings.slides.destroy');
        Route::put('/settings/social/update', [SettingController::class, 'socialUpdate'])->name('settings.social.update');
        Route::put('/settings/logos/update', [SettingController::class, 'imagesUpdate'])->name('settings.logos.update');

        /********* media & upload *********/
        Route::post('upload/books/image', [UploadController::class, 'bookImage'])->name('upload.books.image');
        Route::post('upload/courses/image', [UploadController::class, 'courseImage'])->name('upload.courses.image');
        Route::post('upload/users/image', [UploadController::class, 'userImage'])->name('upload.users.image');
        Route::post('upload/pages/image', [UploadController::class, 'pageImage'])->name('upload.pages.image');
        Route::post('upload/general/image', [UploadController::class, 'generalImage'])->name('upload.general.image');

        Route::delete('media/{media}/{type}', [UploadController::class, 'destroy'])->name('media.destroy');

        /********* Auth *********/
        Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
        Route::put('profile/update', [AuthController::class, 'update'])->name('profile.update');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/test', [TestController::class, 'index']);
    });

});


