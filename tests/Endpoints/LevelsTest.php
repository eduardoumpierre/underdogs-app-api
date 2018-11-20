<?php

class LevelsTest extends \TestCase
{
    const URL = '/api/v1/levels/';

    /**
     * Get all levels test
     */
    public function testGettingAllLevels()
    {
        // Request without authentication
        $this->get(LevelsTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Get all levels with the authenticated user
        $this->get(LevelsTest::URL);
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'number', 'experience'
            ]
        ]);
    }

    /**
     * Get one level test
     */
    public function testGettingSpecificLevel()
    {
        // Request without authentication
        $this->get(LevelsTest::URL . '1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Get one level
        $this->get(LevelsTest::URL . '1');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'experience', 'number', 'drops'
        ]);

        // Accessing invalid level should give 404
        $this->get(LevelsTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     * Creating level test
     */
    public function testCreatingLevel()
    {
        // Request without authentication
        $this->post(LevelsTest::URL, [
            'number' => '1000',
            'experience' => '9999',
            'drops' => [1]
        ]);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        factory(\App\Drop::class)->create();

        // Valid request
        $this->post(LevelsTest::URL, [
            'number' => '1000',
            'experience' => '9999',
            'drops' => [0 => 1]
        ]);
        $this->assertResponseStatus(201);

        // Invalid request - required fields are missing
        $this->post(LevelsTest::URL, [
            'number' => '1000',
            'experience' => '9999'
        ]);
        $this->assertResponseStatus(422);
    }

    /**
     * Updating level test
     */
    public function testUpdatingLevel()
    {
        // Request without authentication
        $this->put(LevelsTest::URL . '1', [
            'number' => '1000',
            'experience' => '9999',
            'drops' => [1]
        ]);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        factory(\App\Drop::class)->create();

        // Valid request
        $this->put(LevelsTest::URL . '1', [
            'number' => '1000',
            'experience' => '9999',
            'drops' => [0 => 1]
        ]);
        $this->assertResponseStatus(200);

        // Invalid request - required fields are missing
        $this->put(LevelsTest::URL . '1', [
            'number' => '1000',
            'experience' => '9999'
        ]);
        $this->assertResponseStatus(422);

        // Invalid request - level do not exists
        $this->put(LevelsTest::URL . '123456789', [
            'number' => '1001',
            'experience' => '9999',
            'drops' => [1]
        ]);
        $this->assertResponseStatus(404);
    }

    /**
     * Removing level test
     */
    public function testRemovingLevel()
    {
        // Request without authentication
        $this->delete(LevelsTest::URL . '1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->delete(LevelsTest::URL . '1');
        $this->assertResponseStatus(204);

        // Invalid request - level don't exists
        $this->delete(LevelsTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }
}
