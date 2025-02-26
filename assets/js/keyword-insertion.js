/**
 * Keyword Insertion for WordPress
 *
 * Handles keyword insertion from URL parameters with Cornerstone Editor compatibility
 * Version: 1.0.0
 */

(function ($) {
  'use strict';

  /**
   * Check if we're in the Cornerstone Editor or another builder environment
   * @return {boolean} True if in editor environment
   */
  const isInEditor = function () {
    return (
      typeof window.csModernizr !== 'undefined' ||
      document.body.classList.contains('cornerstone-ui') ||
      window.location.href.indexOf('/cornerstone/edit/') > -1 ||
      document.body.classList.contains('elementor-editor-active') ||
      document.body.classList.contains('fl-builder-edit') ||
      document.body.classList.contains('et-fb') ||
      document.body.classList.contains('block-editor-page')
    );
  };

  // Only run if not in editor
  if (!isInEditor()) {
    $(function () {
      // Get plugin options
      const paramName = typeof keyinsOptions !== 'undefined' ? keyinsOptions.paramName : 'k';
      const maxLength = typeof keyinsOptions !== 'undefined' ? keyinsOptions.maxLength : 100;

      /**
       * Get URL parameter value
       * @param {string} name - Parameter name
       * @return {string} Parameter value or empty string
       */
      function getUrlParameter(name) {
        if (typeof name !== 'string') return '';

        try {
          name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
          const regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
          const results = regex.exec(location.search);
          return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
        } catch (e) {
          console.error('Keyword Insertion: Error in getUrlParameter:', e);
          return '';
        }
      }

      /**
       * Sanitize HTML to prevent XSS
       * @param {string} str - String to sanitize
       * @return {string} Sanitized string
       */
      function sanitizeHTML(str) {
        if (typeof str !== 'string') return '';

        const escape = {
          '&': '&amp;',
          '<': '&lt;',
          '>': '&gt;',
          '"': '&quot;',
          "'": '&#39;',
          '/': '&#x2F;',
        };

        return str.replace(/[&<>"'/]/g, function (match) {
          return escape[match];
        });
      }

      // Process keywords
      const keyword = getUrlParameter(paramName);

      if (keyword && keyword.trim() && keyword.length <= maxLength) {
        try {
          document.querySelectorAll('.keyword-insert').forEach(function (el) {
            if (el instanceof HTMLElement) {
              el.innerHTML = sanitizeHTML(keyword.trim());

              // Dispatch a custom event for integrations
              const event = new CustomEvent('keyinsUpdated', {
                detail: {
                  element: el,
                  keyword: keyword.trim(),
                },
              });
              document.dispatchEvent(event);
            }
          });

          // Console log for debugging (will be removed in production)
          if (window.location.hash === '#keyins-debug') {
            console.log('Keyword Insertion: Replaced content with "' + keyword.trim() + '"');
          }
        } catch (e) {
          console.error('Keyword Insertion: Error updating keywords:', e);
        }
      }
    });
  }
})(jQuery);
