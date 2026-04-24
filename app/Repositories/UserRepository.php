<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * پیدا کردن کاربر بر اساس username
     */
    public function findByUsername(string $username): ?User
    {
        return $this->model->where('username', $username)->first();
    }

    /**
     * دریافت کاربران فعال
     */
    public function getActiveUsers()
    {
        return $this->model
            ->where('status', 'active')
            ->get();
    }

    /**
     * جستجو بر اساس نام کامل
     */
    public function searchByName(string $keyword, int $limit = 20)
    {
        return $this->model
            ->where('full_name', 'LIKE', "%{$keyword}%")
            ->limit($limit)
            ->get();
    }

    /**
     * دریافت کاربر همراه با نقش
     */
    public function getUserWithRole(int $id)
    {
        return $this->model->with('role')->findOrFail($id);
    }
}
