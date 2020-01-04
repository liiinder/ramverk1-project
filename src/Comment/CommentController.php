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



    // /**
    //  * The initialize method is optional and will always be called before the
    //  * target method/action. This is a convienient method where you could
    //  * setup internal properties that are commonly used by several methods.
    //  *
    //  * @return void
    //  */
    // public function initialize() : void
    // {
    //     ;
    // }



    // /**
    //  * Show all items.
    //  *
    //  * @return object as a response object
    //  */
    // public function indexActionGet() : object
    // {
    //     $page = $this->di->get("page");
    //     $post = new Post();
    //     $post->setDb($this->di->get("dbqb"));
    //     $data = [
    //         "items" => $post->findAllJoin("user", "post.userId = user.id"),
    //         "user" => $this->di->get("session")->get("username")
    //     ];

    //     $page->add("post/crud/view-all", $data);

    //     return $page->render([
    //         "title" => "A collection of items",
    //     ]);
    // }



    /**
     * Handler with form to create a new item.
     *
     * @return object as a response object
     */
    public function createAction(int $id) : object
    {
        if ($this->di->get("session")->has("username") == false) {
            $this->di->get("response")->redirect("user/login");
        }
        $page = $this->di->get("page");
        $form = new CreateForm($this->di, $id);
        $form->check();

        $post = new Post();
        $post->setDb($this->di->get("dbqb"));

        $page->add("post/crud/view-post", [
            "post" => $post->findWhereJoin("post.id", $id, "user", "user.id = post.userId")
        ]);

        $page->add("post/crud/create", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Create a item",
        ]);
    }



    /**
     * Handler with form to delete an item.
     *
     * @return object as a response object
     */
    public function deleteAction() : object
    {
        $page = $this->di->get("page");
        $form = new DeleteForm($this->di);
        $form->check();

        $page->add("post/crud/delete", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Delete an item",
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
        $username = $this->di->get("session")->get("username");
        if (!$username) {
            $this->di->get("response")->redirect("user/login");
        }
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("username", $username);
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $post->find("id", $id);
        if ($post->userId != $user->id) {
            $this->di->get("response")->redirect("user/login");
        }

        $page = $this->di->get("page");
        $form = new UpdateForm($this->di, $id);
        $form->check();

        $page->add("post/crud/update", [
            "form" => $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Update an item",
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
        $post->findWhereJoin(
            "post.id = ?",
            $id,
            "user",
            "post.userId = user.id"
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
