<?php

namespace App\Tests\api;

use App\Entity\Product;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;
use function PHPUnit\Framework\assertEquals;

class ProductCest
{
    public function _before(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
    }

    public function testIndex(ApiTester $I): void
    {
        $I->seeNumRecords(1, Product::class);
        /** @var Product $product */
        $product = $I->grabEntityFromRepository(Product::class, ['id' => 1]);
        assertEquals(1, $product->getId());
        assertEquals('First', $product->getName());

        $I->sendGet('/product/');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'total'   => 1,
            'page'    => 1,
            'perPage' => 3,
            'items'   => [
                [
                    'id'   => $product->getId(),
                    'name' => $product->getName()
                ]
            ],
        ]);
    }

    public function testPost(ApiTester $I, $newId = 2): void
    {
        $I->seeNumRecords(1, Product::class);

        $I->sendPost('/product/', [
            'name'     => 'testProduct',
            'quantity' => 12
        ]);
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();

        /** @var Product $product */
        $product = $I->grabEntityFromRepository(Product::class, ['id' => $newId]);
        assertEquals($newId, $product->getId());
        assertEquals('testProduct', $product->getName());
        assertEquals(12, $product->getQuantity());

        $I->seeResponseContainsJson([
            'id'       => $product->getId(),
            'name'     => $product->getName(),
            'quantity' => $product->getQuantity()
        ]);

        $I->seeNumRecords(2, Product::class);
    }

    public function testPostValidationFailed(ApiTester $I): void
    {
        $I->sendPost('/product/', [
            'name' => '',
        ]);
        $I->seeResponseCodeIs(HttpCode::EXPECTATION_FAILED);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name'     => [
                "This value is too short. It should have 3 characters or more.",
                "This value should not be blank."
            ],
            'quantity' => [
                "This value should not be blank."
            ]
        ]);
    }

    public function testIndexAfterPost(ApiTester $I): void
    {
        $this->testPost($I, 3);

        $I->sendGet('/product/');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'total'   => 2,
            'page'    => 1,
            'perPage' => 3,
            'items'   => [
                [
                    'id'   => 1,
                    'name' => 'First'
                ],
                [
                    'id'   => 3,
                    'name' => 'testProduct'
                ]
            ],
        ]);
    }

    public function testGetSingle(ApiTester $I): void
    {
        /** @var Product $product */
        $product = $I->grabEntityFromRepository(Product::class, ['id' => 1]);

        assertEquals(1, $product->getId());
        assertEquals('First', $product->getName());
        assertEquals(100, $product->getQuantity());

        $I->sendGet('/product/' . $product->getId());

        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name'     => $product->getName(),
            'quantity' => $product->getQuantity()
        ]);
    }

    public function testGetSingleNotExistent(ApiTester $I): void
    {
        $I->sendGet('/product/0');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([]);
    }

    public function testPut(ApiTester $I): void
    {
        $I->sendPut('/product/1', [
            'name'     => 'updated',
            'quantity' => 12,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name'     => 'updated',
            'quantity' => 12
        ]);

        /** @var Product $updated */
        $updated = $I->grabEntityFromRepository(Product::class, ['id' => 1]);

        assertEquals(1, $updated->getId());
        assertEquals('updated', $updated->getName());
        assertEquals(12, $updated->getQuantity());
    }

    public function testPutNotExistent(ApiTester $I): void
    {
        $I->sendPut('/product/0', [
            'name' => 'testName',
            'quantity' => 12
        ]);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([]);
    }

    public function testPutInvalidData(ApiTester $I): void
    {
        $I->sendPut('/product/1', [
            'name' => '',
        ]);
        $I->seeResponseCodeIs(HttpCode::EXPECTATION_FAILED);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name'     => [
                "This value is too short. It should have 3 characters or more.",
                "This value should not be blank."
            ],
            'quantity' => [
                "This value should not be blank."
            ]
        ]);
    }

    public function testPatch(ApiTester $I): void
    {
        $I->sendPatch('/product/1', [
            'quantity' => 12,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name'     => 'First',
            'quantity' => 12
        ]);

        /** @var Product $updated */
        $updated = $I->grabEntityFromRepository(Product::class, ['id' => 1]);

        assertEquals(1, $updated->getId());
        assertEquals('First', $updated->getName());
        assertEquals(12, $updated->getQuantity());
    }

    public function testPatchNotExistent(ApiTester $I): void
    {
        $I->sendPatch('/product/0', [
            'quantity' => 12
        ]);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([]);
    }

    public function testPatchInvalidData(ApiTester $I): void
    {
        $I->sendPatch('/product/1', [
            'name' => ''
        ]);
        $I->seeResponseCodeIs(HttpCode::EXPECTATION_FAILED);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'name'     => [
                "This value is too short. It should have 3 characters or more.",
                "This value should not be blank."
            ],
        ]);
    }

    public function testDelete(ApiTester $I): void
    {
        $I->sendDelete('/product/1');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $I->dontSeeInRepository(Product::class, ['id' => 1]);
    }

    public function testDeleteNotExistent(ApiTester $I): void
    {
        $I->sendDelete('/product/0');
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([]);
    }
}
