<?php namespace BIRD3\Support\Model;

final class Visible {

    // Public: Visible to everyone.
    const TO_PUBLIC = 0;

    // Community: Visible only to the community.
    const TO_COMMUNITY = 1;

    // Self: Only the owner sees this. Except they share the link.
    const TO_SELF = 2;

}
