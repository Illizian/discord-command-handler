<?php

namespace Tests\Feature;

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
