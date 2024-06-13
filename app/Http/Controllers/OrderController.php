<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;



class OrderController extends Controller
{
    public function index()
    {
    }

    public function order()
    {
        $data['order'] = Order::with(['writer:id,name', 'subwriter:id,name','mulsubwriter' => function ($query) {$query->with('user:id,name');}])
        ->where('admin_id', auth()->user()->id)->orderBy('id', 'desc')
        ->select([
                    'id', 'order_id', 'services', 'typeofpaper', 'pages', 'title',
                    'writer_deadline', 'chapter', 'wid', 'swid', 'writer_status',
                    'writer_fd', 'writer_ud', 'writer_ud_h', 'writer_fd_h',
                    'resit', 'tech'
                ])
        ->paginate(10);

        $data['tl'] = User::where('role_id', 6)->where('flag', 0)->where('admin_id' , auth()->user()->id)->orderBy('created_at', 'desc')->get(['id', 'name']);
        $data['writer'] = User::where('flag', 0)->where('role_id' , 7)->get(['id' , 'name' , 'admin_id']);

        return view('order.order-admin', compact('data'));
    }


    public function pagination(Request $request)
    {

        $data['order'] = Order::with(['writer:id,name', 'subwriter:id,name','mulsubwriter' => function ($query) {$query->with('user:id,name');}])
        ->where('admin_id', auth()->user()->id)->orderBy('id', 'desc')
        ->select([
                    'id', 'order_id', 'services', 'typeofpaper', 'pages', 'title',
                    'writer_deadline', 'chapter', 'wid', 'swid', 'writer_status',
                    'writer_fd', 'writer_ud', 'writer_ud_h', 'writer_fd_h',
                    'resit', 'tech'
                ])
        ->paginate(10);

        $data['tl'] = User::where('role_id', 6)->where('flag', 0)->where('admin_id' , auth()->user()->id)->orderBy('created_at', 'desc')->get(['id', 'name']);
        $data['writer'] = User::where('flag', 0)->where('role_id' , 7)->get(['id' , 'name' , 'admin_id']);

        return view('order.render.render-paginate-data', compact('data'))->render();


    }

    public function update_order(Request $req, $id)
    {
             $order = Order::find($id);
             $order->writer_status = $req->input('status'); 
              $order->wid = $req->input('tlid');
            // $order->writer_fd = $req->input('fromdate');
            // $order->writer_ud = $req->input('uptodate'); 
            // $order->writer_fd_h = $req->input('writer_fd_half'); 
            // $order->writer_ud_h = $req->input('writer_ud_half'); 
            $order->save();

            return response()->json([
                'status' => 'success'
            ]);

            // $subwriterIds = $req->input('subwriterSelect');
            // multipleswiter::where('order_id', $id)->delete();
            // foreach ($subwriterIds as $subwriterId) {
            //     $writer = new multipleswiter;
            //     $writer->order_id = $id;
            //     $writer->user_id = $subwriterId;
            //     $writer->save();
            // }
    }


    public function edit(Request $request , $id)
    {
        $data['order'] = Order::with(['writer:id,name', 'subwriter:id,name','mulsubwriter' => function ($query) {$query->with('user:id,name');}])
        ->where('admin_id', auth()->user()->id)->orderBy('id', 'desc')
        ->select([
                    'id', 'order_id', 'services', 'typeofpaper', 'pages', 'title',
                    'writer_deadline', 'chapter', 'wid', 'swid', 'writer_status',
                    'writer_fd', 'writer_ud', 'writer_ud_h', 'writer_fd_h',
                    'resit', 'tech'
                ])
        ->paginate(10);

        $data['tl'] = User::where('role_id', 6)->where('flag', 0)->where('admin_id' , auth()->user()->id)->orderBy('created_at', 'desc')->get(['id', 'name']);
        $data['writer'] = User::where('flag', 0)->where('role_id' , 7)->get(['id' , 'name' , 'admin_id']);

        return view('order.render.render-edit-form', compact('data'))->render();

    }

