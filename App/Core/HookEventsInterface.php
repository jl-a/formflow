<?php

namespace FormFlow\Core;

/**
 * Provides an interface for classes to use the `hook_events` function,
 * which will be safely run when the site loads. Classes can put side
 * effects or other logic that needs to run on class construction in
 * the `hook_events` function. Only use `__construct` when logic needs
 * to run before any other class is loaded.
 */
interface HookEventsInterface {
    /**
     * Intitialisation function that can be safely called to load functionalty.
     * @return void
     */
    function hook_events(): void;
}
