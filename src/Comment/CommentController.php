<?php

namespace linder\Comment;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use linder\Comment\HTMLForm\CreateForm;
use linder\Comment\HTMLForm\UpdateForm;
use linder\User\User;
use linder\Post\Post;
use \Michelf\MarkdownExtra;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class CommentController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public $flash;

    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize() : void
    {
        // Use to set the flash picture on all tag subpages
        $this->flash = "image/theme/sunset.jpg?width=1100&height=200&cf&area=65,0,0,0";
    }

    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction(int $id) : object
    {
        $userId = $this->di->get("session")->get("userId");
        if (!$userId) {
            $this->di->get("response")->redirect("user/login");
        }

        $page = $this->di->get("page");
        $form = new CreateForm($this->di, $id);
        $form->check();

        $post = new Post();
        $post->setDb($this->di->get("dbqb"));

        $page->add("anax/v2/image/default", [
            "src" => $this->flash,
        ], "flash");

        $page->add("post/crud/view-post", [
            "post" => $post->findAllWhere("post.postId", $id)[0],
            "userId" => $userId,
            "filter" => new MarkdownExtra()
        ]);

        $page->add("post/crud/create", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Kommentera",
        ]);
    }


    /**
     * Handler with form to update an item.
     *
     * @param int $id the id to update.
     *
     * @return object as a response object
     */
    public function updateAction(int $id) : object
    {
        $userId = $this->di->get("session")->get("userId");
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comment->find("commentId", $id);

        if (!$userId || ($comment->userId != $userId)) {
            $this->di->get("response")->redirect("user/login");
        }

        $page = $this->di->get("page");
        $form = new UpdateForm($this->di, $id);
        $form->check();

        $page->add("anax/v2/image/default", [
            "src" => $this->flash,
        ], "flash");

        $page->add("post/crud/update", [
            "form" => $form->getHTML(),
            "type" => "kommentar"
        ]);

        return $page->render([
            "title" => "Redigera en kommentar",
        ]);
    }

}
