<?php
declare(strict_types=1);

/**
 * Generates WordPress.org plugin screenshots for Author Profile Blocks.
 * Screenshots 1-5 are generated as realistic admin/frontend mockups.
 *
 * Usage: php resources/banners-icons/generate-screenshots.php
 *        composer run gen-screenshots
 */

$FONT_BOLD    = '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf';
$FONT_REGULAR = '/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf';
$FONT_MONO    = '/usr/share/fonts/truetype/liberation/LiberationMono-Regular.ttf';
$OUT_DIR      = dirname(__DIR__, 2) . '/.wordpress-org';

const W = 1280;
const H = 800;

// ── WP admin palette ────────────────────────────────────────────────────────
const C_BODY      = '#f0f0f1';
const C_SIDEBAR   = '#1d2327';
const C_TOPBAR    = '#1d2327';
const C_WHITE     = '#ffffff';
const C_BLUE      = '#2271b1';
const C_BLUE_LT   = '#d0e5f4';
const C_BORDER    = '#c3c4c7';
const C_TEXT      = '#1d2327';
const C_MUTED     = '#646970';
const C_INDIGO    = '#4f46e5';

// ── Helpers ──────────────────────────────────────────────────────────────────
function new_canvas(string $bg = C_BODY): Imagick {
    $img = new Imagick();
    $img->newImage(W, H, new ImagickPixel($bg));
    $img->setImageFormat('png');
    return $img;
}

function text(Imagick $img, string $font, float $size, string $color, int $x, int $y, string $str): void {
    $d = new ImagickDraw();
    $d->setFont($font);
    $d->setFontSize($size);
    $d->setFillColor($color);
    $d->setTextAntialias(true);
    $img->annotateImage($d, $x, $y, 0, $str);
}

function tw(Imagick $img, string $font, float $size, string $str): int {
    $d = new ImagickDraw();
    $d->setFont($font);
    $d->setFontSize($size);
    return (int) $img->queryFontMetrics($d, $str)['textWidth'];
}

function rect(Imagick $img, int $x1, int $y1, int $x2, int $y2, string $color, float $opacity = 1.0): void {
    $d = new ImagickDraw();
    $d->setFillColor($color);
    $d->setFillOpacity($opacity);
    $d->setStrokeWidth(0);
    $d->rectangle($x1, $y1, $x2, $y2);
    $img->drawImage($d);
}

function rrect(Imagick $img, int $x1, int $y1, int $x2, int $y2, int $r, string $color,
               float $opacity = 1.0, string $stroke = '', float $sw = 1.0): void {
    $d = new ImagickDraw();
    $d->setFillColor($color);
    $d->setFillOpacity($opacity);
    if ($stroke !== '') {
        $d->setStrokeColor($stroke);
        $d->setStrokeWidth($sw);
    } else {
        $d->setStrokeWidth(0);
    }
    $d->roundRectangle($x1, $y1, $x2, $y2, $r, $r);
    $img->drawImage($d);
}

function circle(Imagick $img, int $cx, int $cy, int $r, string $color, float $opacity = 1.0): void {
    $d = new ImagickDraw();
    $d->setFillColor($color);
    $d->setFillOpacity($opacity);
    $d->setStrokeWidth(0);
    $d->circle($cx, $cy, $cx + $r, $cy);
    $img->drawImage($d);
}

function line(Imagick $img, int $x1, int $y1, int $x2, int $y2, string $color, float $w = 1.0): void {
    $d = new ImagickDraw();
    $d->setStrokeColor($color);
    $d->setStrokeWidth($w);
    $d->setFillColor('none');
    $d->line($x1, $y1, $x2, $y2);
    $img->drawImage($d);
}

// ── Draw WP admin chrome (topbar + sidebar) ───────────────────────────────
function draw_admin_chrome(Imagick $img, string $fontBold, string $fontRegular,
                            string $activeItem = 'Pages'): void {
    // Topbar
    rect($img, 0, 0, W, 32, C_TOPBAR);
    // WP logo placeholder
    circle($img, 16, 16, 9, '#ffffff', 0.15);
    text($img, $fontBold, 11, '#ffffff', 30, 21, 'WC Affiliate');

    // Sidebar
    rect($img, 0, 32, 160, H, C_SIDEBAR);

    $items = ['Dashboard', 'Posts', 'Media', 'Pages', 'Appearance', 'Plugins', 'Users', 'Settings'];
    $sideY = 52;
    foreach ($items as $item) {
        $active = ($item === $activeItem);
        if ($active) {
            rect($img, 0, $sideY - 14, 160, $sideY + 8, '#2c3338');
            rect($img, 0, $sideY - 14, 3, $sideY + 8, C_BLUE);
        }
        text($img, $fontRegular, 12, $active ? '#ffffff' : '#a7aaad', 18, $sideY, $item);
        $sideY += 30;
    }
}

