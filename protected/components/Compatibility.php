<?php class Compatibility {
    public static function check($feature) {
        $mtm = "check_".$feature;
        if(method_exists(get_class(), $mtm)) {
            return self::{$mtm}();
        } else {
            // Not implemented, is always true.
            throw new CException("Browser check for feature '$feature' is not implemented!");
        }
    }

    public static function check_blur_bg() {
        $b = Yii::app()->browser;
        if($b->getBrowser() == Browser::BROWSER_SAFARI) {
            $v = explode(".", $b->getVersion());
            if($v[0] == 8) {
                // Version is 8. OS X Yosemite assumed, broken.
                return false;
            } else {
                // Versions earlier than 8 not tested.
                return true;
            }
        } elseif($b->isMobile()) {
            // ALL phones will have issues with the re-sampling! Kill the effect.
            return false;
        } else {
            // No other browsers tested.
            // Chrome works.
            return true;
        }
    }
} ?>
