<?php

namespace App\Http\Controllers;

use App\Repositories\BankBranchRepository;
use Illuminate\Http\JsonResponse;

class BankBranchController extends Controller
{
    protected BankBranchRepository $branchRepository;

    public function __construct(BankBranchRepository $branchRepository)
    {
        $this->branchRepository = $branchRepository;
    }

    public function index(): JsonResponse
    {
        $data = $this->branchRepository->all();
        $headers = [ 'Content-Type' => 'application/json; charset=utf-8' ];

        return response()->json($data, 200, $headers, JSON_UNESCAPED_UNICODE);
    }
}
