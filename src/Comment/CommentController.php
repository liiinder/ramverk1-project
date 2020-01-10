<?php

namespace linder\Comment;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use linder\Comment\HTMLForm\CreateForm;
use linder\Comment\HTMLForm\EditForm;
use linder\Comment\HTMLForm\DeleteForm;
use linder\Comment\HTMLForm\UpdateForm;
use linder\User\User;
use linder\Post\Post;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class CommentController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var $data description
     */
    //private $data;

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

        $page->add("post/crud/view-post", [
            "post" => $post->findAllWhere("post.postId", $id)[0],
            "userId" => $userId
        ]);

        $page->add("post/crud/create", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Create a item",
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

        $page->add("post/crud/update", [
            "form" => $form->getHTML(),
            "type" => "kommentar"
        ]);

        return $page->render([
            "title" => "Redigera en kommentar",
        ]);
    }

    /**
     * Handler to show an post.
     *
     * @param int $id the id to view.
     *
     * @return object as a response object
     */
    public function viewAction(int $id) : object
    {
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $post->findAllWhereJoin(
            "post.postId = ?",
            $id,
            "user",
            "post.userId = user.userId"
        );

        $page = $this->di->get("page");
        $form = new UpdateForm($this->di, $id);
        $form->check();

        $data = [
            "post" => $post
        ];

        $page->add("post/crud/view-post", $data);

        return $page->render([
            "title" => "View post",
        ]);
    }
}
