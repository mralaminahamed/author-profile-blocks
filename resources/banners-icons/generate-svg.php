<?php
declare(strict_types=1);

/**
 * Generates SVG versions of all WordPress.org plugin assets.
 * Design matches the PNG generator (generate.php).
 */

$OUT_DIR = dirname(__DIR__, 2) . '/.wordpress-org';

// ---------------------------------------------------------------------------
// Banner SVG
// ---------------------------------------------------------------------------
function banner_svg(int $W, int $H): string {
    $panelW = (int)($W * 0.30);
    $sepW   = max(3, (int)($W * 0.004));
    $textX  = $panelW + $sepW + (int)($W * 0.034);

    // Card geometry
    $cW  = (int)($panelW * 0.70);
    $cH  = (int)($H * 0.52);
    $cR  = (int)(min($cW, $cH) * 0.10);
    $cX  = (int)(($panelW - $cW) / 2);
    $cY  = (int)(($H - $cH) / 2);
    $off = (int)($cH * 0.16);

    // Title font size (approximate — will scale with SVG viewBox)
    $titleSize   = (int)($H * 0.160);
    $subtitleSize = (int)($H * 0.062);
    $pillFontSize = (int)($H * 0.060);
    $pillH       = (int)($pillFontSize * 2.20);
    $pillR       = (int)($pillH / 2);
    $pillPad     = (int)($pillFontSize * 0.68);
    $pillGap     = (int)($pillFontSize * 0.44);

    // Vertical layout
    $ulH       = max(2, (int)($titleSize * 0.10));
    $titleCapH = (int)($titleSize * 0.72);
    $blockH    = $titleCapH + (int)($titleSize * 0.18) + $ulH + (int)($titleSize * 0.55) + $pillH;
    $blockTop  = (int)(($H - $blockH) / 2);
    $titleY    = $blockTop + $titleCapH;
    $ulTop     = $titleY + (int)($titleSize * 0.18);
    $pillTop   = $ulTop + $ulH + (int)($titleSize * 0.55);

    // Avatar centre
    $avCx = $cX + (int)($cW * 0.28);
    $avCy = $cY + (int)($cH * 0.42);
    $avR  = (int)($cH * 0.20);
    $headR = (int)($avR * 0.38);

    // Pills
    $pills = [
        ['Profile Block', true],
        ['Author Grid',   false],
        ['Team Cards',    false],
        ['Social Links',  false],
        ['Responsive',    false],
    ];
    // Approximate char widths at given font size (Liberation Sans Regular ~0.55× em per char)
    $charW = $pillFontSize * 0.555;
    $pillsData = [];
    $px = $textX;
    foreach ($pills as [$label, $filled]) {
        $pw = (int)(strlen($label) * $charW) + $pillPad * 2;
        $pillsData[] = [$label, $filled, $px, $pw];
        $px += $pw + $pillGap;
    }

    // Dot grid pattern spacing
    $dotSpacing = 28;
    $dotR = max(1, (int)round($dotSpacing * 0.07));

    ob_start(); ?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 <?= $W ?> <?= $H ?>" width="<?= $W ?>" height="<?= $H ?>">
  <defs>
    <!-- Background gradient -->
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0f1729"/>
      <stop offset="100%" stop-color="#1a1f3a"/>
    </linearGradient>
    <!-- Panel glow -->
    <radialGradient id="glow" cx="50%" cy="50%" r="50%">
      <stop offset="0%" stop-color="#6366f1" stop-opacity="0.22"/>
      <stop offset="100%" stop-color="#6366f1" stop-opacity="0"/>
    </radialGradient>
    <!-- Card shimmer -->
    <linearGradient id="shimmer" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#ffffff" stop-opacity="0.12"/>
      <stop offset="100%" stop-color="#ffffff" stop-opacity="0"/>
    </linearGradient>
    <!-- Dot pattern -->
    <pattern id="dots" x="<?= $panelW + $sepW ?>" y="0" width="<?= $dotSpacing ?>" height="<?= $dotSpacing ?>" patternUnits="userSpaceOnUse">
      <circle cx="<?= $dotSpacing/2 ?>" cy="<?= $dotSpacing/2 ?>" r="<?= $dotR ?>" fill="#ffffff" opacity="0.04"/>
    </pattern>
    <!-- Shadow filter -->
    <filter id="shadow" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="2" dy="3" stdDeviation="4" flood-color="#000000" flood-opacity="0.22"/>
    </filter>
  </defs>

  <!-- Background -->
  <rect width="<?= $W ?>" height="<?= $H ?>" fill="url(#bg)"/>

  <!-- Dot grid (right panel) -->
  <rect x="<?= $panelW + $sepW ?>" y="0" width="<?= $W - $panelW - $sepW ?>" height="<?= $H ?>" fill="url(#dots)"/>

  <!-- Left panel -->
  <rect x="0" y="0" width="<?= $panelW ?>" height="<?= $H ?>" fill="#090e1e"/>
  <!-- Glow -->
  <ellipse cx="<?= $panelW/2 ?>" cy="<?= $H/2 ?>" rx="<?= $panelW * 0.46 ?>" ry="<?= $panelW * 0.46 ?>" fill="url(#glow)"/>

  <!-- Separator -->
  <rect x="<?= $panelW ?>" y="0" width="<?= $sepW ?>" height="<?= $H ?>" fill="#6366f1"/>

  <!-- Back card -->
  <rect x="<?= $cX+$off ?>" y="<?= $cY+$off ?>" width="<?= $cW ?>" height="<?= $cH ?>" rx="<?= $cR ?>" fill="#312e81" filter="url(#shadow)"/>
  <!-- Front card -->
  <rect x="<?= $cX ?>" y="<?= $cY ?>" width="<?= $cW ?>" height="<?= $cH ?>" rx="<?= $cR ?>" fill="#4f46e5"/>
  <!-- Shimmer -->
  <rect x="<?= $cX ?>" y="<?= $cY ?>" width="<?= $cW ?>" height="<?= (int)($cH*0.28) ?>" rx="<?= $cR ?>" fill="url(#shimmer)"/>

  <!-- Avatar circle -->
  <circle cx="<?= $avCx ?>" cy="<?= $avCy ?>" r="<?= $avR ?>" fill="#818cf8"/>
  <!-- Head -->
  <circle cx="<?= $avCx ?>" cy="<?= $avCy - (int)($avR*0.18) ?>" r="<?= $headR ?>" fill="#ffffff"/>
  <!-- Shoulders -->
  <ellipse cx="<?= $avCx ?>" cy="<?= $avCy + (int)($avR*0.42) ?>" rx="<?= (int)($avR*0.60) ?>" ry="<?= (int)($avR*0.32) ?>" fill="#ffffff"/>

  <!-- Name line -->
  <?php $lh = max(2, (int)($cH * 0.058)); $lR2 = (int)($lh/2); ?>
  <?php $lx1 = $cX + (int)($cW * 0.54); $lx2 = $cX + (int)($cW * 0.90); ?>
  <rect x="<?= $lx1 ?>" y="<?= $cY + (int)($cH*0.30) ?>" width="<?= $lx2-$lx1 ?>" height="<?= $lh ?>" rx="<?= $lR2 ?>" fill="#ffffff"/>
  <!-- Subtitle lines -->
  <rect x="<?= $lx1 ?>" y="<?= $cY + (int)($cH*0.46) ?>" width="<?= $cX + (int)($cW*0.82) - $lx1 ?>" height="<?= $lh ?>" rx="<?= $lR2 ?>" fill="#c7d2fe"/>
  <rect x="<?= $lx1 ?>" y="<?= $cY + (int)($cH*0.60) ?>" width="<?= $cX + (int)($cW*0.82) - $lx1 ?>" height="<?= $lh ?>" rx="<?= $lR2 ?>" fill="#c7d2fe"/>
  <!-- Social dots -->
  <?php foreach ([0.30, 0.44, 0.58] as $fx): $dotSz = max(3, (int)($lh * 0.9)); $dx = $cX + (int)($cW * $fx); ?>
  <circle cx="<?= $dx ?>" cy="<?= $cY + (int)($cH*0.80) ?>" r="<?= $dotSz ?>" fill="#a5b4fc"/>
  <?php endforeach; ?>

  <!-- Title -->
  <text x="<?= $textX ?>" y="<?= $titleY ?>"
        font-family="Liberation Sans, Arial, sans-serif" font-weight="bold"
        font-size="<?= $titleSize ?>" fill="#f8fafc">Author Profile Blocks</text>

  <!-- Underline -->
  <?php $titleW = (int)(strlen('Author Profile Blocks') * $titleSize * 0.575); ?>
  <rect x="<?= $textX ?>" y="<?= $ulTop ?>" width="<?= $titleW ?>" height="<?= $ulH ?>" rx="<?= (int)($ulH/2) ?>" fill="#6366f1"/>

  <!-- Pills -->
  <?php foreach ($pillsData as [$label, $filled, $px2, $pw]): ?>
  <?php if ($filled): ?>
    <rect x="<?= $px2 ?>" y="<?= $pillTop ?>" width="<?= $pw ?>" height="<?= $pillH ?>" rx="<?= $pillR ?>" fill="#4f46e5"/>
    <text x="<?= $px2 + $pillPad ?>" y="<?= $pillTop + (int)($pillH*0.68) ?>"
          font-family="Liberation Sans, Arial, sans-serif" font-size="<?= $pillFontSize ?>" fill="#ffffff"><?= htmlspecialchars($label) ?></text>
  <?php else: ?>
    <rect x="<?= $px2 ?>" y="<?= $pillTop ?>" width="<?= $pw ?>" height="<?= $pillH ?>" rx="<?= $pillR ?>"
          fill="#1e2545" fill-opacity="0.55" stroke="#4a5080" stroke-width="1"/>
    <text x="<?= $px2 + $pillPad ?>" y="<?= $pillTop + (int)($pillH*0.68) ?>"
          font-family="Liberation Sans, Arial, sans-serif" font-size="<?= $pillFontSize ?>" fill="#94a3b8"><?= htmlspecialchars($label) ?></text>
  <?php endif; ?>
  <?php endforeach; ?>
</svg>
<?php
    return (string) ob_get_clean();
}

