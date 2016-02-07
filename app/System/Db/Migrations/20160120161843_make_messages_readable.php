<?php use Phinx\Migration\AbstractMigration;
class MakeMessagesReadable extends AbstractMigration {
    public function change() {
        $rsName = "user_pm_msg_readstatus";
        $readStatus = $this->table($rsName);
        $readStatus
            ->addColumn("msg_id", "integer")
            ->addForeignKey("msg_id","user_pm_msg","id",[
                "delete"=>"CASCADE"
            ])

            ->addColumn("user_id","integer")
            ->addForeignKey("user_id","users","id",[
                "delete"=>"CASCADE"
            ])
            ->addColumn("isRead","boolean",["default"=>false])
        ->create();
    }
}