// ── Draw avatar (circle + person silhouette) ─────────────────────────────
function draw_person_avatar(Imagick $img, int $cx, int $cy, int $r,
                             string $bgColor = '#e2e8f0', string $fgColor = '#94a3b8'): void {
    circle($img, $cx, $cy, $r, $bgColor);
    // head
    $hr = (int)($r * 0.38);
    circle($img, $cx, $cy - (int)($r * 0.18), $hr, $fgColor);
    // shoulders
    $d = new ImagickDraw();
    $d->setFillColor($fgColor);
    $d->setStrokeWidth(0);
    $d->ellipse($cx, $cy + (int)($r * 0.42), (int)($r * 0.60), (int)($r * 0.32), 190, 350);
    $img->drawImage($d);
}

// ── Screenshot 1: Gutenberg editor with Author Profile block ─────────────
function screenshot_1(string $out, string $fontBold, string $fontRegular): void {
    $img = new_canvas(C_WHITE);

    // Editor top bar (dark)
    rect($img, 0, 0, W, 44, '#1e1e1e');
    // Hamburger icon
    for ($i = 0; $i < 3; $i++) {
        rect($img, 14, 14 + $i * 6, 28, 16 + $i * 6, '#ffffff');
    }
    // WP logo
    circle($img, 50, 22, 12, '#3858e9');
    text($img, $fontBold, 10, '#ffffff', 43, 27, 'W');
    // Title bar centre
    rrect($img, 460, 10, 820, 34, 4, '#f0f0f0');
    text($img, $fontRegular, 12, '#1e1e1e', 540, 27, 'Add title');
    // Top-right buttons
    rrect($img, 1020, 10, 1100, 34, 4, C_BLUE);
    text($img, $fontBold, 12, '#ffffff', 1040, 27, 'Publish');
    text($img, $fontRegular, 12, '#1e1e1e', 1110, 27, '···');

    // Left sidebar tools strip
    rect($img, 0, 44, 56, H, '#f8f9f9');
    line($img, 56, 44, 56, H, C_BORDER);
    // Tool icons (simplified)
    foreach ([70, 110, 150, 190] as $ty) {
        rrect($img, 14, $ty - 14, 42, $ty + 14, 4, '#e0e0e0');
    }

    // Editor canvas area
    rect($img, 56, 44, 900, H, C_WHITE);

    // Post title
    text($img, $fontBold, 28, '#1e1e1e', 180, 120, 'Meet Our Authors');

    // Author Profile block (selected, blue outline)
    rrect($img, 120, 145, 860, 480, 6, C_WHITE, 1.0, C_BLUE, 2.0);

    // Block content: avatar + info
    draw_person_avatar($img, 220, 290, 70, '#e8eaf6', '#9fa8da');

    // Name
    text($img, $fontBold, 22, '#1e1e1e', 320, 255, 'John Smith');
    // Badge
    rrect($img, 320, 265, 430, 285, 10, '#e8eaf6');
    text($img, $fontRegular, 11, C_INDIGO, 330, 280, 'Lead Developer');
    // Email
    text($img, $fontRegular, 14, C_BLUE, 320, 308, 'john.smith@example.com');
    // Bio lines
    foreach ([330, 350, 370] as $ly) {
        $w2 = ($ly === 370) ? 380 : 480;
        rrect($img, 320, $ly, 320 + $w2, $ly + 12, 2, '#e5e7eb');
    }
    // Social icons
    foreach ([0, 1, 2] as $si) {
        circle($img, 328 + $si * 36, 408, 14, '#f1f5f9');
        text($img, $fontRegular, 10, C_MUTED, 321 + $si * 36, 413, ['in', 't', 'g'][$si]);
    }

    // Block toolbar
    rrect($img, 120, 112, 340, 138, 4, '#1e1e1e');
    foreach (['Author Profile', '■', '▲', '▼', '⋮'] as $i => $lbl) {
        text($img, $fontRegular, 11, '#ffffff', 132 + $i * 42, 130, $lbl);
    }

    // Right settings panel
    rect($img, 900, 44, W, H, '#f8f9f9');
    line($img, 900, 44, 900, H, C_BORDER);

    // Panel tabs
    rect($img, 900, 44, W, 78, C_WHITE);
    text($img, $fontBold, 13, '#1e1e1e', 920, 67, 'Block');
    text($img, $fontRegular, 13, C_MUTED, 1080, 67, 'Document');
    line($img, 900, 78, W, 78, C_BORDER);
    line($img, 920, 78, 990, 78, C_BLUE, 2.0);

    // Panel sections
    $py = 100;
    // Display Settings
    text($img, $fontBold, 13, '#1e1e1e', 920, $py, 'Display Settings');
    line($img, 900, $py + 8, W, $py + 8, C_BORDER);
    $py += 20;
    foreach (['Show Author Image', 'Show Author Email', 'Show Description', 'Show Social Links'] as $opt) {
        // Toggle on
        rrect($img, 920, $py, 952, $py + 16, 8, C_BLUE);
        circle($img, 948, $py + 8, 6, C_WHITE);
        text($img, $fontRegular, 12, C_TEXT, 962, $py + 13, $opt);
        $py += 28;
    }

    // Style Settings
    $py += 8;
    text($img, $fontBold, 13, '#1e1e1e', 920, $py, 'Style Settings');
    line($img, 900, $py + 8, W, $py + 8, C_BORDER);
    $py += 20;
    text($img, $fontRegular, 12, C_MUTED, 920, $py + 13, 'Layout');
    foreach (['Card', 'Compact', 'Centered'] as $i => $lbl) {
        $active = ($i === 0);
        rrect($img, 1010 + $i * 68, $py, 1070 + $i * 68, $py + 22, 4,
              $active ? C_BLUE : C_WHITE, 1.0, C_BORDER, 1.0);
        text($img, $fontRegular, 11, $active ? '#ffffff' : C_TEXT, 1018 + $i * 68, $py + 15, $lbl);
    }
    $py += 36;
    text($img, $fontRegular, 12, C_MUTED, 920, $py + 13, 'Background Color');
    rrect($img, 1010, $py, 1036, $py + 22, 3, '#ffffff', 1.0, C_BORDER, 1.0);
    text($img, $fontRegular, 11, C_MUTED, 1044, $py + 15, '#ffffff');

    $img->writeImage($out);
    $img->destroy();
    echo "  ✓ $out\n";
}

