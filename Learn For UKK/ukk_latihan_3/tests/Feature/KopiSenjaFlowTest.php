<?php

use App\Services\CartService;
use App\Services\MenuService;

test('menu service can load all menus from json and find by id', function () {
    $menus = MenuService::all();
    expect($menus)->not->toBeEmpty();
    expect($menus->first())->toHaveKeys(['id', 'name', 'price', 'category', 'stock']);

    $firstMenu = $menus->first();
    $found = MenuService::find($firstMenu['id']);
    expect($found)->not->toBeNull();
    expect($found['name'])->toBe($firstMenu['name']);

    $notFound = MenuService::find(99999);
    expect($notFound)->toBeNull();
});

test('menus index page can be accessed and filtered', function () {
    $response = $this->get(route('menus.index'));
    $response->assertStatus(200);
    $response->assertSee('Menu Kopi Senja');

    // Test filter search
    $searchResponse = $this->get(route('menus.index', ['search' => 'Espresso']));
    $searchResponse->assertStatus(200);
    $searchResponse->assertSee('Espresso');

    // Test filter category
    $categoryResponse = $this->get(route('menus.index', ['category' => 'Kopi']));
    $categoryResponse->assertStatus(200);
    $categoryResponse->assertSee('Kopi');
});

test('menu show page can be accessed or returns 404', function () {
    $response = $this->get(route('menus.show', 1));
    $response->assertStatus(200);
    $response->assertSee('Espresso');

    $notFound = $this->get(route('menus.show', 99999));
    $notFound->assertStatus(404);
});

test('cart operations work according to module 14 cases', function () {
    // Menu 1 (Espresso, stock 20)
    $res = $this->post(route('cart.store', 1), ['quantity' => 2]);
    $res->assertSessionHas('success');
    $this->assertEquals(2, session('cart.1.quantity'));

    // Exceeding stock: stock is 20, already 2, trying to add 25
    $failRes = $this->post(route('cart.store', 1), ['quantity' => 25]);
    $failRes->assertSessionHas('error');

    // Update quantity (increase)
    $this->patch(route('cart.update', 1), ['action' => 'increase']);
    $this->assertEquals(3, session('cart.1.quantity'));

    // Update quantity (decrease)
    $this->patch(route('cart.update', 1), ['action' => 'decrease']);
    $this->assertEquals(2, session('cart.1.quantity'));

    // Test cart page
    $cartPage = $this->get(route('cart.index'));
    $cartPage->assertStatus(200);
    $cartPage->assertSee('Espresso');

    // Test promo code apply (HEMAT10)
    $promoRes = $this->post(route('promo.apply'), ['promo_code' => 'hemat10']);
    $promoRes->assertSessionHas('success');
    expect(session('promo_code'))->toBe('HEMAT10');

    // Test summary calculation
    $summary = CartService::summary();
    expect($summary['subtotal'])->toBeGreaterThan(0);
    expect($summary['service_charge'])->toBeGreaterThanOrEqual(0);
    expect($summary['tax'])->toBeGreaterThanOrEqual(0);
    expect($summary['grand_total'])->toBeGreaterThan(0);

    // Remove promo
    $this->delete(route('promo.remove'));
    expect(session('promo_code'))->toBeNull();

    // Delete item
    $delRes = $this->delete(route('cart.destroy', 1));
    $delRes->assertSessionHas('success');
    expect(session('cart'))->toBeEmpty();
});

test('checkout order process works according to module and redirects to receipt', function () {
    // Add item to cart
    $this->post(route('cart.store', 1), ['quantity' => 1]);

    $summary = CartService::summary();
    $grandTotal = $summary['grand_total'];

    // Checkout with insufficient cash
    $insufficientRes = $this->post(route('orders.store'), [
        'customer_name' => 'Budi Santoso',
        'table_number' => 5,
        'cash' => $grandTotal - 1000,
    ]);
    $insufficientRes->assertSessionHasErrors(['cash']);

    // Checkout with valid data
    $checkoutRes = $this->post(route('orders.store'), [
        'customer_name' => 'Budi Santoso',
        'table_number' => 5,
        'cash' => $grandTotal + 10000,
    ]);

    $checkoutRes->assertSessionHasNoErrors();
    $orders = session('orders', []);
    expect($orders)->not->toBeEmpty();
    $createdOrder = $orders[0];
    expect($createdOrder['customer_name'])->toBe('Budi Santoso');
    expect($createdOrder['table_number'])->toBe(5);
    expect($createdOrder['change'])->toBe(10000);

    // Cart and promo should now be empty
    expect(session('cart'))->toBeNull();
    expect(session('promo_code'))->toBeNull();

    // Check receipt page
    $receiptRes = $this->get(route('orders.show', $createdOrder['code']));
    $receiptRes->assertStatus(200);
    $receiptRes->assertSee($createdOrder['code']);
    $receiptRes->assertSee('Budi Santoso');

    // Check orders list page
    $ordersListRes = $this->get(route('orders.index'));
    $ordersListRes->assertStatus(200);
    $ordersListRes->assertSee($createdOrder['code']);
    $ordersListRes->assertSee('Budi Santoso');
});
