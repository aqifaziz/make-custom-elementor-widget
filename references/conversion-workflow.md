# Conversion workflow

## Contents

1. Source audit
2. Section extraction
3. Control mapping
4. Markup conversion
5. Asset conversion
6. Responsive and alignment decisions
7. Packaging
8. Common failure modes

## 1. Source audit

Extract the supplied package into a temporary working directory. List files with a fast recursive file search. Identify:

- page entry HTML files;
- shared and section-specific stylesheets;
- scripts and their dependencies;
- images, SVGs, fonts, and relative paths;
- existing responsive breakpoints;
- repeated components and interactive states;
- source selectors that may collide with a WordPress theme.

Inspect the entire relevant HTML block and all matching CSS rules, including media queries, pseudo-elements, hover/focus states, and CSS variables. Inspect JavaScript for behavior used by the section only. Do not copy unrelated menu, search, header, footer, or page scripts.

Build a fidelity inventory before conversion. Record every visible text string and punctuation mark, icon, image, link URL/target, item order, CSS state, breakpoint change, animation, transition, JavaScript action, default open/selected state, and small decorative element. The conversion is incomplete until every recorded item is preserved or an explicit user-requested change explains the difference.

Do not silently improve source wording, substitute icons, normalize capitalization, change labels, replace assets, adjust URLs, remove decoration, simplify animations, or alter interaction timing. Elementor controls must use the original source values as defaults so a newly inserted widget reproduces the source immediately.

When screenshots are supplied, compare them with the source and use them to resolve ambiguity about visible spacing, alignment, and responsive behavior.

## 2. Section extraction

Create a section map before coding:

| Source part | Elementor representation | Editing mechanism |
| --- | --- | --- |
| Single heading | semantic heading | TEXT |
| Long article | sanitized rich content | WYSIWYG |
| Cards/list/steps | looped markup | Repeater |
| Image | attachment or URL | MEDIA + alt + fit |
| Destination | anchor | URL |
| Accordion/tab/filter | accessible component | Repeater + scoped vanilla JS |
| Brand/design value | CSS variable | COLOR/SLIDER/SELECT |

Preserve source defaults. A user should be able to add the widget and see the intended design without configuring padding, widths, colors, or content.

## 3. Control mapping

Group controls by user intent: Content, Repeated items, Media, Links and routing, Interaction settings, Appearance, and Responsive spacing.

Use a repeater when the number of items may change. Do not create numbered controls such as `card_1_title`, `card_2_title`, and `card_3_title`.

Use a WYSIWYG control when the user needs a real article with headings, paragraphs, lists, links, images, tables, or inline formatting. Sanitize rendered article content with `wp_kses_post()`. Apply shortcodes only when the project expects them.

For automatically generated tables of contents:

1. Sanitize article HTML.
2. Find H2/H3 headings server-side where practical.
3. Assign widget-instance-prefixed unique IDs.
4. Render TOC anchors before JavaScript runs.
5. Use JavaScript only for smooth scrolling or active-state enhancement.
6. Apply `scroll-margin-top` to account for sticky headers.

## 4. Markup conversion

Wrap the section in one unique root class. Use meaningful internal classes prefixed with the widget namespace.

Prefer semantic elements: `<section>`, `<article>`, `<nav>`, `<aside>`, `<ul>`, `<ol>`, `<a>`, and `<button type="button">`.

Generate IDs from the widget instance ID plus a stable repeater row ID. Escape output according to context:

- `esc_html()` for plain text;
- `esc_attr()` for attributes;
- `esc_url()` for URLs;
- `wp_kses_post()` for permitted rich HTML;
- Elementor icon rendering or a proven SVG sanitizer for SVG.

Respect external and nofollow URL settings. Add `noopener noreferrer` for external links.

## 5. Asset conversion

Copy only assets used by the section. Rewrite relative URLs to theme-aware PHP output or stable CSS-relative paths. Do not leave links pointing to the extracted ZIP directory.

Images:

- preserve source aspect ratio unless the source uses deliberate cropping;
- set width/height or `aspect-ratio` to reserve space;
- use `loading="eager"` and `fetchpriority="high"` only for a real above-the-fold LCP image;
- use lazy loading for below-the-fold images;
- provide editable alt text;
- support clean horizontal responsive galleries when multiple images are part of the design.

Fonts:

- prefer the project's existing local/system font stack;
- do not introduce remote font calls without permission;
- reserve space and avoid layout shifts.

JavaScript:

- include only behavior needed by this widget;
- scope queries to the widget root;
- use event delegation for dynamic or editor-rendered content;
- keep initialization idempotent;
- support multiple widget instances;
- respect `prefers-reduced-motion`.

## 6. Responsive and alignment decisions

Use the source's breakpoints when available. Otherwise verify at least 1440, 1280, 1024, 768, 390, and 360 pixels.

For a full-width widget aligned to the site header:

```css
.widget-root {
  width: 100%;
  max-width: 100%;
  margin: 0;
  overflow: hidden;
}

.widget-root__inner {
  width: 100%;
  max-width: none;
  margin: 0;
  padding-inline: var(--site-shell-padding, 24px);
}
```

Do not use `100vw`; it commonly includes scrollbar width and creates horizontal overflow. Reset Elementor's widget wrapper spacing with a widget-specific selector rather than changing all Elementor widgets.

## 7. Packaging

Place the PHP and assets exactly where the requested theme structure expects them. Add `INSTALL.txt` containing final file paths, the one caller line, Elementor widget title and category, asset-loading behavior, and any special usage rule.

Archive from the package root so the ZIP opens directly to `inc/`, `assets/`, and `INSTALL.txt`, without an unnecessary outer directory.

## 8. Common failure modes

- CSS appears late: stylesheet was printed after markup or enqueued too late.
- Old CSS remains: fixed version string instead of `filemtime()`.
- Theme changes buttons: selectors too weak or source styles not reset.
- Horizontal scrollbar: `100vw`, transformed shell, oversized fixed media, or min-width.
- Links do not work: static paths were copied without URL controls or routing logic.
- Elementor editor differs: dependencies were not returned from `get_style_depends()`/`get_script_depends()`.
- Multiple widgets conflict: global IDs or document-wide queries.
- Page becomes heavy: all section assets enqueued globally.
- SEO heading issue: every section generates H1 or visual headings are non-semantic divs.
- Image layout shifts: no dimensions/aspect ratio.
