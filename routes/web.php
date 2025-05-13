<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Order;
use App\Models\User;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Livewire\QcSheet;
use App\Livewire\TaskReport;
use NunoMaduro\Collision\Writer;
use App\Models\Multipleswiter;
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

    if (Auth::user()->role_id == 3) {
        $data['TeamMemberData'] = User::where('role_id', '3')->select([
            'name', 'photo'
        ])->get();
        // echo '<pre>' ; print_r($data['TeamMemberData']) ; exit;
    }elseif (Auth::user()->role_id == 5) {
        $data['TeamMemberData'] = User::where('role_id', '5')->select([
            'name', 'photo'
        ])->get();
        // echo '<pre>' ; print_r($data['TeamMemberData']) ; exit;
    }elseif (Auth::user()->role_id == 6) {
        $user_id = Auth::user()->id;

        $data['TotalOrdersTl']      = Order::where('wid', $user_id)->count();
        $data['InprogressOrderTl']  = Order::where('wid', $user_id)->where('writer_status', 'In Progress')->count();
        $data['NotAssignOrderTl']   =  Order::where('wid', $user_id)->where(function($query)
        {
            $query
            ->whereNull('writer_status')
            ->orWhere('writer_status', 'Not Assigned')
            ->orWhere('writer_status', '');
            
        })->count();
        
    }elseif (Auth::user()->role_id == 7) {
        # code...Writer
        $user_id = Auth::user()->id;

        $multipleWriters = Multipleswiter::where('user_id', $user_id)->get();            
        $orderIds = $multipleWriters->pluck('order_id')->toArray();            
        $data['TotalOrdersWriter'] = Order::whereIn('id', $orderIds)->count();   
        $data['InprogressOrderWriter']  = Order::whereIn('id', $orderIds)->where('writer_status', 'In Progress')->count();
        $data['NotAssignOrderWriter']   = Order::whereIn('id', $orderIds)->where(function($query)
        {
            $query
            ->whereNull('writer_status')
            ->orWhere('writer_status', 'Not Assigned')
            ->orWhere('writer_status', '');
            
        })->count();
    }
    

    return view('dashboard', compact('data'));
    
})->middleware(['auth', 'verified', 'role'])->name('dashboard');



Route::middleware(['auth', 'verified', 'role'])->group(function () 
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
    Route::get('/search-writer-order', [UserController::class, 'searchWriterOrder'])->name('search.writer-order');
    Route::get('/order/paginate-data', [OrderController::class, 'pagination']);
    Route::get('/order/{id}', [OrderController::class, 'edit']);
    Route::get('/order-form/{id}', [OrderController::class, 'editform']);
    Route::put('/order/{id}', [OrderController::class, 'update'])->name('order.update');


    // livewire
    //Qc
    Route::get('/Qc-Sheets', [OrderController::class, 'Qc'])->name('Qc-Sheets');
    // order
    Route::get('/order' , [OrderController::class, 'order'])->name('order');


    // Writer-available
    Route::get('/writer-available', [OrderController::class , 'writerAvailablity'])->name('writer-available');

    // ticket number

    route::get('/ticket-sheet', [OrderController::class ,  'tickerSheet'])->name('ticket-sheet');

    
});
Route::get('/task-reports', function () {
    return view('task-reports.index');
})->name('task-reports.index');
Route::get('/task-review', function () {
    return view('task-reports.task-review');
})->name('task-review.index');
Route::get('/admin-task-report', function () {
    return view('task-reports.admin-task-report');
})->name('admin.task.report');

require __DIR__.'/auth.php';