<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * @param  Request  $request
     * @return mixed
     */
    public function login(Request $request): mixed
    {
        try {
            $attr = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            // Artisan::call('cache:clear');
            // Artisan::call('config:cache');

            $remember = $request->remember;

            if (! Auth::attempt($attr, $remember)) {
                return $this->error('Credentials not match', 401);
            }

            session(['locale' => $request->localeApp]);

            $user = User::find(auth()->user()->id);
            $user->last_logged_in_at = Carbon::now();
            $user->locale = $request->localeApp;
            $user->save();

            return response()->json([
                'token' => Auth::user()->createToken('api-token')->plainTextToken,
                'user' => auth()->user(),
                'locale' => session('locale'),
            ]);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 401, [
                'trace' => $exception->getTrace(),
            ]);
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        //auth()->logout();

        $request->user()->tokens()->delete();

        return $this->success([
            'message' => 'Tokens Revoked',
        ]);
    }

    /**
     * JWT refresh token
     *
     * @return mixed
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string  $token
     * @return JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'user' => auth()->user(),
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
        ]);
    }

    /**
     * @param  Request  $request
     * @return JsonResponse
     */
    public function user(Request $request)
    {
        $user = User::where('id', '=', $request->user()->id)
            ->with(['roles', 'entity', 'entity.currency'])
            ->first();

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * Display a listing of permissions from current logged user.
     *
     * @return JsonResponse
     */
    public function permissions(): JsonResponse
    {
        return response()->json(auth()->user()->getAllPermissions()->pluck('name'));
    }

    /**
     * Display a listing of roles from current logged user.
     *
     * @return JsonResponse
     */
    public function roles(): JsonResponse
    {
        return response()->json(auth()->user()->getRoleNames());
    }

    /**
     * @return JsonResponse
     */
    public function dateFilter(): JsonResponse
    {
        App::setLocale(auth()->user()->locale);
        $now = Carbon::now();
        $weekStartDate = $now->startOfWeek()->format('Y-m-d');
        $weekEndDate = $now->endOfWeek()->format('Y-m-d');

        $startOfMonth = $now->startOfMonth('Y-m-d')->format('Y-m-d');
        $endOfMonth = $now->endOfMonth()->format('Y-m-d');

        // $startOfYear = $now->startOfYear();
        $startOfYear = Carbon::create(date('Y'), '01')->startOfMonth()->format('Y-m-d');
        $endOfYear   = $now->endOfYear()->format('Y-m-d');


        $lastWeek = Carbon::now()->addDays(-7);
        $startOfLastWeek = $lastWeek->startOfWeek()->format('Y-m-d');
        $endOfLastWeek = $lastWeek->endOfWeek()->format('Y-m-d');

        $filter = [
            [
                'text' => 'Custom',
                'date_from' => null,
                'date_to' => null
            ],
            [
                'text' => __('Since Yesterday'),
                'date_from' => Carbon::now()->addDays(-1)->format('Y-m-d'),
                'date_to' => date('Y-m-d')
            ],
            [
                'text' => __('Since 2 Days Ago'),
                'date_from' => Carbon::now()->addDays(-2)->format('Y-m-d'),
                'date_to' => date('Y-m-d')
            ],
            [
                'text' => __('This Week'),
                'date_from' => $weekStartDate,
                'date_to' => $weekEndDate
            ],
            [
                'text' => __('Last Week'),
                'date_from' => $startOfLastWeek,
                'date_to' => $endOfLastWeek
            ],
             [
                 'text' => __('This Week to date'),
                 'date_from' => $weekStartDate,
                 'date_to' => date('Y-m-d')
             ],
            [
                'text' => __('This Month'),
                'date_from' => $startOfMonth,
                'date_to' => $endOfMonth
            ],
             [
                 'text' => __('This Month-to-date'),
                 'date_from' => $startOfMonth,
                 'date_to' => date('Y-m-d')
             ],
             [
                 'text' => __('This Year'),
                 'date_from' => $startOfYear,
                 'date_to' => $endOfYear
             ],
             [
                 'text' => __('This Year to date'),
                 'date_from' => $startOfYear,
                 'date_to' => date('Y-m-d')
             ],
             [
                 'text' => __('Since 30 Days Ago'),
                 'date_from' => Carbon::now()->addDays(-30)->format('Y-m-d'),
                 'date_to' => date('Y-m-d')
             ],
             [
                 'text' => __('Since 60 Days Ago'),
                 'date_from' => Carbon::now()->addDays(-60)->format('Y-m-d'),
                 'date_to' => date('Y-m-d')
             ],
             [
                 'text' => __('Since 90 Days Ago'),
                 'date_from' => Carbon::now()->addDays(-90)->format('Y-m-d'),
                 'date_to' => date('Y-m-d')
             ],
             [
                 'text' => __('Since 365 Days Ago'),
                 'date_from' => Carbon::now()->addDays(-365)->format('Y-m-d'),
                 'date_to' => date('Y-m-d')
             ],
        ];

        return $this->success([
            'date_filter' => $filter
        ]);
    }

    /**
     * list menus
     *
     * @param  \Illuminate\Http\Request  $request
     * @return JsonResponse
     */
    public function menus(Request $request): JsonResponse
    {
        try {
            App::setLocale(auth()->user()->locale);

            $permissions = $request->user()
                ->getAllPermissions()
                ->sortBy('order_line')
                ->where('parent_id', '=', '0')
                ->where('route_name', '=', 'N');

            $array = [];
            foreach ($permissions as $permission) {
                $children = $request->user()
                    ->getAllPermissions()
                    ->sortBy('order_line')
                    ->where('parent_id', '=', $permission->id);

                $array_child = [];
                $prev_name = '';
                foreach ($children as $child) {
                    if ($prev_name != $child->menu_name) {
                        if (Str::contains($child->name, 'index')) {
                            $array_child[] = [
                                'menu' => __($child->menu_name),
                                'icon' => __($child->icon),
                                'route_name' => __($child->route_name),
                            ];

                            $prev_name = $child->menu_name;
                        }
                    }
                }

                $array[] = [
                    'menu' => __($permission->menu_name),
                    'icon' => __($permission->icon),
                    'route_name' => __($permission->route_name),
                    'children' => $array_child,
                ];
            }

            return $this->success([
                'menus' => $array,
            ]);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                'trace' => $exception->getTrace(),
            ]);
        }
    }
}
