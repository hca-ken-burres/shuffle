<?php

namespace App\Infolists\Components;

use Filament\Infolists\Components\Entry;

class LinkEntry extends Entry
{
    protected string $view = 'infolists.components.link-entry';

    protected string $target = '_blank';

    protected string $text = '';

    // protected string $url = '';

    public function text($text) {
        $this->text = $text;
        return $this;
    }

    // public function url($url) {
    //     $this->url = $url;
    //     return $this;
    // }

    // public function openUrlInNewTab() {
    //     $this->target = '_blank';
    //     return $this;
    // }


}
