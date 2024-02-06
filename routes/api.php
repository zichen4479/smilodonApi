<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/user/login', 'App\Http\Controllers\User\UserController@login');

/**
 * 系统语言API
 */
Route::namespace('App\Http\Controllers\System\Setting\Language')->group(function () {
    // 查看系统语言列表 分页 ?page = {page}
    Route::get('/system/setting/language', 'SystemSettingLanguageController@getAllSystemSettingLanguage');
    // 查看系统语言
    /**
     *
     * 支持模糊搜索
     * @example /system/language/1,/system/language/chin,/system/language/zh-cn,
     */
    Route::get('/system/setting/language/{query}', 'SystemSettingLanguageController@showSystemSettingLanguage');
});

/**
 * 系统菜单类型
 */
Route::get('/system/setting/menu/type',
    'App\Http\Controllers\System\Setting\MenuType\SystemSettingMenuTypeController@listMenuType');

/**
 * Categories
 */
Route::namespace('App\Http\Controllers\Front')->group(function () {
    Route::get('front/ads/{site_id?}', 'AdsController@listAds');
    Route::post('front/articles/{site_id}', 'ArticleController@listArticle');
    Route::get('front/article/{id?}', 'ArticleController@showArticle');
    Route::get('front/category/{site_id?}', 'CategoryController@listCategory');
    Route::get('front/file/{id}', 'FileController@showFile');
    Route::get('front/menu/{site_id?}', 'MenuController@listMenu');
    Route::get('front/page/{id}', 'PageController@showPage');
    Route::get('front/site', 'SiteController@listSite');
    Route::get('front/site/{id}', 'SiteController@showSite');
    Route::post('front/site', 'SiteController@showSiteByDomain');
    Route::post('front/message', 'MessageController@sendMessage');
    Route::get('front/project/{id}', 'ProjectController@showProject');
});

Route::group(['middleware' => ['auth.jwt']], function () {

    Route::get('/user/info', 'App\Http\Controllers\User\UserController@getUser');
    Route::post('/user/logout', 'App\Http\Controllers\User\UserController@logout');

    /**
     * Categories
     */
    Route::namespace('App\Http\Controllers\Category')->group(function () {
        Route::get('category/site/{site_id?}', 'CategoryController@listCategory');
        Route::post('category/{id}', 'CategoryController@editCategory');
        Route::post('category', 'CategoryController@addCategory');
        Route::delete('category/{id}', 'CategoryController@deleteCategory');
    });

    /**
     * Articles
     */
    Route::namespace('App\Http\Controllers\Article')->group(function () {
        Route::get('article/site/{site_id}/{limit?}', 'ArticleController@listArticle');
        Route::get('article/type', 'ArticleController@listArticleType');
        Route::get('video/type', 'ArticleController@listVideoType');
        Route::get('article/{id}', 'ArticleController@showArticle');
        Route::post('article/{id}', 'ArticleController@editArticle');
        Route::post('article', 'ArticleController@addArticle');
        Route::delete('article/{id}', 'ArticleController@deleteArticle');
    });

    /**
     * Site Config
     */
    Route::namespace('App\Http\Controllers\Site')->group(function () {
        Route::get('site/{id}', 'SiteController@showSite');
        Route::get('site', 'SiteController@listSite');
        Route::post('site/{id}', 'SiteController@editSite');
        Route::post('site', 'SiteController@addSite');
    });

    /**
     * Pages
     */
    Route::namespace('App\Http\Controllers\Page')->group(function () {
        Route::get('page/site/{site_id?}', 'PageController@listPage');
        Route::get('page/{id}', 'PageController@showPage');
        Route::post('page/{id}', 'PageController@editPage');
        Route::post('page', 'PageController@addPage');
        Route::delete('page/{id}', 'PageController@deletePage');
    });

    /**
     * Files
     */
    Route::namespace('App\Http\Controllers\File')->group(function () {
        Route::get('file/{id}', 'FileController@showFile');
        Route::get('file', 'FileController@listFile');
        Route::post('file', 'FileController@addFile');
        Route::post('bigfile', 'FileController@addBigFile');
        Route::delete('file/{id}', 'FileController@deleteFile');
    });

    /**
     * Ads
     */
    Route::namespace('App\Http\Controllers\Ads')->group(function () {
        Route::get('ad/site/{site_id?}', 'AdsController@listAd');
        Route::get('ad/{id}', 'AdsController@showAd');
        Route::post('ad/{id}', 'AdsController@editAd');
        Route::post('ad', 'AdsController@addAd');
        Route::delete('ad/{id}', 'AdsController@deleteAd');
    });

    /**
     * projects
     */
    Route::namespace('App\Http\Controllers\Projects')->group(function () {
        Route::get('project', 'ProjectController@listProject');
        Route::get('project/{id}', 'ProjectController@showProject');
        Route::post('project/{id}', 'ProjectController@editProject');
        Route::post('project', 'ProjectController@addProject');
        Route::delete('project/{id}', 'ProjectController@deleteProject');
    });

    /**
     * Menu
     */
    Route::namespace('App\Http\Controllers\Menu')->group(function () {
        Route::get('menu/site/{site_id?}', 'MenuController@listMenu');
        Route::get('menu/{id}', 'MenuController@showMenu');
        Route::post('menu/{id}', 'MenuController@editMenu');
        Route::post('menu', 'MenuController@addMenu');
        Route::delete('menu/{id}', 'MenuController@deleteMenu');
    });
});

Route::fallback(function () {
    $content = array(
        'code' => 49998,
        'data' => null,
        'msg' => 'Not Found!'
    );
    return response($content, 404);
});
