<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryProducts;
use App\Http\Controllers\BranchProduct;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\DeliveryController;

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

//Frontend
Route::get('/', [HomeController::class, 'index']);
Route::get('/trang-chu', [HomeController::class, 'index']);
Route::post('/tim-kiem', [HomeController::class, 'search']);

// Category, Brand homepage
Route::get('/danh-muc-san-pham/{category_id}', [CategoryProducts::class, 'category_by_id']);
Route::get('/thuong-hieu-san-pham/{brand_id}', [BranchProduct::class, 'brand_by_id']);
 // Product Detail
Route::get('/chi-tiet-san-pham/{product_id}', [ProductController::class, 'detail_product']);

//Backend
Route::get('/admin', [AdminController::class, 'index']);
Route::get('/dashboard', [AdminController::class, 'admin_layout']);
Route::get('/logout', [AdminController::class, 'logout']);

Route::post('/admin_dashboard', [AdminController::class, 'dashboard']);

// Category Product
Route::get('/add-category-product', [CategoryProducts::class, 'add_category_product']);
Route::get('/all-category-product', [CategoryProducts::class, 'all_category_product']);
Route::get('/edit-category-product/{categoryProduct_id}', [CategoryProducts::class, 'edit_category_product']);
Route::get('/delete-category-product/{categoryProduct_id}', [CategoryProducts::class, 'delete_category_product']);

Route::get('/unactive-category/{categoryProduct_id}', [CategoryProducts::class, 'unactive_category_product']);
Route::get('/active-category/{categoryProduct_id}', [CategoryProducts::class, 'active_category_product']);

Route::post('/save-category-product', [CategoryProducts::class, 'save_category_product']);
Route::post('/update-category-product/{categoryProduct_id}', [CategoryProducts::class, 'update_category_product']);

// Branch Product
Route::get('/add-branch-product', [BranchProduct::class, 'add_branch_product']);
Route::get('/all-branch-product', [BranchProduct::class, 'all_branch_product']);
Route::get('/edit-branch-product/{branchProduct_id}', [BranchProduct::class, 'edit_branch_product']);
Route::get('/delete-branch-product/{branchProduct_id}', [BranchProduct::class, 'delete_branch_product']);

Route::get('/unactive-branch/{branchProduct_id}', [BranchProduct::class, 'unactive_branch_product']);
Route::get('/active-branch/{branchProduct_id}', [BranchProduct::class, 'active_branch_product']);

Route::post('/save-branch-product', [BranchProduct::class, 'save_branch_product']);
Route::post('/update-branch-product/{branchProduct_id}', [BranchProduct::class, 'update_branch_product']);

// Product
Route::get('/add-product', [ProductController::class, 'add_product']);
Route::get('/all-product', [ProductController::class, 'all_product']);
Route::get('/edit-product/{product_id}', [ProductController::class, 'edit_product']);
Route::get('/delete-product/{product_id}', [ProductController::class, 'delete_product']);

Route::get('/unactive-product/{product_id}', [ProductController::class, 'unactive_product']);
Route::get('/active-product/{product_id}', [ProductController::class, 'active_product']);

Route::post('/save-product', [ProductController::class, 'save_product']);
Route::post('/update-product/{product_id}', [ProductController::class, 'update_product']);

// Cart
Route::post('/save-cart', [CartController::class, 'save_cart']);
Route::get('/view-cart', [CartController::class, 'view_cart']);
Route::get('/gio-hang', [CartController::class, 'gio_hang']);
Route::get('/del-cart/{session_id}', [CartController::class, 'del_cart']);

Route::get('/delete-to-cart/{rowId}', [CartController::class, 'delete_row_cart']);
Route::get('/delete-cart', [CartController::class, 'delete_cart']);
Route::get('/clear-all-cart', [CartController::class, 'clear_all_cart']);

Route::post('/add-cart-ajax', [CartController::class, 'add_cart_ajax']);
Route::post('/update-cart', [CartController::class, 'update_cart']);

Route::post('/update-view-cart', [CartController::class, 'update_cart_quanlity']);

