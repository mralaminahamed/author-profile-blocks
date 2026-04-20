<?php
declare(strict_types=1);

$FONT_BOLD    = '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf';
$FONT_REGULAR = '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf';
$OUT_DIR      = dirname(__DIR__, 2) . '/.wordpress-org';

@mkdir($OUT_DIR, 0755, true);

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------
function tw(Imagick $img, string $font, float $size, string $text): int {
    $d = new ImagickDraw();
    $d->setFont($font);
    $d->setFontSize($size);
    return (int) $img->queryFontMetrics($d, $text)['textWidth'];
}

function fit_font(Imagick $probe, string $font, float $size, string $text, int $maxW, float $step = 1.0): float {
    while ($size > 8 && tw($probe, $font, $size, $text) > $maxW) {
        $size -= $step;
    }
    return $size;
}

// ---------------------------------------------------------------------------
// Draw a person avatar: circle head + arc shoulders
// ---------------------------------------------------------------------------
function draw_avatar(ImagickDraw $d, int $cx, int $cy, int $r, string $color): void {
    // head
    $hr = (int)($r * 0.38);
    $d->setFillColor($color);
    $d->circle($cx, $cy - (int)($r * 0.18), $cx + $hr, $cy - (int)($r * 0.18));

    // shoulders (filled ellipse arc approximated as a fat ellipse)
    $d->ellipse($cx, $cy + (int)($r * 0.42), (int)($r * 0.62), (int)($r * 0.34), 190, 350);
}

