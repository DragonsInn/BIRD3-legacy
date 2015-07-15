<?php

use Phinx\Migration\AbstractMigration;

class Polls extends AbstractMigration
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
        $cascader = ["delete"=>"CASCADE","update"=>"NO_ACTION"];
        $donull = ["null"=>true];
        $poll = $this->table("poll");
        $poll
            ->addColumn("subject", "string", ["limit"=>200])
            ->addColumn("question", "text")
        ->create();

        $pollOpts = $this->table("poll_options");
        $pollOpts
            ->addcolumn("pID","integer",["null"=>false])
            ->addForeignKey("pID","poll","id",$cascader)
            ->addColumn("sentence","string",["limit"=>100]+$donull)
        ->create();

        $pollVote = $this->table("poll_vote");
        $pollVote
            # This user...
            ->addColumn("uID","integer")
            ->addForeignKey("uID","users","id",$cascader)
            # ... voted $oID(id) option ...
            ->addColumn("oID","integer")
            ->addForeignKey("oID","poll_options","id",$cascader)
            # ... on this poll.
            ->addColumn("pID","integer")
            ->addForeignKey("pID","poll","id",$cascader)
        ->create();
    }
}
