<div id="wpfy-inquiry-popup" class="wpfy-popup" style="display: none;">
    <div class="wpfy-popup-content">
        <button class="wpfy-close-popup" onclick="closeInquiryPopup()">&times;</button>


        <?php
        // Get the current product title
        $product_title = get_the_title();
        ?>

        <h4><?php esc_html_e('Submit your inquiry for', 'wpfypi'); ?> <span class="popup-item"><?php echo esc_html($product_title); ?></span></h4>



        <form id="wpfy-inquiry-form">
            <label for="wpfy-inquiry-name"><?php esc_html_e('Name:', 'wpfypi'); ?></label>
            <input type="text" name="wpfy-inquiry-name" id="wpfy-inquiry-name" required><br>

            <label for="wpfy-inquiry-email"><?php esc_html_e('Email:', 'wpfypi'); ?></label>
            <input type="email" name="wpfy-inquiry-email" id="wpfy-inquiry-email" required><br>

            <label for="wpfy-inquiry-message"><?php esc_html_e('Message:', 'wpfypi'); ?></label>
            <textarea name="wpfy-inquiry-message" id="wpfy-inquiry-message" rows="4" required></textarea><br>

            <!-- Div to show form submission confirmation -->
            <div id="wpfy-inquiry-success" style="display: none;" class="wpfy-inquiry-success"></div>

            <button type="submit"><?php esc_html_e('Submit Inquiry', 'wpfypi'); ?></button>
        </form>
    </div>
</div>

