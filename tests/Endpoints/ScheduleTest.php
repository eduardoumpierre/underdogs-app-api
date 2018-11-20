<?php

class ScheduleTest extends \TestCase
{
    const URL = '/api/v1/schedule/';

    /**
     * Get all events test
     */
    public function testGettingAllEvents()
    {
        factory(\App\Schedule::class)->create();

        // Get all events with the authenticated user
        $this->get(ScheduleTest::URL);
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'title', 'description', 'date'
            ]
        ]);
    }

    /**
     * Get one event test
     */
    public function testGettingSpecificEvent()
    {
        factory(\App\Schedule::class)->create();

        // Get one event
        $this->get(ScheduleTest::URL . '1');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'title', 'description', 'date'
        ]);

        // Accessing invalid event should give 404
        $this->get(ScheduleTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     * Creating event test
     */
    public function testCreatingEvent()
    {
        // Request without authentication
        $this->post(ScheduleTest::URL, [
            'title' => 'Nome do evento',
            'description' => 'Descricao teste',
            'date' => '2018-05-01'
        ]);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        factory(\App\Schedule::class)->create();

        // Valid request
        $this->post(ScheduleTest::URL, [
            'title' => 'Nome do evento',
            'description' => 'Descricao teste',
            'date' => '2018-05-01'
        ]);
        $this->assertResponseStatus(201);

        // Invalid request - required fields are missing
        $this->post(ScheduleTest::URL, [
            'title' => 'Nome do evento',
            'description' => 'Descricao teste'
        ]);
        $this->assertResponseStatus(422);
    }

    /**
     * Updating event test
     */
    public function testUpdatingEvent()
    {
        // Request without authentication
        $this->put(ScheduleTest::URL . '1', [
            'title' => 'Nome do evento',
            'description' => 'Descricao teste',
            'date' => '2018-05-01'
        ]);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        factory(\App\Schedule::class)->create();

        // Valid request
        $this->put(ScheduleTest::URL . '1', [
            'title' => 'Nome do evento',
            'description' => 'Descricao teste',
            'date' => '2018-05-01'
        ]);
        $this->assertResponseStatus(200);

        // Invalid request - required fields are missing
        $this->put(ScheduleTest::URL . '1', [
            'title' => 'Nome do evento',
            'description' => 'Descricao teste',
        ]);
        $this->assertResponseStatus(422);

        // Invalid request - event do not exists
        $this->put(ScheduleTest::URL . '4444', [
            'title' => 'Nome do evento',
            'description' => 'Descricao teste',
            'date' => '2018-05-01'
        ]);
        $this->assertResponseStatus(404);
    }

    /**
     * Removing event test
     */
    public function testRemovingEvent()
    {
        // Request without authentication
        $this->delete(ScheduleTest::URL . '1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        factory(\App\Schedule::class)->create();

        // Valid request
        $this->delete(ScheduleTest::URL . '1');
        $this->assertResponseStatus(204);

        // Invalid request - event don't exists
        $this->delete(ScheduleTest::URL . '4444');
        $this->assertResponseStatus(404);
    }
}
