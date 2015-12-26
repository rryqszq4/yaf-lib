<?php
/**
 * Created by PhpStorm.
 * User: zhaoquan
 * Date: 15-12-26
 * Time: 下午4:09
 */

class MarkdownController extends Controller{

    public $_layout = "markdown_layout";

    public function indexAction(){
        $md = isset($_GET['md']) ? trim($_GET['md']) : "php-yajl";

        $parser = new System_Parsedown();
        $markdown = file_get_contents(APPLICATION_PATH."/data/doc/{$md}.md");
        $html = $parser->parse($markdown);

        $this->getView()->assign("content",  $html);
        return TRUE;
    }

}