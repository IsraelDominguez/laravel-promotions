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
    public static function csvToArray($filename, $delimiter = ',', $with_headers = true) {
        if(!file_exists($filename) || !is_readable($filename))
            return FALSE;

        $data = array();

        if (($handle = fopen($filename, 'r')) !== FALSE)
        {
            if ($with_headers)
                $headers = array_map('trim', fgetcsv($handle, 1000, $delimiter));

            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if ($row != null)
                    $data[] = ($with_headers) ? self::ArrayCombine($headers, $row, '') : $row;
            }
            fclose($handle);
        }
        return $data;
    }

    public static function ArrayCombine($array1, $array2, $default = '')
    {
        if (count($array2)<count($array1)) {
            for ($i = 0; $i < count($array1); $i++) {
                $array2[$i] = $array2[$i] ?? $default;
            }
        }

        return array_combine($array1, $array2);
    }
}
