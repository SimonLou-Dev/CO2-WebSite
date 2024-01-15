<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utils\ModelTestTools;
use Tests\Utils\UserTestTools;

class SensorControllerTest extends TestCase
{
    use WithFaker;




    public function test_withoutPerm()
    {
        $user = UserTestTools::getTestUser();
        Sanctum::actingAs($user);

        $this->get('/api/sensors')->assertStatus(403);
        $this->post('/api/sensors')->assertStatus(403);
        $this->put('/api/sensors/1')->assertStatus(403);
        $this->get('/api/sensors/1')->assertStatus(403);
        $this->delete('/api/sensors/1')->assertStatus(403);
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
        $user = UserTestTools::getTestUser("administrator");
        Sanctum::actingAs($user);

        $room = ModelTestTools::createRoom();

        $response = $this->postJson('/api/sensors',
            ["device_addr"=> $this->faker->macAddress,
                "room_id"=>$room->id]
        );

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
