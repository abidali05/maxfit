<?php

use App\Services\AgeGroupParser;

it('parses a single age', function () {
    expect(AgeGroupParser::parse('14'))->toMatchArray([
        'min' => 14,
        'max' => 14,
        'single' => true,
    ]);
});

it('parses an age range', function () {
    expect(AgeGroupParser::parse('14-30'))->toMatchArray([
        'min' => 14,
        'max' => 30,
        'single' => false,
    ]);
});

it('infers genz for father fit ranges', function () {
    expect(AgeGroupParser::inferGenz('14-30'))->toBe('fatherfits');
});

