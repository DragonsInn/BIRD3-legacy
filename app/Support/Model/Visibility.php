<?php namespace BIRD3\Support\Model;

use BIRD3\Foundation\Traits\Stateability;
use BIRD3\Foundation\User\Entity as User;

/**
 * Describe the visibility of an object.
 *
 * For instance, an artwork may be published to only be seen by the community.
 * A work in progress story may only be published privately, so that a friend can review it.
 */
trait Visibility {
    use Stateability;

    // Constructor
    private function initVisibility() {
        $this->__initState("visibilityField", "visibility", Visible::TO_PUBLIC);
    }

    // # Scopes

    public function scopeIsVisibleToPublic($q) {
        return $q->where($this->visibilityField, Visible::TO_PUBLIC);
    }

    public function scopeIsVisibleToCommunity($q) {
        return $q->where($this->visibilityField, Visible::TO_COMMUNITY);
    }

    public function scopeIsVisibleToSelf($q) {
        return $q->where($this->visibilityField, Visible::TO_SELF);
    }

    // # Testing

    /**
     * Get the current visibility level.
     * @return Visible (TO_PUBLIC, TO_COMMUNITY, ...)
     */
    private function getVisibility() {
        return $this->{$this->visibilityField};
    }

    /**
     * Returns true, if everyone - even non-registered users - can see this.
     * @return boolean True, if public.
     */
    public function isPublic() {
        return $this->getVisibility() == Visible::TO_PUBLIC;
    }

    /**
     * Returns true, if only registered users can see this.
     * @return boolean True, if community can see it.
     */
    public function isCommunityOnly() {
        return $this->getVisibility() == Visible::TO_COMMUNITY;
    }

    /**
     * Returns true, if only the owning user
     * (and possibly only few others) can see this.
     * @return boolean True if private.
     */
    public function isPrivate() {
        return $this->getVisibility() == Visible::TO_SELF;
    }

    /**
     * Returns true if the target can see this content.
     *
     * Target ($obj) can be:
     * 		- `null` : A guest user.
     * 		- BIRD3 User : A registered user
     *
     * @param  [type] $obj [description]
     * @return [type]      [description]
     */
    public function canBeSeenBy($obj) {
        $isUser = ($obj instanceof User);
        if($this->isPublic()) {
            // Everyone can see this.
            return true;
        } else if($isUser) {
            if(!$this->isPrivate()) {
                // Logged in users can see all but private content.
                return true;
            } else {
                // A class using this trait should overwrite `canBeSeenByUser()`
                // to guarantee this to work properly.
                return $this->canBeSeenByUser($obj);
            }
        }
    }

    /**
     * An object using this trait can use this function to customize
     * the behaviour, when a registered user tries to view the content.
     *
     * This is useful if sharing is enabled on a resource.
     *
     * @param  User   $user The accessing user.
     * @return boolean       True if access is granted. False, if not.
     */
    public function canBeSeenByUser(User $user) {
        // False by default, since private is private.
        // Everybody but secret agencies knows that (:
        return false;
    }

}
