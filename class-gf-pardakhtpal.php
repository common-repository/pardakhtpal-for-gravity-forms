<?php

add_action( 'wp', array( 'GFPardakhtPal', 'maybe_thankyou_page' ), 5 );

GFForms::include_payment_addon_framework();

    
class GFPardakhtPal extends GFPaymentAddOn {

	protected $_version = GF_PARDAKHTPAL_VERSION;
	protected $_min_gravityforms_version = '1.8.12';
	protected $_slug = 'gravityformspardakhtpal';
	protected $_path = 'gf-pardakhtpal/gf-pardakhtpal.php';
	protected $_full_path = __FILE__;
	protected $_url = 'http://www.gravityforms.com';
	protected $_title = '&#1583;&#1585;&#1711;&#1575;&#1607; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604; &#1576;&#1585;&#1575;&#1740; &#1575;&#1601;&#1586;&#1608;&#1606;&#1607; &#1711;&#1585;&#1575;&#1608;&#1740;&#1578;&#1740; &#1601;&#1608;&#1585;&#1605;';
	protected $_short_title = '&#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604;';
	protected $_supports_callbacks = true;
	private $production_url = '';              // bad edit shavad-------------------------
    private $pp_msg = '';

	// Members plugin integration
	protected $_capabilities = array( 'gravityforms_pardakhtpal', 'gravityforms_pardakhtpal_uninstall' );

	// Permissions
	protected $_capabilities_settings_page = 'gravityforms_pardakhtpal';
	protected $_capabilities_form_settings = 'gravityforms_pardakhtpal';
	protected $_capabilities_uninstall = 'gravityforms_pardakhtpal_uninstall';

	// Automatic upgrade enabled
	protected $_enable_rg_autoupgrade = true;

