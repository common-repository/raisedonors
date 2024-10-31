<?php

class Raise_Donors_Connection
{
    static function campaignsSearch($for)
    {
        return static::get('embed-campaigns/search?searchFor=' . urlencode($for));
    }

    static function campaigns($page = 1, $pageSize = 25)
    {
        return static::get('embed-campaigns?page=' . $page . '&pageSize=' . $pageSize);
    }

    static function campaign($id)
    {
        return static::get('embed-campaigns/' . $id);
    }

    private static function get($url)
    {
        $settings = Raise_Donors_Settings::getSettings();
        $args = array(
            'headers' => array(
                'Content' => 'application/js',
                'X-OrganizationKey' => $settings['organization_key_0'],
                'Authorization' => 'BASIC ' . $settings['license_key_1']
            )
        );

        $crl = wp_remote_get('https://api.raisedonors.com/v3/' . $url, $args);

        $crl = wp_remote_retrieve_body($crl);

        return json_decode($crl, true);
    }
}