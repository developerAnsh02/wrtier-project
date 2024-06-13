<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Order;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;


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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    $user_id = Auth::user()->id;

    $data['TotalOrders']      = Order::where('admin_id', $user_id)->count();
    $data['InprogressOrder']  = Order::where('admin_id', $user_id)->where('writer_status', 'In Progress')->count();
    $data['NotAssignOrder']   =  Order::where('admin_id', $user_id)->where(function($query)
    {
        $query
        ->whereNull('writer_status')
        ->orWhere('writer_status', 'Not Assigned')
        ->orWhere('writer_status', '');
        
    })->count();
    
    
    $data['Tl'] = User::with(['writer' => function($query) {
        $query->where('flag', '0'); 
    }])
    ->where('role_id', 6)
    ->where('flag', '0')
    ->paginate(8);

    

    return view('dashboard', compact('data'));
    
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () 
{
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit') ;
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Writer Tl For all route  for admin 
    Route::get('/writerTL',    [UserController::class, 'WriterTl'])->name('writerTL') ;
    route::post('/add-tl' , [UserController::class , 'InsertNewWriterTl'])->name('add.tl');
    Route::get('/writerTL/{id}', [UserController::class, 'updateWriterTL'])->name('update');
    Route::put('/writerTL/{id}', [UserController::class, 'UpdateWT'])->name('writerTL.update');
    Route::delete('/writerTL/{id}', [UserController::class, 'deactiveTL'])->name('writerTL.delete');
    Route::get('/pagination/paginate-data', [UserController::class, 'paginationTL']);

    // subwriter Route for admin 
    Route::get('/subwriter' , [UserController::class, 'Subwriter'])->name('subwriter');
    Route::get('/search-writer', [UserController::class, 'searchWriter'])->name('search.writer');
    route::post('/add-writer' , [UserController::class , 'InsertNewWriter'])->name('add.writer');
    Route::get('writer.{id}', [UserController::class, 'updateWriter'])->name('Writerupdate');
    Route::put('/writer/{id}', [UserController::class, 'UpdatesubWriter'])->name('writer.update');
    Route::delete('/writer/{id}', [UserController::class, 'deactivewriter'])->name('writer.delete');
    

    // Order for admin 
    Route::get('/order' , [OrderController::class, 'order'])->name('order');
    Route::get('/search-writer-order', [UserController::class, 'searchWriterOrder'])->name('search.writer-order');
    Route::get('/order/paginate-data', [OrderController::class, 'pagination']);
    Route::put('/order/{id}' , [OrderController::class, 'update_order'])->name('order.update');
    Route::get('/order/{id}', [OrderController::class, 'edit']);





});

require __DIR__.'/auth.php';