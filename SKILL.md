---
name: convert-elementor-section
description: Convert supplied HTML, CSS, JavaScript, ZIP exports, screenshots, or section source files into exact, responsive, production-ready WordPress Elementor widgets. Use when an AI must preserve an existing section design while making content, images, links, colors, spacing, and repeatable items editable; isolate the widget from theme global styles; load assets conditionally before paint; prevent cache, overflow, and CSS-flash problems; package files in the requested theme structure; and provide the functions.php caller.
---

# Convert Elementor Section

Convert the supplied section into a self-contained Elementor widget without redesigning it. Treat the supplied ZIP and its HTML, CSS, JavaScript, images, fonts, icons, and other assets as the authoritative source of truth; use screenshots to verify the rendered result. Preserve every visible word, icon, asset, link, state, layout detail, responsive behavior, animation, and interaction exactly unless the user explicitly requests a change. Adapt technical names and paths to the current project without imposing a fixed brand or previous project structure.

## Read resources selectively

- Read `references/conversion-workflow.md` before inspecting or converting source files.
- Read `references/wordpress-elementor-patterns.md` when implementing the widget class, controls, URLs, media, and conditional asset loading.
- Read `references/css-isolation.md` when writing CSS or fixing theme overrides, width, alignment, overflow, or flash-of-unstyled-content issues.
- Read `references/quality-gates.md` before implementation and again before delivery. Its mobile, speed, Core Web Vitals, accessibility, security, compatibility, and testing requirements are mandatory.
- Read `references/validation.md` before packaging or handing off files.
- Copy and adapt files from `assets/widget-starter/` instead of recreating common boilerplate.
- Give `assets/portable-master-prompt.md` to an AI system that cannot install skills.

## Required workflow

1. Inventory every supplied file before editing. Extract ZIPs and locate HTML, CSS, JavaScript, images, fonts, icons, and nested assets.
2. Inspect the complete source section and its responsive breakpoints. Identify only the markup, CSS, and behavior used by the requested section.
3. Run the short pre-build questionnaire below only for information that is still missing. Do not ask the user to repeat details already supplied or clearly inferable from the project.
4. Record the requested destination paths and naming conventions. If the user accepts automatic naming, derive a neutral collision-resistant structure from the page name and section number.
5. Map static content to Elementor controls. Use repeaters for repeated cards, lists, FAQs, tabs, statistics, links, or steps. Use a WYSIWYG control for complete article content.
6. Preserve the source layout, dimensions, spacing, typography, colors, radii, shadows, text, icons, images, links, hover/focus/active states, breakpoints, animations, and interaction behavior exactly. Do not rewrite, replace, simplify, omit, reorder, or redesign source content unless explicitly asked.
7. Scope every selector and JavaScript operation to one widget root. Make multiple instances safe.
8. Register assets early, enqueue only on pages containing the widget, version local assets with `filemtime()`, place CSS in the existing document head, and load JavaScript in the footer.
9. Prevent theme and Elementor global styles from changing the approved design. Never style `html`, `body`, or unrelated global elements.
10. Preserve semantic heading order, keyboard behavior, focus states, alt text, link semantics, and reduced-motion behavior.
11. Validate structure, syntax, archive contents, asset paths, responsive overflow, and expected dependencies.
12. Deliver the exact requested folder structure, one caller line, installation notes, and a ZIP only when the user requests or clearly needs a portable package.

## Short pre-build questionnaire

Ask one compact message containing only unresolved items. Prefer no more than four questions and show a recommended automatic option:

1. **Elementor widget title:** What name should appear in Elementor's Elements panel, for example `Feature Grid Section 3`?
2. **Elementor panel icon:** Ask the user to paste an Elementor built-in `eicon-*` class, or write `Automatic` so the AI selects the closest built-in icon for the section. Use the selected class in `get_icon()`. Do not load an external icon library for the panel icon. If a truly custom icon is explicitly required, request its asset and implement isolated editor-only styling.
3. **File identity and placement:** Confirm page/folder key, section number, and PHP filename or offer a complete recommended set. Respect an already supplied directory structure exactly.
4. **Registration context:** When unclear, ask for the Elementor category name and whether this is a new widget or a replacement for an existing widget/caller.

Ask about link destinations, interaction behavior, heading level, or asset choices only when the source files and request do not answer them and guessing would materially change functionality, SEO, or layout. Do not block work for cosmetic details that can be faithfully derived from the source.

## Mandatory quality gates

Every conversion must satisfy `references/quality-gates.md`. In particular:

