<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loginUser(Request $request): Response
    {
        $input = $request->all();

        Auth::attempt($input);

        
        $user = Auth::user();
        // dd(Auth::user());
        $token = $user->createToken('UserToken')->accessToken;
        return Response(['status' => 200,'token' => $token],200);
    }

    public function regUser(Request $request): Response
    {
        // dd($request);
          // Validate the incoming request
          $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|',
            'password' => 'required|string|min:5',
            'role' => 'required|',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        // Generate a token for the user
        $token = $user->createToken('UserToken')->accessToken;

        // Return the token as a response
        return Response(['status'=> 200,'access_token' => $token,'user'=> $user], 200);
    }

    public function index(Request $request)
    {
        $user = Auth::guard('api')->user();
        // dd($user->role);

        if ($user->role == "staff") {
            $query = Student::query();

            // Search by name or email
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('email', 'LIKE', '%' . $searchTerm . '%');
                });
            }
        }

            $students = $query->paginate(10); // Adjust the pagination limit as per your requirement

            return response()->json($students);
        // }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getUserDetail(): Response
    {
        if(Auth::guard('api')->check()){
            $user = Auth::guard('api')->user();
            return Response(['data' => $user],200);
        }
        return Response(['data' => 'Unauthorized'],401);
    }

    /**
     * Display the specified resource.
     */
    public function userLogout(): Response
    {
        if(Auth::guard('api')->check()){
            $accessToken = Auth::guard('api')->user()->token();

                \DB::table('oauth_refresh_tokens')
                    ->where('access_token_id', $accessToken->id)
                    ->update(['revoked' => true]);
            $accessToken->revoke();

            return Response(['data' => 'Unauthorized','message' => 'User logout successfully.'],200);
        }
        return Response(['data' => 'Unauthorized'],401);
    }

   
}
