<?php

class CardsTest extends \TestCase
{
    const URL = '/api/v1/cards/';

    /**
     * Get all cards test
     */
    public function testGettingAllCards()
    {
        // Request without authentication
        $this->get(CardsTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Get all cards with the authenticated user
        $this->get(CardsTest::URL);
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'number'
            ]
        ]);

        // Get all cards with the authenticated user
        $this->get(CardsTest::URL . '?active=true');
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'number'
            ]
        ]);
    }

    /**
     * Get one card test
     */
    public function testGettingSpecificCard()
    {
        // Request without authentication
        $this->get(CardsTest::URL . '1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Get one card
        $this->get(CardsTest::URL . '1');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'number'
        ]);

        // Accessing invalid card should give 404
        $this->get(CardsTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     * Creating card test
     */
    public function testCreatingCard()
    {
        // Request without authentication
        $this->post(CardsTest::URL, [
            'number' => '123456789'
        ]);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->post(CardsTest::URL, [
            'number' => '123456789'
        ]);
        $this->assertResponseStatus(201);

        // Invalid request
        $this->post(CardsTest::URL, [
            'number' => 'abc'
        ]);
        $this->assertResponseStatus(422);
    }

    /**
     * Updating card test
     */
    public function testUpdatingCard()
    {
        // Request without authentication
        $this->put(CardsTest::URL . '1', [
            'number' => '123456789'
        ]);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->put(CardsTest::URL . '1', [
            'number' => '123456789'
        ]);
        $this->assertResponseStatus(200);

        // Invalid request
        $this->put(CardsTest::URL . '1', [
            'number' => 'abc'
        ]);
        $this->assertResponseStatus(422);

        // Invalid request - card do not exists
        $this->put(CardsTest::URL . '4444', [
            'number' => '123456789'
        ]);
        $this->assertResponseStatus(404);
    }

    /**
     * Removing card test
     */
    public function testRemovingCard()
    {
        // Request without authentication
        $this->delete(CardsTest::URL . '1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->delete(CardsTest::URL . '1');
        $this->assertResponseStatus(204);

        // Invalid request - card don't exists
        $this->delete(CardsTest::URL . '4444');
        $this->assertResponseStatus(404);
    }
}