// ── Screenshot 2: Block settings — layout and color options ──────────────
function screenshot_2(string $out, string $fontBold, string $fontRegular): void {
    $img = new_canvas(C_WHITE);

    // Editor chrome (minimal)
    rect($img, 0, 0, W, 44, '#1e1e1e');
    circle($img, 50, 22, 12, '#3858e9');
    text($img, $fontBold, 10, '#ffffff', 43, 27, 'W');
    rrect($img, 1020, 10, 1100, 34, 4, C_BLUE);
    text($img, $fontBold, 12, '#ffffff', 1040, 27, 'Update');

    // Left strip
    rect($img, 0, 44, 56, H, '#f8f9f9');
    line($img, 56, 44, 56, H, C_BORDER);

    // Editor canvas
    rect($img, 56, 44, 900, H, '#f9f9f9');

    // Block preview card (with selection border)
    rrect($img, 100, 90, 860, 420, 8, C_WHITE, 1.0, C_INDIGO, 2.0);
    draw_person_avatar($img, 180, 220, 65, '#e8eaf6', '#9fa8da');
    text($img, $fontBold, 20, C_TEXT, 270, 185, 'Jane Doe');
    rrect($img, 270, 193, 360, 212, 10, '#fce7f3');
    text($img, $fontRegular, 11, '#be185d', 280, 207, 'UX Designer');
    text($img, $fontRegular, 13, C_BLUE, 270, 232, 'jane.doe@example.com');
    foreach ([255, 273, 291] as $i => $ly) {
        rrect($img, 270, $ly, 270 + [440, 380, 300][$i], $ly + 11, 2, '#e5e7eb');
    }
    foreach ([0, 1, 2] as $si) {
        circle($img, 278 + $si * 36, 332, 14, '#f1f5f9');
        text($img, $fontRegular, 10, C_MUTED, 271 + $si * 36, 337, ['in', 't', 'g'][$si]);
    }

    // Right panel — detailed settings
    rect($img, 900, 44, W, H, C_WHITE);
    line($img, 900, 44, 900, H, C_BORDER);
    rect($img, 900, 44, W, 78, C_WHITE);
    text($img, $fontBold, 13, '#1e1e1e', 920, 67, 'Block');
    text($img, $fontRegular, 13, C_MUTED, 1080, 67, 'Document');
    line($img, 900, 78, W, 78, C_BORDER);
    line($img, 920, 78, 975, 78, C_BLUE, 2.0);

    $py = 96;
    // Display Settings section
    rrect($img, 900, $py, W, $py + 28, 0, '#f8f9f9');
    text($img, $fontBold, 12, C_TEXT, 920, $py + 18, 'Display Settings');
    text($img, $fontRegular, 16, C_MUTED, 1255, $py + 18, '▲');
    line($img, 900, $py + 28, W, $py + 28, C_BORDER);
    $py += 36;
    foreach (['Show Author Image' => true, 'Show Author Email' => true,
              'Show Description' => true, 'Show More Section' => false] as $lbl => $on) {
        rrect($img, 920, $py, 952, $py + 16, 8, $on ? C_BLUE : '#c3c4c7');
        circle($img, $on ? 948 : 924, $py + 8, 6, C_WHITE);
        text($img, $fontRegular, 12, C_TEXT, 962, $py + 13, $lbl);
        $py += 28;
    }

    // Style Settings section
    $py += 4;
    rrect($img, 900, $py, W, $py + 28, 0, '#f8f9f9');
    text($img, $fontBold, 12, C_TEXT, 920, $py + 18, 'Style Settings');
    text($img, $fontRegular, 16, C_MUTED, 1255, $py + 18, '▲');
    line($img, 900, $py + 28, W, $py + 28, C_BORDER);
    $py += 36;
    text($img, $fontRegular, 12, C_MUTED, 920, $py + 13, 'Background Color');
    rrect($img, 1100, $py, 1126, $py + 22, 3, '#ffffff', 1.0, C_BORDER, 1.0);
    text($img, $fontRegular, 11, C_MUTED, 1134, $py + 15, '#ffffff');
    $py += 34;
    text($img, $fontRegular, 12, C_MUTED, 920, $py + 13, 'Text Alignment');
    foreach (['Left', 'Center', 'Right'] as $i => $lbl) {
        $active = ($i === 0);
        rrect($img, 1090 + $i * 56, $py, 1140 + $i * 56, $py + 22, 4,
              $active ? C_BLUE : C_WHITE, 1.0, C_BORDER, 1.0);
        text($img, $fontRegular, 11, $active ? '#ffffff' : C_TEXT, 1098 + $i * 56, $py + 15, $lbl);
    }

    // Color Settings section
    $py += 38;
    rrect($img, 900, $py, W, $py + 28, 0, '#f8f9f9');
    text($img, $fontBold, 12, C_TEXT, 920, $py + 18, 'Color Settings');
    text($img, $fontRegular, 16, C_MUTED, 1255, $py + 18, '▼');
    line($img, 900, $py + 28, W, $py + 28, C_BORDER);
    $py += 38;
    $swatches = ['#f8fafc','#e0f2fe','#dcfce7','#fef9c3','#fce7f3','#ede9fe','#f1f5f9'];
    foreach ($swatches as $i => $sw) {
        circle($img, 928 + $i * 32, $py + 11, 11, $sw, 1.0);
        rrect($img, 916 + $i * 32, $py, 940 + $i * 32, $py + 22, 11, 'none', 1.0, C_BORDER, 1.0);
    }
    $py += 34;
    text($img, $fontRegular, 12, C_MUTED, 920, $py + 13, 'Custom Color');
    rrect($img, 1210, $py, 1240, $py + 22, 3, '#6366f1', 1.0, C_BORDER, 1.0);

    $img->writeImage($out);
    $img->destroy();
    echo "  ✓ $out\n";
}

