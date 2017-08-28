<?php

namespace SmoDav\Auth\TwoFactor;

use App\User;
use BaconQrCode\Renderer\Image\Png;
use BaconQrCode\Writer;
use PragmaRX\Google2FA\Google2FA;
use SmoDav\Auth\Models\RecoveryKey;

/**
 * Class Guard.
 *
 * @category PHP
 *
 * @author   David Mjomba <smodavprivate@gmail.com>
 */
class Guard
{
    /**
     * Get the Google2FA instance.
     *
     * @return Google2FA
     */
    private static function get2FA()
    {
        $auth = new Google2FA;
        $auth->setWindow(config('sauth.window'));

        return $auth;
    }

    /**
     * Generate a two factor secret for a given user.
     *
     * @param User $user
     *
     * @return User
     */
    public static function generateSecret(User $user)
    {
        $user->auth_2fa = self::get2FA()->generateSecretKey(32, strtoupper(str_pad(uniqid(), 16, 'X')));
        $user->has_2fa = true;
        $user->save();
        $user = self::generateRecoveryKeys($user);

        return $user;
    }

    /**
     * Generate recovery keys for the 2FA User.
     *
     * @param User $user
     *
     * @return User
     */
    private static function generateRecoveryKeys(User $user)
    {
        RecoveryKey::where('user_id', $user->id)->delete();

        $keys = [];

        for ($i = 0; $i < 10; $i++) {
            $key = rand(100000, rand(100001, 999999));
            $keys[] = [
                'user_id' => $user->id,
                'key' => $key,
            ];
        }

        RecoveryKey::insert($keys);
        $user->keys = $keys;

        return $user;
    }

    /**
     * Generate QR Code URL for display.
     *
     * @param User $user
     *
     * @return mixed
     */
    public static function generateURL(User $user)
    {
        if (! $user->auth_2fa) {
            $user = self::generateSecret($user);
        }

        return self::get2FA()->getQRCodeUrl(config('sauth.name'), $user->email, $user->auth_2fa);
    }

    /**
     * Generate a Base64 String of the QR Code.
     *
     * @param User $user
     * @param int  $size
     *
     * @return string
     */
    public static function generateBase64QRString(User $user, $size = 200)
    {
        $url = self::generateURL($user);

        $render = new Png();
        $render->setHeight($size);
        $render->setWidth($size);
        $writer = new Writer($render);

        $string = $writer->writeString($url);

        return 'data:image/png;base64,' . base64_encode($string);
    }

    /**
     * Verify if the given key is correct.
     *
     * @param User $user
     * @param $key
     *
     * @return bool|mixed
     */
    public static function verify(User $user, $key)
    {
        if (! config('sauth.strict')) {
            return self::verifyNonStrict($user, $key);
        }

        return self::verifyStrict($user, $key);
    }

    /**
     * Verify using non-strict method.
     *
     * @param User $user
     * @param $key
     *
     * @return mixed
     */
    protected static function verifyNonStrict(User $user, $key)
    {
        $user->completed_login = false;
        $verified = self::get2FA()->verifyKey($user->auth_2fa, $key);

        if ($verified) {
            $user->completed_login = true;
        }

        $user->save();

        return $verified;
    }

    /**
     * Verify using strict method.
     *
     * @param User $user
     * @param $key
     *
     * @return bool
     */
    protected static function verifyStrict(User $user, $key)
    {
        $user->completed_login = false;
        $timestamp = self::get2FA()->verifyKeyNewer($user->auth_2fa, $key, $user->last_auth_2fa);

        if (!$timestamp) {
            $user->save();

            return false;
        }

        $user->last_auth_2fa = $timestamp;
        $user->completed_login = true;
        $user->save();

        return true;
    }
}
