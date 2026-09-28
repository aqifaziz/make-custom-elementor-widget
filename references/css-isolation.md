# CSS isolation and exact layout

## Contents

1. Scope contract
2. Reset strategy
3. Full-width alignment
4. Theme-resistant components
5. Responsive media
6. Flash and cache prevention

## 1. Scope contract

Prefix every selector with the widget root. Never ship bare selectors such as `h2`, `p`, `.card`, `.button`, `.container`, or `.faq`. Rename generic source classes or qualify them under the root.

Use CSS custom properties on the root for editable values:

```css
.prefix-section {
  --section-accent: #2563eb;
  --section-heading: #1f2937;
  --section-body: #64748b;
  --section-bg: #fff;
}
```

## 2. Reset strategy

Use a narrow box-sizing reset:

```css
.prefix-section,
.prefix-section *,
.prefix-section *::before,
.prefix-section *::after {
  box-sizing: border-box;
}
```

Reset only elements used by the component. Explicitly set critical typography, spacing, border, background, and text decoration. Use stronger specificity and selective `!important` only for properties commonly overridden by theme globals.

```css
.elementor-widget-prefix_widget_name .prefix-section__title {
  margin: 0 !important;
  color: var(--section-heading) !important;
  font: 700 40px/1.1 system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif !important;
  text-transform: none !important;
}
```

Do not apply `all: initial`; it breaks accessibility, inherited language direction, media behavior, and form controls.

## 3. Full-width alignment

Reset only the current Elementor widget wrapper:

```css
.elementor-widget-prefix_widget_name {
  width: 100% !important;
  max-width: none !important;
  margin: 0 !important;
  padding: 0 !important;
}

.elementor-widget-prefix_widget_name > .elementor-widget-container {
  width: 100% !important;
  max-width: none !important;
  margin: 0 !important;
  padding: 0 !important;
  overflow: hidden !important;
}
```

Use an inner shell aligned to project variables with safe fallbacks:

```css
.prefix-section__inner {
  width: 100%;
  max-width: none;
  margin: 0;
  padding-inline: var(--site-header-side-padding, 24px);
}

@media (max-width: 900px) {
  .prefix-section__inner {
    padding-inline: var(--site-mobile-side-padding, 20px);
  }
}
```

If the approved source has a centered maximum width, preserve it. Do not force a full-width inner layout merely because the widget wrapper is full width.

## 4. Theme-resistant components

Navigation links:

```css
.prefix-section .prefix-section__link,
.prefix-section .prefix-section__link:visited {
  color: var(--section-accent) !important;
  text-decoration: none !important;
}
```

Buttons:

```css
.prefix-section .prefix-section__button {
  display: inline-flex !important;
  align-items: center !important;
  justify-content: center !important;
  margin: 0 !important;
  border: 1px solid transparent !important;
  border-radius: 10px !important;
  background: var(--section-accent) !important;
  color: #fff !important;
  font: 700 16px/1 system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif !important;
  text-decoration: none !important;
  box-shadow: none !important;
}
```

Focus:

```css
.prefix-section :focus-visible {
  outline: 3px solid rgba(194, 30, 46, 0.3);
  outline-offset: 3px;
}
```

## 5. Responsive media

```css
.prefix-section img {
  display: block;
  max-width: 100%;
  height: auto;
}
```

Use `object-fit: contain` for logos, diagrams, book covers, or assets that must remain fully visible. Use `cover` only where the source deliberately crops photographs.

For a clean responsive image row:

```css
.prefix-section__gallery {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(min(220px, 100%), 1fr));
  gap: 16px;
  align-items: start;
}

.prefix-section__gallery img {
  width: 100%;
  height: auto;
  object-fit: contain;
}
```

Apply `min-width: 0` to grid/flex children that contain long text. Use `overflow-wrap: anywhere` only where long URLs or words can overflow.

## 6. Flash and cache prevention

FOUC is prevented by architecture, not animation:

- keep critical section CSS in a registered external stylesheet;
- enqueue it during `wp_enqueue_scripts` before `wp_head()`;
- detect the widget from saved Elementor data;
- return the same handle from `get_style_depends()` for editor and preview;
- version with the local file modification time;
- do not wait for JavaScript to add layout classes;
- render initial accordion/tab states in PHP and let JavaScript enhance them.

Do not hide the whole widget until JavaScript loads. That harms LCP, accessibility, and no-JS behavior.
