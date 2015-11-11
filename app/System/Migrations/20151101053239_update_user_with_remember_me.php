<?php use Phinx\Migration\AbstractMigration;
class UpdateUserWithRememberMe extends AbstractMigration {
    public function change() {
        $users = $this->table("users");
        $users->addColumn("remember_me","string",[
            "limit" => 60,
            "null" => true
        ]);
        $users->save();
    }
}
