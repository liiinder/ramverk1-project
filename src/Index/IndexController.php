<?php

namespace linder\Index;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;


/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $di if implementing the interface
 * ContainerInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class IndexController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;


    /**
     * This is the index method action, it handles:
     *
     * @return object
     */
    public function indexAction() : object
    {
        $this->di->get("response")->redirect("tag");
    }

    /**
     * Render a page using flat file content.
     *
     * @param array $args as a variadic to catch all arguments.
     *
     * @return mixed as null when flat file is not found and otherwise a
     *               complete response object with content to render.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function catchAll(...$args)
    {
        // Get the current route and see if it matches a content/file
        $path = $this->di->get("request")->getRoute();
        $file1 = ANAX_INSTALL_PATH . "/content/{$path}.md";
        $file2 = ANAX_INSTALL_PATH . "/content/{$path}/index.md";

        $file = is_file($file1) ? $file1 : null;
        $file = is_file($file2) ? $file2 : $file;
        
        if (!$file) {
            return;
        }

        // Check that file is really in the right place
        $real = realpath($file);
        $base = realpath(ANAX_INSTALL_PATH . "/content/");
        if (strncmp($base, $real, strlen($base))) {
            return;
        }

        // Get content from markdown file
        $content = file_get_contents($file);
        $content = $this->di->get("textfilter")->parse(
            $content,
            ["frontmatter", "variable", "shortcode", "markdown", "titlefromheader"]
        );

        // Add content as a view and then render the page
        $page = $this->di->get("page");
        $page->add("anax/v2/article/default", [
            "content" => $content->text,
            "frontmatter" => $content->frontmatter,
        ]);

        return $page->render($content->frontmatter);
    }

}
