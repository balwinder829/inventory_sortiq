<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
       $this->middleware('permission:all_store.view')->only(['index','create','store','destroy']);
       $this->middleware('permission:my_store.view|store_management.view')->only(['userStores']);
       $this->middleware('permission:all_store.view|my_store.view|store_management.view')->only(['show']);
       
    }
    public function index()
    {
            // $stores = Store::with('user')->get();
             if (auth()->user()->is_superadmin == 1) {

                $stores = Store::with('user')->get();

            } else {

                $stores = Store::with('user')
                    ->whereIn(
                        'id',
                        auth()->user()->stores->pluck('id')
                    )
                    ->get();
            }
            // dd($stores);/
            return view('admin.stores.index', compact('stores'));
            // return view('admin.store_list');
    }

    public function show(Store $store)
    {   
        // dd($store);
       
        $user = auth()->user();
        // $store = Store::with('items')->get();
        // $store->load('items');
        $store->load(['items' => function ($q) {
            $q->with(['batches' => function ($b) {
                $b->where('remaining_quantity', '>', 0);
            }]);
        }]);

       if ($user->is_superadmin) {
            // superadmin can view any store
            return view('admin.stores.show', compact('store'));
        }

        // If user role is 'user', check if the store is assigned to them
        // Example for single user assignment:
        if ($user->id === $store->user_id) {
            return view('admin.stores.show', compact('store'));
        }

        // Or for many-to-many (pivot) relation:
        // if ($store->users->contains($user->id)) {
        //     return view('admin.store_show', compact('store'));
        // }

        abort(403, 'Unauthorized access.');

        return view('admin.stores.show', compact('store'));
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
        return view('admin.stores.create', compact('users'));
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

    public function edit(Store $store)
    {
        $users = User::all();

        return view('admin.stores.edit', compact('store', 'users'));
    }

    public function update(Request $request, Store $store)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $store->update([
            'name' => $request->name,
            'user_id' => $request->user_id,
        ]);

        return redirect()
            ->route('stores.index')
            ->with('success', 'Store updated successfully!');
    }
    public function destroy(Store $store)
    {
        $store->delete(); // soft delete
        return response()->json(['message' => 'Store deleted successfully']);
    }
}

