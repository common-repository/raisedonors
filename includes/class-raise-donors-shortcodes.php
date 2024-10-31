<?php
/**
 * Register all shortcodes for the plugin
 *
 * @link       https://raisedonors.com/
 * @since      1.0.0
 *
 * @package    Raise_Donors
 * @subpackage Raise_Donors/includes
 */

/**
 * Register all shortcodes for the plugin.
 *
 *
 * @package    Raise_Donors
 * @subpackage Raise_Donors/shortcodes
 * @author     RaiseDonors, LLC <info@raisedonors.com>
 */
class Raise_Donors_Shortcodes
{
    function load()
    {

        add_shortcode('raise-donors', [$this, 'raise_donors_shortcode']);
    }

    function raise_donors_shortcode($atts)
    {
        if (!$atts['campaignid']) {
            return '';
        }

        if (!$atts['display']) {
            $atts['display'] = 'form';
        }

        $args = array(
            'meta_key' => 'raise_donors_campaign_id',
            'meta_value' => $atts['campaignid'],
            'post_type' => 'rd_campaign'
        );
        $query = new WP_Query($args);
        if ($query->post_count) {
            $current_post = $query->post;
        } else {
            $current_post = $this->getCampaign($atts['campaignid']);
        }
        if($current_post == 'error' &&  current_user_can('administrator') ){
            return '<blockquote style="color: red;">ERROR: The campaign you\'ve selected is no longer available. Please choose another campaign page.</blockquote>';
        }

        return get_post_meta($current_post->ID, 'raise_donors_format_' . $atts['display'], true);

    }

    public static function getCampaign($id)
    {
        $campaign = Raise_Donors_Connection::campaign($id);
        if ($campaign['error']) {
            return 'error';
        }
        $pID = wp_insert_post([
            'post_title' => $campaign['publicTitle'],
            'post_type' => 'rd_campaign'
        ]);
        add_post_meta($pID, 'raise_donors_format_full', $campaign['embedScripts']['pageContentWithDonationForm'],
            true);
        add_post_meta($pID, 'raise_donors_format_form', $campaign['embedScripts']['donationFormOnly'], true);
        add_post_meta($pID, 'raise_donors_campaign_id', $campaign['campaignId'], true);
        add_post_meta($pID, 'raise_donors_data', json_encode($campaign), true);

        return get_post($pID);
    }


}