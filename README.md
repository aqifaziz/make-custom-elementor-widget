# Convert Elementor Section

A reusable AI skill for converting supplied HTML, CSS, JavaScript, ZIP exports, screenshots, and assets into exact, editable, production-ready WordPress Elementor widgets.

The supplied ZIP and source files remain authoritative. The skill requires the converted widget to preserve every word, icon, image, link, state, breakpoint, animation, and interaction unless the user explicitly requests a change.

## Highlights

- Exact source fidelity with no automatic redesign or copy changes
- Editable Elementor text, rich content, media, icons, links, repeaters, colors, and responsive settings
- Relevant built-in Elementor `eicon-*` panel icon
- Desktop, tablet, mobile, landscape, touch, and browser-zoom validation
- Theme and Elementor global-style isolation
- Full-width alignment without horizontal scrolling
- CSS available before first paint to prevent unstyled-content flash
- Conditional per-widget CSS and JavaScript loading
- Automatic local-asset cache invalidation through `filemtime()`
- Core Web Vitals safeguards for LCP, CLS, and INP
- Accessibility, semantic HTML, SEO, security, and multiple-instance support
- Exact requested file structure and `functions.php` caller

## Repository structure

```text
.
├── SKILL.md
├── agents/
│   └── openai.yaml
├── assets/
│   ├── icon.svg
│   ├── portable-master-prompt.md
│   └── widget-starter/
├── references/
│   ├── conversion-workflow.md
│   ├── css-isolation.md
│   ├── quality-gates.md
│   ├── validation.md
│   └── wordpress-elementor-patterns.md
├── LICENSE
└── README.md
```

## Using the skill

Install or make this repository available to an AI system that supports skills, then request `convert-elementor-section` while supplying the section ZIP or source files.

The skill asks only for important information that is missing:

1. Elementor widget title
2. Elementor Elements-panel icon, either an `eicon-*` class or `Automatic`
3. Page/folder key, section number, and PHP filename
4. Elementor category and whether the widget is new or replacing an existing one, when unclear

It does not ask users to repeat details already present in the request or source files.

## Portable use with other AI systems

If the target AI does not support installable skills, provide it with:

```text
assets/portable-master-prompt.md
```

Supply the original section files with that prompt. The portable prompt contains the same fidelity, responsiveness, performance, caching, accessibility, SEO, security, and validation requirements.

## Expected output

The default structure is:

```text
inc/elementor-widgets/<page-key>/<widget-file>.php
assets/<page-key>/section-<number>/style.css
assets/<page-key>/section-<number>/app.js
assets/<page-key>/section-<number>/images/
INSTALL.txt
```

The user's requested structure always takes priority. The output includes the resolved `functions.php` caller and only the assets required by the widget.

## License

Released under the MIT License. See [LICENSE](LICENSE).
