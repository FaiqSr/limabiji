<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

class ReCaptcha implements ValidationRule
{
    /**
     * Verify a Google reCAPTCHA v2 token against the siteverify endpoint.
     * When no secret key is configured the rule is a no-op, so the form
     * keeps working in local development before credentials are added.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = (string) config('services.recaptcha.secret_key');

        if (blank($secret)) {
            return;
        }

        if (blank($value)) {
            $fail('landing.contact_form_captcha_error');

            return;
        }

        try {
            $response = Http::asForm()
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secret,
                    'response' => $value,
                ])
                ->json();
        } catch (\Throwable $e) {
            $response = ['success' => false];
        }

        if (($response['success'] ?? false) !== true) {
            $fail('landing.contact_form_captcha_error');
        }
    }
}
