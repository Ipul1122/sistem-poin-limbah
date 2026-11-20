<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PointCalculationService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected PointCalculationService $pointService
    ) {}

    /**
     * Tampilkan daftar seluruh peserta EcoPoint
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $query = User::where('role', 'peserta')
            ->withCount(['wasteTransactions', 'pointRedemptions']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderByDesc('total_points')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users', 'search'));
    }
}
