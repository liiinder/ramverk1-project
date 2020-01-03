<?php

namespace linder\USer\HTMLForm;

use Anax\HTMLForm\FormModel;
use linder\User\User;
use Psr\Container\ContainerInterface;

/**
 * Example of FormModel implementation.
 */
class UserLogin extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di)
    {
        parent::__construct($di);

        $this->form->create(
            [
                "id" => __CLASS__,
                "legend" => "User Login"
            ],
            [
                "user" => [
                    "type"        => "text",
                    //"description" => "Here you can place a description.",
                    //"placeholder" => "Here is a placeholder",
                ],
                        
                "password" => [
                    "type"        => "password",
                    //"description" => "Here you can place a description.",
                    //"placeholder" => "Here is a placeholder",
                ],

                "submit" => [
                    "type" => "submit",
                    "value" => "Login",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "create" => [
                    "type" => "submit",
                    "value" => "Register",
                    "callback" => [$this, "callbackRegister"]
                ],
            ]
        );
    }


    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackSubmit()
    {
        // Get values from the submitted form
        $username       = $this->form->value("user");
        $password       = $this->form->value("password");

        // Try to login
        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $res = $user->verifyPassword($username, $password);

        if (!$res) {
            $this->form->rememberValues();
            $this->form->addOutput("User or password did not match.");
            $this->di->get("session")->delete("username");
            return false;
        }

        $this->form->addOutput("User " . $user->username . " logged in.");
        $this->di->get("session")->set("username", $user->username);
        return true;
    }

    /**
     * Callback for the register button
     */
    public function callbackRegister()
    {
        $this->di->get("response")->redirect("user/create");
    }
}
