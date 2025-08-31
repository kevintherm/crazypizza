<?php

test('it can display ingredient list', function () {
    $response = $this->get(route('ingredients.dataTable'));

    $response->assertStatus(200);

    echo $response->getContent();
});
