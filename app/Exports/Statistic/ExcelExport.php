<?php

namespace App\Exports\Statistic;

use App\Models\Ad;
use App\Models\Statistic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExcelExport implements FromCollection, WithMapping, WithHeadings
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
            'Стиль музыки',
            'Подстиль музыки',
            'Название',
            'Музыка/Реклама',
            'Дата добавления',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->id,
            $invoice->style,
            $invoice->substyle,
            $invoice->music,
            $invoice->is_ad ? 'Реклама' : 'Музыка',
            date('d.m.Y H:i:s', strtotime($invoice->created_at))
        ];
    }

    public function collection()
    {
        $statistics = Statistic::where('facility_id', $this->request->id)->whereDate('created_at', '>=', date('Y-m-d H:i:s', strtotime(request('date_start', Carbon::today()))))->whereDate('created_at', '<=', date('Y-m-d H:i:s', strtotime(request('date_end', Carbon::tomorrow()))));

        if (!empty($this->request->is_ad) || $this->request->is_ad === '0') {
            $statistics->where('is_ad', $this->request->is_ad);
        }

        $statistics = $statistics->orderBy('created_at', 'asc')->get();

        foreach ($statistics as $key => $item) {
            if ($item['is_ad']) {
                $temp_arr = explode('/', $item['storage_music']);
                $name = Ad::where('storage', 'LIKE', '%' . end($temp_arr))->where('user_id', $temp_arr[count($temp_arr) - 2])->first();

                if ($name) {
                    $statistics[$key]['music'] = $name->name;
                }
            } else {
                $temp_arr = explode('/', str_replace(['/storage/music/', 'music/'], ['', ''], $item['storage_music']));

                foreach ($temp_arr as $key_music => $item_music) {
                    switch ($key_music) {
                        case 0: $statistics[$key]['style'] = (Storage::exists('public/music/' . $item_music . '/config.json') ? json_decode(Storage::get('public/music/' . $item_music . '/config.json'))->name : 'Данные не найдены'); break;
                        case 1: $statistics[$key]['substyle'] = (Storage::exists('public/music/' . $temp_arr[$key_music - 1] . '/' . $item_music . '/config.json') ? json_decode(Storage::get('public/music/' . $temp_arr[$key_music - 1] . '/' . $item_music . '/config.json'))->name : 'Данные не найдены'); break;
                    }
                }

                $statistics[$key]['music'] = urldecode(last($temp_arr));
            }
        }

        return $statistics;
    }
}
