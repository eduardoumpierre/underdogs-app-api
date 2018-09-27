<?php

class BillsTest extends \TestCase
{
    const URL = '/api/v1/bills/';

    /**
     * Get all bills test
     */
    public function testGettingAllBills()
    {
        // Request without authentication
        $this->get(BillsTest::URL);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Get all bills with the authenticated user
        $this->get(BillsTest::URL);
        $this->assertResponseOk();

        $this->seeJsonStructure([
            '*' => [
                'id', 'name', 'number'
            ]
        ]);
    }

    /**
     * Get one bill test
     */
    public function testGettingSpecificBill()
    {
        // Request without authentication
        $this->get(BillsTest::URL . '1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        factory(\App\Bill::class)->create([
            'is_active' => true,
            'users_id' => $user->id
        ]);

        // Get one bill
        $this->get(BillsTest::URL . '1');
        $this->assertResponseStatus(200);

        $this->seeJsonStructure([
            'id', 'is_active', 'products', 'total', 'card', 'user'
        ]);

        // Accessing invalid bill should give 404
        $this->get(BillsTest::URL . '123456789');
        $this->assertResponseStatus(404);
    }

    /**
     * Creating bill test
     */
    public function testCreatingBill()
    {
        // Request without authentication
        $this->post(BillsTest::URL, [
            'users_id' => 1,
            'cards_id' => 1
        ]);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->post(BillsTest::URL, [
            'users_id' => 1,
            'cards_id' => 1
        ]);
        $this->assertResponseStatus(201);

        // Invalid request - required fields are missing
        $this->post(BillsTest::URL, [
            'users_id' => 1,
        ]);
        $this->assertResponseStatus(422);
    }

    /**
     * Updating bill test
     */
    public function testUpdatingBill()
    {
        // Request without authentication
        $this->put(BillsTest::URL . '1', [
            'users_id' => 1,
            'cards_id' => 1
        ]);
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->put(BillsTest::URL . '1', [
            'users_id' => 1,
            'cards_id' => 1
        ]);
        $this->assertResponseStatus(200);

        // Invalid request - required fields are missing
        $this->put(BillsTest::URL . '1', [
            'users_id' => 1,
        ]);
        $this->assertResponseStatus(422);

        // Invalid request - bill do not exists
        $this->put(BillsTest::URL . '4444', [
            'users_id' => 1,
            'cards_id' => 1
        ]);
        $this->assertResponseStatus(404);
    }

    /**
     * Removing bill test
     */
    public function testRemovingBill()
    {
        // Request without authentication
        $this->delete(BillsTest::URL . '1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->delete(BillsTest::URL . '1');
        $this->assertResponseStatus(204);

        // Invalid request - bill don't exists
        $this->delete(BillsTest::URL . '4444');
        $this->assertResponseStatus(404);
    }

    /**
     * Checkout test
     */
    public function testCheckout()
    {
        // Request without authentication
        $this->post(BillsTest::URL . 'checkout');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->post(BillsTest::URL . 'checkout', [
            'id' => 1
        ]);
        $this->assertResponseStatus(200);
    }

    /**
     * Add product to bill test
     */
    public function testAddProduct()
    {
        // Request without authentication
        $this->post(BillsTest::URL . 'products');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->post(BillsTest::URL . 'products', [
            'bills_id' => 1,
            'products' => [
                [
                    'products_id' => 1,
                    'note' => ''
                ]
            ]
        ]);
        $this->assertResponseStatus(201);
    }

    /**
     * Remove product from bill test
     */
    public function testRemoveProduct()
    {
        // Request without authentication
        $this->delete(BillsTest::URL . '1/products/1');
        $this->assertResponseStatus(401);

        // Authentication
        $user = factory(\App\User::class)->create();
        $this->actingAs($user);

        // Valid request
        $this->post(BillsTest::URL . 'products', [
            'bills_id' => 1,
            'products' => [
                [
                    'products_id' => 1,
                    'note' => ''
                ]
            ]
        ]);

        $this->delete(BillsTest::URL . '1/products/1');
        $this->assertResponseOk();
    }
}
