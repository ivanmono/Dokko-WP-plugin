(function($) {
    'use strict';
    
    $(document).ready(function() {
        var mediaUploader;
        
        // Handle upload button clicks
        $(document).on('click', '.dokko-upload-button', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var field = $button.data('field');
            var title = $button.data('title') || 'Select Image';
            // Convert underscore field names to hyphenated IDs (e.g., dkw_header_bg_image -> dokko-chat-dkw-header-bg-image)
            var fieldId = field.replace(/_/g, '-');
            var $input = $('#dokko-chat-' + fieldId);
            
            if (!wp.media) {
                alert('Media upload not available');
                return;
            }
            
            mediaUploader = wp.media({
                title: title,
                button: {
                    text: 'Use this image'
                },
                multiple: false
            });
            
            mediaUploader.on('select', function() {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $input.val(attachment.url);
                
                // Show preview with bordered styling and X icon
                var $wrapper = $input.closest('.dokko-image-upload-wrapper');
                var previewHtml = '<div class="dokko-image-preview">' +
                    '<img src="' + attachment.url + '"/>' +
                    '<button type="button" class="dokko-remove-button" data-field="' + field + '" title="Remove image">✕</button>' +
                    '</div>';
                
                $wrapper.find('.dokko-image-preview').remove();
                $wrapper.append(previewHtml);
            });
            
            mediaUploader.open();
        });
        
        // Handle remove button clicks
        $(document).on('click', '.dokko-remove-button', function(e) {
            e.preventDefault();
            
            var $button = $(this);
            var field = $button.data('field');
            // Convert underscore field names to hyphenated IDs
            var fieldId = field.replace(/_/g, '-');
            var $input = $('#dokko-chat-' + fieldId);
            $input.val('');
            $button.closest('.dokko-image-preview').remove();
        });
    });
    
})(jQuery);
