<?php namespace BIRD3\Support\Model;

use BIRD3\Foundation\Traits\Stateability;
use BIRD3\Foundation\User\Entity as User;
use Request;

trait Rateable {
    use Stateability;

    private function initRateable() {
        $this->__initState("rateField", "rating", Rating::IS_CLEAN);
    }

    public function scopeClean($q) {
        return $q->where($this->rateField, Rating::IS_CLEAN);
    }

    public function scopeTasteful($q) {
        return $q->where($this->rateField, Rating::IS_TASTEFUL);
    }

    public function scopeAdult($q) {
        return $q->where($this->rateField, Rating::IS_ADULT);
    }

    public function getRating() {
        return $this->{$this->rateField};
    }

    public function isClean() {
        return $this->getRating() == Rating::IS_CLEAN;
    }

    public function isTasteful() {
        return $this->getRating() == Rating::IS_TASTEFUL;
    }

    public function isAdult() {
        return $this->getRating() == Rating::IS_ADULT;
    }

    public function canBeSeenBy($obj) {
        $isUser = $obj instanceof User;
        if($isUser) {
            if($this->isClean()) {
                return true;
            } else {
                return $obj->canSeeNSFWContent();
            }
        } else {
            // A guest visitor...
            // Do they have NSFW mode enabled? If they do, they can see it.
            // FIXME: Implement NSFW mode.
            return Request::hasCookie("BIRD3_nsfw_mode");
        }
    }
}
