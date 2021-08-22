<?php


namespace App\Repositories;


use App\User;

class UserRepository
{
    public function getAll()
    {
        return User::all();
    }

    public function getOne($id)
    {
        return User::findOrFail($id);
    }

    public function delete($id)
    {
        $user = User::findorFail($id);
        return $user->delete();
    }

    public function save($data)
    {
        return User::create($data);
    }

    public function update($id, $data)
    {
        return User::where('id', $id)->update($data);
    }
}
