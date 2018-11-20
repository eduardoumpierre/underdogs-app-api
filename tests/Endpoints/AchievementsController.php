<?php

class AchievementsTest extends \TestCase
{
    const URL = '/api/v1/achievements/';

    /**
     * Get all achievements test
     */
    public function testGettingAllAchievements()
    {
        // Request without authentication
        $this->get(AchievementsTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        factory(\App\Achievement::class)->create();

        // Get all achievements with the authenticated user
        $this->get(AchievementsTest::URL);
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'category', 'drops_id', 'entity', 'experience', 'name', 'value'
            ]
        ]);
    }

    /**
     * Get one achievement test
     */
    public function testGettingSpecificAchievements()
    {
        // Request without authentication
        $this->get(AchievementsTest::URL . '1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        factory(\App\Achievement::class)->create();

        // Get one achievement
        $this->get(AchievementsTest::URL . '1');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'category', 'drops_id', 'entity', 'experience', 'name', 'value'
        ]);

        // Accessing invalid achievement should give 404
        $this->get(AchievementsTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     * Creating achievement test
     */
    public function testCreatingAchievements()
    {
        // Request without authentication
        $this->post(AchievementsTest::URL, [
            'name' => 'Teste',
            'experience' => '9999',
            'category' => 0,
            'entity' => 1,
            'value' => 1
        ]);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        factory(\App\Achievement::class)->create();

        // Valid request
        $this->post(AchievementsTest::URL, [
            'name' => 'Teste',
            'experience' => '9999',
            'category' => 0,
            'entity' => 1,
            'value' => 1
        ]);
        $this->assertResponseStatus(201);

        // Invalid request - required fields are missing
        $this->post(AchievementsTest::URL, [
            'name' => 'Teste',
            'experience' => '9999',
            'category' => 0,
            'entity' => 1
        ]);
        $this->assertResponseStatus(422);
    }

    /**
     * Updating achievement test
     */
    public function testUpdatingAchievements()
    {
        // Request without authentication
        $this->put(AchievementsTest::URL . '1', [
            'name' => 'Teste',
            'experience' => '9999',
            'category' => 0,
            'entity' => 1,
            'value' => 1
        ]);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        factory(\App\Achievement::class)->create();

        // Valid request
        $this->put(AchievementsTest::URL . '1', [
            'name' => 'Teste',
            'experience' => '9999',
            'category' => 0,
            'entity' => 1,
            'value' => 1
        ]);
        $this->assertResponseStatus(200);

        // Invalid request - required fields are missing
        $this->put(AchievementsTest::URL . '1', [
            'name' => 'Teste',
            'experience' => '9999',
            'category' => 0,
            'entity' => 1,
        ]);
        $this->assertResponseStatus(422);

        // Invalid request - achievement do not exists
        $this->put(AchievementsTest::URL . '123456789', [
            'name' => 'Teste',
            'experience' => '9999',
            'category' => 0,
            'entity' => 1,
            'value' => 1
        ]);
        $this->assertResponseStatus(404);
    }

    /**
     * Removing achievement test
     */
    public function testRemovingAchievements()
    {
        // Request without authentication
        $this->delete(AchievementsTest::URL . '1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        factory(\App\Achievement::class)->create();

        // Valid request
        $this->delete(AchievementsTest::URL . '1');
        $this->assertResponseStatus(204);

        // Invalid request - achievement don't exists
        $this->delete(AchievementsTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }
}
