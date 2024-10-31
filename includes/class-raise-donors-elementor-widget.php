<?php

class Raise_Donors_Elementor_Widget extends \Elementor\Widget_Base {

	public function __construct( $data = [], $args = null ) {
		parent::__construct( $data, $args );


	}

	public function get_name() {
		return 'raise-donors';
	}

	public function get_title() {
		return __( 'RaiseDonors Form', 'raise-donors' );
	}

	public function get_icon() {
		return 'rd_elementor_widget';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'raise-donors' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'campaign',
			[
				'label_block' => true,
				'label'       => __( 'Select Donation Page', 'raise-donors' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'options'     => [
				],
			]
		);


		$this->add_control(
			'display_style',
			[
				'label_block' => true,
				'label'       => __( 'Select Display Style', 'raise-donors' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'full',
				'options'     => [
					'full' => __( 'Show Page Content + Donation Form', 'raise-donors' ),
					'form' => __( 'Show Donation Form Only', 'raise-donors' )
				],
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( ! $settings['campaign'] ) {
			return '';
		}

		if ( ! $settings['display_style'] ) {
			$settings['display_style'] = 'form';
		}

		echo do_shortcode( '[raise-donors campaignId="' . $settings['campaign'] . '" display="' . $settings['display_style'] . '"]' );
	}
}