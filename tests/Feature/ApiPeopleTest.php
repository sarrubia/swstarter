<?php

it('returns a successful response', function () {
    $response = $this->get('/api/people?name=yoda');
    $response->assertStatus(200);
});


test('people name is required, returns a bad request response', function () {
    $response = $this->get('/api/people?name=');
    $response->assertStatus(400);
});
