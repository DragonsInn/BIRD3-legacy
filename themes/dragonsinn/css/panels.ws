<?php
/**
 * Dragon's Inn: Panels
 * In here, we're going to set up variables and CSS for the panels.
 */
$panel_side_width    = 240;
$panel_top_height    = 130;
$panel_bottom_height = 150;

WS(".panel-default")
    ->position(fixed)
    ->background->rgba(0,0,0, 0.5)
    ->visibility("hidden")
->end;

WS(".panel-side")
    ->width($panel_side_width)
    ->height("100%")
    ->top(0)
    ->overflow->y(auto)
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

WS(
    ".panel-bottom-active", ".panel-top-active",
    ".panel-side-active"
)
    ->visibility("visible")
->end;

// Pushing
WS(".panel-pusher")
    ->width("100%")
    ->height("100%")
    ->overflow->x(hidden)
    ->overflow->y(auto)
    ->position(absolute)
    ->left(0)
    ->top(0)
->end;
WS(".panel-pusher-toright")
    ->left($panel_side_width)
->end;
WS(".panel-pusher-toleft")
    ->left(-$panel_side_width)
->end;
WS(".panel-pusher-frombottom")
    ->bottom($panel_bottom_height)
->end;
WS(".panel-pusher-fromtop")
    ->top($panel_top_height)
->end;

// Visual effects
WS(".panel-default", ".panel-pusher")
    ->transition(all, "0.3s", ease)
->end;
