<?php

it('redirects to login when unauthenticated', function () {
    $response = $this->get('/');

    $response->assertRedirect('/login');
});

it('redirects to dashboard when authenticated', function () {
    $user = \App\Models\User::factory()->create();

    $response = $this->actingAs($user)->get('/');

    $response->assertRedirect('/dashboard');
});
