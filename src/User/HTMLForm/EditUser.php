<?php

namespace linder\User\HTMLForm;

use Anax\HTMLForm\FormModel;
use linder\User\User;
use Psr\Container\ContainerInterface;

/**
 * Example of FormModel implementation.
 */
class EditUser extends FormModel
{
    /**
     * Constructor injects with DI container.
     *
     * @param Psr\Container\ContainerInterface $di a service container
     */
    public function __construct(ContainerInterface $di, $id)
    {
        parent::__construct($di);

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $user->find("userId", $id);

        $this->form->create(
            [
                "id" => __CLASS__,
            ],
            [
                "user" => [
                    "label"       => "Användarnamn:",
                    "type"          => "text",
                    "value"         => $user->username,
                    "readonly"      => true
                ],
                
                "old-password" => [
                    "label"       => "Lösenord:",
                    "type"        => "password"
                ],
                
                "new-password" => [
                    "label"       => "Nytt lösenord:",
                    "type"        => "password",
                ],
                
                "re-password" => [
                    "label" => "Repetera det nya lösenordet:",
                    "type"        => "password"
                ],
                
                "email" => [
                    "type" => "email",
                    "value" => $user->email,
                ],
                
                "submit" => [
                    "type" => "submit",
                    "value" => "Spara ändringar",
                    "callback" => [$this, "callbackSubmit"]
                ],

                "logout" => [
                    "type" => "submit",
                    "value" => "Logga ut",
                    "callback" => [$this, "callbackLogout"]
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
        $old            = $this->form->value("old-password");
        $password       = $this->form->value("new-password");
        $repass         = $this->form->value("re-password");
        $email          = $this->form->value("email");
        
        // Check password matches
        if ( $old && ($password !== $repass )) {
            $this->form->rememberValues();
            $this->form->addOutput("New passwords did not match.");
            return false;
        }

        $user = new User();
        $user->setDb($this->di->get("dbqb"));
        $res = $user->verifyPassword($username, $old);

        if (!$res) {
            $this->form->rememberValues();
            $this->form->addOutput("User or password did not match.");
            return false;
        }

        $this->form->addOutput("User " . $user->username . " profile edited.");
        if ($password) {
            $user->setPassword($password);
        }
        $user->email = $email;
        $user->save();
        return true;
    }

    /**
     * Callback for submit-button which should return true if it could
     * carry out its work and false if something failed.
     *
     * @return boolean true if okey, false if something went wrong.
     */
    public function callbackLogout()
    {
        $this->di->get("session")->delete("userId");
        return true;
    }
}
