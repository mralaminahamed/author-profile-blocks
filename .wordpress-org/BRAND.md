# Author Profile Blocks — Brand & Asset System

Everything in `.wordpress-org/` is generated. `icon.svg` and
`resources/brand/banner.html` are the only files edited by hand — never touch
the PNGs directly, they are overwritten on every render.

---

## 1. Asset Inventory

| File | Dimensions | Purpose | Source |
|---|---|---|---|
| `icon.svg` | vector | Canonical icon. WordPress.org consumes this directly when present. | Authored here (master) |
| `icon-256x256.png` | 256×256 | Directory hero icon (retina) | Rasterised from `icon.svg` |
| `icon-128x128.png` | 128×128 | Directory listing grid | Rasterised from `icon.svg` |
| `icon-512x512.png` | 512×512 | Non-directory channels that ask for a larger mark | Rasterised from `icon.svg` |
| `banner-1544x500.png` | 1544×500 | Desktop directory banner (retina) | `resources/brand/banner.html` |
| `banner-772x250.png` | 772×250 | Mobile / non-retina banner | Same markup, narrow variant |
| `banner-1024x512.png` | 1024×512 | Square-ish crop for channels outside the directory | Same markup, square variant |
| `screenshot-*.png` | 1200×900 | Feature screenshots, branded frame | Live site capture, composed |
| `blueprints/` | — | WordPress Playground "Live Preview" | Authored here |

Only the 128/256 icons and the 772×250 / 1544×500 banners are used by the
plugin directory itself; the other sizes exist for other channels and cost
nothing to keep in step.

### What this replaced

Three per-size icon SVGs, two per-size banner SVGs, and — the part that
mattered — five "screenshots" that were not screenshots. Each was an SVG
containing the words *Screenshot 1* and a caption in grey placeholder type,
rasterised to PNG and published. They described the plugin's interface without
showing it, on the listing, for months.

Nothing here is drawn any more. The screenshots are captures of the plugin
running, which is why `resources/dev/seed.php` exists: an image of a team page
is only reproducible if the team is.

---

## 2. Design Tokens

### 2.1 Colour

**The palette lives in `tests/assets/brand.ts`, and only there.** The icon, the
banners and the screenshot frames all import it. Do not copy a hex out of that
file into another one — the per-size SVGs this replaced each carried their own
list, and copies of a palette agree only until somebody edits one.

`icon.svg` is the exception the format forces: SVG cannot import. Its values are
duplicated deliberately and its header comment names `brand.ts` as canonical.

Every token is a Tailwind **indigo** stop. The hue is inherited — the previous
artwork was indigo on near-black and is live on the directory, so changing it
would throw away what recognition the listing has. What changed is that the
values are now the scale itself rather than approximations of it.

| Token | Hex | Tailwind | Used for |
|---|---|---|---|
| `ink` | `#1e1b4b` | indigo-950 | Ground, darkest corner |
| `inkMid` | `#312e81` | indigo-900 | Ground, middle |
| `inkLift` | `#3730a3` | indigo-800 | Ground, lit corner |
| `royal` | `#4f46e5` | indigo-600 | Avatar disc, first detail line, glow |
| `royalLight` | `#6366f1` | indigo-500 | Reserved |
| `sky` | `#818cf8` | indigo-400 | Second detail line, frame kicker |
| `accent` | `#a5b4fc` | indigo-300 | Wordmark gradient, text on the ground |
| `cardCrown` / `cardMid` / `cardBase` | `#ffffff` / `#eef2ff` / `#c7d2fe` | white / indigo-50 / indigo-200 | The profile card, crown to base |
| `shadow` | `30, 27, 75` | indigo-950 | Shadow colour, as RGB channels |

### 2.2 The mark

A profile card: an avatar beside three lines of detail, with a second card
peeking out behind it. Every author box on the web is that shape, so it reads
without being taught — and the card behind says this plugin renders grids,
carousels and lists, where a single card would say the opposite.

The card is inset from the squircle rather than drawn edge to edge. At 128px,
the size the directory grid actually uses, a card running into the rounded
corners has its own corners clipped by them and collapses into a plain
rectangle.

---

## 3. Regenerating

```bash
yarn shots:banners   # icon + banners — renders local markup, no site needed

wp eval-file resources/dev/seed.php                      # the demo team and pages
WP_LOGIN_URL="$(wp magic-login --login=admin --porcelain --no-launch)" \
  yarn shots:wporg   # screenshots — drives a logged-in WordPress admin
```

`WP_BASE_URL` overrides the site (default `http://wp-plugin-dev.test`). Instead
of `WP_LOGIN_URL`, `WP_ADMIN_USER` and `WP_ADMIN_PASS` also work.

Adding a screenshot means adding a numbered entry to `readme.txt` under
`== Screenshots ==` as well — WordPress.org matches them by number, not by name,
so an image with no caption is captioned by whatever entry happens to share its
index.

### The demo data

`resources/dev/seed.php` creates six authors with roles, bios, locations and
social links, and four pages — one per block. Everything carries
`_apbl_demo_seed`, so `--remove` deletes what was seeded and nothing else:

```bash
wp eval-file resources/dev/seed.php -- --list             # the scopes
wp eval-file resources/dev/seed.php -- --only=authors     # one of them
wp eval-file resources/dev/seed.php -- --remove           # all of it, backwards
```

It also sets `avatar_default` to `identicon` and restores the previous value on
removal. Avatars come from Gravatar, and six addresses with no Gravatar fall
back to the same mystery-person silhouette six times, which makes a team page
look like a stock photo of nobody.

---

## 4. Capturing honestly

The frame around each screenshot is branded; what is inside it is not touched.
The rules the capture follows:

- **Nothing is mocked.** Every pixel inside the card came from the plugin
  running on a real site.
- **Furniture is stripped by position, not by name.** A header or footer that
  does not contain the block being photographed is not part of the block. The
  capture machine runs a Divi child theme with its own navigation and a
  four-column footer; naming those classes would fix one machine and not the
  next one.
- **Other plugins do not appear.** The editor toolbar carried "Edit with
  Elementor" and "Use The Divi Builder" until it was told not to — two
  competitors advertised inside this plugin's own screenshot.
- **Each shot asserts its subject is present** before it is taken. A grid that
  rendered empty is exactly the failure these images exist to disprove, and a
  capture that photographs it silently is worse than no capture.
