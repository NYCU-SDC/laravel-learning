<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Actions\Jetstream\DeleteUser;

class UserController extends Controller
{
    protected $creator;
    protected $updater;
    protected $deleter;

    /**
     * Dependency Injection via Constructor
     */    
    public function __construct(CreateNewUser $creator, 
                                UpdateUserProfileInformation $updater, 
                                DeleteUser $deleter)
    {
        $this->creator = $creator;
        $this->updater = $updater;
        $this->deleter = $deleter;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('users.index', [
            'users' => User::All()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $arrayData = $request->all();
        $this->creator->create($arrayData);
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        $parameters = ['user' => $user];
        return view('users.show', $parameters);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        $parameters = ['user' => $user];
        return view('users.edit', $parameters);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);
        $arrayData = $request->all();
        $this->updater->update($user, $arrayData);
        return redirect()->route('users.show', $user->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $this->deleter->delete($user);
        return redirect()->route('users.index');
    }
}
