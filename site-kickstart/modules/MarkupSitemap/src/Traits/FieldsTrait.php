<?php

/**
 * Field helpers for ProcessWire
 * Allows field-building on the fly.
 * Add namespace and `use FieldsTrait` in your class.
 *
 * @author  Mike Rockett
 * @license ISC
 */

namespace Rockett\Traits;

trait FieldsTrait
{
    /**
     * Given a fieldtype, create, populate, and return an Inputfield
     * @param  string       $fieldNameId
     * @param  array        $meta
     * @return Inputfield
     */
    protected function buildInputField($fieldNameId, $meta)
    {
        $field = $this->modules->{"Inputfield{$fieldNameId}"};
        foreach ($meta as $metaNames => $metaInfo) {
            $metaNames = explode('+', $metaNames);
            foreach ($metaNames as $metaName) {
                $field->$metaName = $metaInfo;
            }
        }

        return $field;
    }
}
