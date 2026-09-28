# Mandatory quality gates

These requirements are completion gates, not optional improvements. Apply all relevant gates to every Elementor widget conversion. If the execution environment cannot verify a gate, document that limitation instead of claiming success.

## 1. Visual fidelity and immediate insertion

- Preserve the supplied design, content structure, spacing, typography, colors, borders, radii, shadows, states, and interactions.
- Preserve every supplied word, punctuation mark, icon, image, asset, link, item order, default state, breakpoint behavior, transition, animation, and JavaScript interaction. A similar replacement is not an exact conversion.
- Treat the ZIP/source files as authoritative and screenshots as rendered verification. An explicit user-requested change is the only reason to depart from them.
- Make content editable while retaining exact source values as defaults; editability must not change the initial appearance or behavior.
- The widget must look correct immediately after insertion, with no manual margin, padding, width, or typography repair.
- Align the section content with the site's established header/content grid when the source shows that relationship.
- Do not create a redesign, generic substitute, or brand-specific assumption not present in the current input.

## 2. Responsive and mobile-friendly behavior

- Verify 1440, 1280, 1024, 768, 390, and 360 pixel viewports; add source-specific breakpoints when required.
- Verify tablet portrait, mobile portrait, and mobile landscape behavior.
- Stack or reflow columns intentionally. Do not merely shrink desktop content until it becomes unreadable.
- Prevent horizontal scrolling: avoid `100vw`, constrain media, use `min-width:0` on grid/flex children, and wrap long text and URLs safely.
- Keep readable line lengths, sensible mobile type sizes, clean image crops, and adequate spacing without wasting the viewport.
- Make interactive targets at least 44 by 44 CSS pixels where practical, keep sufficient separation between targets, and never depend on hover alone.
- Ensure focus, active, expanded, selected, and disabled states work on touch and keyboard devices.
- Test common browser zoom levels from 90% through 125% and confirm content remains usable at 200% text zoom where practical.

## 3. Loading speed and asset delivery

- Register assets early. Load the widget stylesheet in the existing document `<head>` before first paint to prevent flash of unstyled or uneven content.
- Load behavior scripts in the footer unless a documented source behavior requires earlier execution.
- Enqueue CSS and JavaScript only on pages that contain the corresponding widget. Unrelated pages must receive zero bytes from that section's assets.
- Version every local CSS and JavaScript file with `filemtime()` so edits invalidate browser, CDN, and optimization-plugin caches automatically.
- Avoid jQuery, remote libraries, remote fonts, icon kits, duplicate dependencies, and large frameworks unless the source truly requires them and the user approves.
- Keep the DOM lean, reuse CSS classes and custom properties, avoid expensive selectors, and do not duplicate large inline styles per repeater item.
- Initialize JavaScript once per widget root. Prefer event delegation, avoid polling and repeated layout reads/writes, and remove or avoid unnecessary listeners.
- Render the complete initial visual state in PHP. JavaScript may enhance behavior but must not be required to make the section initially aligned and readable.
- Production files should be compact and readable. Do not ship source maps, temporary bundles, sample libraries, or unused code.

## 4. Core Web Vitals

### LCP

- If the widget contains the probable above-the-fold LCP image, do not lazy-load it. Use eager loading and `fetchpriority="high"` only for the true priority image.
- Lazy-load below-the-fold media and avoid preloading every image.
- Use responsive attachment output such as `wp_get_attachment_image()` when possible so WordPress provides `srcset` and `sizes`.
- Do not introduce render-blocking fonts, libraries, or oversized background assets.

### CLS

- Reserve media space with intrinsic `width` and `height`, `aspect-ratio`, or an equivalent stable container.
- Render open/closed, selected, and first-visible states in the initial HTML/CSS so JavaScript does not cause a first-load jump.
- Use stable font fallbacks and avoid injecting banners, icons, controls, or content above already-rendered material.

### INP

- Keep click, tap, input, filter, tab, and accordion handlers small and scoped.
- Avoid long synchronous loops, repeated full-document queries, forced reflow chains, and work unrelated to the current interaction.
- Use passive listeners for touch/wheel events when they do not call `preventDefault()`.

## 5. Theme and plugin isolation

- Prefix every selector with the unique widget root or Elementor widget wrapper.
- Explicitly set critical typography, button/link appearance, spacing, borders, backgrounds, and decoration so theme globals cannot alter the approved design.
- Reset only the current widget wrapper. Never globally style `html`, `body`, headings, links, buttons, containers, or other widgets.
- Do not use `all:initial`, broad wildcard resets, global overflow hiding, or fragile negative viewport positioning.
- Use `!important` only for known conflicts such as theme-controlled typography, button styles, or Elementor wrapper spacing.
- Confirm common cache, minification, and CSS-combination plugins do not depend on a fixed query string or late inline stylesheet.

