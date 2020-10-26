<?php
 
namespace App\Imports;
 
use App\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
class TenantsImport implements ToModel,WithStartRow,WithValidation,SkipsOnFailure
{
    use Importable,SkipsFailures;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Member([
            'name' => $row[0],
            'username' => $row[1],
            'password' => encrypt($row[2])
        ]);
    }
    // 從2行開始讀取資料
    public function startRow(): int
    {
        return 2;
    }
 
    
    // 驗證
    public function rules(): array
    {
        return [
            '0' => 'required|unique:members',
            '1' => 'required|unique:members',
            '2' => 'required|unique:members',
        ];
    }
 // 自定義驗證資訊
 public function customValidationMessages()
 {
    return [
        '0.required' => '姓名未填',
        '0.unique' => '姓名重複'
    ];
      
 }
 public function batchSize(): int
    {
        return 1000;
    }
}
