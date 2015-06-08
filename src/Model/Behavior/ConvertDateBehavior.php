<?php
/**
 * Behavior para converter campos de datas para o formato americano
 *
 * MIT License
 *
 * @author     Jorge Jardim <jorge@jorgejardim.com.br>
 * @license    http://www.opensource.org/licenses/mit-license.php The MIT License
 */
namespace JCustomCakephp3\Model\Behavior;

use Cake\ORM\Entity;
use Cake\Event\Event;
use Cake\ORM\Behavior;

class ConvertDateBehavior extends Behavior
{

    public $fields;

    public function initialize(array $config = [])
    {
        if (empty($config)) {
            $this->fields[$this->_table->alias()] = $this->_lookingDateFields();
        } else {
            $this->fields[$this->_table->alias()] = $config;
        }
    }

    public function beforeSave(Event $event, Entity &$entity)
    {
        $this->_adjustDates($entity);
    }

    private function _adjustDates(Entity &$entity)
    {
        foreach ($this->fields[$this->_table->alias()] as $field) {
            if ($entity->has($field) && $entity->get($field) !== "") {
                if ($this->_isDateTime($entity->get($field))) {
                    $this->_adjustDateTime($entity, $field);
                } elseif (preg_match('/\d{1,2}\/\d{1,2}\/\d{2,4}/', $entity->get($field))) {
                    $this->_adjustDate($entity, $field);
                }
            }
        }
    }

    private function _isDateTime($value)
    {
        return preg_match('/\d{1,2}\/\d{1,2}\/\d{2,4} \d{1,2}\:\d{1,2}/', $value);
    }

    private function _lookingDateFields()
    {
        $columns = $this->_table->schema()->columns();
        if (!is_array($columns)) {
            return [];
        }
        $exit = [];
        foreach ($columns as $field) {
            if ($this->_table->schema()->columnType($field) === 'date'
                || $this->_table->schema()->columnType($field) === 'datetime'
                && !in_array($field, ['created', 'updated', 'modified'])
            ) {
                $exit[] = $field;
            }
        }
        return $exit;
    }

    private function _adjustDateTime(Entity &$entity, $field)
    {
        if (is_array($field)) {
            $field = implode('', $field);
        }
        $newDate = $this->_splitDateTime($entity->get($field));
        list($day, $month, $year) = explode('/', $newDate[0]);
        $newDate[1] = strlen(trim($newDate[1]))==5?$newDate[1].':00':$newDate[1];
        list($hour, $minute, $second) = explode(':', $newDate[1]);
        if (strlen($year) == 2) {
            $year = $year > 50 ? $year + 1900 : $year + 2000;
        }
        $entity->set($field, "$year-$month-$day $hour:$minute:$second");
    }

    private function _adjustDate(Entity &$entity, $field)
    {
        if (is_array($field)) {
            $field = implode('', $field);
        }
        list($day, $month, $year) = explode('/', $entity->get($field));
        if (strlen($year) == 2) {
            $year = $year > 50 ? $year + 1900 : $year + 2000;
        }
        $entity->set($field, "$year-$month-$day");
    }

    private function _splitDateTime($value = "")
    {
        if (!isset($value) || empty($value)) {
            return [];
        }
        if (strpos($value, "T")) {
            return explode("T", $value);
        } elseif (strpos($value, "t")) {
            return explode("t", $value);
        } elseif (strpos($value, " ")) {
            return explode(" ", $value);
        }
    }
}