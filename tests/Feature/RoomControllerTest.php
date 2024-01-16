<?php

namespace Tests\Feature;

use App\Models\Room;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Tests\Utils\ModelTestTools;
use Tests\Utils\UserTestTools;

class RoomControllerTest extends TestCase
{

    use WithFaker;


    public function test_withoutPerm()
    {
        $user = UserTestTools::getTestUser();
        Sanctum::actingAs($user);

        $room = ModelTestTools::createRoom();

        $this->get('/api/rooms')->assertStatus(200);
        $this->post('/api/rooms')->assertStatus(403);
        $this->put('/api/rooms/' . $room->id)->assertStatus(403);
        $this->get('/api/rooms/' . $room->id)->assertStatus(200);
        $this->delete('/api/rooms/' . $room->id)->assertStatus(403);
    }

    public function test_viewAll_usr()
    {
        $user = UserTestTools::getTestUser();
        Sanctum::actingAs($user);

        $response = $this->get('/api/rooms');

        $response->assertStatus(200);
    }

    public function test_viewAll_adm()
    {
        $user = UserTestTools::getTestUser("administrator");
        Sanctum::actingAs($user);

        $response = $this->get('/api/rooms');

        $response->assertStatus(200);
    }



    public function test_createOne_adm()
    {
        $user = UserTestTools::getTestUser("administrator");
        Sanctum::actingAs($user);

        $name = $this->faker->name;

        $response = $this->postJson('/api/rooms',
            ["name"=> $name],
        );

        $response->assertStatus(201);

        $this->assertDatabaseHas("rooms", [
            "name"=> $name,
        ]);
    }

    public function test_viewOne_adm()
    {
        $user = UserTestTools::getTestUser("administrator");
        Sanctum::actingAs($user);

        $room = ModelTestTools::createRoom();

        $response = $this->getJson('/api/rooms/'.$room->id);

        $response->assertStatus(200);
    }

    public function test_viewOne_usr()
    {
        $user = UserTestTools::getTestUser();
        Sanctum::actingAs($user);

        $room = ModelTestTools::createRoom();

        $response = $this->getJson('/api/rooms/'.$room->id);

        $response->assertStatus(200);
    }

    public function test_updateOne_adm()
    {
        $user = UserTestTools::getTestUser("administrator");
        Sanctum::actingAs($user);

        $room = ModelTestTools::createRoom();
        $name = $this->faker->name;

        $response = $this->putJson('/api/rooms/'.$room->id,
            ["name"=> $name],
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas("rooms", [
            "name"=> $name,
        ]);
    }

    public function test_deleteOne_adm()
    {
        $user = UserTestTools::getTestUser("administrator");
        Sanctum::actingAs($user);

        $room = ModelTestTools::createRoom();

        $response = $this->delete('/api/rooms/'.$room->id);

        $response->assertStatus(200);


    }


}
