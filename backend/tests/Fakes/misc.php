<?php

global $next_bga_rand;

function set_bga_rand($val) : int {
    global $next_bga_rand;
    return $next_bga_rand = $val;
}

function bga_rand($min,$max) : int {
    global $next_bga_rand;
    return $next_bga_rand % ($max+1-$min) + $min;
}