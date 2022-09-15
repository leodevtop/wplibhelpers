<?php
function tict_products_customize_register( $wp_customize )
{
	class WP_Customize_Textarea_Control extends WP_Customize_Control
	{
		public $type = 'textarea';

		public function render_content()
		{
		?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<textarea rows="5" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
			</label>
		<?php
		}
	}

if(function_exists('pll_languages_list'))
{
	$_lang = pll_languages_list();
}

	$wp_customize->add_section('slideshow_section',array(
			'title' => __('Slideshow', 'tict_products'),
			'description' => '',
			'priority' => null
	));
	$wp_customize->add_setting('slideshow_ids',array(
			'capability' => 'edit_theme_options',
			'default'	=> '100,101',
			'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('slideshow_ids',array(
			'label'	=> __('Ids page/post', 'tict_products'),
			'section'	=> 'slideshow_section',
			'settings'	=> 'slideshow_ids'
	));
	$wp_customize->add_section('welcome_section',array(
			'title' => __('Welcome home', 'tict_products'),
			'description' => '',
			'priority' => null
	));
	$wp_customize->add_setting('welcome',array(
			'capability' => 'edit_theme_options',
			'default'	=> '0',
			'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('welcome',array(
			'type'	=> 'dropdown-pages',
			'label'	=> __('Select page Welcome', 'tict_products'),
			'section'	=> 'welcome_section',
			'settings'	=> 'welcome'
	));
	$wp_customize->add_section('social_sec',array(
			'title' => __('Social Settings', 'tict_products'),
			'description' => '',
			'priority' => null
	));

	$wp_customize->add_setting('fb_link',array(
			'capability' => 'edit_theme_options',
			'default'	=> '#facebook',
			'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('fb_link',array(
			'label'	=> __('Add facebook link here', 'tict_products'),
			'section'	=> 'social_sec',
			'settings'	=> 'fb_link'
	));
	$wp_customize->add_setting('twitt_link',array(
			'capability' => 'edit_theme_options',
			'default'	=> '#twitter',
			'sanitize_callback' => 'esc_url_raw'
	));
	
	$wp_customize->add_control('twitt_link',array(
			'label'	=> __('Add twitter link here', 'tict_products'),
			'section'	=> 'social_sec',
			'settings'	=> 'twitt_link'
	));
	$wp_customize->add_setting('gplus_link',array(
			'capability' => 'edit_theme_options',
			'default'	=> '#gplus',
			'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('gplus_link',array(
			'label'	=> __('Add google plus link here', 'tict_products'),
			'section'	=> 'social_sec',
			'settings'	=> 'gplus_link'
	));
	$wp_customize->add_setting('youtube_link',array(
			'capability' => 'edit_theme_options',
			'default'	=> '#youtube',
			'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('youtube_link',array(
			'label'	=> __('Add youtube link here', 'tict_products'),
			'section'	=> 'social_sec',
			'settings'	=> 'youtube_link'
	));
	$wp_customize->add_setting('instagram_link',array(
			'capability' => 'edit_theme_options',
			'default'	=> '#instagram',
			'sanitize_callback'	=> 'esc_url_raw'
	));
	$wp_customize->add_control('instagram_link',array(
			'label'	=> __('Add Instagram link here', 'tict_products'),
			'section'	=> 'social_sec',
			'settings'	=> 'instagram_link'
	));


	$wp_customize->add_section('info_section',array(
			'title' => __('Infomation Company', 'tict_products'),
			'description' => '',
			'priority' => null
	));
	
	$wp_customize->add_setting('company_name',array(
			'capability' => 'edit_theme_options',
			'default'=> __('My Company', 'tict_products'),
			'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('company_name',array(
			'label'	=> __('Company name', 'tict_products'),
			'section'	=> 'info_section',
			'settings'	=> 'company_name',
			'type'    => 'text',
	));

	if(isset($_lang))
	{
		foreach($_lang as $_lg)
		{
			$wp_customize->add_setting('company_name_'.$_lg,array(
					'capability' => 'edit_theme_options',
					'default'=> __('My Company', 'tict_products'),
					'sanitize_callback' => 'sanitize_text_field'
			));
			$wp_customize->add_control('company_name_'.$_lg,array(
					'label'	=> __('Company name - '.$_lg, 'tict_products'),
					'section'	=> 'info_section',
					'settings'	=> 'company_name_'.$_lg,
					'type'    => 'text',
			));
		}
	}
	$wp_customize->add_setting('slogan',array(
			'capability' => 'edit_theme_options',
			'default'=> __('I\'m here!', 'tict_products'),
			'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('slogan',array(
			'label'	=> __('Slogan', 'tict_products'),
			'section'	=> 'info_section',
			'settings'	=> 'slogan',
			'type'    => 'text',
	));

	if(isset($_lang))
	{
		foreach($_lang as $_lg)
		{
			$wp_customize->add_setting('philosophy_'.$_lg,array(
					'capability' => 'edit_theme_options',
					'default'=> __('Consectetur, adipisci velit, sed quiaony on numquam eius modi tempora incidunt, ut laboret dolore agnam aliquam quaeratine voluptatem. ut enim ad minima veniamting suscipit lab', 'tict_products'),
					'sanitize_callback' => 'esc_textarea'
			));
			$wp_customize->add_control('philosophy_'.$_lg,array(
					'label'	=> __('Philosophy - '.$_lg, 'tict_products'),
					'section'	=> 'info_section',
					'settings'	=> 'philosophy_'.$_lg,
					'type'    => 'textarea',
			));
		}
	}
	$wp_customize->add_setting('address',array(
			'capability' => 'edit_theme_options',
			'default'=> __('Vietnam', 'tict_products'),
			'sanitize_callback' => false
	));
	$wp_customize->add_control('address',array(
			'label'	=> __('Address', 'tict_products'),
			'section'	=> 'info_section',
			'settings'	=> 'address',
			'type'    => 'textarea',
	));

	if(isset($_lang))
	{
		foreach($_lang as $_lg)
		{
			$wp_customize->add_setting('address_'.$_lg,array(
					'capability' => 'edit_theme_options',
					'default'=> __('Vietnam', 'tict_products'),
					'sanitize_callback' => 'sanitize_text_field'
			));
			$wp_customize->add_control('address_'.$_lg,array(
					'label'	=> __('Address - '.$_lg, 'tict_products'),
					'section'	=> 'info_section',
					'settings'	=> 'address_'.$_lg,
					'type'    => 'text',
			));
		}
	}

	$wp_customize->add_setting('telephone',array(
			'capability' => 'edit_theme_options',
			'default'=> __('0987 654 321', 'tict_products'),
			'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('telephone',array(
			'label'	=> __('Tel', 'tict_products'),
			'section'	=> 'info_section',
			'settings'	=> 'telephone',
			'type'    => 'text',
	));
	$wp_customize->add_setting('phone',array(
			'capability' => 'edit_theme_options',
			'default'=> __('02827 654 321', 'tict_products'),
			'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('phone',array(
			'label'	=> __('Phone', 'tict_products'),
			'section'	=> 'info_section',
			'settings'	=> 'phone',
			'type'    => 'text',
	));
	$wp_customize->add_setting('email',array(
			'capability' => 'edit_theme_options',
			'default'=> __('admin@admin.com', 'tict_products'),
			'sanitize_callback' => 'sanitize_text_field'
	));
	$wp_customize->add_control('email',array(
			'label'	=> __('Email', 'tict_products'),
			'section'	=> 'info_section',
			'settings'	=> 'email',
			'type'    => 'text',
	));
	$wp_customize->add_setting('map_link',array(
			'capability' => 'edit_theme_options',
			'default'=> __('', 'tict_products'),
			'sanitize_callback' => false
	));
	$wp_customize->add_control('map_link',array(
			'label'	=> __('Map link (iframe)', 'tict_products'),
			'section'	=> 'info_section',
			'settings'	=> 'map_link',
			'type'    => 'textarea',
	));

}
add_action( 'customize_register', 'tict_products_customize_register' );