    public function qc(Request $request)
        {
            // $data['executive'] = User::where('role_id', 3)->get();
            // $data['writer'] = User::where('role_id', 6)->where('flag', 0)->get();
            // $data['SubWriter'] = User::where('role_id', 7)->where('flag', 0)->get();
            
            // $ordersQuery = Order::with('writer','mulsubwriter', 'subwriter')
            //     ->whereNotNull('admin_id')
            //     ->where('admin_id', '!=', 0)
            //     ->orderBy('created_at', 'desc');

            // $searchTerm = $request->input('search');
            // $status = $request->input('status');
            // $writer = $request->input('writer');
            // $SubWriter = $request->input('SubWriter');
            // $dateStatus = $request->input('date_status');
            // $fromDate = $request->input('fromDate');
            // $toDate = $request->input('toDate');
            // $admin = $request->input('admin');
            // $qc_standard = $request->input('qc_standard');
            // $secondaryMobile = $request->input('secondary_mobile');
            // $selectedDataTextBox = $request->input('selectedDataTextBox');
            // $edited_on = $request->input('edited_on');
            // $OldSubWriter = $request->input('OldSubWriter');

            // if ($fromDate != '' && $toDate != '') {
            //     if ($edited_on == 'Order-date') {
            //         $ordersQuery->whereBetween('writer_deadline', [$fromDate, $toDate]);
            //     } elseif ($edited_on == 'Qc-date') {
            //         $ordersQuery->whereBetween('qc_date', [$fromDate, $toDate]);
            //     }
            // } elseif ($fromDate != '') {
            //     if ($edited_on == 'Order-date') {
            //         $ordersQuery->whereDate('writer_deadline', $fromDate);
            //     } elseif ($edited_on == 'Qc-date') {
            //         $ordersQuery->whereDate('qc_date', $fromDate);
            //     }
            // }

            // if ($searchTerm != '') {
            //     $ordersQuery->where(function ($query) use ($searchTerm) {
            //         $query->where('order_id', 'like', '%' . $searchTerm . '%')
            //             ->orWhere('title', $searchTerm);
            //     });
            // }

            // // if ($status != '') {
            // //     $ordersQuery->where('qc_status', $status);
            // // }
            // if ($status != '') {
            //     $ordersQuery->where(function($query) use ($status) {
            //         if ($status == 'Not Assigned') {
            //             $query->where('writer_status', '')->orWhereNull('writer_status')->orWhere('writer_status', 'Not Assigned');
            //         } else {
            //             $query->where('writer_status', $status);
            //         }
            //     });
            // }

            // if ($writer != '') {
            //     $ordersQuery->where('wid', $writer);
            // }
            //   if ($SubWriter != '') {
                  
            //     $multipleWriters = multipleswiter::where('user_id', $SubWriter)->get();
                
            //     $orderIds = $multipleWriters->pluck('order_id')->toArray();
                
            //     $ordersQuery->whereIn('id', $orderIds);
                
            // }
            
             
            // if ($OldSubWriter != '') {
            //     $ordersQuery->where('swid',$OldSubWriter);
            // }
            
            // if ($admin != '') {
            //     $ordersQuery->where('qc_admin', $admin);
            // }

            // if ($qc_standard != '') {
            //     $ordersQuery->where('qc_standard', $qc_standard);
            // }

           
            // if ($fromDate != '' || $toDate != '' || $searchTerm != '' || $status != '' || $writer != '' || $SubWriter != '' || $admin != '' || $qc_standard != '' || $OldSubWriter != '') {
            //     $orders = $ordersQuery->paginate(1000);
            //     $data['orders'] = $orders;
            // }
            // else
            // {
            //     $orders = $ordersQuery->paginate(10);
            //     $data['orders'] = $orders;
            // }


            // return view('order.qc-sheet', compact('data'));
            return view('order.qc-sheet');
        }
}