// Coupon
Route::get('/unset-coupon', [CouponController::class, 'unset_coupon']);
Route::get('/add-coupon', [CouponController::class, 'add_coupon']);
Route::get('/delete-coupon/{coupon_id}', [CouponController::class, 'delete_coupon']);
Route::get('/all-coupon', [CouponController::class, 'all_coupon']);

Route::post('/check-coupon', [CartController::class, 'check_coupon']);
Route::post('/save-coupon', [CouponController::class, 'save_coupon']);

// Apply/Remove coupon from checkout page
Route::post('/apply-coupon-code', [CartController::class, 'apply_coupon_code']);
Route::post('/remove-coupon-code', [CartController::class, 'remove_coupon_code']);

// Login Checkout
Route::get('/delete-fee-home', [CheckoutController::class, 'delete_fee_home']);
Route::get('/checkout', [CheckoutController::class, 'checkout']);
Route::get('/login-checkout', [CheckoutController::class, 'login_checkout']);
Route::get('/logout-checkout', [CheckoutController::class, 'logout_checkout']);
Route::post('/add-customer', [CheckoutController::class, 'add_customer']);
Route::post('/login', [CheckoutController::class, 'login_customer']);
Route::post('/save-checkout-customer', [CheckoutController::class, 'save_checkout_customer']);
Route::post('/calculate-fee', [CheckoutController::class, 'calculate_fee']);
Route::get('/get-current-fee', [CheckoutController::class, 'getCurrentFee']);
Route::post('/get-delivery-home', [CheckoutController::class, 'get_delivery_home']);
Route::post('/confirm-order', [CheckoutController::class, 'confirmOrder']);

// 🎯 Discount Strategy Routes
Route::post('/check-discounts', [CheckoutController::class, 'checkAvailableDiscounts']);
Route::post('/api/check-available-discounts', [CheckoutController::class, 'checkAvailableDiscounts']);

// 🎫 Coupon Code Routes
Route::post('/apply-coupon-code', [CouponController::class, 'applyCouponCode']);
Route::post('/remove-coupon-code', [CouponController::class, 'removeCouponCode']);

Route::get('/order-success', function() {
    return view('pages.checkout.order_success');
})->name('order-success');

// Order
Route::post('/save-order', [CheckoutController::class, 'save_order']);
Route::get('/manage-order', [CheckoutController::class, 'manage_order']);

Route::get('/view-order-detail/{order_id}', [CheckoutController::class, 'view_order_detail']);
Route::get('/delete-order/{order_id}', [CheckoutController::class, 'delete_order']);

// send mail
Route::get('/contact', [HomeController::class, 'contact']);
Route::post('/send-mail', [HomeController::class, 'send_mail']);

// login facebook
Route::get('/login-fb', [AdminController::class, 'login_facebook']);
Route::get('/admin/callback', [AdminController::class, 'callback_facebook']);

// login google
Route::get('/login-google', [AdminController::class, 'login_google']);
Route::get('/google/callback', [AdminController::class, 'callback_google']);

// Delivery
Route::get('/delivery', [DeliveryController::class, 'delivery']);
Route::post('/get-delivery', [DeliveryController::class, 'get_delivery']);
Route::post('/add-feeship', [DeliveryController::class, 'add_feeship']);
Route::post('/fetch-feeship', [DeliveryController::class, 'fetch_feeship']);
Route::post('/update-feeship', [DeliveryController::class, 'update_feeship']);

// Payment Pages Routes
Route::get('/payment/cod', [CheckoutController::class, 'showPaymentCOD'])->name('payment-cod');
Route::get('/payment/credit', [CheckoutController::class, 'showPaymentCredit'])->name('payment-credit');
Route::get('/payment/success', [CheckoutController::class, 'showPaymentSuccess'])->name('payment-success');

// Payment Processing Routes
Route::post('/payment/credit/process', [CheckoutController::class, 'processCreditPayment'])->name('process-credit-payment');

Route::get('/test/watch-factory', function () {
    return view('test.watch_factory_test');
});