<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\HistoryInController;
use App\Http\Controllers\Admin\HistoryOutController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\NewMaterialController;
use App\Http\Controllers\Admin\QRCodeController;
use App\Http\Controllers\Admin\StockInController;
use App\Http\Controllers\Admin\StockOutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Boards\BoardController;
use App\Http\Controllers\Informations\DepartmentController;
use App\Http\Controllers\Informations\InformationsController;
use App\Http\Controllers\Informations\MaterialController as InformationsMaterialController;
use App\Http\Controllers\Informations\MemberController;
use App\Http\Controllers\Informations\UnitController;
use App\Http\Controllers\Informations\UserProfileController;
use App\Http\Controllers\Informations\WareHouseController;
use App\Http\Controllers\LineNotiController;
use App\Http\Controllers\Users\AccountDetailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::prefix('user')->group(function () {
    Route::get('login', [LoginController::class, 'index'])->name('login');
    Route::post('auth', [LoginController::class, 'auth'])->name('user.auth');

    Route::get('register', [RegisterController::class, 'index'])->name('register');
    Route::post('create', [RegisterController::class, 'create'])->name('user.register');

    Route::post('logout', [LoginController::class, 'logout'])->name('user.logout');

    Route::group(['middleware' => ['auth']], function () {
        Route::get('auth/line/callback', [LineNotiController::class, 'auth_callback'])->name('user.auth.line.callback');
        Route::post('line/test/noti', [LineNotiController::class, 'test_noti'])->name('user.test.line.noti');
        Route::get('detail', [AccountDetailController::class, 'index'])->name('user.detail');
        Route::post('detail/update', [AccountDetailController::class, 'updateProfile'])->name('user.detail.update');
    });
});

//Auth
Route::group(['middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');

    // Route::prefix('qrcode')->group(function () {
    //     Route::get('search', [QRCodeController::class, 'search'])->name('qr.code.search');
    // Route::get('scan/{m_code}', [QRCodeController::class, 'getMaterialByCode'])->name('qrcode.scan');

    //     Route::get('stock/out/get', [QRCodeController::class, 'getStockOutItems'])->name('qrcode.stock.out.get');
    //     Route::post('stock/out/create', [QRCodeController::class, 'updateStockOut'])->name('qrcode.stock.out.create');
    // });

    // Route::prefix('history')->group(function () {
    //     Route::get('out', [HistoryController::class, 'historyOutView'])->name('history.out.view');
    //     Route::get('out/search', [HistoryController::class, 'searchStockOut'])->name('history.out.search');
    //     Route::get('out/pagination', [HistoryController::class, 'stockOutFetchData'])->name('history.out.pagination');
    // });

    // Route::prefix('material')->group(function () {
    //     Route::get('search', [MaterialController::class, 'search'])->name('material.search');
    //     Route::get('pagination', [MaterialController::class, 'fetch_data'])->name('material.pagination');
    // });

    Route::prefix('reports')->group(function () {
        Route::view('home', 'reports.report_home')->name('reports.home');
        Route::get('show', 'Boards\ReportsController@reportView')->name('reports.show');

        Route::get('stock/in', 'Boards\ReportsController@stockInlistAll');
        Route::get('stock/in/orderBy', 'Boards\ReportsController@stockInOrderBySum');

        Route::get('stock/out', 'Boards\ReportsController@stockOutlistAll');
        Route::get('stock/out/orderBy', 'Boards\ReportsController@stockOutOrderBySum');

        Route::get('by/date', 'Boards\ReportsController@reportByDate')->name('reports.by.date');

        Route::get('by/warehouse', 'Boards\ReportsController@reportByWarehouse');
        Route::get('by/type', 'Boards\ReportsController@reportByType');
        Route::get('by/price', 'Boards\ReportsController@reportByPrice');

        Route::post('download/excelStockInDate', 'Boards\ReportsController@excelReportDateStockIn')->name('reports.download.excelStockInDate');
        Route::post('download/excelStockOutDate', 'Boards\ReportsController@excelReportDateStockOut')->name('reports.download.excelStockOutDate');
        Route::post('download/excelStockInWarehouse', 'Boards\ReportsController@excelReportWarehouseStockIn')->name('reports.download.excelStockInWarehouse');
        Route::post('download/excelStockOutWarehouse', 'Boards\ReportsController@excelReportWarehouseStockOut')->name('reports.download.excelStockOutWarehouse');
        Route::post('download/excelStockInType', 'Boards\ReportsController@excelReportTypeStockIn')->name('reports.download.excelStockInType');
        Route::post('download/excelStockOutType', 'Boards\ReportsController@excelReportTypeStockOut')->name('reports.download.excelStockOutType');
    });
});

//Board
Route::group(['prefix' => 'board'], function () {
    Route::group(['middleware' => ['board']], function () {
        Route::get('home', [BoardController::class, 'dashboardView'])->name('board.home');
    });
});

