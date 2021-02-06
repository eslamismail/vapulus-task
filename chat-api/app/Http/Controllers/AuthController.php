<?php

/**
 * @OA\Post(
 * path="/api/auth/login",
 * summary="Sign in",
 * description="Login by email, password",
 * operationId="authLogin",
 * tags={"auth"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"email","password"},
 *       @OA\Property(property="email", type="email", format="code", example="email@exmple.com"),
 *       @OA\Property(property="password", type="string", format="password", example="123456"),
 *    ),
 * ),
 * @OA\Response(
 *    response=200,
 *    description="response with token",
 *    @OA\JsonContent(
 *       @OA\Property(property="data", type="Object", example={"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC82NC4yMjUuMTA4LjQ5XC9hcGlcL3NlbGxlclwvcmVmcmVzaCIsImlhdCI6MTYxMjU1MTY3NCwiZXhwIjoxNjEyNTU1NDI4LCJuYmYiOjE2MTI1NTE4MjgsImp0aSI6ImFHM1REenpzajFQZmI2cW0iLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.Bp4_A1PZKV3w90hDMY4nmIYgbnkm1BubAJwrztlylgo","token_type":"bearer","expires_in":"2021-2-2 23:06"})
 *        )
 *     )
 * )
 * @OA\Post(
 * path="/api/auth/register",
 * summary="Sign in",
 * description="Login by email, password",
 * operationId="authLogin",
 * tags={"auth"},
 * @OA\RequestBody(
 *    required=true,
 *    description="Pass user credentials",
 *    @OA\JsonContent(
 *       required={"email","password","name"},
 *       @OA\Property(property="email", type="email", format="email", example="email@exmple.com"),
 *       @OA\Property(property="name", type="name", format="string", example="Eslam ismail"),
 *       @OA\Property(property="password", type="string", format="password", example="123456"),
 *    ),
 * ),
 * @OA\Response(
 *    response=200,
 *    description="response with token",
 *    @OA\JsonContent(
 *       @OA\Property(property="data", type="Object", example={"access_token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC82NC4yMjUuMTA4LjQ5XC9hcGlcL3NlbGxlclwvcmVmcmVzaCIsImlhdCI6MTYxMjU1MTY3NCwiZXhwIjoxNjEyNTU1NDI4LCJuYmYiOjE2MTI1NTE4MjgsImp0aSI6ImFHM1REenpzajFQZmI2cW0iLCJzdWIiOjEsInBydiI6IjIzYmQ1Yzg5NDlmNjAwYWRiMzllNzAxYzQwMDg3MmRiN2E1OTc2ZjcifQ.Bp4_A1PZKV3w90hDMY4nmIYgbnkm1BubAJwrztlylgo","token_type":"bearer","expires_in":"2021-2-2 23:06"})
 *        )
 *     )
 * )
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;

class AuthController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        request()->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required|min:6',
        ]);

        $credentials = request(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        // return $request->all();
        $request->validate([
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6',
            'name' => 'required|string',
            'image' => 'mimes:png,jpg,ico|max:2048',
            'password_confirmation' => 'required|same:password',
        ]);

        $data = $request->only(['email', 'name']);
        $data['password'] = bcrypt($request->password);

        if ($request->has('image')) {
            $data['profile_picture'] = Storage::disk('uploads')->put('user', $request->image);
        }
        User::create($data);
        return $this->login();
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(['user' => auth('api')->user()]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => auth('api')->user(),
            'message' => 'welcome back ' . auth('api')->user()->name,
        ]);
    }
}
