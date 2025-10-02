<?php

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\SetPasswordController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\DiscountClaimController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\StoreSettingsController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\LaporanPenjualanController;
use App\Http\Controllers\MetodePembayaranController;
use App\Http\Controllers\RiwayatTransaksiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [BerandaController::class, 'index'])->middleware('visitor.logger');

// seting pihak ketiga google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// halaman set password (hanya untuk user yang sudah login)
Route::middleware('auth')->group(function () {
    Route::get('/set-password', [SetPasswordController::class, 'show'])->name('password.set.page');
    Route::post('/set-password', [SetPasswordController::class, 'update'])->name('password.set.update');
    Route::post('/set-password/skip', [SetPasswordController::class, 'skip'])->name('password.set.skip');
});

// seting lupa password dan reset
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');


// Authentication
Route::middleware(['guest'])->group(function () {
    Route::get('/Masuk', [AuthController::class, 'showLoginForm'])->name('Masuk');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/Daftar', [AuthController::class, 'showRegisterForm'])->name('Daftar');
    Route::post('/register', [AuthController::class, 'register']);
});

// menu navbar setelah masuk dan daftar hilang (Beranda)
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profil', [ProfileController::class, 'indexprofillayout'])->name('profil');
    Route::put('/profil', [ProfileController::class, 'updateprofillayout'])->name('profilupdate');
     // alamat PROFIL
    Route::post('/address', [ProfileController::class, 'storeAddress'])->name('address.store');
    Route::put('/address/{id}', [ProfileController::class, 'updateAddress'])->name('address.update');
    Route::delete('/address/{id}', [ProfileController::class, 'destroyAddress'])->name('address.destroy');
    Route::get('/orders/{id}/detail', [ProfileController::class, 'orderDetail'])->name('profile.orders.detail');
    Route::post('/orders/cancel', [ProfileController::class, 'cancelOrder'])->name('profile.orders.cancel');
    Route::post('/orders/confirm', [ProfileController::class, 'confirmOrder'])->name('profile.orders.confirm');
});

// view-menu-layout
Route::delete('/keranjang/{carts}', [CartController::class, 'destroydinavbar'])->name('cart.destroydinavbar');
Route::get('/keranjang', [CartController::class, 'indexlayout']);
Route::post('/keranjang/delete-selected', [CartController::class, 'deleteSelected'])->name('cart.deleteSelected');
Route::post('/keranjang/update-qty', [CartController::class, 'updateQty'])->name('cart.updateQty');
Route::post('/keranjang/add', [CartController::class, 'store'])->name('cart.store');
Route::post('/keranjang/checkout-selected', [CartController::class, 'checkoutSelected'])->name('cart.checkoutSelected');
Route::post('/keranjang/checkout-single', [CartController::class, 'checkoutSingle'])->name('cart.checkoutSingle');
Route::get('/produk-favorit', [WishlistController::class, 'indexlayout']);
Route::delete('/produk-favorit/{wishlist}', [WishlistController::class, 'destroydinavbar'])->name('wishlist.destroydinavbar');
Route::post('/wishlist/add-to-cart', [WishlistController::class, 'addToCart'])->name('wishlist.addToCart');
Route::post('/wishlist/delete-selected', [WishlistController::class, 'deleteSelected'])->name('wishlist.deleteSelected');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::post('/shop/toggle-wishlist', [ShopController::class, 'toggleWishlist'])->name('shop.toggleWishlist');
Route::post('/shop/add-to-cart', [ShopController::class, 'addToCart'])->name('shop.addToCart');
Route::post('/shop/check-stock', [ShopController::class, 'checkStock'])->name('shop.checkStock');
Route::get('/detailproduk/{identifier}', [ProdukController::class, 'showdetail'])->name('produk.showdetail');
Route::post('/detailproduk/{identifier}/review', [ProdukController::class, 'storeReview'])->name('produk.review');
Route::post('/cart/checkout-session', [CartController::class, 'checkoutSession'])->name('cart.checkoutSession');
Route::post('/user/addresses', [UserAddressController::class, 'store'])->name('user.addresses.store');
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/checkout/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
    Route::post('/checkout/upload-proof', [CheckoutController::class, 'uploadProof'])->name('checkout.upload_proof');
    Route::post('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
});
Route::post('/checkout/buy-now', [ProdukController::class, 'buyNow'])->name('product.buyNow');
Route::view('/tentang-kami', 'view-menu-layout.tentang-kami')->name('tentang');
Route::view('/cara-belanja','view-menu-layout.cara-belanja')->name('belanja');
Route::view('/kebijakan-privasi', 'view-menu-layout.kebijakan-privasi')->name('privacy');
Route::view('/syarat-ketentuan', 'view-menu-layout.s&k')->name('syarat');
Route::view('/cara-pembayaran', 'view-menu-layout.cara-pembayaran')->name('cara-pembayaran');
Route::view('/metode-pengiriman', 'view-menu-layout.cara-pengiriman')->name('metode-pengiriman');
Route::view('/cara-pengembalian', 'view-menu-layout.cara-pengembalian')->name('pengembalian');
Route::view('/pusat-bantuan', 'view-menu-layout.cara-bantuan')->name('bantuan');
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::resource('user-role', UserController::class);
});
Route::prefix('voucher')->group(function () {
    Route::post('/check', [DiscountController::class, 'check'])->name('voucher.check');
    Route::post('/claim', [DiscountController::class, 'claim'])->name('voucher.claim');
    Route::post('/use', [DiscountController::class, 'use'])->name('voucher.use');
    Route::post('/apply', [DiscountController::class, 'apply'])->name('voucher.apply');
});
// Route::get('/tes-error-server', function () {
//     // Baris kode di bawah akan memicu error karena kita mencoba memanggil properti dari null
//     $data = null;
//     return $data->nama;
// });
Route::middleware(['auth', 'role:admin|superadmin|client'])->group(function () {
    Route::get('/price-list', [ProdukController::class, 'priceList'])->name('price-list');
    Route::get('/price-list/export', [ProdukController::class, 'exportPriceList'])
    ->name('price-list.export');
});

