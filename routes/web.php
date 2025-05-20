<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;



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
Route::prefix('admin')->middleware('auth','isAdmin')->group(function(){
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin');
    Route::get('/user', [AdminController::class, 'user'])->name('userAdmin');
    Route::get('/{page}', [AdminController::class, 'returnPage'])->name('page');
    Route::post('deleteUser', [AdminController::class, 'deleteUser'])->name('deleteUser');
    Route::post('returnUser', [AdminController::class, 'returnUser'])->name('returnUser');
    Route::post('returnSalesAdmin', [UserController::class, 'returnSales'])->name('returnSalesAdmin');
    Route::post('editUser', [AdminController::class, 'editUser'])->name('editUser');
    Route::post('addUserAdmin', [AdminController::class, 'addUser'])->name('addUserAdmin');

    Route::post('deleteClient', [AdminController::class, 'deleteClient'])->name('deleteClient');
    Route::post('returnClient', [AdminController::class, 'returnClient'])->name('returnClient');
    Route::post('editClient', [AdminController::class, 'editClient'])->name('editClient');

    Route::post('addCategory', [AdminController::class, 'addCategory'])->name('addCategory');
    Route::post('deleteCat', [AdminController::class, 'deleteCat'])->name('deleteCat');
    Route::post('returnCat', [AdminController::class, 'returnCat'])->name('returnCat');
    Route::post('editCat', [AdminController::class, 'editCat'])->name('editCat');

    Route::post('deleteProduct', [AdminController::class, 'deleteProduct'])->name('deleteProductAdmin');
    Route::post('returnProduct', [AdminController::class, 'returnProduct'])->name('returnProductAdmin');
    Route::post('editProduct', [AdminController::class, 'editProduct'])->name('editProductAdmin');

    Route::post('deleteComment', [AdminController::class, 'deleteComment'])->name('deleteComment');
    Route::post('returnComment', [AdminController::class, 'returnComment'])->name('returnComment');
    Route::post('editComment', [AdminController::class, 'editComment'])->name('editComment');

    Route::post('deleteRate', [AdminController::class, 'deleteRate'])->name('deleteRate');
    Route::post('deleteProdRate', [AdminController::class, 'deleteProdRate'])->name('deleteProdRate');

    Route::post('changePasswordUserAdmin', [AdminController::class, 'changePasswordUserAdmin'])->name('changePasswordUserAdmin');
    Route::post('changePasswordClientAdmin', [AdminController::class, 'changePasswordClientAdmin'])->name('changePasswordClientAdmin');

    Route::post('userReport', [AdminController::class, 'userReport'])->name('userReport');
    Route::post('clientReport', [AdminController::class, 'clientReport'])->name('clientReport');

    Route::post('changeStatus', [AdminController::class, 'changeStatus'])->name('changeStatus');
    Route::post('returnProducts', [AdminController::class, 'returnProducts'])->name('returnProducts');
    Route::post('delivered', [AdminController::class, 'delivered'])->name('delivered');

    Route::post('remind', [AdminController::class, 'remind'])->name('remind');

});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('product/{id}', [HomeController::class, 'returnProduct'])->name('returnProduct');
Route::get('userprofile/{id}', [HomeController::class, 'userprofile'])->name('userprofile');
Route::post('reportUser', [HomeController::class, 'reportUser'])->name('reportUser');
Route::post('blockUser', [HomeController::class, 'blockUser'])->name('blockUser');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::post('contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/explorevideos', [HomeController::class, 'explorevideos'])->name('explorevideos');


