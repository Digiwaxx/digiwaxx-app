/**
 * ACE Admin Extra JavaScript
 * Additional functionality for admin dashboard
 */

(function($) {
    'use strict';

    // Sidebar toggle
    $(document).on('click', '.sidebar-toggle, .menu-toggler', function(e) {
        e.preventDefault();
        $('.sidebar').toggleClass('show');
        $('body').toggleClass('sidebar-open');
    });

    // Submenu toggle
    $(document).on('click', '.sidebar-menu > li.has-submenu > a', function(e) {
        e.preventDefault();
        var $parent = $(this).parent();

        // Close other submenus
        $parent.siblings('.has-submenu').find('.submenu').slideUp(200).removeClass('open');
        $parent.siblings('.has-submenu').removeClass('open');

        // Toggle current submenu
        $parent.toggleClass('open');
        $parent.find('> .submenu').slideToggle(200);
    });

    // Dropdown hover for desktop
    if ($(window).width() > 991) {
        $(document).on('mouseenter', '.dropdown', function() {
            $(this).find('.dropdown-menu').stop(true, true).fadeIn(200);
        }).on('mouseleave', '.dropdown', function() {
            $(this).find('.dropdown-menu').stop(true, true).fadeOut(200);
        });
    }

    // Widget collapse
    $(document).on('click', '.widget-header .widget-toolbar [data-action="collapse"]', function(e) {
        e.preventDefault();
        var $widget = $(this).closest('.widget-box');
        var $body = $widget.find('.widget-body');

        $body.slideToggle(200);
        $(this).find('i').toggleClass('fa-chevron-up fa-chevron-down');
    });

    // Widget close
    $(document).on('click', '.widget-header .widget-toolbar [data-action="close"]', function(e) {
        e.preventDefault();
        $(this).closest('.widget-box').fadeOut(200, function() {
            $(this).remove();
        });
    });

    // Tab persistence with localStorage
    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function(e) {
        var id = $(this).attr('href');
        var tabGroup = $(this).closest('.nav-tabs').data('tab-group');
        if (tabGroup && id) {
            localStorage.setItem('ace-tab-' + tabGroup, id);
        }
    });

    // Restore active tabs
    $('.nav-tabs[data-tab-group]').each(function() {
        var tabGroup = $(this).data('tab-group');
        var activeTab = localStorage.getItem('ace-tab-' + tabGroup);
        if (activeTab) {
            $(this).find('a[href="' + activeTab + '"]').tab('show');
        }
    });

    // Table row selection
    $(document).on('change', '.table .select-all', function() {
        var checked = $(this).prop('checked');
        $(this).closest('table').find('.select-row').prop('checked', checked);
        updateBulkActions();
    });

    $(document).on('change', '.table .select-row', function() {
        var $table = $(this).closest('table');
        var total = $table.find('.select-row').length;
        var checked = $table.find('.select-row:checked').length;

        $table.find('.select-all').prop('checked', total === checked);
        updateBulkActions();
    });

    function updateBulkActions() {
        var checked = $('.table .select-row:checked').length;
        if (checked > 0) {
            $('.bulk-actions').addClass('show');
            $('.bulk-count').text(checked);
        } else {
            $('.bulk-actions').removeClass('show');
        }
    }

    // Form dirty check
    var formOriginalData = {};

    $('form[data-dirty-check]').each(function() {
        var formId = $(this).attr('id') || Math.random().toString(36).substr(2, 9);
        formOriginalData[formId] = $(this).serialize();
        $(this).data('form-id', formId);
    });

    $(window).on('beforeunload', function() {
        var dirty = false;
        $('form[data-dirty-check]').each(function() {
            var formId = $(this).data('form-id');
            if ($(this).serialize() !== formOriginalData[formId]) {
                dirty = true;
                return false;
            }
        });
        if (dirty) {
            return 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    // Auto-save drafts
    var autoSaveTimers = {};

    $('[data-autosave]').each(function() {
        var $form = $(this);
        var endpoint = $form.data('autosave');
        var interval = $form.data('autosave-interval') || 30000;

        $form.on('input change', ':input', function() {
            var formId = $form.attr('id');

            clearTimeout(autoSaveTimers[formId]);
            autoSaveTimers[formId] = setTimeout(function() {
                $.ajax({
                    url: endpoint,
                    method: 'POST',
                    data: $form.serialize(),
                    success: function() {
                        showNotification('Draft saved', 'success');
                    }
                });
            }, interval);
        });
    });

    // Toast notifications
    window.showNotification = function(message, type) {
        type = type || 'info';
        var $toast = $('<div class="ace-toast ace-toast-' + type + '">' + message + '</div>');

        if (!$('.ace-toast-container').length) {
            $('body').append('<div class="ace-toast-container"></div>');
        }

        $('.ace-toast-container').append($toast);

        setTimeout(function() {
            $toast.addClass('show');
        }, 10);

        setTimeout(function() {
            $toast.removeClass('show');
            setTimeout(function() {
                $toast.remove();
            }, 300);
        }, 3000);
    };

    // Date/time formatting helper
    window.formatDateTime = function(dateString) {
        var date = new Date(dateString);
        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    // Initialize on document ready
    $(document).ready(function() {
        // Set active menu item based on URL
        var currentPath = window.location.pathname;
        $('.sidebar-menu a').each(function() {
            var href = $(this).attr('href');
            if (href && currentPath.indexOf(href) !== -1) {
                $(this).parent('li').addClass('active');
                $(this).closest('.submenu').addClass('open').show();
                $(this).closest('.has-submenu').addClass('active open');
            }
        });

        // Initialize tooltips
        if (typeof $.fn.tooltip === 'function') {
            $('[data-toggle="tooltip"]').tooltip();
        }

        // Initialize popovers
        if (typeof $.fn.popover === 'function') {
            $('[data-toggle="popover"]').popover();
        }
    });

})(jQuery);

// Add CSS for toast notifications
(function() {
    var style = document.createElement('style');
    style.textContent = [
        '.ace-toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; }',
        '.ace-toast { padding: 12px 20px; margin-bottom: 10px; border-radius: 4px; color: #fff; opacity: 0; transform: translateX(100%); transition: all 0.3s; }',
        '.ace-toast.show { opacity: 1; transform: translateX(0); }',
        '.ace-toast-success { background: #28a745; }',
        '.ace-toast-error { background: #dc3545; }',
        '.ace-toast-warning { background: #ffc107; color: #000; }',
        '.ace-toast-info { background: #17a2b8; }'
    ].join('\n');
    document.head.appendChild(style);
})();
