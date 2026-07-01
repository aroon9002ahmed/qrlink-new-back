<?php

use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Auth\SocialLoginController;
use App\Http\Controllers\Api\Auth\VerifyEmailController;
use App\Http\Controllers\Api\BannersController;
use App\Http\Controllers\Api\BranchesController;
use App\Http\Controllers\Api\CodeController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Http\Controllers\Api\FaqsController;
use App\Http\Controllers\Api\LinksController;
use App\Http\Controllers\Api\NotificationsController;
use App\Http\Controllers\Api\PagesController;
use App\Http\Controllers\Api\PageTypesController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\QrcodesController;
use App\Http\Controllers\Api\RestaurantMenuController;
use App\Http\Controllers\Api\RestaurantOrdersController;
use App\Http\Controllers\Api\RestaurantTablesController;
use App\Http\Controllers\Api\SubscriptionPlansController;
use App\Http\Controllers\Api\TemplatesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    // Guest
    Route::post('login', LoginController::class)->name('api.auth.login');
    Route::post('register', RegisterController::class)->name('api.auth.register');
    Route::post('social-login', [SocialLoginController::class, 'socialLogin'])->name('api.auth.social-login');
    Route::post('forgot-password', ForgotPasswordController::class)->name('api.auth.forgot-password');
    Route::post('reset-password', ResetPasswordController::class)->name('api.auth.reset-password');

    // Password Reset (GET link from email)
    Route::get('reset-password/{token}', function (Request $request, $token) {
        return response()->json([
            'status' => true,
            'message' => 'Reset password token retrieved.',
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    })->name('password.reset');

    // Email Verification (signed link)
    Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Authenticated
    Route::middleware('auth:sanctum')->group(function () {
        Route::delete('logout', LogoutController::class)->name('api.auth.logout');

        // Resend Verification Email
        Route::post('/email/verification-notification', [VerifyEmailController::class, 'resend'])
            ->middleware('throttle:6,1')
            ->name('verification.send');
    });
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
// Unified public route for resolving any short code (Link or QR Code)
Route::get('/r/{code}', [CodeController::class, 'resolve'])->name('api.resolve');

Route::post('/codes/report', [CodeController::class, 'report'])->middleware('throttle:5,1')->name('api.codes.report');

// Subscription Plans
Route::get('/subscription-plans', [SubscriptionPlansController::class, 'index'])->name('api.subscription-plans.index');
Route::get('/subscription-plans/{id}', [SubscriptionPlansController::class, 'show'])->name('api.subscription-plans.show');

// FAQs
Route::get('/faqs', [FaqsController::class, 'index'])->name('api.faqs.index');

// Configurations
Route::get('/configurations/{slug?}', [ConfigurationController::class, 'index'])->name('api.configurations.index');

// Pages
Route::get('/p/{slug}', [PagesController::class, 'showPublic'])->name('api.pages.showPublic');
Route::post('/p/{slug}/orders', [RestaurantOrdersController::class, 'storePublic'])->name('api.pages.storePublicOrder');

// Templates (Public)
Route::get('/templates/{id}', [TemplatesController::class, 'show'])->name('api.templates.show');

// Page Types (Public)
Route::get('/page-types', [PageTypesController::class, 'index'])->name('api.page-types.index');
Route::get('/page-types/{id}', [PageTypesController::class, 'show'])->name('api.page-types.show');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // Current authenticated user
    Route::get('/user', [ProfileController::class, 'index'])->name('api.user');
    Route::put('/user/profile', [ProfileController::class, 'updateProfile'])->name('api.user.profile');
    Route::put('/user/password', [ProfileController::class, 'updatePassword'])->name('api.user.password');

    // Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationsController::class, 'index'])->name('api.notifications.index');
        Route::post('/{id}/read', [NotificationsController::class, 'markAsRead'])->name('api.notifications.read');
        Route::post('/read-all', [NotificationsController::class, 'markAllAsRead'])->name('api.notifications.read-all');
        Route::delete('/{id}', [NotificationsController::class, 'destroy'])->name('api.notifications.destroy');
        Route::delete('/', [NotificationsController::class, 'clearAll'])->name('api.notifications.clear-all');
    });

    // Pages
    Route::prefix('pages')->group(function () {
        Route::get('/', [PagesController::class, 'index'])->name('api.pages.index');
        Route::get('/{page}', [PagesController::class, 'show'])->name('api.pages.show');
        Route::patch('/{page}', [PagesController::class, 'update'])->name('api.pages.update');
        Route::put('/{page}/status', [PagesController::class, 'updateStatus'])->name('api.pages.updateStatus');
    });

    // Social Platforms & Links
    Route::get('/social-platforms', [PagesController::class, 'socialPlatforms'])->name('api.social-platforms');
    Route::post('/pages/{page}/social-links', [PagesController::class, 'updateSocialLinks'])->name('api.pages.updateSocialLinks');

    // Banners
    Route::prefix('pages/{page}/banners')->group(function () {
        Route::get('/', [BannersController::class, 'index'])->name('api.banners.index');
        Route::post('/', [BannersController::class, 'store'])->name('api.banners.store');
        Route::post('/reorder', [BannersController::class, 'reorder'])->name('api.banners.reorder');
        Route::match(['post', 'put'], '/{banner}', [BannersController::class, 'update'])->name('api.banners.update');
        Route::delete('/{banner}', [BannersController::class, 'destroy'])->name('api.banners.destroy');
    });

    // Branches
    Route::prefix('pages/{page}/branches')->group(function () {
        Route::get('/', [BranchesController::class, 'index'])->name('api.branches.index');
        Route::post('/', [BranchesController::class, 'store'])->name('api.branches.store');
        Route::match(['post', 'put'], '/{branch}', [BranchesController::class, 'update'])->name('api.branches.update');
        Route::delete('/{branch}', [BranchesController::class, 'destroy'])->name('api.branches.destroy');
    });

    // Tables
    Route::prefix('pages/{page}/tables')->group(function () {
        Route::get('/', [RestaurantTablesController::class, 'index'])->name('api.tables.index');
        Route::post('/', [RestaurantTablesController::class, 'store'])->name('api.tables.store');
        Route::match(['post', 'put'], '/{table}', [RestaurantTablesController::class, 'update'])->name('api.tables.update');
        Route::delete('/{table}', [RestaurantTablesController::class, 'destroy'])->name('api.tables.destroy');
    });

    // Restaurant Menu Categories & Items
    Route::prefix('pages/{page}/categories')->group(function () {
        Route::get('/', [RestaurantMenuController::class, 'indexCategories']);
        Route::post('/', [RestaurantMenuController::class, 'storeCategory']);
        Route::post('/reorder', [RestaurantMenuController::class, 'reorderCategories']);
        Route::put('/{category}', [RestaurantMenuController::class, 'updateCategory']);
        Route::delete('/{category}', [RestaurantMenuController::class, 'destroyCategory']);
    });

    Route::prefix('pages/{page}/items')->group(function () {
        Route::post('/', [RestaurantMenuController::class, 'storeItem']);
        Route::post('/reorder', [RestaurantMenuController::class, 'reorderItems']);
        Route::match(['post', 'put'], '/{item}', [RestaurantMenuController::class, 'updateItem']);
        Route::delete('/{item}', [RestaurantMenuController::class, 'destroyItem']);
        Route::post('/{item}/move', [RestaurantMenuController::class, 'moveItem']);
    });

    // Restaurant Orders
    Route::prefix('pages/{page}/orders')->group(function () {
        Route::get('/', [RestaurantOrdersController::class, 'index']);
        Route::post('/handover-shift', [RestaurantOrdersController::class, 'handoverShift']);
        Route::post('/close-day', [RestaurantOrdersController::class, 'closeDay']);
        Route::get('/{order}', [RestaurantOrdersController::class, 'show']);
        Route::put('/{order}/status', [RestaurantOrdersController::class, 'updateStatus']);
        Route::put('/{order}', [RestaurantOrdersController::class, 'update']);
    });

    // Links
    Route::prefix('links')->group(function () {
        Route::get('/', [LinksController::class, 'index'])->name('api.links.index');
        Route::get('/{link}', [LinksController::class, 'show'])->name('api.links.show');
        Route::post('/', [LinksController::class, 'store'])->name('api.links.store');
        Route::put('/{link}', [LinksController::class, 'update'])->name('api.links.update');
        Route::delete('/{link}', [LinksController::class, 'destroy'])->name('api.links.destroy');
    });

    // QrCodes
    Route::prefix('qrcodes')->group(function () {
        Route::get('/', [QrcodesController::class, 'index'])->name('api.qrcodes.index');
        Route::get('/{qrcode}', [QrcodesController::class, 'show'])->name('api.qrcodes.show');
        Route::post('/', [QrcodesController::class, 'store'])->name('api.qrcodes.store');
        Route::put('/{qrcode}', [QrcodesController::class, 'update'])->name('api.qrcodes.update');
        Route::delete('/{qrcode}', [QrcodesController::class, 'destroy'])->name('api.qrcodes.destroy');
    });

    // Templates (Protected)
    Route::prefix('templates')->group(function () {
        Route::get('/', [TemplatesController::class, 'index'])->name('api.templates.index');
        Route::get('/page-type/{page_type_id}', [TemplatesController::class, 'byPageType'])->name('api.templates.byPageType');
    });
});
