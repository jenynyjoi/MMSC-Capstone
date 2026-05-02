<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class CaptchaController extends Controller
{
    // Characters that are unambiguous to read
    private const CHARS = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

    // ── Generate CAPTCHA image ────────────────────────────────
    public function image(): Response
    {
        $text = $this->generateText(5);
        session()->put('captcha_text', $text);

        $width  = 180;
        $height = 60;
        $img    = imagecreatetruecolor($width, $height);

        // Background — very light blue-grey
        $bg = imagecolorallocate($img, 246, 248, 252);
        imagefill($img, 0, 0, $bg);

        // Light grid
        $grid = imagecolorallocate($img, 225, 230, 240);
        for ($x = 0; $x < $width; $x += 12) {
            imageline($img, $x, 0, $x, $height, $grid);
        }
        for ($y = 0; $y < $height; $y += 12) {
            imageline($img, 0, $y, $width, $y, $grid);
        }

        // Noise dots
        for ($i = 0; $i < 400; $i++) {
            $c = imagecolorallocate($img, rand(160, 210), rand(160, 210), rand(160, 210));
            imagesetpixel($img, rand(0, $width - 1), rand(0, $height - 1), $c);
        }

        // Curved distraction lines
        for ($i = 0; $i < 5; $i++) {
            $c = imagecolorallocate($img, rand(170, 215), rand(170, 215), rand(185, 225));
            $x1 = rand(0, $width / 2);
            $y1 = rand(0, $height);
            $x2 = rand($width / 2, $width);
            $y2 = rand(0, $height);
            imageline($img, $x1, $y1, $x2, $y2, $c);
        }

        // Draw each character
        $fontPath = 'C:/Windows/Fonts/Arial.ttf';
        $useTTF   = function_exists('imagettftext') && file_exists($fontPath);

        for ($i = 0; $i < strlen($text); $i++) {
            $r = rand(10, 60);
            $g = rand(10, 70);
            $b = rand(120, 210);
            $color = imagecolorallocate($img, $r, $g, $b);

            if ($useTTF) {
                $size  = rand(22, 27);
                $angle = rand(-18, 18);
                $x     = 18 + $i * 32 + rand(-3, 3);
                $y     = rand(38, 48);
                imagettftext($img, $size, $angle, $x, $y, $color, $fontPath, $text[$i]);
            } else {
                // Fallback: built-in font 5 (9×15px)
                $x = 14 + $i * 32;
                $y = rand(12, 24);
                imagestring($img, 5, $x, $y, $text[$i], $color);
            }
        }

        // Border
        $border = imagecolorallocate($img, 210, 218, 230);
        imagerectangle($img, 0, 0, $width - 1, $height - 1, $border);

        ob_start();
        imagepng($img);
        $data = ob_get_clean();
        imagedestroy($img);

        return response($data, 200, [
            'Content-Type'  => 'image/png',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma'        => 'no-cache',
        ]);
    }

    // ── Verify submitted text ─────────────────────────────────
    public function verify(Request $request): RedirectResponse
    {
        $submitted = strtoupper(trim($request->input('captcha_input', '')));
        $expected  = strtoupper(session('captcha_text', ''));

        if (!$expected || $submitted !== $expected) {
            // Wrong — regenerate and show image section right away
            session()->put('show_captcha',       true);
            session()->put('captcha_show_image', true);
            session()->forget('captcha_text');   // image route will regenerate

            return redirect()->route('login')
                ->withErrors(['captcha' => 'Incorrect. Please try again.']);
        }

        // Correct — mark captcha as passed
        session()->put('captcha_cleared', true);
        session()->forget(['show_captcha', 'captcha_text', 'captcha_show_image']);

        return redirect()->route('login');
    }

    // ── Private ───────────────────────────────────────────────
    private function generateText(int $length): string
    {
        $chars = self::CHARS;
        $text  = '';
        for ($i = 0; $i < $length; $i++) {
            $text .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $text;
    }
}
