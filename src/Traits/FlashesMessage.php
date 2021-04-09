<?php

namespace JamesMills\LaravelTimezone\Traits;

trait FlashesMessage
{
    protected function flashLaravelMessage(string $key, string $message): void
    {
        request()->session()->flash($key, $message);
    }

    protected function flashLaracastsMessage(string $key, string $message): void
    {
        flash()->success($message);
    }

    protected function flashMercuryseriesMessage(string $key, string $message): void
    {
        flashy()->success($message);
    }

    protected function flashSpatieMessage(string $key, string $message): void
    {
        flash()->success($message);
    }

    protected function flashMckenzieartsMessage(string $key, string $message): void
    {
        notify()->success($message);
    }
}
