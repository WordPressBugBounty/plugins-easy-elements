<?php
namespace Easyel\EasyElements\Utils;

if ( ! defined( 'ABSPATH' ) ) exit;
class QueryHelper {

    /** @var self|null */
    private static $instance = null;

    /**
     * Singleton Instance
     */
    public static function get_instance() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_ajax_easyel_get_posts_by_query', [$this, 'get_posts_by_query']);
        add_action('wp_ajax_easyel_get_posts_title_by_id', [$this, 'get_posts_title_by_id']);
        add_action('wp_ajax_nopriv_easyel_get_posts_title_by_id', [$this, 'get_posts_title_by_id']);
        add_action('wp_ajax_easyel_get_taxonomies_by_query', [$this, 'get_taxonomies_by_query']);
        add_action('wp_ajax_nopriv_easyel_get_taxonomies_by_query', [$this, 'get_taxonomies_by_query']);
        add_action('wp_ajax_easyel_get_taxonomies_title_by_id', [$this, 'get_taxonomies_title_by_id']);
        add_action('wp_ajax_nopriv_easyel_get_taxonomies_title_by_id', [$this, 'get_taxonomies_title_by_id']);
    }

    public function get_posts_by_query() {
        check_ajax_referer('easyel_autocomplete_nonce', 'security');

        $search = isset($_POST['q']) ? sanitize_text_field( wp_unslash($_POST['q']) ) : '';
        $post_type = isset($_POST['post_type']) ? sanitize_text_field( wp_unslash($_POST['post_type']) ) : 'post';
        $query_type = isset($_POST['query_type']) ? sanitize_text_field( wp_unslash($_POST['query_type']) ) : '';

        $results = [];

        switch($query_type){
            case 'post_type':
            case 'single_post_type':
                $results = $this->get_public_post_types($search);
                break;
            case 'post_id':
                $results = $this->get_posts_by_id($search);
                break;
            case 'taxonomy':
                $results = $this->get_public_taxonomies($search);
                break;
            case 'term_id':
            case 'single_posts_term_id':
                $results = $this->get_terms_by_search($search);
                break;
            case 'user_id':
                $results = $this->get_users_by_search( $search );
                break;

            case 'user_role':
                $results = $this->get_user_roles_by_search( $search );
        break;
            default:
                $results = $this->get_posts_by_search($post_type, $search);
        }

        wp_send_json($results);
    }

    private function get_users_by_search( $search = '' ) {

        $results = [];

        $args = [
            'number' => 20,
            'search' => '*' . esc_attr( $search ) . '*',
            'search_columns' => [ 'user_login', 'user_email', 'display_name' ],
        ];

        $users = get_users( $args );

        foreach ( $users as $user ) {
            $results[] = [
                'id'   => $user->ID,
                'text' => $user->display_name,
            ];
        }

        return $results;
    }

    private function get_user_roles_by_search( $search = '' ) {

        global $wp_roles;
        $results = [];

        foreach ( $wp_roles->roles as $role_key => $role ) {

            if ( $search && stripos( $role['name'], $search ) === false ) {
                continue;
            }

            $results[] = [
                'id'   => $role_key,
                'text' => $role['name'],
            ];
        }

        return $results;
    }

    public function get_posts_title_by_id() {

        check_ajax_referer('easyel_autocomplete_nonce', 'security');

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $raw_ids = $_POST['id'] ?? [];
        $ids = map_deep( wp_parse_args( wp_unslash(  $raw_ids ?? [] ), [] ), 'sanitize_text_field' );
        $post_type = isset($_POST['post_type']) ? sanitize_text_field( wp_unslash($_POST['post_type']) ) : 'post';
        $query_type = isset($_POST['query_type']) ? sanitize_text_field( wp_unslash($_POST['query_type']) ) : '';
        $results = [];

        switch($query_type){
            case 'post_type':
            case 'single_post_type':
                foreach($ids as $id) {
                    $obj = get_post_type_object($id);
                    if($obj) $results[$id]=$obj->label;
                }
                break;
            case 'post_id':
                $query = new \WP_Query([
                    'post_type'=>get_post_types(['public'=>true]),
                    'post__in'=>$ids,
                    'posts_per_page'=>-1,
                    'orderby'=>'post__in'
                ]);
                foreach($query->posts as $post) $results[$post->ID]=$post->post_title;
                break;
            case 'taxonomy':
                foreach($ids as $id){
                    $taxonomy=get_taxonomy($id);
                   
                    if($taxonomy) $results[$id]=$taxonomy->label;
                }
                break;
            case 'term_id':
            case 'single_posts_term_id':
                foreach($ids as $id){
                    $term=get_term($id);
                    if($term && !is_wp_error($term)) $results[$id]=$term->name;
                }
                break;

            case 'user_id':
                foreach ( $ids as $id ) {
                    $user = get_user_by( 'id', $id );
                    if ( $user ) {
                        $results[ $id ] = $user->display_name;
                    }
                }
                break;

            case 'user_role':
                global $wp_roles;
                foreach ( $ids as $id ) {
                    if ( isset( $wp_roles->roles[ $id ] ) ) {
                        $results[ $id ] = $wp_roles->roles[ $id ]['name'];
                    }
                }
                break;

            default:
                $query = new \WP_Query(['post_type'=>$post_type,'post__in'=>$ids,'posts_per_page'=>-1,'orderby'=>'post__in']);
                foreach($query->posts as $post) $results[$post->ID]=$post->post_title;
        }

        wp_send_json($results);
    }

    public function get_taxonomies_by_query() {

        check_ajax_referer('easyel_autocomplete_nonce', 'security');
        $search = isset($_POST['q']) ? sanitize_text_field( wp_unslash($_POST['q']) ) : '';

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $taxonomy = $_POST['taxonomy'] ?? [];
        $taxonomy = map_deep( wp_parse_args( wp_unslash(  $taxonomy ?? [] ), [] ), 'sanitize_text_field' );

        $taxonomy = is_array($taxonomy) ? array_filter($taxonomy, 'taxonomy_exists') : $taxonomy;
        $results=[];

        $terms = get_terms(['taxonomy'=>$taxonomy,'hide_empty'=>false,'search'=>$search]);
        foreach($terms as $term) {
            $results[]=['id'=>$term->term_id,'text'=>$term->name];
        }

        wp_send_json($results);
    }

    public function get_taxonomies_title_by_id() {

        check_ajax_referer('easyel_autocomplete_nonce', 'security');

        // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
        $raw_ids = $_POST['id'] ?? [];
        $ids = map_deep( wp_parse_args( wp_unslash(  $raw_ids ?? [] ), [] ), 'sanitize_text_field' );
        $terms = get_terms(['include'=>$ids,'hide_empty'=>false]);
        $results=[];
        foreach($terms as $term) {
            $results[$term->term_id]=$term->name;
        }

        wp_send_json($results);
    }

    // Helper methods
    private function get_public_post_types($search=''){
        $results=[];
        $types = get_post_types(['public'=>true],'objects');
        foreach($types as $type){
            if($search && stripos($type->label,$search)===false) continue;
            $results[]=['id'=>$type->name,'text'=>$type->label];
        }
        return $results;
    }

    private function get_posts_by_id($search=''){
        $results=[];
        $posts = get_posts(['post_type'=>get_post_types(['public'=>true]),'posts_per_page'=>100,'s'=>$search]);
        foreach($posts as $post) $results[]=['id'=>$post->ID,'text'=>$post->post_title];
        return $results;
    }

    private function get_public_taxonomies($search=''){
        $results=[];
        $taxes=get_taxonomies(['public'=>true],'objects');
        foreach($taxes as $tax){
            if($search && stripos($tax->label,$search)===false) continue;
            $results[]=['id'=>$tax->name,'text'=>$tax->label];
        }
        return $results;
    }

    private function get_terms_by_search($search=''){
        $results=[];
        $taxes = get_taxonomies( ['public' => true] );

        
        foreach($taxes as $tax){
            $terms=get_terms(['taxonomy'=>$tax,'hide_empty'=>false,'search'=>$search]);
            foreach($terms as $term) $results[]=['id'=>$term->term_id,'text'=>$term->name];
        }
        return $results;
    }

    private function get_posts_by_search($post_type,$search=''){
        $results=[];
        $query=new \WP_Query(['post_type'=>$post_type,'post_status'=>'publish','s'=>$search,'posts_per_page'=>-1]);
        foreach($query->posts as $post) $results[]=['id'=>$post->ID,'text'=>$post->post_title];
        return $results;
    }
}