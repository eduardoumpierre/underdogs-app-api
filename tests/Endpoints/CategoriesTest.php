<?php
/**
 * Created by PhpStorm.
 * User: Pichau
 * Date: 23/04/2018
 * Time: 15:19
 */

class CategoriesTest extends \TestCase
{
    const URL = '/api/v1/categories/';

    /**
     * Get all categories test
     */
    public function testGettingAllCategories()
    {
        // Request without authentication
        $this->get(CategoriesTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Get all categories with the authenticated user
        $this->get(CategoriesTest::URL);
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'name'
            ]
        ]);
    }

    /**
     * Get one category test
     */
    public function testGettingSpecificCategory()
    {
        // Request without authentication
        $this->get(CategoriesTest::URL . '1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Get one category
        $this->get(CategoriesTest::URL . '1');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'name'
        ]);

        // Accessing invalid category should give 404
        $this->get(CategoriesTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     * Creating category test
     */
    public function testCreatingCategory()
    {
        // Request without authentication
        $this->post(CategoriesTest::URL, [
            'name' => 'Category teste'
        ]);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->post(CategoriesTest::URL, [
            'name' => 'Category teste'
        ]);
        $this->assertResponseStatus(201);

        // Invalid request - required fields are missing
        $this->post(CategoriesTest::URL, [
            'experience' => '1'
        ]);
        $this->assertResponseStatus(422);
    }

    /**
     * Updating category test
     */
    public function testUpdatingCategory()
    {
        // Request without authentication
        $this->put(CategoriesTest::URL . '1', [
            'name' => 'Category teste'
        ]);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->put(CategoriesTest::URL . '1', [
            'name' => 'Category teste'
        ]);
        $this->assertResponseStatus(200);

        // Invalid request - required fields are missing
        $this->put(CategoriesTest::URL . '1', []);
        $this->assertResponseStatus(422);

        // Invalid request - category do not exists
        $this->put(CategoriesTest::URL . '4444', [
            'name' => 'Category teste'
        ]);
        $this->assertResponseStatus(404);
    }

    /**
     * Removing category test
     */
    public function testRemovingCategory()
    {
        // Request without authentication
        $this->delete(CategoriesTest::URL . '1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->delete(CategoriesTest::URL . '1');
        $this->assertResponseStatus(204);

        // Invalid request - category don't exists
        $this->delete(CategoriesTest::URL . '4444');
        $this->assertResponseStatus(404);
    }
}
