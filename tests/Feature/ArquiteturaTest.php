<?php

test('teste global', function () {
    expect(['dd', 'dump', 'ray', 'ds'])
    ->not->toBeUsed();
});
