<?php
/**
 * Created by PhpStorm.
 * User: Pichau
 * Date: 23/04/2018
 * Time: 15:19
 */

class BadgesTest extends \TestCase
{
    const URL = '/api/v1/badges/';

    /**
     * Get all badges test
     */
    public function testGettingAllBadges()
    {
        // Get all badges with the authenticated user
        $this->get(BadgesTest::URL);
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'name', 'experience'
            ]
        ]);
    }

    /**
     * Get one badge test
     */
    public function testGettingSpecificBadge()
    {
        // Get one badge
        $this->get(BadgesTest::URL . '1');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'name', 'experience'
        ]);

        // Accessing invalid badge should give 404
        $this->get(BadgesTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     * Creating badge test
     */
    public function testCreatingBadge()
    {
        // Valid request
        $this->post(BadgesTest::URL, [
            'name' => 'Badge teste',
            'experience' => 10
        ]);
        $this->assertResponseStatus(201);

        // Invalid request - required fields are missing
        $this->post(BadgesTest::URL, [
            'experience' => '1'
        ]);
        $this->assertResponseStatus(422);
    }

    /**
     * Updating badge test
     */
    public function testUpdatingBadge()
    {
        // Valid request
        $this->put(BadgesTest::URL . '1', [
            'name' => 'Badge teste',
            'experience' => 10
        ]);
        $this->assertResponseStatus(200);

        // Invalid request - required fields are missing
        $this->put(BadgesTest::URL . '1', [
            'category_id' => '1'
        ]);
        $this->assertResponseStatus(422);

        // Invalid request - badge do not exists
        $this->put(BadgesTest::URL . '4444', [
            'name' => 'Badge teste',
            'experience' => 10
        ]);
        $this->assertResponseStatus(404);
    }

    /**
     * Removing badge test
     */
    public function testRemovingBadge()
    {
        // Valid request
        $this->delete(BadgesTest::URL . '1');
        $this->assertResponseStatus(204);

        // Invalid request - badge don't exists
        $this->delete(BadgesTest::URL . '4444');
        $this->assertResponseStatus(404);
    }
}