// Admin
Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => ['admin']], function () {

        Route::post('users/save', 'UserProfileController@insertUser');

        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        Route::prefix('history')->group(function () {
            Route::get('in', [HistoryInController::class, 'historyInView'])->name('admin.history.in.view');
            // Route::get('in/search', [HistoryInController::class, 'searchStockIn'])->name('admin.history.in.search');
            Route::get('in/pagination', [HistoryInController::class, 'stockInFetchData'])->name('admin.history.in.pagination');
            Route::post('in/update', [HistoryInController::class, 'update'])->name('admin.history.in.update');
            Route::get('in/cancel/{stockId}', [HistoryInController::class, 'cancel'])->name('admin.history.in.cancel');
            // Route::post('in/download/excel', [HistoryInController::class, 'downloadExcel'])->name('admin.history.in.download.excel');

            Route::get('out', [HistoryOutController::class, 'historyOutView'])->name('admin.history.out.view');
            // Route::get('out/search', [HistoryOutController::class, 'searchStockOut'])->name('admin.history.out.search');
            Route::get('out/pagination', [HistoryOutController::class, 'stockOutFetchData'])->name('admin.history.out.pagination');
            Route::post('out/update', [HistoryOutController::class, 'update'])->name('admin.history.out.update');
            Route::get('out/cancel/{stockId}', [HistoryOutController::class, 'cancel'])->name('admin.history.out.cancel');
        });

        Route::prefix('informations')->group(function () {
            Route::get('/', [InformationsController::class, 'index'])->name('admin.informations');
            Route::post('user/add', [InformationsController::class, 'addUser'])->name('informations.user.add');
            Route::post('user/edit', [UserProfileController::class, 'updateUserProfile'])->name('informations.user.edit');
        });

        Route::prefix('qrcode')->group(function () {
            Route::get('/', [QRCodeController::class, 'showQrcode'])->name('admin.qr.code');
            Route::get('/search/pagination', [QRCodeController::class, 'search'])->name('admin.qr.code.search');

            Route::get('scan/material/{m_code}', [QRCodeController::class, 'getMaterialByCode'])->name('admin.qrcode.scan');

            Route::get('scan/in', [QRCodeController::class, 'scanInView'])->name('admin.qrcode.scan.in.view');
            Route::put('scan/in/exp', [QRCodeController::class, 'updateStockInExp'])->name('admin.qrcode.stock.in.update.exp');
            Route::put('scan/in', [QRCodeController::class, 'updateStockIn'])->name('admin.qrcode.stock.in.update');

            Route::get('scan/out', [QRCodeController::class, 'scanOutView'])->name('admin.qrcode.scan.out.view');
            Route::get('scan/out/list', [QRCodeController::class, 'getStockOutsList'])->name('admin.qrcode.scan.out.list');
            Route::get('scan/out/members', [QRCodeController::class, 'getMembers'])->name('admin.qrcode.scan.out.members');
            Route::put('scan/out', [QRCodeController::class, 'updateStockOut'])->name('admin.qrcode.scan.out.update');
        });

        Route::prefix('material')->group(function () {
            Route::get('detail/{m_code}', [MaterialController::class, 'getMaterialById'])->name('admin.home.get.material.by.id');

            //stockin
            Route::get('stock/in', [StockInController::class, 'index'])->name('material.stock.in.view');
            Route::get('stock/in/search', [StockInController::class, 'search'])->name('admin.material.stock.in.search');
            Route::post('stock/in', [StockInController::class, 'updateMaterial'])->name('admin.material.stock.in.update');

            //stockout
            Route::get('stock/out', [StockOutController::class, 'index'])->name('admin.material.stock.out.view');
            Route::get('stock/out/pagination', [StockOutController::class, 'fetch_data'])->name('admin.material.stock.out.search');
            Route::post('stock/out/members', [StockOutController::class, 'getUserByName'])->name('admin.get.member');
            Route::post('stock/out', [StockOutController::class, 'widthDraw'])->name('admin.material.stock.out.widthdraw');

            //new materail
            Route::get('new', [NewMaterialController::class, 'index'])->name('admin.material.new.view');
            Route::post('create', [NewMaterialController::class, 'addMaterial'])->name('admin.material.new.add');

            //add CRUD
            Route::post('type/add', [InformationsMaterialController::class, 'addType'])->name('material.type.add');
            Route::post('unit/add', [UnitController::class, 'addUnit'])->name('material.unit.add');
            Route::post('warehouse/add', [WareHouseController::class, 'addWarehouse'])->name('material.warehouse.add');
            Route::post('department/add', [DepartmentController::class, 'addDep'])->name('material.department.add');
            Route::post('member/add', [MemberController::class, 'addMember'])->name('material.member.add');

            //edit CRUD
            Route::post('edit', [InformationsMaterialController::class, 'editMaterial'])->name('material.edit');
            Route::post('type/edit', [MaterialTypeController::class, 'editType'])->name('material.type.edit');
            Route::post('unit/edit', [UnitController::class, 'editUnit'])->name('material.unit.edit');
            Route::post('warehouse/edit', [WareHouseController::class, 'editWarehouse'])->name('material.warehouse.edit');
            Route::post('department/edit', [DepartmentController::class, 'editDep'])->name('material.department.edit');
            Route::post('member/edit', [MemberController::class, 'editMember'])->name('material.member.edit');

            //delete CRUD
            Route::post('delete', [InformationsMaterialController::class, 'deleteMaterialById'])->name('material.material.delete');
            Route::post('unit/delete', [UnitController::class, 'deleteUnitById'])->name('material.unit.delete');
            Route::post('type/delete', [MaterialTypeController::class, 'deleteTypeById'])->name('material.type.delete');
            Route::post('warehouse/delete', [WareHouseController::class, 'deleteWarehouseById'])->name('material.warehouse.delete');
            Route::post('department/delete', [DepartmentController::class, 'deleteDepById'])->name('material.department.delete');
            Route::post('member/delete', [MemberController::class, 'deleteMember'])->name('material.member.delete');
        });
    });
});
