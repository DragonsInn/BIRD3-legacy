<?php
/**
 * Dragon's Inn: Panels
 * In here, we're going to set up variables and CSS for the panels.
 */
$panel_side_width    = 240;
$panel_top_height    = 150;
$panel_bottom_height = 150;

WS(".panel-default")
    ->position(fixed)
    ->background->rgba(0,0,0, 0.5)
->end;

WS(".panel-side")
    ->width($panel_side_width)
    ->height("100%")
    ->top(0)
    ->zIndex(1000)
->end;

WS(".panel-top", ".panel-bottom")
    ->width("100%")
    ->left(0)
    ->zIndex(1000)
    ->overlfow(hidden)
->end;
WS(".panel-top")
    ->height($panel_top_height)
->end;
WS(".panel-bottom")
    ->height($panel_bottom_height)
->end;

// Sliding settings...
WS(".panel-left")
    ->left(-$panel_side_width)
->end;
WS(".panel-right")
    ->right(-$panel_side_width)
->end;
WS(".panel-top")
    ->top(-$panel_top_height)
->end;
WS(".panel-bottom")
    ->top(-$panel_bottom_height)
->end;

WS(".panel-left.panel-side-active")
    ->left(0)
->end;
WS(".panel-right.panel-side-active")
    ->right(0)
->end;
WS(".panel-top.panel-top-active")
    ->top(0)
->end;
WS(".panel-bottom.panel-bottom-active")
    ->bottom(0)
->end;

// Visual effects
WS(".panel-default", ".panel-pusher")
    ->transition(all, "0.3s", ease)
->end;
