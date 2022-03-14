<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

            if (!Auth::attempt($attr)) {
                return $this->error('Credentials not match', 401);
            }

            session(['company_id' => 1]);

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
            $permissions = $request->user()
                ->getAllPermissions()
                ->where('parent_id', '=', '0');

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
                                'menu' => $child->menu_name,
                                'icon' => $child->icon,
                                'route_name' => $child->route_name,
                            ];

                            $prev_name = $child->menu_name;
                        }
                    }
                }

                $array[] = [
                    'menu' => $permission->menu_name,
                    'icon' => $permission->icon,
                    'route_name' => $permission->route_name,
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
}
