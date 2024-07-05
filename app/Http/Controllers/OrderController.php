<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Multipleswiter;



class OrderController extends Controller
{
    public function index()
    {
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


 // Controller method
public function editform($id)
{
    $data['orderdata'] = Order::with([
            'writer:id,name',
            'subwriter:id,name',
            'mulsubwriter' => function ($query) {
                $query->with('user:id,name');
            }
        ])
        ->where('id', $id)
        ->select([
            'id', 'order_id', 'wid', 'writer_status',
            'writer_fd', 'writer_ud', 'writer_ud_h', 'writer_fd_h'
        ])
        ->first();

    $data['order'] = Order::with([
            'writer:id,name',
            'subwriter:id,name',
            'mulsubwriter' => function ($query) {
                $query->with('user:id,name');
            }
        ])
        ->where('admin_id', auth()->user()->id)
        ->orderBy('id', 'desc')
        ->select([
            'id', 'order_id', 'services', 'typeofpaper', 'pages', 'title',
            'writer_deadline', 'chapter', 'wid', 'swid', 'writer_status',
            'writer_fd', 'writer_ud', 'writer_ud_h', 'writer_fd_h',
            'resit', 'tech'
        ])
        ->paginate(10);

    $data['tl'] = User::where('role_id', 6)
        ->where('flag', 0)
        ->where('admin_id', auth()->user()->id)
        ->orderBy('created_at', 'desc')
        ->get(['id', 'name']);

    $data['writer'] = User::where('flag', 0)
        ->where('role_id', 7)
        ->get(['id', 'name', 'admin_id']);

    return view('order.render.edit-form', compact('data'))->render();
}





public function update(Request $request, $id)
{
    $request->validate([
        'fromdate' => 'required|date',
        'fromdate_time' => 'required|date_format:H:i',
        'uptodate' => 'required|date',
        'uptodate_time' => 'required|date_format:H:i',
        'tl_id' => 'required',
        'status' => 'required',
        'writers' => 'array|required'
    ]);

    try {
        $order = Order::findOrFail($id);
        $order->writer_fd = $request->fromdate;
        $order->writer_fd_h = $request->fromdate_time;
        $order->writer_ud = $request->uptodate;
        $order->writer_ud_h = $request->uptodate_time;
        $order->wid = $request->tl_id;
        $order->writer_status = $request->status;  // Update the status
        $order->save();

        // Handle updating the selected writers
        Multipleswiter::where('order_id', $id)->delete();
        foreach ($request->writers as $writerId) {
            $writer = new Multipleswiter;
            $writer->order_id = $id;
            $writer->user_id = $writerId;
            $writer->save();
        }

        return response()->json([
            'message' => 'Order updated successfully',
            'order' => $order
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'An error occurred while updating the order.',
            'details' => $e->getMessage()
        ], 500);
    }
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
    public function order()
    {
        if (auth()->user()->role_id == 6) {
            return view('WriterTeamLeader.order-tl');
        }elseif(auth()->user()->role_id == 7) {
            return view('Writer.order-writer');
        }
        return view('order.order');
    }

    public function writerAvailablity()
    {
        return view('order.writer-available');
    }

    public function tickerSheet()
    {
        return view('order.ticketNumber-Sheet');
    }
}
