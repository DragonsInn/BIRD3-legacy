<?php use Phinx\Migration\AbstractMigration;
class CharactersInit extends AbstractMigration {

    public function change() {
        # Reusables
        $donull = ["null"=>true];
        $cascade = ["delete"=>"CASCADE","update"=>"NO_ACTION"];

        $chars = $this->table("characters",["id"=>"cID"]);
        $chars
            ->addColumn("uID","integer")
            ->addForeignKey("uID","users","id",$cascade)
            ->addColumn("created_at","integer")
            ->addColumn("edited_at","integer",$donull)
            ->addColumn("last_played","integer",$donull)
            # Main, Casual. Might becoe deprecated later.
            ->addColumn("importance","integer")
            # New 0, OK 1, Abandoned 2
            ->addColumn("status","integer",["default"=>0])
            # Private, Community, Public
            ->addColumn("visibility","integer")
            # Adultness
            ->addColumn("adult","boolean")

            # Basic but important stuff
            ->addColumn("name","string",["limit"=>100])
            ->addColumn("nickName","string",["limit"=>15]+$donull)
            ->addColumn("species","string",["limit"=>50])
            ->addColumn("sex","integer")
            ->addColumn("orientation","integer")
            ->addColumn("personality","text")
            ->addColumn("birthday","string",["limit"=>20]+$donull)
            ->addColumn("birthPlace","string",["limit"=>100]+$donull)
        ;

        // Add generics
        foreach([
            "height", "weight",
            "eye_c", "eye_s",
            "hair_c", "hair_s", "hair_l",
        ] as $cn) $chars->addColumn($cn, "integer",$donull);

        $chars
            ->addColumn("bodyType","integer",$donull)
            ->addColumn("appearance", "text",$donull)
        ;

        # Literatore
        foreach(["history","likes","dislikes","addit_desc"] as $cn)
            $chars->addColumn($cn, "text",$donull);

        # Adult
        $chars
            ->addColumn("dom_sub","integer",$donull)
            ->addColumn("preferences","text",$donull)
        ;

        // Customization
        $chars
            # Additional information for an artist. Refs, etc.
            ->addColumn("artistNote","text",$donull)
            # This font colour will be used int he chat upon activation.
            ->addColumn("font_colour","string",["limit"=>10]+$donull)
            # Create an additional, fully customizable page.
            ->addColumn("other_title","string",["limit"=>50]+$donull)
            ->addColumn("other_content","text",$donull)
            # CSS code that can be applied to the character page.
            ->addColumn("css","text",$donull)
            # Add a "theme" thingy to the front page of the char.
            ->addColumn("theme_type","integer",$donull)
            ->addColumn("theme_content","text",$donull)
            # Each character has one job that also affects RPG stats.
            # FIXME: Maybe allow up to 2?
            ->addColumn("jID","integer",$donull)
            ->addForeignKey("jID","hotel_jobs","id")
        ;

        # DONE!
        $chars->create();

        $pics = $this->table("characterPictures");
        $pics
            # Links to a character, association or alike. Hence Object ID.
            ->addColumn("oID","integer")
            # Define what kind of thing this is related to. Char, Form, Assoc., ...
            ->addColumn("type","integer")
            # Description
            ->addColumn("name","string",["limit"=>100]+$donull)
            # Should be checked for tagging and the like
            ->addColumn("desc","string",["limit"=>255]+$donull)
            # Time of modification
            ->addColumn("created_at","integer")
            ->addColumn("modified_at","integer",$donull)
        ->create();

        $relations = $this->table("characterRelationship");
        $relations
            # $s_id is related to $t_id as $type.
            ->addColumn("s_id","integer")
            ->addForeignKey("s_id","characters","cID",$cascade)
            ->addColumn("t_id","integer")
            ->addForeignKey("t_id","characters","cID",$cascade)
            ->addColumn("type","integer")
        ->create();

        $relationTypes = $this->table("characterRelationship_Type");
        $relationTypes
            ->addColumn("title","string",["limit"=>100])
        ->create();

        $relations->addForeignKey("type","characterRelationship_Type","id",$cascade);
        $relations->save();

        $forms = $this->table("characterForm");
        $forms
            ->addColumn("cID","integer")
            ->addForeignKey("cID","characters","cID",$cascade)
            ->addColumn("name","string",["limit"=>100])
            ->addColumn("desc","text")
        ->create();

        $assoc = $this->table("characterAssociation");
        $assoc
            ->addColumn("name","string",["limit"=>100])
            ->addColumn("summary","string",["limit"=>200])
            ->addColumn("details","text",$donull)
        ->create();

        $assocRel = $this->table(
            "characterAssociation_Rel",
            ["id"=>false,"primary_key"=>["cID","aID"]]
        );
        $assocRel
            ->addColumn("cID","integer")
            ->addForeignKey("cID","characters","cID",$cascade)
            ->addColumn("aID","integer")
            ->addForeignKey("aID","characterAssociation","id",$cascade)
        ->create();

        $shares = $this->table("characterShare_Rel");
        $shares
            ->addColumn("cID","integer")
            ->addForeignKey("cID","characters","cID",$cascade)
            ->addColumn("tID","integer")
            ->addForeignKey("tID","users","id",$cascade)
            ->addColumn("inserted","integer")
            ->addColumn("active","boolean",["default"=>false])
        ->create();

        $faves = $this->table("characterFaves");
        $faves
            ->addColumn("uID","integer")
            ->addForeignKey("uID","users","id",$cascade)
            ->addColumn("cID","integer")
            ->addForeignKey("cID","characters","cID",$cascade)
        ->create();

        $medias = $this->table("characterMedia_Rel",["id"=>false,"primary_key"=>["cID","mID"]]);
        $medias
            ->addColumn("cID","integer")
            ->addForeignKey("cID","characters","cID",$cascade)
            ->addColumn("mID","integer")
            ->addForeignKey("mID","media_submission","id",$cascade)
        ->create();
    }

}