## 6. Elementor and WordPress compatibility

- Preserve frontend, editor preview, and responsive editor parity.
- Return the registered handles from `get_style_depends()` and `get_script_depends()` when the widget needs them in Elementor editing contexts.
- Support multiple instances on one page without duplicate IDs, shared mutable state, or cross-widget event handling.
- Provide stable defaults for settings missing from older saved widget data.
- Guard reusable functions/classes where collision is possible and use a unique prefix for PHP functions, handles, classes, CSS roots, and JavaScript data attributes.
- Do not flush rewrite rules, perform expensive work on every request, or mutate unrelated global WordPress/Elementor configuration.
- Keep the requested directory layout and make every filesystem path, URI, handle, widget name, and caller agree exactly.

## 7. Images, icons, and media

- Use Elementor Media controls and WordPress attachment rendering when possible.
- Preserve editable alt text; use an empty alt attribute for decorative images and meaningful text for informative images.
- Use `object-fit:contain` when the full asset must remain visible and `cover` only when the source deliberately crops it.
- Keep images responsive with `max-width:100%; height:auto;` unless a stable cropped container is required.
- Sanitize SVG input and never print arbitrary raw SVG or uploaded file content without an allowlist-based pathway.
- Provide a clean horizontal gallery/grid when the source calls for multiple article images, with predictable wrapping on smaller screens.

## 8. Accessibility

- Use semantic landmarks and elements. Use links for navigation and buttons for actions.
- Preserve a logical heading hierarchy and avoid adding another page H1 when one already exists.
- Every interactive control must be reachable and operable with the keyboard and show a visible focus state.
- Provide accessible names. Synchronize `aria-expanded`, `aria-controls`, `aria-selected`, roles, and hidden states for accordions, tabs, menus, and disclosures.
- Maintain sufficient foreground/background contrast, including hover and focus states.
- Respect `prefers-reduced-motion`; essential content and controls must remain usable without animation.
- Do not communicate meaning with color, motion, or icons alone.

## 9. SEO and content integrity

- Do not output another document head, canonical URL, meta description, Open Graph block, schema graph, site header, or site footer from a section widget.
- Use one page H1 only when the page architecture requires it; use H2/H3 structure for subsection content.
- Preserve crawlable anchor links and meaningful anchor text. Do not replace real URLs with JavaScript click handlers.
- For article/table-of-contents widgets, generate stable unique heading anchors, link each TOC item to its matching heading, and include FAQs only when present in the rendered content.
- Keep important content in the server-rendered HTML instead of hiding it behind client-only rendering.

## 10. Security and data safety

- Escape plain text with the correct WordPress function for its output context, including `esc_html()`, `esc_attr()`, and `esc_url()`.
- Sanitize rich content with an appropriate allowlist such as `wp_kses_post()`.
- Validate selections, numbers, CSS units, colors, URLs, and repeater values before rendering.
- Do not use `eval`, dynamically execute editor content, trust arbitrary HTML attributes, or expose filesystem paths.
- Prevent direct access to standalone PHP widget files with `defined( 'ABSPATH' ) || exit;` when appropriate.
- Do not add data collection, tracking, external requests, or cookies unless explicitly required.

## 11. Browser and interaction testing

- Verify current Chrome, Firefox, Safari, and Edge where available.
- Test links, buttons, hover, keyboard focus, touch, accordions, tabs, filters, TOC scrolling, FAQs, and reduced-motion behavior.
- Test no-JavaScript initial rendering for layout stability even when enhanced interactions require JavaScript.
- Check multiple instances, long headings, long URLs, empty optional fields, missing images, large repeater counts, and translated or right-to-left text when relevant.

## 12. Delivery and verification

- Run PHP lint and JavaScript syntax checks when the required runtimes exist.
- Check CSS parsing/braces, paths, placeholder tokens, old names, `100vw`, broad selectors, duplicate IDs, and unsafe output.
- Confirm CSS appears in the head on widget pages and section assets are absent from unrelated pages.
- Confirm changing an asset changes its `filemtime()` version.
- Verify the exact file tree and caller, and test archive integrity when a ZIP is requested.
- Report tests performed, results, and environment limitations. Never describe an untested claim as verified.
