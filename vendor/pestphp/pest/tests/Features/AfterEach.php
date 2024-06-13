<?php

$state = new stdClass();

beforeEach(function () use ($state) {
    $this->state = $state;
});

afterEach(function () {
    $this->state->bar = 1;
});

afterEach(function () {
    unset($this->state->bar);
});

it('does not get executed before the test', function () {
    expect($this->state)->not->toHaveProperty('bar');
});

it('gets executed after the test', function () {
    expect($this->state)->toHaveProperty('bar');
    expect($this->state->bar)->toBe(2);
});

afterEach(function () {
    $this->state->bar = 2;
});
