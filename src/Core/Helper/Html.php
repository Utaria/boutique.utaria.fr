<?php
namespace core\Helper;

class Html {

    public function css($file) {
        $out  = filter_var($file, FILTER_VALIDATE_URL);
        $href = ($out) ? $file : BASE_URL . '/public/css/' . $file;

        if (!$out && substr($href, -4) != '.css')
            $href = $href . ".css";

        return '<link rel="stylesheet" type="text/css" href="' . $href . '">
';
    }

    public function js($file) {
        if (substr($file, -3) != '.js')
            $file = $file . ".js";

        return '<script type="text/javascript" src="' . BASE_URL . '/public/js/' . $file . '"></script>
';
    }

    public function srcImg($file) {
        return BASE_URL . "/public/img/$file";
    }

    public function img($file) {
        return '<img src="' . $this->srcImg($file) . '">
';
    }

    public function href($link) {
        $out  = filter_var($link, FILTER_VALIDATE_URL);
        $href = ($out) ? $link : \App::getInstance()->getBaseURI() . $link;

        return $href;
    }

    public function link($link, $content) {
        return '<a href="' . $this->href($link) . '">' . $content . '</a>
';
    }

    public function getCurrentURL() {
        return (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
    
}