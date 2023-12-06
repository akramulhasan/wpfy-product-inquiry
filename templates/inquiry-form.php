<div id="wpfy-inquiry-popup" class="wpfy-popup" style="display: none;">
    <div class="wpfy-popup-content">
        <button class="wpfy-close-popup" onclick="closeInquiryPopup()">&times;</button>
        <form id="wpfy-inquiry-form">
            <label for="wpfy-inquiry-name"><?php esc_html_e('Name:', 'wpfypi'); ?></label>
            <input type="text" name="wpfy-inquiry-name" id="wpfy-inquiry-name" required>

            <label for="wpfy-inquiry-email"><?php esc_html_e('Email:', 'wpfypi'); ?></label>
            <input type="email" name="wpfy-inquiry-email" id="wpfy-inquiry-email" required>

            <label for="wpfy-inquiry-message"><?php esc_html_e('Message:', 'wpfypi'); ?></label>
            <textarea name="wpfy-inquiry-message" id="wpfy-inquiry-message" rows="4" required></textarea>

            <button type="submit"><?php esc_html_e('Submit Inquiry', 'wpfypi'); ?></button>
        </form>
    </div>
</div>