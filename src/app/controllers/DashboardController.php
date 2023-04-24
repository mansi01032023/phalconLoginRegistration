<?php
// this is the dashboard cotroller
use Phalcon\Mvc\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        if (!$this->cookies->has('loggedin')) {
            $this->response->redirect("login");
        } else {
            $this->view->date = $this->time->getTime();
        }
    }
}
