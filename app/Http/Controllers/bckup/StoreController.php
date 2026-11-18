<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index()
    {
            $stores = Store::with('user')->get();
            // dd($stores);/
            return view('admin.store_list', compact('stores'));
            // return view('admin.store_list');
    }

    public function show(Store $store)
    {   
        // dd($store);
       
        $user = auth()->user();
        // $store = Store::with('items')->get();
        $store->load('items');
       if ($user->is_superadmin) {
            // superadmin can view any store
            return view('admin.store_show', compact('store'));
        }

        // If user role is 'user', check if the store is assigned to them
        // Example for single user assignment:
        if ($user->id === $store->user_id) {
            return view('admin.store_show', compact('store'));
        }

        // Or for many-to-many (pivot) relation:
        // if ($store->users->contains($user->id)) {
        //     return view('admin.store_show', compact('store'));
        // }

        abort(403, 'Unauthorized access.');

        return view('admin.store_show', compact('store'));
    }

    public function userStores()
    {
        $stores = Store::with('user')
        ->where('user_id', Auth::id())
        ->get();
        //dd($stores);
        return view('admin.store_list', compact('stores'));
    }

    
    public function create()
    {
        $users = User::all();
        return view('admin.store_create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $store = Store::create(['name' => $request->name,'user_id' => $request->user_id]);

        return redirect()->route('stores.index')->with('success', 'Store created and user assigned!');
    }

    public function destroy(Store $store)
    {
        $store->delete(); // soft delete
        return response()->json(['message' => 'Store deleted successfully']);
    }
}

