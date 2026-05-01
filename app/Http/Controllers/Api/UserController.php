<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    protected UserRepository $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return response()->json($this->repo->getActiveUsers());
    }
}
