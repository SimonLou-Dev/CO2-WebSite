<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Annotations\OpenApi as OA;

class ApiTokenController extends Controller
{





    public function editRole(Request $request, string $role){
        $user = User::where('id', $request->user()->id)->first();
        $roles = $user->getRoleNames();
        if(count($roles) == 0) {
            $user->assignRole($role);

            return response()->json([
                'message' => 'Role set successfully',
                'user' => $user
            ], 200);
        }

        $user->removeRole($roles[0]);

        $user->assignRole($role);

        return response()->json([
            'message' => 'Role updated successfully',
            'user' => $user
        ], 200);
    }


}
