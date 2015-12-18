<?php use Phinx\Migration\AbstractMigration;
class ConversationsCanHavedates extends AbstractMigration {
    public function change() {
        $conv = $this->table("user_pm_conv");
        $conv->addColumn("created_at","timestamp");
        $conv->save();
    }
}
