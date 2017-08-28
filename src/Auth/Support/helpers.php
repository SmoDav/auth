<?php

use App\User;
use SmoDav\Auth\TwoFactor\Guard;

/**
 * Generate a two factor secret for a given user.
 *
 * @param User $user
 *
 * @return User
 */
function tfa_secret(User $user)
{
    return Guard::generateSecret($user);
}

/**
 * Generate QR Code URL for display.
 *
 * @param User $user
 *
 * @return mixed
 */
function tfa_url(User $user)
{
    return Guard::generateURL($user);
}

/**
 * Generate a Base64 String of the QR Code.
 *
 * @param User $user
 *
 * @return string
 */
function base64_tfa_url(User $user)
{
    return Guard::generateBase64QRString($user);
}

/**
 * Verify if the given key is correct.
 *
 * @param User $user
 * @param $code
 *
 * @return bool|mixed
 */
function verify_tfa(User $user, $code)
{
    return Guard::verify($user, $code);
}
