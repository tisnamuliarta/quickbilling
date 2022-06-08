<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\App;

class AuthController extends Controller
{
    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function login(Request $request)
    {
        try {
            $attr = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            $remember = $request->remember;

            if (!Auth::attempt($attr, $remember)) {
                return $this->error('Credentials not match', 401);
            }

            session(['locale' => $request->locale]);

            $user = User::find(auth()->user()->id);
            $user->last_logged_in_at = Carbon::now();
            $user->save();

            return response()->json([
                'token' => $request->user()->createToken('api-token')->plainTextToken,
                'user' => auth()->user()
            ]);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 401, [
                'trace' => $exception->getTrace()
            ]);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        //auth()->logout();

        $request->user()->tokens()->delete();

        return $this->success([
            'message' => 'Tokens Revoked'
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
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'token' => $token,
            'user' => auth()->user(),
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        $user = User::where('id', '=', $request->user()->id)->with('roles')->first();
        return response()->json([
            'user' => $user
        ]);
    }

    /**
     * Display a listing of permissions from current logged user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function permissions(): \Illuminate\Http\JsonResponse
    {
        return response()->json(auth()->user()->getAllPermissions()->pluck('name'));
    }

    /**
     * Display a listing of roles from current logged user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function roles(): \Illuminate\Http\JsonResponse
    {
        return response()->json(auth()->user()->getRoleNames());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function menus(Request $request)
    {
        try {
            App::setLocale(session('locale'));

            $permissions = $request->user()
                ->getAllPermissions()
                ->where('parent_id', '=', '0')
                ->where('route_name', '=', 'N');

            $array = [];
            foreach ($permissions as $permission) {
                $children = $request->user()
                    ->getAllPermissions()
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
                    'children' => $array_child
                ];
            }
            return $this->success([
                'menus' => $array
            ]);
        } catch (\Exception $exception) {
            return $this->error($exception->getMessage(), 422, [
                'trace' => $exception->getTrace()
            ]);
        }
    }

    public function staticMenus()
    {

    }
}