// ---------------------------------------------------------------------------
// Banner generator
// ---------------------------------------------------------------------------
function gen_banner(int $W, int $H, string $file, string $fontBold, string $fontRegular): void {

    // ── Layout ───────────────────────────────────────────────────────────────
    $panelW   = (int)($W * 0.30);
    $sepW     = max(3, (int)($W * 0.004));
    $textX    = $panelW + $sepW + (int)($W * 0.034);
    $rightPad = (int)($W * 0.04);
    $maxTextW = $W - $textX - $rightPad;

    // ── Font sizes ───────────────────────────────────────────────────────────
    $probe = new Imagick();
    $probe->newImage(1, 1, new ImagickPixel('white'));

    $titleSize = fit_font($probe, $fontBold, (float)($H * 0.170), 'Author Profile Blocks', $maxTextW, 1.0);

    // Adaptive pill sizing — shrink until all 5 fit
    $labels = ['Profile Block', 'Author Grid', 'Team Cards', 'Social Links', 'Responsive'];
    $pillFontSize = round($titleSize * 0.40, 1);
    $pillH = $pillPad = $pillGap = $pillWidths = 0;
    while ($pillFontSize >= 8) {
        $pillH   = (int)($pillFontSize * 2.20);
        $pillPad = (int)($pillFontSize * 0.68);
        $pillGap = (int)($pillFontSize * 0.44);
        $pillWidths = [];
        $totalW = 0;
        foreach ($labels as $i => $lbl) {
            $pw = tw($probe, $fontRegular, $pillFontSize, $lbl) + $pillPad * 2;
            $pillWidths[] = $pw;
            $totalW += $pw + ($i < count($labels) - 1 ? $pillGap : 0);
        }
        if ($textX + $totalW <= $W - $rightPad) break;
        $pillFontSize -= 0.5;
    }
    $pillR = (int)($pillH / 2);
    $probe->destroy();

    // ── Vertical centring ────────────────────────────────────────────────────
    $ulGap      = (int)($titleSize * 0.18);
    $ulH        = max(2, (int)($titleSize * 0.10));
    $blockGap   = (int)($titleSize * 0.55);
    $titleCapH  = (int)($titleSize * 0.72);
    $blockH     = $titleCapH + $ulGap + $ulH + $blockGap + $pillH;
    $blockTop   = (int)(($H - $blockH) / 2);
    $titleY     = $blockTop + $titleCapH;
    $ulTop      = $titleY + $ulGap;
    $pillTop    = $ulTop + $ulH + $blockGap;
    $pillBaseline = $pillTop + (int)($pillH * 0.68);

    // ── Canvas: deep indigo-to-violet gradient ────────────────────────────────
    $img = new Imagick();
    $img->newPseudoImage($W, $H, 'gradient:#0f1729-#1a1f3a');
    $img->setImageFormat('png');

    // ── Dot-grid on right panel ───────────────────────────────────────────────
    $dotSpacing = 28;
    $dotR       = max(1, (int)round($dotSpacing * 0.07));
    $dot = new ImagickDraw();
    $dot->setFillColor('#ffffff');
    $dot->setFillOpacity(0.04);
    for ($x = $panelW + $sepW + $dotSpacing; $x < $W; $x += $dotSpacing) {
        for ($y = (int)($dotSpacing * 0.5); $y < $H; $y += $dotSpacing) {
            $dot->circle($x, $y, $x + $dotR, $y);
        }
    }
    $img->drawImage($dot);

    // ── Left panel ───────────────────────────────────────────────────────────
    $panel = new ImagickDraw();
    $panel->setFillColor('#090e1e');
    $panel->setStrokeWidth(0);
    $panel->rectangle(0, 0, $panelW, $H);
    $img->drawImage($panel);

    // Glow — violet/indigo tint
    $glow = new ImagickDraw();
    $gcx  = (int)($panelW / 2);
    $gcy  = (int)($H / 2);
    $maxR = (int)($panelW * 0.46);
    foreach ([1.00 => 0.035, 0.72 => 0.045, 0.48 => 0.040, 0.28 => 0.030, 0.12 => 0.015] as $frac => $opacity) {
        $gr = (int)($maxR * $frac);
        $glow->setFillColor('#6366f1');   // indigo
        $glow->setFillOpacity($opacity);
        $glow->circle($gcx, $gcy, $gcx + $gr, $gcy);
    }
    $glow->setFillOpacity(1.0);
    $img->drawImage($glow);

    // ── Accent separator ─────────────────────────────────────────────────────
    $sep = new ImagickDraw();
    $sep->setFillColor('#6366f1');
    $sep->rectangle($panelW, 0, $panelW + $sepW, $H);
    $img->drawImage($sep);

    // ── Icon: stacked profile cards ──────────────────────────────────────────
    $cW  = (int)($panelW * 0.70);
    $cH  = (int)($H * 0.52);
    $cR  = (int)(min($cW, $cH) * 0.10);
    $cX  = (int)(($panelW - $cW) / 2);
    $cY  = (int)(($H - $cH) / 2);
    $off = (int)($cH * 0.16);

    $cards = new ImagickDraw();

    // shadow
    $cards->setFillColor('#000000');
    $cards->setFillOpacity(0.22);
    $cards->roundRectangle($cX + $off + 3, $cY + $off + 4, $cX + $cW + $off + 3, $cY + $cH + $off + 4, $cR, $cR);
    $cards->setFillOpacity(1.0);

    // back card (darker indigo)
    $cards->setFillColor('#312e81');
    $cards->roundRectangle($cX + $off, $cY + $off, $cX + $cW + $off, $cY + $cH + $off, $cR, $cR);

    // front card
    $cards->setFillColor('#4f46e5');
    $cards->roundRectangle($cX, $cY, $cX + $cW, $cY + $cH, $cR, $cR);

    // shimmer
    $cards->setFillColor('#ffffff');
    $cards->setFillOpacity(0.10);
    $cards->roundRectangle($cX, $cY, $cX + $cW, $cY + (int)($cH * 0.28), $cR, $cR);
    $cards->setFillOpacity(1.0);

    // avatar circle on front card
    $avCx = $cX + (int)($cW * 0.28);
    $avCy = $cY + (int)($cH * 0.42);
    $avR  = (int)($cH * 0.20);
    $cards->setFillColor('#818cf8');  // lighter indigo
    $cards->circle($avCx, $avCy, $avCx + $avR, $avCy);

    // avatar person icon (white)
    $img->drawImage($cards);

    $ava = new ImagickDraw();
    $ava->setFillColor('#ffffff');
    draw_avatar($ava, $avCx, $avCy, $avR, '#ffffff');
    $img->drawImage($ava);

    // name line + subtitle lines on card
    $lines = new ImagickDraw();
    $lines->setFillColor('#ffffff');
    $lh = max(2, (int)($cH * 0.058));
    $lR = (int)($lh / 2);
    // name line (wider)
    $lx1 = $cX + (int)($cW * 0.54);
    $lx2 = $cX + (int)($cW * 0.90);
    $ly  = $cY + (int)($cH * 0.30);
    $lines->roundRectangle($lx1, $ly, $lx2, $ly + $lh, $lR, $lR);
    // subtitle lines (shorter)
    $lines->setFillColor('#c7d2fe');
    foreach ([0.46, 0.60] as $fy) {
        $ly2 = $cY + (int)($cH * $fy);
        $lx2s = $cX + (int)($cW * 0.82);
        $lines->roundRectangle($lx1, $ly2, $lx2s, $ly2 + $lh, $lR, $lR);
    }
    // social dots row
    $dotY = $cY + (int)($cH * 0.80);
    $dotSz = max(3, (int)($lh * 0.9));
    foreach ([0.30, 0.44, 0.58] as $fx) {
        $dx = $cX + (int)($cW * $fx);
        $lines->setFillColor('#a5b4fc');
        $lines->circle($dx, $dotY, $dx + $dotSz, $dotY);
    }
    $img->drawImage($lines);

    // ── Title ────────────────────────────────────────────────────────────────
    $dt = new ImagickDraw();
    $dt->setFont($fontBold);
    $dt->setFontSize($titleSize);
    $dt->setFillColor('#f8fafc');
    $dt->setTextAntialias(true);
    $img->annotateImage($dt, $textX, $titleY, 0, 'Author Profile Blocks');

    // underline
    $titleW = tw($img, $fontBold, $titleSize, 'Author Profile Blocks');
    $ul = new ImagickDraw();
    $ul->setFillColor('#6366f1');
    $ul->roundRectangle($textX, $ulTop, $textX + $titleW, $ulTop + $ulH, (int)($ulH / 2), (int)($ulH / 2));
    $img->drawImage($ul);

    // ── Pills ────────────────────────────────────────────────────────────────
    $strokePx = $W / 772.0;
    $px = $textX;
    foreach ($labels as $i => $label) {
        $pw = $pillWidths[$i];

        $bg = new ImagickDraw();
        $bg->setStrokeWidth(0);
        if ($i === 0) {
            $bg->setFillColor('#4f46e5');
        } else {
            $bg->setFillColor('#1e2545');
            $bg->setFillOpacity(0.55);
        }
        $bg->roundRectangle($px, $pillTop, $px + $pw, $pillTop + $pillH, $pillR, $pillR);
        $img->drawImage($bg);

        if ($i !== 0) {
            $border = new ImagickDraw();
            $border->setFillColor('none');
            $border->setStrokeColor('#4a5080');
            $border->setStrokeWidth($strokePx);
            $border->setStrokeAntialias(true);
            $border->roundRectangle($px, $pillTop, $px + $pw, $pillTop + $pillH, $pillR, $pillR);
            $img->drawImage($border);
        }

        $dp = new ImagickDraw();
        $dp->setFont($fontRegular);
        $dp->setFontSize($pillFontSize);
        $dp->setFillColor($i === 0 ? '#ffffff' : '#94a3b8');
        $dp->setTextAntialias(true);
        $img->annotateImage($dp, $px + $pillPad, $pillBaseline, 0, $label);

        $px += $pw + $pillGap;
    }

    $img->writeImage($file);
    $img->destroy();
    echo "  ✓ {$file}\n";
}

