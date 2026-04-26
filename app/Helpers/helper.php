<?php

if (!function_exists('greet')) {
    function greet($name) {
        return "Hello, {$name}!";
    }
}