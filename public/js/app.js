/**
 * Digiwaxx Main Application JavaScript
 */

(function($) {
    'use strict';

    var DigiwaxxApp = {
        init: function() {
            this.bindEvents();
            this.initComponents();
        },

        bindEvents: function() {
            var self = this;

            // Form validation feedback
            $(document).on('submit', 'form', function(e) {
                var $form = $(this);
                if ($form.find('.is-invalid').length > 0) {
                    e.preventDefault();
                    self.scrollToFirstError($form);
                }
            });

            // Password visibility toggle
            $(document).on('click', '.field-icon, .toggle-password', function() {
                var $input = $(this).siblings('input, .form-control');
                var type = $input.attr('type') === 'password' ? 'text' : 'password';
                $input.attr('type', type);
                $(this).find('i').toggleClass('fa-eye fa-eye-slash');
            });

            // AJAX form submission
            $(document).on('submit', '.ajax-form', function(e) {
                e.preventDefault();
                self.submitAjaxForm($(this));
            });

            // Confirm dialogs
            $(document).on('click', '[data-confirm]', function(e) {
                var message = $(this).data('confirm') || 'Are you sure?';
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });

            // Image preview on file input change
            $(document).on('change', 'input[type="file"][data-preview]', function() {
                self.previewImage(this);
            });

            // Track play button
            $(document).on('click', '.play-btn, .track-play', function(e) {
                e.preventDefault();
                var trackUrl = $(this).data('track') || $(this).closest('[data-track]').data('track');
                if (trackUrl) {
                    self.playTrack(trackUrl, $(this));
                }
            });

            // Modal close on backdrop click
            $(document).on('click', '.modal', function(e) {
                if ($(e.target).hasClass('modal')) {
                    $(this).modal('hide');
                }
            });

            // Copy to clipboard
            $(document).on('click', '[data-copy]', function() {
                var text = $(this).data('copy') || $(this).text();
                self.copyToClipboard(text, $(this));
            });

            // Dismiss alerts
            $(document).on('click', '.alert .close, .alert-dismiss', function() {
                $(this).closest('.alert').fadeOut(300, function() {
                    $(this).remove();
                });
            });

            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert.auto-dismiss').fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
        },

        initComponents: function() {
            // Initialize selectpicker if present
            if (typeof $.fn.selectpicker === 'function') {
                $('.selectpicker').selectpicker({
                    style: 'btn-default',
                    size: 10
                });
            }

            // Initialize datepickers if jQuery UI is loaded
            if (typeof $.fn.datepicker === 'function') {
                $('.datepicker').datepicker({
                    dateFormat: 'yy-mm-dd',
                    changeMonth: true,
                    changeYear: true
                });
            }

            // Initialize tooltips
            if (typeof $.fn.tooltip === 'function') {
                $('[data-toggle="tooltip"], [title]').tooltip();
            }

            // Initialize lazy loading for images
            this.initLazyLoading();

            // Initialize form validation
            this.initValidation();

            // Show processing loader on form submit
            $(document).on('submit', 'form:not(.ajax-form)', function() {
                if (!$(this).data('no-loader')) {
                    $('.processing_loader_gif').css('display', 'flex');
                }
            });
        },

        initLazyLoading: function() {
            // Simple lazy loading for images
            var lazyImages = document.querySelectorAll('img[data-src]');

            if ('IntersectionObserver' in window) {
                var imageObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var image = entry.target;
                            image.src = image.dataset.src;
                            image.classList.remove('lazy');
                            imageObserver.unobserve(image);
                        }
                    });
                });

                lazyImages.forEach(function(image) {
                    imageObserver.observe(image);
                });
            } else {
                // Fallback for browsers without IntersectionObserver
                lazyImages.forEach(function(image) {
                    image.src = image.dataset.src;
                });
            }
        },

        initValidation: function() {
            if (typeof $.fn.validate === 'function') {
                $.validator.setDefaults({
                    errorElement: 'span',
                    errorClass: 'invalid-feedback',
                    highlight: function(element) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element) {
                        $(element).removeClass('is-invalid');
                    },
                    errorPlacement: function(error, element) {
                        if (element.parent('.input-group').length) {
                            error.insertAfter(element.parent());
                        } else {
                            error.insertAfter(element);
                        }
                    }
                });

                // Validate forms with class 'needs-validation'
                $('form.needs-validation').each(function() {
                    $(this).validate();
                });
            }
        },

        submitAjaxForm: function($form) {
            var self = this;
            var $submitBtn = $form.find('[type="submit"]');
            var originalText = $submitBtn.text();

            // Disable submit button
            $submitBtn.prop('disabled', true).text('Processing...');

            $.ajax({
                url: $form.attr('action'),
                method: $form.attr('method') || 'POST',
                data: new FormData($form[0]),
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        self.showNotification('success', response.message || 'Success!');
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    } else {
                        self.showNotification('error', response.message || 'An error occurred.');
                    }
                },
                error: function(xhr) {
                    var message = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    self.showNotification('error', message);
                },
                complete: function() {
                    $submitBtn.prop('disabled', false).text(originalText);
                }
            });
        },

        previewImage: function(input) {
            var previewSelector = $(input).data('preview');
            var $preview = $(previewSelector);

            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $preview.attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        },

        playTrack: function(url, $button) {
            // Check if jPlayer is initialized
            if ($('#jquery_jplayer_1').length && typeof $.fn.jPlayer === 'function') {
                $('#jquery_jplayer_1').jPlayer('setMedia', {
                    mp3: url
                }).jPlayer('play');

                // Update UI
                $('.play-btn, .track-play').removeClass('playing');
                $button.addClass('playing');
            } else {
                // Fallback to HTML5 audio
                if (!window.audioPlayer) {
                    window.audioPlayer = new Audio();
                }
                window.audioPlayer.src = url;
                window.audioPlayer.play();
            }
        },

        copyToClipboard: function(text, $element) {
            var self = this;
            navigator.clipboard.writeText(text).then(function() {
                self.showNotification('success', 'Copied to clipboard!');
                if ($element) {
                    var originalText = $element.text();
                    $element.text('Copied!');
                    setTimeout(function() {
                        $element.text(originalText);
                    }, 2000);
                }
            }).catch(function() {
                // Fallback for older browsers
                var $temp = $('<input>');
                $('body').append($temp);
                $temp.val(text).select();
                document.execCommand('copy');
                $temp.remove();
                self.showNotification('success', 'Copied to clipboard!');
            });
        },

        showNotification: function(type, message) {
            var alertClass = type === 'success' ? 'alert-success' :
                            type === 'error' ? 'alert-danger' :
                            type === 'warning' ? 'alert-warning' : 'alert-info';

            var $alert = $('<div class="alert ' + alertClass + ' auto-dismiss">' +
                '<button type="button" class="close alert-dismiss">&times;</button>' +
                message +
                '</div>');

            // Find or create notification container
            var $container = $('.notification-container');
            if (!$container.length) {
                $container = $('<div class="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 99999; max-width: 350px;"></div>');
                $('body').append($container);
            }

            $container.prepend($alert);

            // Auto dismiss
            setTimeout(function() {
                $alert.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);
        },

        scrollToFirstError: function($form) {
            var $firstError = $form.find('.is-invalid').first();
            if ($firstError.length) {
                $('html, body').animate({
                    scrollTop: $firstError.offset().top - 100
                }, 300);
                $firstError.focus();
            }
        },

        // Utility: Format number with commas
        formatNumber: function(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        },

        // Utility: Format file size
        formatFileSize: function(bytes) {
            if (bytes === 0) return '0 Bytes';
            var k = 1024;
            var sizes = ['Bytes', 'KB', 'MB', 'GB'];
            var i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        // Utility: Format duration (seconds to MM:SS)
        formatDuration: function(seconds) {
            var mins = Math.floor(seconds / 60);
            var secs = Math.floor(seconds % 60);
            return mins + ':' + (secs < 10 ? '0' : '') + secs;
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        DigiwaxxApp.init();
    });

    // Expose to global scope
    window.DigiwaxxApp = DigiwaxxApp;

    // Close campaign modal function (used in templates)
    window.close_campaign_modal = function() {
        $('#alertModal_campaign').modal('hide');
    };

})(jQuery);
