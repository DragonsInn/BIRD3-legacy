<?php class PmController extends Controller {

    use UserFilters;
    public function filters() {
        return [
            "must_be_logged_in + box, compose, show"
        ];
    }

    public function actionBox($page=1) {
        $convos = PrivateConversation::model()->countByAttributes([
            "owner_id" => User::me()->id
        ]);
        $pg = new Voodoo\Paginator();
        #$pg->setUrl($_SERVER["REQUEST_URI"], "/user/pm/box/page/{:num}");
        $pg->setPage($page);
        $count = count($convos);
        $pg->setItems($count, 20);
        $pg->setPrevNextTitle(
            '<i class="fa fa-caret-left" aria-hidden="true"></i> Prev',
            'Next <i class="fa fa-caret-right" aria-hidden="true"></i>'
        );
        $limit = $pg->getPerPage();
        $offset = $pg->getStart();
        $myConvos = PrivateConversation::model()->findAll([
            "limit"=>$limit,
            "offset"=>$offset
        ]);
        $pages = $pg->toArray();
        $this->render("box", ["pages"=>$pages, "convos"=>$myConvos]);
    }

    public function actionCompose($to=null) {
        $user = null;
        if(!is_null($to)) $user = User::get($to);
        else $user = new User;

        // The conversation
        $convo = new PrivateConversation;
        $convo->owner_id = User::me()->id;
        $members = [];

        // A new message
        $msg = new PrivateMessage;
    }

    public function actionShow($conv_id) {
        // Find and back-travel all messages
        $messages = PrivateMessage::model()->findAllByAttributes([
            "conv_id"=>$conv_id
        ]);
        $convo = PrivateConversation::model()->findByPk($conv_id);
        $this->render("show",[
            "messages"=>$messages,
            "convo"=>$convo,
            "newMsg"=>new PrivateMessage
        ]);
    }
}
