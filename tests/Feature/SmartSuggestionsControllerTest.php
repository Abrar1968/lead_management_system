<?php

use App\Models\Service;
use App\Models\User;

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->salesPerson = User::factory()->create(['role' => 'sales_person']);
    $this->service = Service::factory()->create();
});

test('it renders the smart suggestions dashboard for admin', function () {
    $response = $this->actingAs($this->admin)->get(route('smart-suggestions.index'));

    $response->assertStatus(200);
    $response->assertViewIs('smart-suggestions.index');
    $response->assertViewHasAll(['followUpSuggestions', 'assignmentRecommendations', 'unassignedLeads', 'stats']);
});

test('it renders the smart suggestions dashboard for sales person', function () {
    $response = $this->actingAs($this->salesPerson)->get(route('smart-suggestions.index'));

    $response->assertStatus(200);
    $response->assertViewIs('smart-suggestions.index');
});

test('it redirects unauthenticated users to login', function () {
    $response = $this->get(route('smart-suggestions.index'));

    $response->assertRedirect(route('login'));
});
