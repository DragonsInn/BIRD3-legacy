<?php

use Phinx\Migration\AbstractMigration;

class UserBans extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     */
    public function change() {
        $donull = ["null"=>true];
        $bans = $this->table("bans");
        $bans
            # If this ban is for a user, then this is filled
            ->addColumn("uID","integer")
            # IP
            ->addColumn("ip","string", ["limit"=>16]+$donull)
            # A fingerprint for a user.
            ->addColumn("fingerprint","string", ["limit"=>100]+$donull)
            # The reason for the ban
            ->addColumn("reason","text")
            # Will this ban end? ==0 Yes ; >0 Untill then
            ->addColumn("endTime", "integer")
        ->create();
    }
}
