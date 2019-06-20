<?php

class WebContentHelper {

    function get_data($url) {
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_CAINFO, "GlobalSignRootCA.crt");
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    function get_words($returned_content) {
        $start = stripos($returned_content, "<body");
        $end = stripos($returned_content, "</body");
        $body = strip_tags(substr($returned_content,$start,$end-$start));
        $no_tags = preg_replace('/[^A-Za-z]/', ' ', $body);
        $clean = preg_replace('!\s+!', ' ', $no_tags);
        return $clean;
    }
}