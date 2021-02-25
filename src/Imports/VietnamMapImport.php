<?php

namespace HoangPhi\VietnamMap\Imports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;

class VietnamMapImport implements WithHeadingRow, SkipsOnFailure, ToArray, WithChunkReading
{
    protected $districtMap = [];

    protected $provinceMap = [];

    protected $wardMap = [];

    protected $tableNames;

    protected $columnNames;

    public function __construct()
    {
        $this->tableNames = config('vietnam-maps.tables');
        $this->columnNames = config('vietnam-maps.columns');

        $this->createProvinceMap();
        $this->createDistrictMap();
        $this->createWardMap();
    }

    public function onFailure(Failure ...$failures)
    {

    }

    public function array(array $array)
    {
        $wardImport = [];
        foreach ($array as $item) {
            if (empty($item['ma_px']) || empty($item['phuong_xa'])) {
                continue;
            }

            if (isset($this->wardMap[$item['ma_px']])) {
                continue;
            }

            $districtId = $this->getDistrictId($item);
            $wardImport[] = [
                $this->columnNames['name'] => $item['phuong_xa'],
                $this->columnNames['gso_id'] => $item['ma_px'],
                $this->columnNames['district_id'] => $districtId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        try {
            DB::table($this->tableNames['wards'])->insert($wardImport);
        } catch (\Exception $e) {
            // Code
        }
    }

    public function chunkSize(): int
    {
        return 1000;
    }

    private function getProvinceId(array $item)
    {
        return $this->provinceMap[$item['ma_tp']] ?? $this->createProvince($item);
    }

    private function getDistrictId(array $item)
    {
        return $this->districtMap[$item['ma_qh']] ?? $this->createDistrict($item);
    }

    private function createProvince(array $item)
    {
        $provinceId = DB::table($this->tableNames['provinces'])->insertGetId([
            $this->columnNames['name'] => $item['tinh_thanh_pho'],
            $this->columnNames['gso_id'] => $item['ma_tp'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->provinceMap[$item['ma_tp']] = $provinceId;

        return $provinceId;
    }

    private function createDistrict(array $item)
    {
        $provinceId = $this->getProvinceId($item);

        $districtId = DB::table($this->tableNames['districts'])->insertGetId([
            $this->columnNames['name'] => $item['quan_huyen'],
            $this->columnNames['gso_id'] => $item['ma_qh'],
            $this->columnNames['province_id'] => $provinceId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->districtMap[$item['ma_qh']] = $districtId;

        return $districtId;
    }

    private function createProvinceMap()
    {
        $provinces = DB::table($this->tableNames['provinces'])->get();

        $this->provinceMap = $provinces
            ->keyBy($this->columnNames['gso_id'])
            ->map(function ($item) {
                return $item->id;
            })
            ->toArray();
    }

    private function createDistrictMap()
    {
        $districts = DB::table($this->tableNames['districts'])->get();

        $this->districtMap = $districts
            ->keyBy($this->columnNames['gso_id'])
            ->map(function ($item) {
                return $item->id;
            })
            ->toArray();
    }

    private function createWardMap()
    {
        $wards = DB::table($this->tableNames['wards'])->get();

        $this->wardMap = $wards
            ->keyBy($this->columnNames['gso_id'])
            ->map(function ($item) {
                return $item->id;
            })
            ->toArray();
    }
}
