<?php
namespace Easyel\EasyElements\Tracking;

use Appsero\Client;

if ( ! defined( 'ABSPATH' ) ) exit;

class Appsero_Tracker {

    private static $instance = null;
    private $client;

    public static function instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init();
    }

    private function init() {

        // Appsero Client init
        $this->client = new Client(
            '8c8586eb-5fc7-493d-97f3-87f342fa8a09', 
            'Easy Elements for Elementor',
            EASYELEMENTS_FILE
        );

        $this->client->set_textdomain( 'easy-elements' );

        $this->client->insights()
            ->add_plugin_data()
            ->add_extra( $this->extra_data() )
            ->init();
    }

    private function extra_data() {
        return [
            'is_pro_active' => defined( 'EASYELEMENTS_PRO_VER' ) ? 'Yes' : 'No',
            'pro_version'   => defined( 'EASYELEMENTS_PRO_VER' ) ? EASYELEMENTS_PRO_VER : '',
        ];
    }
}