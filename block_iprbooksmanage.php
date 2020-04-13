<?php

class block_iprbooksmanage extends block_base
{
    public function init()
    {
        $this->title = get_string('iprbooksmanage', 'block_iprbooksmanage');
    }

    public function get_content()
    {
        global $CFG;
        if ($this->content !== null) {
            return $this->content;
        }

        $style = file_get_contents($CFG->dirroot . "/blocks/iprbooksmanage/style/iprbooksmanage.css");
        $js = file_get_contents($CFG->dirroot . "/blocks/iprbooksmanage/js/iprbooksmanage.js");
        $mainPage = file_get_contents($CFG->dirroot . "/blocks/iprbooksmanage/templates/rendermainpage.mustache");

        $this->content = new stdClass;
        $this->content->text .= "<style>" . $style . "</style>";
        $this->content->text .= "<script src=\"https://code.jquery.com/jquery-1.9.1.min.js\"></script>";
        $this->content->text .= $mainPage;
        $this->content->text .= "<script type=\"text/javascript\"> " . $js . " </script>";

        return $this->content;
    }

    public function hide_header()
    {
        return true;
    }

    function has_config()
    {
        return true;
    }

}
