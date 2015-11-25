<?php namespace BIRD3\Foundation\User\Widgets;

use View;
use Auth;

class UserSidebar {
    public function register($content="", $attr=[]) {
        $data = ["__partial__" => true];
        if(Auth::check()) {
            $view = View::make("User::widgets.UserPanel", $data);
            $view->getEngine()->setContext(Auth::user());
        } else {
            $view = View::make("User::widgets.LoginPanel", $data);
        }
        return $view;
    }
}
