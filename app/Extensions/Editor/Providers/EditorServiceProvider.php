<?php namespace BIRD3\Extensions\Editor\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

use Widget;
use HTML;

class EditorServiceProvider extends ServiceProvider {
    public function register() {

    }

    public function boot() {
        Widget::register("MarkdownEditor", function($content="", $attrs=[]){
            $attrs = array_replace([
                "placement"=>"bottom",
                "taClass"=>"",
                "autogrow"=>false,
                "textDisplay"=>true,
                "useWell"=>true,
                "editorPlacement"=>"bottom",
                "groupSize"=>"sm",
                "placeholder"=>"",
                "height"=>"200px",
                "b3me"=>true,
                "id"=>"u-".uniqid()
            ], $attrs);

            // The final attributes.
            $divAttrs = [];

            // ID and class...
            $divAttrs["class"] = ($attrs["useWell"] ? "well well-sm" : "NothingHereButUsBools");
            $divAttrs["id"] = (isset($attrs["id"]) ? $attrs["id"] : $attrs["attribute"]);

            if(isset($attrs["id"])) unset($attrs["id"]);

            // Convert specific keys.
            foreach($attrs as $key=>$val) {
                $divAttrs["data-".Str::snake($key, "-")] = $val;
            }

            $attrString = HTML::attributes($divAttrs);
            return "<div{$attrString}>{$content}</div>";
        });
    }
}
