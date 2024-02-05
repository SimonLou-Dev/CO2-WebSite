<?php

namespace Tests\Feature;

use App\Jobs\AddNewDeviceToGatJob;
use App\Models\Room;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utils\ChirpstackKeysTestTools;
use Tests\Utils\ModelTestTools;
use Tests\Utils\UserTestTools;

class SensorControllerTest extends TestCase
{
    use WithFaker;




    public function test_withoutPerm()
    {
        $user = UserTestTools::getTestUser();
        Sanctum::actingAs($user);

        $sensor = ModelTestTools::createSensor();

        $this->get('/api/sensors')->assertStatus(403);
        $this->post('/api/sensors')->assertStatus(403);
        $this->put('/api/sensors/' . $sensor->id)->assertStatus(403);
        $this->get('/api/sensors/' . $sensor->id)->assertStatus(200);
        $this->delete('/api/sensors/' . $sensor->id)->assertStatus(403);
    }

    public function test_unAuthed()
    {

        $sensor = ModelTestTools::createSensor();

        $this->get('/api/sensors')->assertStatus(302);
        $this->post('/api/sensors')->assertStatus(302);
        $this->put('/api/sensors/' . $sensor->id)->assertStatus(302);
        $this->get('/api/sensors/' . $sensor->id)->assertStatus(200);
        $this->delete('/api/sensors/' . $sensor->id)->assertStatus(302);
    }

    public function test_viewAll_adm()
    {
        $user = UserTestTools::getTestUser("administrator");
        Sanctum::actingAs($user);

        $response = $this->get('/api/sensors');

        $response->assertStatus(200);
    }

    public function test_createOne_adm()
    {

        Queue::fake();

        Cache::shouldReceive("has")->with("CHIRPSTACK_API_KEY")->andReturn(true);
        Cache::shouldReceive("has")->with("CHIRPSTACK_DEVICE_PROFILE_ID")->andReturn(true);
        Cache::shouldReceive("has")->with("CHIRPSTACK_APPLICATION_ID")->andReturn(true);

        Cache::shouldReceive("get")->with("CHIRPSTACK_API_KEY")->andReturn("test");
        Cache::shouldReceive("get")->with("CHIRPSTACK_DEVICE_PROFILE_ID")->andReturn("test");
        Cache::shouldReceive("get")->with("CHIRPSTACK_APPLICATION_ID")->andReturn("test");

        $user = UserTestTools::getTestUser("administrator");
        Sanctum::actingAs($user);

        $room = ModelTestTools::createRoom();

        $response = $this->postJson('/api/sensors',
            ["device_addr"=> $this->faker->macAddress,
                "room_id"=>$room->id]
        );

        Queue::shouldReceive(AddNewDeviceToGatJob::class);

        $response->assertStatus(201);

    }

    public function test_viewOne_adm()
    {
        $user = UserTestTools::getTestUser("administrator");
        Sanctum::actingAs($user);

        $sensor = ModelTestTools::createSensor();

        $response = $this->getJson('/api/sensors/'.$sensor->id);

        $response->assertStatus(200);
    }

    public function test_updateOne_adm()
    {
        $user = UserTestTools::getTestUser("administrator");
        Sanctum::actingAs($user);

        $room = ModelTestTools::createRoom();
        $sensor = ModelTestTools::createSensor();

        $response = $this->putJson('/api/sensors/'.$sensor->id,
            ["room_id"=> $room->id,
                "device_addr"=> $this->faker->macAddress],
        );

        $response->assertStatus(200);
    }

    public function test_deleteOne_adm()
    {
        $user = UserTestTools::getTestUser("administrator");
        Sanctum::actingAs($user);

        $sensor = ModelTestTools::createSensor();

        $response = $this->delete('/api/sensors/'.$sensor->id);

        $response->assertStatus(200);

        $this->assertDatabaseMissing("sensors", [
            "id"=> $sensor->id,
        ]);
    }
}
