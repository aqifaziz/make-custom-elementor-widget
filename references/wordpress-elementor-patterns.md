# WordPress and Elementor patterns

## Contents

1. Naming
2. Elementor panel icon
3. Conditional assets
4. Widget registration
5. Controls
6. Links and images
7. Article TOC
8. JavaScript lifecycle

## 1. Naming

Derive a collision-resistant namespace from the current project, page, and section. Keep the PHP function prefix, class name, Elementor widget name, asset handle, root CSS class, and file paths synchronized.

Example neutral tokens:

```text
prefix: demo_feature_s1
widget name: demo_feature_section_1
handle: demo-feature-s1
root class: demo-feature-s1
```

Never reuse this example literally if the input identifies another project.

## 2. Elementor panel icon

The icon shown beside the widget in Elementor's Elements panel comes from `get_icon()` and is separate from any icon rendered inside the frontend section.

Ask the user to paste a built-in Elementor icon class such as `eicon-posts-grid`, or let them answer `Automatic`. For automatic selection, choose the closest meaningful built-in `eicon-*` class after inspecting the section. Examples include:

```text
hero or banner: eicon-banner
cards or directory: eicon-posts-grid
article or content details: eicon-post-content
FAQ or accordion: eicon-accordion
image gallery: eicon-gallery-grid
forms: eicon-form-horizontal
navigation or table of contents: eicon-table-of-contents
generic multi-column section: eicon-columns
```

Verify the chosen class exists in the Elementor version used by the project when that environment is available. Fall back to `eicon-columns` only when no more relevant verified icon is available. Do not add Font Awesome, a remote icon kit, or another frontend dependency merely for the Elements-panel icon.

```php
public function get_icon() {
    return 'eicon-posts-grid';
}
```

A truly custom Elementor-panel icon requires an explicitly supplied asset or design and isolated editor-only CSS. Do not confuse that with Elementor's `ICONS` control, which edits icons displayed inside the frontend widget.

## 3. Conditional assets

Register assets early, detect the widget in Elementor data, and enqueue before the page head is printed:

```php
function prefix_register_assets() {
    $css_rel = '/assets/page-key/section-1/style.css';
    $js_rel  = '/assets/page-key/section-1/app.js';
    $css_abs = get_theme_file_path( $css_rel );
    $js_abs  = get_theme_file_path( $js_rel );

    if ( is_readable( $css_abs ) && ! wp_style_is( 'prefix-section-1', 'registered' ) ) {
        wp_register_style( 'prefix-section-1', get_theme_file_uri( $css_rel ), array(), (string) filemtime( $css_abs ) );
    }

    if ( is_readable( $js_abs ) && ! wp_script_is( 'prefix-section-1', 'registered' ) ) {
        wp_register_script( 'prefix-section-1', get_theme_file_uri( $js_rel ), array(), (string) filemtime( $js_abs ), true );
    }
}
add_action( 'wp_enqueue_scripts', 'prefix_register_assets', 1 );
add_action( 'elementor/frontend/after_register_styles', 'prefix_register_assets', 1 );
add_action( 'elementor/frontend/after_register_scripts', 'prefix_register_assets', 1 );

function prefix_page_has_widget() {
    $post_id = get_queried_object_id();
    if ( ! $post_id ) {
        return false;
    }

    $data = get_post_meta( $post_id, '_elementor_data', true );
    return is_string( $data ) && false !== strpos( $data, 'prefix_widget_name' );
}

function prefix_maybe_enqueue_assets() {
    prefix_register_assets();
    if ( prefix_page_has_widget() ) {
        wp_enqueue_style( 'prefix-section-1' );
        wp_enqueue_script( 'prefix-section-1' );
    }
}
add_action( 'wp_enqueue_scripts', 'prefix_maybe_enqueue_assets', 5 );
```

Return dependency handles from the widget as an editor/preview fallback:

```php
public function get_style_depends() {
    prefix_register_assets();
    return array( 'prefix-section-1' );
}

public function get_script_depends() {
    prefix_register_assets();
    return array( 'prefix-section-1' );
}
```

Omit script registration and `get_script_depends()` when the section has no JavaScript.

## 4. Widget registration

