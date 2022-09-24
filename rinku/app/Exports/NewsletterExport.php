<?php

namespace App\Exports;

use App\Models\Newsletter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NewsletterExport implements FromCollection, ShouldAutoSize, WithHeadings
{
  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return Newsletter::selectRaw('id, vc_email')
      ->get();
  }

  /**
   * @return array
   */
  public function headings(): array
  {
    return [
      'ID',
      'Email',
    ];
  }
}