// ── Screenshot 3: Author Profiles admin list ─────────────────────────────
function screenshot_3(string $out, string $fontBold, string $fontRegular): void {
    $img = new_canvas(C_BODY);
    draw_admin_chrome($img, $fontBold, $fontRegular, 'Users');

    $cx = 170; // content x start

    // Page heading
    text($img, $fontBold, 24, C_TEXT, $cx, 72, 'Author Profiles');
    rrect($img, 1080, 44, 1240, 72, 4, C_BLUE);
    text($img, $fontBold, 13, '#ffffff', 1110, 63, '+ Add New');

    // Table container
    rrect($img, $cx, 90, W - 30, H - 30, 4, C_WHITE, 1.0, C_BORDER, 1.0);

    // Table header
    rect($img, $cx, 90, W - 30, 124, '#f6f7f7');
    line($img, $cx, 124, W - 30, 124, C_BORDER);
    $cols = ['', 'Image', 'Name', 'Email', 'Description', 'Date'];
    $colX = [$cx + 12, $cx + 36, $cx + 120, $cx + 310, $cx + 520, $cx + 860];
    foreach ($cols as $i => $h) {
        text($img, $fontBold, 13, C_TEXT, $colX[$i], 113, $h);
    }

    // Table rows
    $authors = [
        ['John Smith',    'john.smith@example.com',    'Lead developer with 10+ years of experience.',   'Apr 20, 2026'],
        ['Jane Doe',      'jane.doe@example.com',      'UX Designer specializing in user interfaces.',   'Apr 18, 2026'],
        ['Michael Johnson','michael.j@example.com',    'Marketing specialist with digital expertise.',   'Apr 12, 2026'],
        ['Sarah Williams','sarah.w@example.com',       'Content strategist focused on storytelling.',    'Apr 08, 2026'],
    ];

    $rowH = 68;
    foreach ($authors as $i => $row) {
        $ry = 124 + $i * $rowH;
        if ($i % 2 === 0) rect($img, $cx, $ry, W - 30, $ry + $rowH, '#fafafa');
        line($img, $cx, $ry + $rowH, W - 30, $ry + $rowH, C_BORDER);

        // Checkbox
        rrect($img, $cx + 12, $ry + 22, $cx + 28, $ry + 38, 2, C_WHITE, 1.0, C_BORDER, 1.0);

        // Avatar
        draw_person_avatar($img, $cx + 68, $ry + 34, 22, '#e8eaf6', '#9fa8da');

        // Name (linked blue)
        text($img, $fontBold, 13, C_BLUE, $colX[2], $ry + 30, $row[0]);
        // Row actions
        text($img, $fontRegular, 11, C_MUTED, $colX[2], $ry + 48, 'Edit | Delete');

        // Email
        text($img, $fontRegular, 12, C_BLUE, $colX[3], $ry + 38, $row[1]);

        // Description (truncated)
        $desc = strlen($row[2]) > 45 ? substr($row[2], 0, 45) . '...' : $row[2];
        text($img, $fontRegular, 12, C_TEXT, $colX[4], $ry + 38, $desc);

        // Date
        text($img, $fontRegular, 12, C_MUTED, $colX[5], $ry + 38, $row[3]);
    }

    $img->writeImage($out);
    $img->destroy();
    echo "  ✓ $out\n";
}

