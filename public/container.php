<?php

require '../vendor/autoload.php';

use Pimple\Container;

$ioc = new Container();

$ioc['ConnectionProvider'] = function($c) {
    return new \App\Provider\ConnectionProvider();
};

$ioc['CommandProvider'] = function($c) {
    return new \App\Provider\CommandProvider($c['ConnectionProvider']);
};

$ioc['AstronautRepository'] = function($c) {
    return new \App\Repository\AstronautRepository(
        $c['CommandProvider']
    );
};

$ioc['PersonRepository'] = function($c) {
    return new \App\Repository\PersonRepository(
        $c['CommandProvider']
    );
};

$ioc['CourseRepository'] = function($c) {
    return new \App\Repository\CourseRepository(
        $c['CommandProvider']
    );
};

$ioc['AstronautService'] = function($c) {
    return new \App\Service\AstronautService(
        $c['AstronautRepository']
    );
};

$ioc['CourseService'] = function($c) {
    return new \App\Service\CourseService(
        $c['CourseRepository']
    );
};

$ioc['PersonService'] = function($c) {
    return new \App\Service\PersonService(
        $c['PersonRepository']
    );
};