<?php

namespace linder\User;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use linder\User\HTMLForm\UserLogin;
use linder\User\HTMLForm\CreateUser;
use linder\User\HTMLForm\EditUser;
use linder\Comment\Comment;
use linder\Post\Post;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 */
class UserController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function loginAction() : object
    {
        $page = $this->di->get("page");
        $form = new UserLogin($this->di);
        $form->check();

        $page->add("anax/v2/image/default", [
            "src" => "image/theme/tree.jpg?width=1100&height=150&crop-to-fit&area=0,0,30,0",
        ], "flash");

        $page->add("anax/v2/article/default", [
            "content" => "<h1>Logga in</h1>" . $form->getHTML(),
        ]);

        return $page->render([
            "title" => "A login page",
        ]);
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function createAction() : object
    {
        $page = $this->di->get("page");
        $form = new CreateUser($this->di);
        $form->check();

        $page->add("anax/v2/image/default", [
            "src" => "image/theme/tree.jpg?width=1100&height=150&crop-to-fit&area=0,0,30,0",
        ], "flash");

        $page->add("anax/v2/article/default", [
            "content" => "<h1>Registrera nytt konto</h1>" . $form->getHTML(),
        ]);

        return $page->render([
            "title" => "A create user page",
        ]);
    }

    /**
     * Description.
     *
     * @param datatype $variable Description
     *
     * @throws Exception
     *
     * @return object as a response object
     */
    public function editAction(int $id) : object
    {
        $page = $this->di->get("page");
        $userId = $this->di->get("session")->get("userId");
        if ($userId == $id) {
            $form = new EditUser($this->di, $id);
        } else {
            $form = new UserLogin($this->di);
        }
        $form->check();

        $page->add("anax/v2/image/default", [
            "src" => "image/theme/tree.jpg?width=1100&height=150&crop-to-fit&area=0,0,30,0",
        ], "flash");

        $page->add("anax/v2/article/default", [
            "content" => "<h1>Redigera konto</h1>" . $form->getHTML(),
        ]);

        return $page->render([
            "title" => "Edit a user",
        ]);
    }

    /**
     * @param int $userId
     * 
     * @return object as a response object
     */
    public function profileAction(int $id) : object
    {
        $page = $this->di->get("page");
        $comment = new Comment();
        $comment->setDb($this->di->get("dbqb"));
        $comments = $comment->findAllWhereJoin("comment.userId = ?", $id, "post", "post.postId = comment.postId");
        $post = new Post();
        $post->setDb($this->di->get("dbqb"));
        $posts = $post->findAllWhere("post.userId", $id);
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("userId", $id);
        if ($user->userId == null) {
            $this->di->get("response")->redirect("post");
        }
        $active = ($this->di->get("session")->get("userId") == $user->userId);

        $data = [
            "posts" => $posts,
            "comments" => $comments,
            "user" => $user,
            "active" => $active
        ];

        $page->add("anax/v2/image/default", [
            "src" => "image/theme/tree.jpg?width=1100&height=150&crop-to-fit&area=0,0,30,0",
        ], "flash");

        $page->add("user/profile", $data);
        return $page->render([
            "title" => "User profile"
        ]);
    }

    /**
     * Logout route
     */
    public function logoutAction() : object
    {
        $this->di->get("session")->delete("userId");
        $this->di->get("response")->redirect("user/login");
    }
}
