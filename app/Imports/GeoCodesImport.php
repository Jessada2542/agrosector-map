<?php

namespace App\Imports;

use App\Models\GeoCode;
use Maatwebsite\Excel\Concerns\ToModel;

class GeoCodesImport implements ToModel
{
    public function model(array $row)
    {
        return new GeoCode([
            'province_code'        => $row[0],  // สมมติว่าในไฟล์ Excel คอลัมน์แรกเป็น province_code
            'province_name_th'     => $row[1],  // province_name_th
            'province_name_en'     => $row[2],  // province_name_en
            'district_code'        => $row[3],  // district_code
            'district_name_th'     => $row[4],  // district_name_th
            'district_name_en'     => $row[5],  // district_name_en
            'subdistrict_code'     => $row[6],  // subdistrict_code
            'subdistrict_name_th'  => $row[7],  // subdistrict_name_th
            'subdistrict_name_en'  => $row[8],  // subdistrict_name_en
        ]);
    }
}
