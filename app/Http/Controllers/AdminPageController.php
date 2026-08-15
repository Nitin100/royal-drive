<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AdminPageController extends Controller
{
    public function users(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            return $this->usersDatatable($request);
        }

        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $newSignups = User::where('created_at', '>=', now()->subDays(30))->count();
        $newThisWeek = User::where('created_at', '>=', now()->startOfWeek())->count();

        $roleBreakdown = Role::query()
            ->withCount('users')
            ->orderByDesc('users_count')
            ->get()
            ->map(function (Role $role) {
                return [
                    'name' => $role->name,
                    'count' => $role->users_count,
                ];
            });

        $roleLookup = $roleBreakdown->keyBy(fn (array $role) => strtolower($role['name']));
        $adminCount = $roleLookup['admin']['count'] ?? 0;
        $editorCount = $roleLookup['editor']['count'] ?? 0;
        $viewerCount = $roleLookup['viewer']['count'] ?? 0;

        return view('admin.users', [
            'totalUsers' => $totalUsers,
            'verifiedUsers' => $verifiedUsers,
            'newSignups' => $newSignups,
            'newThisWeek' => $newThisWeek,
            'adminCount' => $adminCount,
            'editorCount' => $editorCount,
            'viewerCount' => $viewerCount,
            'roleBreakdown' => $roleBreakdown,
        ]);
    }

    private function usersDatatable(Request $request): JsonResponse
    {
        $draw = (int) $request->input('draw', 1);
        $start = max(0, (int) $request->input('start', 0));
        $length = (int) $request->input('length', 10);
        $searchValue = trim((string) $request->input('search.value', ''));
        $orderColumnIndex = (int) $request->input('order.0.column', 3);
        $orderDirection = strtolower((string) $request->input('order.0.dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $columns = ['name', 'email', null, 'created_at', 'email_verified_at'];
        $orderColumn = $columns[$orderColumnIndex] ?? 'created_at';

        $query = User::query()->with('roles');
        $recordsTotal = (clone $query)->count();

        if ($searchValue !== '') {
            $query->where(function ($builder) use ($searchValue) {
                $builder->where('name', 'like', "%{$searchValue}%")
                    ->orWhere('email', 'like', "%{$searchValue}%")
                    ->orWhereHas('roles', function ($roleQuery) use ($searchValue) {
                        $roleQuery->where('name', 'like', "%{$searchValue}%");
                    });
            });
        }

        $recordsFiltered = (clone $query)->count();

        if ($orderColumn !== null) {
            $query->orderBy($orderColumn, $orderDirection);
        } else {
            $query->latest();
        }

        $users = $query->skip($start)
            ->take($length > 0 ? $length : 10)
            ->get();

        $data = $users->map(function (User $user) {
            $rolesHtml = $user->roles->isNotEmpty()
                ? $user->roles->map(fn ($role) => '<span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-200">' . e($role->name) . '</span>')->implode(' ')
                : '<span class="text-xs text-gray-500 dark:text-gray-400">Unassigned</span>';

            $statusHtml = $user->email_verified_at
                ? '<span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">Verified</span>'
                : '<span class="rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">Unverified</span>';

            return [
                'name' => e($user->name),
                'email' => e($user->email),
                'roles' => '<div class="flex flex-wrap gap-1">' . $rolesHtml . '</div>',
                'joined' => e($user->created_at?->diffForHumans() ?? '-'),
                'status' => $statusHtml,
            ];
        })->values();

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function reports(Request $request)
    {
        $rangeOptions = [
            '7d' => ['label' => '7D', 'days' => 7],
            '30d' => ['label' => '30D', 'days' => 30],
            '90d' => ['label' => '90D', 'days' => 90],
            '180d' => ['label' => '180D', 'days' => 180],
        ];

        $activeRange = $request->string('range')->toString();
        if (! array_key_exists($activeRange, $rangeOptions)) {
            $activeRange = '30d';
        }

        $rangeDays = $rangeOptions[$activeRange]['days'];
        $endDate = now()->endOfDay();
        $startDate = now()->startOfDay()->subDays($rangeDays - 1);
        $isDaily = $rangeDays <= 30;

        $signups = User::query()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get(['created_at']);

        if ($isDaily) {
            $signupMap = $signups->groupBy(fn (User $user) => $user->created_at->toDateString())
                ->map(fn ($users) => $users->count());

            $chartData = collect(range(0, $rangeDays - 1))->map(function (int $offset) use ($startDate, $signupMap) {
                $date = (clone $startDate)->addDays($offset);
                $key = $date->toDateString();

                return [
                    'label' => $date->format('M j'),
                    'count' => (int) ($signupMap[$key] ?? 0),
                ];
            });
        } else {
            $weekCount = (int) ceil($rangeDays / 7);
            $weekStart = now()->startOfWeek()->subWeeks($weekCount - 1);

            $signupMap = $signups->groupBy(function (User $user) {
                return sprintf('%d%02d', $user->created_at->isoWeekYear, $user->created_at->isoWeek);
            })->map(fn ($users) => $users->count());

            $chartData = collect(range(0, $weekCount - 1))->map(function (int $offset) use ($weekStart, $signupMap) {
                $date = (clone $weekStart)->addWeeks($offset);
                $key = sprintf('%d%02d', $date->isoWeekYear, $date->isoWeek);

                return [
                    'label' => $date->format('M j'),
                    'count' => (int) ($signupMap[$key] ?? 0),
                ];
            });
        }

        $maxSignups = max(1, $chartData->max('count'));

        $totalUsers = User::count();
        $verifiedUsers = User::whereNotNull('email_verified_at')->count();
        $unverifiedUsers = max(0, $totalUsers - $verifiedUsers);
        $verifiedPercent = $totalUsers > 0 ? (int) round(($verifiedUsers / $totalUsers) * 100) : 0;
        $unverifiedPercent = 100 - $verifiedPercent;

        $latestUsers = User::latest()->take(5)->get()->map(function (User $user) {
            return [
                'name' => $user->name,
                'email' => $user->email,
                'joined' => Carbon::parse($user->created_at)->diffForHumans(),
            ];
        });

        return view('admin.reports', [
            'totalUsers' => $totalUsers,
            'rangeOptions' => $rangeOptions,
            'activeRange' => $activeRange,
            'isDaily' => $isDaily,
            'chartData' => $chartData,
            'maxSignups' => $maxSignups,
            'verifiedUsers' => $verifiedUsers,
            'unverifiedUsers' => $unverifiedUsers,
            'verifiedPercent' => $verifiedPercent,
            'unverifiedPercent' => $unverifiedPercent,
            'latestUsers' => $latestUsers,
        ]);
    }
}