Route::get('/login', [CustomAuthController::class, 'index'])->name('login');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');
Route::get('registration', [CustomAuthController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');
Route::get('/verifiy/{token}', [CustomAuthController::class, 'verify'])->name('verify');

Route::post('clientRegistration', [ClientController::class, 'signup'])->name('signup');
Route::get('/verification/{token}', [ClientController::class, 'verifyClient'])->name('verifyClient');
Route::post('custom-client-login', [ClientController::class, 'customClientLogin'])->name('login.client');
Route::get('/logout', [ClientController::class, 'logOut'])->name('logout');
Route::post('placeorder', [ClientController::class, 'placeorder'])->name('placeorder');
Route::post('rateUser', [ClientController::class, 'rateUser'])->name('rateUser');
Route::post('rateProduct', [ClientController::class, 'rateProduct'])->name('rateProduct');
Route::get('/deleteOrder', [ClientController::class, 'deleteOrder'])->name('deleteOrder');
Route::get('/addcomment', [ClientController::class, 'addcomment'])->name('addcomment');
Route::post('deletecomment', [ClientController::class, 'deleteComment'])->name('deletecomment');
Route::post('sendMessage', [ClientController::class, 'sendMessage'])->name('sendMessage');
Route::post('addFav', [ClientController::class, 'addFav'])->name('addFav');
Route::post('returnCategoryToHome', [HomeController::class, 'returnCategoryToHome'])->name('returnCategoryToHome');
Route::post('changeClientPassword', [ClientController::class, 'changePassword'])->name('changeClientPassword');
Route::post('updateClient', [ClientController::class, 'updateClient'])->name('updateClient');
Route::get('/cart', [ClientController::class, 'cart'])->name('cart');
Route::post('/addToCart', [ClientController::class, 'addToCart'])->name('addToCart');
Route::post('/ToCart', [ClientController::class, 'ToCart'])->name('ToCart');
Route::post('/removeFromCart', [ClientController::class, 'removeFromCart'])->name('removeFromCart');
Route::post('increase', [ClientController::class, 'increase'])->name('increase');
Route::post('decrease', [ClientController::class, 'decrease'])->name('decrease');
Route::post('orderCart', [ClientController::class, 'orderCart'])->name('orderCart');
Route::post('sendEmail', [ClientController::class, 'sendEmail'])->name('sendEmail');
Route::post('subscribe', [ClientController::class, 'subscribe'])->name('subscribe');
Route::post('unsubscribe', [ClientController::class, 'unsubscribe'])->name('unsubscribe');
Route::post('processPayment', [PaymentController::class, 'processPayment'])->name('processPayment');



Auth::routes();
Route::get('/user', [UserController::class, 'index'])->middleware('auth')->name('user');
Route::post('addproduct', [UserController::class, 'addproduct'])->name('addproduct');
Route::post('addvideo', [UserController::class, 'addvideo'])->name('addvideo');
Route::get('/returnProduct', [UserController::class, 'returnProduct'])->name('returnProduct');
Route::post('updateProduct', [UserController::class, 'updateProduct'])->name('updateProduct');
Route::get('/deleteProduct', [UserController::class, 'deleteProduct'])->name('deleteProduct');
Route::get('Comments/{id}', [UserController::class, 'Comment'])->name('Comment');
Route::post('removeComment', [UserController::class, 'removeComment'])->name('removeComment');
Route::post('confirmOrder', [UserController::class, 'confirmOrder'])->name('confirmOrder');
Route::post('ready', [UserController::class, 'ready'])->name('ready');
Route::post('cancelOrder', [UserController::class, 'cancelOrder'])->name('cancelOrder');
Route::get('/allOrders', [UserController::class, 'allOrders'])->name('allOrders');
Route::post('updateUser', [CustomAuthController::class, 'updateUser'])->name('updateUser');
Route::post('/changeUserPassword', [CustomAuthController::class, 'changePassword'])->name('changeUserPassword');
Route::post('reportClient', [UserController::class, 'reportClient'])->name('reportClient');
Route::get('blockClient', [UserController::class, 'blockClient'])->name('blockClient');
Route::post('unblockClient', [UserController::class, 'unblockClient'])->name('unblockClient');
Route::post('returnSales', [UserController::class, 'returnSales'])->name('returnSales');


Route::get('/delivery/profile', [DeliveryController::class, 'deliveryprofile'])->name('deliveryprofile');
Route::get('/delivery/login', [DeliveryController::class, 'index'])->name('deliverylogin');
Route::post('deliveryLogin', [DeliveryController::class, 'customDeliveryLogin'])->name('deliveryLogin');
Route::post('deliveryResponse', [DeliveryController::class, 'deliveryResponse'])->name('deliveryResponse');