```php
add_action(
    'elementor/widgets/register',
    static function ( $widgets_manager ) {
        if ( ! class_exists( '\\Elementor\\Widget_Base' ) || class_exists( 'Prefix_Section_Widget', false ) ) {
            return;
        }

        class Prefix_Section_Widget extends \Elementor\Widget_Base {
            public function get_name() { return 'prefix_widget_name'; }
            public function get_title() { return esc_html__( 'Page SEC - 1', 'project-textdomain' ); }
            public function get_icon() { return 'eicon-posts-grid'; }
            public function get_categories() { return array( 'project-category' ); }
        }

        $widgets_manager->register( new Prefix_Section_Widget() );
    }
);
```

Guard category creation if several section files may add the same category. Follow the target Elementor version's category API.

## 5. Controls

Text:

```php
$this->add_control( 'heading', array(
    'label'       => 'Heading',
    'type'        => \Elementor\Controls_Manager::TEXT,
    'default'     => 'Source heading',
    'label_block' => true,
) );
```

Rich article:

```php
$this->add_control( 'article', array(
    'label'   => 'Complete article',
    'type'    => \Elementor\Controls_Manager::WYSIWYG,
    'default' => '<h2>Source heading</h2><p>Source content.</p>',
) );
```

Repeater:

```php
$item = new \Elementor\Repeater();
$item->add_control( 'title', array(
    'label' => 'Title',
    'type'  => \Elementor\Controls_Manager::TEXT,
) );
$item->add_control( 'link', array(
    'label'         => 'Link',
    'type'          => \Elementor\Controls_Manager::URL,
    'show_external' => true,
) );

$this->add_control( 'items', array(
    'label'       => 'Items',
    'type'        => \Elementor\Controls_Manager::REPEATER,
    'fields'      => $item->get_controls(),
    'default'     => $this->source_defaults(),
    'title_field' => '{{{ title }}}',
) );
```

Color through a scoped variable:

```php
$this->add_control( 'accent', array(
    'label'     => 'Accent color',
    'type'      => \Elementor\Controls_Manager::COLOR,
    'default'   => '#2563eb',
    'selectors' => array(
        '{{WRAPPER}} .prefix-root' => '--section-accent: {{VALUE}};',
    ),
) );
```

## 6. Links and images

Use Elementor's link attributes API for non-repeater links:

```php
$this->add_link_attributes( 'cta', $settings['cta_link'] );
echo '<a ' . $this->get_render_attribute_string( 'cta' ) . '>';
```

When links live in repeaters, build escaped attributes and preserve `is_external` and `nofollow`. Add `noopener noreferrer` to external targets.

Use `MEDIA` controls. Resolve attachment URLs and metadata through Elementor/WordPress helpers. Render editable alt text; if empty, fall back to the attachment alt. Reserve dimensions whenever known.

```php
echo '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '" width="' . esc_attr( $width ) . '" height="' . esc_attr( $height ) . '" loading="lazy">';
```

For a true above-fold LCP image only, use `loading="eager" fetchpriority="high"`.

## 7. Article TOC

Sanitize rich content before rendering. For a generated TOC, use a server-side heading callback so links exist before JavaScript:

```php
$toc   = array();
$used  = array();
$clean = wp_kses_post( $content );

$clean = preg_replace_callback(
    '/<h2\\b([^>]*)>(.*?)<\\/h2>/is',
    static function ( $match ) use ( &$toc, &$used, $instance ) {
        $label = trim( wp_strip_all_tags( $match[2] ) );
        $slug  = sanitize_title( $label ?: 'section-' . ( count( $toc ) + 1 ) );
        $base  = $slug;
        $n     = 2;

        while ( isset( $used[ $slug ] ) ) {
            $slug = $base . '-' . $n++;
        }

        $used[ $slug ] = true;
        $id = $instance . '-' . $slug;
        $toc[] = array( 'id' => $id, 'label' => $label );
        $attrs = preg_replace( "/\\s+id\\s*=\\s*([\"']).*?\\1/i", '', $match[1] );

        return '<h2' . $attrs . ' id="' . esc_attr( $id ) . '">' . $match[2] . '</h2>';
    },
    $clean
);
```

Prefix IDs with `$this->get_id()` to prevent collisions across instances.

## 8. JavaScript lifecycle

Prefer delegated listeners scoped by a root class for simple behavior. For complex initialization, register an Elementor frontend hook:

```js
window.addEventListener('elementor/frontend/init', function () {
  elementorFrontend.hooks.addAction(
    'frontend/element_ready/prefix_widget_name.default',
    function ($scope) {
      initSection($scope[0]);
    }
  );
});
```

Keep `initSection` idempotent with a `data-initialized` guard. Do not store state in global variables shared by widget instances.
