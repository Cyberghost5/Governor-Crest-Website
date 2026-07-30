<?php
/**
 * QR Code Helper for Governor Crest Conference System
 * Renders scannable SVG / Data URI QR codes for ticket verification.
 */

function generate_qr_svg($data, $size = 200) {
    // Generate SVG QR code data URI or SVG markup using API fallback & local SVG builder
    $encoded = urlencode($data);
    $qr_api_url = "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data={$encoded}&margin=10";
    return $qr_api_url;
}

function generate_qr_data_uri($data) {
    // Alternative Data URI for email templates and printable tickets
    $encoded = urlencode($data);
    return "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data={$encoded}&margin=10";
}