	private static $_instance = null;

	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new GFPardakhtPal();
		}

		return self::$_instance;
	}

	private function __clone() {
	} /* do nothing */

	public function init_frontend() {
		parent::init_frontend();

		add_filter( 'gform_disable_post_creation', array( $this, 'delay_post' ), 10, 3 );
		add_filter( 'gform_disable_notification', array( $this, 'delay_notification' ), 10, 4 );
        
	}


	//----- SETTINGS PAGES ----------//

	public function plugin_settings_fields() {
        $description = '&#1580;&#1607;&#1578; &#1575;&#1587;&#1578;&#1601;&#1575;&#1583;&#1607; &#1575;&#1586; &#1583;&#1585;&#1711;&#1575;&#1607; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604; &#1576;&#1575;&#1740;&#1583; &#1575;&#1576;&#1578;&#1583;&#1575; &#1608;&#1576;&#1587;&#1575;&#1740;&#1578;&#1578;&#1575;&#1606; &#1585;&#1575; &#1583;&#1585; &#1575;&#1705;&#1575;&#1606;&#1578; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604;&#1578;&#1575;&#1606; &#1601;&#1593;&#1575;&#1604; &#1606;&#1605;&#1575;&#1740;&#1740;&#1583;&#1548; &#1587;&#1662;&#1587; &#1705;&#1583; API &#1605;&#1582;&#1589;&#1608;&#1589; &#1608;&#1576;&#1587;&#1575;&#1740;&#1578; &#1582;&#1608;&#1583;&#1578;&#1575;&#1606; &#1585;&#1575; &#1705;&#1607; &#1575;&#1586; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604; &#1583;&#1585;&#1740;&#1575;&#1601;&#1578; &#1606;&#1605;&#1608;&#1583;&#1607; &#1575;&#1740;&#1583; &#1583;&#1585; &#1586;&#1740;&#1585; &#1608;&#1575;&#1585;&#1583; &#1606;&#1605;&#1575;&#1740;&#1740;&#1583;. ';
		return array(
			array(
				'title'       => '',
				'description' => $description,
				'fields'      => array(
                	array(
						'name'    => 'gf_pardakhtpal_api',
						'label'   => '&#1705;&#1583; API &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604;',
						'type'    => 'text'
					),
					array(
						'name'    => 'gf_pardakhtpal_configured',
						'label'   => '&#1601;&#1593;&#1575;&#1604; &#1587;&#1575;&#1586;&#1740; &#1583;&#1585;&#1711;&#1575;&#1607; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604;',
						'type'    => 'checkbox',
						'choices' => array( array( 'label' => '&#1601;&#1593;&#1575;&#1604; &#1587;&#1575;&#1586;&#1740; &#1583;&#1585;&#1711;&#1575;&#1607; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604;', 'name' => 'gf_pardakhtpal_configured' ) )
					),
					array(
						'type' => 'save',
						'messages' => array( 'success' => '&#1578;&#1606;&#1592;&#1740;&#1605;&#1575;&#1578; &#1576;&#1575; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1576;&#1607; &#1585;&#1608;&#1586; &#1588;&#1583;.' ),
					),
				),
			),
		);
	}

	public function feed_list_no_item_message(){
		$settings = $this->get_plugin_settings();
		if ( ! rgar( $settings, 'gf_pardakhtpal_configured' ) ){
			return '<a href="' . admin_url( 'admin.php?page=gf_settings&subview=' . $this->_slug ) . '">&#1575;&#1606;&#1578;&#1602;&#1575;&#1604; &#1576;&#1607; &#1589;&#1601;&#1581;&#1607; &#1578;&#1606;&#1592;&#1740;&#1605;&#1575;&#1578; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604;</a>';
		}
		else {
			return parent::feed_list_no_item_message();
		}
	}

	public function feed_settings_fields() {
		$default_settings = parent::feed_settings_fields();

		//--add PardakhtPal fields
		$fields = array(
			array(
				'name'     => 'pardakhtpalapi',
				'label'    => '&#1705;&#1583; API &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604;',
				'type'     => 'text',
				'class'    => 'medium',
				'required' => true,
            )
		);

		$default_settings = parent::add_field_after( 'feedName', $fields, $default_settings );
		//--------------------------------------------------------------------------------------

		//--add donation to transaction type drop down
		$transaction_type = parent::get_field( 'transactionType', $default_settings );
		$choices          = $transaction_type['choices'];
		$add_donation     = true;
		foreach ( $choices as $choice ) {
			//add donation option if it does not already exist
			if ( $choice['value'] == 'donation' ) {
				$add_donation = false;
			}
		}
		if ( $add_donation ) {
			//add donation transaction type
			$choices[] = array( 'label' => __( 'Donations', 'gravityformspardakhtpal' ), 'value' => 'donation' );
		}
		$transaction_type['choices'] = $choices;
		$default_settings            = $this->replace_field( 'transactionType', $transaction_type, $default_settings );
		//-------------------------------------------------------------------------------------------------

		//--add Page Style, Continue Button Label, Cancel URL
		$fields = array(
			array(
				'name'     => 'continueText',
				'label'    => '&#1593;&#1606;&#1608;&#1575;&#1606; &#1583;&#1705;&#1605;&#1607; &#1585;&#1601;&#1578;&#1606; &#1576;&#1607; &#1605;&#1585;&#1581;&#1604;&#1607; &#1576;&#1593;&#1583;',
				'type'     => 'text',
				'class'    => 'medium',
				'required' => false,
				'tooltip'  => '<h6>&#1593;&#1606;&#1608;&#1575;&#1606; &#1583;&#1705;&#1605;&#1607; &#1585;&#1601;&#1578;&#1606; &#1576;&#1607; &#1605;&#1585;&#1581;&#1604;&#1607; &#1576;&#1593;&#1583;</h6>&#1593;&#1606;&#1608;&#1575;&#1606;&#1740; &#1576;&#1585;&#1575;&#1740; &#1583;&#1705;&#1605;&#1607; &#1585;&#1601;&#1578;&#1606; &#1576;&#1607; &#1605;&#1585;&#1581;&#1604;&#1607; &#1576;&#1593;&#1583; &#1608;&#1575;&#1585;&#1583; &#1606;&#1605;&#1575;&#1740;&#1740;&#1583; &#1578;&#1575; &#1576;&#1607; &#1705;&#1575;&#1585;&#1576;&#1585; &#1583;&#1585; &#1586;&#1605;&#1575;&#1606;&#1740; &#1705;&#1607; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1578;&#1608;&#1587;&#1591; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1570;&#1605;&#1740;&#1586; &#1576;&#1608;&#1583; &#1606;&#1605;&#1575;&#1740;&#1588; &#1583;&#1575;&#1583;&#1607; &#1588;&#1608;&#1583;.'
			),
			array(
				'name'     => 'cancelUrl',
				'label'    => '&#1604;&#1740;&#1606;&#1705; &#1604;&#1594;&#1608; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578;',
				'type'     => 'text',
				'class'    => 'medium',
				'required' => false,
				'tooltip'  => '<h6>&#1604;&#1740;&#1606;&#1705; &#1604;&#1594;&#1608; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578;</h6>&#1570;&#1583;&#1585;&#1587;&#1740; &#1576;&#1585;&#1575;&#1740; &#1575;&#1585;&#1587;&#1575;&#1604; &#1705;&#1575;&#1585;&#1576;&#1585; &#1583;&#1585; &#1589;&#1608;&#1585;&#1578; &#1604;&#1594;&#1608; &#1593;&#1605;&#1604;&#1740;&#1575;&#1578; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1583;&#1585; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604; &#1740;&#1575; &#1583;&#1585;&#1711;&#1575;&#1607; &#1576;&#1575;&#1606;&#1705; &#1608;&#1575;&#1585;&#1583; &#1606;&#1605;&#1575;&#1740;&#1740;&#1583;.'
			),
			array(
				'name'    => 'options',
				'label'   => '&#1578;&#1606;&#1592;&#1740;&#1605;&#1575;&#1578;',
				'type'    => 'options',
				'tooltip' => '<h6>&#1578;&#1606;&#1592;&#1740;&#1605;&#1575;&#1578;</h6>&#1601;&#1593;&#1575;&#1604; &#1740;&#1575; &#1594;&#1740;&#1585; &#1601;&#1593;&#1575;&#1604; &#1587;&#1575;&#1586;&#1740; &#1711;&#1586;&#1740;&#1606;&#1607; &#1607;&#1575;&#1740; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; '
			),
			array(
				'name'    => 'notifications',
				'label'   => '&#1607;&#1588;&#1583;&#1575;&#1585;&#1607;&#1575;',
				'type'    => 'notifications',
				'tooltip' => '<h6>&#1607;&#1588;&#1583;&#1575;&#1585;&#1607;&#1575;</h6>&#1575;&#1711;&#1585; &#1605;&#1740; &#1582;&#1608;&#1575;&#1607;&#1740;&#1583; &#1662;&#1740;&#1594;&#1575;&#1605; &#1579;&#1576;&#1578; &#1601;&#1585;&#1605; &#1662;&#1587; &#1575;&#1586; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1570;&#1605;&#1740;&#1586; &#1575;&#1585;&#1587;&#1575;&#1604; &#1588;&#1608;&#1583; &#1575;&#1740;&#1606; &#1711;&#1586;&#1740;&#1606;&#1607; &#1585;&#1575; &#1601;&#1593;&#1575;&#1604; &#1606;&#1605;&#1575;&#1740;&#1740;&#1583;&#1548; &#1583;&#1585; &#1594;&#1740;&#1585; &#1575;&#1740;&#1606; &#1589;&#1608;&#1585;&#1578; &#1662;&#1740;&#1575;&#1605; &#1579;&#1576;&#1578; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1570;&#1605;&#1740;&#1586; &#1601;&#1585;&#1605; &#1576;&#1607; &#1605;&#1581;&#1590; &#1601;&#1588;&#1585;&#1583;&#1606; &#1705;&#1604;&#1740;&#1583; &#1579;&#1576;&#1578; &#1575;&#1585;&#1587;&#1575;&#1604; &#1605;&#1740; &#1588;&#1608;&#1583;.'
			),
		);

		//Add post fields if form has a post
		$form = $this->get_current_form();
		if ( GFCommon::has_post_field( $form['fields'] ) ) {
			$post_settings = array(
				'name'    => 'post_checkboxes',
				'label'   => '&#1662;&#1587;&#1578; &#1607;&#1575;',
				'type'    => 'checkbox',
				'tooltip' => '<h6>&#1662;&#1587;&#1578; &#1607;&#1575;</h6> &#1575;&#1740;&#1606; &#1711;&#1586;&#1740;&#1606;&#1607; &#1585;&#1575; &#1601;&#1593;&#1575;&#1604; &#1606;&#1605;&#1575;&#1740;&#1740;&#1583; &#1578;&#1575; &#1578;&#1606;&#1607;&#1575; &#1583;&#1585; &#1589;&#1608;&#1585;&#1578; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1570;&#1605;&#1740;&#1586; &#1576;&#1608;&#1583;&#1606; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1740;&#1705; &#1662;&#1587;&#1578; &#1579;&#1576;&#1578; &#1588;&#1608;&#1583;.',
				'choices' => array(
					array( 'label' => '&#1575;&#1740;&#1580;&#1575;&#1583; &#1662;&#1587;&#1578; &#1578;&#1606;&#1607;&#1575; &#1583;&#1585; &#1589;&#1608;&#1585;&#1578; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1570;&#1605;&#1740;&#1586; &#1576;&#1608;&#1583;&#1606; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578;', 'name' => 'delayPost' ),
				),
			);

			if ( $this->get_setting( 'transactionType' ) == 'subscription' ) {
				$post_settings['choices'][] = array(
					'label'    => '&#1578;&#1594;&#1740;&#1740;&#1585; &#1608;&#1590;&#1593;&#1740;&#1578; &#1662;&#1587;&#1578; &#1583;&#1585; &#1589;&#1608;&#1585;&#1578; &#1604;&#1594;&#1608; &#1593;&#1590;&#1608;&#1740;&#1578;.',
					'name'     => 'change_post_status',
					'onChange' => 'var action = this.checked ? "draft" : ""; jQuery("#update_post_action").val(action);',
				);
			}

			$fields[] = $post_settings;
		}

		//Adding custom settings for backwards compatibility with hook 'gform_pardakhtpal_add_option_group'
		$fields[] = array(
			'name'  => 'custom_options',
			'label' => '',
			'type'  => 'custom',
		);

		$default_settings = $this->add_field_after( 'billingInformation', $fields, $default_settings );
		//-----------------------------------------------------------------------------------------

		//--get billing info section and add customer first/last name
		$billing_info   = parent::get_field( 'billingInformation', $default_settings );
		$billing_fields = $billing_info['field_map'];
		$add_first_name = true;
		$add_last_name  = true;
		foreach ( $billing_fields as $mapping ) {
			//add first/last name if it does not already exist in billing fields
			if ( $mapping['name'] == 'firstName' ) {
				$add_first_name = false;
			} else if ( $mapping['name'] == 'lastName' ) {
				$add_last_name = false;
			}
		}

		if ( $add_last_name ) {
			//add last name
			array_unshift( $billing_info['field_map'], array( 'name' => 'lastName', 'label' => '&#1606;&#1575;&#1605; &#1582;&#1575;&#1606;&#1608;&#1575;&#1583;&#1711;&#1740;', 'required' => false ) );
		}
		if ( $add_first_name ) {
			array_unshift( $billing_info['field_map'], array( 'name' => 'firstName', 'label' => '&#1606;&#1575;&#1605;', 'required' => false ) );
		}
		$default_settings = parent::replace_field( 'billingInformation', $billing_info, $default_settings );
		//----------------------------------------------------------------------------------------------------

		//hide default display of setup fee, not used by pardakhtpal Standard
		$default_settings = parent::remove_field( 'setupFee', $default_settings );

		//--add trial period
		$trial_period     = array(
			'name'    => 'trialPeriod',
			'label'   => '&#1583;&#1608;&#1585;&#1607; &#1578;&#1587;&#1578;',
			'type'    => 'trial_period',
			'hidden'  => ! $this->get_setting( 'trial_enabled' ),
			'tooltip' => '<h6>&#1583;&#1608;&#1585;&#1607; &#1578;&#1587;&#1578;</h6>&#1591;&#1608;&#1604; &#1583;&#1608;&#1585;&#1607; &#1578;&#1587;&#1578; &#1585;&#1575; &#1575;&#1606;&#1578;&#1582;&#1575;&#1576; &#1606;&#1605;&#1575;&#1740;&#1740;&#1583;.'
		);
		$default_settings = parent::add_field_after( 'trial', $trial_period, $default_settings );
		//-----------------------------------------------------------------------------------------

		//--Add Try to bill again after failed attempt.
		$recurring_retry  = array(
			'name'       => 'recurringRetry',
			'label'      => '&#1578;&#1604;&#1575;&#1588; &#1605;&#1580;&#1583;&#1583;',
			'type'       => 'checkbox',
			'horizontal' => true,
			'choices'    => array( array( 'label' => '&#1575;&#1585;&#1587;&#1575;&#1604; &#1605;&#1580;&#1583;&#1583; &#1576;&#1585;&#1575;&#1740; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1583;&#1585; &#1589;&#1608;&#1585;&#1578; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1606;&#1575;&#1605;&#1608;&#1601;&#1602;', 'name' => 'recurringRetry', 'value' => '1' ) ),
			'tooltip'    => '<h6>&#1578;&#1604;&#1575;&#1588; &#1605;&#1580;&#1583;&#1583;</h6>&#1601;&#1593;&#1575;&#1604; &#1740;&#1575; &#1594;&#1740;&#1585; &#1601;&#1593;&#1575;&#1604; &#1587;&#1575;&#1586;&#1740; &#1575;&#1605;&#1705;&#1575;&#1606; &#1578;&#1604;&#1575;&#1588; &#1605;&#1580;&#1583;&#1583; &#1576;&#1585;&#1575;&#1740; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1583;&#1585; &#1589;&#1608;&#1585;&#1578; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1606;&#1575;&#1605;&#1608;&#1601;&#1602;.'
		);
		$default_settings = parent::add_field_after( 'recurringTimes', $recurring_retry, $default_settings );

		//-----------------------------------------------------------------------------------------------------

		return apply_filters( 'gform_pardakhtpal_feed_settings_fields', $default_settings, $form );
	}

	public function field_map_title() {
		return '&#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1606;&#1575;&#1605;&#1608;&#1601;&#1602;';
	}

	public function settings_trial_period( $field, $echo = true ) {
		//use the parent billing cycle function to make the drop down for the number and type
		$html = parent::settings_billing_cycle( $field );

		return $html;
	}

	public function set_trial_onchange( $field ) {
		//return the javascript for the onchange event
		return "
		if(jQuery(this).prop('checked')){
			jQuery('#{$field['name']}_product').show('slow');
			jQuery('#gaddon-setting-row-trialPeriod').show('slow');
			if (jQuery('#{$field['name']}_product').val() == 'enter_amount'){
				jQuery('#{$field['name']}_amount').show('slow');
			}
			else{
				jQuery('#{$field['name']}_amount').hide();
			}
		}
		else {
			jQuery('#{$field['name']}_product').hide('slow');
			jQuery('#{$field['name']}_amount').hide();
			jQuery('#gaddon-setting-row-trialPeriod').hide('slow');
		}";
	}

	public function settings_options( $field, $echo = true ) {
		$checkboxes = array(
			'name'    => 'options_checkboxes',
			'type'    => 'checkboxes',
			'choices' => array(
				array( 'label' => '&#1593;&#1583;&#1605; &#1606;&#1605;&#1575;&#1740;&#1588; &#1607;&#1588;&#1583;&#1575;&#1585; &#1576;&#1607; &#1705;&#1575;&#1585;&#1576;&#1585; &#1580;&#1607;&#1578; &#1578;&#1705;&#1605;&#1740;&#1604; &#1601;&#1740;&#1604;&#1583; &#1570;&#1583;&#1585;&#1587;', 'name' => 'disableShipping' ),
				array( 'label' => '&#1593;&#1583;&#1605; &#1606;&#1605;&#1575;&#1740;&#1588; &#1607;&#1588;&#1583;&#1575;&#1585; &#1576;&#1607; &#1705;&#1575;&#1585;&#1576;&#1585; &#1580;&#1607;&#1578; &#1578;&#1705;&#1605;&#1740;&#1604; &#1601;&#1740;&#1604;&#1583; &#1578;&#1608;&#1590;&#1740;&#1581;&#1575;&#1578; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578;', 'name' => 'disableNote' ),
			)
		);

		$html = $this->settings_checkbox( $checkboxes, false );

		//--------------------------------------------------------
		//For backwards compatibility.
		ob_start();
		do_action( 'gform_pardakhtpal_action_fields', $this->get_current_feed(), $this->get_current_form() );
		$html .= ob_get_clean();
		//--------------------------------------------------------

		if ( $echo ) {
			echo $html;
		}

		return $html;
	}

	public function settings_custom( $field, $echo = true ) {

		ob_start();
		?>
<div id='gf_pardakhtpal_custom_settings'>
    <?php
        do_action( 'gform_pardakhtpal_add_option_group', $this->get_current_feed(), $this->get_current_form() );
			?>
</div>

<script type='text/javascript'>
    jQuery(document).ready(function () {
        jQuery('#gf_pardakhtpal_custom_settings label.right_header').css('margin-right', '-200px');
    });
</script>

<?php

		$html = ob_get_clean();

		if ( $echo ) {
			echo $html;
		}

		return $html;
	}

	public function settings_notifications( $field, $echo = true ) {
		$checkboxes = array(
			'name'    => 'delay_notification',
			'type'    => 'checkboxes',
			'onclick' => 'ToggleNotifications();',
			'choices' => array(
				array(
					'label' => '&#1575;&#1585;&#1587;&#1575;&#1604; &#1607;&#1588;&#1583;&#1575;&#1585; &#1578;&#1606;&#1607;&#1575; &#1583;&#1585; &#1589;&#1608;&#1585;&#1578; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1570;&#1605;&#1740;&#1586;.',
					'name'  => 'delayNotification',
				),
			)
		);

		$html = $this->settings_checkbox( $checkboxes, false );

		$html .= $this->settings_hidden( array( 'name' => 'selectedNotifications', 'id' => 'selectedNotifications' ), false );

		$form                      = $this->get_current_form();
		$has_delayed_notifications = $this->get_setting( 'delayNotification' );
		ob_start();
		?>
<ul id="gf_pardakhtpal_notification_container" style="padding-left:20px; margin-top:10px; <?php echo $has_delayed_notifications ? '' : 'display:none;' ?>">
    <?php
			if ( ! empty( $form ) && is_array( $form['notifications'] ) ) {
				$selected_notifications = $this->get_setting( 'selectedNotifications' );
				if ( ! is_array( $selected_notifications ) ) {
					$selected_notifications = array();
				}

				//$selected_notifications = empty($selected_notifications) ? array() : json_decode($selected_notifications);

				$notifications = GFCommon::get_notifications( 'form_submission', $form );

				foreach ( $notifications as $notification ) {
					?>
    <li class="gf_pardakhtpal_notification">
        <input type="checkbox" class="notification_checkbox" value="<?php echo $notification['id'] ?>" onclick="SaveNotifications();" <?php checked( true, in_array( $notification['id'], $selected_notifications ) ) ?> />
        <label class="inline" for="gf_pardakhtpal_selected_notifications"><?php echo $notification['name']; ?></label>
    </li>
    <?php
				}
			}
			?>
</ul>
<script type='text/javascript'>
    function SaveNotifications() {
        var notifications = [];
        jQuery('.notification_checkbox').each(function () {
            if (jQuery(this).is(':checked')) {
                notifications.push(jQuery(this).val());
            }
        });
        jQuery('#selectedNotifications').val(jQuery.toJSON(notifications));
    }

    function ToggleNotifications() {

        var container = jQuery('#gf_pardakhtpal_notification_container');
        var isChecked = jQuery('#delaynotification').is(':checked');

        if (isChecked) {
            container.slideDown();
            jQuery('.gf_pardakhtpal_notification input').prop('checked', true);
        }
        else {
            container.slideUp();
            jQuery('.gf_pardakhtpal_notification input').prop('checked', false);
        }

        SaveNotifications();
    }
</script>
<?php

		$html .= ob_get_clean();

		if ( $echo ) {
			echo $html;
		}

		return $html;
	}

	public function checkbox_input_change_post_status( $choice, $attributes, $value, $tooltip ) {
		$markup = $this->checkbox_input( $choice, $attributes, $value, $tooltip );

		$dropdown_field = array(
			'name'     => 'update_post_action',
			'choices'  => array(
				array( 'label' => '' ),
				array( 'label' => '&#1579;&#1576;&#1578; &#1605;&#1591;&#1604;&#1576; &#1576;&#1607; &#1589;&#1608;&#1585;&#1578; &#1662;&#1740;&#1588; &#1606;&#1608;&#1740;&#1587;', 'value' => 'draft' ),
				array( 'label' => '&#1581;&#1584;&#1601; &#1662;&#1587;&#1578;', 'value' => 'delete' ),

			),
			'onChange' => "var checked = jQuery(this).val() ? 'checked' : false; jQuery('#change_post_status').attr('checked', checked);",
		);
		$markup .= '&nbsp;&nbsp;' . $this->settings_select( $dropdown_field, false );

		return $markup;
	}

	public function option_choices() {
		return false;
		$option_choices = array(
			array( 'label' => '&#1593;&#1583;&#1605; &#1606;&#1605;&#1575;&#1740;&#1588; &#1607;&#1588;&#1583;&#1575;&#1585; &#1576;&#1607; &#1705;&#1575;&#1585;&#1576;&#1585; &#1580;&#1607;&#1578; &#1578;&#1705;&#1605;&#1740;&#1604; &#1601;&#1740;&#1604;&#1583; &#1570;&#1583;&#1585;&#1587;', 'name' => 'disableShipping', 'value' => '' ),
			array( 'label' => '&#1593;&#1583;&#1605; &#1606;&#1605;&#1575;&#1740;&#1588; &#1607;&#1588;&#1583;&#1575;&#1585; &#1576;&#1607; &#1705;&#1575;&#1585;&#1576;&#1585; &#1580;&#1607;&#1578; &#1578;&#1705;&#1605;&#1740;&#1604; &#1601;&#1740;&#1604;&#1583; &#1578;&#1608;&#1590;&#1740;&#1581;&#1575;&#1578; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578;', 'name' => 'disableNote', 'value' => '' ),
		);

		return $option_choices;
	}

	public function save_feed_settings( $feed_id, $form_id, $settings ) {

		//--------------------------------------------------------
		//For backwards compatibility
		$feed = $this->get_feed( $feed_id );

		//Saving new fields into old field names to maintain backwards compatibility for delayed payments
		$settings['type'] = $settings['transactionType'];

		if ( isset( $settings['recurringAmount'] ) ) {
			$settings['recurring_amount_field'] = $settings['recurringAmount'];
		}

		$feed['meta'] = $settings;
		$feed         = apply_filters( 'gform_pardakhtpal_save_config', $feed );
		
		//call hook to validate custom settings/meta added using gform_pardakhtpal_action_fields or gform_pardakhtpal_add_option_group action hooks
		$is_validation_error = apply_filters( 'gform_pardakhtpal_config_validation', false, $feed );
		if ( $is_validation_error ) {
			//fail save
			return false;
		}
		
		$settings     = $feed['meta'];
		
		//--------------------------------------------------------

		return parent::save_feed_settings( $feed_id, $form_id, $settings );
	}
    
	//------ SENDING TO pardakhtpal -----------//

	public function redirect_url( $feed, $submission_data, $form, $entry ) {

		//Don't process redirect url if request is a pardakhtpal return
		if ( ! rgempty( 'gf_pardakhtpal_return', $_GET ) ) {
			return false;
		}
        
        add_filter( 'the_content', array( $this, 'pardakhtpal_print_message' ), 10 , 1 );
        add_filter( 'gform_confirmation', array( $this, 'pardakhtpal_ajax_calls_message' ), 10, 4 );

        //add_action( 'gform_post_process' , array( $this, 'pardakhtpal_add_message' ) );
        
        
		//updating lead's payment_status to Processing
		GFAPI::update_entry_property( $entry['id'], 'payment_status', 'Processing' );
        
        $customer_fields = $this->customer_query_string( $feed, $entry );
        
        $query_string = '';

        switch ( $feed['meta']['transactionType'] ) {
            case 'product' :
                //build query string using $submission_data
                $query_string = $this->get_product_query_string( $submission_data, $entry['id'] );
                break;

            case 'donation' :
                $query_string = $this->get_donation_query_string( $submission_data, $entry['id'] );
                break;

            case 'subscription' :
                $query_string = $this->get_subscription_query_string( $feed, $submission_data, $entry['id'] );
                break;
        }

        $query_string = apply_filters( "gform_pardakhtpal_query_{$form['id']}", apply_filters( 'gform_pardakhtpal_query', $query_string, $form, $entry, $feed ), $form, $entry, $feed );

        if ( ! $query_string ) {
            $this->log_debug( __METHOD__ . '(): NOT sending to PardakhtPal: The price is either zero or the gform_pardakhtpal_query filter was used to remove the querystring that is sent to PardakhtPal.' );

            return '';
        }
        
        
        $redirect = get_bloginfo( 'url' ) . '/?page=gf_pardakhtpal_ipn&ent=' . $entry['id'] . '&cancelurl=' . $feed['meta']['cancelUrl'] . '&custom=' . $entry['id'] . '|' . wp_hash( $entry['id'] ) . '&' . $customer_fields . $query_string . '&return_page=' . $this->return_url( $form['id'], $entry['id'] );//$query_string;
        //$OrderId = $entry['id'];
        
        // Sending to pardakhtpal to check availability

        $amount = intval( rgar( $submission_data, 'payment_amount' ) ); // Require
        
        if( $entry['currency'] == 'IRT' ) {
            $amount = $amount * 10; //pardakhtpal only accepts rials
        }
        
        $description = '&#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1601;&#1608;&#1585;&#1605; &#1576;&#1607; &#1588;&#1605;&#1575;&#1585;&#1607; &#1740; ' . $entry['id']; // Required 
        
        $api = ( isset( $feed['meta']['pardakhtpalapi'] ) ) ? $feed['meta']['pardakhtpalapi'] : '';
        $url = 'http://www.pardakhtpal.com/WebService/WebService.asmx?wsdl';
      
        $client = new SoapClient( $url ); 

        $params = array( 'API' => $api , 'Amount' => $amount, 'CallBack' => $redirect, 'OrderId' => $entry['id'], 'Text' => $description );

        $res = $client->requestpayment( $params ); 
        $Result = $res->requestpaymentResult; 
        
        if( strlen($Result) == 8 ){
            $payment_url = 'http://www.pardakhtpal.com/payment/pay_invoice/' . $Result;
            return $payment_url;
        }
        else{
            RGFormsModel::update_lead_property($entry["id"], "payment_status", 'Failed');
            $this->pp_msg = array( 'message' => '&#1662;&#1575;&#1587;&#1582; &#1583;&#1585;&#1740;&#1575;&#1601;&#1578;&#1740; &#1575;&#1586; &#1583;&#1585;&#1711;&#1575;&#1607; &#1606;&#1575;&#1605;&#1593;&#1578;&#1576;&#1585; &#1576;&#1608;&#1583;. &#1605;&#1602;&#1583;&#1575;&#1585; &#1576;&#1575;&#1586;&#1711;&#1588;&#1578;&#1740;: ' . $Result . "<br /><a href='{$entry['source_url']}'>&#1576;&#1575;&#1586;&#1711;&#1588;&#1578;</a>", 'type' => 'error' );
            return '';
        }
	}

	public function get_product_query_string( $submission_data, $entry_id ) {

		if ( empty( $submission_data ) ) {
			return false;
		}

		$query_string   = '';
		$payment_amount = rgar( $submission_data, 'payment_amount' );
		$setup_fee      = rgar( $submission_data, 'setup_fee' );
		$trial_amount   = rgar( $submission_data, 'trial' );
		$line_items     = rgar( $submission_data, 'line_items' );
		$discounts      = rgar( $submission_data, 'discounts' );

		$product_index = 1;
		$shipping      = '';
		$discount_amt  = 0;
		$cmd           = '_cart';
		$extra_qs      = '&upload=1';

		//work on products
		if ( is_array( $line_items ) ) {
			foreach ( $line_items as $item ) {
				$product_name = urlencode( $item['name'] );
				$quantity     = $item['quantity'];
				$unit_price   = $item['unit_price'];
				$options      = rgar( $item, 'options' );
				$product_id   = $item['id'];
				$is_shipping  = rgar( $item, 'is_shipping' );

				if ( $is_shipping ) {
					//populate shipping info
					$shipping .= ! empty( $unit_price ) ? "&shipping_1={$unit_price}" : '';
				} else {
					//add product info to querystring
					$query_string .= "&item_name_{$product_index}={$product_name}&amount_{$product_index}={$unit_price}&quantity_{$product_index}={$quantity}";
				}
				//add options
				if ( ! empty( $options ) ) {
					if ( is_array( $options ) ) {
						$option_index = 1;
						foreach ( $options as $option ) {
							$option_label = urlencode( $option['field_label'] );
							$option_name  = urlencode( $option['option_name'] );
							$query_string .= "&on{$option_index}_{$product_index}={$option_label}&os{$option_index}_{$product_index}={$option_name}";
							$option_index ++;
						}
					}
				}
				$product_index ++;
			}
		}

		//look for discounts
		if ( is_array( $discounts ) ) {
			foreach ( $discounts as $discount ) {
				$discount_full = abs( $discount['unit_price'] ) * $discount['quantity'];
				$discount_amt += $discount_full;
			}
			if ( $discount_amt > 0 ) {
				$query_string .= "&discount_amount_cart={$discount_amt}";
			}
		}

		$query_string .= "{$shipping}&cmd={$cmd}{$extra_qs}";
		
		//save payment amount to lead meta
		gform_update_meta( $entry_id, 'payment_amount', $payment_amount );

		return $payment_amount > 0 ? $query_string : false;

	}

	public function get_donation_query_string( $submission_data, $entry_id ) {
		if ( empty( $submission_data ) ) {
			return false;
		}

		$query_string   = '';
		$payment_amount = rgar( $submission_data, 'payment_amount' );
		$line_items     = rgar( $submission_data, 'line_items' );
		$purpose        = '';
		$cmd            = '_donations';

		//work on products
		if ( is_array( $line_items ) ) {
			foreach ( $line_items as $item ) {
				$product_name    = $item['name'];
				$quantity        = $item['quantity'];
				$quantity_label  = $quantity > 1 ? $quantity . ' ' : '';
				$options         = rgar( $item, 'options' );
				$is_shipping     = rgar( $item, 'is_shipping' );
				$product_options = '';

				if ( ! $is_shipping ) {
					//add options
					if ( ! empty( $options ) ) {
						if ( is_array( $options ) ) {
							$product_options = ' (';
							foreach ( $options as $option ) {
								$product_options .= $option['option_name'] . ', ';
							}
							$product_options = substr( $product_options, 0, strlen( $product_options ) - 2 ) . ')';
						}
					}
					$purpose .= $quantity_label . $product_name . $product_options . ', ';
				}
			}
		}

		if ( ! empty( $purpose ) ) {
			$purpose = substr( $purpose, 0, strlen( $purpose ) - 2 );
		}

		$purpose = urlencode( $purpose );

		//truncating to maximum length allowed by pardakhtpal
		if ( strlen( $purpose ) > 127 ) {
			$purpose = substr( $purpose, 0, 124 ) . '...';
		}

		$query_string = "&amount={$payment_amount}&item_name={$purpose}&cmd={$cmd}";
		
		//save payment amount to lead meta
		gform_update_meta( $entry_id, 'payment_amount', $payment_amount );

		return $payment_amount > 0 ? $query_string : false;

	}

	public function get_subscription_query_string( $feed, $submission_data, $entry_id ) {

		if ( empty( $submission_data ) ) {
			return false;
		}

		$query_string         = '';
		$payment_amount       = rgar( $submission_data, 'payment_amount' );
		$setup_fee            = rgar( $submission_data, 'setup_fee' );
		$trial_enabled        = rgar( $feed['meta'], 'trial_enabled' );
		$line_items           = rgar( $submission_data, 'line_items' );
		$discounts            = rgar( $submission_data, 'discounts' );
		$recurring_field      = rgar( $submission_data, 'payment_amount' ); //will be field id or the text 'form_total'
		$product_index        = 1;
		$shipping             = '';
		$discount_amt         = 0;
		$cmd                  = '_xclick-subscriptions';
		$extra_qs             = '';
		$name_without_options = '';
		$item_name            = '';

		//work on products
		if ( is_array( $line_items ) ) {
			foreach ( $line_items as $item ) {
				$product_id     = $item['id'];
				$product_name   = $item['name'];
				$quantity       = $item['quantity'];
				$quantity_label = $quantity > 1 ? $quantity . ' ' : '';

				$unit_price  = $item['unit_price'];
				$options     = rgar( $item, 'options' );
				$product_id  = $item['id'];
				$is_shipping = rgar( $item, 'is_shipping' );

				$product_options = '';
				if ( ! $is_shipping ) {
					//add options

					if ( ! empty( $options ) && is_array( $options ) ) {
						$product_options = ' (';
						foreach ( $options as $option ) {
							$product_options .= $option['option_name'] . ', ';
						}
						$product_options = substr( $product_options, 0, strlen( $product_options ) - 2 ) . ')';
					}

					$item_name .= $quantity_label . $product_name . $product_options . ', ';
					$name_without_options .= $product_name . ', ';
				}
			}

			//look for discounts to pass in the item_name
			if ( is_array( $discounts ) ) {
				foreach ( $discounts as $discount ) {
					$product_name   = $discount['name'];
					$quantity       = $discount['quantity'];
					$quantity_label = $quantity > 1 ? $quantity . ' ' : '';
					$item_name .= $quantity_label . $product_name . ' (), ';
					$name_without_options .= $product_name . ', ';
				}
			}

			if ( ! empty( $item_name ) ) {
				$item_name = substr( $item_name, 0, strlen( $item_name ) - 2 );
			}

			//if name is larger than max, remove options from it.
			if ( strlen( $item_name ) > 127 ) {
				$item_name = substr( $name_without_options, 0, strlen( $name_without_options ) - 2 );

				//truncating name to maximum allowed size
				if ( strlen( $item_name ) > 127 ) {
					$item_name = substr( $item_name, 0, 124 ) . '...';
				}
			}
			$item_name = urlencode( $item_name );

		}

		$trial = '';
		//see if a trial exists
		if ( $trial_enabled ) {
			$trial_amount        = rgar( $submission_data, 'trial' ) ? rgar( $submission_data, 'trial' ) : 0;
			$trial_period_number = rgar( $feed['meta'], 'trialPeriod_length' );
			$trial_period_type   = $this->convert_interval( rgar( $feed['meta'], 'trialPeriod_unit' ), 'char' );
			$trial               = "&a1={$trial_amount}&p1={$trial_period_number}&t1={$trial_period_type}";
		}

		//check for recurring times
		$recurring_times = rgar( $feed['meta'], 'recurringTimes' ) ? '&srt=' . rgar( $feed['meta'], 'recurringTimes' ) : '';
		$recurring_retry = rgar( $feed['meta'], 'recurringRetry' ) ? '1' : '0';

		$billing_cycle_number = rgar( $feed['meta'], 'billingCycle_length' );
		$billing_cycle_type   = $this->convert_interval( rgar( $feed['meta'], 'billingCycle_unit' ), 'char' );

		$query_string = "&cmd={$cmd}&item_name={$item_name}{$trial}&a3={$payment_amount}&p3={$billing_cycle_number}&t3={$billing_cycle_type}&src=1&sra={$recurring_retry}{$recurring_times}";

		//save payment amount to lead meta
		gform_update_meta( $entry_id, 'payment_amount', $payment_amount );
		
		return $payment_amount > 0 ? $query_string : false;

	}

    public function customer_query_string( $feed, $lead ) {
        $fields = '';
        foreach ( $this->get_customer_fields() as $field ) {
            $field_id = $feed['meta'][ $field['meta_name'] ];
            $value    = rgar( $lead, $field_id );

            if ( $field['name'] == 'country' ) {
                $value = class_exists( 'GF_Field_Address' ) ? GF_Fields::get( 'address' )->get_country_code( $value ) : GFCommon::get_country_code( $value );
            } elseif ( $field['name'] == 'state' ) {
                $value = class_exists( 'GF_Field_Address' ) ? GF_Fields::get( 'address' )->get_us_state_code( $value ) : GFCommon::get_us_state_code( $value );
            }

            if ( ! empty( $value ) ) {
                $fields .= "&{$field['name']}=" . urlencode( $value );
            }
        }

        return $fields;
    }

	public function return_url( $form_id, $lead_id ) {
		$pageURL = GFCommon::is_ssl() ? 'https://' : 'http://';
		$server_port = apply_filters( 'gform_pardakhtpal_return_url_port', $_SERVER['SERVER_PORT'] );

		if ( $server_port != '80' ) {
			$pageURL .= $_SERVER['SERVER_NAME'] . ':' . $server_port . $_SERVER['REQUEST_URI'];
		} else {
			$pageURL .= $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		}

		$ids_query = "ids={$form_id}|{$lead_id}";
		$ids_query .= '&hash=' . wp_hash( $ids_query );

		return add_query_arg( 'gf_pardakhtpal_return', base64_encode( $ids_query ), $pageURL );
	}

	public static function maybe_thankyou_page() {
		$instance = self::get_instance();

		if ( ! $instance->is_gravityforms_supported() ) {
			return;
		}

		if ( $str = rgget( 'gf_pardakhtpal_return' ) ) {
			$str = base64_decode( $str );

			parse_str( $str, $query );
			if ( wp_hash( 'ids=' . $query['ids'] ) == $query['hash'] ) {
				list( $form_id, $lead_id ) = explode( '|', $query['ids'] );

				$form = GFAPI::get_form( $form_id );
				$lead = GFAPI::get_entry( $lead_id );

				if ( ! class_exists( 'GFFormDisplay' ) ) {
					require_once( GFCommon::get_base_path() . '/form_display.php' );
				}
                
                $pardakhtpal_status = self::print_static_message();
                
				$confirmation = GFFormDisplay::handle_confirmation( $form, $lead, false );

				if ( is_array( $confirmation ) && isset( $confirmation['redirect'] ) ) {
					header( "Location: {$confirmation['redirect']}" );
					exit;
				}
                
				GFFormDisplay::$submission[ $form_id ] = array( 'is_confirmation' => true, 'confirmation_message' => $confirmation . $pardakhtpal_status, 'form' => $form, 'lead' => $lead );
			}
		}
	}

	public function get_customer_fields() {
		return array(
			array( 'name' => 'first_name', 'label' => 'First Name', 'meta_name' => 'billingInformation_firstName' ),
			array( 'name' => 'email', 'label' => 'Email', 'meta_name' => 'billingInformation_email' )
		);
	}

	public function convert_interval( $interval, $to_type ) {
		//convert single character into long text for new feed settings or convert long text into single character for sending to pardakhtpal
		//$to_type: text (change character to long text), OR char (change long text to character)
		if ( empty( $interval ) ) {
			return '';
		}

		$new_interval = '';
		if ( $to_type == 'text' ) {
			//convert single char to text
			switch ( strtoupper( $interval ) ) {
				case 'D' :
					$new_interval = 'day';
					break;
				case 'W' :
					$new_interval = 'week';
					break;
				case 'M' :
					$new_interval = 'month';
					break;
				case 'Y' :
					$new_interval = 'year';
					break;
				default :
					$new_interval = $interval;
					break;
			}
		} else {
			//convert text to single char
			switch ( strtolower( $interval ) ) {
				case 'day' :
					$new_interval = 'D';
					break;
				case 'week' :
					$new_interval = 'W';
					break;
				case 'month' :
					$new_interval = 'M';
					break;
				case 'year' :
					$new_interval = 'Y';
					break;
				default :
					$new_interval = $interval;
					break;
			}
		}

		return $new_interval;
	}

	public function delay_post( $is_disabled, $form, $entry ) {

		$feed = $this->get_payment_feed( $entry );
		$submission_data = $this->get_submission_data( $feed, $form, $entry );

		if ( ! $feed || empty( $submission_data['payment_amount'] ) ){
			return $is_disabled;
		}

		return ! rgempty( 'delayPost', $feed['meta'] );
	}

	public function delay_notification( $is_disabled, $notification, $form, $entry ){

		$feed = $this->get_payment_feed( $entry );
		$submission_data = $this->get_submission_data( $feed, $form, $entry );

		if ( ! $feed || empty( $submission_data['payment_amount'] ) ){
			return $is_disabled;
		}

		$selected_notifications = is_array( rgar( $feed['meta'], 'selectedNotifications' ) ) ? rgar( $feed['meta'], 'selectedNotifications' ) : array();

		return isset( $feed['meta']['delayNotification'] ) && in_array( $notification['id'], $selected_notifications ) ? true : $is_disabled;
	}


	//------- PROCESSING pardakhtpal (Callback) -----------//

	public function callback() {

		if ( ! $this->is_gravityforms_supported() ) {
			return false;
		}

        add_action( 'gform_post_payment_callback', array( $this, 'pardakhtpal_show_result_page' ) );

		$this->log_debug( __METHOD__ . '(pardakhtpal): request received. Starting to process => ' . print_r( $_POST, true ) );
        //$Authority = $_POST['au'];
        
        //------- Send request to pardakhtpal and verify it has not been spoofed ---------------------//
        //$is_verified = $this->verify_pardakhtpal_ipn();
        if( isset( $_GET['custom'] ) && $_GET['custom'] != '' )
            $entry = $this->get_entry( $_GET['custom'] );
        else
            $this->log_error( __METHOD__ . '(pardakhtpal): Morede pardakhti yaft nashod.  Aborting.' );
                
        if ( ! $entry ) {
            $this->log_error( __METHOD__ . '(pardakhtpal): Entry could not be found. Entry ID: ' . rgar( $entry, 'id' ) . '. Aborting.' );

            return false;
        }
        $this->log_debug( __METHOD__ . '(pardakhtpal): Entry has been found => ' . print_r( $entry, true ) );

        //------ Getting feed related to this IPN ------------------------------------------//
        $feed = $this->get_payment_feed( $entry );
            
        //Ignore IPN messages from forms that are no longer configured with the pardakhtpal add-on
        if ( ! $feed || ! rgar( $feed, 'is_active' ) ) {
            $this->log_error( __METHOD__ . "(pardakhtpal): in form motealegh be dargahe pardakhtpal nist. Shenaseye form: {$entry['form_id']}. dar hale laghve amaliat..." );

            return false;
        }
            
        $this->log_debug( __METHOD__ . "(pardakhtpal): Form {$entry['form_id']} ba movafaghiat tanzim shod." );
               
        $amount = intval( gform_get_meta( $entry['id'], 'payment_amount' ) ); // Require
            
        if( $entry['currency'] == 'IRT' ) {
            $amount = $amount * 10; //pardakhtpal only accepts rials
        }
           
        $Authority = ( isset( $_POST['au'] ) ) ? $_POST['au'] : '';
        
        $action['id']               = ($Authority == '')? 'failed-' . $entry['id'] : $Authority;
        $action['type']             = 'fail_payment';
        $action['amount']           = $amount;
        $action['entry_id']         = $entry['id'];
        $action['payment_method']	= 'PardakhtPal';
        $action['trt']              = $feed['meta']['transactionType'];

        if( strlen( $Authority ) > 4 ){ 
            $api = ( isset( $feed['meta']['pardakhtpalapi'] ) ) ? $feed['meta']['pardakhtpalapi'] : '';
            $url = 'http://www.pardakhtpal.com/WebService/WebService.asmx?wsdl';
            
            $client = new SoapClient( $url ); 
            $params = array('API' => $api , 'Amount' => $amount, 'InvoiceId' => $Authority); 

            $res = $client->verifypayment($params); 
            $Result = $res->verifypaymentResult; 
            
            $this->log_debug( __METHOD__ . '(pardakhtpal): aghaze pardazeshe nahaiye tarakonesh...' );   

            if( $Result == 1 ){
                $this->pp_msg = array( 'message' => '&#1593;&#1605;&#1604;&#1740;&#1575;&#1578; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1576;&#1575; &#1605;&#1608;&#1601;&#1602;&#1740;&#1578; &#1576;&#1607; &#1662;&#1575;&#1740;&#1575;&#1606; &#1585;&#1587;&#1740;&#1583;.' . "<br />&#1588;&#1606;&#1575;&#1587;&#1607; &#1578;&#1585;&#1575;&#1705;&#1606;&#1588;: {$Authority}<br /><br /><a href='{$entry['source_url']}'>&#1576;&#1575;&#1586;&#1711;&#1588;&#1578;</a>", 'type' => 'updated' );
                $this->log_debug( __METHOD__ . '(pardakhtpal): pardakhte karbar ba movafaghiat anjam shodeh ast.' );
                $guessed_date = '';
                if( function_exists( 'jdate') )
                    $guessed_date = jdate( 'y-m-d H:i:s' );
                else
                    $guessed_date = gmdate( 'y-m-d H:i:s' );
                
                if( $action['trt'] == 'product' || $action['trt'] == 'donation' ){
                    $action['type'] = 'complete_payment';
                    $action['payment_date']     = $guessed_date;
                }
                elseif( $action['trt'] == 'subscription' ) {
                    $action['type'] = 'add_subscription_payment';
                    $action['subscription_id']  = '';//$subscriber_id;
                }
                else{
                    $action['type'] = 'complete_payment';
                    $action['payment_date']     = $guessed_date;
                }
                
                $action['transaction_id']   = $Authority;
                $action['ready_to_fulfill'] = ! $entry['is_fulfilled'] ? true : false;
                if ( rgempty( 'entry_id', $action ) ) {
                    return false;
                }
                else {
                    $this->log_debug( __METHOD__ . '(pardakhtpal): pardazeshe tarakoneshe daryafti ba movafaghiat be payan resid.' );
                    return $action;
                }
            }
            else{
                $this->pp_msg = array( 'message' => '&#1605;&#1602;&#1583;&#1575;&#1585; &#1583;&#1585;&#1740;&#1575;&#1601;&#1578;&#1740; &#1575;&#1586; &#1606;&#1578;&#1740;&#1580;&#1607; &#1576;&#1585;&#1585;&#1587;&#1740; &#1578;&#1585;&#1575;&#1705;&#1606;&#1588; &#1606;&#1575;&#1605;&#1593;&#1578;&#1576;&#1585; &#1575;&#1587;&#1578;. &#1605;&#1602;&#1583;&#1575;&#1585; &#1576;&#1575;&#1586;&#1711;&#1588;&#1578;&#1740;: ' . $Result . "<br /><a href='{$entry['source_url']}'>&#1576;&#1575;&#1586;&#1711;&#1588;&#1578;</a>", 'type' => 'error' );
                if( $action['trt'] == 'product' || $action['trt'] == 'donation' )
                    $action['type'] = 'fail_payment';
                elseif( $action['trt'] == 'subscription' ) {
                    $action['type'] = 'fail_subscription_payment';
                    $action['subscription_id']  = '';
                }

                return false;
            }
        }
        else{
            $this->pp_msg = array( 'message' => '&#1605;&#1602;&#1575;&#1583;&#1740;&#1585; &#1605;&#1608;&#1585;&#1583; &#1606;&#1740;&#1575;&#1586; &#1575;&#1586; &#1587;&#1608;&#1740; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604; &#1583;&#1585;&#1740;&#1575;&#1601;&#1578; &#1606;&#1588;&#1583;.', 'type' => 'error' );
            //$this->log_error( __METHOD__ . '(pardakhtpal): Payment canceled by user' );
            if( $action['trt'] == 'product' || $action['trt'] == 'donation' )
                $action['type'] = 'fail_payment';
            elseif( $action['trt'] == 'subscription' ) {
                $action['type'] = 'fail_subscription_payment';
                $action['subscription_id']  = '';
            }
            
            if( isset( $_GET['cancelurl'] ) && $_GET['cancelurl'] != '' ){
                header( "Location: " . $_GET['cancelurl'] );
                return false;
            }
            else{
                return $action;
            }
        }

	}
    
    #############################
    # Hook into confirmation page
    #############################
    
    public function pardakhtpal_ajax_calls_message( $confirmation, $form, $entry, $ajax )
    {
        if( $ajax )
        {
            $confirmation .= ( ! empty( $this->pp_msg ) ) ? $this->pp_msg['message'] : '';
        }
        return $confirmation;
    }

    public function pardakhtpal_print_message( $content )
    {
        //echo $this->pp_msg['message'];
        $content .= "<br />&#1606;&#1578;&#1740;&#1580;&#1607; &#1593;&#1605;&#1604;&#1740;&#1575;&#1578; : {$this->pp_msg['message']}";
        return $content;
    }
    
    public static function print_static_message( )
    {
        @session_start();
        //var_dump( $lead, $foem );
        if( ! isset( $_SESSION['pardakhtpal_session'] ) || ! isset( $_GET['pp_msg_id'] ) )
        {
            return ''; //$confirmation;
        }
        
        if( ! isset( $_SESSION['pardakhtpal_session'][ $_GET['pp_msg_id'] ] ) )
        {
            return ''; //$confirmation;
        }
        
        //$confirmation .=
        $msg  = $_SESSION['pardakhtpal_session'][ $_GET['pp_msg_id'] ];
        unset( $_SESSION['pardakhtpal_session'][ $_GET['pp_msg_id'] ] );
        return "<br />&#1606;&#1578;&#1740;&#1580;&#1607; &#1593;&#1605;&#1604;&#1740;&#1575;&#1578; : " . $msg . "<br />";
        //return $form;
        //return $confirmation;
    }
    
    public function pardakhtpal_show_result_page( $entry )
    {
        //if( isset( $_GET['return_page'] ) )
        //{
        @session_start();
        if( ! isset( $_SESSION['pardakhtpal_session'] ) )
        {
            $_SESSION['pardakhtpal_session'] = array();
        }
        $time = time();
        $_SESSION['pardakhtpal_session'][$time] = $this->pp_msg['message'];
        $url = add_query_arg( array( 'pp_msg_id' => $time ), $_GET['return_page'] );
        header( "Location: {$url}");
        die();
       // }
    }

	public function get_payment_feed( $entry, $form = false ){

		$feed = parent::get_payment_feed( $entry, $form );

		if ( empty( $feed ) && ! empty($entry['id']) ){
			//looking for feed created by legacy versions
			$feed = $this->get_pardakhtpal_feed_by_entry( $entry['id'] );
		}

		$feed = apply_filters( 'gform_pardakhtpal_get_payment_feed', $feed, $entry, $form );

		return $feed;
	}

	private function get_pardakhtpal_feed_by_entry( $entry_id ) {

		$feed_id = gform_get_meta( $entry_id, 'pardakhtpal_feed_id' );
		$feed = $this->get_feed( $feed_id );

		return ! empty( $feed ) ? $feed : false;
	}

	public function post_callback( $callback_action, $callback_result ) {
		if ( is_wp_error( $callback_action ) || ! $callback_action ){
			return false;
		}

		//run the necessary hooks
		$entry          = GFAPI::get_entry( $callback_action['entry_id'] );
		$feed           = $this->get_payment_feed( $entry );
		$transaction_id = rgar( $callback_action, 'transaction_id' );
		$amount         = rgar( $callback_action, 'amount' );
		$subscriber_id  = rgar( $callback_action, 'subscriber_id' );
		$pending_reason = '';
		$reason         = '';
		$status         = $callback_action[ 'type' ];
		$txn_type       = $callback_action[ 'trt' ];
		$parent_txn_id  = '';
        
		//run gform_pardakhtpal_fulfillment only in certain conditions
		if ( rgar( $callback_action, 'ready_to_fulfill' ) && ! rgar( $callback_action, 'abort_callback' ) ) {
			$this->fulfill_order( $entry, $transaction_id, $amount, $feed );
            header("Location: {$_GET['return_page']}");
		} 
		else {
			if ( rgar( $callback_action, 'abort_callback' ) ){
				$this->log_debug( __METHOD__ . '(): Callback processing was aborted. Not fulfilling entry.' );
			}
			else {
				$this->log_debug( __METHOD__ . '(): Entry is already fulfilled or not ready to be fulfilled, not running gform_pardakhtpal_fulfillment hook.' );
			}
		}

		do_action( 'gform_post_payment_status', $feed, $entry, $status, $transaction_id, $subscriber_id, $amount, $pending_reason, $reason );
		if ( has_filter( 'gform_post_payment_status' ) ) {
			$this->log_debug( __METHOD__ . '(): Executing functions hooked to gform_post_payment_status.' );
		}

        //do_action( 'gform_pardakhtpal_ipn_' . $txn_type, $entry, $feed, $status, $txn_type, $transaction_id, $parent_txn_id, $subscriber_id, $amount, $pending_reason, $reason );
        //if ( has_filter( 'gform_pardakhtpal_ipn_' . $txn_type ) ) {
        //    $this->log_debug( __METHOD__ . "(): Executing functions hooked to gform_pardakhtpal_ipn_{$txn_type}." );
        //}

        //do_action( 'gform_pardakhtpal_post_ipn', $_POST, $entry, $feed, false );
        //if ( has_filter( 'gform_pardakhtpal_post_ipn' ) ) {
        //    $this->log_debug( __METHOD__ . '(): Executing functions hooked to gform_pardakhtpal_post_ipn.' );
        //}
	}

	private function verify_pardakhtpal_ipn() {

		$req = 'cmd=_notify-validate';
		foreach ( $_POST as $key => $value ) {
			$value = urlencode( stripslashes( $value ) );
			$req .= "&$key=$value";
		}

		//$url = rgpost( 'test_ipn' ) ? $this->sandbox_url : $this->production_url;
        

		$this->log_debug( __METHOD__ . "(): Sending IPN request to pardakhtpal for validation. URL: $url - Data: $req" );

		$url_info = parse_url( $url );

		//Post back to pardakhtpal system to validate
		$request  = new WP_Http();
		$headers  = array( 'Host' => $url_info['host'] );
		$response = $request->post( $url, array( 'httpversion' => '1.1', 'headers' => $headers, 'sslverify' => false, 'ssl' => true, 'body' => $req, 'timeout' => 20 ) );
		$this->log_debug( __METHOD__ . '(): Response: ' . print_r( $response, true ) );

		if ( is_wp_error( $response ) ){
			return $response;
		}

		return trim( $response['body'] ) == 'VERIFIED';

	}



	public function get_entry( $custom_field ) {

		//Valid IPN requests must have a custom field
		if ( empty( $custom_field ) ) {
			$this->log_error( __METHOD__ . '(): IPN request does not have a custom field, so it was not created by Gravity Forms. Aborting.' );

			return false;
		}

		//Getting entry associated with this IPN message (entry id is sent in the 'custom' field)
		list( $entry_id, $hash ) = explode( '|', $custom_field );
		$hash_matches = wp_hash( $entry_id ) == $hash;

		//allow the user to do some other kind of validation of the hash
		$hash_matches = apply_filters( 'gform_pardakhtpal_hash_matches', $hash_matches, $entry_id, $hash, $custom_field );

		//Validates that Entry Id wasn't tampered with
		if ( ! rgpost( 'test_ipn' ) && ! $hash_matches ) {
			$this->log_error( __METHOD__ . "(): Entry Id verification failed. Hash does not match. Custom field: {$custom_field}. Aborting." );

			return false;
		}

		$this->log_debug( __METHOD__ . "(): IPN message has a valid custom field: {$custom_field}" );

		$entry = GFAPI::get_entry( $entry_id );

		if ( is_wp_error( $entry ) ) {
			$this->log_error( __METHOD__ . '(): ' . $entry->get_error_message() );

			return false;
		}

		return $entry;
	}



	public function cancel_subscription( $entry, $feed, $note = null ) {

		parent::cancel_subscription( $entry, $feed, $note );

		$this->modify_post( rgar( $entry, 'post_id' ), rgars( $feed, 'meta/update_post_action' ) );

		return true;
	}

	public function modify_post( $post_id, $action ) {

		$result = false;

		if ( ! $post_id ){
			return $result;
		}

		switch ( $action ) {
			case 'draft':
				$post = get_post( $post_id );
				$post->post_status = 'draft';
				$result = wp_update_post( $post );
				$this->log_debug( __METHOD__ . "(): Set post (#{$post_id}) status to \"draft\"." );
				break;
			case 'delete':
				$result = wp_delete_post( $post_id );
				$this->log_debug( __METHOD__ . "(): Deleted post (#{$post_id})." );
				break;
		}

		return $result;
	}

	private function get_reason( $code ) {

		switch ( strtolower( $code ) ) {
			case 'adjustment_reversal':
				return __( 'Reversal of an adjustment', 'gravityformspaypal' );
			case 'buyer-complaint':
				return __( 'A reversal has occurred on this transaction due to a complaint about the transaction from your customer.', 'gravityformspaypal' );

			case 'chargeback':
				return __( 'A reversal has occurred on this transaction due to a chargeback by your customer.', 'gravityformspaypal' );

			case 'chargeback_reimbursement':
				return __( 'Reimbursement for a chargeback.', 'gravityformspaypal' );

			case 'chargeback_settlement':
				return __( 'Settlement of a chargeback.', 'gravityformspaypal' );

			case 'guarantee':
				return __( 'A reversal has occurred on this transaction due to your customer triggering a money-back guarantee.', 'gravityformspaypal' );

			case 'other':
				return __( 'Non-specified reason.', 'gravityformspaypal' );

			case 'refund':
				return __( 'A reversal has occurred on this transaction because you have given the customer a refund.', 'gravityformspaypal' );

			default:
				return empty( $code ) ? __( 'Reason has not been specified. For more information, contact PayPal Customer Service.', 'gravityformspaypal' ) : $code;
		}
	}

	public function is_callback_valid() {
		if ( rgget( 'page' ) != 'gf_pardakhtpal_ipn' ) {
			return false;
		}

		return true;
	}



	//------- AJAX FUNCTIONS ------------------//

	public function init_ajax(){

		parent::init_ajax();

		add_action( 'wp_ajax_gf_dismiss_pardakhtpal_menu', array( $this, 'ajax_dismiss_menu' ) );

	}

	//------- ADMIN FUNCTIONS/HOOKS -----------//

	public function init_admin() {

		parent::init_admin();

		//add actions to allow the payment status to be modified
		add_action( 'gform_payment_status', array( $this, 'admin_edit_payment_status' ), 3, 3 );

		if ( version_compare( GFCommon::$version, '1.8.17.4', '<' ) ){
			//using legacy hook
			add_action( 'gform_entry_info', array( $this, 'admin_edit_payment_status_details' ), 4, 2 );
		}
		else {
			add_action( 'gform_payment_date', array( $this, 'admin_edit_payment_date' ), 3, 3 );
			add_action( 'gform_payment_transaction_id', array( $this, 'admin_edit_payment_transaction_id' ), 3, 3 );
			add_action( 'gform_payment_amount', array( $this, 'admin_edit_payment_amount' ), 3, 3 );
		}

		add_action( 'gform_after_update_entry', array( $this, 'admin_update_payment' ), 4, 2 );

		add_filter( 'gform_addon_navigation', array( $this, 'maybe_create_menu' ) );
	}

	public function maybe_create_menu( $menus ){
		$current_user = wp_get_current_user();
		$dismiss_pardakhtpal_menu = get_metadata( 'user', $current_user->ID, 'dismiss_pardakhtpal_menu', true );
		if ( $dismiss_pardakhtpal_menu != '1' ){
			$menus[] = array( 'name' => $this->_slug, 'label' => $this->get_short_title(), 'callback' => array( $this, 'temporary_plugin_page' ), 'permission' => $this->_capabilities_form_settings );
		}

		return $menus;
	}

	public function ajax_dismiss_menu(){

		$current_user = wp_get_current_user();
		update_metadata( 'user', $current_user->ID, 'dismiss_pardakhtpal_menu', '1' );
	}

	public function temporary_plugin_page(){
		$current_user = wp_get_current_user();
		?>
<script type="text/javascript">
    function dismissMenu() {
        jQuery('#gf_spinner').show();
        jQuery.post(ajaxurl, {
            action: "gf_dismiss_pardakhtpal_menu"
        },
            function (response) {
                document.location.href = '?page=gf_edit_forms';
                jQuery('#gf_spinner').hide();
            }
        );

    }
</script>

<div class="wrap about-wrap">
    <h1>&#1583;&#1585;&#1711;&#1575;&#1607; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604; &#1576;&#1585;&#1575;&#1740; &#1575;&#1601;&#1586;&#1608;&#1606;&#1607; &#1740; &#1711;&#1585;&#1575;&#1608;&#1740;&#1578;&#1740; &#1601;&#1608;&#1585;&#1605; &#1606;&#1587;&#1582;&#1607; 1.0</h1>
    <div class="about-text">&#1575;&#1586; &#1575;&#1740;&#1606;&#1705;&#1607; &#1602;&#1589;&#1583; &#1575;&#1587;&#1578;&#1601;&#1575;&#1583;&#1607; &#1575;&#1586; &#1583;&#1585;&#1711;&#1575;&#1607; &#1662;&#1585;&#1583;&#1575;&#1582;&#1578; &#1662;&#1575;&#1604; &#1576;&#1585;&#1575;&#1740; &#1575;&#1601;&#1586;&#1608;&#1606;&#1607; &#1711;&#1585;&#1575;&#1608;&#1740;&#1578;&#1740; &#1585;&#1575; &#1583;&#1575;&#1585;&#1740;&#1583; &#1576;&#1587;&#1740;&#1575;&#1585; &#1582;&#1608;&#1588;&#1581;&#1575;&#1604;&#1740;&#1605;!</div>
    <div class="changelog">
        <hr />
        <div class="feature-section col two-col">
            <div class="col-1">
                <h3>&#1606;&#1581;&#1608;&#1607; &#1583;&#1587;&#1578;&#1585;&#1587;&#1740; &#1576;&#1607; &#1604;&#1740;&#1587;&#1578; &#1578;&#1585;&#1575;&#1705;&#1606;&#1588; &#1607;&#1575; &#1583;&#1585; &#1606;&#1587;&#1582;&#1607; &#1607;&#1575;&#1740; &#1580;&#1583;&#1740;&#1583; &#1711;&#1585;&#1575;&#1608;&#1740;&#1578;&#1740; &#1601;&#1608;&#1585;&#1605;</h3>
                <p>&#1607;&#1605;&#1575;&#1606;&#1591;&#1608;&#1585; &#1705;&#1607; &#1575;&#1581;&#1578;&#1605;&#1575;&#1604;&#1575; &#1576;&#1583;&#1575;&#1606;&#1740;&#1583; &#1583;&#1585; &#1606;&#1587;&#1582;&#1607; &#1607;&#1575;&#1740; &#1580;&#1583;&#1740;&#1583; &#1711;&#1585;&#1575;&#1608;&#1740;&#1578;&#1740; &#1601;&#1608;&#1585;&#1605; &#1576;&#1585;&#1575;&#1740; &#1605;&#1588;&#1575;&#1607;&#1583;&#1607; &#1740; &#1604;&#1740;&#1587;&#1578; &#1578;&#1585;&#1575;&#1705;&#1606;&#1588; &#1607;&#1575;&#1740; &#1607;&#1585; &#1583;&#1585;&#1711;&#1575;&#1607; &#1576;&#1575;&#1740;&#1583; &#1576;&#1607; &#1578;&#1576; &#1570;&#1606; &#1583;&#1585;&#1711;&#1575;&#1607; &#1583;&#1585; &#1578;&#1606;&#1592;&#1740;&#1605;&#1575;&#1578; &#1601;&#1608;&#1585;&#1605; &#1605;&#1608;&#1585;&#1583; &#1606;&#1592;&#1585; &#1605;&#1585;&#1575;&#1580;&#1593;&#1607; &#1705;&#1606;&#1740;&#1583;.</p>
            </div>
        </div>

        <hr />

        <form method="post" id="dismiss_menu_form" style="margin-top: 20px;">
            <input type="checkbox" name="dismiss_paypal_menu" value="1" onclick="dismissMenu();">
            <label><?php _e( 'I understand this change, dismiss this message!', 'gravityformspaypal' ) ?></label>
            <img id="gf_spinner" src="<?php echo GFCommon::get_base_url() . '/images/spinner.gif'?>" alt="<?php _e( 'Please wait...', 'gravityformspaypal' ) ?>" style="display:none;"/>
        </form>

    </div>
</div>
<?php
	}

	public function admin_edit_payment_status( $payment_status, $form, $lead ) {
		//allow the payment status to be edited when for paypal, not set to Approved/Paid, and not a subscription
		if ( ! $this->is_payment_gateway( $lead['id'] ) || strtolower( rgpost( 'save' ) ) <> 'edit' || $payment_status == 'Approved' || $payment_status == 'Paid' || rgar( $lead, 'transaction_type' ) == 2 ) {
			return $payment_status;
		}

		//create drop down for payment status
		$payment_string = gform_tooltip( 'pardakhtpal_edit_payment_status', '', true );
		$payment_string .= '<select id="payment_status" name="payment_status">';
		$payment_string .= '<option value="' . $payment_status . '" selected>' . $payment_status . '</option>';
		$payment_string .= '<option value="Paid">Paid</option>';
		$payment_string .= '</select>';

		return $payment_string;
	}

	public function admin_edit_payment_date( $payment_date, $form, $lead ) {
		//allow the payment status to be edited when for paypal, not set to Approved/Paid, and not a subscription
		if ( ! $this->is_payment_gateway( $lead['id'] ) || strtolower( rgpost( 'save' ) ) <> 'edit' ) {
			return $payment_date;
		}

		$payment_date = $lead['payment_date'];
		if ( empty( $payment_date ) ) {
			$payment_date = gmdate( 'y-m-d H:i:s' );
		}

		$input = '<input type="text" id="payment_date" name="payment_date" value="' . $payment_date . '">';

		return $input;
	}

	public function admin_edit_payment_transaction_id( $transaction_id, $form, $lead ) {
		//allow the payment status to be edited when for paypal, not set to Approved/Paid, and not a subscription
		if ( ! $this->is_payment_gateway( $lead['id'] ) || strtolower( rgpost( 'save' ) ) <> 'edit' ) {
			return $transaction_id;
		}

		$input = '<input type="text" id="pardakhtpal_transaction_id" name="pardakhtpal_transaction_id" value="' . $transaction_id . '">';

		return $input;
	}

	public function admin_edit_payment_amount( $payment_amount, $form, $lead ) {

		//allow the payment status to be edited when for paypal, not set to Approved/Paid, and not a subscription
		if ( ! $this->is_payment_gateway( $lead['id'] ) || strtolower( rgpost( 'save' ) ) <> 'edit' ) {
			return $payment_amount;
		}

		if ( empty( $payment_amount ) ) {
			$payment_amount = GFCommon::get_order_total( $form, $lead );
		}

		$input = '<input type="text" id="payment_amount" name="payment_amount" class="gform_currency" value="' . $payment_amount . '">';

		return $input;
	}


	public function admin_edit_payment_status_details( $form_id, $lead ) {

		$form_action = strtolower( rgpost( 'save' ) );
		if ( ! $this->is_payment_gateway( $lead['id'] ) || $form_action <> 'edit' ) {
			return;
		}

		//get data from entry to pre-populate fields
		$payment_amount = rgar( $lead, 'payment_amount' );
		if ( empty( $payment_amount ) ) {
			$form           = GFFormsModel::get_form_meta( $form_id );
			$payment_amount = GFCommon::get_order_total( $form, $lead );
		}
		$transaction_id = rgar( $lead, 'transaction_id' );
		$payment_date   = rgar( $lead, 'payment_date' );
		if ( empty( $payment_date ) ) {
			$payment_date = gmdate( 'y-m-d H:i:s' );
		}

		//display edit fields
		?>
<div id="edit_payment_status_details" style="display: block">
    <table>
        <tr>
            <td colspan="2"><strong>Payment Information</strong></td>
        </tr>

        <tr>
            <td>Date:<?php gform_tooltip( 'pardakhtpal_edit_payment_date' ) ?></td>
            <td>
                <input type="text" id="payment_date" name="payment_date" value="<?php echo $payment_date ?>">
            </td>
        </tr>
        <tr>
            <td>Amount:<?php gform_tooltip( 'pardakhtpal_edit_payment_amount' ) ?></td>
            <td>
                <input type="text" id="payment_amount" name="payment_amount" class="gform_currency" value="<?php echo $payment_amount ?>">
            </td>
        </tr>
        <tr>
            <td nowrap>Transaction ID:<?php gform_tooltip( 'pardakhtpal_edit_payment_transaction_id' ) ?></td>
            <td>
                <input type="text" id="pardakhtpal_transaction_id" name="pardakhtpal_transaction_id" value="<?php echo $transaction_id ?>">
            </td>
        </tr>
    </table>
</div>
<?php
	}

	public function admin_update_payment( $form, $lead_id ) {
		check_admin_referer( 'gforms_save_entry', 'gforms_save_entry' );

		//update payment information in admin, need to use this function so the lead data is updated before displayed in the sidebar info section
		$form_action = strtolower( rgpost( 'save' ) );
		if ( ! $this->is_payment_gateway( $lead_id ) || $form_action <> 'update' ) {
			return;
		}
		//get lead
		$lead = GFFormsModel::get_lead( $lead_id );
        
        //check if current payment status is processing
        if($lead['payment_status'] != 'Processing')
            return;
        
		//get payment fields to update
		$payment_status = rgpost( 'payment_status' );
		//when updating, payment status may not be editable, if no value in post, set to lead payment status
		if ( empty( $payment_status ) ) {
			$payment_status = $lead['payment_status'];
		}

		$payment_amount      = GFCommon::to_number( rgpost( 'payment_amount' ) );
		$payment_transaction = rgpost( 'pardakhtpal_transaction_id' );
		$payment_date        = rgpost( 'payment_date' );
		if ( empty( $payment_date ) ) {
			$payment_date = gmdate( 'y-m-d H:i:s' );
		} else {
			//format date entered by user
			$payment_date = date( 'Y-m-d H:i:s', strtotime( $payment_date ) );
		}

		global $current_user;
		$user_id   = 0;
		$user_name = 'System';
		if ( $current_user && $user_data = get_userdata( $current_user->ID ) ) {
			$user_id   = $current_user->ID;
			$user_name = $user_data->display_name;
		}

		$lead['payment_status'] = $payment_status;
		$lead['payment_amount'] = $payment_amount;
		$lead['payment_date']   = $payment_date;
		$lead['transaction_id'] = $payment_transaction;

		// if payment status does not equal approved/paid or the lead has already been fulfilled, do not continue with fulfillment
		if ( ( $payment_status == 'Approved' || $payment_status == 'Paid' ) && ! $lead['is_fulfilled'] ) {
			$action['id']               = $payment_transaction;
			$action['type']             = 'complete_payment';
			$action['transaction_id']   = $payment_transaction;
			$action['amount']           = $payment_amount;
			$action['entry_id']         = $lead['id'];

			$this->complete_payment( $lead, $action );
			$this->fulfill_order( $lead, $payment_transaction, $payment_amount );
		}
		//update lead, add a note
		GFAPI::update_entry( $lead );
		GFFormsModel::add_note( $lead['id'], $user_id, $user_name, sprintf( __( 'Payment information was manually updated. Status: %s. Amount: %s. Transaction Id: %s. Date: %s', 'gravityformspaypal' ), $lead['payment_status'], GFCommon::to_money( $lead['payment_amount'], $lead['currency'] ), $payment_transaction, $lead['payment_date'] ) );
	}

	public function fulfill_order( &$entry, $transaction_id, $amount, $feed = null ) {

		if ( ! $feed ) {
			$feed = $this->get_payment_feed( $entry );
		}

		$form = GFFormsModel::get_form_meta( $entry['form_id'] );
		if ( rgars( $feed, 'meta/delayPost' ) ) {
			$this->log_debug( __METHOD__ . '(): Creating post.' );
			$entry['post_id'] = GFFormsModel::create_post( $form, $entry );
			$this->log_debug( __METHOD__ . '(): Post created.' );
		}

		if ( rgars( $feed, 'meta/delayNotification' ) ) {
			//sending delayed notifications
			$notifications = rgars( $feed, 'meta/selectedNotifications' );
			GFCommon::send_notifications( $notifications, $form, $entry, true, 'form_submission' );
		}

		do_action( 'gform_pardakhtpal_fulfillment', $entry, $feed, $transaction_id, $amount );
		if ( has_filter( 'gform_pardakhtpal_fulfillment' ) ) {
			$this->log_debug( __METHOD__ . '(): Executing functions hooked to gform_pardakhtpal_fulfillment.' );
		}

	}

	private function is_valid_initial_payment_amount( $entry_id, $amount_paid ){

		//get amount initially sent to paypal
		$amount_sent = gform_get_meta( $entry_id, 'payment_amount' );
		if ( empty( $amount_sent ) ){
			return true;
		}

		$epsilon = 0.00001;
		$is_equal = abs( floatval( $amount_paid ) - floatval( $amount_sent ) ) < $epsilon;
		$is_greater = floatval( $amount_paid ) > floatval( $amount_sent );

		//initial payment is valid if it is equal to or greater than product/subscription amount
		if ( $is_equal || $is_greater ){
			return true;
		}

		return false;

	}

	public function pardakhtpal_fulfillment( $entry, $paypal_config, $transaction_id, $amount ) {
		//no need to do anything for paypal when it runs this function, ignore
		return false;
	}

	//------ FOR BACKWARDS COMPATIBILITY ----------------------//

	//Change data when upgrading from legacy paypal
	public function upgrade( $previous_version ) {

		$previous_is_pre_addon_framework = version_compare( $previous_version, '2.0.dev1', '<' );

		if ( $previous_is_pre_addon_framework ) {

			//copy plugin settings
			$this->copy_settings();

			//copy existing feeds to new table
			$this->copy_feeds();

			//copy existing paypal transactions to new table
			$this->copy_transactions();

			//updating payment_gateway entry meta to 'gravityformspaypal' from 'paypal'
			$this->update_payment_gateway();

			//updating entry status from 'Approved' to 'Paid'
			$this->update_lead();			
			
		}
	}

	public function update_feed_id( $old_feed_id, $new_feed_id ){
		global $wpdb;
		$sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}rg_lead_meta SET meta_value=%s WHERE meta_key='pardakhtpal_feed_id' AND meta_value=%s", $new_feed_id, $old_feed_id );
		$wpdb->query( $sql );
	}

	public function add_legacy_meta( $new_meta, $old_feed ){

		$known_meta_keys = array(
								'email', 'mode', 'type', 'style', 'continue_text', 'cancel_url', 'disable_note', 'disable_shipping', 'recurring_amount_field', 'recurring_times',
								'recurring_retry', 'billing_cycle_number', 'billing_cycle_type', 'trial_period_enabled', 'trial_amount', 'trial_period_number', 'trial_period_type', 'delay_post',
								'update_post_action', 'delay_notifications', 'selected_notifications', 'pardakhtpal_conditional_enabled', 'pardakhtpal_conditional_field_id',
								'pardakhtpal_conditional_operator', 'pardakhtpal_conditional_value', 'customer_fields',
								);

		foreach ( $old_feed['meta'] as $key => $value ){
			if ( ! in_array( $key, $known_meta_keys ) ){
				$new_meta[ $key ] = $value;
			}
		}

		return $new_meta;
	}

	public function update_payment_gateway() {
		global $wpdb;
		$sql = $wpdb->prepare( "UPDATE {$wpdb->prefix}rg_lead_meta SET meta_value=%s WHERE meta_key='payment_gateway' AND meta_value='pardakhtpal'", $this->_slug );
		$wpdb->query( $sql );
	}

	public function update_lead() {
		global $wpdb;
		$sql = $wpdb->prepare(
			"UPDATE {$wpdb->prefix}rg_lead
			    SET payment_status='Paid', payment_method='PardakhtPal'
		        WHERE payment_status='Approved'
		     		AND ID IN (
					  	SELECT lead_id FROM {$wpdb->prefix}rg_lead_meta WHERE meta_key='payment_gateway' AND meta_value=%s
				   	)",
			$this->_slug);

		$wpdb->query( $sql );
	}

	public function copy_settings() {
		//copy plugin settings
		$old_settings = get_option( 'gf_pardakhtpal_configured' );
		$new_settings = array( 'gf_pardakhtpal_configured' => $old_settings );
		$this->update_plugin_settings( $new_settings );
	}

	public function copy_feeds() {
		//get feeds
		$old_feeds = $this->get_old_feeds();

		if ( $old_feeds ) {

			$counter = 1;
			foreach ( $old_feeds as $old_feed ) {
				$feed_name       = 'Feed ' . $counter;
				$form_id         = $old_feed['form_id'];
				$is_active       = $old_feed['is_active'];
				$customer_fields = $old_feed['meta']['customer_fields'];

				$new_meta = array(
					'feedName'                     => $feed_name,
					'pardakhtpalEmail'                  => rgar( $old_feed['meta'], 'email' ),
					'mode'                         => rgar( $old_feed['meta'], 'mode' ),
					'transactionType'              => rgar( $old_feed['meta'], 'type' ),
					'type'                         => rgar( $old_feed['meta'], 'type' ), //For backwards compatibility of the delayed payment feature
					'pageStyle'                    => rgar( $old_feed['meta'], 'style' ),
					'continueText'                 => rgar( $old_feed['meta'], 'continue_text' ),
					'cancelUrl'                    => rgar( $old_feed['meta'], 'cancel_url' ),
					'disableNote'                  => rgar( $old_feed['meta'], 'disable_note' ),
					'disableShipping'              => rgar( $old_feed['meta'], 'disable_shipping' ),

					'recurringAmount'              => rgar( $old_feed['meta'], 'recurring_amount_field' ) == 'all' ? 'form_total' : rgar( $old_feed['meta'], 'recurring_amount_field' ),
					'recurring_amount_field'       => rgar( $old_feed['meta'], 'recurring_amount_field' ), //For backwards compatibility of the delayed payment feature
					'recurringTimes'               => rgar( $old_feed['meta'], 'recurring_times' ),
					'recurringRetry'               => rgar( $old_feed['meta'], 'recurring_retry' ),
					'paymentAmount'                => 'form_total',
					'billingCycle_length'          => rgar( $old_feed['meta'], 'billing_cycle_number' ),
					'billingCycle_unit'            => $this->convert_interval( rgar( $old_feed['meta'], 'billing_cycle_type' ), 'text' ),

					'trial_enabled'                => rgar( $old_feed['meta'], 'trial_period_enabled' ),
					'trial_product'                => 'enter_amount',
					'trial_amount'                 => rgar( $old_feed['meta'], 'trial_amount' ),
					'trialPeriod_length'           => rgar( $old_feed['meta'], 'trial_period_number' ),
					'trialPeriod_unit'             => $this->convert_interval( rgar( $old_feed['meta'], 'trial_period_type' ), 'text' ),

					'delayPost'                    => rgar( $old_feed['meta'], 'delay_post' ),
					'change_post_status'           => rgar( $old_feed['meta'], 'update_post_action' ) ? '1' : '0',
					'update_post_action'           => rgar( $old_feed['meta'], 'update_post_action' ),

					'delayNotification'            => rgar( $old_feed['meta'], 'delay_notifications' ),
					'selectedNotifications'        => rgar( $old_feed['meta'], 'selected_notifications' ),

					'billingInformation_firstName' => rgar( $customer_fields, 'first_name' ),
					'billingInformation_lastName'  => rgar( $customer_fields, 'last_name' ),
					'billingInformation_email'     => rgar( $customer_fields, 'email' ),
					'billingInformation_address'   => rgar( $customer_fields, 'address1' ),
					'billingInformation_address2'  => rgar( $customer_fields, 'address2' ),
					'billingInformation_city'      => rgar( $customer_fields, 'city' ),
					'billingInformation_state'     => rgar( $customer_fields, 'state' ),
					'billingInformation_zip'       => rgar( $customer_fields, 'zip' ),
					'billingInformation_country'   => rgar( $customer_fields, 'country' ),

				);

				$new_meta = $this->add_legacy_meta( $new_meta, $old_feed );

				//add conditional logic
				$conditional_enabled = rgar( $old_feed['meta'], 'pardakhtpal_conditional_enabled' );
				if ( $conditional_enabled ) {
					$new_meta['feed_condition_conditional_logic']        = 1;
					$new_meta['feed_condition_conditional_logic_object'] = array(
						'conditionalLogic' =>
							array(
								'actionType' => 'show',
								'logicType'  => 'all',
								'rules'      => array(
									array(
										'fieldId'  => rgar( $old_feed['meta'], 'pardakhtpal_conditional_field_id' ),
										'operator' => rgar( $old_feed['meta'], 'pardakhtpal_conditional_operator' ),
										'value'    => rgar( $old_feed['meta'], 'pardakhtpal_conditional_value' )
									),
								)
							)
					);
				} else {
					$new_meta['feed_condition_conditional_logic'] = 0;
				}


				$new_feed_id = $this->insert_feed( $form_id, $is_active, $new_meta );
				$this->update_feed_id( $old_feed['id'], $new_feed_id );

				$counter ++;
			}
		}
	}

	public function copy_transactions(){
		//copy transactions from the paypal transaction table to the add payment transaction table
		global $wpdb;
		$old_table_name = $this->get_old_transaction_table_name();
		$this->log_debug( __METHOD__ . '(): Copying old PayPal transactions into new table structure.' );

		$new_table_name = $this->get_new_transaction_table_name();
		
		$sql	=	"INSERT INTO {$new_table_name} (lead_id, transaction_type, transaction_id, is_recurring, amount, date_created)
					SELECT entry_id, transaction_type, transaction_id, is_renewal, amount, date_created FROM {$old_table_name}";

		$wpdb->query( $sql );

		$this->log_debug( __METHOD__ . "(): transactions: {$wpdb->rows_affected} rows were added." );
	}
	
	public function get_old_transaction_table_name(){
		global $wpdb;
		return $wpdb->prefix . 'rg_pardakhtpal_transaction';
	}

	public function get_new_transaction_table_name(){
		global $wpdb;
		return $wpdb->prefix . 'gf_addon_payment_transaction';
	}

	public function get_old_feeds() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'rg_pardakhtpal';

		$form_table_name = GFFormsModel::get_form_table_name();
		$sql     = "SELECT s.id, s.is_active, s.form_id, s.meta, f.title as form_title
					FROM {$table_name} s
					INNER JOIN {$form_table_name} f ON s.form_id = f.id";

		$this->log_debug( __METHOD__ . "(): getting old feeds: {$sql}" );

		$results = $wpdb->get_results( $sql, ARRAY_A );

		$this->log_debug( __METHOD__ . "(): error?: {$wpdb->last_error}" );

		$count = sizeof( $results );

		$this->log_debug( __METHOD__ . "(): count: {$count}" );

		for ( $i = 0; $i < $count; $i ++ ) {
			$results[ $i ]['meta'] = maybe_unserialize( $results[ $i ]['meta'] );
		}

		return $results;
	}

	//This function kept static for backwards compatibility
	public static function get_config_by_entry( $entry ) {

		$pardakhtpal = GFPardakhtPal::get_instance();

		$feed = $pardakhtpal->get_payment_feed( $entry );

		if ( empty( $feed ) ) {
			return false;
		}

		return $feed['addon_slug'] == $pardakhtpal->_slug ? $feed : false;
	}

	//This function kept static for backwards compatibility
	//This needs to be here until all add-ons are on the framework, otherwise they look for this function
	public static function get_config( $form_id ) {

		$pardakhtpal = GFPardakhtPal::get_instance();
		$feed   = $pardakhtpal->get_feeds( $form_id );

		//Ignore IPN messages from forms that are no longer configured with the pardakhtpal add-on
		if ( ! $feed ) {
			return false;
		}

		return $feed[0]; //only one feed per form is supported (left for backwards compatibility)
	}
}