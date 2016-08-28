<?php

namespace HNG\Http\Controllers\Admin;

use HNG\User;
use HNG\Http\Requests;
use HNG\Events\UserWasUpdated;
use HNG\Http\Controllers\Controller;

class UserController extends Controller {

    /**
     * Get the list of all the users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list()
    {
        return view('admin.users.list', [
            'inPageTitle' => 'User Management',
            'users' => User::orderBy('wallet', 'DESC')->paginate(50)
        ]);
    }

    /**
     * Update the user.
     *
     * @param  Requests\AdminUserUpdateRequest $request
     * @return array
     */
    public function update(Requests\AdminUserUpdateRequest $request)
    {
        $user = User::find($request->get('user_id'));

        $oldUser = clone $user;

        $user->setRole($request->get('role'));
        $user->setFreelunch($request->get('freelunch'));

        event(new UserWasUpdated([
            'oldUser'       => $oldUser,
            'updateRequest' => $request,
            'updatedUser'   => User::find($user->id),
        ]));

        return ['status' => 'success'];
    }
}