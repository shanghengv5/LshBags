<?php
/*
 * @Date: 2021-01-22 11:56:45
 * @LastEditors: LiShangHeng
 * @LastEditTime: 2021-01-22 17:03:23
 * @FilePath: /LshBags/src/Core/Console/EzColumn.php
 */

namespace Lsh\Core\Console;
use Illuminate\Console\Command;
use Lsh\Core\Console\Traits\EzCommand;
class EzColumn extends Command
{
    use EzCommand;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ez:column {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the table column info';

    /**
     * 表属性
     * @var 
     */
    protected $columns;
    protected $noSave = [
        'id', 'created_at', 'updated_at', 'is_on'
    ];
    protected $listRule = [];
    protected $storeRule = [];
    protected $updateRule = [];
    protected $requiredRule = 'required';
    protected $delimiterRule = '|';
    protected $modelMap = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->getCommandArguments();
        
        $this->getColumns();

        $this->getFieldInfo();
        
        $this->exportInfo();

    }

    /**
     * @name: LiShangHeng
     * @info: 通过其他命令执行
     * @param array $data
     * @return {*}
     */
    public function runByCommand($data) {
        $this->getCommandArguments($data);
        $this->dealNameArguments();
        
        $this->getColumns();

        $this->getFieldInfo();
        
    }


    /**
     * @name: LiShangHeng
     * @info: 打印可用信息
     */
    public function exportInfo() {
        switch ($this->cmdType) {
            case 'list':
                var_export($this->listRule);
                break;
            case 'store':
                var_export($this->storeRule);
                break;
            case 'update':
                var_export($this->updateRule);
                break;
            case 'model':
                var_export($this->modelMap);
                break;
            default:
                var_export($this->listRule);
                break;
        }
    }

    /**
     * @name: LiShangHeng
     * @info: 返回可用信息
     * @param  $type
     * @return string
     */
    public function exportAndReturn($type = 'list') {
        switch ($type) {
            case 'list':
                $info = var_export($this->listRule, true);
                break;
            case 'store':
                $info = var_export($this->storeRule, true);
                break;
            case 'update':
                $info = var_export($this->updateRule, true);
                break;
            case 'model':
                $info = var_export($this->modelMap, true);
                break;
            default:
                $info = var_export($this->listRule, true);
                break;
        }
        return $info;
    }

    /**
     * @name: LiShangHeng
     * @info: 获取表的列
     */
    public function getColumns() {
        $this->columns = \DB::select("SHOW FULL COLUMNS FROM `{$this->snakeName($this->className)}`");
    }


    /**
     * @name: LiShangHeng
     * @info: 设置不需要保存的数据
     * @param array $array
     * @return {*}
     */
    public function setNoSave($array) {
        $this->noSave = $array;
    }

    /**
     * @name: LiShangHeng
     * @info: 获取有用的列信息
     */
    public function getFieldInfo() {
        foreach($this->columns as $value) {
            $key = $value->Field;
            $type = $value->Type;
            $comment = $value->Comment;
            
            $this->dealRule($key, $type);

            $this->dealModel($key, $comment);
        }
    }

    

    /**
     * @name: LiShangHeng
     * @info: 处理模型
     * @param string key
     * @param  $comment 
     * @return {*}
     */
    public function dealModel(string $key, $comment) {
        if(strpos($comment, 'media') !== false) {
            $this->modelMap[$key] = 'media';
        }
    }

    /**
     * @name: LiShangHeng
     * @info: 处理规则
     * @param string key 
     * @param string type
     */
    public function dealRule($key,$type) {
        $typeRule = $this->getTypeRule($type);
        // 处理更新和添加
        // $this->line(in_array($key, $this->noSave));
        if(!in_array($key, $this->noSave)) {
            $this->storeRule[$key] = $this->requiredRule . $this->delimiterRule . $typeRule;
            $this->updateRule[$key] = $typeRule;
        }
        // 处理列表规则
        $this->listRule[$key] = $typeRule;
    }

    /**
     * @name: LiShangHeng
     * @info: 获取字段类型对应规则
     * @param string type
     * @return string
     */
    public function getTypeRule($type) {
        $ruleMap = [
            'varchar' => 'string',
            'int' => 'integer',
            'text' => 'string',
        ];
        
        foreach($ruleMap as $key => $rule) {
            if(strpos($type, $key) !== false) {
                return $rule;
            }
        }
        return '';
    }
}
