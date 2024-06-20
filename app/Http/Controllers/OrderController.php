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
            return view('order.qc-sheet');
        }
}
