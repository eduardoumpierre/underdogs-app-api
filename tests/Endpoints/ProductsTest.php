<?php
/**
 * Created by PhpStorm.
 * User: Pichau
 * Date: 23/04/2018
 * Time: 15:19
 */

class ProductsTest extends \TestCase
{
    const URL = '/api/v1/products/';

    /**
     * Get all products test
     */
    public function testGettingAllProducts()
    {
        // Get all products with the authenticated user
        $this->get(ProductsTest::URL);
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'name', 'list'
            ]
        ]);
    }

    /**
     * Get one product test
     */
    public function testGettingSpecificProduct()
    {
        // Get one product
        $this->get(ProductsTest::URL . '1');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'name', 'price', 'experience'
        ]);

        // Accessing invalid product should give 404
        $this->get(ProductsTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     * Creating product test
     */
    public function testCreatingProduct()
    {
        // Valid request
        $this->post(ProductsTest::URL, [
            'name' => 'Produto teste',
            'price' => '19,90',
            'experience' => '15',
            'categories_id' => 1
        ]);
        $this->assertResponseStatus(201);

        // Invalid request - required fields are missing
        $this->post(ProductsTest::URL, [
            'category_id' => '1'
        ]);
        $this->assertResponseStatus(422);
    }

    /**
     * Updating product test
     */
    public function testUpdatingProduct()
    {
        // Valid request
        $this->put(ProductsTest::URL . '1', [
            'name' => 'Produto teste',
            'price' => '19,90',
            'experience' => '15',
            'categories_id' => 1
        ]);
        $this->assertResponseStatus(200);

        // Invalid request - required fields are missing
        $this->put(ProductsTest::URL . '1', [
            'category_id' => '1'
        ]);
        $this->assertResponseStatus(422);

        // Invalid request - product do not exists
        $this->put(ProductsTest::URL . '4444', [
            'name' => 'Produto teste',
            'price' => '19,90',
            'experience' => '15',
            'categories_id' => 1
        ]);
        $this->assertResponseStatus(404);
    }

    /**
     * Removing product test
     */
    public function testRemovingProduct()
    {
        // Valid request
        $this->delete(ProductsTest::URL . '1');
        $this->assertResponseStatus(204);

        // Invalid request - product don't exists
        $this->delete(ProductsTest::URL . '4444');
        $this->assertResponseStatus(404);
    }
}
