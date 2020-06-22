<?php

class CRMSettingPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'CRM Settings', 
            'manage_options', 
            'crm-setting-page', 
            array( $this, 'crm_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function crm_admin_page()
    {
        // Set class property
        $this->options = get_option( 'crm_option_name' );
        ?>
        <div class="wrap">
            <?php 
            $my_options = get_option( 'crm_option_name' );
            $name_label = $my_options['name_label'];
            $phonenumber_label = $my_options['phonenumber'];
            $email = $my_options['email'];
            $desiredbudget_label = $my_options['desiredbudget'];
            $message_label = $my_options['message'];
            $message_cols = $my_options['message_cols'];
            $message_rows = $my_options['message_rows'];
            $name_max_size = $my_options['name_max_size'];
            $phonenumber_max_lenght = $my_options['phonenumber_max_lenght'];
            $email_max_lenght = $my_options['email_max_lenght'];
            $budget_max_lenght = $my_options['budget_max_lenght'];
            ?>
            <h1><?php _e("CRM Form Shortcode");?></h1>
            <p><?php _e("Place this shortcode in you page.");?></p>
            <div class="shortcode">
            <h2><code>
                <?php if($name_label  && $phonenumber_label && $email && $desiredbudget_label && $message_label && $message_cols && $message_rows && $name_max_size
                    && $phonenumber_max_lenght && $email_max_lenght && $budget_max_lenght == ""){
                    echo "`[customer_form]`";
                }  ?>

                [customer_form <?php if( $name_label != "" ){ echo 'name='.'"'.$name_label.'"';} if( $phonenumber_label != "" ){ echo ' phonenumber='.'"'.$phonenumber_label.'"';}
                if( $email != "" ){ echo ' email='.'"'.$email.'"';}if( $desiredbudget_label != "" ){ echo ' desiredbudget='.'"'.$desiredbudget_label.'"';}if( $message_label != "" ){ echo ' message='.'"'.$message_label.'"';}if( $message_cols != "" ){ echo ' message_cols='.'"'.$message_cols.'"';}if( $message_rows != "" ){ echo ' message_rows='.'"'.$message_rows.'"';} if( $name_max_size != "" ){ echo ' name_max_lenght='.'"'.$name_max_size.'"';} if( $phonenumber_max_lenght != "" ){ echo ' phone_max_lenght='.'"'.$phonenumber_max_lenght.'"';} if( $email_max_lenght != "" ){ echo ' email_max_lenght='.'"'.$email_max_lenght.'"';}if( $budget_max_lenght != "" ){ echo ' budget_max_lenght='.'"'.$budget_max_lenght.'"';}?>]    </code></h2>
            </div>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'crm_option_group' );
                do_settings_sections( 'crm-setting-page' );
                submit_button();
            ?>
            </form>
        </div>

        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'crm_option_group', // Option group
            'crm_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'CRM Custom Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'crm-setting-page' // Page
        );  

        add_settings_field(
            'name_label', // ID
            'Name Label', // Title 
            array( $this, 'name_label_callback' ), // Callback
            'crm-setting-page', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'phonenumber', 
            'Phone Number Label', 
            array( $this, 'phonenumber_callback' ), 
            'crm-setting-page', 
            'setting_section_id'
        );        

        add_settings_field(
            'email', 
            'Email Label', 
            array( $this, 'email_callback' ), 
            'crm-setting-page', 
            'setting_section_id'
        );       

        add_settings_field(
            'desiredbudget', 
            'Desired Budget Label', 
            array( $this, 'desiredbudget_callback' ), 
            'crm-setting-page', 
            'setting_section_id'
        );       

        add_settings_field(
            'message', 
            'Message', 
            array( $this, 'message_callback' ), 
            'crm-setting-page', 
            'setting_section_id'
        ); 


        add_settings_field(
            'message_cols', 
            'Message Columns', 
            array( $this, 'message_cols_callback' ), 
            'crm-setting-page', 
            'setting_section_id'
        );          

        add_settings_field(
            'message_rows', 
            'Message Rows', 
            array( $this, 'message_rows_callback' ), 
            'crm-setting-page', 
            'setting_section_id'
        );            

        add_settings_field(
            'name_max_size', 
            'Name Max Size', 
            array( $this, 'name_max_size_callback' ), 
            'crm-setting-page', 
            'setting_section_id'
        );           

        add_settings_field(
            'phonenumber_max_lenght', 
            'Phone Number Max Size', 
            array( $this, 'phonenumber_max_lenght_callback' ), 
            'crm-setting-page', 
            'setting_section_id'
        );           

        add_settings_field(
            'budget_max_lenght', 
            'Desired Budget Max Size', 
            array( $this, 'budget_max_lenght_callback' ), 
            'crm-setting-page', 
            'setting_section_id'
        ); 

        add_settings_field(
            'email_max_lenght', 
            'Email Max Size', 
            array( $this, 'email_max_lenght_callback' ), 
            'crm-setting-page', 
            'setting_section_id'
        ); 
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['name_label'] ) )
            $new_input['name_label'] = sanitize_text_field( $input['name_label'] );

        if( isset( $input['phonenumber'] ) )
            $new_input['phonenumber'] = sanitize_text_field( $input['phonenumber'] );

        if( isset( $input['email'] ) )
            $new_input['email'] = sanitize_text_field( $input['email'] );

        if( isset( $input['desiredbudget'] ) )
            $new_input['desiredbudget'] = sanitize_text_field( $input['desiredbudget'] );

        if( isset( $input['message'] ) )
            $new_input['message'] = sanitize_text_field( $input['message'] );

        if( isset( $input['message_cols'] ) )
            $new_input['message_cols'] = absint( $input['message_cols'] );

        if( isset( $input['message_rows'] ) )
            $new_input['message_rows'] = absint( $input['message_rows'] );

        if( isset( $input['name_max_size'] ) )
            $new_input['name_max_size'] = absint( $input['name_max_size'] );

        if( isset( $input['phonenumber_max_lenght'] ) )
            $new_input['phonenumber_max_lenght'] = absint( $input['phonenumber_max_lenght'] );

        if( isset( $input['budget_max_lenght'] ) )
            $new_input['budget_max_lenght'] = absint( $input['budget_max_lenght'] );

        if( isset( $input['email_max_lenght'] ) )
            $new_input['email_max_lenght'] = absint( $input['email_max_lenght'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print "<p>Leave it blank if you don't want to change default setting of field. </p>";
        print '<p>Enter your settings below:</p>';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function name_label_callback()
    {
        printf(
            '<input type="text" id="name_label" name="crm_option_name[name_label]" value="%s" />',
            isset( $this->options['name_label'] ) ? esc_attr( $this->options['name_label']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function phonenumber_callback()
    {
        printf(
            '<input type="text" id="phonenumber" name="crm_option_name[phonenumber]" value="%s" />',
            isset( $this->options['phonenumber'] ) ? esc_attr( $this->options['phonenumber']) : ''
        );
    }


    /** 
     * Get the settings option array and print one of its values
     */
    public function email_callback()
    {
        printf(
            '<input type="text" id="email" name="crm_option_name[email]" value="%s" />',
            isset( $this->options['email'] ) ? esc_attr( $this->options['email']) : ''
        );
    }


    /** 
     * Get the settings option array and print one of its values
     */
    public function desiredbudget_callback()
    {
        printf(
            '<input type="text" id="desiredbudget" name="crm_option_name[desiredbudget]" value="%s" />',
            isset( $this->options['desiredbudget'] ) ? esc_attr( $this->options['desiredbudget']) : ''
        );
    }


    /** 
     * Get the settings option array and print one of its values
     */
    public function message_callback()
    {
        printf(
            '<input type="text" id="message" name="crm_option_name[message]" value="%s" />',
            isset( $this->options['message'] ) ? esc_attr( $this->options['message']) : ''
        );
    }


    /** 
     * Get the settings option array and print one of its values
     */
    public function message_cols_callback()
    {
        printf(
            '<input type="number" id="message_cols" name="crm_option_name[message_cols]" value="%d" />',
            isset( $this->options['message_cols'] ) ? esc_attr( $this->options['message_cols']) : ''
        );
    }


    /** 
     * Get the settings option array and print one of its values
     */
    public function message_rows_callback()
    {
        printf(
            '<input type="number" id="message_rows" name="crm_option_name[message_rows]" value="%d" />',
            isset( $this->options['message_rows'] ) ? esc_attr( $this->options['message_rows']) : ''
        );
    }


    /** 
     * Get the settings option array and print one of its values
     */
    public function name_max_size_callback()
    {
        printf(
            '<input type="number" id="name_max_size" name="crm_option_name[name_max_size]" value="%d" />',
            isset( $this->options['name_max_size'] ) ? esc_attr( $this->options['name_max_size']) : ''
        );
    }


    /** 
     * Get the settings option array and print one of its values
     */
    public function phonenumber_max_lenght_callback()
    {
        printf(
            '<input type="number" id="phonenumber_max_lenght" name="crm_option_name[phonenumber_max_lenght]" value="%d" />',
            isset( $this->options['phonenumber_max_lenght'] ) ? esc_attr( $this->options['phonenumber_max_lenght']) : ''
        );
    }



    /** 
     * Get the settings option array and print one of its values
     */
    public function budget_max_lenght_callback()
    {
        printf(
            '<input type="number" id="budget_max_lenght" name="crm_option_name[budget_max_lenght]" value="%d" />',
            isset( $this->options['budget_max_lenght'] ) ? esc_attr( $this->options['budget_max_lenght']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function email_max_lenght_callback()
    {
        printf(
            '<input type="number" id="email_max_lenght" name="crm_option_name[email_max_lenght]" value="%d" />',
            isset( $this->options['email_max_lenght'] ) ? esc_attr( $this->options['email_max_lenght']) : ''
        );
    }
}

if( is_admin() )
    $my_settings_page = new CRMSettingPage();