<?php
/**
 * HTML Escape Utility
 * Simple function to escape HTML entities
 */

if (!function_exists('htmlEscape')) {
    function htmlEscape($string) {
        if ($string === null) {
            return '';
        }
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}