// ---------------------------------------------------------------------------
// Icon SVG
// ---------------------------------------------------------------------------
function icon_svg(int $size): string {
    $bgR  = (int)($size * 0.22);
    $pad  = (int)($size * 0.13);
    $cW   = (int)($size * 0.74);
    $cH   = (int)($size * 0.66);
    $cR   = (int)(min($cW, $cH) * 0.10);
    $off  = (int)($cH * 0.15);
    $frontY = $pad + (int)($size * 0.08);

    $avCx = $pad + (int)($cW * 0.28);
    $avCy = $frontY + (int)($cH * 0.44);
    $avR  = (int)($cH * 0.20);
    $headR = (int)($avR * 0.38);

    $lh  = max(2, (int)($cH * 0.060));
    $lR  = (int)($lh / 2);
    $lx1 = $pad + (int)($cW * 0.52);
    $lx2 = $pad + (int)($cW * 0.92);

    ob_start(); ?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 <?= $size ?> <?= $size ?>" width="<?= $size ?>" height="<?= $size ?>">
  <defs>
    <linearGradient id="shimmer-<?= $size ?>" x1="0%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="#ffffff" stop-opacity="0.12"/>
      <stop offset="100%" stop-color="#ffffff" stop-opacity="0"/>
    </linearGradient>
    <filter id="shadow-<?= $size ?>" x="-20%" y="-20%" width="140%" height="140%">
      <feDropShadow dx="1" dy="2" stdDeviation="3" flood-color="#000000" flood-opacity="0.22"/>
    </filter>
  </defs>

  <!-- Background -->
  <rect width="<?= $size ?>" height="<?= $size ?>" rx="<?= $bgR ?>" fill="#1e1b4b"/>

  <!-- Back card -->
  <rect x="<?= $pad+$off ?>" y="<?= $pad+$off ?>" width="<?= $cW ?>" height="<?= $cH ?>" rx="<?= $cR ?>"
        fill="#312e81" filter="url(#shadow-<?= $size ?>)"/>

  <!-- Front card -->
  <rect x="<?= $pad ?>" y="<?= $frontY ?>" width="<?= $cW ?>" height="<?= $cH ?>" rx="<?= $cR ?>" fill="#4f46e5"/>
  <!-- Shimmer -->
  <rect x="<?= $pad ?>" y="<?= $frontY ?>" width="<?= $cW ?>" height="<?= (int)($cH*0.28) ?>" rx="<?= $cR ?>"
        fill="url(#shimmer-<?= $size ?>)"/>

  <!-- Avatar circle -->
  <circle cx="<?= $avCx ?>" cy="<?= $avCy ?>" r="<?= $avR ?>" fill="#818cf8"/>
  <!-- Head -->
  <circle cx="<?= $avCx ?>" cy="<?= $avCy - (int)($avR*0.18) ?>" r="<?= $headR ?>" fill="#ffffff"/>
  <!-- Shoulders -->
  <ellipse cx="<?= $avCx ?>" cy="<?= $avCy + (int)($avR*0.42) ?>" rx="<?= (int)($avR*0.60) ?>" ry="<?= (int)($avR*0.32) ?>" fill="#ffffff"/>

  <!-- Name line -->
  <rect x="<?= $lx1 ?>" y="<?= $frontY + (int)($cH*0.30) ?>" width="<?= $lx2-$lx1 ?>" height="<?= $lh ?>" rx="<?= $lR ?>" fill="#ffffff"/>
  <!-- Subtitle lines -->
  <rect x="<?= $lx1 ?>" y="<?= $frontY + (int)($cH*0.46) ?>" width="<?= $pad + (int)($cW*0.84) - $lx1 ?>" height="<?= $lh ?>" rx="<?= $lR ?>" fill="#c7d2fe"/>
  <rect x="<?= $lx1 ?>" y="<?= $frontY + (int)($cH*0.60) ?>" width="<?= $pad + (int)($cW*0.84) - $lx1 ?>" height="<?= $lh ?>" rx="<?= $lR ?>" fill="#c7d2fe"/>
</svg>
<?php
    return (string) ob_get_clean();
}

