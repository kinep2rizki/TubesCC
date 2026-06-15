<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;

    class AuthController extends Controller
    {
        /**
         * Create a new AuthController instance.
         *
         * @return void
         */
        public function __construct()
        {
            // Protect endpoints with auth:api middleware, except login
            // In Laravel 11, middleware in controllers is often defined differently or in routes, 
            // but we'll do it in routes to be clean.
        }

        /**
         * Get a JWT via given credentials.
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function login()
        {
            $credentials = request(['email', 'password']);

            if (! $token = auth('api')->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $this->respondWithToken($token);
        }

        /**
         * Get the authenticated User.
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function me()
        {
            return response()->json(auth('api')->user());
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
                'expires_in' => auth('api')->factory()->getTTL() * 60
            ]);
        }
    }
