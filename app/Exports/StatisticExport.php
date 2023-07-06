<?php

namespace App\Exports;

use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StatisticExport implements FromCollection, WithMapping, WithHeadings
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function headings(): array
    {
        return [
            '#',
            'URL музыки/рекламы',
            'Музыка/Реклама',
            'Дата добавления',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->id,
            $invoice->storage_music,
            $invoice->is_ad ? 'Музыка' : 'Реклама',
            date('d.m.Y H:i:s', strtotime($invoice->created_at))
        ];
    }

    public function collection()
    {
        $statistics = Statistic::where('facility_id', $this->request->id)->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime(request('date_start', Carbon::today()))))->whereDate('created_at', '<=', date('Y-m-d H:i:s', strtotime(request('date_end', Carbon::tomorrow()))));

        if (!empty($this->request->is_ad) || $this->request->is_ad === '0') {
            $statistics->where('is_ad', $this->request->is_ad);
        }

        return $statistics->orderBy('created_at', 'asc')->get();
    }
}
