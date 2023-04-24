<?php
// signup controller
use Phalcon\Mvc\Controller;

class SignupController extends Controller
{
    public function IndexAction()
    {
        // this is the default action of signup controller
    }

    public function registerAction()
    {
        $user = new Users();
        $post = $this->request->getPost();
        $user->assign(
            $this->request->getPost(),
            [
                'name',
                'email',
                'password'
            ]
        );
        $success = $user->save();

        $this->view->success = $success;

        if ($success) {
            if ($post['check'] == 'on') {
                $this->cookies->set("email", $post['email'], time() + 30 * 86400, '/');
                $this->cookies->set("password", $post['password'], time() + 30 * 86400, '/');
            }
            $this->view->message = "Register succesfully";
        } else {
            $this->view->message = "Not Register \
            succesfully due to following reason: <br>" . implode("<br>", $user->getMessages());
        }
    }
}
