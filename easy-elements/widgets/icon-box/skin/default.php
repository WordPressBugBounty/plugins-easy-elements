<?php
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="ee--icon-box <?php echo esc_attr($icon_direction); ?> eel-bf-<?php echo esc_attr( $item_hover_styles ); ?> eel-info-<?php echo esc_attr($info_skin); ?>">
        <?php if ( $link && $enable_box_link ) : ?>
            <a class="eel-box-link" href="<?php echo esc_url($link); ?>"<?php
                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Hardcoded attribute strings, not user input.
                echo $target . $nofollow;
            ?>></a>
        <?php endif; ?>

        <?php if ( isset( $settings['icon']['value'] ) && $settings['icon']['value'] ) : ?>
            <span class="eel-icon">
                <?php
                \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                ?>
            </span>
        <?php elseif ( 'image' === $settings['icon_type'] && ! empty( $settings['icon_image']['url'] ) ) : ?>
            <span class="eel-icon eel-icon-image">
                <?php
                if ( ! empty( $settings['icon_image']['id'] ) ) {
                    echo wp_get_attachment_image(
                        $settings['icon_image']['id'],
                        'full',
                        false,
                        ['alt' => $settings['icon_image']['alt'] ?? '']
                    );
                } elseif ( ! empty( $settings['icon_image']['url'] ) ) {
                    echo '<img src="' . esc_url( $settings['icon_image']['url'] ) . '" alt="">';
                }
                ?>
            </span>
        <?php endif; ?>

        <?php if ( ! empty( $settings['number_title'] ) ) : ?>
            <div class="eel-pro-number  easyel-gradeint-<?php echo esc_attr($settings['number_gradeint']) ?>"><?php echo esc_html( $settings['number_title'] ); ?></div>
        <?php endif; ?>

        <?php if ( $settings['icon_direction'] === 'left' || $settings['icon_direction'] === 'right' ) : ?>
            <div class="eel-title-content-wrap">
        <?php endif; ?>

        <?php if ( ! empty( $settings['procs_title'] ) ) :
            $this->add_inline_editing_attributes( 'procs_title' );
            $easyel_title_tag = isset( $settings['title_tag'] ) ? esc_attr( $settings['title_tag'] ) : 'h3';
        ?>
            <<?php echo tag_escape( $easyel_title_tag ); ?> class="icon-box-title elementor-inline-editing" <?php
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $this->get_render_attribute_string( 'procs_title' ); ?>>
                <?php echo wp_kses_post( $settings['procs_title'] ); ?>
            </<?php echo tag_escape( $easyel_title_tag ); ?>>
        <?php endif; ?>


        <?php if ( ! empty( $settings['_description'] ) ) :
            $this->add_inline_editing_attributes( '_description' );
        ?>
            <div class="icon-box-description elementor-inline-editing" <?php
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo $this->get_render_attribute_string( '_description' ); ?>>
            <?php echo wp_kses_post( $settings['_description'] ); ?></div>
        <?php endif; ?>

        <?php if ( !empty($settings['show_read_more']) && $settings['show_read_more'] === 'yes' ) : ?>
            <div class="eel-read-more">
                <?php if ( $link ) : ?>
                    <a class="eel-read-more-link" href="<?php echo esc_url($link); ?>"<?php
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Hardcoded attribute strings, not user input.
                        echo $target . $nofollow;
                    ?>>
                <?php endif; ?>
                <?php if ( $settings['read_more_type'] === 'read_icon' ) : ?>
                    <span class="eel-read-more-icon">
                        <?php
                        if ( !empty($settings['read_more_icon']['value']) ) {
                            \Elementor\Icons_Manager::render_icon( $settings['read_more_icon'], [ 'aria-hidden' => 'true' ] );
                        } else {
                            echo '<i class="unicon-arrow-up-right"></i>';
                        }
                        ?>
                    </span>
                <?php elseif ( $settings['read_more_type'] === 'read_text' && !empty($settings['read_more_text']) ) : ?>
                    <span class="eel-read-more-text">
                        <?php echo esc_html( $settings['read_more_text'] ); ?>
                        <?php
                        if (
                            !empty($settings['read_more_text_icon_show']) &&
                            $settings['read_more_text_icon_show'] === 'yes'
                        ) {
                            echo '<span class="eel-read-more-text-icon">';
                            if (!empty($settings['read_more_text_icon']['value'])) {
                                \Elementor\Icons_Manager::render_icon( $settings['read_more_text_icon'], [ 'aria-hidden' => 'true' ] );
                            } else {
                                echo '<i class="unicon-arrow-up-right"></i>';
                            }
                            echo '</span>';
                        }
                        ?>
                    </span>
                <?php elseif ( $settings['read_more_type'] === 'read_icon_to_text' && !empty($settings['read_more_text']) ) : ?>
                    <span class="eel-read-more-text eel-icon-to-text">
                        <span class="eel-text"><?php echo esc_html( $settings['read_more_text'] ); ?></span>
                        <?php
                        if (
                            !empty($settings['read_more_text_icon_show']) &&
                            $settings['read_more_text_icon_show'] === 'yes'
                        ) {
                            echo '<span class="eel-read-more-text-icon">';
                            if (!empty($settings['read_more_text_icon']['value'])) {
                                \Elementor\Icons_Manager::render_icon( $settings['read_more_text_icon'], [ 'aria-hidden' => 'true' ] );
                            } else {
                                echo '<i class="unicon-add"></i>';
                            }
                            echo '</span>';
                        }
                        ?>
                    </span>
                <?php endif; ?>
                <?php if ( $link ) : ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ( $settings['icon_direction'] === 'left' || $settings['icon_direction'] === 'right' ) : ?>
            </div>
        <?php endif; ?>
    </div>
