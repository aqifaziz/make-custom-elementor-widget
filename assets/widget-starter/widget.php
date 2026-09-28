<?php
/**
 * Replace every {{TOKEN}} before shipping this file.
 * Caller:
 * require_once get_theme_file_path( '/inc/elementor-widgets/{{PAGE_KEY}}/{{WIDGET_FILE}}.php' );
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( '{{FUNCTION_PREFIX}}_register_assets' ) ) {
	function {{FUNCTION_PREFIX}}_register_assets() {
		$css_rel = '/assets/{{PAGE_KEY}}/section-{{SECTION_NUMBER}}/style.css';
		$js_rel  = '/assets/{{PAGE_KEY}}/section-{{SECTION_NUMBER}}/app.js';
		$css_abs = get_theme_file_path( $css_rel );
		$js_abs  = get_theme_file_path( $js_rel );

		if ( is_readable( $css_abs ) && ! wp_style_is( '{{ASSET_HANDLE}}', 'registered' ) ) {
			wp_register_style( '{{ASSET_HANDLE}}', get_theme_file_uri( $css_rel ), array(), (string) filemtime( $css_abs ) );
		}

		if ( is_readable( $js_abs ) && ! wp_script_is( '{{ASSET_HANDLE}}', 'registered' ) ) {
			wp_register_script( '{{ASSET_HANDLE}}', get_theme_file_uri( $js_rel ), array(), (string) filemtime( $js_abs ), true );
		}
	}
	add_action( 'wp_enqueue_scripts', '{{FUNCTION_PREFIX}}_register_assets', 1 );
	add_action( 'elementor/frontend/after_register_styles', '{{FUNCTION_PREFIX}}_register_assets', 1 );
	add_action( 'elementor/frontend/after_register_scripts', '{{FUNCTION_PREFIX}}_register_assets', 1 );
}

if ( ! function_exists( '{{FUNCTION_PREFIX}}_page_has_widget' ) ) {
	function {{FUNCTION_PREFIX}}_page_has_widget() {
		$post_id = get_queried_object_id();
		if ( ! $post_id ) {
			return false;
		}

		$data = get_post_meta( $post_id, '_elementor_data', true );
		return is_string( $data ) && false !== strpos( $data, '{{WIDGET_NAME}}' );
	}
}

if ( ! function_exists( '{{FUNCTION_PREFIX}}_maybe_enqueue_assets' ) ) {
	function {{FUNCTION_PREFIX}}_maybe_enqueue_assets() {
		{{FUNCTION_PREFIX}}_register_assets();
		if ( {{FUNCTION_PREFIX}}_page_has_widget() ) {
			wp_enqueue_style( '{{ASSET_HANDLE}}' );
			wp_enqueue_script( '{{ASSET_HANDLE}}' );
		}
	}
	add_action( 'wp_enqueue_scripts', '{{FUNCTION_PREFIX}}_maybe_enqueue_assets', 5 );
}

add_action(
	'elementor/elements/categories_registered',
	static function ( $manager ) {
		$manager->add_category(
			'{{CATEGORY_SLUG}}',
			array(
				'title' => esc_html__( '{{CATEGORY_TITLE}}', '{{TEXT_DOMAIN}}' ),
				'icon'  => 'eicon-site-identity',
			)
		);
	}
);

add_action(
	'elementor/widgets/register',
	static function ( $widgets_manager ) {
		if ( ! class_exists( '\\Elementor\\Widget_Base' ) || class_exists( '{{CLASS_NAME}}', false ) ) {
			return;
		}

		class {{CLASS_NAME}} extends \Elementor\Widget_Base {
			public function get_name() { return '{{WIDGET_NAME}}'; }
			public function get_title() { return esc_html__( '{{WIDGET_TITLE}}', '{{TEXT_DOMAIN}}' ); }
			public function get_icon() { return '{{ELEMENTOR_PANEL_ICON}}'; }
			public function get_categories() { return array( '{{CATEGORY_SLUG}}' ); }

			public function get_style_depends() {
				{{FUNCTION_PREFIX}}_register_assets();
				return array( '{{ASSET_HANDLE}}' );
			}

			public function get_script_depends() {
				{{FUNCTION_PREFIX}}_register_assets();
				return array( '{{ASSET_HANDLE}}' );
			}

			protected function register_controls() {
				$this->start_controls_section( 'content', array( 'label' => 'Content' ) );
				$this->add_control( 'heading', array(
					'label'       => 'Heading',
					'type'        => \Elementor\Controls_Manager::TEXT,
					'default'     => '{{DEFAULT_HEADING}}',
					'label_block' => true,
				) );
				$this->add_control( 'description', array(
					'label'   => 'Description',
					'type'    => \Elementor\Controls_Manager::TEXTAREA,
					'default' => '{{DEFAULT_DESCRIPTION}}',
				) );
				$this->add_control( 'cta_text', array(
					'label'   => 'Button text',
					'type'    => \Elementor\Controls_Manager::TEXT,
					'default' => '{{DEFAULT_CTA}}',
				) );
				$this->add_control( 'cta_link', array(
					'label'         => 'Button link',
					'type'          => \Elementor\Controls_Manager::URL,
					'show_external' => true,
				) );
				$this->end_controls_section();

				$this->start_controls_section( 'appearance', array(
					'label' => 'Appearance',
					'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
				) );
				$this->add_control( 'accent', array(
					'label'     => 'Accent color',
					'type'      => \Elementor\Controls_Manager::COLOR,
					'default'   => '{{DEFAULT_ACCENT}}',
					'selectors' => array( '{{WRAPPER}} .{{ROOT_CLASS}}' => '--section-accent: {{VALUE}};' ),
				) );
				$this->end_controls_section();
			}

			protected function render() {
				$s = $this->get_settings_for_display();
				$this->add_link_attributes( 'cta', $s['cta_link'] );
				?>
				<section class="{{ROOT_CLASS}}" aria-labelledby="{{ROOT_CLASS}}-title-<?php echo esc_attr( $this->get_id() ); ?>">
					<div class="{{ROOT_CLASS}}__inner">
						<h2 id="{{ROOT_CLASS}}-title-<?php echo esc_attr( $this->get_id() ); ?>" class="{{ROOT_CLASS}}__title"><?php echo esc_html( $s['heading'] ); ?></h2>
						<p class="{{ROOT_CLASS}}__description"><?php echo esc_html( $s['description'] ); ?></p>
						<?php if ( ! empty( $s['cta_text'] ) && ! empty( $s['cta_link']['url'] ) ) : ?>
							<a class="{{ROOT_CLASS}}__button" <?php echo $this->get_render_attribute_string( 'cta' ); ?>><?php echo esc_html( $s['cta_text'] ); ?></a>
						<?php endif; ?>
					</div>
				</section>
				<?php
			}
		}

		$widgets_manager->register( new {{CLASS_NAME}}() );
	}
);
