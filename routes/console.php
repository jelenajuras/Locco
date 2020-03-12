<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('email:Rodjendan', function () {
    $this->comment(Rodjendan::quote());
})->describe('Display an Rodjendan quote');

Artisan::command('email:Godisnjica', function () {
    $this->comment(Godisnjica::quote());
})->describe('Display an Godisnjica quote');

Artisan::command('email:Lijecnicki', function () {
    $this->comment(Lijecnicki::quote());
})->describe('Display an Lijecnicki quote');

Artisan::command('email:Lijecnicki1', function () {
    $this->comment(Lijecnicki1::quote());
})->describe('Display an Lijecnicki1 quote');

Artisan::command('email:Lijecnicki2', function () {
    $this->comment(Lijecnicki2::quote());
})->describe('Display an Lijecnicki2 quote');

Artisan::command('email:Probni', function () {
    $this->comment(Probni::quote());
})->describe('Display an Probni quote');

Artisan::command('email:Probni1', function () {
    $this->comment(Probni1::quote());
})->describe('Display an Probni1 quote');

Artisan::command('email:Probni2', function () {
    $this->comment(Probni2::quote());
})->describe('Display an Probni2 quote');

Artisan::command('email:GO', function () {
    $this->comment(GO::quote());
})->describe('Display an GO quote');

Artisan::command('email:Odjava', function () {
    $this->comment(Odjava::quote());
})->describe('Display an Odjava quote');

Artisan::command('email:Anketa', function () {
    $this->comment(Anketa::quote());
})->describe('Display an Anketa quote');

Artisan::command('email:Osposobljavanje', function () {
    $this->comment(Osposobljavanje::quote());
})->describe('Display an Osposobljavanje quote');

Artisan::command('email:Stranci', function () {
    $this->comment(Stranci::quote());
})->describe('Display an Stranci quote');

Artisan::command('email:task', function () {
    $this->comment(task::quote());
})->describe('Display an Stranci quote');