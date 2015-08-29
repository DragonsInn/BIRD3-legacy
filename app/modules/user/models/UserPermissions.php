<?php class UserPermissions extends CActiveRecord {
    public static function model($className=__CLASS__) {
        return parent::model($className);
    }
    public function tableName() {
        return "user_permissions";
    }
    public function primaryKey() { return "id"; }

    /**
     *  @int id PK/FK       | Reffers to the user
     *  @bool publicBlog    | The user's blog is public, merged into the front page.
     *
     *  Discuss who can do these
     *  @bool manageJobs    | User can manage jobs in the hotel
     */
    // ovo

}