- Desktop, tablet, mobile, touch, landscape, and browser-zoom behavior must be intentional and free of horizontal overflow.
- CSS must be available before first paint, JavaScript must not block rendering, and section assets must load only on pages that contain the widget.
- Protect LCP, CLS, and INP through correct image priority, reserved dimensions, efficient DOM/event handling, and stable initial PHP-rendered markup.
- The widget must remain visually isolated from theme and Elementor global styles without changing unrelated site elements.
- Keyboard access, visible focus, ARIA state, contrast, reduced motion, semantic HTML, heading order, alt text, and meaningful links are required.
- Escape and sanitize all Elementor values, keep multiple instances safe, preserve editor/frontend parity, and avoid global identifiers or behavior.
- Verify current Chrome, Firefox, Safari, and Edge behavior where the environment permits; state any untested item instead of claiming it passed.

## Non-negotiable implementation rules

- Never invent a new visual direction when source files exist.
- Never correct, rewrite, shorten, expand, translate, or normalize even small source text unless the user requests it.
- Never replace a supplied icon, image, label, link, animation, state, or interaction with a merely similar alternative. Reuse the exact supplied asset and behavior whenever technically possible.
- Never omit small decorative or functional details because they seem unimportant.
- Never use `100vw` for the widget shell. Use `width:100%`, `max-width:100%`, and controlled inner padding.
- Never add a second `<head>`, `<body>`, site header, site footer, or page-level wrapper inside a widget.
- Never use jQuery unless the supplied behavior requires it and no safe vanilla alternative exists.
- Never inline a large stylesheet after the markup. Load a registered CSS file before render.
- Never enqueue section assets on unrelated pages.
- Never hardcode one organization's name, colors, URLs, or slugs unless they appear in the current source or request.
- Never leave the Elementor panel icon as an unrelated boilerplate icon. Use the user's `eicon-*` choice or select the closest relevant built-in Elementor icon.
- Never force every widget to contain an H1. A non-hero section normally starts with H2. Preserve exactly one page H1 only when the source and page role require it.
- Never turn navigation links into non-semantic buttons. Use `<a>` for destinations and `<button>` for in-page actions.
- Never output unsanitized user content, URLs, SVG, or attributes.
- Never use fragile negative viewport-width positioning to fake full width.
- Never delete unrelated files or overwrite an existing caller block without inspecting it.

## Editable-control standard

Expose practical controls, not arbitrary implementation details:

- Text: `TEXT`, `TEXTAREA`, or `WYSIWYG` depending on content complexity.
- Repeated content: `Repeater` with stable defaults and `title_field`.
- Images: Media Library control, optional external URL only if requested, editable alt text, fit mode, and responsive dimensions.
- Icons: Elementor Icons control or sanitized SVG pathway when the source uses icons.
- Widget panel icon: a relevant built-in Elementor `eicon-*` class returned by `get_icon()`; this is separate from editable icons rendered inside the section.
- Links: `URL` control with external and nofollow support.
- Colors: scoped CSS custom properties through Elementor selectors.
- Sizing/spacing: responsive controls only where users reasonably need them.
- Visibility or behavior: switches and selects with clear defaults.

Do not expose every pixel as a control. Keep source-perfect defaults so inserting the widget immediately reproduces the approved section.

## Output contract

Return or create:

```text
inc/elementor-widgets/<page-key>/<widget-file>.php
assets/<page-key>/section-<number>/<style-file>.css
assets/<page-key>/section-<number>/<script-file>.js   # only when needed
assets/<page-key>/section-<number>/images/...         # only when needed
INSTALL.txt
```

Include the exact caller:

```php
require_once get_theme_file_path( '/inc/elementor-widgets/<page-key>/<widget-file>.php' );
```

If the user's project specifies another structure, follow it exactly and update every registration path consistently.

## Completion standard

Do not call the work complete until the widget:

- preserves every supplied word, icon, asset, link, state, animation, and interaction unless an explicit requested change is documented;
- visually matches the source at desktop, tablet, and mobile widths;
- works at 360, 390, 768, 1024, 1280, and 1440 pixels, in mobile landscape, and at common 90–125% zoom levels;
- inserts with correct defaults and no manual spacing repair;
- has no horizontal scrollbar;
- remains stable under theme global typography/button/link rules;
- supports all requested editing controls;
- keeps links and interactions functional;
- loads CSS before paint without stale-cache behavior;
- loads no section CSS or JS on unrelated pages;
- avoids avoidable render blocking, layout shift, long interaction tasks, duplicate dependencies, and unnecessary remote requests;
- uses responsive images, correct intrinsic dimensions, and appropriate eager/lazy loading priority;
- is keyboard accessible, touch friendly, reduced-motion safe, semantically correct, escaped, and sanitized;
- works with multiple instances;
- contains no duplicate IDs;
- preserves frontend and Elementor editor/preview behavior;
- passes every applicable quality gate, validation checklist, and archive test.
