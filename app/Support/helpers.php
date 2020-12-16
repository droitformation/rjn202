<?php

function generatePassword() {
    return date('y').rand(pow(10,7),pow(10,8)-1);
}

function getRandomPasswords($nbr){
    $random = [];

    foreach (range(1,$nbr) as $nbr){
        $random[] = generatePassword();
    }

    return $random;
}
