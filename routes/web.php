<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCutController;
use App\Http\Controllers\AdminColorController;
use App\Http\Controllers\AdminIssueController;
use App\Http\Controllers\AdminKapanController;
use App\Http\Controllers\AdminKhataController;
use App\Http\Controllers\AdminPartyController;
use App\Http\Controllers\AdminShapeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminIncomeController;
use App\Http\Controllers\AdminPolishController;
use App\Http\Controllers\AdminWorkerController;
use App\Http\Controllers\AdminClarityController;
use App\Http\Controllers\AdminCompanyController;
use App\Http\Controllers\AdminDiamondController;
use App\Http\Controllers\AdminExpenseController;
use App\Http\Controllers\AdminSymmetryController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminKhataBillController;
use App\Http\Controllers\AdminKapanPartsController;
use App\Http\Controllers\AdminDesignationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ReportController;

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

Route::get('/', function () {
    return view('auth.login');
})->name('admin.login');


// // Route::post('/login', [LoginController::class, 'login']);
// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/register', [RegisterController::class, 'register']);
// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('/password/email', [AdminController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Route::get('/password-reset', [AdminController::class, 'showResetForm'])->name('password.reset');
// Route::post('/password-reset', [AdminController::class, 'resetPassword'])->name('password.update');

// // Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
// // Route::get('/password/reset/{token}', [AdminController::class, 'showResetForm'])->name('password.reset');



//  for admin registration below comment uncomment karvi and above auth.login ne comment karvi
// Route::get('/', function () {
//     return view('welcome');
// });
// Auth::routes();

