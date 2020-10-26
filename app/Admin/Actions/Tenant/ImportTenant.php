<?php
 
namespace App\Admin\Actions\Tenant;
 
use Encore\Admin\Actions\Action;
use App\Imports\TenantsImport;
use Illuminate\Http\Request;
 
class ImportTenant extends Action
{
    protected $selector = '.import-tenant';
 
    public function handle(Request $request)
    {
        $request->file('file');
        
        // 下面的程式碼獲取到上傳的檔案，然後使用`maatwebsite/excel`等包來處理上傳你的檔案，儲存到資料庫
        $import = new TenantsImport();
        $import->import($request->file('file'));
        $this->app->make(\Maatwebsite\Excel\Transactions\TransactionManager::class)->extend('your_handler', function() {
            return new YourTransactionHandler();
        });
        $str = "";
        foreach ($import->failures() as $failure) {
            $str .=  ' 第'. $failure->row() . '行 失敗原因：' . implode(' ', $failure->errors()) . '<br> 行資料：' . implode(' ', $failure->values()). '<br>';
        }
        if ($str !== '') {
            return $this->response()->error($str)->topFullWidth()->timeout(7000000);
        }
        return $this->response()->success('匯入完成！')->refresh();
    }
    public function form()
    {
        print(chr(0xEF).chr(0xBB).chr(0xBF));
        $this->file('file', '請選擇檔案');
    }
    public function html()
    {
        return <<<HTML
        <a class="btn btn-sm btn-default import-tenant">匯入資料</a>
HTML;
    }
}
