<?php use Phinx\Migration\AbstractMigration;
class UserMedia extends AbstractMigration {
    public function change() {
        $donull = ["null"=>true];
        $submission = $this->table("media_submission");
        $submission
            ->addColumn("uiD","integer")
            ->addForeignKey("uID","users","id",["delete"=>"CASCADE","update"=>"NO_ACTION"])
            ->addColumn("title","string",["limit"=>50])
            ->addColumn("desc","text")
            ->addColumn("public","boolean",["default"=>true])
            # Is this adult? If the user has adult stuff disabled, this is ALWAYS false.
            ->addColumn("adult","boolean")
            ->addColumn("type","integer")

            # Specirics
            ->addColumn("length","integer")
            ->addColumn("filesize","integer")
            ->addColumn("ext","string",["limit"=>5])
            ->addColumn("hash","string",["limit"=>40])
        ->create();

        $comment = $this->table("media_comment");
        $comment
            # media id
            ->addColumn("mID","integer")
            ->addForeignKey("mID","media_submission","id",["delete"=>"CASCADE","update"=>"NO_ACTION"])
            # User
            ->addColumn("uID","integer")
            ->addForeignKey("uID","users","id",["delete"=>"CASCADE","update"=>"NO_ACTION"])
            # If this comment is a response, this is greater than 0.
            ->addColumn("responseTo","integer",["default"=>-1])
            ->addColumn("content","text")
        ->create();

        $faves = $this->table("media_faves",["id"=>false,"primary_key"=>["mID","uID"]]);
        $faves
            # Media
            ->addColumn("mID","integer")
            ->addForeignKey("mID","media_submission","id",["delete"=>"CASCADE","update"=>"NO_ACTION"])
            # User
            ->addColumn("uID","integer")
            ->addForeignKey("uID","media_submission","id",["delete"=>"CASCADE","update"=>"NO_ACTION"])
            # should i add faved_at?
        ->create();

        # FIXME: Connector tables and data entries.
        # How do i get the rank value int his thing?
        #$rating = $this->table("media_rating");

    }
}
