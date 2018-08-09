<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return \View::make('user.list');
    }

    public function getDatatable()
    {

        $users = User::all();

        return Datatables::of($users)
            ->addColumn('action', function ($object) {
                return '<a href="'.\URL::route('user.edit', ['id' => $object->id]).'"><i class="fa fa-search"></i></a>';
            })
            ->addColumn('organisatie', function ($object) {
                if (isset($object->client->naam)) {
                    return $object->client->naam;
                } else {
                    return 'Geen';
                }
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return \View::make('user.create')
            ->withLimited(false);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = new User;

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->password = $request->input('password');
        $user->password_confirmation = $request->input('password_confirmation');
        $user->client_id = $request->input('client_id');


        if (!$user->save()) {
            return redirect()->route('user.create')->with('errors', $user->errors());
        }

        $role = Role::find($request->input('role'));
        $user->detachRoles($user->roles);
        $user->attachRole($role);

        return redirect()->route('user.index')->with('status', 'Gebruiker opgeslagen.');
    }

    /**
     * Display the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = \Auth::user();

        return \View::make('user.edit')
            ->withUser($user)
            ->withLimited(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        return \View::make('user.edit')
            ->withUser($user)
            ->withLimited(false);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->first_name = \Input::get('first_name');
        $user->last_name = \Input::get('last_name');
        $user->email = \Input::get('email');
        if (!empty(\Input::get('password'))) {
            $user->password = \Input::get('password');
            $user->password_confirmation = \Input::get('password_confirmation');
        } else {
            unset($user->password);
        }
        $user->client_id = \Input::get('client_id');

        if (!$user->save()) {
            return redirect()->route('user.edit', ['id' => $user->id])->with('errors', $user->errors());
        }

        if (!empty($request->input('role'))) {
            $role = Role::find(\Input::get('role'));
            $user->detachRoles($user->roles);
            $user->attachRole($role);

            return redirect()->route('user.index')->with('status', 'Gebruiker opgeslagen.');
        }

        return redirect()->route('home');
    }

    public function resetPassword()
    {
        return \View::make('auth.forgot_password');
    }

    public function storeResetPassword()
    {
        $user = User::where('email', \Input::get('email'))->first();

        if ($user != null) {
            $new_password = str_random(10);

            $user->password = $new_password;
            $user->password_confirmation = $new_password;

            if ($user->save()) {
                \Mail::send('emails.reminder', ['user' => $user, 'password' => $new_password], function ($m) use ($user) {
                    $m->from('info@davelaar.nl', 'Logboek Davelaarbouw B.V.');

                    $m->to($user->email, $user->name)->subject('Nieuw wachtwoord');
                });
            }
        }

        return redirect()->route('login')->with('status', 'Nieuw wachtwoord verzonden.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $object = User::findOrFail($id);

        $object->delete();

        return redirect()->route('user.index')->with('status', 'Gebruiker is uitgeschakeld.');
    }
}
