<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\gii\generators\crud;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\gii\CodeFile;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\gii\generators\crud\Generator as CrudGenerator;

/**
 * Generates CRUD
 *
 * @property array $columnNames Model column names. This property is read-only.
 * @property string $controllerID The controller ID (without the module ID prefix). This property is
 * read-only.
 * @property array $searchAttributes Searchable attributes. This property is read-only.
 * @property boolean|\yii\db\TableSchema $tableSchema This property is read-only.
 * @property string $viewPath The controller view path. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Generator extends CrudGenerator
{
    public $title;
    public $searchFields;
    public $listFields;
    public $formFields;
    public $inputType;
    public $isBatch = false;
    public $baseControllerClass = 'src\controllers\BaseController';

    const TYPE_TEXT = 1;
//    const TYPE_UEDITOR = 2;
    const TYPE_DATE = 3;
    const TYPE_SELECT = 4;

    public function fieldTypes() {
        return [
            self::TYPE_TEXT => 'text',
//            self::TYPE_UEDITOR => 'ueditor',
            self::TYPE_DATE => 'date',
            self::TYPE_SELECT => 'select',
        ];
    }

    /**
     * @var boolean whether to wrap the `GridView` or `ListView` widget with the `yii\widgets\Pjax` widget
     * @since 2.0.5
     */
    public $enablePjax = false;

    public $template = 'myCrud';

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return '新CRUD';
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return '该发生器修改了系统默认的控制器和视图，实现CRUD（创建、读取、更新、删除）指定数据模型的操作。';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['title', 'searchFields', 'listFields', 'formFields', 'inputType', 'isBatch'], 'safe'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'title' => '模型名称',
            'searchFields' => '可搜索字段',
            'listFields' => '展示字段',
            'formFields' => '添加字段',
            'inputType' => '字段类型',
            'isBatch' => '是否批量操作'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function hints()
    {
        return array_merge(parent::hints(), [
            'title' => '模型的标题，减少修改中文e.g., <code>文章</code>.会生成create/添加文章 update/修改文章 index/文章列表 delete/删除文章',
            'searchFields' => '可以被列表页展示的搜索字段',
            'listFields' => '可以被列表页展示的字段',
            'formFields' => '添加修改页面可以被显示页面，e.g., <code>自增ID、添加时间、操作者ID..</code>',
            'inputType' => '字段类型生成',
            'isBatch' => '是否批量操作'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function requiredTemplates()
    {
        return ['controller.php'];
    }

    /**
     * @inheritdoc
     */
    public function stickyAttributes()
    {
        return array_merge(parent::stickyAttributes(), ['baseControllerClass', 'indexWidgetType']);
    }

    /**
     * Checks if model class is valid
     */
    public function validateModelClass()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pk = $class::primaryKey();
        if (empty($pk)) {
            $this->addError('modelClass', "The table associated with $class must have primary key(s).");
        }
    }

    /**
     * @inheritdoc
     */
    public function generate()
    {
        $controllerFile = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->controllerClass, '\\')) . '.php');

        $files = [
            new CodeFile($controllerFile, $this->render('controller.php')),
        ];

        if (!empty($this->searchModelClass)) {
            $searchModel = Yii::getAlias('@' . str_replace('\\', '/', ltrim($this->searchModelClass, '\\') . '.php'));
            $files[] = new CodeFile($searchModel, $this->render('search.php'));
        }

        $viewPath = $this->getViewPath();
        $templatePath = $this->getTemplatePath() . '/views';
        foreach (scandir($templatePath) as $file) {
            if (empty($this->searchModelClass) && $file === '_search.php') {
                continue;
            }
            if (is_file($templatePath . '/' . $file) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $files[] = new CodeFile("$viewPath/$file", $this->render("views/$file"));
            }
        }
        return $files;
    }

    /**
     * @return string the controller ID (without the module ID prefix)
     */
    public function getControllerID()
    {
        $pos = strrpos($this->controllerClass, '\\');
        $class = substr(substr($this->controllerClass, $pos + 1), 0, -10);

        return Inflector::camel2id($class);
    }

    /**
     * @return string the controller view path
     */
    public function getViewPath()
    {
        if (empty($this->viewPath)) {
            return Yii::getAlias('@src/templates/' . $this->getControllerID());
        } else {
            return Yii::getAlias($this->viewPath);
        }
    }

    public function getNameAttribute()
    {
        foreach ($this->getColumnNames() as $name) {
            if (!strcasecmp($name, 'name') || !strcasecmp($name, 'title')) {
                return $name;
            }
        }
        /* @var $class \yii\db\ActiveRecord */
        $class = $this->modelClass;
        $pk = $class::primaryKey();

        return $pk[0];
    }

    /**
     * Generates code for active field
     * @param string $attribute
     * @return string
     */
    public function generateActiveField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        $type = $this->inputType[$attribute];

        switch ($type) {
            case self::TYPE_TEXT:
                return <<<EOL
                <el-form-item prop="{$attribute}" label="请输入关键词">
                    <el-input v-model="selectForm.{$attribute}"></el-input>
                </el-form-item>
EOL;

//            case self::TYPE_UEDITOR:
//                return "\$this->render('/common/_ueditor', ['model'=>\$model, 'attribute'=>'{$attribute}'])";
            case self::TYPE_SELECT:
                return <<<EOL
                        <el-form-item label="选择\$model->getAttributeLabel({$attribute})">
                            <el-select v-model="value" placeholder="请选择<?=\$model->getAttributeLabel('{$attribute}')?>">
                                <el-option>
                                </el-option>
                            </el-select>
                        </el-form-item>
EOL;
            case self::TYPE_DATE:
                return <<<EOL
                        <el-form-item label="选择<?=\$model->getAttributeLabel('{$attribute}')?>">
                            <el-date-picker
                              type="date"
                              placeholder="选择日期">
                            </el-date-picker>
                        </el-form-item>
EOL;

        }

    }

    /**
     * Generates code for active search field
     * @param string $attribute
     * @return string
     */
    public function generateActiveSearchField($attribute)
    {
        $tableSchema = $this->getTableSchema();
        if ($tableSchema === false) {
            return "\$form->field(\$model, '$attribute')";
        }
        $column = $tableSchema->columns[$attribute];
        if ($column->phpType === 'boolean') {
            return "\$form->field(\$model, '$attribute')->checkbox()";
        } else {
            return "\$form->field(\$model, '$attribute')";
        }
    }

    /**
     * Generates column format
     * @param \yii\db\ColumnSchema $column
     * @return string
     */
    public function generateColumnFormat($column)
    {
        if ($column->phpType === 'boolean') {
            return 'boolean';
        } elseif ($column->type === 'text') {
            return 'ntext';
        } elseif (stripos($column->name, 'time') !== false && $column->phpType === 'integer') {
            return 'datetime';
        } elseif (stripos($column->name, 'email') !== false) {
            return 'email';
        } elseif (stripos($column->name, 'url') !== false) {
            return 'url';
        } else {
            return 'text';
        }
    }

    /**
     * Generates validation rules for the search model.
     * @return array the generated validation rules
     */
    public function generateSearchRules()
    {
        if (($table = $this->getTableSchema()) === false) {
            return ["[['" . implode("', '", $this->getColumnNames()) . "'], 'safe']"];
        }
        $types = [];
        foreach ($table->columns as $column) {
            switch ($column->type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                    $types['integer'][] = $column->name;
                    break;
                case Schema::TYPE_BOOLEAN:
                    $types['boolean'][] = $column->name;
                    break;
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                    $types['number'][] = $column->name;
                    break;
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_DATETIME:
                case Schema::TYPE_TIMESTAMP:
                default:
                    $types['safe'][] = $column->name;
                    break;
            }
        }

        $rules = [];
        foreach ($types as $type => $columns) {
            $rules[] = "[['" . implode("', '", $columns) . "'], '$type']";
        }

        return $rules;
    }

    /**
     * @return array searchable attributes
     */
    public function getSearchAttributes()
    {
        return $this->getColumnNames();
    }

    /**
     * Generates the attribute labels for the search model.
     * @return array the generated attribute labels (name => label)
     */
    public function generateSearchLabels()
    {
        /* @var $model \yii\base\Model */
        $model = new $this->modelClass();
        $attributeLabels = $model->attributeLabels();
        $labels = [];
        foreach ($this->getColumnNames() as $name) {
            if (isset($attributeLabels[$name])) {
                $labels[$name] = $attributeLabels[$name];
            } else {
                if (!strcasecmp($name, 'id')) {
                    $labels[$name] = 'ID';
                } else {
                    $label = Inflector::camel2words($name);
                    if (!empty($label) && substr_compare($label, ' id', -3, 3, true) === 0) {
                        $label = substr($label, 0, -3) . ' ID';
                    }
                    $labels[$name] = $label;
                }
            }
        }

        return $labels;
    }

    public function generateListField() {
        $fields = "";
        if (!empty($this->listFields)) {
            foreach ($this->listFields as $column) {
                $type = $this->inputType[$column];
                switch ($type) {
                    case self::TYPE_SELECT:
                        $fields .= "							[
								'attribute' => '{$column}',
								'value' => function(\$model) {
									//\${$column}List = [];
									//return isset(\${$column}List[\$model->{$column}]) ? \${$column}List[\$model->{$column}] : '(未设置)';
									return \$model->{$column};
								}
							],\n";
                        break;
                    default:
                        $fields .= "							'" . $column . "',\n";
                }
            }
        }
        return $fields;
    }

    public function generateSearchField() {
        $fields = "";
        if ($this->searchFields) {
            $searchFields = array_chunk($this->searchFields, 4);
            foreach ($searchFields as $columns) {
                foreach ($columns as $column) {
                    $type = $this->inputType[$column];
                    $columnName = $this->getAttributeLabel($column);
                    switch ($type) {
                        case self::TYPE_SELECT:
                            $fields .= <<<EOL
                        <el-form-item label="选择{$columnName}">
                            <el-select placeholder="请选择{$columnName}" clearable v-model="selectArgs.{$column}">
                                <el-option v-for="item in statusMap"
                                           :key="item.value"
                                           :label="item.label"
                                           :value="item.value">
                                </el-option>
                            </el-select>
                        </el-form-item>
EOL;
                            break;
                        case self::TYPE_DATE:
                            $fields .= <<<EOL
                        <el-form-item label="选择{$columnName}">
                            <el-date-picker
                                    v-model="selectArgs.{$column}"
                                    value=""
                                    type="date"
                                    placeholder="选择日期">
                            </el-date-picker>
                        </el-form-item>
EOL;
                            break;
                        default:
                            $fields .= <<<EOL
                        <el-form-item prop="{$column}" label="请输入{$columnName}">
                            <el-input v-model="selectArgs.{$column}"></el-input>
                        </el-form-item>
EOL;
                    }
                    $fields .= "
                    \n";
                }
                $fields .= "			\n";
            }
        }
        return $fields;
    }

    /**
     * Generates search conditions
     * @return array
     */
    public function generateSearchConditions()
    {
        $columns = [];
        if (($table = $this->getTableSchema()) === false) {
            $class = $this->modelClass;
            /* @var $model \yii\base\Model */
            $model = new $class();
            foreach ($model->attributes() as $attribute) {
                $columns[$attribute] = 'unknown';
            }
        } else {
            foreach ($table->columns as $column) {
                $columns[$column->name] = $column->type;
            }
        }

        $likeConditions = [];
        $hashConditions = [];
        $timeConditions = [];
//         f_d($columns);
        foreach ($columns as $column => $type) {
            if ($this->searchFields) {
                if (!in_array($column, $this->searchFields)) {
                    continue;
                }
            }
            switch ($type) {
                case Schema::TYPE_SMALLINT:
                case Schema::TYPE_INTEGER:
                case Schema::TYPE_BIGINT:
                case Schema::TYPE_BOOLEAN:
                case Schema::TYPE_FLOAT:
                case Schema::TYPE_DOUBLE:
                case Schema::TYPE_DECIMAL:
                case Schema::TYPE_MONEY:
                case Schema::TYPE_DATE:
                case Schema::TYPE_TIME:
                case Schema::TYPE_TIMESTAMP:
                    $hashConditions[] = "'{$column}' => \$this->{$column},";
                    break;
                case Schema::TYPE_DATETIME:
                    $timeConditions[] = "if (\$this->{$column}) {
			list(\$start, \$end) = explode(' - ', \$this->{$column});
			\$query->andFilterWhere(['between', '{$column}', \$start, \$end]);
		}";
                    break;
                default:
                    $likeConditions[] = "->andFilterWhere(['like', '{$column}', \$this->{$column}])";
                    break;
            }
        }

        $conditions = [];
        if (!empty($hashConditions)) {
            $conditions[] = "\$query->andFilterWhere([\n"
                . str_repeat(' ', 12) . implode("\n" . str_repeat(' ', 12), $hashConditions)
                . "\n" . str_repeat(' ', 8) . "]);\n";
        }
        if (!empty($timeConditions)) {
            $conditions[] = implode("\n" . str_repeat(' ', 8), $timeConditions) . ";\n";
        }
        if (!empty($likeConditions)) {
            $conditions[] = "\$query" . implode("\n" . str_repeat(' ', 12), $likeConditions) . ";\n";
        }

        return $conditions;
    }

    /**
     * Generates URL parameters
     * @return string
     */
    public function generateUrlParams()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (count($pks) === 1) {
            if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                return "'id' => (string)\$model->{$pks[0]}";
            } else {
                return "'id' => \$model->{$pks[0]}";
            }
        } else {
            $params = [];
            foreach ($pks as $pk) {
                if (is_subclass_of($class, 'yii\mongodb\ActiveRecord')) {
                    $params[] = "'$pk' => (string)\$model->$pk";
                } else {
                    $params[] = "'$pk' => \$model->$pk";
                }
            }

            return implode(', ', $params);
        }
    }

    /**
     * Generates action parameters
     * @return string
     */
    public function generateActionParams()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (count($pks) === 1) {
            return '$id';
        } else {
            return '$' . implode(', $', $pks);
        }
    }

    /**
     * Generates parameter tags for phpdoc
     * @return array parameter tags for phpdoc
     */
    public function generateActionParamComments()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        $pks = $class::primaryKey();
        if (($table = $this->getTableSchema()) === false) {
            $params = [];
            foreach ($pks as $pk) {
                $params[] = '@param ' . (substr(strtolower($pk), -2) == 'id' ? 'integer' : 'string') . ' $' . $pk;
            }

            return $params;
        }
        if (count($pks) === 1) {
            return ['@param ' . $table->columns[$pks[0]]->phpType . ' $id'];
        } else {
            $params = [];
            foreach ($pks as $pk) {
                $params[] = '@param ' . $table->columns[$pk]->phpType . ' $' . $pk;
            }

            return $params;
        }
    }

    /**
     * Returns table schema for current model class or false if it is not an active record
     * @return boolean|\yii\db\TableSchema
     */
    public function getTableSchema()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema();
        } else {
            return false;
        }
    }

    /**
     * @return array model column names
     */
    public function getColumnNames()
    {
        /* @var $class ActiveRecord */
        $class = $this->modelClass;
        if (is_subclass_of($class, 'yii\db\ActiveRecord')) {
            return $class::getTableSchema()->getColumnNames();
        } else {
            /* @var $model \yii\base\Model */
            $model = new $class();

            return $model->attributes();
        }
    }

    public function getPrimayKey(){
        $class = $this->modelClass;
        $pks = $class::primaryKey();

        return $pks;
    }
}
