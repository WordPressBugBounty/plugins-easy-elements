<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


	
		

		// logo data
		$easyel_logo_data   = ! empty( $item['logo']['id'] ) ? wp_get_attachment_image_src( $item['logo']['id'], 'full' ) : '';
		$easyel_logo_alt    = ! empty( $item['logo']['id'] ) ? get_post_meta( $item['logo']['id'], '_wp_attachment_image_alt', true ) : '';
		$easyel_logo_title  = ! empty( $item['logo']['id'] ) ? get_the_title( $item['logo']['id'] ) : '';

		// image data
		$easyel_image_data  = ! empty( $item['image']['id'] ) ? wp_get_attachment_image_src( $item['image']['id'], 'full' ) : '';
		$easyel_alt         = ! empty( $item['image']['id'] ) ? get_post_meta( $item['image']['id'], '_wp_attachment_image_alt', true ) : '';
		$easyel_title       = ! empty( $item['image']['id'] ) ? get_the_title( $item['image']['id'] ) : '';
		?>
		
                    
                    <?php if ( ! empty( $easyel_logo_data ) ) : ?>
                        <div class="eel-company-logo">
                            <img src="<?php echo esc_url( $easyel_logo_data[0] ); ?>"
                                width="<?php echo esc_attr( $easyel_logo_data[1] ); ?>"
                                height="<?php echo esc_attr( $easyel_logo_data[2] ); ?>"
                                alt="<?php echo esc_attr( $easyel_logo_alt ); ?>"
                                title="<?php echo esc_attr( $easyel_logo_title ); ?>"
                                loading="lazy" decoding="async" fetchpriority="low">
                        </div>
                    <?php endif; ?>
        
                    <?php if ( ! empty( $item['description'] ) ) : ?>
                        <div class="eel-description"><?php echo esc_html( $item['description'] ); ?></div>
                    <?php endif; ?>
        
                    <div class="eel-author-wrap">
                        <?php if ( $settings['show_image'] === 'yes' && $easyel_image_data ) : ?>
                            <div class="eel-picture">
                                <img src="<?php echo esc_url( $easyel_image_data[0] ); ?>"
                                    width="<?php echo esc_attr( $easyel_image_data[1] ); ?>"
                                    height="<?php echo esc_attr( $easyel_image_data[2] ); ?>"
                                    alt="<?php echo esc_attr( $easyel_alt ); ?>"
                                    title="<?php echo esc_attr( $easyel_title ); ?>"
                                    loading="lazy" decoding="async" fetchpriority="low">
                            </div>
                        <?php endif; ?>
        
                        <div class="eel-author">
                            <?php if ( ! empty( $item['name'] ) ) : ?>
                                <div class="eel-name"><?php echo esc_html( $item['name'] ); ?></div>
                            <?php endif; ?>
        
                            <?php if ( ! empty( $item['designation'] ) ) : ?>
                                <em class="eel-designation"><?php echo esc_html( $item['designation'] ); ?></em>
                            <?php endif; ?>
                        </div>
        
                        <?php if ( ! empty( $item['rating'] ) ) : ?>
                            <div class="eel-rating" aria-label="Rating: <?php echo intval( $item['rating'] ); ?> out of 5">
                                <?php
                                $easyel_rating = intval( $item['rating'] );
                                for ( $easyel_j = 1; $easyel_j <= 5; $easyel_j++ ) {
                                    echo '<span class="star' . ( $easyel_j <= $easyel_rating ? ' filled' : '' ) . '">' . ( $easyel_j <= $easyel_rating ? '★' : '☆' ) . '</span>';
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                    </div>
            
	

    <?php if ( $i > 6 ) : ?>
       <div class="overlay"></div>
    <?php endif; ?>
</div>
