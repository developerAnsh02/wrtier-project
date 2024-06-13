<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function writerTL()
    {
        $data['tl'] = User::where('role_id', 6)
        ->where('flag', 0)
        ->where('admin_id' , auth()->user()->id)
        ->orderBy('created_at', 'desc')
        ->paginate(5);
        return view('user.WriterTL' , compact('data'));
    }

    public function InsertNewWriterTl(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Provide a valid email address',
            'email.unique' => 'Email already exists',
        ]);
    
        
        $writerTl = new User();
        $writerTl->name = $request->input('name');
        $writerTl->email = $request->input('email');
        $writerTl->password = Hash::make('user@123');
        $writerTl->role_id = 6;
        $writerTl->admin_id = auth()->user()->id ;
        $writerTl->save();
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function updateWriterTL(Request $req ,$id) 
    {
        
        $data['WriterTl'] = User::find($id);

         $data['tl'] = User::where('role_id', 6)
        ->where('flag', 0)
        ->where('admin_id' , auth()->user()->id)
        ->orderBy('created_at', 'desc')
        ->paginate(5);
        
        return view('user.render.render_tleditform', compact('data'))->render();   
    }

    public function UpdateWT(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Provide a valid email address',
            'email.unique' => 'Email already exists',
        ]);
    
        $writerTl = User::findOrFail($id);
        $writerTl->update($validatedData);
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function deactiveTL($id)
    {
        $writerTl = User::findOrFail($id);
        if (!$writerTl ) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $writerTl->flag = 1;
        $writerTl->save();
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function paginationTL(Request $request)
    {
        $data['tl'] = User::where('role_id', 6)
            ->where('flag', 0)
            ->where('admin_id' , auth()->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('user.render.render_tl', compact('data'))->render();
    }


    public function Subwriter()
    {
        $data['tl'] = User::where('role_id', 6)
        ->where('flag', 0)
        ->where('admin_id' , auth()->user()->id)
        ->get();

        $data['writer'] = User::where('flag', 0)->where('role_id' , 7)->get();

        return view('user.Subwriter' , compact('data'));
    }

    public function searchWriter(Request $request)
    {
        $data['writer'] = User::where('flag', 0)->where('role_id' , 7)->where('tl_id', $request->tlid)->get();

        if($data['writer']->count() >= 1)
        {
            return view('user.render.render_writer', compact('data'))->render();
        }
        else
        {
            return response()->json([
                'status' => 'nothing_found'
            ]);
        }

    }

    public function InsertNewWriter(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'tl' => 'required|string|max:255'
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Provide a valid email address',
            'email.unique' => 'Email already exists',
            'tl.required' => 'Team Leader is required',
        ]);
    
        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->tl_id = $validatedData['tl'];
        $user->role_id= 7;
        $user->password = Hash::make('user@123');
        
        $user->save();
    
        return response()->json([
            'status' => 'success',
        ], 201);
    }

    public function updateWriter($id)
    {
        $data['editablewriter'] = User::find($id);

        $data['tl'] = User::where('role_id', 6)
                    ->where('flag', 0)
                    ->where('admin_id' , auth()->user()->id)
                    ->get();

        $data['writer'] = User::where('flag', 0)->where('role_id' , 7)->get();

        return view('user.render.render_writerform', compact('data'))->render();
    }

    public function UpdatesubWriter(Request $request, $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'tl_id' => 'nullable|integer|exists:users,id'
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Provide a valid email address',
            'email.unique' => 'Email already exists',
            'tl_id.exists' => 'The selected team leader does not exist',
        ]);
    
        $writer = User::findOrFail($id);
        $writer->name = $request->input('name');
        $writer->email = $request->input('email');
        $writer->tl_id = $request->input('tl_id');
        $writer->save();


        return response()->json([
            'status' => 'success'
        ]);
    }
    
    


    public function deactivewriter($id)
    {
        $writer = User::findOrFail($id);
        
        if (!$writer ) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $writer->flag = 1;
        $writer->save();
        return response()->json([
            'status' => 'success'
        ]);
    }


    public function searchWriterOrder(Request $request)
    {
        $id = $request->id;
        
        $data['writer'] = User::where('flag', 0)->where('role_id' , 7)->where('tl_id', $request->tlid)->get();
            if($data['writer']->count() >= 1)
            {
                return view('order.render.render-writer', compact('data', 'id'))->render();
            }
            else
            {
                return response()->json([
                    'status' => 'nothing_found'
                ]);
            }
    }

    
    
    
}
