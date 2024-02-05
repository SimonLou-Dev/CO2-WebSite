<?php

namespace Tests\Feature;


use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utils\UserTestTools;

class UserTest extends TestCase
{




    use WithFaker;
    public function test_login(){

        $user = User::factory()->make();
        $origin = $user->password;
        $user->password = \Hash::make($origin);
        $user->save();


        $this->postJson("/api/login", [
           "email"=>$user->email,
           "password"=>$origin,
           "device_name"=>$this->faker->name
        ])->assertStatus(200);



    }

    public function test_register(){
        $user = User::factory()->make();

        $request = $this->postJson("/api/register", [
           "name"=>$user->name,
           "email"=>$user->email,
           "password"=>$user->password,
           "password_confirmation"=>$user->password,
           "device_name"=>$this->faker->name
        ]);

        $request->assertStatus(201);

    }

    public function test_logout_logged(){
        $user = UserTestTools::getTestUser();
        Sanctum::actingAs($user);

         $this->patchJson("/api/logout")->assertStatus(200);
    }

    public function test_logout_unlogged(){

        $this->patchJson("/api/logout")->assertStatus(401);
    }

    public function test_authRoutes_logged()
    {
        $user = UserTestTools::getTestUser();
        Sanctum::actingAs($user);

        $this->postJson("/api/login")->assertStatus(302);
        $this->postJson("/api/register")->assertStatus(302);

    }

    public function test_userRoutes_unlogged()
    {

        $userTester = User::factory()->make();
        $userTester->save();
        $userTester = User::where("email", $userTester->email)->first();


        $this->get("/api/users")->assertStatus(302);
        $this->delete("/api/users/".$userTester->id)->assertStatus(302);
        $this->get("/api/user")->assertStatus(302);

    }

    public function test_userRoutes_withoutRight()
    {
        $user = UserTestTools::getTestUser();
        Sanctum::actingAs($user);

        $userTester = User::factory()->make();
        $userTester->save();
        $userTester = User::where("email", $userTester->email)->first();


        $this->get("/api/users")->assertStatus(403);
        $this->delete("/api/users/".$userTester->id)->assertStatus(403);

    }

    public function test_retrieve_current_user()
    {

        $user = UserTestTools::getTestUser();
        Sanctum::actingAs($user);

        $this->get("/api/user")->assertStatus(200);

    }


    public function test_getAllUser(){
        $user = UserTestTools::getTestUser("administrator");
        Sanctum::actingAs($user);

        $this->get("/api/users")->assertStatus(200);
    }

    public function test_deleteUser(){
        $userTester = User::factory()->make();
        $userTester->save();
        $userTester = User::where("email", $userTester->email)->first();

        $user = UserTestTools::getTestUser("administrator");

        Sanctum::actingAs($user);

        $this->delete("/api/users/".$userTester->id)->assertStatus(200);

    }
}
