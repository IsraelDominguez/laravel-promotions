<?php namespace Genetsis\Promotions\Seeds;

class PromotionSeedsHelper
{
    /**
     * Read a csv file and return an array with the data
     *
     * @param $filename file with data
     * @param string $delimeter
     * @return array|bool
     */
    public static function csvToArray($filename, $delimiter = ',') {
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $data = array();
        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                $data[] = $row;
            }
            fclose($handle);
        }
        return $data;
    }
}