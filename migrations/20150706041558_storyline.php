<?php

use Phinx\Migration\AbstractMigration;

class Storyline extends AbstractMigration
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
        # One char -> One job. Using a charJobs table, I can make it n:m.
        # Jobs should probably provide RPG statistics.
        $donull = ["null"=>true];
        $jobs = $this->table("hotel_jobs");
        $jobs
            ->addColumn("title","string", ["limit"=>30])
            ->addColumn("where_id","integer")
            ->addColumn("intro","string",["limit"=>200])
            ->addColumn("desc","text")
        ->create();

        $places = $this->table("hotel_places");
        $places
            ->addColumn("title","string",["limit"=>30])
            ->addColumn("desc","text")
            ->addColumn("image_url","string",["limit"=>255]+$donull)
        ->create();

        $xynu = $this->table("system_xynu");
        $xynu
            # Who taught her...
            ->addColumn("uID","integer")
            # ...this line?
            ->addColumn("sentence","text")
        ->create();

        # FK links
        $jobs->addForeignKey("where_id","hotel_places","id",["delete"=>"CASCADE","update"=>"NO_ACTION"]);
        $jobs->save();
    }
}
