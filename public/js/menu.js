/**
 * Digiwaxx Menu JavaScript
 * Handles mobile menu toggle and navigation
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Mobile menu toggle
        $('.menu-btn').on('click', function(e) {
            e.preventDefault();
            $('.menu-con').addClass('open');
            $('body').addClass('menu-open');
        });

        // Close menu
        $('.menu-close').on('click', function(e) {
            e.preventDefault();
            $('.menu-con').removeClass('open');
            $('body').removeClass('menu-open');
        });

        // Close menu when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.menu-con, .menu-btn').length) {
                $('.menu-con').removeClass('open');
                $('body').removeClass('menu-open');
            }
        });

        // Dropdown toggle for mobile
        $('.nav-item.dropdown > a').on('click', function(e) {
            if ($(window).width() < 992) {
                e.preventDefault();
                $(this).parent().toggleClass('show');
                $(this).next('.dropdown-menu').slideToggle(200);
            }
        });

        // Sidebar toggle for dashboard
        $('.sidebar-toggle').on('click', function(e) {
            e.preventDefault();
            $('.sidebar-left').toggleClass('show');
            $('.sidebar-overlay').toggleClass('show');
        });

        // Close sidebar when clicking overlay
        $('.sidebar-overlay').on('click', function() {
            $('.sidebar-left').removeClass('show');
            $(this).removeClass('show');
        });

        // Smooth scroll for anchor links
        $('a[href^="#"]').on('click', function(e) {
            var target = $(this.getAttribute('href'));
            if (target.length) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - 80
                }, 500);
            }
        });

        // Active menu item based on current URL
        var currentPath = window.location.pathname;
        $('.menu-con ul li a, .sidebar-left ul li a').each(function() {
            var linkPath = $(this).attr('href');
            if (linkPath && currentPath.indexOf(linkPath) !== -1) {
                $(this).parent('li').addClass('active');
            }
        });

        // Header scroll effect
        var lastScrollTop = 0;
        $(window).on('scroll', function() {
            var scrollTop = $(this).scrollTop();

            if (scrollTop > 100) {
                $('header').addClass('scrolled');
            } else {
                $('header').removeClass('scrolled');
            }

            // Hide/show header on scroll
            if (scrollTop > lastScrollTop && scrollTop > 200) {
                $('header').addClass('header-hidden');
            } else {
                $('header').removeClass('header-hidden');
            }
            lastScrollTop = scrollTop;
        });

        // Initialize tooltips if Bootstrap is loaded
        if (typeof $.fn.tooltip === 'function') {
            $('[data-toggle="tooltip"]').tooltip();
        }

        // Initialize popovers if Bootstrap is loaded
        if (typeof $.fn.popover === 'function') {
            $('[data-toggle="popover"]').popover();
        }
    });

})(jQuery);