// ── Screenshot 4: Frontend author card ──────────────────────────────────
function screenshot_4(string $out, string $fontBold, string $fontRegular): void {
    $img = new_canvas('#f8fafc');

    // Site header
    rect($img, 0, 0, W, 60, C_WHITE);
    line($img, 0, 60, W, 60, C_BORDER);
    text($img, $fontBold, 18, C_TEXT, 40, 38, 'MySite');
    $navItems = ['Home', 'About', 'Blog', 'Contact'];
    foreach ($navItems as $i => $nav) {
        $nx = 500 + $i * 100;
        $active = ($nav === 'About');
        text($img, $active ? $fontBold : $fontRegular, 14, $active ? C_INDIGO : C_TEXT, $nx, 38, $nav);
        if ($active) line($img, $nx, 56, $nx + tw($img, $fontBold, 14, $nav), 56, C_INDIGO, 2.0);
    }

    // Page title
    text($img, $fontBold, 32, C_TEXT, 80, 120, 'Meet Our Team');
    text($img, $fontRegular, 15, C_MUTED, 80, 148, 'Get to know the amazing people behind our work');

    // Author card grid (2 cards side by side)
    $cards = [
        ['John Smith', 'Lead Developer', 'john.smith@example.com',
         'John is a senior developer with over 10 years of experience building WordPress solutions. He specializes in performance and clean code.',
         '#e8eaf6', '#9fa8da'],
        ['Jane Doe', 'UX Designer', 'jane.doe@example.com',
         'Jane creates intuitive user experiences. With a background in psychology, she bridges the gap between design and usability.',
         '#fce7f3', '#f9a8d4'],
    ];

    foreach ($cards as $i => $card) {
        $cx2 = 80 + $i * 560;
        rrect($img, $cx2, 175, $cx2 + 510, 640, 10, C_WHITE, 1.0, C_BORDER, 1.0);

        // Avatar
        draw_person_avatar($img, $cx2 + 80, 275, 55, $card[4], $card[5]);

        // Name
        text($img, $fontBold, 22, C_TEXT, $cx2 + 160, 248, $card[0]);
        // Position badge
        rrect($img, $cx2 + 160, 256, $cx2 + 160 + tw($img, $fontRegular, 12, $card[1]) + 20, 277, 12,
              '#ede9fe');
        text($img, $fontRegular, 12, C_INDIGO, $cx2 + 170, 271, $card[1]);
        // Email
        text($img, $fontRegular, 13, C_BLUE, $cx2 + 160, 298, $card[2]);

        // Bio
        $words = explode(' ', $card[3]);
        $line1 = implode(' ', array_slice($words, 0, 9));
        $line2 = implode(' ', array_slice($words, 9, 9));
        $line3 = implode(' ', array_slice($words, 18));
        text($img, $fontRegular, 13, C_TEXT, $cx2 + 40, 360, $line1);
        text($img, $fontRegular, 13, C_TEXT, $cx2 + 40, 380, $line2);
        if ($line3) text($img, $fontRegular, 13, C_TEXT, $cx2 + 40, 400, $line3);

        // Divider
        line($img, $cx2 + 40, 430, $cx2 + 470, 430, C_BORDER);

        // Social links
        foreach ([[0,'in'],[1,'tw'],[2,'gh']] as [$si, $sl]) {
            $sx = $cx2 + 40 + $si * 52;
            rrect($img, $sx, 446, $sx + 40, 470, 20, '#f1f5f9', 1.0, C_BORDER, 1.0);
            text($img, $fontRegular, 11, C_MUTED, $sx + 10, 463, $sl);
        }

        // "View Profile" link
        text($img, $fontRegular, 13, C_BLUE, $cx2 + 160, 463, '→ View Profile');
    }

    $img->writeImage($out);
    $img->destroy();
    echo "  ✓ $out\n";
}

