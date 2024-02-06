<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utils\UserTestTools;

class ChirpstackControllerTest extends TestCase
{

    public function test_update_Keys_asVisitor()
    {
        $this->putJson("/api/chirpstack/keys")->assertStatus(401);
    }

    public function test_update_Keys_asUser()
    {
        $user = UserTestTools::getTestUser();
        Sanctum::actingAs($user);
        $this->putJson("/api/chirpstack/keys")->assertStatus(403);
    }

    /*public function test_update_Keys_asAdmin(){
        $user = UserTestTools::getTestUser("administrator");
        Sanctum::actingAs($user);

        Queue::fake();

        Cache::shouldReceive("has")->with("CHIRPSTACK_API_KEY")->andReturn(false);
        Cache::shouldReceive("has")->with("CHIRPSTACK_DEVICE_PROFILE_ID")->andReturn(false);
        Cache::shouldReceive("has")->with("CHIRPSTACK_APPLICATION_ID")->andReturn(false);

        Cache::shouldReceive("add")->with("CHIRPSTACK_API_KEY", \Mockery::any(), \Mockery::any())->andReturn(true);
        Cache::shouldReceive("add")->with("CHIRPSTACK_DEVICE_PROFILE_ID", \Mockery::any(), \Mockery::any())->andReturn(true);
        Cache::shouldReceive("add")->with("CHIRPSTACK_APPLICATION_ID", \Mockery::any(), \Mockery::any())->andReturn(true);



        $this->putJson('/api/chirpstack/keys', [
            "app_id" => "87674096-09e4-41e8-b114-a994a56e9e8b",
            "profile_id" => "e4d9526a-d02d-440c-b467-61213cd55d00",
            "api_key" => "e4d9526a-d02d-440c-b467-61213cd55d00"
        ])->assertStatus(200);

    }*/
}
