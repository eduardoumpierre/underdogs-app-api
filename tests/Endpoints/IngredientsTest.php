<?php
/**
 * Created by PhpStorm.
 * User: Pichau
 * Date: 23/04/2018
 * Time: 15:19
 */

class IngredientsTest extends \TestCase
{
    const URL = '/api/v1/ingredients/';

    /**
     * Get all ingredients test
     */
    public function testGettingAllIngredients()
    {
        // Get all ingredients with the authenticated user
        $this->get(IngredientsTest::URL);
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'name', 'allergenic'
            ]
        ]);
    }

    /**
     * Get one ingredient test
     */
    public function testGettingSpecificIngredient()
    {
        // Get one ingredient
        $this->get(IngredientsTest::URL . '1');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'name', 'allergenic'
        ]);

        // Accessing invalid ingredient should give 404
        $this->get(IngredientsTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     * Creating ingredient test
     */
    public function testCreatingIngredient()
    {
        // Valid request
        $this->post(IngredientsTest::URL, [
            'name' => 'Ingrediente teste',
            'allergenic' => 1
        ]);
        $this->assertResponseStatus(201);

        // Invalid request - required fields are missing
        $this->post(IngredientsTest::URL, [
            'allergenic' => '1'
        ]);
        $this->assertResponseStatus(422);
    }

    /**
     * Updating ingredient test
     */
    public function testUpdatingIngredient()
    {
        // Valid request
        $this->put(IngredientsTest::URL . '1', [
            'name' => 'Ingrediente teste',
            'allergenic' => 1
        ]);
        $this->assertResponseStatus(200);

        // Invalid request - required fields are missing
        $this->put(IngredientsTest::URL . '1', [
            'category_id' => '1'
        ]);
        $this->assertResponseStatus(422);

        // Invalid request - ingredient do not exists
        $this->put(IngredientsTest::URL . '4444', [
            'name' => 'Ingrediente teste',
            'allergenic' => 1
        ]);
        $this->assertResponseStatus(404);
    }

    /**
     * Removing ingredient test
     */
    public function testRemovingIngredient()
    {
        // Valid request
        $this->delete(IngredientsTest::URL . '1');
        $this->assertResponseStatus(204);

        // Invalid request - ingredient don't exists
        $this->delete(IngredientsTest::URL . '4444');
        $this->assertResponseStatus(404);
    }
}