// ── Screenshot 5: Author Grid block (multiple authors in grid) ──────────
function screenshot_5(string $out, string $fontBold, string $fontRegular): void {
    $img = new_canvas(C_WHITE);

    // Editor chrome
    rect($img, 0, 0, W, 44, '#1e1e1e');
    circle($img, 50, 22, 12, '#3858e9');
    text($img, $fontBold, 10, '#ffffff', 43, 27, 'W');
    rrect($img, 1020, 10, 1100, 34, 4, C_BLUE);
    text($img, $fontBold, 12, '#ffffff', 1040, 27, 'Publish');

    rect($img, 0, 44, 56, H, '#f8f9f9');
    line($img, 56, 44, 56, H, C_BORDER);

    rect($img, 56, 44, 900, H, '#f9f9f9');
    text($img, $fontBold, 26, C_TEXT, 180, 110, 'Our Team');

    // Grid block border (selected)
    rrect($img, 80, 130, 880, 720, 6, '#f9f9f9', 1.0, C_INDIGO, 2.0);
    // Block toolbar
    rrect($img, 80, 97, 280, 123, 4, '#1e1e1e');
    text($img, $fontRegular, 11, '#ffffff', 92, 115, 'Author Grid  ■ ▲ ▼ ⋮');

    // 3-column grid of author mini-cards
    $gridAuthors = [
        ['John Smith',   'Developer', '#e8eaf6', '#9fa8da'],
        ['Jane Doe',     'Designer',  '#fce7f3', '#f9a8d4'],
        ['Mike Johnson', 'Marketing', '#dcfce7', '#86efac'],
        ['Sarah W.',     'Content',   '#fef9c3', '#fde047'],
        ['Alex Chen',    'DevOps',    '#ede9fe', '#c4b5fd'],
        ['Lisa Park',    'Product',   '#fee2e2', '#fca5a5'],
    ];

    $cols = 3;
    $cardW = 240;
    $cardH = 170;
    $gapX  = 20;
    $gapY  = 16;
    $startX = 100;
    $startY = 148;

    foreach ($gridAuthors as $i => $ga) {
        $col = $i % $cols;
        $row = (int)($i / $cols);
        $gx  = $startX + $col * ($cardW + $gapX);
        $gy  = $startY + $row * ($cardH + $gapY);

        rrect($img, $gx, $gy, $gx + $cardW, $gy + $cardH, 8, C_WHITE, 1.0, C_BORDER, 1.0);
        draw_person_avatar($img, $gx + $cardW / 2, $gy + 52, 30, $ga[2], $ga[3]);
        text($img, $fontBold, 13, C_TEXT, $gx + ($cardW / 2) - 30, $gy + 100, $ga[0]);
        rrect($img, $gx + 60, $gy + 108, $gx + $cardW - 60, $gy + 126, 8, '#f1f5f9');
        text($img, $fontRegular, 10, C_MUTED, $gx + 65, $gy + 121, $ga[1]);
        // mini social dots
        foreach ([0, 1, 2] as $si) {
            circle($img, $gx + 80 + $si * 28, $gy + 148, 8, '#f1f5f9');
        }
    }

    // Right panel
    rect($img, 900, 44, W, H, C_WHITE);
    line($img, 900, 44, 900, H, C_BORDER);
    rect($img, 900, 44, W, 78, C_WHITE);
    text($img, $fontBold, 13, '#1e1e1e', 920, 67, 'Block');
    text($img, $fontRegular, 13, C_MUTED, 1080, 67, 'Document');
    line($img, 900, 78, W, 78, C_BORDER);
    line($img, 920, 78, 975, 78, C_BLUE, 2.0);

    $py = 96;
    rrect($img, 900, $py, W, $py + 28, 0, '#f8f9f9');
    text($img, $fontBold, 12, C_TEXT, 920, $py + 18, 'Grid Settings');
    line($img, 900, $py + 28, W, $py + 28, C_BORDER);
    $py += 36;
    text($img, $fontRegular, 12, C_MUTED, 920, $py + 13, 'Columns');
    foreach ([2, 3, 4] as $i => $n) {
        $active = ($n === 3);
        rrect($img, 1080 + $i * 48, $py, 1122 + $i * 48, $py + 22, 4,
              $active ? C_BLUE : C_WHITE, 1.0, C_BORDER, 1.0);
        text($img, $fontRegular, 11, $active ? '#ffffff' : C_TEXT, 1095 + $i * 48, $py + 15, (string)$n);
    }
    $py += 36;
    text($img, $fontRegular, 12, C_MUTED, 920, $py + 13, 'Max Authors');
    rrect($img, 1080, $py, 1240, $py + 22, 4, C_WHITE, 1.0, C_BORDER, 1.0);
    text($img, $fontRegular, 12, C_TEXT, 1090, $py + 15, '6');
    $py += 36;
    text($img, $fontRegular, 12, C_MUTED, 920, $py + 13, 'Filter by Role');
    rrect($img, 1080, $py, 1240, $py + 22, 4, C_WHITE, 1.0, C_BORDER, 1.0);
    text($img, $fontRegular, 12, C_TEXT, 1090, $py + 15, 'All Roles  ▾');

    $img->writeImage($out);
    $img->destroy();
    echo "  ✓ $out\n";
}

// ── Run ──────────────────────────────────────────────────────────────────────
echo "Generating screenshots...\n";
screenshot_1("{$OUT_DIR}/screenshot-1.png", $FONT_BOLD, $FONT_REGULAR);
screenshot_2("{$OUT_DIR}/screenshot-2.png", $FONT_BOLD, $FONT_REGULAR);
screenshot_3("{$OUT_DIR}/screenshot-3.png", $FONT_BOLD, $FONT_REGULAR);
screenshot_4("{$OUT_DIR}/screenshot-4.png", $FONT_BOLD, $FONT_REGULAR);
screenshot_5("{$OUT_DIR}/screenshot-5.png", $FONT_BOLD, $FONT_REGULAR);
echo "Done. Output → {$OUT_DIR}/\n";
