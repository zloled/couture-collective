<?php

class CC_New_User_Email extends WC_Email_Customer_New_Account {

	/**
	 * Add a hook on user-approval to send the welcome email.
	 */
	public function __construct() {
		add_action('wpau_approve', array($this, 'trigger'));
		parent::__construct();
	}

	/**
	 * Trigger the email iff the user has been approved.
	 * 
	 * @param int $user_id the id of the user to send the email to
	 * @param string $user_password that user's password
	 * @param bool $password_generated true if the password was autogenerated for the user, false otherwise.
	 */
	public function trigger( $user_id, $user_password = '', $password_generated = false ) {
		if ( get_user_meta( $user_id, 'wp-approve-user', true ) ) {
			parent::trigger( $user_id, $user_password, $password_generated );
		}
	}
}

?>