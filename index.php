<?php
include_once('./_common.php');
define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit;

if (defined('G5_THEME_PATH')) {
    require_once(G5_THEME_PATH . '/index.php');
    return;
}