// sidebar admin/superadmin ( view hanya untuk admin dan superadmin dan juga staff )
Route::middleware(['auth', 'role:admin|superadmin','can:akses-dashboard-admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('produk', ProdukController::class);
    Route::resource('discounts', DiscountController::class);
    Route::delete('/produk/image/{id}', [ProdukController::class, 'deleteImage'])->name('produk.deleteImage');
    Route::resource('brand', BrandController::class);
    Route::resource('kategori', KategoriController::class);
    Route::resource('kurir', KurirController::class);
    Route::resource('pelanggan', CustomerController::class);
    Route::resource('ulasan', ReviewController::class);
    Route::post('/ulasan/status/{id}', [ReviewController::class, 'updateStatus'])->name('reviews.updateStatus');
    Route::get('/stok/export', [ProductStockController::class, 'export'])->name('stok.export');
    Route::post('/stok/import', [ProductStockController::class, 'import'])->name('stok.import');
    Route::get('/stok', [ProductStockController::class, 'index'])->name('stok.index');
    Route::post('/stok', [ProductStockController::class, 'store'])->name('stok.store');
    Route::put('/stok/{stok}', [ProductStockController::class, 'update'])->name('stok.update');
    Route::delete('/stok/{stok}', [ProductStockController::class, 'destroy'])->name('stok.destroy');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::get('whislist', [WishlistController::class, 'index'])->name('whislist.index');
    Route::delete('whislist/{wishlist}', [WishlistController::class, 'destroy'])->name('whislist.destroy');
    Route::get('/pengaturan-toko', [StoreSettingsController::class, 'index'])->name('store_settings.index');
    Route::post('/pengaturan-toko/info', [StoreSettingsController::class, 'updateInfo'])->name('store_settings.updateInfo');
    Route::post('/pengaturan-toko/banner', [StoreSettingsController::class, 'addBanner'])->name('store_settings.addBanner');
    Route::delete('/pengaturan-toko/banner/{id}', [StoreSettingsController::class, 'deleteBanner'])->name('store_settings.deleteBanner');
    Route::post('/store_settings/banner/{id}/toggle', [StoreSettingsController::class, 'toggleBanner'])->name('store_settings.toggleBanner');
    Route::put('/store_settings/banner/{id}', [StoreSettingsController::class, 'updateBanner'])->name('store_settings.updateBanner');
    Route::get('/analitik', [DashboardController::class, 'analitik'])->middleware('visitor.logger');
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/pesanan', [OrderController::class, 'index'])->name('admin.pesanan.index');
    Route::put('/pesanan/{id}', [OrderController::class, 'update'])->name('admin.pesanan.update');
    Route::get('/pesanan/{id}/invoice', [OrderController::class, 'invoice'])->name('admin.pesanan.invoice');
    Route::get('/admin/orders/invoice/{id}', [OrderController::class, 'invoice'])
    ->name('orders.invoice');
    Route::delete('/pesanan/{id}', [OrderController::class, 'destroy'])->name('admin.pesanan.destroy');
    Route::get('/pesanan/detail/{kode_pesanan}', [OrderController::class, 'detail'])->name('admin.orders.detail');
    Route::resource('kurir', KurirController::class);
    Route::resource('metode-pembayaran', MetodePembayaranController::class);
    Route::get('riwayat-transaksi', [RiwayatTransaksiController::class,'index'])->name('riwayat-transaksi.index');
    Route::get('/laporan', [LaporanPenjualanController::class, 'index'])->name('laporan.penjualan');
    Route::get('/laporan/penjualan/export', [LaporanPenjualanController::class, 'export'])->name('laporan.penjualan.export');
    Route::get('/profile-sidebar', [DashboardController::class, 'updateprofile'])->name('profile.update');
});
