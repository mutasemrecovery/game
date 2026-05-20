<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class OrdersExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Order::with(['user', 'delivery', 'orderProducts.product'])->latest();

        if (!empty($this->filters['check_date'])) {
            $query->where('order_status', 6)
                  ->whereDate('date', '<', $this->filters['check_date']);
        }

        if (!empty($this->filters['number'])) {
            $query->where('number', 'like', '%' . $this->filters['number'] . '%');
        }

        if (!empty($this->filters['user_name'])) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $this->filters['user_name'] . '%'));
        }

        if (!empty($this->filters['delivery_place'])) {
            $query->whereHas('delivery', fn($q) => $q->where('place', 'like', '%' . $this->filters['delivery_place'] . '%'));
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            '#',
            'رقم الطلب',
            'اسم العميل',
            'هاتف العميل',
            'تاريخ الطلب',
            'الوقت',
            'حالة الطلب',
            'الشخصيات',
            'مجموع السعر',
            'رسوم التوصيل',
            'الإجمالي',
            'موقع التوصيل',
            'العنوان',
            'ملاحظة',
        ];
    }

    public function map($order): array
    {
        static $i = 0;
        $i++;

        $statusMap = [
            1 => 'في الانتظار',
            2 => 'قيد المعالجة',
            3 => 'ملغي',
            6 => 'تم التنفيذ',
            7 => 'تم الإرجاع',
        ];

        $products = $order->orderProducts->map(function ($item) {
            return ($item->product->name_ar ?? $item->product->name_en) . ' x' . $item->quantity;
        })->implode(' | ');

        return [
            $i,
            $order->number,
            $order->user->name ?? '-',
            $order->user->phone ?? '-',
            Carbon::parse($order->date)->format('d/m/Y'),
            Carbon::parse($order->date)->format('g:i A'),
            $statusMap[$order->order_status] ?? $order->order_status,
            $products,
            'JD ' . number_format($order->total_prices, 2),
            'JD ' . number_format($order->delivery_fee, 2),
            'JD ' . number_format($order->total_prices + $order->delivery_fee, 2),
            $order->delivery->place ?? '-',
            $order->address ?? '-',
            $order->note ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['argb' => 'FF2563EB']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