// ---------------------------------------------------------------------------
// Icon generator
// ---------------------------------------------------------------------------
function gen_icon(int $size, string $file): void {
    $img = new Imagick();
    $img->newImage($size, $size, new ImagickPixel('transparent'));
    $img->setImageFormat('png');

    $draw = new ImagickDraw();

    // rounded square bg
    $bgR = (int)($size * 0.22);
    $draw->setFillColor('#1e1b4b');
    $draw->roundRectangle(0, 0, $size - 1, $size - 1, $bgR, $bgR);

    $pad  = (int)($size * 0.13);
    $cW   = (int)($size * 0.74);
    $cH   = (int)($size * 0.66);
    $cR   = (int)(min($cW, $cH) * 0.10);
    $off  = (int)($cH * 0.15);

    // shadow
    $draw->setFillColor('#000000');
    $draw->setFillOpacity(0.22);
    $draw->roundRectangle($pad + $off + 2, $pad + $off + 3, $pad + $cW + $off + 2, $pad + $cH + $off + 3, $cR, $cR);
    $draw->setFillOpacity(1.0);

    // back card
    $draw->setFillColor('#312e81');
    $draw->roundRectangle($pad + $off, $pad + $off, $pad + $cW + $off, $pad + $cH + $off, $cR, $cR);

    // front card
    $frontY = $pad + (int)($size * 0.08);
    $draw->setFillColor('#4f46e5');
    $draw->roundRectangle($pad, $frontY, $pad + $cW, $frontY + $cH, $cR, $cR);

    // shimmer
    $draw->setFillColor('#ffffff');
    $draw->setFillOpacity(0.10);
    $draw->roundRectangle($pad, $frontY, $pad + $cW, $frontY + (int)($cH * 0.28), $cR, $cR);
    $draw->setFillOpacity(1.0);

    // avatar circle
    $avCx = $pad + (int)($cW * 0.28);
    $avCy = $frontY + (int)($cH * 0.44);
    $avR  = (int)($cH * 0.20);
    $draw->setFillColor('#818cf8');
    $draw->circle($avCx, $avCy, $avCx + $avR, $avCy);
    $img->drawImage($draw);

    // avatar person
    $ava = new ImagickDraw();
    $ava->setFillColor('#ffffff');
    draw_avatar($ava, $avCx, $avCy, $avR, '#ffffff');
    $img->drawImage($ava);

    // name + lines
    $lines = new ImagickDraw();
    $lines->setFillColor('#ffffff');
    $lh  = max(2, (int)($cH * 0.060));
    $lR  = (int)($lh / 2);
    $lx1 = $pad + (int)($cW * 0.52);
    $lx2 = $pad + (int)($cW * 0.92);
    $lines->roundRectangle($lx1, $frontY + (int)($cH * 0.30), $lx2, $frontY + (int)($cH * 0.30) + $lh, $lR, $lR);
    $lines->setFillColor('#c7d2fe');
    foreach ([0.46, 0.60] as $fy) {
        $ly = $frontY + (int)($cH * $fy);
        $lines->roundRectangle($lx1, $ly, $pad + (int)($cW * 0.84), $ly + $lh, $lR, $lR);
    }
    $img->drawImage($lines);

    $img->writeImage($file);
    $img->destroy();
    echo "  ✓ {$file}\n";
}

// ---------------------------------------------------------------------------
// Generate
// ---------------------------------------------------------------------------
echo "Generating banners & icons...\n";
gen_banner(772,  250, "{$OUT_DIR}/banner-772x250.png",  $FONT_BOLD, $FONT_REGULAR);
gen_banner(1544, 500, "{$OUT_DIR}/banner-1544x500.png", $FONT_BOLD, $FONT_REGULAR);
gen_icon(128, "{$OUT_DIR}/icon-128x128.png");
gen_icon(256, "{$OUT_DIR}/icon-256x256.png");
echo "Done.\n";