// ---------------------------------------------------------------------------
// Screenshot placeholder SVG (links to PNG equivalent)
// ---------------------------------------------------------------------------
function screenshot_svg(int $n, int $W, int $H): string {
    $titles = [
        1 => 'Author Profile block in the Gutenberg editor',
        2 => 'Block display and style settings panel',
        3 => 'Author Profiles admin list',
        4 => 'Frontend author card layout',
        5 => 'Author Grid block — 3-column team grid',
    ];
    $title = $titles[$n] ?? "Screenshot $n";

    ob_start(); ?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 <?= $W ?> <?= $H ?>" width="<?= $W ?>" height="<?= $H ?>">
  <rect width="<?= $W ?>" height="<?= $H ?>" fill="#f8fafc"/>
  <rect x="0" y="0" width="<?= $W ?>" height="<?= $H ?>" fill="none" stroke="#e2e8f0" stroke-width="2"/>
  <text x="<?= $W/2 ?>" y="<?= $H/2 - 12 ?>"
        font-family="Liberation Sans, Arial, sans-serif" font-size="18" fill="#94a3b8"
        text-anchor="middle">Screenshot <?= $n ?></text>
  <text x="<?= $W/2 ?>" y="<?= $H/2 + 16 ?>"
        font-family="Liberation Sans, Arial, sans-serif" font-size="14" fill="#cbd5e1"
        text-anchor="middle"><?= htmlspecialchars($title) ?></text>
  <text x="<?= $W/2 ?>" y="<?= $H/2 + 44 ?>"
        font-family="Liberation Sans, Arial, sans-serif" font-size="12" fill="#e2e8f0"
        text-anchor="middle">See screenshot-<?= $n ?>.png for the full rendered version</text>
</svg>
<?php
    return (string) ob_get_clean();
}

// ---------------------------------------------------------------------------
// Write all SVGs
// ---------------------------------------------------------------------------
echo "Generating SVGs...\n";

file_put_contents("{$OUT_DIR}/banner-772x250.svg",  banner_svg(772,  250));
echo "  ✓ banner-772x250.svg\n";

file_put_contents("{$OUT_DIR}/banner-1544x500.svg", banner_svg(1544, 500));
echo "  ✓ banner-1544x500.svg\n";

file_put_contents("{$OUT_DIR}/icon-128x128.svg",  icon_svg(128));
echo "  ✓ icon-128x128.svg\n";

file_put_contents("{$OUT_DIR}/icon-256x256.svg",  icon_svg(256));
echo "  ✓ icon-256x256.svg\n";

file_put_contents("{$OUT_DIR}/icon-512x512.svg",  icon_svg(512));
echo "  ✓ icon-512x512.svg\n";

for ($i = 1; $i <= 5; $i++) {
    file_put_contents("{$OUT_DIR}/screenshot-{$i}.svg", screenshot_svg($i, 1280, 800));
    echo "  ✓ screenshot-{$i}.svg\n";
}

echo "Done.\n";
