<?php

namespace App\Core\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class BaseTransformer extends TransformerAbstract
{
    /**
     * Mapper of conversion between presenters
     */
    protected $conversionMapper;

    /**
     * Object with item converted
     */
    protected $convertedItem;

    protected function convert($data, $from, $to)
    {
        if ($this->fieldHasTheSameTitle($from)) {
            if (!isset($data[$to])) {
                return false;
            }
            return $this->convertedItem[$to] = !empty($data[$to]) ? $data[$to] : null;
        }

        if (!array_key_exists($from, $data)) {
            return false;
        }

        if (is_array($to)) {
            return $this->typecast($data, $from, $to);
        }

        return $this->convertedItem[$to] = !empty($data[$from]) ? $data[$from] : null;
    }

    /**
     * @param $from
     * @return bool
     */
    protected function fieldHasTheSameTitle($from)
    {
        return is_int($from);
    }

    /**
     * @param $to
     * @param $from
     * @return array
     */
    protected function flipNames($to, $from)
    {
        if (is_array($to)) {
            if (isset($to['name'])) {
                $aux = $to['name'];
                $to = $from;
                $from = $aux;
            } else {
                $to['name'] = $from;
            }

            return [$to, $from];
        }

        if (!is_int($from)) {
            $aux = $to;
            $to = $from;
            $from = $aux;

            return [$to, $from];
        }

        return [$to, $from];
    }

    /**
     * @param $data
     * @param $from
     * @param $to
     * @return mixed
     * @throws \Exception
     */
    protected function typecast($data, $from, $to)
    {
        if (! isset($to['name'])) {
            $to['name'] = $from;
        }

        switch ($to['type']) {
            case 'date':
                if ($data[$from]) {
                    $date = new Carbon($data[$from]);
                    $this->convertedItem[$to['name']] = isset($to['format']) ? $date->format($to['format']) : $date;
                }
                break;
            case 'boolean':
                $this->convertedItem[$to['name']] = (bool) $data[$from];
                break;
            case 'integer':
                $this->convertedItem[$to['name']] = (int) $data[$from];
                break;
            case 'double':
            case 'float':
                $this->convertedItem[$to['name']] = (float) $data[$from];
                break;
            default:
                throw new \Exception('The type of field to convert is invalid');
                break;
        }
        return $this->convertedItem[$to['name']];
    }
}