// Route::get('/logout', 'Auth\LoginController@logout');
Route::post('/login', [AdminController::class, 'login'])->name('login');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth', 'usersession']], function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin');
    Route::get('/admin/dashboard2', [AdminController::class, 'dashboard2'])->name('admin2');

    Route::get('/profile/{id}', [AdminController::class, 'profiledit'])->name('profile.edit');
    Route::post('/profile/update', [AdminController::class, 'profileUpdate'])->name('profile.update');

    Route::get("admin/company", [AdminCompanyController::class, 'index'])->name('admin.company.index');
    Route::get('admin/company/create', [AdminCompanyController::class, 'create'])->name('admin.company.create');
    Route::post('admin/company/store', [AdminCompanyController::class, 'store'])->name('admin.company.store');
    Route::get('admin/company/edit/{id}', [AdminCompanyController::class, 'edit'])->name('admin.company.edit');
    Route::patch('admin/company/update/{id}', [AdminCompanyController::class, 'update'])->name('admin.company.update');
    Route::get('admin/company/destroy/{id}', [AdminCompanyController::class, 'destroy'])->name('admin.company.destroy');

    Route::get("admin/kapan", [AdminKapanController::class, 'index'])->name('admin.kapan.index');
    Route::get('admin/kapan/create', [AdminKapanController::class, 'create'])->name('admin.kapan.create');
    Route::post('admin/kapan/store', [AdminKapanController::class, 'store'])->name('admin.kapan.store');
    Route::get('admin/kapan/edit/{id}', [AdminKapanController::class, 'edit'])->name('admin.kapan.edit');
    Route::patch('admin/kapan/update/{id}', [AdminKapanController::class, 'update'])->name('admin.kapan.update');
    Route::get('admin/kapan/destroy/{id}', [AdminKapanController::class, 'destroy'])->name('admin.kapan.destroy');
    Route::post("admin/kapan/active", [AdminKapanController::class, 'statusUpdate'])->name('admin.kapan.active');

    Route::get("admin/kapan_part", [AdminKapanPartsController::class, 'index'])->name('admin.kapan_part.index');
    Route::get("admin/get_kapan_part", [AdminKapanPartsController::class, 'getKapanParts'])->name('admin.get_kapan_part.index');
    Route::post("admin/kapan_part/update-weight", [AdminKapanPartsController::class, 'updateSingleWeight'])->name('admin.kapan.parts.update.single');


    Route::get('admin/kapan_part/create', [AdminKapanPartsController::class, 'create'])->name('admin.kapan_part.create');
    Route::post('admin/kapan_part/store', [AdminKapanPartsController::class, 'store'])->name('admin.kapan_part.store');
    Route::get('admin/kapan_part/edit/{id}', [AdminKapanPartsController::class, 'edit'])->name('admin.kapan_part.edit');
    Route::patch('admin/kapan_part/update/{id}', [AdminKapanPartsController::class, 'update'])->name('admin.kapan_part.update');
    Route::get('admin/kapan_part/destroy/{id}', [AdminKapanPartsController::class, 'destroy'])->name('admin.kapan_part.destroy');
    Route::post("admin/kapan_part/active", [AdminKapanPartsController::class, 'statusUpdate'])->name('admin.kapan_part.active');


    Route::get("admin/designation", [AdminDesignationController::class, 'index'])->name('admin.designation.index');
    Route::get('admin/designation/show/{id}', [AdminDesignationController::class, 'show'])->name('admin.designation.show');
    Route::get('admin/designation/create', [AdminDesignationController::class, 'create'])->name('admin.designation.create');
    Route::post('admin/designation/store', [AdminDesignationController::class, 'store'])->name('admin.designation.store');
    Route::get('admin/designation/edit/{id}', [AdminDesignationController::class, 'edit'])->name('admin.designation.edit');
    Route::patch('admin/designation/update/{id}', [AdminDesignationController::class, 'update'])->name('admin.designation.update');
    Route::get('admin/designation/destroy/{id}', [AdminDesignationController::class, 'destroy'])->name('admin.designation.destroy');

    Route::get("admin/color", [AdminColorController::class, 'index'])->name('admin.color.index');
    Route::get('admin/color/create', [AdminColorController::class, 'create'])->name('admin.color.create');
    Route::post('admin/color/store', [AdminColorController::class, 'store'])->name('admin.color.store');
    Route::get('admin/color/edit/{id}', [AdminColorController::class, 'edit'])->name('admin.color.edit');
    Route::patch('admin/color/update/{id}', [AdminColorController::class, 'update'])->name('admin.color.update');
    Route::get('admin/color/destroy/{id}', [AdminColorController::class, 'destroy'])->name('admin.color.destroy');

    Route::get("admin/shape", [AdminShapeController::class, 'index'])->name('admin.shape.index');
    Route::get('admin/shape/create', [AdminShapeController::class, 'create'])->name('admin.shape.create');
    Route::post('admin/shape/store', [AdminShapeController::class, 'store'])->name('admin.shape.store');
    Route::get('admin/shape/edit/{id}', [AdminShapeController::class, 'edit'])->name('admin.shape.edit');
    Route::patch('admin/shape/update/{id}', [AdminShapeController::class, 'update'])->name('admin.shape.update');
    Route::get('admin/shape/destroy/{id}', [AdminShapeController::class, 'destroy'])->name('admin.shape.destroy');

    Route::get("admin/clarity", [AdminClarityController::class, 'index'])->name('admin.clarity.index');
    Route::get('admin/clarity/create', [AdminClarityController::class, 'create'])->name('admin.clarity.create');
    Route::post('admin/clarity/store', [AdminClarityController::class, 'store'])->name('admin.clarity.store');
    Route::get('admin/clarity/edit/{id}', [AdminClarityController::class, 'edit'])->name('admin.clarity.edit');
    Route::patch('admin/clarity/update/{id}', [AdminClarityController::class, 'update'])->name('admin.clarity.update');
    Route::get('admin/clarity/destroy/{id}', [AdminClarityController::class, 'destroy'])->name('admin.clarity.destroy');

    Route::get("admin/cut", [AdminCutController::class, 'index'])->name('admin.cut.index');
    Route::get('admin/cut/create', [AdminCutController::class, 'create'])->name('admin.cut.create');
    Route::post('admin/cut/store', [AdminCutController::class, 'store'])->name('admin.cut.store');
    Route::get('admin/cut/edit/{id}', [AdminCutController::class, 'edit'])->name('admin.cut.edit');
    Route::patch('admin/cut/update/{id}', [AdminCutController::class, 'update'])->name('admin.cut.update');
    Route::get('admin/cut/destroy/{id}', [AdminCutController::class, 'destroy'])->name('admin.cut.destroy');

    Route::get("admin/polish", [AdminPolishController::class, 'index'])->name('admin.polish.index');
    Route::get('admin/polish/create', [AdminPolishController::class, 'create'])->name('admin.polish.create');
    Route::post('admin/polish/store', [AdminPolishController::class, 'store'])->name('admin.polish.store');
    Route::get('admin/polish/edit/{id}', [AdminPolishController::class, 'edit'])->name('admin.polish.edit');
    Route::patch('admin/polish/update/{id}', [AdminPolishController::class, 'update'])->name('admin.polish.update');
    Route::get('admin/polish/destroy/{id}', [AdminPolishController::class, 'destroy'])->name('admin.polish.destroy');

    Route::get("admin/symmetry", [AdminSymmetryController::class, 'index'])->name('admin.symmetry.index');
    Route::get('admin/symmetry/create', [AdminSymmetryController::class, 'create'])->name('admin.symmetry.create');
    Route::post('admin/symmetry/store', [AdminSymmetryController::class, 'store'])->name('admin.symmetry.store');
    Route::get('admin/symmetry/edit/{id}', [AdminSymmetryController::class, 'edit'])->name('admin.symmetry.edit');
    Route::patch('admin/symmetry/update/{id}', [AdminSymmetryController::class, 'update'])->name('admin.symmetry.update');
    Route::get('admin/symmetry/destroy/{id}', [AdminSymmetryController::class, 'destroy'])->name('admin.symmetry.destroy');

    Route::get("admin/worker", [AdminWorkerController::class, 'index'])->name('admin.worker.index');
    Route::get('admin/worker/create', [AdminWorkerController::class, 'create'])->name('admin.worker.create');
    Route::post('admin/worker/store', [AdminWorkerController::class, 'store'])->name('admin.worker.store');
    Route::get('admin/worker/edit/{id}', [AdminWorkerController::class, 'edit'])->name('admin.worker.edit');
    Route::patch('admin/worker/update/{id}', [AdminWorkerController::class, 'update'])->name('admin.worker.update');
    Route::get('admin/worker/destroy/{id}', [AdminWorkerController::class, 'destroy'])->name('admin.worker.destroy');
    Route::get("admin/worker/active/{id}", [AdminWorkerController::class, 'workerActive'])->name('admin.worker.active');

    Route::post('admin/get-workers', [AdminWorkerController::class, 'getWorkersByDesignation'])->name('admin.getworker');


    Route::get("admin/sub-division", [AdminDiamondController::class, 'subDivision'])->name('admin.sub-division.index');
    Route::post('admin/get-kapan-parts', [AdminDiamondController::class, 'getKapanParts'])->name('admin.getKapanParts');

    Route::get("admin/diamonds", [AdminDiamondController::class, 'index'])->name('admin.diamonds.index');
    Route::post('admin/diamond/store', [AdminDiamondController::class, 'store'])->name('admin.diamond.store');
    Route::post('admin/diamond/update/{id}', [AdminDiamondController::class, 'update'])->name('admin.diamond.update');
    Route::post('admin/diamond/delete/{id}', [AdminDiamondController::class, 'delete'])->name('admin.diamond.delete');
    Route::get('admin/diamond/edit/{id}', [AdminDiamondController::class, 'edit'])->name('admin.diamond.edit');
    Route::patch('admin/diamond/updatebyedit/{id}', [AdminDiamondController::class, 'updateByEdit'])->name('admin.diamond.updateByEdit');
    Route::get('admin/diamond/destroy/{id}', [AdminDiamondController::class, 'destroy'])->name('admin.diamond.destroy');

    Route::post(
        'admin/issue/delete-inline/{id}',
        [AdminIssueController::class, 'deleteInline']
    );

    Route::post(
        'admin/issue/update-inline/{id}',
        [AdminIssueController::class, 'updateInline']
    );


    Route::get("admin/issue", [AdminIssueController::class, 'index'])->name('admin.issue.index');
    Route::post('admin/issue/store', [AdminIssueController::class, 'store'])->name('admin.issue.store');
    Route::get("admin/return", [AdminIssueController::class, 'return'])->name('admin.return.index');
    Route::post('/admin/return/store', [AdminIssueController::class, 'storeReturn'])
        ->name('admin.return.store');

    // Route::get("admin/issue", [AdminIssueController::class, 'index'])->name('admin.issue.index');
    // Route::post('admin/issue/store', [AdminIssueController::class, 'store'])->name('admin.issue.store');

    Route::post('admin/get-issued-kapan-parts', [AdminIssueController::class, 'getIssuedKapanParts'])->name('admin.getIssuedKapanParts');

    Route::get("admin/purchase", [AdminDiamondController::class, 'purchase'])->name('admin.purchase.index');
    Route::get('admin/purchase/export', [AdminDiamondController::class, 'export'])
        ->name('admin.purchase.export');
    Route::get('admin/purchase/edit/{id}', [AdminDiamondController::class, 'purchaseEdit'])->name('admin.purchase.edit');
    Route::patch('admin/purchase/update/{id}', [AdminDiamondController::class, 'purchaseUpdate'])->name('admin.purchase.update');

    Route::post('/admin/sell/store', [AdminDiamondController::class, 'sellStore'])
        ->name('admin.sell.store');

    Route::get("admin/sell", [AdminDiamondController::class, 'sellList'])->name('admin.sell.index');
    Route::get('admin/sell/edit/{id}', [AdminDiamondController::class, 'sellEdit'])->name('admin.sell.edit');
    Route::patch('admin/sell/update/{id}', [AdminDiamondController::class, 'sellUpdate'])->name('admin.sell.update');
    Route::get('admin/sell/destroy/{id}', [AdminDiamondController::class, 'sellDestroy'])->name('admin.sell.destroy');
    Route::get('admin/updatePartyBrokerFieldvalue', [AdminDiamondController::class, 'updatePartyBrokerFieldvalue'])->name('admin.updatePartyBrokerFieldvalue');


    Route::get("admin/party", [AdminPartyController::class, 'index'])->name('admin.party.index');
    Route::get('admin/party/create', [AdminPartyController::class, 'create'])->name('admin.party.create');
    Route::post('admin/party/store', [AdminPartyController::class, 'store'])->name('admin.party.store');
    Route::get('admin/party/edit/{id}', [AdminPartyController::class, 'edit'])->name('admin.party.edit');
    Route::patch('admin/party/update/{id}', [AdminPartyController::class, 'update'])->name('admin.party.update');
    Route::get('admin/party/destroy/{id}', [AdminPartyController::class, 'destroy'])->name('admin.party.destroy');
    Route::get("admin/party/active/{id}", [AdminPartyController::class, 'partyActive'])->name('admin.party.active');

    Route::get("admin/khata", [AdminKhataController::class, 'index'])->name('admin.khata.index');
    Route::get('admin/khata/create', [AdminKhataController::class, 'create'])->name('admin.khata.create');
    Route::post('admin/khata/store', [AdminKhataController::class, 'store'])->name('admin.khata.store');
    Route::get('admin/khata/edit/{id}', [AdminKhataController::class, 'edit'])->name('admin.khata.edit');
    Route::patch('admin/khata/update/{id}', [AdminKhataController::class, 'update'])->name('admin.khata.update');
    Route::get('admin/khata/destroy/{id}', [AdminKhataController::class, 'destroy'])->name('admin.khata.destroy');
    Route::get("admin/khata/active/{id}", [AdminKhataController::class, 'khataActive'])->name('admin.khata.active');

    Route::get("admin/income", [AdminIncomeController::class, 'index'])->name('admin.income.index');
    Route::get('admin/income/create', [AdminIncomeController::class, 'create'])->name('admin.income.create');
    Route::post('admin/income/store', [AdminIncomeController::class, 'store'])->name('admin.income.store');
    Route::get('admin/income/edit/{id}', [AdminIncomeController::class, 'edit'])->name('admin.income.edit');
    Route::patch('admin/income/update/{id}', [AdminIncomeController::class, 'update'])->name('admin.income.update');
    Route::get('admin/income/destroy/{id}', [AdminIncomeController::class, 'destroy'])->name('admin.income.destroy');


    Route::post('/khata-bill/store', [AdminKhataBillController::class, 'store'])->name('admin.khatabill.store');
    Route::get('/khata-bill/{id}/edit', [AdminKhataBillController::class, 'edit'])->name('admin.khatabill.edit');
    Route::put('/khata-bill/{id}', [AdminKhataBillController::class, 'update'])->name('admin.khatabill.update');

    // Route::get("admin/khatabill", [AdminKhataBillController::class, 'index'])->name('admin.khatabill.index');
    // Route::get('admin/khatabill/create', [AdminKhataBillController::class, 'create'])->name('admin.khatabill.create');
    // Route::post('admin/khatabill/store', [AdminKhataBillController::class, 'store'])->name('admin.khatabill.store');
    // Route::get('admin/khatabill/edit/{id}', [AdminKhataBillController::class, 'edit'])->name('admin.khatabill.edit');
    // Route::patch('admin/khatabill/update/{id}', [AdminKhataBillController::class, 'update'])->name('admin.khatabill.update');
    // Route::get('admin/khatabill/destroy/{id}', [AdminKhataBillController::class, 'destroy'])->name('admin.khatabill.destroy');

    Route::get("admin/expen", [AdminExpenseController::class, 'index'])->name('admin.expnese.index');
    Route::post('/expense/store', [AdminExpenseController::class, 'store'])->name('admin.expense.store');
    Route::get('/expense/{id}/edit', [AdminExpenseController::class, 'edit'])->name('admin.expense.edit');
    Route::put('/expense/{id}', [AdminExpenseController::class, 'update'])->name('admin.expense.update');
    // Route::get('admin/expnese/create', [AdminExpenseController::class, 'create'])->name('admin.expnese.create');
    // Route::post('admin/expnese/store', [AdminExpenseController::class, 'store'])->name('admin.expnese.store');
    Route::get('admin/expnese/edit/{id}', [AdminExpenseController::class, 'editExpense'])->name('admin.expnese.edit.simple');
    // Route::patch('admin/expnese/update/{id}', [AdminExpenseController::class, 'update'])->name('admin.expnese.update');
    // Route::get('admin/expnese/destroy/{id}', [AdminExpenseController::class, 'destroy'])->name('admin.expnese.destroy');

    Route::get("admin/update-diamond-status-to-sell", [AdminDiamondController::class, 'updateDiamondStatusToSell'])->name('admin.update.diamonds.status');
    Route::get("admin/all-diamonds", [AdminDiamondController::class, 'allDiamonds'])->name('admin.all.diamonds');
    Route::get('admin/diamond/detail/{id}', [AdminDiamondController::class, 'diamondDetail'])->name('admin.diamond.detail');

    Route::post('admin/diamond/update-name', [AdminDiamondController::class, 'updateName'])
        ->name('admin.diamond.update.name');

    Route::get('admin/expense-summary', [ReportController::class, 'expenseSummary'])
        ->name('admin.expense-summary');
    Route::get('admin/ledger/{id}', [ReportController::class, 'ledger'])
        ->name('admin.ledger');

    Route::get('/admin/income-summary', [ReportController::class, 'incomeSummary'])
        ->name('admin.income-summary');

    Route::get('admin/worker-report', [ReportController::class, 'index'])
        ->name('worker.report');

    Route::get(
        'get-workers-by-designation',
        [ReportController::class, 'getWorkers']
    );

    Route::get('admin/kapan-report', [ReportController::class, 'kapanReport'])
        ->name('kapan.report');
    Route::get(
        'admin/kapan-detail/{id}',
        [ReportController::class, 'kapanDetail']
    )->name('kapan.detail');
    Route::get('admin/sell-report', [ReportController::class, 'sellReport'])
        ->name('sell.report');
    Route::get('admin/sell-report/{id}', [ReportController::class, 'sellDetail'])
        ->name('sell.report.detail');
});

//Clear Cache facade value:
Route::get('/admin/clear-cache', function () {
    Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/admin/optimize', function () {
    Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/admin/route-cache', function () {
    Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/admin/route-clear', function () {
    Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/admin/view-clear', function () {
    Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/admin/config-cache', function () {
    Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});
