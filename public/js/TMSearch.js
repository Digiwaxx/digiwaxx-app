/**
 * Digiwaxx Track Management Search
 * Handles track search and filtering functionality
 */

(function($) {
    'use strict';

    var TMSearch = {
        init: function() {
            this.bindEvents();
            this.initAutocomplete();
        },

        bindEvents: function() {
            var self = this;

            // Search form submission
            $(document).on('submit', '.tm-search form, .search-form', function(e) {
                e.preventDefault();
                self.performSearch($(this));
            });

            // Search input keyup with debounce
            var searchTimeout;
            $(document).on('keyup', '.search-input, .tm-search-input', function() {
                var $input = $(this);
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    if ($input.val().length >= 2) {
                        self.showSuggestions($input);
                    } else {
                        self.hideSuggestions();
                    }
                }, 300);
            });

            // Clear search
            $(document).on('click', '.search-clear', function() {
                var $container = $(this).closest('.search-container, .tm-search');
                $container.find('input[type="text"]').val('').focus();
                self.hideSuggestions();
                self.clearResults();
            });

            // Filter tag click
            $(document).on('click', '.filter-tag:not(.active)', function() {
                $(this).addClass('active').siblings().removeClass('active');
                self.applyFilter($(this).data('filter'));
            });

            // Remove filter tag
            $(document).on('click', '.filter-tag .remove', function(e) {
                e.stopPropagation();
                $(this).parent().removeClass('active');
                self.applyFilter('all');
            });

            // Search result click
            $(document).on('click', '.search-results .result-item', function() {
                var url = $(this).data('url');
                if (url) {
                    window.location.href = url;
                }
            });

            // Search history item click
            $(document).on('click', '.search-history li', function() {
                var query = $(this).text().trim();
                $('.search-input').val(query);
                self.performSearch($('.search-form'));
            });

            // Clear search history
            $(document).on('click', '.clear-history', function() {
                self.clearHistory();
            });
        },

        initAutocomplete: function() {
            if (typeof $.fn.autocomplete === 'function') {
                $('.tm-search-input, .track-search-input').autocomplete({
                    source: function(request, response) {
                        // Make AJAX request for suggestions
                        $.ajax({
                            url: '/api/search/suggestions',
                            data: { term: request.term },
                            success: function(data) {
                                response(data);
                            },
                            error: function() {
                                response([]);
                            }
                        });
                    },
                    minLength: 2,
                    select: function(event, ui) {
                        $(this).val(ui.item.value);
                        $(this).closest('form').submit();
                    }
                });
            }
        },

        performSearch: function($form) {
            var self = this;
            var query = $form.find('input[type="text"]').val();
            var filter = $form.find('[name="filter"]').val() || 'all';

            if (!query || query.length < 2) {
                return;
            }

            // Save to history
            this.saveToHistory(query);

            // Show loading state
            this.showLoading();

            // Make AJAX request
            $.ajax({
                url: $form.attr('action') || '/search',
                method: 'GET',
                data: {
                    q: query,
                    filter: filter
                },
                success: function(response) {
                    self.displayResults(response);
                },
                error: function() {
                    self.showError('Search failed. Please try again.');
                },
                complete: function() {
                    self.hideLoading();
                }
            });
        },

        showSuggestions: function($input) {
            var self = this;
            var query = $input.val();
            var $container = $input.closest('.search-container, .tm-search');
            var $results = $container.find('.search-results');

            if (!$results.length) {
                $results = $('<div class="search-results"></div>');
                $container.append($results);
            }

            // Make AJAX request for suggestions
            $.ajax({
                url: '/api/search/suggestions',
                data: { q: query },
                success: function(data) {
                    if (data && data.length > 0) {
                        var html = '';
                        $.each(data, function(index, item) {
                            html += '<div class="result-item" data-url="' + item.url + '">';
                            if (item.image) {
                                html += '<img src="' + item.image + '" class="result-image" alt="">';
                            }
                            html += '<div class="result-info">';
                            html += '<div class="result-title">' + item.title + '</div>';
                            if (item.meta) {
                                html += '<div class="result-meta">' + item.meta + '</div>';
                            }
                            html += '</div>';
                            if (item.type) {
                                html += '<span class="result-type">' + item.type + '</span>';
                            }
                            html += '</div>';
                        });
                        $results.html(html).addClass('show');
                    } else {
                        self.hideSuggestions();
                    }
                }
            });
        },

        hideSuggestions: function() {
            $('.search-results').removeClass('show').empty();
        },

        displayResults: function(response) {
            // This can be customized based on the response structure
            if (response.html) {
                $('.search-results-container').html(response.html);
            } else if (response.data) {
                // Handle JSON response
                var html = this.renderResults(response.data);
                $('.search-results-container').html(html);
            }
        },

        renderResults: function(results) {
            if (!results || results.length === 0) {
                return '<div class="search-no-results">' +
                    '<i class="fa fa-search"></i>' +
                    '<p>No results found</p>' +
                    '</div>';
            }

            var html = '<div class="search-results-list">';
            $.each(results, function(index, item) {
                html += '<div class="track-row">';
                html += '<div class="play-btn"><i class="fa fa-play"></i></div>';
                if (item.image) {
                    html += '<img src="' + item.image + '" class="track-thumb" alt="">';
                }
                html += '<div class="track-details">';
                html += '<div class="track-name">' + item.title + '</div>';
                html += '<div class="track-meta">' + item.artist + '</div>';
                html += '</div>';
                html += '<div class="track-actions">';
                html += '<a href="' + item.url + '" class="btn btn-theme btn-sm">View</a>';
                html += '</div>';
                html += '</div>';
            });
            html += '</div>';

            return html;
        },

        showLoading: function() {
            if (!$('.search-loading').length) {
                $('.search-results-container').html(
                    '<div class="search-loading">' +
                    '<i class="fa fa-spinner fa-spin"></i>' +
                    '<p>Searching...</p>' +
                    '</div>'
                );
            }
        },

        hideLoading: function() {
            $('.search-loading').remove();
        },

        showError: function(message) {
            $('.search-results-container').html(
                '<div class="alert alert-danger">' + message + '</div>'
            );
        },

        clearResults: function() {
            $('.search-results-container').empty();
        },

        applyFilter: function(filter) {
            var $form = $('.search-form, .tm-search form');
            if ($form.length) {
                $form.find('[name="filter"]').val(filter);
                if ($form.find('input[type="text"]').val().length >= 2) {
                    this.performSearch($form);
                }
            }
        },

        saveToHistory: function(query) {
            var history = this.getHistory();
            // Remove if already exists
            var index = history.indexOf(query);
            if (index > -1) {
                history.splice(index, 1);
            }
            // Add to beginning
            history.unshift(query);
            // Keep only last 10
            history = history.slice(0, 10);
            localStorage.setItem('searchHistory', JSON.stringify(history));
        },

        getHistory: function() {
            try {
                return JSON.parse(localStorage.getItem('searchHistory')) || [];
            } catch (e) {
                return [];
            }
        },

        clearHistory: function() {
            localStorage.removeItem('searchHistory');
            $('.search-history ul').empty();
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        TMSearch.init();
    });

    // Expose to global scope
    window.TMSearch = TMSearch;

})(jQuery);
