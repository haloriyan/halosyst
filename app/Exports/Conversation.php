<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class Conversation implements FromView, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($datas)
    {
        $this->datas = $datas;
    }
    public function view(): View {
        return view('exports.conversation', [
            'datas' => $this->datas
        ]);
    }
}
