# Validation and handoff

## Contents

1. Static checks
2. WordPress/Elementor checks
3. Visual checks
4. Performance checks
5. Accessibility, SEO, security, and browser checks
6. Packaging checks
7. Handoff format

## 1. Static checks

- Run `php -l` on every PHP file when PHP CLI is available.
- Run `node --check` on every JavaScript file.
- Confirm balanced CSS braces and inspect parser/minifier output if available.
- Search for old source paths, temporary directories, placeholder tokens, `100vw`, bare global selectors, and duplicate IDs.
- Confirm all registered asset paths exist.
- Confirm every output context is escaped or intentionally sanitized.

If PHP CLI is unavailable, report that limitation internally and perform structural checks; do not claim a PHP lint passed.

## 2. WordPress/Elementor checks

- Widget category registers without fatal errors when several files use it.
- Widget title and a section-relevant built-in `eicon-*` icon appear correctly in Elementor's Elements panel.
- Default insertion matches the source.
- Editor preview loads the same CSS and JS as frontend.
- Repeaters add, remove, reorder, and edit correctly.
- URL controls preserve external/nofollow options.
- Media controls render attachment and alt data correctly.
- Multiple widget instances work on the same page.
- Saved older settings have reasonable defaults after an update.

## 3. Visual checks

Compare source and widget at 1440, 1280, 1024, 768, 390, and 360 pixels. Check tablet portrait, mobile portrait and landscape, header/content alignment, spacing, typography, wrapping, image dimensions, cards, hover/focus/open states, theme overrides, and horizontal scrolling at 90–125% browser zoom. Check 200% text zoom where practical. Verify touch targets are at least 44 by 44 CSS pixels where practical and that no essential action depends on hover.

Compare against the pre-conversion fidelity inventory. Verify every word and punctuation mark, icon, image, URL/target, item order, decorative detail, default state, responsive state, transition, animation, and JavaScript interaction. Treat any undocumented substitution, omission, rewriting, reordering, or behavior change as a failed conversion.

## 4. Performance checks

- CSS appears in the document head on pages containing the widget.
- Section CSS and JS are absent from unrelated pages.
- Asset version changes after editing the file.
- No remote font or library is introduced without approval.
- No duplicate CSS/JS dependency is registered.
- JavaScript listeners are delegated or initialized once.
- Below-fold images are lazy; real LCP image is eager with reserved dimensions.
- No layout shift occurs when images, icons, ads, or fonts load.
- Initial alignment and visible state are server rendered and do not wait for JavaScript.
- Interaction handlers remain small, scoped, and free from avoidable long tasks or forced reflow chains.
- Responsive WordPress image output supplies appropriate `srcset` and `sizes` when attachment data is available.
- No unused framework, source map, remote font, icon kit, tracking request, or temporary production asset is shipped.

## 5. Accessibility, SEO, security, and browser checks

- Test keyboard-only operation, visible focus, touch input, reduced motion, accessible names, and synchronized ARIA state.
- Check semantic landmarks, exactly one appropriate page H1, logical H2/H3 order, meaningful links, and informative/decorative alt behavior.
- Check contrast for normal, hover, focus, active, selected, expanded, and disabled states.
- Verify correct contextual escaping, allowlist sanitization, validated control values, safe SVG handling, and direct-access guards where appropriate.
- Verify multiple instances and frontend/editor parity in current Chrome, Firefox, Safari, and Edge where available.
- Test long headings and URLs, empty optional fields, missing images, large repeaters, and no-JavaScript initial layout.

## 6. Packaging checks

List archive contents and verify PHP and asset paths, required files only, no source ZIP or temporary files, correct `INSTALL.txt` caller, and a successful archive integrity test.

## 7. Handoff format

Lead with the completed result. Include the download or file location when requested, exact caller, final file tree, editable areas, conditional/cache-versioned asset statement, and any installation instruction. Do not force the user to reconstruct paths.
